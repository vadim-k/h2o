<?php

/**
 * @file
 * Contains \Drupal\mason\Plugin\Field\FieldFormatter\MasonImageFormatter.
 */

namespace Drupal\mason\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\mason\MasonFormatterInterface;
use Drupal\mason\MasonManagerInterface;
use Drupal\mason\MasonDefault;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatterBase;
use Drupal\mason\Entity\Mason;

/**
 * Plugin implementation of the 'mason image' formatter.
 *
 * @FieldFormatter(
 *   id = "mason_image",
 *   label = @Translation("Mason"),
 *   description = @Translation("Display the images as a perfect gapless grid of elements."),
 *   field_types = {"image"},
 * )
 */
class MasonImageFormatter extends ImageFormatterBase implements ContainerFactoryPluginInterface {
  use MasonFormatterTrait;

  /**
   * Constructs a MasonImageFormatter instance.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, MasonFormatterInterface $formatter, MasonManagerInterface $manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->manager = $manager;
    $this->formatter = $formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('mason.formatter'),
      $container->get('mason.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings = MasonDefault::imageSettings();
    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $files = $this->getEntitiesToView($items, $langcode);

    $elements = [];

    // Early opt-out if the field is empty.
    if (empty($files)) {
      return $elements;
    }

    // Collects specific settings to this formatter.
    $settings = $this->getSettings();
    $build = ['settings' => $settings];

    $this->formatter->buildSettings($build, $items);

    // Build the elements.
    $this->buildElements($build, $files);

    $elements = $this->manager()->build($build);

    return $elements;
  }

  /**
   * Build the mason elements.
   */
  public function buildElements(array &$build = [], $files) {
    $settings = &$build['settings'];
    $item_id  = $settings['item_id'];

    foreach ($files as $delta => $file) {
      /* @var Drupal\image\Plugin\Field\FieldType\ImageItem $item */
      $item = $file->_referringItem;

      $settings['delta']     = $delta;
      $settings['file_tags'] = $file->getCacheTags();
      $settings['type']      = 'image';
      $settings['uri']       = ($entity = $item->entity) && empty($item->uri) ? $entity->getFileUri() : $item->uri;

      $element = ['item' => $item, 'settings' => $settings];

      if (!empty($settings['caption'])) {
        foreach ($settings['caption'] as $caption) {
          $element['caption'][$caption] = empty($item->$caption) ? [] : ['#markup' => Xss::filterAdmin($item->$caption)];
        }
      }

      // Image with responsive image, lazyLoad, and lightbox supports.
      $element['delta'] = $delta;
      $element['image'] = [];
      $element[$item_id] = $this->formatter->getImage($element);
      $build['items'][$delta] = $element;
 
      unset($build['items'][$delta]['item']);

      if ($settings['nav']) {
        // Thumbnail usages: asNavFor pagers, dot, arrows, photobox thumbnails.
        $element[$item_id] = empty($settings['thumbnail_style']) ? [] : $this->formatter->getThumbnail($element['settings']);

        $caption = $settings['thumbnail_caption'];
        $element['caption'] = empty($item->$caption) ? [] : ['#markup' => Xss::filterAdmin($item->$caption)];

        $build['thumb']['items'][$delta] = $element;
      }
      unset($element);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element    = [];
    $definition = $this->getScopedFormElements();

    $definition['_views'] = isset($form['field_api_classes']);

    $this->admin()->buildSettingsForm($element, $definition);
    return $element;
  }

  /**
   * Defines the scope for the form elements.
   */
  public function getScopedFormElements() {
    $captions = ['title' => t('Title'), 'alt' => t('Alt')];
    return [
      'captions'          => $captions,
      'field_name'        => $this->fieldDefinition->getName(),
      'image_style_form'  => TRUE,
      'media_switch_form' => TRUE,
      'settings'          => $this->getSettings(),
      'thumb_captions'    => $captions,
      'nav'               => TRUE,
    ];
  }

}

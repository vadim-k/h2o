<?php

/**
 * @file
 * Contains \Drupal\intense\Plugin\Field\FieldFormatter\IntenseFormatter.
 */

namespace Drupal\intense\Plugin\Field\FieldFormatter;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatterBase;

/**
 * Plugin for the Intense image formatter.
 *
 * @FieldFormatter(
 *   id = "intense",
 *   label = @Translation("Intense images"),
 *   field_types = {"image"}
 * )
 */
class IntenseFormatter extends ImageFormatterBase implements ContainerFactoryPluginInterface {

  /**
   * The image style entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $imageStyleStorage;

  /**
   * Constructs an IntenseFormatter object.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings,  EntityStorageInterface $image_style_storage) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->imageStyleStorage = $image_style_storage;
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
      $container->get('entity_type.manager')->getStorage('image_style')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'image_style'   => '',
      'intense_style' => '',
      'intense_icon'  => FALSE,
      'caption'       => [],
    ];
  }

  /**
   * Returns the intense admin service.
   */
  public function intenseAdmin() {
    return \Drupal::service('intense.admin');
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $files = $this->getEntitiesToView($items, $langcode);

    // Early opt-out if the field is empty.
    if (empty($files)) {
      return $elements;
    }

    $settings                    = $this->getSettings();
    $settings['caption']         = array_filter($settings['caption']);
    $settings['intense_caption'] = !empty($settings['caption']);
    $image_style_setting         = $settings['image_style'];
    $intense_style_setting       = $settings['intense_style'];

    // Collect cache tags to be added for each item in the field.
    $cache_tags = [];
    if (!empty($image_style_setting)) {
      $image_style = $this->imageStyleStorage->load($image_style_setting);
      $cache_tags  = $image_style->getCacheTags();
    }

    foreach ($files as $delta => $file) {
      $cache_tags = Cache::mergeTags($cache_tags, $file->getCacheTags());

      // Extract field item attributes for the theme function, and unset them
      // from the $item so that the field template does not re-render them.
      $item = $file->_referringItem;
      $item_attributes = $item->_attributes;
      unset($item->_attributes);

      $settings['uri'] = $file->getFileUri();
      $url = $item->entity->url();
      if (!empty($intense_style_setting)) {
        $intense_style = $this->imageStyleStorage->load($intense_style_setting);
        $url = $intense_style->buildUrl($settings['uri']);
      }

      $settings['delta']       = $delta;
      $settings['intense_url'] = $url;

      $elements[$delta] = [
        '#theme'           => 'intense',
        '#item'            => $item,
        '#item_attributes' => $item_attributes,
        '#settings'        => $settings,
        '#cache'           => ['tags' => $cache_tags],
      ];
    }

    $elements['#attached']['library'][] = 'intense/intense';
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element      = [];
    $settings     = $this->getSettings();
    $image_styles = image_style_options(FALSE);

    $this->intenseAdmin()->buildSettingsForm($element, ['settings' => $settings]);

    $element['image_style'] = [
      '#title'         => t('Content image style'),
      '#type'          => 'select',
      '#default_value' => $settings['image_style'],
      '#empty_option'  => t('None (original image)'),
      '#options'       => $image_styles,
      '#description'   => t('The content image style.'),
      '#weight'        => -99,
    ];

    $element['caption'] = [
      '#title'         => t('Captions'),
      '#type'          => 'checkboxes',
      '#default_value' => $settings['caption'],
      '#options'       => ['title' => t('Title'), 'alt' => t('Alt')],
      '#description'   => t('The caption to display over the fullscreen intense image. Leave empty to not use captions.'),
      '#weight'        => -99,
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary      = [];
    $image_styles = image_style_options(FALSE);
    unset($image_styles['']);

    $image_setting   = $this->getSetting('image_style');
    $intense_setting = $this->getSetting('intense_style');
    $original        = t('None (original image)');
    $image_style     = isset($image_styles[$image_setting]) ? $image_styles[$image_setting] : $original;
    $intense_style   = isset($image_styles[$intense_setting]) ? $image_styles[$intense_setting] : $original;
    $caption         = array_filter($this->getSetting('caption'));

    $summary[] = t('Image style: @style', ['@style' => $image_style]);
    $summary[] = t('Intense image style: @style', ['@style' => $intense_style]);
    $summary[] = t('Caption: @caption', ['@caption' => $caption ? implode(', ', $caption) : t('None')]);

    return $summary;
  }

}

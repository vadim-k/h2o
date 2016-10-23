<?php

/**
 * @file
 * Contains \Drupal\gallery\Plugin\Field\FieldFormatter\GalleryImageFormatter.
 */

namespace Drupal\gallery\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatterBase;
use Drupal\gallery\GalleryManager;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'gallery' formatter.
 *
 * @FieldFormatter(
 *   id = "image_gallery",
 *   label = @Translation("Image Gallery"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class GalleryImageFormatter extends ImageFormatter implements ContainerFactoryPluginInterface {

  /**
   * The image style entity storage.
   *
   * @var \Drupal\image\ImageStyleStorageInterface
   */
  protected $galleryStyleStorage;

  /**
   * The gallery plugin manager.
   *
   * @var \Drupal\gallery\GalleryManager
   */
  protected $galleryManager;

  /**
   * Constructs a GalleryImageFormatter object.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param array $third_party_settings
   *   Any third party settings settings.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\gallery\GalleryManager $gallery_manager
   *   The gallery manager service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, AccountInterface $current_user, EntityStorageInterface $image_style_storage, EntityStorageInterface $gallery_style_storage, GalleryManager $gallery_manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings, $current_user, $image_style_storage);
    $this->galleryManager = $gallery_manager;
    $this->galleryStyleStorage = $gallery_style_storage;
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
      $container->get('current_user'),
      $container->get('entity.manager')->getStorage('image_style'),
      $container->get('entity.manager')->getStorage('gallery_style'),
      $container->get('plugin.manager.gallery')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'gallery_style' => '',
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);
    $gallery_styles = gallery_style_options(FALSE);
    $description_link = Link::fromTextAndUrl(
      $this->t('Configure Gallery Styles'),
      Url::fromRoute('entity.gallery_style.list')
    );
    $element['gallery_style'] = array(
      '#type' => 'select',
      '#title' => 'Gallery style',
      '#default_value' => $this->getSetting('gallery_style'),
      '#empty_option' => t('None'),
      '#options' => $gallery_styles,
      '#description' => $description_link->toRenderable() + array(
        '#access' => $this->currentUser->hasPermission('administer gallery styles'),
      ),
    );
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();

    $gallery_styles = gallery_style_options(FALSE);
    // Unset possible 'No defined styles' option.
    unset($gallery_styles['']);
    $gallery_style_setting = $this->getSetting('gallery_style');
    if (isset($gallery_styles[$gallery_style_setting])) {
      $summary[] = t('Gallery style: @style', array('@style' => $gallery_styles[$gallery_style_setting]));
    }
    else {
      $summary[] = t('Gallery style: none');
    }

    return $summary;
  }


  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();
    $items = parent::viewElements($items, $langcode);
    if ($items) {
      $gallery_style_setting = $this->getSetting('gallery_style');
      if (!empty($gallery_style_setting)) {
        $gallery_style = $this->galleryStyleStorage->load($gallery_style_setting);
        if ($gallery_style) {
          $this->galleryInstance = $this->galleryManager->createInstance($gallery_style->getStyle(), array('items' => $items));
          $elements = $this->galleryInstance->build();
        }
      }
    }
    return $elements;
  }

}

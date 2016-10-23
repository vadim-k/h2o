<?php

/**
 * @file
 * Contains \Drupal\gallery\Plugin\Field\FieldFormatter\GalleryEntityReferenceEntityFormatter.
 */

namespace Drupal\gallery\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceEntityFormatter;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\gallery\GalleryManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'gallery' formatter.
 *
 * @FieldFormatter(
 *   id = "gallery",
 *   label = @Translation("Rendered Entity Gallery"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class GalleryEntityReferenceEntityFormatter extends EntityReferenceEntityFormatter implements ContainerFactoryPluginInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The gallery plugin manager.
   *
   * @var \Drupal\gallery\GalleryManager
   */
  protected $galleryManager;

  /**
   * Constructs a GalleryEntityReferenceEntityFormatter object.
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
   * @param LoggerChannelFactoryInterface $logger_factory
   *   The logger factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The entity display repository.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Entity\EntityStorageInterface $gallery_style_storage
   *   The gallery style storage service.
   * @param \Drupal\gallery\GalleryManager $gallery_manager
   *   The gallery manager service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, LoggerChannelFactoryInterface $logger_factory, EntityTypeManagerInterface $entity_type_manager, EntityDisplayRepositoryInterface $entity_display_repository, AccountInterface $current_user, EntityStorageInterface $gallery_style_storage, GalleryManager $gallery_manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings, $logger_factory, $entity_type_manager, $entity_display_repository);
    $this->currentUser = $current_user;
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
      $container->get('logger.factory'),
      $container->get('entity_type.manager'),
      $container->get('entity_display.repository'),
      $container->get('current_user'),
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
          $style_type = $gallery_style->getType();
          $settings = $gallery_style->get('settings');
          $style_name = $gallery_style->get('name');
          $config = array(
            'items' => $items,
            'settings' => $settings,
            'style_name' => $style_name,
          );
          $this->galleryInstance = $this->galleryManager->createInstance($style_type, $config);
          $elements = $this->galleryInstance->build();
        }
      }
    }
    return $elements;
  }

}

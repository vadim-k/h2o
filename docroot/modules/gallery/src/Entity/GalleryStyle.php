<?php

namespace Drupal\gallery\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the gallery config entity.
 *
 * @ConfigEntityType(
 *   id = "gallery_style",
 *   label = @Translation("Gallery style"),
 *   admin_permission = "administer gallery styles",
 *   config_prefix = "style",
 *   handlers = {
 *     "list_builder" = "Drupal\gallery\Controller\GalleryStyleListBuilder",
 *     "form" = {
 *       "add" = "Drupal\gallery\Form\GalleryStyleAddForm",
 *       "edit" = "Drupal\gallery\Form\GalleryStyleEditForm",
 *       "delete" = "Drupal\gallery\Form\GalleryStyleDeleteForm"
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "name",
 *     "label" = "label"
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/media/gallery-styles/manage/{gallery_style}",
 *     "delete-form" = "/admin/config/media/gallery-styles/manage/{gallery_style}/delete"
 *   },
*    config_export = {
 *     "name",
 *     "label",
 *     "style",
 *     "settings",
 *   }
 * )
 */
class GalleryStyle extends ConfigEntityBase {

  /**
   * The name of the gallery style.
   *
   * @var string
   */
  public $name;

  /**
   * The gallery UUID.
   *
   * @var string
   */
  public $uuid;

  /**
   * The gallery label.
   *
   * @var string
   */
  public $label;

  /**
   * The gallery style.
   *
   * @var string
   */
  public $style;

  /**
   * The gallery settings.
   *
   * @var array
   */
  public $settings;

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name');
  }

  /**
   * Gets the gallery style settings value by key.
   *
   * @param string $key
   *   The key of the value to retrieve.
   *
   * @return mixed
   *   The value for the key, or NULL if the value does not exist.
   */
  public function getSettingsValue($key) {
    $settings = $this->get('settings');
    $value = isset($settings[$key]) ? $settings[$key] : NULL;
    return $value;
  }

}

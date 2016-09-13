<?php

namespace Drupal\gallery\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the gallery config entity.
 *
 * @ConfigEntityType(
 *   id = "gallery",
 *   label = @Translation("Gallery"),
 *   admin_permission = "administer gallery",
 *   handlers = {
 *     "list_builder" = "Drupal\gallery\Controller\GalleryListBuilder",
 *     "form" = {
 *       "add" = "Drupal\gallery\Form\GalleryAddForm",
 *       "edit" = "Drupal\gallery\Form\GalleryEditForm",
 *       "delete" = "Drupal\gallery\Form\GalleryDeleteForm"
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/media/gallery/{gallery}",
 *     "delete-form" = "/admin/config/media/gallery/{gallery}/delete"
 *   }
 * )
 */
class Gallery extends ConfigEntityBase {

  /**
   * The gallery ID.
   *
   * @var string
   */
  public $id;

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
   * The gallery type.
   *
   * @var string
   */
  public $type;

}

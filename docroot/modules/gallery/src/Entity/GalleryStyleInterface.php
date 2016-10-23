<?php

namespace Drupal\gallery\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Defines an interface for gallery plugins.
 */
interface GalleryStyleInterface extends ConfigEntityInterface {

  /**
   * Gets the gallery style settings value by key.
   *
   * @param string $key
   *   The key of the value to retrieve.
   *
   * @return mixed
   *   The value for the key, or NULL if the value does not exist.
   */
  public function getSettingsValue($key);

  /**
   * Gets the gallery type.
   *
   * @return string
   *   The gallery type.
   */
  public function getType();

}

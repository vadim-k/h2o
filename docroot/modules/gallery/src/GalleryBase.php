<?php

namespace Drupal\gallery;

use Drupal\Component\Plugin\PluginBase;

/**
 * Defines a base gallery implementation
 *
 */
class GalleryBase extends PluginBase implements GalleryInterface {

  /**
   * Returns gallery label
   *
   * @return string
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * Returns gallery description
   *
   * @return string
   */
  public function getDescription() {
    return $this->pluginDefinition['description'];
  }

}

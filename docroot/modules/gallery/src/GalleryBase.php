<?php

namespace Drupal\gallery;

use Drupal\Component\Plugin\PluginBase;

/**
 * Defines a base gallery implementation
 *
 */
class GalleryBase extends PluginBase implements GalleryInterface {

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->pluginDefinition['description'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array();
  }

}

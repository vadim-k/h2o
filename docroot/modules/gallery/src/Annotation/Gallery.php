<?php

/**
 * @file
 * Contains \Drupal\gallery\Annotation\Gallery.
 */

namespace Drupal\gallery\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Gallery item annotation object.
 *
 * @Annotation
 */
class Gallery extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the gallery plugin.
   *
   * @var string
   */
  public $label;

  /**
   * A brief description of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $description;

  /**
   * Plugin weight.
   *
   * @var int
   */
  public $weight;

}

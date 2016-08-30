<?php
/**
 * @file
 * Contains \Drupal\gallery\Plugin\Gallery\Chocolate.
 */
namespace Drupal\gallery_slick\Plugin\Gallery;

use Drupal\gallery\GalleryBase;

/**
 * Provides 'slick' plugin.
 *
 * @Gallery(
 *   id = "slick",
 *   label = "Slick Carousel",
 *   description = @Translation("Slick is a powerful and performant slideshow/carousel solution leveraging Ken Wheeler's Slick carousel.")
 * )
 */
class Slick extends GalleryBase {

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = array(
      '#markup' => 'test2',
    );
    return $build;
  }

}

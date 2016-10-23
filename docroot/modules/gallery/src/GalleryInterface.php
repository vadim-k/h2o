<?php

namespace Drupal\gallery;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for gallery plugins.
 */
interface GalleryInterface extends PluginInspectionInterface {

  /**
   * Return the label of the gallery plugin.
   *
   * @return string
   */
  public function getLabel();

  /**
   * Return the description of the gallery plugin.
   *
   * @return string
   */
  public function getDescription();

  /**
   * Return the renderable array of the gallery plugin.
   *
   * @return array
   */
  public function build();

  /**
   * Return the renderable array of the gallery items.
   *
   * @return array
   */
  public function getItems();

  /**
   * Set the renderable array of the gallery items.
   *
   * @param array $items
   */
  public function setItems($items);

  /**
   * Returns the gallery settings form.
   *
   * @return array
   *   The form array.
   */
  public function getSettingsForm();

}

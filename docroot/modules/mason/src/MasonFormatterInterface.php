<?php

namespace Drupal\mason;

/**
 * Defines re-usable services and functions for mason field plugins.
 */
interface MasonFormatterInterface {

  /**
   * Returns the mason field formatter and custom coded settings.
   *
   * @param array $build
   *   The array containing: settings, optionset.
   * @param array $items
   *   The items to prepare settings for.
   *
   * @return array
   *   The combined settings of a mason field formatter.
   */
  public function buildSettings(array &$build = [], $items);

}

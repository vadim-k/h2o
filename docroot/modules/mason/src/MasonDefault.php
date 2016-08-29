<?php

namespace Drupal\mason;

use Drupal\blazy\Dejavu\BlazyDefault;

/**
 * Defines shared plugin default settings for field formatter and Views style.
 *
 * @see FormatterBase::defaultSettings()
 * @see StylePluginBase::defineOptions()
 */
class MasonDefault extends BlazyDefault {
  /**
   * Returns image-related field formatter and Views settings.
   */
  public static function imageSettings() {
    return self::baseSettings() + parent::imageSettings();
  }

}

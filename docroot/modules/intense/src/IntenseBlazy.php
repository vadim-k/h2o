<?php

/**
 * @file
 * Contains \Drupal\intense\IntenseBlazy.
 */

namespace Drupal\intense;

use Drupal\blazy\Plugin\Field\FieldFormatter\BlazyFormatter;

/**
 * Modify plugin implementation of the 'blazy' formatter.
 *
 * @see intense_field_formatter_info_alter().
 */
class IntenseBlazy extends BlazyFormatter {
  use IntenseFormatterTrait;

}

<?php

/**
 * @file
 * Contains \Drupal\intense\IntenseSlickImage.
 */

namespace Drupal\intense;

use Drupal\slick\Plugin\Field\FieldFormatter\SlickImageFormatter;

/**
 * Modify plugin implementation of the 'slick_image' formatter.
 *
 * @see intense_field_formatter_info_alter().
 */
class IntenseSlickImage extends SlickImageFormatter {
  use IntenseFormatterTrait;

}

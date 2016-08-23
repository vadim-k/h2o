<?php

/**
 * @file
 * Contains \Drupal\intense\IntenseSlickMedia.
 */

namespace Drupal\intense;

use Drupal\slick_media\Plugin\Field\FieldFormatter\SlickMediaFormatter;

/**
 * Modify plugin implementation of the 'slick_media' formatter.
 *
 * @see intense_field_formatter_info_alter().
 */
class IntenseSlickMedia extends SlickMediaFormatter {
  use IntenseFormatterTrait;

}

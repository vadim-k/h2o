<?php

/**
 * @file
 * Contains \Drupal\mason\MasonFormatter.
 */

namespace Drupal\mason;

use Drupal\mason\Entity\Mason;
use Drupal\blazy\BlazyFormatterManager;

/**
 * Implements MasonFormatterInterface.
 */
class MasonFormatter extends BlazyFormatterManager implements MasonFormatterInterface {

  /**
   * {@inheritdoc}
   */
  public function buildSettings(array &$build = [], $items) {
    $settings = &$build['settings'];

    // Prepare integration with Blazy.
    $settings['item_id']          = 'box';
    $settings['namespace']        = 'mason';
    $settings['theme_hook_image'] = isset($settings['theme_hook_image']) ? $settings['theme_hook_image'] : 'mason_image';

    parent::buildSettings($build, $items);

    $optionset_name             = $settings['optionset'] ?: 'default';
    $build['optionset']         = Mason::load($optionset_name);
    $settings['nav']            = !empty($settings['optionset_thumbnail']) && isset($items[1]);
  }

  /**
   * Gets the thumbnail image.
   */
  public function getThumbnail($settings = []) {
    if (empty($settings['uri'])) {
      return [];
    }
    $thumbnail = [
      '#theme'      => 'image_style',
      '#style_name' => $settings['thumbnail_style'],
      '#uri'        => $settings['uri'],
    ];

    foreach (['height', 'width', 'alt', 'title'] as $data) {
      $thumbnail["#$data"] = isset($settings[$data]) ? $settings[$data] : NULL;
    }
    return $thumbnail;
  }

  /**
   * Overrides BlazyFormatterManager::getMediaSwitch().
   */
  public function getMediaSwitch(array &$element = [], $settings = []) {
    parent::getMediaSwitch($element, $settings);
    $switch = $settings['media_switch'];

    if (isset($element['#url_attributes'])) {
      $element['#url_attributes']['class'] = ['mason__' . $switch, 'litebox'];
    }
  }

}

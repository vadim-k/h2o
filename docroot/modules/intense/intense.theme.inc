<?php
/**
 * @file
 * Hook and preprocess functions for intense images.
 */

use Drupal\Component\Utility\Unicode;
use Drupal\intense\IntenseManager;

/**
 * Prepares variables for intense image formatter templates.
 *
 * Default template: intense.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - item: An ImageItem object.
 *   - item_attributes: An optional associative array of html attributes to be
 *     placed in the img tag.
 *   - settings: An array containing: image_style, intense_style, caption.
 */
function template_preprocess_intense(&$variables) {
  foreach (['item', 'item_attributes', 'settings'] as $key) {
    $variables[$key] = isset($variables['element']["#$key"]) ? $variables['element']["#$key"] : [];
  }

  $settings = $variables['settings'];
  if ($settings['image_style']) {
    $variables['image'] = array(
      '#theme' => 'image_style',
      '#style_name' => $settings['image_style'],
    );
  }
  else {
    $variables['image']['#theme'] = 'image';
  }

  $item = $variables['item'];

  // Do not output an empty 'title' attribute.
  if (Unicode::strlen($item->title) != 0) {
    $variables['image']['#title'] = $item->title;
  }

  $variables['image']['#attributes'] = $variables['item_attributes'];
  foreach (array('width', 'height', 'alt') as $key) {
    $variables['image']["#$key"] = $item->$key;
  }

  IntenseManager::buildAttributes($variables);

  $variables['image']['#uri'] = $variables['settings']['uri'];
}

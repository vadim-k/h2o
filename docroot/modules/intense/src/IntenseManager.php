<?php

/**
 * @file
 * Contains \Drupal\intense\IntenseManager.
 */

namespace Drupal\intense;

use Drupal\Core\Template\Attribute;
use Drupal\Component\Utility\NestedArray;
use Drupal\image\Entity\ImageStyle;

/**
 * Defines an intense manager.
 */
class IntenseManager {

  /**
   * Modifies attributes for both theme_intense() and any supported theme once.
   */
  public static function buildAttributes(array &$variables) {
    $item       = $variables['item'];
    $settings   = &$variables['settings'];
    $attributes = new Attribute();

    $intense_style_setting = $settings['intense_style'];

    $uri = ($entity = $item->entity) && empty($item->uri) ? $entity->getFileUri() : $item->uri;
    $url = isset($settings['intense_url']) ? $settings['intense_url'] : $item->entity->url();

    $settings['uri'] = isset($settings['uri']) ? $settings['uri'] : $uri;
    if (!empty($intense_style_setting) && !isset($settings['intense_url'])) {
      $style = ImageStyle::load($intense_style_setting);
      $url = $style->buildUrl($settings['uri']);
    }

    $settings['intense_url'] = $url;

    $attributes->addClass('intense');
    $attributes->setAttribute('data-image', $settings['intense_url']);
    if (!empty($settings['caption']) && !empty($settings['intense_caption'])) {
      foreach (array_filter($settings['caption']) as $caption) {
        if (!empty($item->$caption)) {
          $data = $caption == 'alt' ? 'caption' : 'title';
          $attributes->setAttribute('data-' . $data, $item->$caption);
        }
      }
    }

    $settings['icon'] = [];
    if ($settings['intense_icon']) {
      $attributes->addClass('intense--icon');
      $settings['icon']['#markup'] = '<span' . $attributes . '></span>';
    }
    else {
      $variables['image']['#attributes'] = NestedArray::mergeDeep($variables['image']['#attributes'], $attributes->toArray());
    }
  }

  /**
   * Overrides variables for the external supported templates.
   */
  public static function addAttributes(array &$variables) {
    $switch = isset($variables['settings']['media_switch']) ? $variables['settings']['media_switch'] : '';
    if ($switch != 'intense') {
      return;
    }
    self::buildAttributes($variables);
  }

}

<?php

/**
 * @file
 * Contains \Drupal\mason\MasonSkin.
 */

namespace Drupal\mason;

/**
 * Implements MasonSkinInterface.
 */
class MasonSkin implements MasonSkinInterface {

  /**
   * {@inheritdoc}
   */
  public function skins() {
    $skins = [
      'default' => [
        'name' => t('Default'),
        'provider' => 'mason',
        'css' => [
          'theme' => [
            'css/theme/mason.theme--default.css' => [],
          ],
        ],
      ],
      'selena' => [
        'name' => t('Selena'),
        'provider' => 'selena',
        'css' => [
          'theme' => [
            'css/theme/mason.theme--selena.css' => [],
          ],
        ],
        'description' => t('Provide Selena skin.'),
      ],
    ];

    return $skins;
  }

}

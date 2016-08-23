<?php

/**
 * @file
 * Contains \Drupal\intense\IntenseAdmin.
 */

namespace Drupal\intense;

/**
 * Provides re-usable admin functions, or form elements.
 */
class IntenseAdmin implements IntenseAdminInterface {

  /**
   * Defines re-usable form elements.
   */
  public function buildSettingsForm(array &$element, $definition = []) {
    $settings     = $definition['settings'];
    $image_styles = image_style_options(FALSE);
    $states       = [];

    if (isset($element['media_switch'])) {
      $states = ['visible' => [
        'select[name*="[media_switch]"]' => ['value' => 'intense'],
      ]];
      $element['media_switch']['#options']['intense'] = t('Image to Intense');
    }

    $element['intense_header'] = [
      '#type'   => 'item',
      '#markup' => '<h3 class="form__title">Intense image integration</h3>',
      '#states' => $states,
      '#weight' => -90,
      '#access' => isset($settings['optionset']),
    ];

    $element['intense_style'] = [
      '#title'         => t('Intense image style'),
      '#type'          => 'select',
      '#default_value' => $settings['intense_style'],
      '#options'       => $image_styles,
      '#empty_option'  => t('None (original image)'),
      '#description'   => t('The fullscreen image style. Be sure the image is large enough for a fullscreen. Leave empty to use original image.'),
      '#states'        => $states,
      '#weight'        => -90,
    ];

    $element['intense_icon'] = [
      '#title'         => t('Use Intense icon'),
      '#type'          => 'checkbox',
      '#default_value' => $settings['intense_icon'],
      '#description'   => t('If checked, the provided Intense icon will act as the trigger. Otherwise the image itself. Useful to avoid issue with draggable slider, etc.'),
      '#states'        => $states,
      '#weight'        => -90,
    ];

    $element['intense_caption'] = [
      '#title'         => t('Use Intense caption'),
      '#type'          => 'checkbox',
      '#default_value' => isset($settings['intense_caption']) ? $settings['intense_caption'] : FALSE,
      '#description'   => t('If checked, the selected captions will also be displayed as part of Intense fullscreen images.'),
      '#states'        => $states,
      '#weight'        => -90,
      '#access'        => isset($settings['optionset']),
    ];
  }

}

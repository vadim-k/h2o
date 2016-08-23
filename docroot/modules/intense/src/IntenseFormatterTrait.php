<?php

/**
 * @file
 * Contains \Drupal\intense\IntenseFormatterTrait.
 */

namespace Drupal\intense;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * A Trait for intense slick formatters.
 */
trait IntenseFormatterTrait {

  /**
   * Returns the intense admin service.
   */
  public function intenseAdmin() {
    return \Drupal::service('intense.admin');
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'intense_style'   => '',
      'intense_caption' => FALSE,
      'intense_icon'    => FALSE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    if ($this->getSetting('media_switch') == 'intense') {
      $plugin = get_class($this);
      if ($plugin == 'Drupal\intense\IntenseSlickMedia') {
        $elements['#build']['settings']['media'] = 'media';
      }
      if (isset($elements['#build']['attached'])) {
        $elements['#build']['attached']['library'][] = 'intense/intense';
      }
      else {
        $elements['#attached']['library'][] = 'intense/intense';
      }
    }
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element    = parent::settingsForm($form, $form_state);
    $definition = [];

    if (method_exists($this, 'getScopedFormElements')) {
      $definition = $this->getScopedFormElements();
    }

    $definition['settings'] = isset($definition['settings']) ? array_merge($definition['settings'], $this->getSettings()) : $this->getSettings();

    $this->intenseAdmin()->buildSettingsForm($element, $definition);
    if (method_exists($this, 'admin')) {
      $this->admin()->finalizeForm($element, $definition);
    }

    return $element;
  }

}

<?php

namespace Drupal\mason\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * A Trait common for mason formatters.
 */
trait MasonFormatterTrait {

  /**
   * The mason field formatter manager.
   *
   * @var \Drupal\mason\MasonFormatterInterface.
   */
  protected $formatter;

  /**
   * The mason field formatter manager.
   *
   * @var \Drupal\mason\MasonManagerInterface.
   */
  protected $manager;

  /**
   * Returns the mason field formatter service.
   */
  public function formatter() {
    return $this->formatter;
  }

  /**
   * Returns the mason service.
   */
  public function manager() {
    return $this->manager;
  }

  /**
   * Returns the mason admin service shortcut.
   */
  public function admin() {
    return \Drupal::service('mason.admin');
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    return $this->admin()->settingsSummary($this);
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    return $field_definition->getFieldStorageDefinition()->isMultiple();
  }

}

<?php

namespace Drupal\gallery;

use Drupal\Core\Entity\EntityViewBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Field\PluginSettingsBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a base gallery implementation
 *
 */
class GalleryBase extends PluginSettingsBase implements GalleryInterface, ContainerFactoryPluginInterface {
  use LinkGeneratorTrait;

  /**
   * The entity storage.
   *
   * @var \Drupal\Core\Entity\EntityManager
   */
  protected $entityManager;

  /**
   * The gallery items.
   *
   * @var array
   */
  protected $items;

  /**
   * GalleryBase constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    if (!empty($this->configuration['items'])) {
      $this->setItems($this->configuration['items']);
    }
    if (!empty($configuration['settings'])) {
      $this->setSettings($configuration['settings']);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->pluginDefinition['description'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function getItems() {
    return $this->items;
  }

  /**
   * {@inheritdoc}
   */
  public function setItems($items = array()) {
    $this->items = $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettingsForm() {
    return array();
  }

}

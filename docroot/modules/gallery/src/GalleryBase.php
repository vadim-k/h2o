<?php

namespace Drupal\gallery;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityViewBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Component\Plugin\PluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a base gallery implementation
 *
 */
class GalleryBase extends PluginBase implements GalleryInterface, ContainerFactoryPluginInterface {

  /**
   * The entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')
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
    $items = array();
    $entity_type = 'file';
    $view_mode = 'default';
    $entity_storage = $this->entityManager->getStorage($entity_type);
    $view_builder = $this->entityManager->getViewBuilder($entity_type);
    
    $entities = $entity_storage->loadMultiple();
    foreach ($entities as $entity) {
      $items[] = $view_builder->view($entity, $view_mode);
    }
    return $items;
  }

}

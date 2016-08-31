<?php

namespace Drupal\gallery;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityViewBuilderInterface;
use Drupal\Core\Entity\Query\QueryFactory;
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
   * @var \Drupal\Core\Entity\EntityManager
   */
  protected $entityManager;

  /**
   * The entity query.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;

  /**
   * The entity type.
   *
   * @var string
   */
  protected $entityType;

  /**
   * The entity view mode.
   *
   * @var string
   */
  protected $entityViewMode;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManagerInterface $entity_manager, QueryFactory $entity_query) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
    $this->entityQuery = $entity_query;
    $this->entityType = 'file';
    $this->entityViewMode = 'default';
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager'),
      $container->get('entity.query')
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
    $entity_storage = $this->entityManager->getStorage($this->entityType);
    $view_builder = $this->entityManager->getViewBuilder($this->entityType);
    $entity_query = $this->getQuery();
    $ids = $entity_query->execute();
    $entities = $entity_storage->loadMultiple($ids);
    foreach ($entities as $entity) {
      $items[] = $view_builder->view($entity, $this->entityViewMode);
    }
    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getQuery() {
    $entity_query = $this->entityQuery->get($this->entityType);
    $entity_query->condition('status', 1);
    $entity_query->sort('created', 'DESC');
    $entity_query->sort('uuid', 10000, '>');
    $entity_query->range(0, 20);
    if ($this->entityType == 'file') {
      $entity_query->condition('filesize', 10000, '>');
    }
    return $entity_query;
  }
}

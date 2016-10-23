<?php

namespace Drupal\gallery\Plugin\Block;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\gallery\GalleryManager;

/**
 * Provides a 'GalleryBlock' block plugin.
 *
 * @Block(
 *   id = "gallery_block",
 *   admin_label = @Translation("gallery block"),
 *   deriver = "Drupal\gallery\Plugin\Derivative\GalleryBlock"
 * )
 */
class GalleryBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The gallery plugin manager.
   *
   * @var \Drupal\gallery\GalleryManager
   */
  protected $galleryManager;


  /**
   * The gallery plugin definition.
   *
   * @var mixed
   */
  protected $galleryDefinition;

  /**
   * The gallery plugin instance.
   *
   * @var object
   *   A fully configured plugin instance.
   */
  protected $galleryInstance;

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
   * Creates a GalleryBlock instance.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param array $plugin_definition
   * @param \Drupal\gallery\GalleryManager $gallery_manager
   *   The gallery manager service.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, GalleryManager $gallery_manager, EntityManagerInterface $entity_manager, QueryFactory $entity_query) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->galleryManager = $gallery_manager;
    $this->entityManager = $entity_manager;
    $this->entityQuery = $entity_query;
    $this->setEntityType();
    $this->setEntityViewMode();
    $gallery_id = $this->getDerivativeId();
    $this->galleryDefinition = $this->galleryManager->getDefinition($gallery_id);
    $this->galleryInstance = $this->galleryManager->createInstance($gallery_id, array('items' => $this->getItems()));

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.gallery'),
      $container->get('entity.manager'),
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'entity_type' => 'file',
      'entity_view_mode' => 'default',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    foreach ($this->entityManager->getDefinitions() as $key => $entity_definition) {
      if ($entity_definition->getHandlerClass('view_builder')) {
        $options[$key] = $entity_definition->getLabel();
      }
    }
    $form['entity_type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Entity Type'),
      '#description' => $this->t('Entity type.'),
      '#options' => $options,
      '#default_value' => $this->configuration['entity_type'],
    );
    $form['entity_view_mode'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('View Mode'),
      '#description' => $this->t('View mode.'),
      '#default_value' => $this->configuration['entity_view_mode'],
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['entity_type'] = $form_state->getValue('entity_type');
    $this->configuration['entity_view_mode'] = $form_state->getValue('entity_view_mode');
  }
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = $this->galleryInstance->build();
    return $build;
  }

  /**
   * Sets the entity type.
   *
   * @param string $entity_type
   */
  public function setEntityType($entity_type = NULL) {
    if (!$entity_type) {
      $entity_type = !empty($this->configuration['entity_type']) ? $this->configuration['entity_type'] : 'file';
    }
    $this->entityType = $entity_type;
  }

  /**
   * Sets the entity view mode.
   *
   * @param string $entity_view_mode
   */
  public function setEntityViewMode($entity_view_mode = NULL) {
    if (!$entity_view_mode) {
      $entity_view_mode = !empty($this->configuration['entity_view_mode']) ? $this->configuration['entity_view_mode'] : 'default';
    }
    $this->entityViewMode = $entity_view_mode;
  }

  /**
   * Return the renderable array of the gallery items.
   *
   * @return array
   */
  public function getItems() {
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
   * Return the items entity query
   *
   * @return \Drupal\Core\Entity\Query\QueryFactory
   */
  public function getQuery() {
    $entity_query = $this->entityQuery->get($this->entityType);
    $entity_query->sort('uuid', 10000, '>');
    $entity_query->range(0, 20);
    if (in_array($this->entityType, array('file', 'node', 'user', 'comment', 'block'))) {
      $entity_query->condition('status', 1);
    }
    if (in_array($this->entityType, array('file', 'node', 'user', 'comment', 'block'))) {
      $entity_query->sort('created', 'DESC');
    }
    if (in_array($this->entityType, array('file'))) {
      $entity_query->condition('filesize', 10000, '>');
    }
    return $entity_query;
  }

}

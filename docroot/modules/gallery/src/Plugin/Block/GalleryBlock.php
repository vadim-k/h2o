<?php

namespace Drupal\gallery\Plugin\Block;

use Drupal\Core\Entity\EntityManagerInterface;
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
  public function __construct(array $configuration, $plugin_id, $plugin_definition, GalleryManager $gallery_manager, EntityManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->galleryManager = $gallery_manager;
    $this->entityManager = $entity_manager;
    $gallery_id = $this->getDerivativeId();
    $this->galleryDefinition = $this->galleryManager->getDefinition($gallery_id);
    $this->galleryInstance = $this->galleryManager->createInstance($gallery_id, $configuration);
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
      $container->get('entity.manager')
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
}

<?php

namespace Drupal\gallery\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
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
   * Creates a GalleryBlock instance.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param array $plugin_definition
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, GalleryManager $gallery_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->galleryManager = $gallery_manager;
    $gallery_id = $this->getDerivativeId();
    $this->galleryDefinition = $this->galleryManager->getDefinition($gallery_id);
    $this->galleryInstance = $this->galleryManager->createInstance($gallery_id);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.gallery')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array();
  }
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = array();
    $build = $this->galleryInstance->build();
    return $build;
  }
}

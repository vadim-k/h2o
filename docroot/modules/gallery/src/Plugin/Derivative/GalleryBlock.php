<?php

namespace Drupal\gallery\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\gallery\GalleryManager;

/**
 * Provides block plugin definitions for gallery plugins.
 */
class GalleryBlock extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The gallery plugin manager.
   *
   * @var \Drupal\gallery\GalleryManager
   */
  protected $galleryManager;

  /**
   * Constructs new GalleryBlock.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $galleryManager
   *   The file storage.
   */
  public function __construct(GalleryManager $gallery_manager) {
    $this->galleryManager = $gallery_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('plugin.manager.gallery')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $gallery_plugin_definitions = $this->galleryManager->getDefinitions();
    foreach ($gallery_plugin_definitions as $gallery_plugin_definition) {
      $id = $gallery_plugin_definition['id'];
      $label = $gallery_plugin_definition['label'];
      $this->derivatives[$id] = $base_plugin_definition;
      $this->derivatives[$id]['admin_label'] = t('Gallery block: ') . $label;
    }
    return $this->derivatives;
  }
}

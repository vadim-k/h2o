<?php

namespace Drupal\gallery\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Controller\ControllerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\gallery\GalleryManager;

/**
 * Controller routines for page example routes.
 */
class GalleryController extends ControllerBase {

  /**
   * The gallery plugin manager.
   *
   * @var \Drupal\gallery\GalleryManager
   */
  protected $galleryManager;

  /**
   * Constructor.
   *
   * @param \Drupal\gallery\GalleryManager $galleryManager
   */
  public function __construct(GalleryManager $gallery_manager) {
    $this->galleryManager = $gallery_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('plugin.manager.gallery'));
  }

  public function demoPage() {
    $gallery_plugin_definitions = $this->galleryManager->getDefinitions();
    $items = array();
    foreach ($gallery_plugin_definitions as $gallery_plugin_definition) {
      $items[] = array(
        '#type' => 'details',
        '#title'       => $gallery_plugin_definition['label'],
        '#open'        => TRUE,
        '#description' => $gallery_plugin_definition['description'],
      );
    }

    // Add our list to the render array.
    $output['plugin_definitions'] = array(
      '#theme' => 'item_list',
      '#title' => 'Gallery plugin definitions',
      '#items' => $items,
    );
    return $output;
  }

}

<?php

namespace Drupal\gallery\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Controller\ControllerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\gallery\GalleryPluginManager;

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
   * @param \Drupal\gallery\GalleryPluginManager $sandwich_manager
   *   The sandwich plugin manager service. We're injecting this service so that
   *   we can use it to access the sandwich plugins.
   */
  public function __construct(GalleryPluginManager $galleryManager) {
    $this->galleryManager = $galleryManager;
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

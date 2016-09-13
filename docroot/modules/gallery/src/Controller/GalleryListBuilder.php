<?php

namespace Drupal\gallery\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of gallery config entities.
 */
class GalleryListBuilder extends ConfigEntityListBuilder {

  /**
   * Builds the header row for the entity listing.
   *
   * @return array
   *   A render array structure of header strings.
   *
   * @see Drupal\Core\Entity\EntityListController::render()
   */
  public function buildHeader() {
    $header['label'] = $this->t('Gallery');
    $header['machine_name'] = $this->t('Machine Name');
    $header['type'] = $this->t('Type');
    return $header + parent::buildHeader();
  }

  /**
   * Builds a row for an entity in the entity listing.
   *
   * @param EntityInterface $entity
   *   The entity for which to build the row.
   *
   * @return array
   *   A render array of the table row for displaying the entity.
   *
   * @see Drupal\Core\Entity\EntityListController::render()
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['machine_name'] = $entity->id();
    $row['type'] = $entity->get('type');

    return $row + parent::buildRow($entity);
  }

}

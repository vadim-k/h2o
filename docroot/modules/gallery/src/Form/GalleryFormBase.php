<?php

namespace Drupal\gallery\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\gallery\GalleryManager;

class galleryFormBase extends EntityForm {

  /**
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQueryFactory;

  /**
   * The gallery plugin manager.
   *
   * @var \Drupal\gallery\GalleryManager
   */
  protected $galleryManager;

  /**
   * Construct the galleryFormBase.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $query_factory
   *   An entity query factory for the gallery entity type.
   */
  public function __construct(QueryFactory $query_factory, GalleryManager $gallery_manager) {
    $this->entityQueryFactory = $query_factory;
    $this->galleryManager = $gallery_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query'),
      $container->get('plugin.manager.gallery')
    );
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::form().
   *
   * Builds the galleryentity add/edit form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   *
   * @return array
   *   An associative array containing the gallery add/edit form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $gallery = $this->entity;
    $gallery_plugin_definitions = $this->galleryManager->getDefinitions();
    $gallery_type_options = array();
    foreach ($gallery_plugin_definitions as $key => $gallery_definition) {
      $gallery_type_options[$key] = $gallery_definition['label'];
    }
    $type = $gallery->get('type');

    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $gallery->label(),
      '#required' => TRUE,
    );
    $form['id'] = array(
      '#type' => 'machine_name',
      '#title' => $this->t('Machine name'),
      '#default_value' => $gallery->id(),
      '#machine_name' => array(
        'exists' => array($this, 'exists'),
        'replace_pattern' => '([^a-z0-9_]+)',
        'error' => 'The machine-readable name must be unique, and can only contain lowercase letters, numbers, and underscores.',
      ),
      '#disabled' => !$gallery->isNew(),
    );

    $form['type'] = array(
      '#type' => 'select',
      '#options' => $gallery_type_options,
      '#title' => $this->t('Gallery Type'),
      '#default_value' => $type,
      '#required' => TRUE,
      '#ajax' => array(
        'callback' => '::typeCallback',
        'wrapper' => 'gallery-config-wrapper',
      ),
    );

    $form['gallery_config_wrapper'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'id' => 'gallery-config-wrapper',
      ),
    );

    if ($type) {
      $this->setGalleryConfigForm($form['gallery_config_wrapper'], $type);
    }

    return $form;
  }

  /**
   * Implements callback for Ajax event on type selection.
   *
   * @param array $form
   *   From render array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current state of form.
   *
   * @return array
   *   An associative array containing the gallery config form.
   */
  public function typeCallback(array &$form, FormStateInterface $form_state) {
    $type = $form_state->getValue('type');

    $this->setGalleryConfigForm($form['gallery_config_wrapper'], $type);

    return $form['gallery_config_wrapper'];
  }

  /**
   * Implements callback for Ajax event on type selection.
   *
   * @param string $type
   *   Gallery plugin id.
   */
  protected function setGalleryConfigForm(array &$form, $type) {
    $form['type'] = array(
      '#markup' => $type,
    );
  }

  /**
   * Checks for an existing gallery.
   *
   * @param string|int $entity_id
   *   The entity ID.
   * @param array $element
   *   The form element.
   * @param FormStateInterface $form_state
   *   The form state.
   *
   * @return bool
   *   TRUE if this format already exists, FALSE otherwise.
   */
  public function exists($entity_id, array $element, FormStateInterface $form_state) {
    // Use the query factory to build a new gallery entity query.
    $query = $this->entityQueryFactory->get('gallery');

    // Query the entity ID to see if its in use.
    $result = $query->condition('id', $element['#field_prefix'] . $entity_id)
      ->execute();

    // We don't need to return the ID, only if it exists or not.
    return (bool) $result;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::actions().
   *
   * To set the submit button text, we need to override actions().
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   *
   * @return array
   *   An array of supported actions for the current entity form.
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    // Get the basic actins from the base class.
    $actions = parent::actions($form, $form_state);

    // Change the submit button text.
    $actions['submit']['#value'] = $this->t('Save');

    // Return the result.
    return $actions;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::validate().
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   */
  public function validate(array $form, FormStateInterface $form_state) {
    parent::validate($form, $form_state);

    // Add code here to validate your config entity's form elements.
    // Nothing to do here.
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   *
   * Saves the entity. This is called after submit() has built the entity from
   * the form values. Do not override submit() as save() is the preferred
   * method for entity form controllers.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   */
  public function save(array $form, FormStateInterface $form_state) {
    // EntityForm provides us with the entity we're working on.
    $gallery = $this->getEntity();

    // Drupal already populated the form values in the entity object. Each
    // form field was saved as a public variable in the entity class. PHP
    // allows Drupal to do this even if the method is not defined ahead of
    // time.
    $status = $gallery->save();

    // Grab the URL of the new entity. We'll use it in the message.
    $url = $gallery->urlInfo();

    // Create an edit link.
    $edit_link = Link::fromTextAndUrl($this->t('Edit'), $url)->toString();

    if ($status == SAVED_UPDATED) {
      // If we edited an existing entity...
      drupal_set_message($this->t('gallery %label has been updated.', array('%label' => $gallery->label())));
      $this->logger('contact')->notice('gallery %label has been updated.', ['%label' => $gallery->label(), 'link' => $edit_link]);
    }
    else {
      // If we created a new entity...
      drupal_set_message($this->t('gallery %label has been added.', array('%label' => $gallery->label())));
      $this->logger('contact')->notice('gallery %label has been added.', ['%label' => $gallery->label(), 'link' => $edit_link]);
    }

    // Redirect the user back to the listing route after the save operation.
    $form_state->setRedirect('entity.gallery.list');
  }

}

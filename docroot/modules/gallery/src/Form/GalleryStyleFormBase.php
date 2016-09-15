<?php

namespace Drupal\gallery\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\gallery\GalleryManager;

class GalleryStyleFormBase extends EntityForm {

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
   *   An entity query factory.
   * @param \Drupal\gallery\GalleryManager $galleryManager
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
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $gallery_plugin_definitions = $this->galleryManager->getDefinitions();
    $gallery_style_options = array();
    foreach ($gallery_plugin_definitions as $key => $gallery_definition) {
      $gallery_style_options[$key] = $gallery_definition['label'];
    }

    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#required' => TRUE,
    );
    $form['name'] = array(
      '#type' => 'machine_name',
      '#title' => $this->t('Machine name'),
      '#default_value' => $this->entity->id(),
      '#machine_name' => array(
        'exists' => array($this, 'exists'),
        'replace_pattern' => '([^a-z0-9_]+)',
        'error' => 'The machine-readable name must be unique, and can only contain lowercase letters, numbers, and underscores.',
      ),
      '#disabled' => !$this->entity->isNew(),
    );

    $style = $this->entity->get('style');
    $form['style'] = array(
      '#type' => 'select',
      '#options' => $gallery_style_options,
      '#title' => $this->t('Style'),
      '#default_value' => $style,
      '#required' => TRUE,
      '#ajax' => array(
        'callback' => '::settingsFormCallback',
        'wrapper' => 'gallery-config-wrapper',
      ),
    );

    $form['settings'] = array(
      '#type' => 'container',
      '#tree' => TRUE,
      '#attributes' => array(
        'id' => 'gallery-config-wrapper',
      ),
    );

    if ($style) {
      $this->setGallerySettingsForm($form['settings'], $style);
    }

    return $form;
  }

  /**
   * Implements callback for Ajax event on style selection.
   *
   * @param array $form
   *   From render array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current state of form.
   *
   * @return array
   *   An associative array containing the gallery config form.
   */
  public function settingsFormCallback(array &$form, FormStateInterface $form_state) {
    $style = $form_state->getValue('style');

    $this->setGallerySettingsForm($form['settings'], $style);

    return $form['settings'];
  }

  /**
   * {@inheritdoc}
   */
  protected function setGallerySettingsForm(array &$form, $style) {
    $form['accessibility'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Accessibility'),
      '#description' => $this->t('Enables tabbing and arrow key navigation.'),
      '#default_value' => $this->entity->getSettingsValue('accessibility'),
    );
    $form['mobile_first'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Mobile first'),
      '#description' => $this->t('Responsive settings use mobile first calculation.'),
      '#default_value' => $this->entity->getSettingsValue('mobile_first'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function exists($entity_id, array $element, FormStateInterface $form_state) {
    // Use the query factory to build a new gallery entity query.
    $query = $this->entityQueryFactory->get('gallery_style');

    // Query the entity ID to see if its in use.
    $result = $query->condition('id', $element['#field_prefix'] . $entity_id)
      ->execute();

    // We don't need to return the ID, only if it exists or not.
    return (bool) $result;
  }

  /**
   * {@inheritdoc}
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
   * {@inheritdoc}
   */
  public function validate(array $form, FormStateInterface $form_state) {
    parent::validate($form, $form_state);
  }

  /**
   * {@inheritdoc}
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
    $form_state->setRedirect('entity.gallery_style.list');
  }

}

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

    $style_options = $this->galleryManager->getStyles();
    $style = $this->entity->get('style') ? $this->entity->get('style') : key($style_options);
    $form['style'] = array(
      '#type' => 'select',
      '#options' => $style_options,
      '#title' => $this->t('Style'),
      '#default_value' => $style,
      '#required' => TRUE,
      '#ajax' => array(
        'callback' => '::settingsFormCallback',
        'wrapper' => 'gallery-settings-wrapper',
      ),
    );

    $form['settings'] = array(
      '#type' => 'fieldset',
      '#tree' => TRUE,
      '#prefix' => '<div id="gallery-settings-wrapper">',
      '#suffix' => '</div>'
    );

    $triggering_element = isset($form_state->getTriggeringElement()['#name']) ? $form_state->getTriggeringElement()['#name'] : NULL;
    if ($triggering_element == 'style') {
      $style = $form_state->getValue('style');
    }

    if ($style) {
      $settings = $this->entity->get('settings') ? $this->entity->get('settings') : array();
      $gallery_plugin_instance = $this->galleryManager->createInstance($style, $settings);
      if ($gallery_plugin_instance) {
        $form['settings'] += $gallery_plugin_instance->getSettingsForm();
      }
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
    return $form['settings'];
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
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->entity->settings = array();
    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $gallery = $this->getEntity();
    $status = $gallery->save();
    $url = $gallery->urlInfo();
    $edit_link = Link::fromTextAndUrl($this->t('Edit'), $url)->toString();

    if ($status == SAVED_UPDATED) {
      drupal_set_message($this->t('gallery %label has been updated.', array('%label' => $gallery->label())));
      $this->logger('contact')->notice('gallery %label has been updated.', ['%label' => $gallery->label(), 'link' => $edit_link]);
    }
    else {
      drupal_set_message($this->t('gallery %label has been added.', array('%label' => $gallery->label())));
      $this->logger('contact')->notice('gallery %label has been added.', ['%label' => $gallery->label(), 'link' => $edit_link]);
    }

    $form_state->setRedirect('entity.gallery_style.list');
  }

}

<?php

/**
 * @file
 * Contains \Drupal\mason\Form\MasonAdmin.
 */

namespace Drupal\mason\Form;

use Drupal\Core\Url;
use Drupal\Component\Utility\Html;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\blazy\Form\BlazyAdminInterface;
use Drupal\mason\MasonManagerInterface;

/**
 * Provides resusable admin functions or form elements.
 */
class MasonAdmin implements MasonAdminInterface {

  /**
   * The blazy admin service.
   *
   * @var \Drupal\blazy\Form\BlazyAdminInterface.
   */
  protected $blazyAdmin;

  /**
   * The mason manager service.
   *
   * @var \Drupal\mason\MasonManagerInterface.
   */
  protected $manager;

  /**
   * Constructs a GridStackAdmin object.
   */
  public function __construct(BlazyAdminInterface $blazy_admin, MasonManagerInterface $manager) {
    $this->blazyAdmin = $blazy_admin;
    $this->manager = $manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('blazy.admin.extended'), $container->get('mason.manager'));
  }

  /**
   * Returns the blazy admin.
   */
  public function blazyAdmin() {
    return $this->blazyAdmin;
  }

  /**
   * Returns the mason manager.
   */
  public function manager() {
    return $this->manager;
  }

  /**
   * Returns all settings form elements.
   */
  public function buildSettingsForm(array &$form, $definition = []) {
    $definition += [
      'namespace'  => 'mason',
      'optionsets' => $this->blazyAdmin->getOptionsetOptions('mason'),
      'skins'      => $this->getSkinOptions(),
    ];

    $definition['layouts'] = isset($definition['layouts']) ? array_merge($this->getLayoutOptions(), $definition['layouts']) : $this->getLayoutOptions();

    $this->openingForm($form, $definition);

    if (isset($definition['image_style_form']) && !isset($form['image_style'])) {
      $this->imageStyleForm($form, $definition);
    }

    if (isset($definition['breakpoints'])) {
      $this->blazyAdmin->breakpointsForm($form, $definition);
    }

    $this->mainForm($form, $definition);
    $this->closingForm($form, $definition);
  }

  /**
   * Returns the opening form elements.
   */
  public function openingForm(array &$form, $definition = []) {
    $path   = drupal_get_path('module', 'mason');
    $readme = Url::fromUri('base:' . $path . '/README.txt')->toString();

    if (!isset($form['optionset'])) {
      $this->blazyAdmin->openingForm($form, $definition);
    }

    $form['skin']['#description'] = t('Skins allow various layouts with just CSS. Some options below depend on a skin. Leave empty to DIY. Or use hook_mason_skins_info() and implement \Drupal\mason\MasonSkinInterface to register ones.', [':url' => $readme]);
    $form['background']['#description'] = t('If trouble with image sizes not filling the given box, check this to turn the image into CSS background instead. To assign different image style per grid/box, edit the working optionset.');
  }

  /**
   * Returns the image formatter form elements.
   */
  public function imageStyleForm(array &$form, $definition = []) {
    $definition['ratios'] = TRUE;

    if (!isset($form['image_style'])) {
      $this->blazyAdmin->imageStyleForm($form, $definition);
    }

    $form['image_style']['#description'] = t('The main image style. Ignored if Breakpoints are provided, use smaller image style here instead. Otherwise this is the only image displayed. If Slick media module installed, this determines iframe sizes to have various iframe dimensions with just a single file entity view mode, relevant for a mix of image and multimedia to get a consistent display.');
  }

  /**
   * Returns the main form elements.
   */
  public function mainForm(array &$form, $definition = []) {
    if (!isset($form['image'])) {
      $this->blazyAdmin->fieldableForm($form, $definition);
    }

    $form['fillers'] = [
      '#type'        => 'select',
      '#title'       => t('Filler start at'),
      '#options'     => array_combine(range(1, 42), range(1, 42)),
      '#description' => t('Index to mark contents as fillers. Contents starting from this value will be treated as fillers. Be sure the total Views rows are bigger than this. Mason uses fillers to fill in gaps. Fillers are elements that you can define or it will reuse elements within the grid. Leave it empty to disable fillers, and use Promoted option instead to control sizes.'),
    ];
  }

  /**
   * Returns the closing form elements.
   */
  public function closingForm(array &$form, $definition = []) {
    if (!isset($form['cache'])) {
      $this->blazyAdmin->closingForm($form, $definition);
    }

    $form['#attached']['library'][] = 'mason/mason.admin';
  }

  /**
   * Returns available mason skins for select options.
   */
  public function getSkinOptions() {
    $skins = &drupal_static(__METHOD__, NULL);
    if (!isset($skins)) {
      $skins = [];
      foreach ($this->manager->getSkins() as $skin => $properties) {
        $skins[$skin] = Html::escape($properties['name']);
      }
    }

    return $skins;
  }

  /**
   * Returns default layout options for the core Image, or Views.
   */
  public function getLayoutOptions() {
    return [
      'bottom' => t('Caption bottom'),
      'center' => t('Caption center'),
      'top'    => t('Caption top'),
    ];
  }

  /**
   * Return the field formatter settings summary.
   */
  public function settingsSummary($plugin) {
    return $this->blazyAdmin->settingsSummary($plugin);
  }

}

<?php
/**
 * @file
 * Contains \Drupal\gallery\Plugin\Gallery\Chocolate.
 */
namespace Drupal\gallery_slick\Plugin\Gallery;

use Drupal\gallery\GalleryBase;
use Drupal\Core\Url;

/**
 * Provides 'slick' plugin.
 *
 * @Gallery(
 *   id = "gallery_slick",
 *   label = "Slick Carousel",
 *   description = @Translation("Slick is a powerful and performant slideshow/carousel solution leveraging Ken Wheeler's Slick carousel."),
 *   weight = -10
 * )
 */
class Slick extends GalleryBase {

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    $url = Url::fromUri('http://kenwheeler.github.io/slick/');
    $url->setOptions(
      array(
        'attributes' => array(
          'target' => '_blank',
        ),
      )
    );
    $description = $this->t("@slick is a responsive carousel jQuery plugin that supports multiple breakpoints, CSS3 transitions, touch events/swiping &amp; much more!", array(
      '@slick' => $this->l('Slick', $url),
    ));
    return $description;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $items = $this->getItems();
    $build = array(
      '#theme' => 'gallery_slick',
      '#slides' => $items,
      '#attached' => array(
        'library' => 'gallery_slick/slick.init',
      ),
    );
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettingsForm() {
    $form['description'] = array(
      '#markup' => $this->getDescription() . '<br /><br />',
    );

    $form['accessibility'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Accessibility'),
      '#description' => $this->t('Enables tabbing and arrow key navigation.'),
      '#default_value' => isset($this->configuration['accessibility']) ? $this->configuration['accessibility'] : TRUE,
    );

    $form['adaptive_height'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('AdaptiveHeight'),
      '#description' => $this->t('Enables adaptive height for single slide horizontal carousels.'),
      '#default_value' => isset($this->configuration['adaptive_height']) ? $this->configuration['adaptive_height'] : FALSE,
    );

    $form['autoplay'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Autoplay'),
      '#description' => $this->t('Enables Autoplay'),
      '#default_value' => isset($this->configuration['autoplay']) ? $this->configuration['autoplay'] : FALSE,
    );

    $form['autoplay_speed'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('AutoplaySpeed'),
      '#description' => $this->t('Autoplay Speed in milliseconds.'),
      '#default_value' => isset($this->configuration['autoplay_speed']) ? $this->configuration['autoplay_speed'] : NULL,
      '#attributes' => array(
        'placeholder' => '3000',
      ),
    );

    $form['arrows'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Arrows'),
      '#description' => $this->t('Prev/Next Arrows.'),
      '#default_value' => isset($this->configuration['arrows']) ? $this->configuration['arrows'] : TRUE,
    );

    $form['as_nav_for'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('AsNavFor'),
      '#description' => $this->t('Set the slider to be the navigation of other slider (Class or ID Name).'),
      '#default_value' => isset($this->configuration['as_nav_for']) ? $this->configuration['as_nav_for'] : NULL,
      '#attributes' => array(
        'placeholder' => '',
      ),
    );

    $form['append_arrows'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('AppendArrows'),
      '#description' => $this->t('Change where the navigation arrows are attached (Selector, htmlString, Array, Element, jQuery object).'),
      '#default_value' => isset($this->configuration['append_arrows']) ? $this->configuration['append_arrows'] : NULL,
      '#attributes' => array(
        'placeholder' => '',
      ),
    );

    $form['append_dots'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('AppendDots'),
      '#description' => $this->t('Change where the navigation dots are attached (Selector, htmlString, Array, Element, jQuery object).'),
      '#default_value' => isset($this->configuration['append_dots']) ? $this->configuration['append_dots'] : NULL,
      '#attributes' => array(
        'placeholder' => '',
      ),
    );

    $form['prev_arrow'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('prevArrow'),
      '#description' => $this->t('Allows you to select a node or customize the HTML for the "Previous" arrow.'),
      '#default_value' => isset($this->configuration['prev_arrow']) ? $this->configuration['prev_arrow'] : NULL,
      '#attributes' => array(
        'placeholder' => '<button type="button" class="slick-prev">Previous</button>',
      ),
    );

    $form['next_arrow'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('nextArrow'),
      '#description' => $this->t('Allows you to select a node or customize the HTML for the "Next" arrow.'),
      '#default_value' => isset($this->configuration['next_arrow']) ? $this->configuration['next_arrow'] : NULL,
      '#attributes' => array(
        'placeholder' => '<button type="button" class="slick-next">Next</button>',
      ),
    );

    $form['center_mode'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('centerMode'),
      '#description' => $this->t('Enables centered view with partial prev/next slides. Use with odd numbered slidesToShow counts.'),
      '#default_value' => isset($this->configuration['center_mode']) ? $this->configuration['center_mode'] : FALSE,
    );

    $form['center_padding'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('centerPadding'),
      '#description' => $this->t('Side padding when in center mode (px or %).'),
      '#default_value' => isset($this->configuration['center_padding']) ? $this->configuration['center_padding'] : NULL,
      '#attributes' => array(
        'placeholder' => '50px',
      ),
    );

    $form['css_ease'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('cssEase'),
      '#description' => $this->t('CSS3 Animation Easing.'),
      '#default_value' => isset($this->configuration['css_ease']) ? $this->configuration['css_ease'] : NULL,
      '#attributes' => array(
        'placeholder' => 'ease',
      ),
    );

    $form['custom_paging'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('customPaging'),
      '#description' => $this->t('Custom paging templates. See source for use example.'),
      '#default_value' => isset($this->configuration['custom_paging']) ? $this->configuration['custom_paging'] : NULL,
    );

    $form['dots'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('dots'),
      '#description' => $this->t('Show dot indicators.'),
      '#default_value' => isset($this->configuration['dots']) ? $this->configuration['dots'] : FALSE,
    );

    $form['dot_class'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('dotsClass'),
      '#description' => $this->t('Class for slide indicator dots container.'),
      '#default_value' => isset($this->configuration['dots']) ? $this->configuration['dots'] : NULL,
      '#attributes' => array(
        'placeholder' => 'slick-dots',
      ),
    );

    $form['draggable'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('draggable'),
      '#description' => $this->t('Enable mouse dragging.'),
      '#default_value' => isset($this->configuration['draggable']) ? $this->configuration['draggable'] : TRUE,
    );

    $form['fade'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('fade'),
      '#description' => $this->t('Enable fade.'),
      '#default_value' => isset($this->configuration['fade']) ? $this->configuration['fade'] : FALSE,
    );

    $form['focus_on_select'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('focusOnSelect'),
      '#description' => $this->t('Enable focus on selected element (click).'),
      '#default_value' => isset($this->configuration['focus_on_select']) ? $this->configuration['focus_on_select'] : FALSE,
    );

    $form['easing'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('easing'),
      '#description' => $this->t('Add easing for jQuery animate. Use with easing libraries or default easing methods.'),
      '#default_value' => isset($this->configuration['easing']) ? $this->configuration['easing'] : FALSE,
      '#attributes' => array(
        'placeholder' => 'linear',
      ),
    );

    $form['edge_friction'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('edgeFriction'),
      '#description' => $this->t('Show dot indicators.'),
      '#default_value' => isset($this->configuration['edge_friction']) ? $this->configuration['edge_friction'] : NULL,
      '#attributes' => array(
        'placeholder' => '0.15',
      ),
    );

    return $form;
  }

}

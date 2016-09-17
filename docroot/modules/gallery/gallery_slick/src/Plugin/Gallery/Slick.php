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
    $description = $this->t('@slick is a responsive carousel jQuery plugin that supports multiple breakpoints, CSS3 transitions, touch events/swiping &amp; much more!', array(
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
      '#default_value' => isset($this->configuration['autoplay_speed']) ? $this->configuration['autoplay_speed'] : '3000',
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
    );

    $form['append_arrows'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('AppendArrows'),
      '#description' => $this->t('Change where the navigation arrows are attached (Selector, htmlString, Array, Element, jQuery object).'),
      '#default_value' => isset($this->configuration['append_arrows']) ? $this->configuration['append_arrows'] : NULL,
    );

    $form['append_dots'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('AppendDots'),
      '#description' => $this->t('Change where the navigation dots are attached (Selector, htmlString, Array, Element, jQuery object).'),
      '#default_value' => isset($this->configuration['append_dots']) ? $this->configuration['append_dots'] : NULL,
    );

    $form['prev_arrow'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('prevArrow'),
      '#description' => $this->t('Allows you to select a node or customize the HTML for the "Previous" arrow.'),
      '#default_value' => isset($this->configuration['prev_arrow']) ? $this->configuration['prev_arrow'] : '<button type="button" class="slick-prev">Previous</button>',
    );

    $form['next_arrow'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('nextArrow'),
      '#description' => $this->t('Allows you to select a node or customize the HTML for the "Next" arrow.'),
      '#default_value' => isset($this->configuration['next_arrow']) ? $this->configuration['next_arrow'] : '<button type="button" class="slick-next">Next</button>',
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
      '#default_value' => isset($this->configuration['center_padding']) ? $this->configuration['center_padding'] : '50px',
    );

    $form['css_ease'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('cssEase'),
      '#description' => $this->t('CSS3 Animation Easing.'),
      '#default_value' => isset($this->configuration['css_ease']) ? $this->configuration['css_ease'] : 'ease',
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

    $form['dots_class'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('dotsClass'),
      '#description' => $this->t('Class for slide indicator dots container.'),
      '#default_value' => isset($this->configuration['dots_class']) ? $this->configuration['dots_class'] : 'slick-dots',
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
      '#default_value' => isset($this->configuration['easing']) ? $this->configuration['easing'] : 'linear',
    );

    $form['edge_friction'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('edgeFriction'),
      '#description' => $this->t('Show dot indicators.'),
      '#default_value' => isset($this->configuration['edge_friction']) ? $this->configuration['edge_friction'] : '0.15',
    );

    $form['infinite'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('infinite'),
      '#description' => $this->t('Infinite loop sliding.'),
      '#default_value' => isset($this->configuration['infinite']) ? $this->configuration['infinite'] : TRUE,
    );

    $form['initial_slide'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('initialSlide'),
      '#description' => $this->t('Slide to start on.'),
      '#default_value' => isset($this->configuration['initial_slide']) ? $this->configuration['initial_slide'] : 0,
    );

    $form['lazy_load'] = array(
      '#type' => 'string',
      '#title' => $this->t('lazyLoad'),
      '#description' => $this->t('Set lazy loading technique. Accepts \'ondemand\' or \'progressive\'.'),
      '#default_value' => isset($this->configuration['lazy_load']) ? $this->configuration['lazy_load'] : 'ondemand',
    );

    $form['mobile_first'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('mobileFirst'),
      '#description' => $this->t('Responsive settings use mobile first calculation.'),
      '#default_value' => isset($this->configuration['mobile_first']) ? $this->configuration['mobile_first'] : FALSE,
    );

    $form['pause_on_focus'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('pauseOnFocus'),
      '#description' => $this->t('Pause Autoplay On Focus.'),
      '#default_value' => isset($this->configuration['pause_on_focus']) ? $this->configuration['pause_on_focus'] : TRUE,
    );

    $form['pause_on_hover'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('pauseOnHover'),
      '#description' => $this->t('Pause Autoplay On Hover.'),
      '#default_value' => isset($this->configuration['pause_on_hover']) ? $this->configuration['pause_on_hover'] : TRUE,
    );

    $form['pause_on_dots_hover'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('pauseOnDotsHover'),
      '#description' => $this->t('Pause Autoplay when a dot is hovered.'),
      '#default_value' => isset($this->configuration['pause_on_dots_hover']) ? $this->configuration['pause_on_dots_hover'] : FALSE,
    );

    $form['respond_to'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('respondTo'),
      '#description' => $this->t('Width that responsive object responds to. Can be \'window\', \'slider\' or \'min\' (the smaller of the two).'),
      '#default_value' => isset($this->configuration['respond_to']) ? $this->configuration['respond_to'] : 'window',
    );

    $form['responsive'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('responsive'),
      '#description' => $this->t('Object containing breakpoints and settings objects (see demo). Enables settings sets at given screen width. Set settings to "unslick" instead of an object to disable slick at a given breakpoint.'),
      '#default_value' => isset($this->configuration['responsive']) ? $this->configuration['responsive'] : NULL,
    );

    $form['rows'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('rows'),
      '#description' => $this->t('Setting this to more than 1 initializes grid mode. Use slidesPerRow to set how many slides should be in each row.'),
      '#default_value' => isset($this->configuration['rows']) ? $this->configuration['rows'] : 1,
    );

    $form['slide'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('slide'),
      '#description' => $this->t('Element query to use as slide.'),
      '#default_value' => isset($this->configuration['slide']) ? $this->configuration['slide'] : NULL,
    );

    $form['slides_per_row'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('slidesPerRow'),
      '#description' => $this->t('With grid mode intialized via the rows option, this sets how many slides are in each grid row.'),
      '#default_value' => isset($this->configuration['slides_per_row']) ? $this->configuration['slides_per_row'] : '1',
    );

    $form['slides_to_show'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('slidesToShow'),
      '#description' => $this->t('# of slides to show.'),
      '#default_value' => isset($this->configuration['slides_to_show']) ? $this->configuration['slides_to_show'] : '1',
    );

    $form['slides_to_scroll'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('slidesToScroll'),
      '#description' => $this->t('# of slides to scroll.'),
      '#default_value' => isset($this->configuration['slides_to_scroll']) ? $this->configuration['slides_to_scroll'] : '1',
    );

    $form['speed'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('speed'),
      '#description' => $this->t('Slide/Fade animation speed.'),
      '#default_value' => isset($this->configuration['speed']) ? $this->configuration['speed'] : '1',
    );

    $form['swipe'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('swipe'),
      '#description' => $this->t('Enable swiping.'),
      '#default_value' => isset($this->configuration['swipe']) ? $this->configuration['swipe'] : TRUE,
    );

    $form['swipe_to_slide'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('swipeToSlide'),
      '#description' => $this->t('Allow users to drag or swipe directly to a slide irrespective of slidesToScroll.'),
      '#default_value' => isset($this->configuration['swipe_to_slide']) ? $this->configuration['swipe_to_slide'] : FALSE,
    );

    $form['touch_move'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('touchMove'),
      '#description' => $this->t('Enable slide motion with touch.'),
      '#default_value' => isset($this->configuration['touch_move']) ? $this->configuration['touch_move'] : TRUE,
    );

    $form['touch_threshold'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('touchThreshold'),
      '#description' => $this->t('To advance slides, the user must swipe a length of (1/touchThreshold) * the width of the slider.'),
      '#default_value' => isset($this->configuration['touch_threshold']) ? $this->configuration['touch_threshold'] : '5',
    );

    $form['use_css'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('useCSS'),
      '#description' => $this->t('Enable/Disable CSS Transitions.'),
      '#default_value' => isset($this->configuration['use_css']) ? $this->configuration['use_css'] : TRUE,
    );

    $form['use_transform'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('useTransform'),
      '#description' => $this->t('Enable/Disable CSS Transforms.'),
      '#default_value' => isset($this->configuration['use_transform']) ? $this->configuration['use_transform'] : TRUE,
    );

    $form['variable_width'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('variable_width'),
      '#description' => $this->t('Variable width slides.'),
      '#default_value' => isset($this->configuration['variable_width']) ? $this->configuration['variable_width'] : FALSE,
    );

    $form['vertical'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('vertical'),
      '#description' => $this->t('Vertical slide mode.'),
      '#default_value' => isset($this->configuration['vertical']) ? $this->configuration['vertical'] : FALSE,
    );

    $form['vertical_swiping'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('verticalSwiping'),
      '#description' => $this->t('Vertical swipe mode.'),
      '#default_value' => isset($this->configuration['vertical_swiping']) ? $this->configuration['vertical_swiping'] : FALSE,
    );

    $form['rtl'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('rtl'),
      '#description' => $this->t('Change the slider\'s direction to become right-to-left.'),
      '#default_value' => isset($this->configuration['rtl']) ? $this->configuration['rtl'] : FALSE,
    );

    $form['wait_for_animate'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('waitForAnimate'),
      '#description' => $this->t('Ignores requests to advance the slide while animating.'),
      '#default_value' => isset($this->configuration['wait_for_animate']) ? $this->configuration['wait_for_animate'] : TRUE,
    );

    $form['z_index'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('zIndex'),
      '#description' => $this->t('Set the zIndex values for slides, useful for IE9 and lower.'),
      '#default_value' => isset($this->configuration['z_index']) ? $this->configuration['z_index'] : '1000',
    );

    return $form;
  }

}

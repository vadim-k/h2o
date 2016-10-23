<?php
/**
 * @file
 * Contains \Drupal\gallery\Plugin\Gallery\Chocolate.
 */
namespace Drupal\gallery_slick\Plugin\Gallery;

use Drupal\gallery\GalleryBase;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Template\Attribute;
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
  public static function defaultSettings() {
    return array(
      'accessibility' => TRUE,
      'adaptive_height' => FALSE,
      'autoplay' => FALSE,
      'autoplay_speed' => '3000',
      'arrows' => TRUE,
      'as_nav_for' => '',
      'append_arrows' => '',
      'append_dots' => '',
      'prev_arrow' => '<button type="button" class="slick-prev">Previous</button>',
      'next_arrow' => '<button type="button" class="slick-next">Next</button>',
      'center_mode' => FALSE,
      'center_padding' => '50px',
      'css_ease' => 'ease',
      'custom_paging' => '',
      'dots' => FALSE,
      'dots_class' => 'slick-dots',
      'draggable' => TRUE,
      'fade' => FALSE,
      'focus_on_select' => FALSE,
      'easing' => 'linear',
      'edge_friction' => '0.15',
      'infinite' => TRUE,
      'initial_slide' => 0,
      'lazy_load' => 'ondemand',
      'mobile_first' => FALSE,
      'pause_on_focus' => TRUE,
      'pause_on_hover' => TRUE,
      'pause_on_dots_hover' => FALSE,
      'respond_to' => 'window',
      'responsive' => '',
      'rows' => 1,
      'slide' => '',
      'slides_per_row' => '1',
      'slides_to_show' => '1',
      'slides_to_scroll' => '1',
      'speed' => '300',
      'swipe' => TRUE,
      'swipe_to_slide' => FALSE,
      'touch_move' => TRUE,
      'touch_threshold' => '5',
      'use_css' => TRUE,
      'use_transform' => TRUE,
      'variable_width' => FALSE,
      'vertical' => FALSE,
      'vertical_swiping' => FALSE,
      'rtl' => FALSE,
      'wait_for_animate' => TRUE,
      'z_index' => 1000,
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $items = $this->getItems();

    $attributes = new Attribute();
    $attributes->addClass('slick-slider');
    $attributes->setAttribute('data-style', $this->configuration['style_name']);

    $settings = $this->getSettings();
    
    foreach ($settings as $key => $setting) {
      if ($setting === '' || $setting === NULL) {
        unset($settings[$key]);
      }
    }

    $build = array(
      '#theme' => 'gallery_slick',
      '#slider_attributes' => $attributes,
      '#slides' => $items,
      '#attached' => array(
        'library' => 'gallery_slick/slick.init',
        'drupalSettings' => array(
          'slickSettings' => array(
            $this->configuration['style_name'] => $settings,
          ),
        ),
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
      '#default_value' => $this->getSetting('accessibility'),
    );

    $form['adaptive_height'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('AdaptiveHeight'),
      '#description' => $this->t('Enables adaptive height for single slide horizontal carousels.'),
      '#default_value' => $this->getSetting('adaptive_height'),
    );

    $form['autoplay'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Autoplay'),
      '#description' => $this->t('Enables Autoplay'),
      '#default_value' => $this->getSetting('autoplay'),
    );

    $form['autoplay_speed'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('AutoplaySpeed'),
      '#description' => $this->t('Autoplay Speed in milliseconds.'),
      '#default_value' => $this->getSetting('autoplay_speed'),
    );

    $form['arrows'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Arrows'),
      '#description' => $this->t('Prev/Next Arrows.'),
      '#default_value' => $this->getSetting('arrows'),
    );

    $form['as_nav_for'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('AsNavFor'),
      '#description' => $this->t('Set the slider to be the navigation of other slider (Class or ID Name).'),
      '#default_value' => $this->getSetting('as_nav_for'),
    );

    $form['append_arrows'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('AppendArrows'),
      '#description' => $this->t('Change where the navigation arrows are attached (Selector, htmlString, Array, Element, jQuery object).'),
      '#default_value' => $this->getSetting('append_arrows'),
    );

    $form['append_dots'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('AppendDots'),
      '#description' => $this->t('Change where the navigation dots are attached (Selector, htmlString, Array, Element, jQuery object).'),
      '#default_value' => $this->getSetting('append_dots'),
    );

    $form['prev_arrow'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('prevArrow'),
      '#description' => $this->t('Allows you to select a node or customize the HTML for the "Previous" arrow.'),
      '#default_value' => $this->getSetting('prev_arrow'),
    );

    $form['next_arrow'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('nextArrow'),
      '#description' => $this->t('Allows you to select a node or customize the HTML for the "Next" arrow.'),
      '#default_value' => $this->getSetting('next_arrow'),
    );

    $form['center_mode'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('centerMode'),
      '#description' => $this->t('Enables centered view with partial prev/next slides. Use with odd numbered slidesToShow counts.'),
      '#default_value' => $this->getSetting('center_mode'),
    );

    $form['center_padding'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('centerPadding'),
      '#description' => $this->t('Side padding when in center mode (px or %).'),
      '#default_value' => $this->getSetting('center_padding'),
    );

    $form['css_ease'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('cssEase'),
      '#description' => $this->t('CSS3 Animation Easing.'),
      '#default_value' => $this->getSetting('css_ease'),
    );

    $form['custom_paging'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('customPaging'),
      '#description' => $this->t('Custom paging templates. See source for use example.'),
      '#default_value' => $this->getSetting('custom_paging'),
    );

    $form['dots'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('dots'),
      '#description' => $this->t('Show dot indicators.'),
      '#default_value' => $this->getSetting('dots'),
    );

    $form['dots_class'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('dotsClass'),
      '#description' => $this->t('Class for slide indicator dots container.'),
      '#default_value' => $this->getSetting('dots_class'),
    );

    $form['draggable'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('draggable'),
      '#description' => $this->t('Enable mouse dragging.'),
      '#default_value' => $this->getSetting('draggable'),
    );

    $form['fade'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('fade'),
      '#description' => $this->t('Enable fade.'),
      '#default_value' => $this->getSetting('fade'),
    );

    $form['focus_on_select'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('focusOnSelect'),
      '#description' => $this->t('Enable focus on selected element (click).'),
      '#default_value' => $this->getSetting('focus_on_select'),
    );

    $form['easing'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('easing'),
      '#description' => $this->t('Add easing for jQuery animate. Use with easing libraries or default easing methods.'),
      '#default_value' => $this->getSetting('easing'),
    );

    $form['edge_friction'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('edgeFriction'),
      '#description' => $this->t('Show dot indicators.'),
      '#default_value' => $this->getSetting('edge_friction'),
    );

    $form['infinite'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('infinite'),
      '#description' => $this->t('Infinite loop sliding.'),
      '#default_value' => $this->getSetting('infinite'),
    );

    $form['initial_slide'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('initialSlide'),
      '#description' => $this->t('Slide to start on.'),
      '#default_value' => $this->getSetting('initial_slide'),
    );

    $form['lazy_load'] = array(
      '#type' => 'string',
      '#title' => $this->t('lazyLoad'),
      '#description' => $this->t('Set lazy loading technique. Accepts \'ondemand\' or \'progressive\'.'),
      '#default_value' => $this->getSetting('lazy_load'),
    );

    $form['mobile_first'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('mobileFirst'),
      '#description' => $this->t('Responsive settings use mobile first calculation.'),
      '#default_value' => $this->getSetting('mobile_first'),
    );

    $form['pause_on_focus'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('pauseOnFocus'),
      '#description' => $this->t('Pause Autoplay On Focus.'),
      '#default_value' => $this->getSetting('pause_on_focus'),
    );

    $form['pause_on_hover'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('pauseOnHover'),
      '#description' => $this->t('Pause Autoplay On Hover.'),
      '#default_value' => $this->getSetting('pause_on_hover'),
    );

    $form['pause_on_dots_hover'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('pauseOnDotsHover'),
      '#description' => $this->t('Pause Autoplay when a dot is hovered.'),
      '#default_value' => $this->getSetting('pause_on_dots_hover'),
    );

    $form['respond_to'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('respondTo'),
      '#description' => $this->t('Width that responsive object responds to. Can be \'window\', \'slider\' or \'min\' (the smaller of the two).'),
      '#default_value' => $this->getSetting('respond_to'),
    );

    $form['responsive'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('responsive'),
      '#description' => $this->t('Object containing breakpoints and settings objects (see demo). Enables settings sets at given screen width. Set settings to "unslick" instead of an object to disable slick at a given breakpoint.'),
      '#default_value' => $this->getSetting('responsive'),
    );

    $form['rows'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('rows'),
      '#description' => $this->t('Setting this to more than 1 initializes grid mode. Use slidesPerRow to set how many slides should be in each row.'),
      '#default_value' => $this->getSetting('rows'),
    );

    $form['slide'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('slide'),
      '#description' => $this->t('Element query to use as slide.'),
      '#default_value' => $this->getSetting('slide'),
    );

    $form['slides_per_row'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('slidesPerRow'),
      '#description' => $this->t('With grid mode intialized via the rows option, this sets how many slides are in each grid row.'),
      '#default_value' => $this->getSetting('slides_per_row'),
    );

    $form['slides_to_show'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('slidesToShow'),
      '#description' => $this->t('# of slides to show.'),
      '#default_value' => $this->getSetting('slides_to_show'),
    );

    $form['slides_to_scroll'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('slidesToScroll'),
      '#description' => $this->t('# of slides to scroll.'),
      '#default_value' => $this->getSetting('slides_to_scroll'),
    );

    $form['speed'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('speed'),
      '#description' => $this->t('Slide/Fade animation speed.'),
      '#default_value' => $this->getSetting('speed'),
    );

    $form['swipe'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('swipe'),
      '#description' => $this->t('Enable swiping.'),
      '#default_value' => $this->getSetting('swipe'),
    );

    $form['swipe_to_slide'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('swipeToSlide'),
      '#description' => $this->t('Allow users to drag or swipe directly to a slide irrespective of slidesToScroll.'),
      '#default_value' => $this->getSetting('swipe_to_slide'),
    );

    $form['touch_move'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('touchMove'),
      '#description' => $this->t('Enable slide motion with touch.'),
      '#default_value' => $this->getSetting('touch_move'),
    );

    $form['touch_threshold'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('touchThreshold'),
      '#description' => $this->t('To advance slides, the user must swipe a length of (1/touchThreshold) * the width of the slider.'),
      '#default_value' => $this->getSetting('touch_threshold'),
    );

    $form['use_css'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('useCSS'),
      '#description' => $this->t('Enable/Disable CSS Transitions.'),
      '#default_value' => $this->getSetting('use_css'),
    );

    $form['use_transform'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('useTransform'),
      '#description' => $this->t('Enable/Disable CSS Transforms.'),
      '#default_value' => $this->getSetting('use_transform'),
    );

    $form['variable_width'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('variable_width'),
      '#description' => $this->t('Variable width slides.'),
      '#default_value' => $this->getSetting('variable_width'),
    );

    $form['vertical'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('vertical'),
      '#description' => $this->t('Vertical slide mode.'),
      '#default_value' => $this->getSetting('vertical'),
    );

    $form['vertical_swiping'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('verticalSwiping'),
      '#description' => $this->t('Vertical swipe mode.'),
      '#default_value' => $this->getSetting('vertical_swiping'),
    );

    $form['rtl'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('rtl'),
      '#description' => $this->t('Change the slider\'s direction to become right-to-left.'),
      '#default_value' => $this->getSetting('rtl'),
    );

    $form['wait_for_animate'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('waitForAnimate'),
      '#description' => $this->t('Ignores requests to advance the slide while animating.'),
      '#default_value' => $this->getSetting('wait_for_animate'),
    );

    $form['z_index'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('zIndex'),
      '#description' => $this->t('Set the zIndex values for slides, useful for IE9 and lower.'),
      '#default_value' => $this->getSetting('z_index'),
    );

    return $form;
  }

}

/**
 * @file
 * Initializes Slick .
 */

(function ($, Drupal, drupalSettings) {

  /**
   * Attaches slick behavior to HTML elements.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.slick = {
    attach: function (context) {
      $('.slick-slider:not(.unslick)', context).once('slick').each(function () {
        $(this).slick({
          
        });
      });
    },
  };

})(jQuery, Drupal, drupalSettings);

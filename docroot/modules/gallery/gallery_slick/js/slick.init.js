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
      //console.log(drupalSettings.slickSettings);
      $('.slick-slider:not(.unslick)', context).once('slick').each(function () {
        var style = $(this).data('style');
        for (var key in drupalSettings.slickSettings[style]) {
          if (drupalSettings.slickSettings[style][key] == 0 || drupalSettings.slickSettings[style][key] == 1) {
            drupalSettings.slickSettings[style][key] = Boolean(drupalSettings.slickSettings[style][key]);
          }
        }
        $(this).slick(drupalSettings.slickSettings[style]);
      });
    },
  };

})(jQuery, Drupal, drupalSettings);

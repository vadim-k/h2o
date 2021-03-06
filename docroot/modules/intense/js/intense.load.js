/**
 * @file
 * Provides Intense loader.
 */

(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.intense = {
    attach: function (context) {

      $('.intense', context).once('intense').each(function () {
        $(this).parent().addClass('intense-wrapper');
        Intense(this);
      });

    }
  };

})(jQuery, Drupal);

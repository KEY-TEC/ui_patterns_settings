/**
 * @file
 * JavaScript file for the UI Pattern settings module.
 */

(function ($, Drupal, drupalSettings, DrupalCoffee) {

  'use strict';

  /**
   * Attaches ui patterns settings module behaviors.
   *
   * Handles enable/disable token element.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attach  ui patterns settings toggle functionality to the page.
   *
   * @todo get most of it out of the behavior in dedicated functions.
   */
  Drupal.behaviors.ups_toggle_token = {
    attach: function () {
      $('.ui-patterns-settings__token-wrapper').once().each(function () {
        var wrapper = $(this);
        var toggler = $('.js-ui-patterns-settings__toggler', wrapper);
        $(toggler).click(function () {
          var tokenInput = $('.js-ui-patterns-settings__token', wrapper);
          if ($(wrapper).hasClass('ui-patterns-settings--token-has-value')) {
            tokenInput.attr('data-init-val', tokenInput.val());
            tokenInput.val('');
            wrapper.removeClass('ui-patterns-settings--token-has-value');
          } else {
            tokenInput.val(tokenInput.attr('data-init-val'));
            wrapper.addClass('ui-patterns-settings--token-has-value');
          }
        });
      });
    }
  };

})(jQuery, Drupal, drupalSettings);

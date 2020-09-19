/**
 * @file
 * JavaScript file for the UI Pattern settings module.
 */

(function ($, Drupal, drupalSettings, DrupalCoffee) {

  'use strict';

  /**
   * Attaches ui patterns settings module behaviors.
   *
   * Handles enable/disable bind input element.
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
      var disableClass = 'ui-pattern-settings--disable';

      $('.ui-pattern-settings__token-wrapper').once().each(function () {
        if ($(this).hasClass('ui-pattern-settings--token-has-value')) {
          $(this).prev().addClass(disableClass);
        }
        else {
          $(this).addClass(disableClass);
        }
      });


      $('.js-ui-patterns-settings-token').once().each(function () {
        $(this).click(function () {
          var tokenWrapper = $(this).closest('.ui-pattern-settings__token-wrapper');
          var tokenInput = tokenWrapper.find('input');
          var inputWrapper = tokenWrapper.prev();
          var initValue = tokenInput.val();

          tokenWrapper.addClass(disableClass);
          tokenInput.attr('data-init-val', initValue);
          tokenInput.val('');
          inputWrapper.removeClass(disableClass);
        });
      });

      $('.js-ui-patterns-settings-input').once().each(function () {
        $(this).click(function () {
          var inputWrapper = $(this).closest('.js-form-item');
          var tokenWrapper = inputWrapper.next();
          inputWrapper.addClass(disableClass);
          tokenWrapper.removeClass(disableClass);
          var tokenInput = tokenWrapper.find('input');
          var restoreVal = tokenInput.attr('data-init-val');
          if (restoreVal != '') {
            tokenInput.val(restoreVal);
          }
        });
      })
    }
  };

})(jQuery, Drupal, drupalSettings);

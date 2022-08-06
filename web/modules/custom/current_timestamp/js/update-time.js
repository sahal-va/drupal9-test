(function ($, Drupal) {
  "use strict";

  Drupal.behaviors.currentTime = {
    attach: function (context, settings) {
      window.onload = function () {
        // Just trigger click to the button.
        // Replacement will be done by using Drupal Ajax APIs only.
        $('#anonymous_current_time').click();
      };
    },
  };
})(jQuery, Drupal);

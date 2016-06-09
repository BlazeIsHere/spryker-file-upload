'use strict';

(function () {

  $('.dropzone').each(function(index) {

      Dropzone.options[this.id] = {
          paramName: 'uploadedFile',
          init: function() {
              this.on('queuecomplete', function(file) {
                  window.location.reload();
              });
          }
      };

  });


})();

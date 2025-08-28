(function($) {
  // Success Toast
  window.showSuccessToast = function(message = 'Operation successful!') {
    resetToastPosition();
    $.toast({
      heading: 'Success',
      text: message,
      showHideTransition: 'slide',
      icon: 'success',
      loaderBg: '#f96868',
      position: 'bottom-right'
    });
  };

  // Info Toast
  window.showInfoToast = function(message = 'Information message') {
    resetToastPosition();
    $.toast({
      heading: 'Info',
      text: message,
      showHideTransition: 'slide',
      icon: 'info',
      loaderBg: '#46c35f',
      position: 'bottom-right'
    });
  };

  // Warning Toast
  window.showWarningToast = function(message = 'Warning!') {
    resetToastPosition();
    $.toast({
      heading: 'Warning',
      text: message,
      showHideTransition: 'slide',
      icon: 'warning',
      loaderBg: '#57c7d4',
      position: 'bottom-right'
    });
  };

  // Danger/Error Toast
  window.showDangerToast = function(message = 'An error occurred') {
    resetToastPosition();
    $.toast({
      heading: 'Error',
      text: message,
      showHideTransition: 'slide',
      icon: 'error',
      loaderBg: '#f2a654',
      position: 'bottom-right'
    });
  };

  // Position-based Toast
  window.showToastPosition = function(position) {
    resetToastPosition();
    $.toast({
      heading: 'Positioning',
      text: 'Custom positioning example.',
      position: String(position),
      icon: 'info',
      stack: false,
      loaderBg: '#f96868'
    });
  };

  // Custom-position Toast
  window.showToastInCustomPosition = function() {
    resetToastPosition();
    $.toast({
      heading: 'Custom positioning',
      text: 'Using exact pixel positions.',
      icon: 'info',
      position: {
        left: 120,
        top: 120
      },
      stack: false,
      loaderBg: '#f96868'
    });
  };

  // Reset previous toasts
  function resetToastPosition() {
    $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center');
    $(".jq-toast-wrap").css({
      "top": "",
      "left": "",
      "bottom": "",
      "right": ""
    });
  }
})(jQuery);

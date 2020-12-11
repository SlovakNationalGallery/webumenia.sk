const ClipboardJS = require('clipboard')

clipboard = new ClipboardJS('[data-clipboard-text]')

clipboard.on('success', function(e) {
  const originalTitle = $(e.trigger).data('original-title')
  const successTitle = $(e.trigger).data('success-title') || originalTitle

  // Replace popover text
  $(e.trigger)
    .attr('title', successTitle)
    .tooltip('fixTitle')
    .tooltip('show')

  // Auto-hide after 2 seconds
  setTimeout(function () {
    $(e.trigger)
      .attr('title', originalTitle)
      .tooltip('fixTitle')
      .tooltip('hide')
  }, 2000);
});

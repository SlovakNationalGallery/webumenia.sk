const ClipboardJS = require('clipboard')

clipboard = new ClipboardJS('[data-clipboard-text]')

clipboard.on('success', function(e) {
  $(e.trigger).tooltip('show')

  // Auto-hide after 2 seconds
  setTimeout(function () {
    $(e.trigger).tooltip('hide');
  }, 2000);
});

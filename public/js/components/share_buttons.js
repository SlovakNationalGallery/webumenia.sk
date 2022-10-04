async function copyToClipboard(url, message, el) {
    var msg = 'Whoops, not copied...';
    await navigator.clipboard.writeText(url).then(() => {
      msg = message;
    });
    el.attr('data-original-title', msg).tooltip('show');
    setTimeout(function () {
      el.tooltip('hide');
    }, 2000);
}


$(document).ready(function(){
  $('.js-copy').click(function(event) {
    event.preventDefault();
    var message = $(this).attr('data-message');
    var url = $(this).attr('data-url');
    var el = $(this);
    copyToClipboard(url, message, el);
  });
})
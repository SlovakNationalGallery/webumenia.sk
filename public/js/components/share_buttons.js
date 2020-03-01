function copyToClipboard(url, message, el) {
    var $temp = $("<input>");
    $("#shareLink").append($temp);
    $temp.val(url).select();
    var successful = document.execCommand("copy");
    $temp.remove();
    var msg = successful ? message : 'Whoops, not copied...';
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
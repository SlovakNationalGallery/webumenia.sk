function copyLinkToClipboard(url, message) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(url).select();
    document.execCommand("copy");
    $temp.remove();
    alert(message);
}

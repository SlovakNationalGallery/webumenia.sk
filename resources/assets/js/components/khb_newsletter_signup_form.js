$(function() {
    $('.js-newsletter-signup-form').on('submit', function (e) {
        var form = $(this);

        var jqXHR = $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
        });

        jqXHR.done(function (data) {
            alert(jqXHR.responseText);
        }).fail(function(jqXHR) {
            alert(jqXHR.responseText);
        });

        e.preventDefault();
    });
});
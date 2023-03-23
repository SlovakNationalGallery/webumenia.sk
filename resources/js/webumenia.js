//jQuery to collapse the navbar on scroll
$(window).scroll(function() {
    // if ($(".navbar").offset().top > 50) {
    //     $(".navbar-fixed-top").addClass("top-nav-collapse");
    // } else {
    //     $(".navbar-fixed-top").removeClass("top-nav-collapse");
    // }

    if ($("#top").offset().top > $(window).height() + 500) {
        $("#top").fadeIn();
    } else {
        $("#top").fadeOut();
    }
});

$(document).ready(function(){
    var is_touch_device = 'ontouchstart' in document.documentElement;

    if (!is_touch_device) {
        $("[data-toggle='tooltip']").tooltip({
            container: 'body'
        });
    }

    $('#top a').click(function(){
        $('html,body').animate({scrollTop:0},'slow');return false;
    });

    // handle links with @href started with '#' only
    $(document).on('click', 'a[href^="#"]', function(e) {
        // target element id
        var id = $(this).attr('href');

        // target element
        var $id = $(id);
        if ($id.length === 0) {
            return;
        }

        // prevent standard hash navigation (avoid blinking in IE)
        e.preventDefault();

        // top position relative to the document
        var pos = $id.offset().top;

        // animated top scrolling
        $('body, html').animate({scrollTop: pos},'slow');
    });

    $('.content-slick-images').each( function() {
        const $slick = $(this);
        $slick.slick({
            slide: 'p, a, img, div',
            lazyLoad: 'progressive',
            variableWidth: true,
            infinite: false,
        });
    });
    // safari fix
    $('.content-slick-images img').each(function (index,image) {
        image.style.width = "";
    })

});

export function spravGrid($container) {
    $container.isotope({
        itemSelector: '.item',
        layoutMode: 'masonry'
    });
}

export function isIE() { return navigator.userAgent.match(/Edge\/|Trident\/|MSIE /); }

export function isMobileSafari() { return navigator.userAgent.match(/(iPod|iPhone|iPad)/); }

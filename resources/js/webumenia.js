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
});

export function spravGrid($container) {
    $container.isotope({
        itemSelector: '.item',
        layoutMode: 'masonry'
    });
}

var light_style = [
    {
        "elementType": "all",
        "featureType": "water",
        "stylers": [
            {
                "hue": "#e9ebed"
            },
            {
                "saturation": -78
            },
            {
                "lightness": 67
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "elementType": "all",
        "featureType": "landscape",
        "stylers": [
            {
                "hue": "#ffffff"
            },
            {
                "saturation": -100
            },
            {
                "lightness": 100
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "elementType": "geometry",
        "featureType": "road",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "elementType": "all",
        "featureType": "poi",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "elementType": "geometry",
        "featureType": "road.local",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "elementType": "all",
        "featureType": "transit",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "elementType": "all",
        "featureType": "administrative.locality",
        "stylers": [
            {
                "hue": "#ffffff"
            },
            {
                "saturation": 7
            },
            {
                "lightness": 0
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "elementType": "labels",
        "featureType": "road",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "elementType": "labels",
        "featureType": "road.arterial",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    }
];

export function isIE() { return navigator.userAgent.match(/Edge\/|Trident\/|MSIE /); }

export function isMobileSafari() { return navigator.userAgent.match(/(iPod|iPhone|iPad)/); }

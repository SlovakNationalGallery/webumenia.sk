//jQuery to collapse the navbar on scroll
$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
    }

    if ($("#top").offset().top > $(window).height() + 500) {
        $("#top").fadeIn();
    } else {
        $("#top").fadeOut();
    }
});

$(document).ready(function(){
    $("[data-toggle='tooltip']").tooltip();
    
    $('#top a').click(function(){
        $('html,body').animate({scrollTop:0},'slow');return false;
    });

});

function spravGrid($container) {
    $container.isotope({
        itemSelector : '.item',
        masonry: {
            isFitWidth: true,
            gutter: 20
        }
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

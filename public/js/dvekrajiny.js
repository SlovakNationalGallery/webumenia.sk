//jQuery to collapse the navbar on scroll
$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
    }
});

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

//Google Map Skin - Get more at http://snazzymaps.com/
var myOptions = {
    zoom: 8,
    region: 'SK',
    center: new google.maps.LatLng(48.575862, 19.125629),
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    disableDefaultUI: true,
    styles: [{
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [{
            "color": "#1c1a0e"
        }, {
            "lightness": 17
        }]
    }, {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [{
            "color": "#1c1a0e"
        }, {
            "lightness": 20
        }]
    }, {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [{
            "color": "#1c1a0e"
        }, {
            "lightness": 17
        }]
    }, {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [{
            "color": "#1c1a0e"
        }, {
            "lightness": 29
        }, {
            "weight": 0.2
        }]
    }, {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [{
            "color": "#1c1a0e"
        }, {
            "lightness": 18
        }]
    }, {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [{
            "color": "#1c1a0e"
        }, {
            "lightness": 16
        }]
    }, {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [{
            "color": "#1c1a0e"
        }, {
            "lightness": 21
        }]
    }, {
        "elementType": "labels.text.stroke",
        "stylers": [{
            "visibility": "on"
        }, {
            "color": "#1c1a0e"
        }, {
            "lightness": 16
        }]
    }, {
        "elementType": "labels.text.fill",
        "stylers": [{
            "saturation": 36
        }, {
            "color": "#1c1a0e"
        }, {
            "lightness": 40
        }]
    }, {
        "elementType": "labels.icon",
        "stylers": [{
            "visibility": "off"
        }]
    }, {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [{
            "color": "#1c1a0e"
        }, {
            "lightness": 19
        }]
    }, {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [{
            "color": "#1c1a0e"
        }, {
            "lightness": 20
        }]
    }, {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [{
            "color": "#1c1a0e"
        }, {
            "lightness": 17
        }, {
            "weight": 1.2
        }]
    }]
};

var mapOptions = {
    center: new google.maps.LatLng(48.705862, 19.925629), //slovensko ??    
    zoom: 7, // How zoomed in you want the map to start at (always required)
    minZoom: 5, // Minimum zoom level allowed (0-20)
    maxZoom: 12, // Maximum soom level allowed (0-20)
    zoomControl:true, // Set to true if using zoomControlOptions below, or false to remove all zoom controls.
    zoomControlOptions: {
        style:google.maps.ZoomControlStyle.DEFAULT // DEFAULT or SMALL - Change to SMALL to force just the + and - buttons.
    },
    // The latitude and longitude to center the map (always required)
    scrollwheel: false,
    panControl:false, 
    mapTypeControl:false,
    streetViewControl:false,
    overviewMapControl:false,
    rotateControl:false,

    // How you would like to style the map. 
    // This is where you would paste any style found on Snazzy Maps.
    styles: light_style
};

// var map = new google.maps.Map(document.getElementById('map'), myOptions);
var map = new google.maps.Map(document.getElementById('map'), mapOptions);

var items = [
  ['Dielo 1', 48.14858,17.13099, 4],
  ['Dielo 2', 49.137508, 20.218231, 5],
  ['Dielo 3', 48.460147, 18.889387, 3],
  ['Dielo 4', 49.271966, 21.902811, 2],
  ['Dielo 5', 48.950000, 19.500000, 1]
];

setMarkers(map, items);


function setMarkers(map, locations) {
  // Add markers to the map

  // Marker sizes are expressed as a Size of X,Y
  // where the origin of the image (0,0) is located
  // in the top left of the image.

  // Origins, anchor positions and coordinates of the marker
  // increase in the X direction to the right and in
  // the Y direction down.
  var image = {
    url: '/images/x.svg',
    size: new google.maps.Size(30, 30),
    origin: new google.maps.Point(0,0),
    anchor: new google.maps.Point(0, 30)
  };
  // Shapes define the clickable region of the icon.
  // The type defines an HTML &lt;area&gt; element 'poly' which
  // traces out a polygon as a series of X,Y points. The final
  // coordinate closes the poly by connecting to the first
  // coordinate.
  for (var i = 0; i < locations.length; i++) {
    var item = locations[i];
    var myLatLng = new google.maps.LatLng(item[1], item[2]);
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: image,
        title: item[0],
        zIndex: item[3]
    });
  }
}


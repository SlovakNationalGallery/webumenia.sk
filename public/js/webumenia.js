var authors = ['Martin Benka', 'Ladislav Mednyánszky', 'Mikuláš Galanda', 'Ľudovít Čordák', 'Pavol Poljak', 'Ruský ikonopisec', 'Ferdinand Katona', 'Peter Michal Bohúň'];
var items = new Bloodhound({
  datumTokenizer: function (d) {
            return Bloodhound.tokenizers.whitespace(d.value);
        },
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: '/data/items.json',
  remote: {
    url: '/katalog/suggestions?search=%QUERY',
    filter: function (items) {
            return $.map(items.results, function (item) {
                return {
                    value: item.author + ': ' + item.title,
                    id: item.id
                };
            });
        }
    }
});


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

    items.initialize();

    $('#search').typeahead(
    {
      hint: true,
      highlight: true,
      minLength: 3
    },
    {
      name: 'items',
      displayKey: 'value',
      source: items.ttAdapter()
    }).bind("typeahead:selected", function(obj, datum, name) {
        console.log(datum.id);
        window.location.href = "/dielo/" + datum.id;
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

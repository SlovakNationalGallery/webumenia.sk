var items = new Bloodhound({
  datumTokenizer: function (d) {
            return Bloodhound.tokenizers.whitespace(d.value);
        },
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  limit: 8,
  remote: {
    url: '/katalog/suggestions?search=%QUERY',
    filter: function (items) {
            return $.map(items.results, function (item) {
                return {
                    id: item.id,
                    author: item.author,
                    title: item.title,
                    image: item.image,
                    value: item.author + ': ' + item.title
                };
            });
        }
    }
});

var authors = new Bloodhound({
  datumTokenizer: function (d) {
            return Bloodhound.tokenizers.whitespace(d.value);
        },
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  limit: 3,
  remote: {
    url: '/autori/suggestions?search=%QUERY',
    filter: function (authors) {
            return $.map(authors.results, function (author) {
                return {
                    id: author.id,
                    name: author.name,
                    birth_year: author.birth_year || "",
                    death_year: author.death_year || "",
                    image: author.image,
                    value: author.name
                };
            });
        }
    }
});

var articles = new Bloodhound({
  datumTokenizer: function (d) {
            return Bloodhound.tokenizers.whitespace(d.value);
        },
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  limit: 3,
  remote: {
    url: '/clanky/suggestions?search=%QUERY',
    filter: function (articles) {
            return $.map(articles.results, function (article) {
                return {
                    author: article.author,
                    title: article.title,
                    url: article.url,
                    image: article.image,
                    value: article.author + ': ' + article.title
                };
            });
        }
    }
});

var collections = new Bloodhound({
  datumTokenizer: function (d) {
            return Bloodhound.tokenizers.whitespace(d.value);
        },
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  limit: 3,
  remote: {
    url: '/kolekcie/suggestions?search=%QUERY',
    filter: function (collections) {
            return $.map(collections.results, function (collection) {
                return {
                    author: collection.author,
                    name: collection.name,
                    items: collection.items,
                    url: collection.url,
                    image: collection.image,
                    value: collection.name
                };
            });
        }
    }
});


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
        $("[data-toggle='tooltip']").tooltip();
    }
    
    $('#top a').click(function(){
        $('html,body').animate({scrollTop:0},'slow');return false;
    });

    items.initialize();
    authors.initialize();
    articles.initialize();
    collections.initialize();
    
    $('#search').typeahead(
    {
      hint: true,
      highlight: true,
      minLength: 2      
    },
    {
      name: 'authors',
      displayKey: 'value',
      source: authors.ttAdapter(),
      templates: {
          header: '<h3 class="suggest-type-name">Autori</h3>',
          suggestion: function (data) {
            var format_years = '';
            if (data.birth_year) {
                format_years += '(&#x2734; ' + data.birth_year;
                if (data.death_year) {
                    format_years += '  &ndash; &#x271D; ' + data.death_year;
                }
                format_years += ')';
            }
            return '<p  data-searchd-result="title/'+data.id+'" data-searchd-title="'+data.value+'"><img src="'+data.image+'" class="preview img-circle" />' + data.name + '<br> ' + format_years + '</p>';
          }
      }
    },
    {
      name: 'items',
      displayKey: 'value',
      source: items.ttAdapter(),
      templates: {
          header: '<h3 class="suggest-type-name">Diela</h3>',
          suggestion: function (data) {
              return '<p  data-searchd-result="title/'+data.id+'" data-searchd-title="'+data.value+'"><img src="'+data.image+'" class="preview" /><em>' + data.author + '</em><br> ' + data.title + '</p>';
          }
      }
    },
    {
      name: 'articles',
      displayKey: 'value',
      source: articles.ttAdapter(),
      templates: {
          header: '<h3 class="suggest-type-name">Články</h3>',
          suggestion: function (data) {
              return '<p  data-searchd-result="title/'+data.id+'" data-searchd-title="'+data.value+'"><img src="'+data.image+'" class="preview" /><em>' + data.author + '</em><br> ' + data.title + '</p>';
          }
      }
    },
    {
      name: 'collections',
      displayKey: 'value',
      source: collections.ttAdapter(),
      templates: {
          header: '<h3 class="suggest-type-name">Kolekcie</h3>',
          suggestion: function (data) {
              return '<p  data-searchd-result="title/'+data.id+'" data-searchd-title="'+data.value+'"><img src="'+data.image+'" class="preview" /><em>' + data.author + '</em><br> ' + data.name + '<em> (' + data.items + ' diel)</em>' + '</p>';
          }
      }
    }).bind("typeahead:selected", function(obj, datum, name) {
        switch (name) {
            case 'authors': 
                window.location.href = "/autor/" + datum.id;
                break;
            case 'articles':
                window.location.href = datum.url;
                break;
            case 'collections':
                window.location.href = datum.url;
                break;
            default:
                window.location.href = "/dielo/" + datum.id;
        }
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

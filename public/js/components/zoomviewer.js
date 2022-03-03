$("document").ready(function() {

  $('.zoomviewer').each(function () {

    var initial         = $(this).data('index');
    var itemURL         = $(this).data('item-url');
    var imageCount      = $(this).data('image-count');
    var tileSources     = $(this).data('tile-sources');

    var isLoaded = false;

    function shortenCopyright() {
      if ($(window).width() < 960) {
        var text = $('.credit').text();
        if (text.indexOf('©') > -1) {
          text = text.replace('©', '');
          var parts = text.split(",");
          $('.credit').text('© ' + parts.pop());
        }
      }
    };

    function getPreviousPage() {
      if (viewer.currentPage() > 0) {
        rotationChecked = false;
        viewer.goToPage(viewer.currentPage() - 1);
      }
    };

    function getNextPage() {
      if (viewer.currentPage() < imageCount) {
        rotationChecked = false;
        viewer.goToPage(viewer.currentPage() + 1);
      }
    };

    function changePage(event) {
      if (event.type == 'touchend' || event.type == 'mouseup') {
        var deltaX = event.pageX - (viewer.container.clientWidth/2);
        var deltaY = event.pageY - (viewer.container.clientHeight/2);
        console.log('deltaX: ' + deltaX);
        console.log('deltaY: ' + deltaY);
        var treshold = 5;
        if (deltaX > treshold) {
          getPreviousPage();
        }
        else if (deltaX < -treshold) {
          getNextPage();
        }
      }
    };

    var OSDOptions = {
      id: "viewer",
      prefixUrl: "/images/openseadragon/",
      toolbar:        "toolbarDiv",
      zoomInButton:   "zoom-in",
      zoomOutButton:  "zoom-out",
      homeButton:     "home",
      fullPageButton: "full-page",
      nextButton:     "next",
      previousButton: "previous",
      showNavigator:  false,
      visibilityRatio: 1,
      minZoomLevel: 0,
      defaultZoomLevel: 0,
      autoResize: false,
      tileSources: tileSources
    }

    if (imageCount > 1) {
      $.extend(OSDOptions, {
        autoHideControls: false,
        controlsFadeDelay: 1000,  //ZOOM/HOME/FULL/SEQUENCE
        controlsFadeLength: 500,  //ZOOM/HOME/FULL/SEQUENCE
        sequenceMode: true,
        showReferenceStrip: true,
        referenceStripSizeRatio: 0.07,
        referenceStripScroll: 'vertical',
        initialPage: initial
      })
    }

    viewer = OpenSeadragon(OSDOptions);

    viewer.addHandler('page', function (event) {
      isLoaded = false;
      $('.currentpage #index').html( event.page + 1 );
    });

    window.addEventListener('resize', function() {
      var newSize = new OpenSeadragon.Point(window.innerWidth, window.innerHeight);
      viewer.viewport.resize(newSize, false);
      viewer.viewport.goHome(true);
      viewer.forceRedraw();
    });

    // zoom out instead of showing context menu on right click
    viewer.canvas.oncontextmenu = function() {$('#zoom-out').click(); return false;};

    $(viewer.canvas).mousedown(function(e){
      if( e.button == 2 ) {
        viewer.viewport.zoomBy(0.45); //0.9 * 0.5
        return false;
      }
      return true;
    });

    $('a.return').click(function(){
      if (document.referrer.split('/')[2] === window.location.host) {
        parent.history.back();

        // fallback when opening in new tab/window and history.back() is disabled but referrer is defined
        setTimeout(function(){
          window.location.href = itemURL;
        }, 500);

        return false;
      } else {
        window.location.href = itemURL;
      }
    });

    document.onkeydown = function(evt) {
      evt = evt || window.event;
      switch (evt.keyCode) {
        case 37:
        getPreviousPage();
        break;
        case 39:
        getNextPage();
        break;
        case 38: //up arrow
        getPreviousPage();
        break;
        case 40: //down arrow
        getNextPage();
        break;
      }
    };

    // hide on inactivity
    var interval = 1;
    var timeoutval = 3; //3 seconds

    setInterval(function(){
      if(interval == timeoutval){
        $('.autohide, .referencestrip').fadeOut();
        interval = 1;
      }
      interval = interval+1;
    }, 1000);

    $(viewer.canvas).bind('mousemove keydown', function() {
      $('.autohide, .referencestrip').fadeIn();
      interval = 1;
    });

    viewer.addHandler('canvas-click', function (event) {
      $('.autohide, .referencestrip').fadeIn();
      interval = 1;
    });

    viewer.addHandler('tile-drawn', function () {
      isLoaded = true;
    });

    $( window ).resize(function() {
      shortenCopyright();
    });

    shortenCopyright();

  })
});
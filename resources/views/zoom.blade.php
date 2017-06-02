<!DOCTYPE html>
<html lang="sk">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="author" content="lab.SNG">
        <meta name="robots" content="noindex, nofollow">

        <title>
            {!! $item->title !!} | {{ trans('zoom.title') }}
        </title>

        <!--  favicons-->
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/apple-touch-icon-144x144.png" />
        <link rel="apple-touch-icon-precomposed" sizes="60x60" href="/apple-touch-icon-60x60.png" />
        <link rel="apple-touch-icon-precomposed" sizes="120x120" href="/apple-touch-icon-120x120.png" />
        <link rel="apple-touch-icon-precomposed" sizes="76x76" href="/apple-touch-icon-76x76.png" />
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/apple-touch-icon-152x152.png" />
        <link rel="icon" type="image/png" href="/favicon-196x196.png" sizes="196x196" />
        <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16" />
        <link rel="icon" type="image/png" href="/favicon-128.png" sizes="128x128" />
        <meta name="application-name" content="&nbsp;"/>
        <meta name="msapplication-TileColor" content="#FFFFFF" />
        <meta name="msapplication-TileImage" content="/mstile-144x144.png" />
        <meta name="msapplication-square70x70logo" content="/mstile-70x70.png" />
        <meta name="msapplication-square150x150logo" content="/mstile-150x150.png" />
        <meta name="msapplication-wide310x150logo" content="/mstile-310x150.png" />
        <meta name="msapplication-square310x310logo" content="/mstile-310x310.png" />
        <!--  /favicons-->

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        {!! Html::style('css/style.css') !!}

  <!-- Basic example style for a 100% view -->
  <style type="text/css">
    body{
      height: 100%;
      padding: 0;
      margin: 0;
      background-color: #fff;
    }
    div#viewer{
      height: 100%;
      min-height: 100%;
      width: 100%;
      position: absolute;
      top: 0;
      left: 0;
      margin: 0;
      padding: 0;
    }
  </style>

 </head>

 <body id="zoomed">
   <div id="viewer"></div>

   <div id="toolbarDiv" class="autohide">
            <a id="zoom-in" href="#zoom-in" title="zoom in"><i class="fa fa-plus"></i></a> 
            <a id="zoom-out" href="#zoom-out" title="zoom out"><i class="fa fa-minus"></i></a>
            <a id="home" href="#home" title="zoom to fit"><i class="fa fa-home"></i></a> 
            <a id="full-page" href="#full-page" title="zobraz fullscreen"><i class="fa fa-expand"></i></a> 
            @if ($related_items)
              <a id="previous" href="#previous" title="predchádzajúce súvisiace dielo"><i class="fa fa-arrow-left"></i></a> 
              <a id="next" href="#next" title="nasledujúce súvisiace dielo"><i class="fa fa-arrow-right"></i></a> 
            @endif
   </div>
   @if (!Request::has('noreturn'))
     <a class="btn btn-default btn-outline return" href="{!! $item->getUrl() !!}" role="button"><i class="fa fa-arrow-left"></i> {{ trans('general.back') }}</a>
   @endif

    @if ($related_items)
      <div class="autohide"><div class="currentpage"><span id="index">{!! array_search($item->iipimg_url, $related_items ) + 1 !!}</span> / {!! count($related_items) !!}</div></div>
    @endif

    <div class="credit">
      @if ($item->isFree())
        <img alt="Creative Commons License" style="height: 20px; width: auto; vertical-align: bottom;" src="/images/license/zero.svg">
         {{ trans('general.public_domain') }}
      @else
        &copy; {!! $item->gallery !!}
      @endif
    </div>

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
   {!! Html::script('js/openseadragon.js') !!}

   <script type="text/javascript">
   $("document").ready(function()
   {

     var server = '/fcgi-bin/iipsrv.fcgi';
     var image = '{!! $item->iipimg_url !!}';
     var initial = {!! ($related_items) ? array_search($item->iipimg_url, $related_items ) : 0!!};

     var images = [
     @foreach ($related_items as $url)
         '/fcgi-bin/iipsrv.fcgi?DeepZoom={!! $url !!}.dzi',
     @endforeach
     ];

     var pocet = {!! count($related_items) !!};

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
          if (viewer.currentPage() < pocet) {
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

          // if (deltaX > treshold || deltaY > treshold) {
          //    getPreviousPage();
          // }
          // else if (deltaX < -treshold || deltaY < -treshold) {
          //    getNextPage();
          // }
    };

     viewer = OpenSeadragon({
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
       // navigatorPosition: "ABSOLUTE",
       // navigatorTop:      "40px",
       // navigatorRight:     "10px",
       // navigatorHeight:   "120px",
       // navigatorWidth:    "145px",
       // tileSources: server + "?DeepZoom=" + image + ".dzi",
       visibilityRatio: 1,
       minZoomLevel: 0,
       defaultZoomLevel: 0,
       autoResize: false,
    @if (empty($related_items))
      tileSources: server + "?DeepZoom=" + image + ".dzi"
    @else
       tileSources: images,
       autoHideControls:       false,
       controlsFadeDelay:       1000,  //ZOOM/HOME/FULL/SEQUENCE
       controlsFadeLength:      500,  //ZOOM/HOME/FULL/SEQUENCE
       sequenceMode: true,
       showReferenceStrip: true,
       referenceStripSizeRatio: 0.07,
       referenceStripScroll: 'vertical',
       initialPage: initial
       // panHorizontal: false,
       // panVertical: false
    @endif
     });

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


     document.oncontextmenu = function() {$('#zoom-out').click(); return false;};

     // $(document).dblclick(function() {
     //   viewer.viewport.goHome();
     // });

     $(document).mousedown(function(e){ 
      if( e.button == 2 ) { 
        viewer.viewport.zoomBy(0.45); //0.9 * 0.5
        return false; 
      } 
      return true; 
     }); 

     $('a.return').click(function(){
            if (document.referrer.split('/')[2] === window.location.host) {
              parent.history.back();
              return false;
            } else {
              window.location.href = '{!! $item->getUrl() !!}'; 
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

     // skryvanie pri neaktivite 

     var interval = 1;
     var timeoutval = 3; //3 sekund

     setInterval(function(){
        if(interval == timeoutval){
            $('.autohide, .referencestrip').fadeOut(); 
            interval = 1;
        }
        interval = interval+1;
     },1000);

     $(document).bind('mousemove keydown', function() {
         $('.autohide, .referencestrip').fadeIn();
         interval = 1;
     });

     viewer.addHandler('canvas-click', function (event) {
         $('.autohide, .referencestrip').fadeIn();
        interval = 1;
     });

     viewer.addHandler('canvas-drag-end', changePage(event) );

     viewer.addHandler('zoom', function () {
         if (!isLoaded) return;
  
         if (viewer.viewport.getZoom() > viewer.viewport.getHomeZoom() ) {
            // viewer.panHorizontal = true;
            viewer.removeHandler('canvas-drag-end');
         } else {
            // viewer.panHorizontal = false;
            viewer.addHandler('canvas-drag-end', changePage(event) );
         }
     });


     viewer.addHandler('tile-drawn', function () {
            isLoaded = true;
            
           });

    $( window ).resize(function() {
        shortenCopyright();
    });

    shortenCopyright();


   });

   </script>


 </body>

</html>

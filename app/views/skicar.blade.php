<!DOCTYPE html>
<html lang="sk">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="lab.SNG">
        <meta name="robots" content="noindex, nofollow">

        <title>
            {{ $item->title }} | zoom
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

        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        {{ HTML::style('css/style.css') }}

  <!-- Basic example style for a 100% view -->
  <style type="text/css">
    body{
      height: 100%;
      padding: 0;
      margin: 0;
      background-color: #000;
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
   <div id="toolbarDiv">
            <a id="zoom-in" href="#zoom-in" title="Zoom in"><i class="fa fa-plus"></i></a> 
            <a id="zoom-out" href="#zoom-out" title="Zoom out"><i class="fa fa-minus"></i></a>
            <a id="home" href="#home" title="Go home"><i class="fa fa-home"></i></a> 
            <a id="full-page" href="#full-page" title="Toggle full page"><i class="fa fa-expand"></i></a> 
   </div>
   <a class="btn btn-default btn-outline return" href="{{ URL::previous() }}" role="button"><i class="fa fa-arrow-left"></i> naspäť</a>    
    <div class="credit">aktualna_strana / celkovy_pocet</div>

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
   {{ HTML::script('js/openseadragon.js') }}

   <script type="text/javascript">
   $("document").ready(function()
   {
     var server = '/fcgi-bin/iipsrv.fcgi';
     var image = '{{ $item->iipimg_url }}';

    <?php $related_tems = Item::where('related_work', '=', $item->related_work)->where('author', '=', $item->author)->orderBy('related_work_order')->get() ?>

    var images = [
    @foreach ($related_tems as $item)
        '/fcgi-bin/iipsrv.fcgi?DeepZoom={{ $item->iipimg_url }}.dzi',
    @endforeach
    ];

     

     viewer = OpenSeadragon({
       id: "viewer",
       prefixUrl: "/images/openseadragon/",
       toolbar:        "toolbarDiv",
       zoomInButton:   "zoom-in",
       zoomOutButton:  "zoom-out",
       homeButton:     "home",
       fullPageButton: "full-page",
       // nextButton:     "next",
       // previousButton: "previous",
       showNavigator:  false,
       // navigatorPosition: "ABSOLUTE",
       // navigatorTop:      "40px",
       // navigatorRight:     "10px",
       // navigatorHeight:   "120px",
       // navigatorWidth:    "145px",
       // tileSources: server + "?DeepZoom=" + image + ".dzi",
       tileSources: images,
       visibilityRatio: 1,
       minZoomLevel: 1,
       defaultZoomLevel: 0,
    // collectionMode:       true,
    // collectionRows:       1, 
    // collectionTileSize:   1024,
    // collectionTileMargin: 256
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
            parent.history.back();
            return false;
      });

   });

   </script>


 </body>

</html>

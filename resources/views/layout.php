<!DOCTYPE HTML>
<head>
	<!-- ========= 
 	Title and Metas 
	========= -->
	<meta charset="utf-8">
	<title><?php echo Section::yield('title') ?></title>
    <meta name="keywords" content="webumenia, sng">
	<meta name="author" content="Slovenská národná galéria">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- ========= 
	Favicons 
    ========= -->
    <link rel="shortcut icon" href="images/favicon.png">
	
    <!-- ========= 
    Fonts 
    ========= -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" type="text/css" href="css/magnific-popup.css" />

    
    <!-- ========= 
  	CSS
	========= -->
    <link rel="stylesheet" href="css/base.css">
	<link rel="stylesheet" href="css/skeleton.css">
	<link rel="stylesheet" href="css/style.css">
    <!-- Media Queries -->  
    <link rel="stylesheet" href="css/media.css">
	
    <!-- ========= 
  	JS
	========= -->
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>  
    <script type="text/javascript" src="http://code.jquery.com/ui/jquery-ui-git.js"></script>
    <!-- Sticky Nav -->
    
  	<script>
		$(window).scroll(function(){
            if ($(this).scrollTop() > 650) {
                $('nav').slideDown();
    			
            } else {
                $('nav').slideUp();
    			
            }
   		}); 	 
  	</script>

    <!-- accordion -->
	<script type="text/javascript" src="js/zebra_accordion.js"></script>

	<!-- custom jquery -->
	<script type="text/javascript" src="js/graphicgeeks.js"></script> 
    <script src="js/modernizr.custom.js"></script>
    <!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-19030232-2', 'sng.sk');
      ga('send', 'pageview');

    </script>
    
</head>

<body>
    
	<!-- START OF DIV -->  
	<div class="page" >

            <!-- NAVIGATION -->
            <nav>
                <div class="container">
                    <div class="sixteen columns">
                        <!-- NAVIGATION - list -->  
                        <ul id="onepagenav">
                            <li><a href="#homepage">Domov</a></li>
                            <!-- <li><a href="#about">O webeumenia</a></li> -->
                        </ul>   
                        <!-- NAVIGATION - list -->  
                    </div>
                </div>
            </nav>
            <!-- NAVIGATION -->
        	
            
            <!-- MODULE : HOMEPAGE -->
			<section id="homepage">
				<div class="container">
                    <div class="sixteen columns">
                        <!-- HOMEPAGE - bigtext -->
                        <div class="home-bigtext">
                            <h1 class="intro-nadpis">Web umenia</h1>
                            <h2>Test 0.1</h2>
                        </div>
                        <!-- HOMEPAGE - bigtext -->                     
					</div>
				</div>
			</section>
			<!-- MODULE : HOMEPAGE -->
                        

            <?php echo $content; ?>

            <!-- MODULE : FOOTER -->
            <footer id="download">
            	<div class="footer-arrow"></div>
                <div class="container">
                    <div class="sixteen columns footer-inside">
                        <h4>SNG Lab</h4>
                    </div>
                </div>
            </footer>
            <!-- MODULE : FOOTER -->      
		<!-- </div> -->
	</div>    
	<!-- END OF DIV -->    
		
	<!-- ========= 
  	JS
	========= -->
	
    <!-- easing lib -->
    <script src="js/jquery.easing.min.js"></script>


    <script src="js/jquery.scrollTo.js"></script>
    <!-- One page Nav -->
    <script src="js/jquery.nav.js"></script>
    <script>
    $(document).ready(function() {
        $('.page').onePageNav({
            changeHash: false,
            filter: ':not(.external)',
            scrollOffset: 0,
        });
    });
    </script>

    <!-- magnific-popup  -->
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script>
    $(document).ready(function() {

        $('.grid').magnificPopup({
          delegate: 'a',
          type: 'image',
          closeOnContentClick: false,
          closeBtnInside: false,
          mainClass: 'mfp-with-zoom mfp-img-mobile',
          fixedContentPos: false,
          image: {
            verticalFit: true,
            titleSrc: function(item) {
              return item.el.attr('title') + ' &middot; <a class="image-source-link" href="'+item.el.attr('data-source')+'" target="_blank">webumenia</a>';
            }
          },
          gallery: {
            enabled: true
          },
          zoom: {
            enabled: true,
            duration: 300, // don't foget to change the duration also in CSS
            opener: function(element) {
              return element.find('img');
            }
          }
        });
    });
    

    </script>

    <!-- Retina Display -->
    <script src="js/retina.js"></script>        

</body>
</html>
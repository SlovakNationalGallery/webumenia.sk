<?php
/*
public String getImagePath(final String workArtId, final String imgFileName) {
        Validate.notEmpty(workArtId);
        // to get rid of prohibited characters from filename
        String transformedWorkArtID = String.valueOf(workArtId.hashCode());
        final int workArtIdInt = Math.abs(Integer.parseInt(transformedWorkArtID));
        // detect images directory location by workArtId
        final Integer[] dirsInLevels = new Integer[levels];
        int tmpValue = workArtIdInt;
        for (int i = 0; i < dirsInLevels.length; i++) {
            dirsInLevels[i] = tmpValue % dirsPerLevel;
            tmpValue = tmpValue / dirsPerLevel;
        }
        File tmpDir = new File(imagesRootPath);
        for (Integer dirsInLevel : dirsInLevels) {
            tmpDir = new File(tmpDir, String.valueOf(dirsInLevel));
        }
        //FIXME: adresar obrazkov workartu sa bude volat presne ako workArtID, kde je ':' nahradena '_'
        tmpDir = new File(tmpDir, workArtId.replaceAll(":", "_"));
        // append filename to workArt image directory location
        LOG.debug("Generated imagePath=" + new File(tmpDir, imgFileName).getAbsolutePath());
        return new File(tmpDir, imgFileName).getAbsolutePath();
    }

*/

define("IMAGES_DIR", "images/");
define("ARTWORKS_DIR", "diela/");

function intval32bits($value)
{
    $value = ($value & 0xFFFFFFFF);

    if ($value & 0x80000000)
        $value = -((~$value & 0xFFFFFFFF) + 1);

    return $value;
}

/**
* Same as java String.hashcode()
*/
function hashcode($s) {
    $len = strLen($s);
    $sum = 0;
    for ($i = 0; $i < $len; $i++) {
        $char = ord($s[$i]);
        $sum = (($sum<<5)-$sum)+$char;
        $sum = $sum & 0xffffffff; // Convert to 32bit integer
    }

    return $sum;
}


function getImagePath($workArtId, $full=false) {
    // $workArtId = "this: "; // 110331228 -> 3420268100
	$levels = 3;
    $dirsPerLevel = 100 ;
    $transformedWorkArtID = hashcode($workArtId);
	$workArtIdInt = abs(intval32bits($transformedWorkArtID ));
    $tmpValue = $workArtIdInt;
    $dirsInLevels = array();

    for ($i = 0; $i < $levels; $i++) {
            $dirsInLevels[$i] = $tmpValue % $dirsPerLevel;
            $tmpValue = $tmpValue / $dirsPerLevel;
    }

    $path = implode("/", $dirsInLevels);

	$trans = array(":" => "_", " " => "_");
    $file = strtr($workArtId, $trans);

    $image_suffix= ($full) ? '' : '-th1';
    $result_path = IMAGES_DIR . ARTWORKS_DIR . "$path/$file/$file$image_suffix.jpeg";
    if (!file_exists( $result_path)) $result_path = IMAGES_DIR . '/no-image'.$image_suffix.'.jpeg';
	return $result_path;

}
 ?>

<!DOCTYPE HTML>
<head>
	<!-- =========
 	Title and Metas
	========= -->
	<meta charset="utf-8">
	<title>Webumenia.test</title>
    <meta name="keywords" content="sng, mednansky, mednyanzsky, strážky">
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
                            <li><a href="#about">O webeumenia</a></li>
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


            <!-- MODULE : OBRAZKY -->
            <section id="painting" class="section-container">
                <div class="container">

                    <div class="two columns">
                        &nbsp;
                    </div>
					<form action="/" method="POST">
                    <div class="ten columns">
                        <div class="field">
                            <input type="text" name="name" id="name" class="text-label left">
                        </div>
                    </div>
                    <div class="two columns">
                        <div class="field">
	                        <button id="send" class="button styled right">hľadať</button>
                        </div>
                    </div>
					</form>
                    <div class="two columns">
                        &nbsp;
                    </div>

                    <div class="clear"></div>

                    <div class="sixteen columns">
                        <p>Počet nádených diel: <?php echo $data->response->numFound; ?></p>

	                    <?php /* ?>
                        <dl class="Zebra_Accordion" id="Zebra_Accordion1">
	                        <dt style="margin-top:0px;">Zobraziť odpoveť</dt>
	                        <dd>
	                            <pre><?php print_r($data); ?></pre>
	                        </dd>
	                    </dl>
                        <?php */ ?>



                    <ul class="grid cs-style-3">
                    	<?php foreach ($data->response->docs as $key => $item) { ?>
                        <!-- dielo-->

                        <a href="<?php echo getImagePath($item->id, true); ?>"  title="<?php echo $item->firstAuthor .' : '. $item->ti[0] ?>">
                        <li class="painting">
                            <figure>
                                <img src="<?php echo getImagePath($item->id); ?>" alt="painting"/>
                                <figcaption>
                                    <h5 class="white"><?php echo $item->firstAuthor ?></h5>
                                    <span><?php echo $item->ti[0] ?></span>
                                </figcaption>
                            </figure>
                        </li>
                        </a>

                        <!-- /dielo-->
                    	<?php } ?>
                    </ul>

                     </div>

                </div>
            </section>
            <!-- MODULE : OBRAZKY -->


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
    <script src="js/jquery.row-grid.min.js"></script>
    <script>
    $(document).ready(function() {
        $(".grid").rowGrid({itemSelector: ".painting", minMargin: 10, maxMargin: 25, firstItemClass: "first-item"});
    });

    $(window).scroll(function() {
         if($(window).scrollTop() + $(window).height() == $(document).height()) {
            $(".container").append("<div class='item'><img src='path/to/image' width='140' height='100' /></div>");
            $(".container").rowGrid("appended");
         }
    });

    </script>

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

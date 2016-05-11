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


function getImagePath($workArtId) {
    // $workArtId = "this is tutorialspoint";
    $levels = 3;
    $dirsPerLevel = 100 ;
    $transformedWorkArtID = hashcode($workArtId);
    $workArtIdInt = abs($transformedWorkArtID & 0xffffffff);
    $tmpValue = $workArtIdInt;
    $dirsInLevels = array();

    for ($i = 0; $i < $levels; $i++) {
            $dirsInLevels[$i] = $tmpValue % $dirsPerLevel;
            $tmpValue = $tmpValue / $dirsPerLevel;
        }
    print_r($dirsInLevels);
    //this is tutorialspoint = 643938959
    echo "$workArtId : $workArtIdInt";die();

    // return 'totojecesta';
    $trans = array(":" => "_", " " => "_");
    return strtr($workArtId, $trans);
     
}
 ?>

 <?php Section::start('title'); ?>
    Web Umenia : OAI-PMH test
<?php Section::stop(); ?>


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

	                    <dl class="Zebra_Accordion" id="Zebra_Accordion1">
	                        <dt style="margin-top:0px;">Zobraziť odpoveť</dt>
	                        <dd>
	                            <pre><?php print_r($data); ?></pre>
	                        </dd>
	                    </dl>

                    </div>

                    <ul class="grid cs-style-3">
                    	<?php foreach ($data->response->docs as $key => $item) { ?>
                        <!-- dielo-->
                        <?php  echo  getImagePath($item->id); ?>
                        <a href="images/diela2/<?php echo getImagePath($item->id); ?>.jpeg" data-source="http://webumenia.sk/web/guest/detail/-/detail/id/SVK:SNG.O_754/" title="<?php echo $item->firstAuthor .' : '. $item->ti[0] ?>">
                        <li class="four columns painting ">
                            <figure>
                                <img src="images/diela2/<?php echo getImagePath($item->id); ?>.jpeg" alt="painting"/>
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
            </section>
            <!-- MODULE : OBRAZKY -->
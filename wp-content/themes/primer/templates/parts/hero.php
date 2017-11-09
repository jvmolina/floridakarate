<?php
/**
 * Displays the site header.
 *
 * @package Primer
 * @since   1.0.0
 */

?>
<style>

.no-video .hero video,
.touch .hero video {
  display: none;
}
.no-video .hero .poster,
.touch .hero .poster {
  display: block !important;
}
.hero {
  position: relative;
  bottom: 0%;
  left: 0%;
  height: 100%;
  width: 100%;
  overflow: hidden;
  background: #000;
width: 100%;
padding-top: 100px;
-webkit-background-size: cover;
background-size: cover;
background-position: center;
background-repeat: no-repeat;
}
.hero .poster img {
  width: 100%;
  bottom: 0;
  position: absolute;
}
.hero .filter {
  z-index: 100;
  position: absolute;
  background: rgba(0, 0, 0, 0.4);
  width: 100%;
}
.hero .title-container {
  z-index: 1000;
  position: absolute;
  top: 35%;
  width: 100%;
  color: #fff;
}
.hero .description {
  font-size: 1em;
  width: 45%;

  left:130px;
}
.hero .link {
  position: absolute;
  bottom: 3em;
  width: 100%;
  text-align: center;
  z-index: 1001;
  font-size: 2em;
  color: #fff;
}
.hero .link a {
  color: #fff;
}
.hero video {
  position: absolute;
  z-index: 0;
  bottom: 0;
}
.hero video.fillWidth {
  width: 100%;
}
</style>
<div class="hero" >
 <video autoplay loop class="fillWidth">
  <source src="http://www.planexitoso.com/wp-content/uploads/2017/11/VideoBackgroundProExample.mp4" type="video/mp4" />Your browser does not support the video tag. I suggest you upgrade your browser.</video>
        <div class="poster hidden">
            <img src="http://www.videojs.com/img/poster.jpg" alt="">
        </div>

             <div class="title-container">
            <div class="headline" style="text-align:center">   	
            <img src="/wordpress/wp-content/uploads/2017/11/logo-nav.png" alt="">
	<?php

	/**
	 * Fires inside the `.hero` element but before the `.hero-inner` element.
	 *
	 * @since 1.7.0
	 */
	do_action( 'primer_pre_hero' );

	?>

		</div>
	<div class="description" style="margin-left:50px;"> 
   
<!--<video src="https://testdrive-archive.azurewebsites.net/Graphics/VideoFormatSupport/big_buck_bunny_trailer_480p_high.mp4" controls="" preload="metadata" onerror="videoFail(this)">
			<div class="sorry noIE9">Your browser cannot play WebM content with the HTML5 video element.</div>
			<div class="sorry installWebM" style="display: none; vertical-align: middle; text-align: center;"><a href="http://www.webmproject.org/ie/" target="_blank"><img style="border: none" alt="Install WebM support from webmproject.org" src="Install-WebM-Support.png"></a></div>
			</video>-->
		<?php

		/**
		 * Fires inside the `.hero` element.
		 *
		 * @hooked primer_add_hero_content - 10
		 *
		 * @since 1.0.0
		 */
		do_action( 'primer_hero' );

		?>


       	</div>
</div>
</div>
<script>

( function( $ ) {

/** Document Ready Functions **/
/********************************************************************/

$( document ).ready(function() {

    // Resive video
    scaleVideoContainer();

    initBannerVideoSize('.hero .poster img');
    initBannerVideoSize('.hero .filter');
    initBannerVideoSize('.hero video');
        
    $(window).on('resize', function() {
        scaleVideoContainer();
        scaleBannerVideoSize('.hero .poster img');
        scaleBannerVideoSize('.hero .filter');
        scaleBannerVideoSize('.hero video');
    });

});

/** Reusable Functions **/
/********************************************************************/

function scaleVideoContainer() {

    var height = $(window).height();
    var unitHeight = parseInt(height) + 'px';
    $('.hero').css('height',unitHeight);

}

function initBannerVideoSize(element){
    
    $(element).each(function(){
        $(this).data('height', $(this).height());
        $(this).data('width', $(this).width());
    });

    //scaleBannerVideoSize(element);

}

function scaleBannerVideoSize(element){

    var windowWidth = $(window).width(),
        windowHeight = $(window).height(),
        videoWidth,
        videoHeight;
    
    console.log(windowHeight);

    $(element).each(function(){
        var videoAspectRatio = $(this).data('height')/$(this).data('width'),
            windowAspectRatio = windowHeight/windowWidth;

        if (videoAspectRatio > windowAspectRatio) {
            videoWidth = windowWidth;
            videoHeight = videoWidth * videoAspectRatio;
            $(this).css({'top' : -(videoHeight - windowHeight) / 2 + 'px', 'margin-left' : 0});
        } else {
            videoHeight = windowHeight;
            videoWidth = videoHeight / videoAspectRatio;
            $(this).css({'margin-top' : 0, 'margin-left' : -(videoWidth - windowWidth) / 2 + 'px'});
        }

        $(this).width(videoWidth).height(videoHeight);

        $('.hero video').addClass('fadeIn animated');
        

    });
}

} )( jQuery );			

	
</script>

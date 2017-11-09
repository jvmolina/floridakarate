<style>

.no-video .overlay-layer-wrap video,
.touch .video-container video {
  display: none;
}
.no-video .overlay-layer-wrap .poster,
.touch .video-container .poster {
  display: block !important;
}

.overlay-layer-wrap .poster img {
  width: 100%;
  bottom: 0;
  position: absolute;
}
.overlay-layer-wrap .filter {
  z-index: 100;
  position: absolute;
  background: rgba(0, 0, 0, 0.4);
  width: 100%;
}
.overlay-layer-wrap .title-container {
  z-index: 1000;
  position: absolute;
  top: 15%;
  width: 100%;
  text-align: center;
  color: #fff;
}
.overlay-layer-wrap .description .inner {
  font-size: 1em;
  width: 45%;
  margin: 0 auto;
}
.overlay-layer-wrap .link {
  position: absolute;
  bottom: 3em;
  width: 100%;
  text-align: center;
  z-index: 1001;
  font-size: 2em;
  color: #fff;
}
.overlay-layer-wrap .link a {
  color: #fff;
}
.overlay-layer-wrap video {
  position: absolute;
  z-index: 0;
  bottom: 0;
}
.overlay-layer-wrap video.fillWidth {
  width: 100%;
  height:100%;
}
</style>
<?php
/**
 * Header section
 *
 * @package parallax-one
 */

$paralax_one_header_logo = get_theme_mod( 'paralax_one_header_logo', parallax_get_file( '/images/logo-2.png' ) );
$paralax_one_header_logo = apply_filters( 'parallax_one_translate_single_string', $paralax_one_header_logo, 'Big Title Section' );

$parallax_one_header_title = get_theme_mod( 'parallax_one_header_title',esc_html__( 'Simple, Reliable and Awesome.','parallax-one' ) );
$parallax_one_header_title = apply_filters( 'parallax_one_translate_single_string', $parallax_one_header_title, 'Big Title Section' );

$parallax_one_header_subtitle = get_theme_mod( 'parallax_one_header_subtitle','Lorem ipsum dolor sit amet, consectetur adipiscing elit.' );
$parallax_one_header_subtitle = apply_filters( 'parallax_one_translate_single_string', $parallax_one_header_subtitle, 'Big Title Section' );

$parallax_one_header_button_text = get_theme_mod( 'parallax_one_header_button_text',esc_html__( 'GET STARTED','parallax-one' ) );
$parallax_one_header_button_text = apply_filters( 'parallax_one_translate_single_string', $parallax_one_header_button_text, 'Big Title Section' );

$parallax_one_header_button_link = get_theme_mod( 'parallax_one_header_button_link','#' );
$parallax_one_header_button_link = apply_filters( 'parallax_one_translate_single_string', $parallax_one_header_button_link, 'Big Title Section' );
if ( ! empty( $parallax_one_header_button_link ) && strpos( $parallax_one_header_button_link, '#' ) === 0 ) {
	$parallax_one_go_to = 'onclick="return false;" data-anchor="' . esc_attr( $parallax_one_header_button_link ) . '"';
} else {
	$parallax_one_go_to = 'onclick="parent.location=\'' . esc_url( $parallax_one_header_button_link ) . '\'" data-anchor=""';
}

$parallax_one_enable_move = get_theme_mod( 'paralax_one_enable_move' );
$parallax_one_first_layer = get_theme_mod( 'paralax_one_first_layer', parallax_get_file( '/images/background1.png' ) );
$parallax_one_first_layer = apply_filters( 'parallax_one_translate_single_string', $parallax_one_first_layer, 'Big Title Section' );

$parallax_one_second_layer = get_theme_mod( 'paralax_one_second_layer',parallax_get_file( '/images/background2.png' ) );
$parallax_one_second_layer = apply_filters( 'parallax_one_translate_single_string', $parallax_one_second_layer, 'Big Title Section' );


if ( ! empty( $paralax_one_header_logo ) || ! empty( $parallax_one_header_title ) || ! empty( $parallax_one_header_subtitle ) || ! empty( $parallax_one_header_button_text ) ) {

	if ( ! empty( $parallax_one_enable_move ) && $parallax_one_enable_move ) {

		echo '<ul id="parallax_move">';


		

		echo '</ul>';

	} ?>

	
	<div class="overlay-layer-wrap">
		  <video autoplay loop class="fillWidth">
  <source src="http://www.planexitoso.com/wp-content/uploads/2017/11/VideoBackgroundProExample.mp4" type="video/mp4" />
 <source src="/wordpress/wp-content/uploads/2017/11/VideoBackgroundProExample.webm" type="video/webm" />Your browser does not support the video tag. I suggest you upgrade your browser.</video>
        <div class="poster hidden">
            <img src="http://www.videojs.com/img/poster.jpg" alt="">
        </div>
		<div class="container overlay-layer" id="parallax_header">

      
  
			<!-- ONLY LOGO ON HEADER -->
			<?php
			if ( ! empty( $paralax_one_header_logo ) ) {
				echo '<div class="only-logo"><div id="only-logo-inner" class="navbar"><div id="parallax_only_logo" class="navbar-header"><img src="' . esc_url( $paralax_one_header_logo ) . '" alt="' . get_bloginfo( 'title' ) . '"></div></div></div>';
			} elseif ( is_customize_preview() ) {
				echo '<div class="only-logo"><div id="only-logo-inner" class="navbar"><div id="parallax_only_logo" class="navbar-header"><img src="" alt="' . get_bloginfo( 'title' ) . '"></div></div></div>';
			}
			?>
			<!-- /END ONLY LOGO ON HEADER -->

			<div class="row">
				<div class="col-md-12 intro-section-text-wrap">

					<!-- HEADING AND BUTTONS -->
					<?php
					if ( ! empty( $paralax_one_header_logo ) || ! empty( $parallax_one_header_title ) || ! empty( $parallax_one_header_subtitle ) || ! empty( $parallax_one_header_button_text ) ) {
					?>
						<div id="intro-section" class="intro-section">

							<!-- WELCOM MESSAGE -->
							<?php
							if ( ! empty( $parallax_one_header_title ) ) {
								echo '<h2 id="intro_section_text_1" class="intro white-text">' . wp_kses_post( $parallax_one_header_title ) . '</h2>';
							} elseif ( is_customize_preview() ) {
								echo '<h2 id="intro_section_text_1" class="intro white-text paralax_one_only_customizer"></h2>';
							}

							if ( ! empty( $parallax_one_header_subtitle ) ) {
								echo '<h5 id="intro_section_text_2" class="white-text">' . wp_kses_post( $parallax_one_header_subtitle ) . '</h5>';
							} elseif ( is_customize_preview() ) {
								echo '<h5 id="intro_section_text_2" class="white-text paralax_one_only_customizer"></h5>';
							}
							?>

							<!-- BUTTON -->
							<?php

							if ( ! empty( $parallax_one_header_button_text ) ) {
							?>
								<button 
								<?php
								if ( ! empty( $parallax_one_go_to ) ) {
									echo $parallax_one_go_to; }
?>
 class="btn btn-danger standard-button inpage-scroll inpage_scroll_btn">
									<span class="screen-reader-text"><?php echo esc_html__( 'Header button label:','parallax-one' ) . strip_tags( trim( $parallax_one_header_button_text ) ); ?></span>
									<?php echo wp_kses_post( $parallax_one_header_button_text ); ?>
								</button>
								<?php
							} elseif ( is_customize_preview() ) {
							?>
								<button class="btn btn-primary standard-button inpage-scroll inpage_scroll_btn paralax_one_only_customizer"  
								<?php
								if ( ! empty( $parallax_one_go_to ) ) {
									echo $parallax_one_go_to; }
?>
></button>
								<?php
							}
							?>
							<!-- /END BUTTON -->

						</div>
						<!-- /END HEADNING AND BUTTONS -->
						<?php
					} // End if().
					?>
				</div>
			</div>
		</div>
		<?php parallax_hook_heading_bottom(); ?>
	</div>
	<?php parallax_hook_heading_after(); ?>
	<?php
} // End if(). ?>
<script>

( function( $ ) {

/** Document Ready Functions **/
/********************************************************************/

$( document ).ready(function() {

    // Resive video
    scaleVideoContainer();

    initBannerVideoSize('.overlay-layer-wrap .poster img');
    initBannerVideoSize('.overlay-layer-wrap .filter');
    initBannerVideoSize('.overlay-layer-wrap video');
        
    $(window).on('resize', function() {
        scaleVideoContainer();
        scaleBannerVideoSize('.overlay-layer-wrap .poster img');
        scaleBannerVideoSize('.overlay-layer-wrap .filter');
        scaleBannerVideoSize('.overlay-layer-wrap video');
    });

});

/** Reusable Functions **/
/********************************************************************/

function scaleVideoContainer() {

    var height = $(window).height();
    var unitHeight = parseInt(height) + 'px';
    $('.overlay-layer-wrap').css('height',unitHeight);

}

function initBannerVideoSize(element){
    
    $(element).each(function(){
        $(this).data('height', $(this).height());
        $(this).data('width', $(this).width());
    });

    scaleBannerVideoSize(element);

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

        $('.overlay-layer-wrap video').addClass('fadeIn animated');
        

    });
}

} )( jQuery );			

	
</script>

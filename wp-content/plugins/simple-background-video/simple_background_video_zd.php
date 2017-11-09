<?php
/*
Plugin Name: Simple Background Video
Plugin URI: http://www.zuhaitz-design.com
Description: Put a title and a responsive background video as a header in any page. Your video could be from Youtube, Vimeo or MP4!
Version: 1.4.4
Author: Willy Muñoz
Author URI: http://www.zuhaitz-design.com
License: GPL2

Copyright 2016 Willy Muñoz  (email: willymunozpiano@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// Call function when plugin is activated
register_activation_hook( __FILE__, 'BVZD_install' );

function BVZD_install() {
	
    //setup default option values
    $BVZD_options_arr = array(
        'currency_sign' => '$',
        'title_select' => 'title-page'
    );

    //save our default option values
    update_option( 'BVZD_options', $BVZD_options_arr );
	
}


// Action hook to add the post products menu item
add_action( 'admin_menu', 'BVZD_menu' );

//create the sub-menu
function BVZD_menu() {
	
    add_options_page( __( 'Simple Background Video Settings Page', 'BVZD-plugin' ), __( 'Simple Background Video Settings', 'BVZD-plugin' ), 'manage_options', 'BVZD-settings', 'BVZD_settings_page' );
	
}

//build the plugin settings page
function BVZD_settings_page() {
	
    //load the plugin options array
    $BVZD_options_arr = get_option( 'BVZD_options' );
    $option_home = get_option( 'page_on_front' );
	
	//set the option array values to variables
    $zd_video_yvn = ( ! empty( $BVZD_options_arr['video_yvn'] ) ) ? $BVZD_options_arr['video_yvn'] : '';
    $video_web = ( ! empty( $BVZD_options_arr['video_web'] ) ) ? $BVZD_options_arr['video_web'] : '';
    $video_mp4 = ( ! empty( $BVZD_options_arr['video_mp4'] ) ) ? $BVZD_options_arr['video_mp4'] : '';
    $title_select_BGZD = ( ! empty( $BVZD_options_arr['title_select'] ) ) ? $BVZD_options_arr['title_select'] : '';
    $title_input_BGZD = ( ! empty( $BVZD_options_arr['title_input'] ) ) ? $BVZD_options_arr['title_input'] : '';
    $title_color_BGZD = ( ! empty( $BVZD_options_arr['color_title'] ) ) ? $BVZD_options_arr['color_title'] : '';
    $image_sn = ( ! empty( $BVZD_options_arr['image_sn'] ) ) ? $BVZD_options_arr['image_sn'] : '';
    $image_mob = ( ! empty( $BVZD_options_arr['image_mob'] ) ) ? $BVZD_options_arr['image_mob'] : '';
    $without_sound = ( ! empty( $BVZD_options_arr['no_sound_ZD'] ) ) ? $BVZD_options_arr['no_sound_ZD'] : '';

    ?>


    <div class="wrap" id="metabox_url_video">

        <h2><?php _e( 'Simple Background Video in Home', 'BVZD-plugin' ) ?></h2>
        <?php
        if (!empty($option_home)) {
            ?>
            <span class="video_ZD" style="color: red;">
                Be careful! In the example page, the videos are only modified when "your latest posts" is activated in "Setting->Reading". You have currently activated the "A Static Page-> <a href="<?php echo get_edit_post_link($option_home); ?>"><?php echo get_the_title($option_home); ?></a>" in "Setting->Reading", and you should modify the Home Background Video <a href="<?php echo get_edit_post_link($option_home); ?>">here</a>.
            </span><br>
        <?php
        }
        ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'BVZD-settings-group' ); ?>
            <span class="video_ZD">Video at HOME:</span>
            <div class="help_y" <?php if ($zd_video_yvn!="youtube" && $zd_video_yvn!="vimeo") { echo ' style="display:none;"';} ?>>
                <p>ID of Video: <a class="help-video" id="">Help with the ID?</a></p>
                <div class="help-y-v">
                    <a class="help-video-y" id="<?php echo plugin_dir_url( __FILE__ ) . "images/video-y.jpg" ?>">Youtube</a>
                    <span> | </span>
                    <a class="help-video-y" id="<?php echo plugin_dir_url( __FILE__ ) . "images/video-v.jpg" ?>">Vimeo</a>
                </div> 
                <br>

                <div class="help-img">
                    <div class="back-help"></div>
                    <img src="">
                </div>
            </div>

            <select name="BVZD_options[video_yvn]" class="video-yvn">
                <option value="select" disabled selected>Select the option:</option>
                <option value="upload" <?php if($zd_video_yvn=="upload") { echo "selected"; } ?>>Upload mp4</option>
                <option value="youtube" <?php if($zd_video_yvn=="youtube") { echo "selected"; } ?>>Youtube ID</option>
                <option value="vimeo" <?php if($zd_video_yvn=="vimeo") { echo "selected"; } ?>>Vimeo ID</option>
                <option value="no-video" <?php if($zd_video_yvn=="no-video") { echo "selected"; } ?>>No Video</option>
            </select>

            <input class="artist-url_video" name="BVZD_options[video_web]" value="<?php echo esc_attr( $video_web ); ?>" <?php if($zd_video_yvn!="no-video"){ echo 'placeholder="ID of Video.."';} ?> <?php if ($zd_video_yvn!="youtube" && $zd_video_yvn!="vimeo") { echo ' style="display:none;"';} ?> /> 

            <div class="cuadro-video" <?php if ( ($zd_video_yvn=="youtube" || $zd_video_yvn=="vimeo") && !empty($video_web) ) {} else { echo ' style="display:none;"';} ?>>
                <div class="video-metabox">
                    <?php 
                        if ( $zd_video_yvn=="youtube" ) { ?>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo esc_attr( $video_web ); ?>?rel=0&amp;controls=0&loop=1&amp;showinfo=0" frameborder="0" allowfullscreen volume="0"></iframe> 
                        <?php  
                        }
                        if ( $zd_video_yvn=="vimeo" ) { ?>
                            <iframe src="https://player.vimeo.com/video/<?php echo esc_attr( $video_web ); ?>?color=be1659&title=0&byline=0&portrait=0" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                        <?php
                        }
                        ?> 
                </div>
            </div>

            


            <?php

            global $post;

            ?>

            <!-- Your image container, which can be manipulated with js -->
            <div class="custom-img-container">
                <?php if ( !empty($video_mp4) && $zd_video_yvn=="upload" ) : ?>
                    <video width="320" height="143" <?php if ($without_sound=="no") { echo "muted"; } ?>>
                      <source src="<?php echo esc_attr( $video_mp4 ); ?>" type="video/mp4">
                    </video>
                <?php endif; ?>
            </div>
            <div class="custom-img-container-photo"></div>
            <!-- Your add & remove image links -->
            <p class="hide-if-no-js selector_video" <?php if ($zd_video_yvn!="upload") { echo " style='display:none;'"; } ?>>
                <a class="upload-custom-img button-primary button-large <?php if ( !empty( $video_mp4) ) { echo 'hidden'; } ?>" 
                   href="">
                    <?php _e('Set custom video') ?>
                </a>
                <a class="delete-custom-img button-primary button-large <?php if ( empty($video_mp4) ) { echo 'hidden'; } ?>" 
                  href="#">
                    <?php _e('Remove this video') ?>
                </a>
            </p>
            <!-- A hidden input to set and post the chosen image id -->

            <span class="title_ZD">Sound: (only mp4)</span>
            <select name="BVZD_options[no_sound_ZD]" class="title-ZD-sc">
                <option <?php if ($without_sound!="no") { echo "selected"; } ?>>Yes</option>
                <option value="no" <?php if ($without_sound=="no") { echo "selected"; } ?>>No</option>
            </select>

            <input class="video-mp4" name="BVZD_options[video_mp4]" type="hidden" value="<?php echo esc_attr( $video_mp4 ); ?>" />

            <div class="title_ZD_caja">
                <span class="title_ZD">Title:</span>
                <select name="BVZD_options[title_select]" class="title-ZD">
                    <option value="title-page" <?php if($title_select_BGZD=="title-page") { echo "selected"; } ?>>Title Home</option>
                    <option value="custom-title" <?php if($title_select_BGZD=="custom-title") { echo "selected"; } ?>>Custom Title</option>
                    <option value="no" <?php if($title_select_BGZD=="no") { echo "selected"; } ?>>No</option>
                </select>
                <input class="custom-title-input" <?php if($title_select_BGZD!="custom-title") { echo "style='display: none;'"; } ?> name="BVZD_options[title_input]" value="<?php echo esc_attr( $title_input_BGZD ); ?>" placeholder="Title here" />
                <div class="hide_picker" <?php if($title_select_BGZD=="no") { echo "style='display: none;'"; } ?>>
                    <script>
                    (function( $ ) {
                        // Add Color Picker to all inputs that have 'color-field' class
                        $(function() {
                        $('.color-field').wpColorPicker();
                        });
                    })( jQuery );
                    </script>
                    <input class="color-field" type="text" name="BVZD_options[color_title]" value="<?php echo esc_attr( $title_color_BGZD ); ?>">
                </div>
            </div>

            <br>
            
            <span class="image_ZD">¿Mobile/Tablet image?</span>
            <span class="what_ZD">In mobile phone autoplay is disabled to prevent unsolicited cellular download. <br>The solution is to show a image if you want.</span>
            <select name="BVZD_options[image_sn]" class="image-sn">
                <option <?php if (empty($image_sn)) { echo "selected";} ?>>No</option>
                <option value="yes" <?php if($image_sn=="yes") { echo "selected"; } ?>>Yes</option>
            </select>

            <!-- Your image container, which can be manipulated with js -->
            <div class="custom-img-container_img" <?php if ( $image_sn!="yes" ) { echo "style='display:none;'"; }  ?>>
                <?php if ( !empty($image_mob) && !empty($image_sn) ) : ?>
                    <img src="<?php echo $image_mob ?>" alt=""/>
                <?php endif; ?>
            </div>
            <div class="custom-img-container-photo_img"></div>
            <!-- Your add & remove image links -->
            <p class="hide-if-no-js selector_image" <?php if ($image_sn!="yes") { echo " style='display:none;'"; } ?>>
                <a class="upload-custom-img_img button-primary button-large <?php if ( !empty( $image_mob ) ) { echo 'hidden'; } ?>" 
                   href="">
                    <?php _e('Set custom image') ?>
                </a>
                <a class="delete-custom-img_img button-primary button-large <?php if ( empty( $image_mob ) ) { echo 'hidden'; } ?>" 
                  href="#">
                    <?php _e('Remove this image') ?>
                </a>
            </p>
            <!-- A hidden input to set and post the chosen image id -->

            <input class="video-mp4_img" name="BVZD_options[image_mob]" type="hidden" value="<?php echo esc_attr( $image_mob ); ?>" />


            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'BVZD-plugin' ); ?>" />
            </p>

            <div class="spam">
                <hr>
                <p>
                    <?php _e( "Plugin made by ", 'PMPZD-plugin' );?><a href="http://www.zuhaitz-design.com" target="_blank">Willy Muñoz</a>
                    <br>
                    <?php _e( '¿Do you need help?', 'PMPZD-plugin' );?>
                    <br>
                    <?php _e( 'info@zuhaitz-design.com', 'PMPZD-plugin' );?>
                    <br>
                    <a href="http://www.zuhaitz-design.com" target="_blank"><?php _e( "Zuhaitz Design | Design & Code", 'PMPZD-plugin' );?></a>
                </p>
            </div>

        </form>
    </div>



<?php
}

// Action hook to register the plugin option settings
add_action( 'admin_init', 'BVZD_register_settings' );

function BVZD_register_settings() {
	
    //register the array of settings
    register_setting( 'BVZD-settings-group', 'BVZD_options', 'BVZD_sanitize_options' );
	
}

function BVZD_sanitize_options( $options ) {

    $options['video_yvn'] = ( ! empty( $options['video_yvn'] ) ) ? sanitize_text_field( $options['video_yvn'] ) : '';
    $options['video_web'] = ( ! empty( $options['video_web'] ) ) ? sanitize_text_field( $options['video_web'] ) : '';
    $options['video_mp4'] = ( ! empty( $options['video_mp4'] ) ) ? sanitize_text_field( $options['video_mp4'] ) : '';
    $options['title_select'] = ( ! empty( $options['title_select'] ) ) ? sanitize_text_field( $options['title_select'] ) : '';
    $options['title_input'] = ( ! empty( $options['title_input'] ) ) ? esc_html( $options['title_input'] ) : '';
    $options['color_title'] = ( ! empty( $options['color_title'] ) ) ? sanitize_text_field( $options['color_title'] ) : '';
    $options['image_sn'] = ( ! empty( $options['image_sn'] ) ) ? sanitize_text_field( $options['image_sn'] ) : '';
    $options['image_mob'] = ( ! empty( $options['image_mob'] ) ) ? sanitize_text_field( $options['image_mob'] ) : '';
    $options['no_sound_ZD'] = ( ! empty( $options['no_sound_ZD'] ) ) ? sanitize_text_field( $options['no_sound_ZD'] ) : '';
	
	return $options;
	
}


if (is_admin()) {

	function load_custom_wp_admin_style() {
	        wp_register_style( 'custom_wp_admin_css', plugin_dir_url( __FILE__ ) . 'css/video-artist.css', false, '1.4.3' );
	        wp_enqueue_style( 'custom_wp_admin_css' );
            wp_register_script( 'video_artist_js', plugin_dir_url( __FILE__ ) . 'js/video-artist.js', false, '1.4.3' );
            wp_enqueue_script( 'video_artist_js' );
            wp_register_script( 'video_media_js', plugin_dir_url( __FILE__ ) . 'js/video-media.js', false, '1.4.3' );
            wp_enqueue_script( 'video_media_js' );
            wp_enqueue_media(); // 2 horas
	}
	add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

}


function metabox_url_video_callback() {

    add_meta_box( 'metabox_url_video', 'Simple Background Video', 'metabox_artist_url_video_callback', 'page', 'side', 'high' );

}
add_action('admin_init','metabox_url_video_callback');

 
function wpse_80236_Colorpicker(){
    wp_enqueue_style( 'wp-color-picker');
    wp_enqueue_script( 'wp-color-picker');
}
add_action('admin_enqueue_scripts', 'wpse_80236_Colorpicker');


function metabox_artist_url_video_callback( $post ) {

    

        $video_yvn = get_post_custom_values('_video-yvn', $post->ID);
        $video_web = get_post_custom_values('_video-web', $post->ID);
        $video_mp4 = get_post_custom_values('_video-mp4', $post->ID);
        $title_select = get_post_custom_values('_title_select', $post->ID);
        $custom_title_input = get_post_custom_values('_custom_title_input', $post->ID);
        $color_title = get_post_custom_values('_color_title_ZD', $post->ID);
        $image_sn_arr = get_post_custom_values('_image_sn', $post->ID);
        $image_mob_arr = get_post_custom_values('_image_mob', $post->ID);
        $without_sound_arr = get_post_custom_values('_without_sound', $post->ID);

        $image_sn = ( !empty( $image_sn_arr ) ) ? $image_sn_arr[0] : '';
        $image_mob = ( !empty( $image_mob_arr ) ) ? $image_mob_arr[0] : '';
        $without_sound = ( !empty( $without_sound_arr ) ) ? $without_sound_arr[0] : '';

    ?>

    <span class="video_ZD">Video:</span>
    <div class="help_y" <?php if ($video_yvn[0]!="youtube" && $video_yvn[0]!="vimeo") { echo ' style="display:none;"';} ?>>
        <p>ID of Video: <a class="help-video" id="">Help with the ID?</a></p>
        <div class="help-y-v">
            <a class="help-video-y" id="<?php echo plugin_dir_url( __FILE__ ) . "images/video-y.jpg" ?>">Youtube</a>
            <span> | </span>
            <a class="help-video-y" id="<?php echo plugin_dir_url( __FILE__ ) . "images/video-v.jpg" ?>">Vimeo</a>
        </div> 
        <br>

        <div class="help-img">
            <div class="back-help"></div>
            <img src="">
        </div>
    </div>

    <select name="video-yvn" class="video-yvn">
        <option disabled selected>Select the option:</option>
        <option value="upload" <?php if($video_yvn[0]=="upload") { echo "selected"; } ?>>Upload mp4</option>
        <option value="youtube" <?php if($video_yvn[0]=="youtube") { echo "selected"; } ?>>Youtube ID</option>
        <option value="vimeo" <?php if($video_yvn[0]=="vimeo") { echo "selected"; } ?>>Vimeo ID</option>
        <option value="no-video" <?php if($video_yvn[0]=="no-video") { echo "selected"; } ?>>No Video</option>
    </select>
    <div class="hide-if-no-opt" <?php if ( $video_yvn[0] == '' || $video_yvn[0] == 'no-video' ) { echo 'style="display:none;"'; } ?>>
        <input class="artist-url_video" name="video-web" value="<?php echo esc_attr( $video_web[0] ); ?>" <?php if($video_yvn[0]!="no-video"){ echo 'placeholder="ID of Video.."';} ?> <?php if ($video_yvn[0]!="youtube" && $video_yvn[0]!="vimeo") { echo ' style="display:none;"';} ?> /> 

        <div class="cuadro-video" <?php if ( ($video_yvn[0]=="youtube" || $video_yvn[0]=="vimeo") && !empty($video_web[0]) ) {} else { echo ' style="display:none;"';} ?>>
            <div class="video-metabox">
                <?php 
                    if ( $video_yvn[0]=="youtube" ) { ?>
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo esc_attr( $video_web[0] ); ?>?rel=0&amp;controls=0&loop=1&amp;showinfo=0" frameborder="0" allowfullscreen volume="0"></iframe> 
                    <?php  
                    }
                    if ( $video_yvn[0]=="vimeo" ) { ?>
                        <iframe src="https://player.vimeo.com/video/<?php echo esc_attr( $video_web[0] ); ?>?color=be1659&title=0&byline=0&portrait=0" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    <?php
                    }
                    ?> 
            </div>
        </div>

        <?php

        global $post;

        // For convenience, see if the array is valid

        ?>

        <!-- Your image container, which can be manipulated with js -->
        <div class="custom-img-container">
            <?php if ( !empty($video_mp4[0]) && $video_yvn[0]=="upload" ) : ?>
                <video width="320" height="143" <?php if ($without_sound=="no") { echo "muted"; } ?>>
                  <source src="<?php echo esc_attr( $video_mp4[0] ); ?>" type="video/mp4">
                </video>
            <?php endif; ?>
        </div>
        <div class="custom-img-container-photo"></div>
        <!-- Your add & remove image links -->
        <p class="hide-if-no-js selector_video" <?php if ($video_yvn[0]!="upload") { echo " style='display:none;'"; } ?>>
            <a class="upload-custom-img button-primary button-large <?php if ( !empty( $video_mp4[0]) ) { echo 'hidden'; } ?>" 
               href="">
                <?php _e('Set custom video') ?>
            </a>
            <a class="delete-custom-img button-primary button-large <?php if ( empty($video_mp4[0]) ) { echo 'hidden'; } ?>" 
              href="#">
                <?php _e('Remove this video') ?>
            </a>
        </p>
        <!-- A hidden input to set and post the chosen image id -->

        <span class="title_ZD">Sound: (only mp4)</span>
        <select name="ZD_sound_check" class="title-ZD-sound">
            <option <?php if ($without_sound!="no") { echo "selected"; } ?>>Yes</option>
            <option value="no" <?php if ($without_sound=="no") { echo "selected"; } ?>>No</option>
        </select>
        <input class="video-mp4" name="video-mp4" type="hidden" value="<?php echo esc_attr( $video_mp4[0] ); ?>" />

        <div class="title_ZD_caja">
            <span class="title_ZD">Title:</span>
            <select name="title_ZD_select" class="title-ZD">
                <option value="title-page" <?php if($title_select[0]=="title-page") { echo "selected"; } ?>>Title Page</option>
                <option value="custom-title" <?php if($title_select[0]=="custom-title") { echo "selected"; } ?>>Custom Title</option>
                <option value="no" <?php if($title_select[0]=="no") { echo "selected"; } ?>>No</option>
            </select>
            <input class="custom-title-input" <?php if($title_select[0]!="custom-title") { echo "style='display: none;'"; } ?> name="custom-title-input" value="<?php echo esc_attr( $custom_title_input[0] ); ?>" placeholder="Title here" />
            <div class="hide_picker" <?php if($title_select[0]=="no") { echo "style='display: none;'"; } ?>>
                <script>
                (function( $ ) {
                    // Add Color Picker to all inputs that have 'color-field' class
                    $(function() {
                    $('.color-field').wpColorPicker();
                    });
                })( jQuery );
                </script>
                <input class="color-field" type="text" name="color_title" value="<?php echo esc_attr( $color_title[0] ); ?>">
            </div>
        </div>


        <br>
                
        <span class="image_ZD">¿Mobile/Tablet image?</span>
        <span class="what_ZD">In mobile phone autoplay is disabled to prevent unsolicited cellular download. <br>The solution is to show a image if you want.</span>
        <select name="image_sn" class="image-sn">
            <option <?php if (empty($image_sn)) { echo "selected";} ?>>No</option>
            <option value="yes" <?php if($image_sn=="yes") { echo "selected"; } ?>>Yes</option>
        </select>

        <!-- Your image container, which can be manipulated with js -->
        <div class="custom-img-container_img" <?php if ( $image_sn!="yes" ) { echo "style='display:none;'"; }  ?>>
            <?php if ( !empty($image_mob) && !empty($image_sn) ) : ?>
                <img src="<?php echo $image_mob ?>" alt=""/>
            <?php endif; ?>
        </div>
        <div class="custom-img-container-photo_img"></div>
        <!-- Your add & remove image links -->
        <p class="hide-if-no-js selector_image" <?php if ($image_sn!="yes") { echo " style='display:none;'"; } ?>>
            <a class="upload-custom-img_img button-primary button-large <?php if ( !empty( $image_mob ) ) { echo 'hidden'; } ?>" href="">
                <?php _e('Set custom image') ?>
            </a>
            <a class="delete-custom-img_img button-primary button-large <?php if ( empty( $image_mob ) ) { echo 'hidden'; } ?>" href="#">
                <?php _e('Remove this image') ?>
            </a>
        </p>
        <!-- A hidden input to set and post the chosen image id -->

        <input class="video-mp4_img" name="image_mob" type="hidden" value="<?php echo esc_attr( $image_mob ); ?>" />
    </div>
    <div class="spam">
        <hr>
        <p>
            <?php _e( "Plugin made by ", 'PMPZD-plugin' );?><a href="http://www.zuhaitz-design.com" target="_blank">Willy Muñoz</a>
            <br>
            <?php _e( '¿Do you need help?', 'PMPZD-plugin' );?>
            <br>
            <?php _e( 'info@zuhaitz-design.com', 'PMPZD-plugin' );?>
            <br>
            <a href="http://www.zuhaitz-design.com" target="_blank"><?php _e( "Zuhaitz Design | Design & Code", 'PMPZD-plugin' );?></a>
        </p>
    </div>


<?php   
}




function add_video() {

    global $post;

    if (is_home()) {

        $BVZD_options_arr = get_option( 'BVZD_options' );

        //set the option array values to variables HOME
        $video_yvn = ( ! empty( $BVZD_options_arr['video_yvn'] ) ) ? $BVZD_options_arr['video_yvn'] : '';
        $video_web = ( ! empty( $BVZD_options_arr['video_web'] ) ) ? $BVZD_options_arr['video_web'] : '';
        $video_mp4 = ( ! empty( $BVZD_options_arr['video_mp4'] ) ) ? $BVZD_options_arr['video_mp4'] : '';
        $title_select = ( ! empty( $BVZD_options_arr['title_select'] ) ) ? $BVZD_options_arr['title_select'] : '';
        $custom_title_input = ( ! empty( $BVZD_options_arr['title_input'] ) ) ? $BVZD_options_arr['title_input'] : '';
        $color_title = ( ! empty( $BVZD_options_arr['color_title'] ) ) ? $BVZD_options_arr['color_title'] : '';
        $image_sn = ( ! empty( $BVZD_options_arr['image_sn'] ) ) ? $BVZD_options_arr['image_sn'] : '';
        $image_mob = ( ! empty( $BVZD_options_arr['image_mob'] ) ) ? $BVZD_options_arr['image_mob'] : '';
        $without_sound = ( ! empty( $BVZD_options_arr['no_sound_ZD'] ) ) ? $BVZD_options_arr['no_sound_ZD'] : '';


    } else {


        $video_yvn_arr = get_post_custom_values('_video-yvn', $post->ID);
        $video_web_arr = get_post_custom_values('_video-web', $post->ID);
        $video_mp4_arr = get_post_custom_values('_video-mp4', $post->ID);
        $title_select_arr = get_post_custom_values('_title_select', $post->ID);
        $custom_title_input_arr = get_post_custom_values('_custom_title_input', $post->ID);
        $color_title_arr = get_post_custom_values('_color_title_ZD', $post->ID);
        $image_sn_arr = get_post_custom_values('_image_sn', $post->ID);
        $image_mob_arr = get_post_custom_values('_image_mob', $post->ID);
        $without_sound_arr = get_post_custom_values('_without_sound', $post->ID);

        $video_yvn = $video_yvn_arr[0];
        $video_web = $video_web_arr[0];
        $video_mp4 = $video_mp4_arr[0];
        $title_select = $title_select_arr[0];
        $custom_title_input = $custom_title_input_arr[0];
        $color_title = $color_title_arr[0];
        $image_sn = $image_sn_arr[0];
        $image_mob = $image_mob_arr[0];
        $without_sound = ( !empty( $without_sound_arr ) ) ? $without_sound_arr[0] : '';

    }


    


    if (!empty($video_web) || !empty($video_mp4)) {

        wp_register_style( 'add_video_css', plugin_dir_url( __FILE__ ) . 'css/add_video.css', false, '1.4.3' );
        wp_enqueue_style( 'add_video_css' );
        wp_register_script( 'add_video_js', plugin_dir_url( __FILE__ ) . 'js/add_video.js', false, '1.4.3' );
        wp_enqueue_script( 'add_video_js' );

        

        if ($video_yvn == "youtube" || $video_yvn == "vimeo") {
            add_filter( 'body_class', 'my_class_names_youvimZD' );
        } else {
            add_filter( 'body_class', 'my_class_names_uploadZD' );
        }

        function my_class_names_youvimZD( $classes ) {
            // add 'class-name' to the $classes array
            $classes[] = 'simple_background_video youvimZD';
            // return the $classes array
            return $classes;
        }
        function my_class_names_uploadZD( $classes ) {
            // add 'class-name' to the $classes array
            $classes[] = 'simple_background_video uploadZD';
            // return the $classes array
            return $classes;
        }

        if ($title_select=="title-page") { ?>
            <h1 class="title_video_ZD <?php if ( $image_sn=="yes" && !empty($image_mob) ) { echo "show"; } ?>" <?php if ( !empty($color_title) ) { echo "style='color:" . $color_title . "'"; } ?>><?php the_title(); ?></h1>
        <?php
        }
        if ($title_select=="custom-title") { ?>
            <h1 class="title_video_ZD <?php if ( $image_sn=="yes" && !empty($image_mob) ) { echo "show"; } ?>" <?php if ( !empty($color_title) ){echo "style='color:" . $color_title . "'";}?>> <?php echo html_entity_decode($custom_title_input);?></h1>
        <?php
        }
        ?>

        <div class="image_mb <?php if ( $image_sn=="yes" && !empty($image_mob) ) { echo "show"; } ?>" <?php if ( $image_sn=="yes" ){ echo "style='background-image:url($image_mob);'"; } ?>></div>

        <div class="video-home-ZD"> 
            <?php
            if ($video_yvn == "youtube") { ?>
                <div class="video-box">
                    <iframe id="video-home" type="text/html" width="640" height="360"
                      src="http://www.youtube.com/embed/<?php echo $video_web; ?>?enablejsapi=1&origin=<?php echo esc_url( $urlHome ); ?>&controls=0&showinfo=0&loop=1&vq=hd1080&autoplay=1&playlist=<?php echo $video_web; ?>"
                      frameborder="0"></iframe>

                </div>
            <?php     
            }
            if ($video_yvn == "vimeo") { ?>
                <div class="video-box">
                    <iframe src="https://player.vimeo.com/video/<?php echo $video_web; ?>?autoplay=1&loop=1&title=0&byline=0&portrait=0loop=1" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
            <?php     
            }

            if ($video_yvn == "upload") { ?>
                <div class="video-box">
                    <video width="560" height="315" autoplay loop <?php if ($without_sound=="no") { echo "muted"; } ?>>
                      <source src="<?php echo $video_mp4; ?>" type="video/mp4">
                    </video>
                </div>
            <?php     
            }
            ?> 
        </div>
        <?php
    }
    

}
add_action('wp_head', 'add_video');



function metabox_artist_url_video_save ( $post_id ) {

    //if autosave skip saving data
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;


    if ( isset($_POST['video-yvn']) ) {
        update_post_meta( $post_id, '_video-yvn', sanitize_text_field( $_POST['video-yvn'] ) );
    }
    if ( !empty($_POST['video-web']) ) {
        $limpiaID = trim ( $_POST['video-web'] );
        $e_video_web = esc_textarea( $limpiaID );
        update_post_meta( $post_id, '_video-web', sanitize_text_field( $e_video_web ) );
    }
    if ( !empty($_POST['video-mp4']) ) {
        update_post_meta( $post_id, '_video-mp4', sanitize_text_field( $_POST['video-mp4'] ) );
    }
    
    if ( !empty($_POST['title_ZD_select']) ) {
        update_post_meta( $post_id, '_title_select', sanitize_text_field( $_POST['title_ZD_select'] ) );
    }
    if ( !empty($_POST['custom-title-input']) ) {
        update_post_meta( $post_id, '_custom_title_input', esc_html( $_POST['custom-title-input'] ) );
    }

    if ( !empty($_POST['ZD_sound_check']) ) {
        update_post_meta( $post_id, '_without_sound', sanitize_text_field( $_POST['ZD_sound_check'] ) );
    }

    if ( !empty($_POST['color_title']) ) {
        update_post_meta( $post_id, '_color_title_ZD', sanitize_text_field( $_POST['color_title'] ) );
    }
    if ( !empty($_POST['image_sn']) ) {
        update_post_meta( $post_id, '_image_sn', sanitize_text_field( $_POST['image_sn'] ) );
    } else {
        delete_post_meta( $post_id, '_image_sn' );
    }
    if ( !empty($_POST['image_mob']) ) {
        update_post_meta( $post_id, '_image_mob', sanitize_text_field( $_POST['image_mob'] ) );
    } else {
        delete_post_meta( $post_id, '_image_mob' );
    }
    if ( !empty($_POST['video-yvn']) && $_POST['video-yvn'] == "no-video") {
        delete_post_meta( $post_id, '_video-web' );
        delete_post_meta( $post_id, '_video-mp4' );
    }
    if ( !empty($_POST['title_ZD_select']) && $_POST['title_ZD_select'] == "no") {
        delete_post_meta( $post_id, '_custom_title_input' );
        delete_post_meta( $post_id, '_color_title_ZD' );
    }
}



add_action('save_post', 'metabox_artist_url_video_save');


?>

<?php

// ----------------------------------------------------------------------------------
//	Register Front-End Styles And Scripts
// ----------------------------------------------------------------------------------

function alante_thinkup_child_frontscripts() {

	wp_enqueue_style( 'alante-thinkup-style', get_template_directory_uri() . '/style.css', array( 'alante-thinkup-bootstrap' ) );
	wp_enqueue_style( 'alante-thinkup-style-business', get_stylesheet_directory_uri() . '/style.css', array( 'alante-thinkup-style' ), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'alante_thinkup_child_frontscripts' );


// ----------------------------------------------------------------------------------
//	Update Options Array With Child Theme Color Values
// ----------------------------------------------------------------------------------

// Add child theme color values to options array
function alante_thinkup_updateoption_child_color() {

	// Set theme name combinations
	$name_theme_upper = 'Alante';
	$name_theme_lower = strtolower( $name_theme_upper );

	// Set possible options names
	$name_options_free  = $name_theme_lower . '_thinkup_redux_variables';
	$name_child_color   = $name_theme_lower . '_thinkup_child_color_business';

	// Get options values (theme options)
	$options_free = get_option( $name_options_free );

	// Get child color values
	$options_child_color = get_option( $name_child_color );

	if( ! empty( $options_free ) ) {

		// Only set child color values if not already set 
		if ( $options_child_color != 1 ) {

			$options_free['thinkup_styles_colorswitch'] = '1';
			$options_free['thinkup_styles_colorcustom'] = '#2d88de';

			// Add child color to theme options array
			update_option( $name_options_free, $options_free );

		}
	}

	// Set the child color flag
	update_option( $options_child_color, 1 );

}
add_action( 'init', 'alante_thinkup_updateoption_child_color', 999 );


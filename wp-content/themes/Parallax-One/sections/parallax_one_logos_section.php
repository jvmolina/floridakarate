<?php
/**
 * SECTION: CLIENTS LOGOs
 *
 * @package parallax-one
 */




/**
 * Display Logos section
 */
$parallax_one_default_content = parallax_one_logos_get_default_content();
$parallax_one_logos = get_theme_mod( 'parallax_one_logos_content', $parallax_one_default_content );
$parallax_one_frontpage_animations = get_theme_mod( 'parallax_one_enable_animations', false );

if ( ! empty( $parallax_one_logos ) ) {
	$parallax_one_logos_decoded = json_decode( $parallax_one_logos );
	parallax_hook_logos_before(); ?>

	<div class="clients white-bg" id="clients" role="region" aria-label="<?php esc_html_e( ' Affiliates Logos', 'parallax-one' ); ?>">
		<?php
		parallax_hook_logos_top();
		?>
		
	<?php
	parallax_hook_logos_after();
} // End if(). ?>

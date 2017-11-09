<?php
/**
 * Twenty Seventeen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 */

/**
 * Twenty Seventeen only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function twentyseventeen_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentyseventeen
	 * If you're building a theme based on Twenty Seventeen, use a find and replace
	 * to change 'twentyseventeen' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentyseventeen' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'twentyseventeen-featured-image', 2000, 1200, true );

	add_image_size( 'twentyseventeen-thumbnail-avatar', 100, 100, true );

	// Set the default content width.
	$GLOBALS['content_width'] = 525;

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'top'    => __( 'Top Menu', 'twentyseventeen' ),
		'social' => __( 'Social Links Menu', 'twentyseventeen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */
	add_editor_style( array( 'assets/css/editor-style.css', twentyseventeen_fonts_url() ) );

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets' => array(
			// Place three core-defined widgets in the sidebar area.
			'sidebar-1' => array(
				'text_business_info',
				'search',
				'text_about',
			),

			// Add the core-defined business info widget to the footer 1 area.
			'sidebar-2' => array(
				'text_business_info',
			),

			// Put two core-defined widgets in the footer 2 area.
			'sidebar-3' => array(
				'text_about',
				'search',
			),
		),

		// Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts' => array(
			'home',
			'about' => array(
				'thumbnail' => '{{image-sandwich}}',
			),
			'contact' => array(
				'thumbnail' => '{{image-espresso}}',
			),
			'blog' => array(
				'thumbnail' => '{{image-coffee}}',
			),
			'homepage-section' => array(
				'thumbnail' => '{{image-espresso}}',
			),
		),

		// Create the custom image attachments used as post thumbnails for pages.
		'attachments' => array(
			'image-espresso' => array(
				'post_title' => _x( 'Espresso', 'Theme starter content', 'twentyseventeen' ),
				'file' => 'assets/images/espresso.jpg', // URL relative to the template directory.
			),
			'image-sandwich' => array(
				'post_title' => _x( 'Sandwich', 'Theme starter content', 'twentyseventeen' ),
				'file' => 'assets/images/sandwich.jpg',
			),
			'image-coffee' => array(
				'post_title' => _x( 'Coffee', 'Theme starter content', 'twentyseventeen' ),
				'file' => 'assets/images/coffee.jpg',
			),
		),

		// Default to a static front page and assign the front and posts pages.
		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),

		// Set the front page section theme mods to the IDs of the core-registered pages.
		'theme_mods' => array(
			'panel_1' => '{{homepage-section}}',
			'panel_2' => '{{about}}',
			'panel_3' => '{{blog}}',
			'panel_4' => '{{contact}}',
		),

		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus' => array(
			// Assign a menu to the "top" location.
			'top' => array(
				'name' => __( 'Top Menu', 'twentyseventeen' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_about',
					'page_blog',
					'page_contact',
				),
			),

			// Assign a menu to the "social" location.
			'social' => array(
				'name' => __( 'Social Links Menu', 'twentyseventeen' ),
				'items' => array(
					'link_yelp',
					'link_facebook',
					'link_twitter',
					'link_instagram',
					'link_email',
				),
			),
		),
	);

	/**
	 * Filters Twenty Seventeen array of starter content.
	 *
	 * @since Twenty Seventeen 1.1
	 *
	 * @param array $starter_content Array of starter content.
	 */
	$starter_content = apply_filters( 'twentyseventeen_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );
}
add_action( 'after_setup_theme', 'twentyseventeen_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function twentyseventeen_content_width() {

	$content_width = $GLOBALS['content_width'];

	// Get layout.
	$page_layout = get_theme_mod( 'page_layout' );

	// Check if layout is one column.
	if ( 'one-column' === $page_layout ) {
		if ( twentyseventeen_is_frontpage() ) {
			$content_width = 644;
		} elseif ( is_page() ) {
			$content_width = 740;
		}
	}

	// Check if is single post and there is no sidebar.
	if ( is_single() && ! is_active_sidebar( 'sidebar-1' ) ) {
		$content_width = 740;
	}

	/**
	 * Filter Twenty Seventeen content width of the theme.
	 *
	 * @since Twenty Seventeen 1.0
	 *
	 * @param $content_width integer
	 */
	$GLOBALS['content_width'] = apply_filters( 'twentyseventeen_content_width', $content_width );
}
add_action( 'template_redirect', 'twentyseventeen_content_width', 0 );

/**
 * Register custom fonts.
 */
function twentyseventeen_fonts_url() {
	$fonts_url = '';

	/**
	 * Translators: If there are characters in your language that are not
	 * supported by Libre Franklin, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$libre_franklin = _x( 'on', 'Libre Franklin font: on or off', 'twentyseventeen' );

	if ( 'off' !== $libre_franklin ) {
		$font_families = array();

		$font_families[] = 'Libre Franklin:300,300i,400,400i,600,600i,800,800i';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function twentyseventeen_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'twentyseventeen-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'twentyseventeen_resource_hints', 10, 2 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function twentyseventeen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'twentyseventeen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentyseventeen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 1', 'twentyseventeen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Add widgets here to appear in your footer.', 'twentyseventeen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 2', 'twentyseventeen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Add widgets here to appear in your footer.', 'twentyseventeen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentyseventeen_widgets_init' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @since Twenty Seventeen 1.0
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function twentyseventeen_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf( '<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'twentyseventeen_excerpt_more' );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Seventeen 1.0
 */
function twentyseventeen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentyseventeen_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function twentyseventeen_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'twentyseventeen_pingback_header' );

/**
 * Display custom color CSS.
 */
function twentyseventeen_colors_css_wrap() {
	if ( 'custom' !== get_theme_mod( 'colorscheme' ) && ! is_customize_preview() ) {
		return;
	}

	require_once( get_parent_theme_file_path( '/inc/color-patterns.php' ) );
	$hue = absint( get_theme_mod( 'colorscheme_hue', 250 ) );
?>
	<style type="text/css" id="custom-theme-colors" <?php if ( is_customize_preview() ) { echo 'data-hue="' . $hue . '"'; } ?>>
		<?php echo twentyseventeen_custom_colors_css(); ?>
	</style>
<?php }
add_action( 'wp_head', 'twentyseventeen_colors_css_wrap' );

/**
 * Enqueue scripts and styles.
 */
function twentyseventeen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentyseventeen-fonts', twentyseventeen_fonts_url(), array(), null );

	// Theme stylesheet.
	wp_enqueue_style( 'twentyseventeen-style', get_stylesheet_uri() );

	// Load the dark colorscheme.
	if ( 'dark' === get_theme_mod( 'colorscheme', 'light' ) || is_customize_preview() ) {
		wp_enqueue_style( 'twentyseventeen-colors-dark', get_theme_file_uri( '/assets/css/colors-dark.css' ), array( 'twentyseventeen-style' ), '1.0' );
	}

	// Load the Internet Explorer 9 specific stylesheet, to fix display issues in the Customizer.
	if ( is_customize_preview() ) {
		wp_enqueue_style( 'twentyseventeen-ie9', get_theme_file_uri( '/assets/css/ie9.css' ), array( 'twentyseventeen-style' ), '1.0' );
		wp_style_add_data( 'twentyseventeen-ie9', 'conditional', 'IE 9' );
	}

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'twentyseventeen-ie8', get_theme_file_uri( '/assets/css/ie8.css' ), array( 'twentyseventeen-style' ), '1.0' );
	wp_style_add_data( 'twentyseventeen-ie8', 'conditional', 'lt IE 9' );

	// Load the html5 shiv.
	wp_enqueue_script( 'html5', get_theme_file_uri( '/assets/js/html5.js' ), array(), '3.7.3' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'twentyseventeen-skip-link-focus-fix', get_theme_file_uri( '/assets/js/skip-link-focus-fix.js' ), array(), '1.0', true );

	$twentyseventeen_l10n = array(
		'quote'          => twentyseventeen_get_svg( array( 'icon' => 'quote-right' ) ),
	);

	if ( has_nav_menu( 'top' ) ) {
		wp_enqueue_script( 'twentyseventeen-navigation', get_theme_file_uri( '/assets/js/navigation.js' ), array(), '1.0', true );
		$twentyseventeen_l10n['expand']         = __( 'Expand child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['collapse']       = __( 'Collapse child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['icon']           = twentyseventeen_get_svg( array( 'icon' => 'angle-down', 'fallback' => true ) );
	}

	wp_enqueue_script( 'twentyseventeen-global', get_theme_file_uri( '/assets/js/global.js' ), array( 'jquery' ), '1.0', true );

	wp_enqueue_script( 'jquery-scrollto', get_theme_file_uri( '/assets/js/jquery.scrollTo.js' ), array( 'jquery' ), '2.1.2', true );

	wp_localize_script( 'twentyseventeen-skip-link-focus-fix', 'twentyseventeenScreenReaderText', $twentyseventeen_l10n );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'twentyseventeen_scripts' );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentyseventeen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( 740 <= $width ) {
		$sizes = '(max-width: 706px) 89vw, (max-width: 767px) 82vw, 740px';
	}

	if ( is_active_sidebar( 'sidebar-1' ) || is_archive() || is_search() || is_home() || is_page() ) {
		if ( ! ( is_page() && 'one-column' === get_theme_mod( 'page_options' ) ) && 767 <= $width ) {
			 $sizes = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
		}
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentyseventeen_content_image_sizes_attr', 10, 2 );

/**
 * Filter the `sizes` value in the header image markup.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $html   The HTML image tag markup being filtered.
 * @param object $header The custom header object returned by 'get_custom_header()'.
 * @param array  $attr   Array of the attributes for the image tag.
 * @return string The filtered header image HTML.
 */
function twentyseventeen_header_image_tag( $html, $header, $attr ) {
	if ( isset( $attr['sizes'] ) ) {
		$html = str_replace( $attr['sizes'], '100vw', $html );
	}
	return $html;
}
add_filter( 'get_header_image_tag', 'twentyseventeen_header_image_tag', 10, 3 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array $attr       Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size       Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function twentyseventeen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( is_archive() || is_search() || is_home() ) {
		$attr['sizes'] = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
	} else {
		$attr['sizes'] = '100vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentyseventeen_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Use front-page.php when Front page displays is set to a static page.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $template front-page.php.
 *
 * @return string The template to be used: blank if is_home() is true (defaults to index.php), else $template.
 */
function twentyseventeen_front_page_template( $template ) {
	return is_home() ? '' : $template;
}
add_filter( 'frontpage_template',  'twentyseventeen_front_page_template' );

/**
 * Implement the Custom Header feature.
 */
require get_parent_theme_file_path( '/inc/custom-header.php' );

/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Additional features to allow styling of the templates.
 */
require get_parent_theme_file_path( '/inc/template-functions.php' );

/**
 * Customizer additions.
 */
require get_parent_theme_file_path( '/inc/customizer.php' );

/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path( '/inc/icon-functions.php' );




// REMAINING BALANCE

function woo_add_cart_fee() {
    global $woocommerce;


     if ( !WC()->cart->is_empty() ){
    $add_fee=false;
     $remaining=0;
   foreach ($woocommerce->cart->cart_contents as $key => $valu ) {
$res = get_post_meta($valu['product_id']);
$attributes=unserialize($res['_product_attributes'][0]);

  
	    if($valu['product_id']==14){
		$add_fee=true;
		$payment2=$attributes['2payment']['value'];
        	$payment3=$attributes['3payment']['value'];
		$remaining=$remaining+$payment2+$payment3;
	   }elseif($valu['product_id']==15){
		$add_fee=true;
		$payment2=$attributes['2payment']['value'];
        	$payment3=$attributes['3payment']['value'];
		$remaining=$remaining+$payment2+$payment3;
	   }elseif($valu['product_id']==13){
		$add_fee=true;
		$payment2=$attributes['2payment']['value'];
		$remaining=$remaining+$payment2;
	   }
	//echo $remaining;
     }
	
	$remaining= number_format($remaining, 2, '.', '');
       
            
       
    }

	if($add_fee==true){
        $woocommerce->cart->add_fee( "Remaining Balance", $remaining );
	}
   
}
add_action( 'woocommerce_after_calculate_totals', 'woo_add_cart_fee');
add_action( 'woocommerce_cart_totals_after_order_total', 'woo_add_cart_fee');



//APLICAR DESCUENTO EN EL CARRITO
// aplicar descuento total de la orden en el action
function apply_discount_product_on_coupon_cart($cart, $values) {
    
global $woocommerce;
if (!empty($woocommerce->cart->applied_coupons))
{
$total=0;
foreach ($woocommerce->cart->cart_contents as $key => $valu ) {


$res = get_post_meta($valu['product_id']);
$attributes=unserialize($res['_product_attributes'][0]);

  
    if($valu['product_id']==10){

	$first_amount=$valu['line_subtotal'];
	$discount=5;
      $amount =  ( $first_amount / 100 ) * $discount;
      $total=$total+$amount;

   }elseif($valu['product_id']==14){

	$first_amount=$attributes['1payment']['value'];
	$discount=1.5;
	$amount =  ( $first_amount / 100 ) * $discount;
	 $total=$total+$amount;

   }elseif($valu['product_id']==15){

	$first_amount=$attributes['1payment']['value'];
	$discount=1.5;
	$amount =  ( $first_amount / 100 ) * $discount;
	 $total=$total+$amount;

   }elseif($valu['product_id']==13){

	$first_amount=$attributes['1payment']['value'];
	$discount=2;
	$amount =  ( $first_amount / 100 ) * $discount;
	 $total=$total+$amount;

   }



}
$total= number_format($total, 2, '.', '');
$total="-$".$total;
return $total;

}

}

add_filter('woocommerce_cart_totals_coupon_html', 'apply_discount_product_on_coupon_cart');



// aplicar descuento total de la orden en el action
function apply_discount_product_on_coupon($cart, $values) {
    
global $woocommerce;
if (!empty($woocommerce->cart->applied_coupons))
{
$total=0;
foreach ($woocommerce->cart->cart_contents as $key => $valu ) {


$res = get_post_meta($valu['product_id']);
$attributes=unserialize($res['_product_attributes'][0]);

  
    if($valu['product_id']==10){

	$first_amount=$valu['line_subtotal'];
	$discount=5;
      $amount =  ( $first_amount / 100 ) * $discount;
      $total=$total+$amount;
   }elseif($valu['product_id']==14){
	
	$first_amount=$attributes['1payment']['value'];
	$discount=1.5;
	$amount =  ( $first_amount / 100 ) * $discount;
	 $total=$total+$amount;
   }elseif($valu['product_id']==15){
	$first_amount=$attributes['1payment']['value'];
	$discount=1.5;
	
	$amount =  ( $first_amount / 100 ) * $discount;
	 $total=$total+$amount;
   }elseif($valu['product_id']==13){
	$first_amount=$attributes['1payment']['value'];
	$discount=2;
	$amount =  ( $first_amount / 100 ) * $discount;
	 $total=$total+$amount;
   }



}
$total= number_format($total, 2, '.', '');
$cart->discount_cart = $total;

}


}

add_action('woocommerce_calculate_totals', 'apply_discount_product_on_coupon');






//cambiar el total en el action de la orden 
   add_action( 'woocommerce_calculate_totals', 'action_cart_calculate_totals', 10, 1 );
function action_cart_calculate_totals( $cart_object, $values) {
global $woocommerce;

     if ( !WC()->cart->is_empty() ){
if (!empty($woocommerce->cart->applied_coupons))
{
  $total=0;
   foreach ($woocommerce->cart->cart_contents as $key => $valu ) {
$res = get_post_meta($valu['product_id']);
$attributes=unserialize($res['_product_attributes'][0]);

  
	    if($valu['product_id']==10){
		$amount=$valu['line_subtotal'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==14){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==15){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==13){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }
     }
	$woodiscount=$woocommerce->cart->discount_cart;
	$total=$total-$woodiscount;

	$total= number_format($total, 2, '.', '');
}else{
     $total=0;
   foreach ($woocommerce->cart->cart_contents as $key => $valu ) {
$res = get_post_meta($valu['product_id']);
$attributes=unserialize($res['_product_attributes'][0]);

  
	    if($valu['product_id']==10){
		$amount=$valu['line_subtotal'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==14){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==15){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==13){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }
	//echo $total;
     }
	$total= number_format($total, 2, '.', '');
       
            
      } 
    }
        $cart_object->cart_contents_total = $total;

   }







//aplicar nombre en el carrito y en la orden
add_filter('woocommerce_order_item_name','wdm_add_user_custom_option_from_session_into_cart',1,3); 
add_filter('woocommerce_checkout_cart_item_quantity','wdm_add_user_custom_option_from_session_into_cart',1,3);  
add_filter('woocommerce_cart_item_price','wdm_add_user_custom_option_from_session_into_cart',1,3);
if(!function_exists('wdm_add_user_custom_option_from_session_into_cart'))
{
 function wdm_add_user_custom_option_from_session_into_cart($product_name, $values, $cart_item_key )
    {
	$res = get_post_meta($values['product_id']);
	$attributes=unserialize($res['_product_attributes'][0]);

    $add_text=false;
    if($values['product_id']==10){
      //SOLO UN PAGO
	$add_text=false;
       
   }elseif($values['product_id']==14){
     //PRODUCTO DE 3 PAGOS
	$add_text=true;
	$payment1=$attributes['1payment']['value'];
	$payment2=$attributes['2payment']['value'];
        $payment3=$attributes['3payment']['value'];
	$remaining=$payment2+$payment3;
	
   }elseif($values['product_id']==15){
	//PRODUCTO DE 3 PAGOS
	$add_text=true;
	$payment1=$attributes['1payment']['value'];
	$payment2=$attributes['2payment']['value'];
        $payment3=$attributes['3payment']['value'];
	$remaining=$payment2+$payment3;
	

   }elseif($values['product_id']==13){
	 //PRODUCTO DE 2 PAGOS
	$add_text=true;
	$payment1=$attributes['1payment']['value'];
	$payment2=$attributes['2payment']['value'];
	$remaining=$payment2;
	

   }

            
            $return_string = $product_name . "</a>";
	    
	    if($add_text==true){
	    $remaining=number_format($remaining, 2, '.', '');
	    $payment1=number_format($payment1, 2, '.', '');
            $return_string .= "<dl class='variation'><table class='wdm_options_table' id='" . $values['product_id'] . "'>";
            $return_string .= "<tr><td> $".$payment1." 1st Payment
($".$remaining." Remaining) </td></tr>";
            $return_string .= "</table></dl>"; 

	     }
            return $return_string;
       
    }
}

add_filter( 'woocommerce_cart_total', 'action_cart_calculate_deposit' );
add_filter( 'woocommerce_cart_totals_order_total_html', 'action_cart_calculate_deposit' );
function action_cart_calculate_deposit( $cart_object) {
global $woocommerce;

if (!empty($woocommerce->cart->applied_coupons))
{
  $total=0;
   foreach ($woocommerce->cart->cart_contents as $key => $valu ) {
$res = get_post_meta($valu['product_id']);
$attributes=unserialize($res['_product_attributes'][0]);

  
	    if($valu['product_id']==10){
		$amount=$valu['line_subtotal'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==14){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==15){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==13){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }
     }
	$woodiscount=$woocommerce->cart->discount_cart;
	$total=$total-$woodiscount;

	$total= number_format($total, 2, '.', '');
	return "$".$total;
}else{
 $total=0;
   foreach ($woocommerce->cart->cart_contents as $key => $valu ) {
$res = get_post_meta($valu['product_id']);
$attributes=unserialize($res['_product_attributes'][0]);

  
	    if($valu['product_id']==10){
		$amount=$valu['line_subtotal'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==14){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==15){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }elseif($valu['product_id']==13){
		$amount=$attributes['1payment']['value'];
		$total=$total+$amount;
	   }
     }
	$total= number_format($total, 2, '.', '');
	return "$".$total;
}
}


add_filter('wp_nav_menu_items','sk_wcmenucart', 10, 2);
function sk_wcmenucart($menu, $args) {

	// Check if WooCommerce is active and add a new item to a menu assigned to Primary Navigation Menu location
	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || 'primary' !== $args->theme_location )
		return $menu;

	ob_start();
		global $woocommerce;
		$viewing_cart = __('View your shopping cart', 'your-theme-slug');
		$start_shopping = __('Start shopping', 'your-theme-slug');
		$cart_url = $woocommerce->cart->get_cart_url();
		$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
		$cart_contents_count = $woocommerce->cart->cart_contents_count;
		$cart_contents = sprintf(_n('%d item', '%d items', $cart_contents_count, 'your-theme-slug'), $cart_contents_count);
		$cart_total = $woocommerce->cart->get_cart_total();
		// Uncomment the line below to hide nav menu cart item when there are no items in the cart
		// if ( $cart_contents_count > 0 ) {
			if ($cart_contents_count == 0) {
				$menu_item = '<li class="right"><a class="wcmenucart-contents" href="'. $shop_page_url .'" title="'. $start_shopping .'">';
			} else {
				$menu_item = '<li class="right"><a class="wcmenucart-contents" href="'. $cart_url .'" title="'. $viewing_cart .'">';
			}

			$menu_item .= '<i class="fa fa-shopping-cart"></i> ';

			$menu_item .= $cart_contents.' - '. $cart_total;
			$menu_item .= '</a></li>';
		// Uncomment the line below to hide nav menu cart item when there are no items in the cart
		// }
		echo $menu_item;
	$social = ob_get_clean();
	return $menu . $social;

}


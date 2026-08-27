<?php
defined( 'ABSPATH' ) || exit;


function wpbb_clouthes_project_mode( $mode ) { return 'woocommerce'; }
add_filter( 'wp_theme_project_mode', 'wpbb_clouthes_project_mode' );
function wpbb_clouthes_woo_profile( $profile ) { return 'store'; }
add_filter( 'wp_theme_woo_support_default_profile', 'wpbb_clouthes_woo_profile' );

function wpbb_clouthes_assets() {
	$theme = wp_get_theme();
	wp_enqueue_style( 'wpbb-clouthes', get_stylesheet_uri(), array( 'wp-theme-style' ), $theme->get( 'Version' ) );
	wp_enqueue_script( 'wpbb-clouthes-navigation', get_stylesheet_directory_uri() . '/assets/js/theme.js', array(), $theme->get( 'Version' ), true );
	if ( function_exists( 'wp_theme_sector_customizer_css' ) ) {
		wp_add_inline_style( 'wpbb-clouthes', wp_theme_sector_customizer_css( '#92400e', '0px', '--sector-primary', '--sector-radius' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_assets', 30 );

function wpbb_clouthes_demo_profile( $profile ) {
	$assets = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/products/';
	return array_merge( $profile, array(
		'id' => 'clothes', 'name' => __( 'Clothes Store', 'wp-bbtheme-child-woo-clouthes' ), 'commerce' => true,
		'eyebrow' => __( 'The new collection', 'wp-bbtheme-child-woo-clouthes' ),
		'hero_title' => __( 'Everyday pieces, made to stay in your wardrobe.', 'wp-bbtheme-child-woo-clouthes' ),
		'hero_text' => __( 'A considered edit of versatile clothing, useful accessories and dependable materials.', 'wp-bbtheme-child-woo-clouthes' ),
		'hero_image' => $assets . 'relaxed-cotton-shirt.jpg',
		'about_image' => $assets . 'utility-overshirt.jpg',
		'primary_label' => __( 'Shop the collection', 'wp-bbtheme-child-woo-clouthes' ), 'primary_url' => '#shop',
		'secondary_label' => __( 'Our approach', 'wp-bbtheme-child-woo-clouthes' ), 'secondary_url' => '#services',
		'industries' => array( __( 'New arrivals', 'wp-bbtheme-child-woo-clouthes' ), __( 'Women', 'wp-bbtheme-child-woo-clouthes' ), __( 'Men', 'wp-bbtheme-child-woo-clouthes' ), __( 'Accessories', 'wp-bbtheme-child-woo-clouthes' ) ),
		'services' => array(
			array( __( 'Better materials', 'wp-bbtheme-child-woo-clouthes' ), __( 'Fabrics selected for comfort, character and useful wear.', 'wp-bbtheme-child-woo-clouthes' ) ),
			array( __( 'Considered fits', 'wp-bbtheme-child-woo-clouthes' ), __( 'Clear sizing and shapes designed for repeat use.', 'wp-bbtheme-child-woo-clouthes' ) ),
			array( __( 'Easy returns', 'wp-bbtheme-child-woo-clouthes' ), __( 'Simple fulfilment and support before and after purchase.', 'wp-bbtheme-child-woo-clouthes' ) ),
		),
	) );
}
add_filter( 'wp_theme_demo_profile', 'wpbb_clouthes_demo_profile' );

function wpbb_clouthes_demo_products() {
	return array(
		array( 'variable', 'Relaxed Cotton Shirt', 'Shirts', 68, 'A softly structured cotton shirt in versatile colours and sizes.' ),
		array( 'variable', 'Classic Oxford Shirt', 'Shirts', 74, 'A dependable everyday Oxford with a clean regular fit.' ),
		array( 'variable', 'Essential T-Shirt', 'T-Shirts', 29, 'Midweight cotton jersey with a relaxed modern shape.' ),
		array( 'variable', 'Merino Crew Knit', 'Knitwear', 95, 'Fine merino knitwear for easy year-round layering.' ),
		array( 'variable', 'Straight Leg Trouser', 'Trousers', 82, 'Tailored everyday trousers with an easy straight leg.' ),
		array( 'variable', 'Everyday Denim', 'Trousers', 89, 'Soft rigid denim with a timeless straight fit.' ),
		array( 'simple', 'Canvas Weekend Bag', 'Accessories', 79, 'A durable carry-all for work, travel and weekends.' ),
		array( 'simple', 'Leather Card Holder', 'Accessories', 35, 'A slim card holder made from smooth leather.' ),
		array( 'simple', 'Wool Scarf', 'Accessories', 48, 'A warm brushed scarf with a soft natural handle.' ),
		array( 'simple', 'Cotton Cap', 'Accessories', 26, 'An unstructured cotton cap for everyday wear.' ),
		array( 'variable', 'Utility Overshirt', 'Outerwear', 110, 'A practical layer with generous patch pockets.' ),
		array( 'variable', 'Lightweight Parka', 'Outerwear', 145, 'Weather-ready outerwear with a clean minimal finish.' ),
	);
}
add_filter( 'wp_theme_woo_demo_product_data', 'wpbb_clouthes_demo_products' );

function wpbb_clouthes_demo_product_image( $path, $product, $index ) {
	$images = array(
		'relaxed-cotton-shirt.jpg', 'classic-oxford-shirt.jpg', 'essential-t-shirt.jpg', 'merino-crew-knit.jpg',
		'straight-leg-trouser.jpg', 'everyday-denim.jpg', 'canvas-weekend-bag.jpg', 'leather-card-holder.jpg',
		'wool-scarf.jpg', 'cotton-cap.jpg', 'utility-overshirt.jpg', 'lightweight-parka.jpg',
	);

	return isset( $images[ $index ] ) ? get_stylesheet_directory() . '/assets/img/products/' . $images[ $index ] : $path;
}
add_filter( 'wp_theme_woo_demo_product_image_path', 'wpbb_clouthes_demo_product_image', 10, 3 );

function wpbb_clouthes_demo_variation_options( $options, $product ) {
	$name = isset( $product[1] ) ? $product[1] : '';
	if ( in_array( $name, array( 'Straight Leg Trouser', 'Everyday Denim' ), true ) ) {
		return array(
			'colors' => 'Everyday Denim' === $name ? array( 'Indigo', 'Washed Black', 'Ecru' ) : array( 'Charcoal', 'Navy', 'Stone' ),
			'sizes'  => array( '28', '30', '32', '34', '36' ),
		);
	}

	$colors = array( 'Ivory', 'Navy', 'Black', 'Olive' );
	if ( 'Merino Crew Knit' === $name ) {
		$colors = array( 'Camel', 'Stone', 'Navy', 'Forest' );
	} elseif ( in_array( $name, array( 'Utility Overshirt', 'Lightweight Parka' ), true ) ) {
		$colors = array( 'Olive', 'Charcoal', 'Sand' );
	}

	return array( 'colors' => $colors, 'sizes' => array( 'XS', 'S', 'M', 'L', 'XL' ) );
}
add_filter( 'wp_theme_woo_demo_variation_options', 'wpbb_clouthes_demo_variation_options', 10, 2 );

function wpbb_clouthes_attribute_label( $label, $name ) {
	return in_array( $name, array( 'pa_color', 'color' ), true ) ? __( 'Colour', 'wp-bbtheme-child-woo-clouthes' ) : $label;
}
add_filter( 'woocommerce_attribute_label', 'wpbb_clouthes_attribute_label', 10, 2 );

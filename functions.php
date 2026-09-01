<?php
defined('ABSPATH') || exit;
function wpbb_clouthes_project_mode($mode){ return 'woocommerce'; }
add_filter('wp_theme_project_mode','wpbb_clouthes_project_mode');
function wpbb_clouthes_woo_profile($profile){ return 'store'; }
add_filter('wp_theme_woo_support_default_profile','wpbb_clouthes_woo_profile');

function wpbb_clouthes_assets() {
    $theme = wp_get_theme();
    wp_enqueue_style('wpbb_clouthes-meta', get_stylesheet_uri(), array('wp-theme-style'), $theme->get('Version'));
    $manifest = get_stylesheet_directory() . '/dist/.vite/manifest.json';
    if (!is_readable($manifest)) return;
    $data = json_decode((string) file_get_contents($manifest), true);
    if (!is_array($data)) return;
    if (!empty($data['src/scss/public.scss']['file'])) {
        wp_enqueue_style('wpbb_clouthes-app', get_stylesheet_directory_uri() . '/dist/' . ltrim($data['src/scss/public.scss']['file'], '/'), array('wpbb_clouthes-meta'), $theme->get('Version'));
        if (function_exists('wp_theme_sector_customizer_css')) wp_add_inline_style('wpbb_clouthes-app', wp_theme_sector_customizer_css('#772f3a', '4px', '--sector-primary', '--sector-radius'));
    }
    if (!empty($data['src/js/main.js']['file'])) wp_enqueue_script('wpbb_clouthes-app', get_stylesheet_directory_uri() . '/dist/' . ltrim($data['src/js/main.js']['file'], '/'), array(), $theme->get('Version'), true);
}
add_action('wp_enqueue_scripts', 'wpbb_clouthes_assets', 30);

function wpbb_clouthes_dark_mode_bootstrap() { echo '<script>(function(){try{var m=localStorage.getItem("wpThemeMode");if(m==="dark"){document.documentElement.classList.add("is-dark-theme");document.documentElement.setAttribute("data-theme","dark");}}catch(e){}})();</script>'; }
add_action('wp_head', 'wpbb_clouthes_dark_mode_bootstrap', 1);
function wpbb_clouthes_demo_profile( $profile ) {
	$assets = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/products/';
	return array_merge( $profile, array(
		'id' => 'clothes', 'name' => __( 'Clothes Store', 'wp-bbtheme-child-woo-clouthes' ), 'commerce' => true,
        'services_eyebrow' => __( 'Atelier service', 'wp-bbtheme-child-woo-clouthes' ),
        'services_heading' => __( 'A quieter shopping experience built around fit, fabric and useful service.', 'wp-bbtheme-child-woo-clouthes' ),
        'about_eyebrow' => __( 'Material story', 'wp-bbtheme-child-woo-clouthes' ),
        'industries_eyebrow' => __( 'Wardrobe edit', 'wp-bbtheme-child-woo-clouthes' ),
        'industries_heading' => __( 'Considered pieces for work, weekends and everything between.', 'wp-bbtheme-child-woo-clouthes' ),
        'shop_eyebrow' => __( 'New arrivals', 'wp-bbtheme-child-woo-clouthes' ),
        'shop_heading' => __( 'A focused collection with useful filters and fewer distractions.', 'wp-bbtheme-child-woo-clouthes' ),
        'process_eyebrow' => __( 'Shopping, simplified', 'wp-bbtheme-child-woo-clouthes' ),
        'process_heading' => __( 'Discover a piece, choose the right option and keep the checkout calm.', 'wp-bbtheme-child-woo-clouthes' ),
        'faq_heading' => __( 'Fit, delivery and returns explained simply.', 'wp-bbtheme-child-woo-clouthes' ),
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

function wpbb_clouthes_demo_profile_premium( $profile ) {
	if ( empty( $profile['id'] ) || 'clothes' !== $profile['id'] ) { return $profile; }
	$profile['about_title'] = __( 'A quieter wardrobe, built around repeat wear.', 'wp-bbtheme-child-woo-clouthes' );
	$profile['about_text'] = __( 'Editorial storytelling, confident product imagery and restrained commerce UI keep the focus on material, fit and the collection.', 'wp-bbtheme-child-woo-clouthes' );
	$profile['stats'] = array(
		array( '12', __( 'Curated pieces', 'wp-bbtheme-child-woo-clouthes' ) ),
		array( '4', __( 'Core wardrobe categories', 'wp-bbtheme-child-woo-clouthes' ) ),
		array( '3', __( 'Useful size and colour options', 'wp-bbtheme-child-woo-clouthes' ) ),
		array( '1', __( 'Editorial storefront system', 'wp-bbtheme-child-woo-clouthes' ) ),
	);
	$profile['process'] = array(
		array( '01', __( 'Discover', 'wp-bbtheme-child-woo-clouthes' ), __( 'Move from collection stories into focused category edits.', 'wp-bbtheme-child-woo-clouthes' ) ),
		array( '02', __( 'Choose', 'wp-bbtheme-child-woo-clouthes' ), __( 'Use simple filters and tactile variation options without visual clutter.', 'wp-bbtheme-child-woo-clouthes' ) ),
		array( '03', __( 'Keep', 'wp-bbtheme-child-woo-clouthes' ), __( 'Support the purchase with clear sizing, delivery, returns and care information.', 'wp-bbtheme-child-woo-clouthes' ) ),
	);
	$profile['cta_title'] = __( 'Build a collection that feels considered from first scroll to checkout.', 'wp-bbtheme-child-woo-clouthes' );
	$profile['cta_text'] = __( 'Editorial content and practical WooCommerce discovery work together to make the collection easy to browse.', 'wp-bbtheme-child-woo-clouthes' );
	$profile['footer_text'] = __( 'A restrained fashion store focused on useful collections, materials and everyday pieces.', 'wp-bbtheme-child-woo-clouthes' );
	$profile['page_labels'] = array( 'about' => __( 'Our story', 'wp-bbtheme-child-woo-clouthes' ), 'services' => __( 'Materials & care', 'wp-bbtheme-child-woo-clouthes' ), 'industries' => __( 'Collections', 'wp-bbtheme-child-woo-clouthes' ), 'contact' => __( 'Contact', 'wp-bbtheme-child-woo-clouthes' ), 'blog' => __( 'Journal', 'wp-bbtheme-child-woo-clouthes' ) );
	return $profile;
}
add_filter( 'wp_theme_demo_profile', 'wpbb_clouthes_demo_profile_premium', 20 );

function wpbb_clouthes_pattern_markup( $name ) {
	$path = get_stylesheet_directory() . '/patterns/' . sanitize_file_name( $name ) . '.php';
	if ( ! is_readable( $path ) ) { return ''; }
	ob_start(); include $path; return trim( (string) ob_get_clean() );
}
function wpbb_clouthes_after_hero_sections( $content, $profile ) {
	if ( empty( $profile['id'] ) || 'clothes' !== $profile['id'] ) { return $content; }
	return $content . wpbb_clouthes_pattern_markup( 'clothes-values' ) . wpbb_clouthes_pattern_markup( 'clothes-categories' );
}
add_filter( 'wp_theme_demo_after_hero_sections', 'wpbb_clouthes_after_hero_sections', 20, 2 );
function wpbb_clouthes_extra_home_sections( $content, $profile ) {
	if ( empty( $profile['id'] ) || 'clothes' !== $profile['id'] ) { return $content; }
	return $content . wpbb_clouthes_pattern_markup( 'clothes-story' ) . wpbb_clouthes_pattern_markup( 'clothes-newsletter' );
}
add_filter( 'wp_theme_demo_extra_home_sections', 'wpbb_clouthes_extra_home_sections', 20, 2 );

function wpbb_clouthes_product_category_url( $name ) {
	$term = get_term_by( 'name', $name, 'product_cat' );
	if ( ! $term || is_wp_error( $term ) ) return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	$url = get_term_link( $term );
	return is_wp_error( $url ) ? home_url( '/shop/' ) : $url;
}

/** Editorial Shop mega menu using real WooCommerce category destinations. */
function wpbb_clouthes_mega_menu_definitions( $definitions, $profile ) {
	if ( empty( $profile['id'] ) || 'clothes' !== $profile['id'] ) return $definitions;
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	$definitions['shop'] = array(
		'title'      => __( 'Collection navigation', 'wp-bbtheme-child-woo-clouthes' ),
		'target_key' => 'shop',
		'eyebrow'    => __( 'The collection', 'wp-bbtheme-child-woo-clouthes' ),
		'heading'    => __( 'Everyday pieces, easier to browse.', 'wp-bbtheme-child-woo-clouthes' ),
		'intro'      => __( 'Move through the collection by garment, material and the pieces you need now.', 'wp-bbtheme-child-woo-clouthes' ),
		'columns'    => array(
			array( 'title' => __( 'Clothing', 'wp-bbtheme-child-woo-clouthes' ), 'links' => array(
				array( __( 'Shirts', 'wp-bbtheme-child-woo-clouthes' ), __( 'Oxford, cotton and relaxed everyday shirts.', 'wp-bbtheme-child-woo-clouthes' ), wpbb_clouthes_product_category_url( 'Shirts' ) ),
				array( __( 'Knitwear', 'wp-bbtheme-child-woo-clouthes' ), __( 'Merino and useful layering pieces.', 'wp-bbtheme-child-woo-clouthes' ), wpbb_clouthes_product_category_url( 'Knitwear' ) ),
				array( __( 'Outerwear', 'wp-bbtheme-child-woo-clouthes' ), __( 'Overshirts and lighter weather layers.', 'wp-bbtheme-child-woo-clouthes' ), wpbb_clouthes_product_category_url( 'Outerwear' ) ),
			) ),
			array( 'title' => __( 'Complete the wardrobe', 'wp-bbtheme-child-woo-clouthes' ), 'links' => array(
				array( __( 'Trousers', 'wp-bbtheme-child-woo-clouthes' ), __( 'Denim and straightforward tailored shapes.', 'wp-bbtheme-child-woo-clouthes' ), wpbb_clouthes_product_category_url( 'Trousers' ) ),
				array( __( 'Accessories', 'wp-bbtheme-child-woo-clouthes' ), __( 'Bags, scarves, caps and small leather goods.', 'wp-bbtheme-child-woo-clouthes' ), wpbb_clouthes_product_category_url( 'Accessories' ) ),
				array( __( 'New arrivals', 'wp-bbtheme-child-woo-clouthes' ), __( 'Browse the latest pieces in the collection.', 'wp-bbtheme-child-woo-clouthes' ), $shop ),
			) ),
			array( 'title' => __( 'Useful information', 'wp-bbtheme-child-woo-clouthes' ), 'links' => array(
				array( __( 'Materials & care', 'wp-bbtheme-child-woo-clouthes' ), __( 'Understand fabric, care and repeat wear.', 'wp-bbtheme-child-woo-clouthes' ), wp_theme_demo_page_url( 'services' ) ),
				array( __( 'Our story', 'wp-bbtheme-child-woo-clouthes' ), __( 'The principles behind the collection.', 'wp-bbtheme-child-woo-clouthes' ), wp_theme_demo_page_url( 'about' ) ),
				array( __( 'My account', 'wp-bbtheme-child-woo-clouthes' ), __( 'Orders, addresses and account details.', 'wp-bbtheme-child-woo-clouthes' ), function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url() ),
			) ),
		),
	);
	return $definitions;
}
add_filter( 'wp_theme_demo_mega_menu_definitions', 'wpbb_clouthes_mega_menu_definitions', 20, 2 );

/** v3.5 sector editorial labels. */
function wpbb_clothes_blog_profile_v35( $profile ) {
    if ( ( $profile['id'] ?? '' ) !== 'clothes' ) return $profile;
    $profile['blog_eyebrow'] = __( 'Journal', 'wp-bbtheme-child-woo-clouthes' );
    $profile['blog_archive_title'] = __( 'Style notes, materials and care for a more useful wardrobe.', 'wp-bbtheme-child-woo-clouthes' );
    $profile['blog_archive_intro'] = __( 'Editorial guidance on repeat wear, thoughtful materials, care and pieces that earn their place.', 'wp-bbtheme-child-woo-clouthes' );
    return $profile;
}
add_filter( 'wp_theme_demo_profile', 'wpbb_clothes_blog_profile_v35', 90 );

/** v3.6: classic WooCommerce PHP shells for the full customer journey. */
function wpbb_clouthes_woocommerce_support_v36() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'wpbb_clouthes_woocommerce_support_v36', 30 );

function wpbb_clouthes_woocommerce_legacy_template_v36( $template ) {
    if ( is_admin() || ! function_exists( 'WC' ) || wp_doing_ajax() || is_feed() ) return $template;
    $base = trailingslashit( get_stylesheet_directory() ) . 'woocommerce-legacy/';
    $candidate = '';
    if ( function_exists( 'is_cart' ) && is_cart() ) $candidate = 'cart.php';
    elseif ( function_exists( 'is_checkout' ) && is_checkout() ) $candidate = 'checkout.php';
    elseif ( function_exists( 'is_account_page' ) && is_account_page() ) $candidate = 'account.php';
    elseif ( function_exists( 'is_product' ) && is_product() ) $candidate = 'product.php';
    elseif ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) ) $candidate = 'catalog.php';
    if ( $candidate && is_readable( $base . $candidate ) ) return $base . $candidate;
    return $template;
}
add_filter( 'template_include', 'wpbb_clouthes_woocommerce_legacy_template_v36', 99 );

function wpbb_clouthes_woocommerce_legacy_body_class_v36( $classes ) {
    if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) $classes[] = 'wp-theme-uses-woo-legacy-shell';
    return $classes;
}
add_filter( 'body_class', 'wpbb_clouthes_woocommerce_legacy_body_class_v36' );

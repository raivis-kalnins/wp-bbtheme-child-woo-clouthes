<?php
defined('ABSPATH') || exit;

require_once __DIR__ . '/inc/frontend-password-protection.php';
function wpbb_clouthes_project_mode($mode){ return 'woocommerce'; }
add_filter('wp_theme_project_mode','wpbb_clouthes_project_mode');
function wpbb_clouthes_woo_profile($profile){ return 'store'; }
add_filter('wp_theme_woo_support_default_profile','wpbb_clouthes_woo_profile');

function wpbb_clouthes_assets() {
    $theme = wp_get_theme();
    $manifest = get_stylesheet_directory() . '/dist/.vite/manifest.json';
    if (!is_readable($manifest)) return;
    $data = json_decode((string) file_get_contents($manifest), true);
    if (!is_array($data)) return;
    if (!empty($data['src/scss/public.scss']['file'])) {
        wp_enqueue_style('wpbb_clouthes-app', get_stylesheet_directory_uri() . '/dist/' . ltrim($data['src/scss/public.scss']['file'], '/'), array(), $theme->get('Version'));
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
		'industries' => array( __( 'Women', 'wp-bbtheme-child-woo-clouthes' ), __( 'Men', 'wp-bbtheme-child-woo-clouthes' ), __( 'Kids', 'wp-bbtheme-child-woo-clouthes' ), __( 'Accessories', 'wp-bbtheme-child-woo-clouthes' ) ),
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
        array( 'variable', 'Women Relaxed Poplin Shirt', 'Women', 72, 'An easy women’s poplin shirt with a softly tailored shape.' ),
        array( 'variable', 'Women Merino Cardigan', 'Women', 98, 'Fine merino layering with a clean cropped proportion.' ),
        array( 'variable', 'Kids Cotton Tee', 'Kids', 24, 'Soft midweight cotton jersey made for everyday movement.' ),
        array( 'variable', 'Kids Lightweight Overshirt', 'Kids', 58, 'A practical lightweight layer with easy patch pockets.' ),
	);
}
add_filter( 'wp_theme_woo_demo_product_data', 'wpbb_clouthes_demo_products' );

function wpbb_clouthes_demo_product_image( $path, $product, $index ) {
	$images = array(
		'relaxed-cotton-shirt.jpg', 'classic-oxford-shirt.jpg', 'essential-t-shirt.jpg', 'merino-crew-knit.jpg',
		'straight-leg-trouser.jpg', 'everyday-denim.jpg', 'canvas-weekend-bag.jpg', 'leather-card-holder.jpg',
		'wool-scarf.jpg', 'cotton-cap.jpg', 'utility-overshirt.jpg', 'lightweight-parka.jpg',
        'relaxed-cotton-shirt.jpg', 'merino-crew-knit.jpg', 'essential-t-shirt.jpg', 'utility-overshirt.jpg',
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
		array( '16', __( 'Curated pieces', 'wp-bbtheme-child-woo-clouthes' ) ),
		array( '3', __( 'Women, men and kids edits', 'wp-bbtheme-child-woo-clouthes' ) ),
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
            array( 'title' => __( 'Shop by wearer', 'wp-bbtheme-child-woo-clouthes' ), 'links' => array(
                array( __( 'Women', 'wp-bbtheme-child-woo-clouthes' ), __( 'Relaxed shirting, knitwear and considered layers.', 'wp-bbtheme-child-woo-clouthes' ), wpbb_clouthes_product_category_url( 'Women' ) ),
                array( __( 'Men', 'wp-bbtheme-child-woo-clouthes' ), __( 'Everyday shirts, trousers, denim and outerwear.', 'wp-bbtheme-child-woo-clouthes' ), wpbb_clouthes_product_category_url( 'Men' ) ),
                array( __( 'Kids', 'wp-bbtheme-child-woo-clouthes' ), __( 'Comfortable everyday pieces with easy sizing.', 'wp-bbtheme-child-woo-clouthes' ), wpbb_clouthes_product_category_url( 'Kids' ) ),
            ) ),
            array( 'title' => __( 'Shop by piece', 'wp-bbtheme-child-woo-clouthes' ), 'links' => array(
                array( __( 'Outerwear', 'wp-bbtheme-child-woo-clouthes' ), __( 'Overshirts and lighter weather layers.', 'wp-bbtheme-child-woo-clouthes' ), wpbb_clouthes_product_category_url( 'Outerwear' ) ),
                array( __( 'Accessories', 'wp-bbtheme-child-woo-clouthes' ), __( 'Bags, scarves, caps and small leather goods.', 'wp-bbtheme-child-woo-clouthes' ), wpbb_clouthes_product_category_url( 'Accessories' ) ),
                array( __( 'New arrivals', 'wp-bbtheme-child-woo-clouthes' ), __( 'Browse the newest pieces across every edit.', 'wp-bbtheme-child-woo-clouthes' ), $shop ),
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

/** v3.8.10.7: seed clear Women / Men / Kids shop edits for the starter. */
function wpbb_clouthes_seed_wearer_categories_v107() {
    if ( ! taxonomy_exists( 'product_cat' ) ) return;
    $ids = array();
    foreach ( array( 'Women', 'Men', 'Kids' ) as $name ) {
        $term = get_term_by( 'name', $name, 'product_cat' );
        if ( ! $term ) {
            $created = wp_insert_term( $name, 'product_cat' );
            if ( ! is_wp_error( $created ) ) $ids[$name] = (int) $created['term_id'];
        } else $ids[$name] = (int) $term->term_id;
    }
    $map = array(
        'Women Relaxed Poplin Shirt'=>'Women','Women Merino Cardigan'=>'Women',
        'Kids Cotton Tee'=>'Kids','Kids Lightweight Overshirt'=>'Kids',
        'Relaxed Cotton Shirt'=>'Men','Classic Oxford Shirt'=>'Men','Essential T-Shirt'=>'Men','Merino Crew Knit'=>'Men',
        'Straight Leg Trouser'=>'Men','Everyday Denim'=>'Men','Utility Overshirt'=>'Men','Lightweight Parka'=>'Men',
    );
    foreach ( $map as $title => $group ) {
        $product = get_page_by_title( $title, OBJECT, 'product' );
        if ( $product && ! empty( $ids[$group] ) ) wp_set_object_terms( $product->ID, array( $ids[$group] ), 'product_cat', true );
    }
}
add_action( 'wp_theme_after_demo_import', 'wpbb_clouthes_seed_wearer_categories_v107', 35 );


/** v3.8.10.7: complete clothing single-product editorial content. */
function wpbb_clouthes_seed_product_content_v107( $page_id = 0, $profile = array() ) {
    if ( ! post_type_exists( 'product' ) ) return;
    foreach ( get_posts(array('post_type'=>'product','post_status'=>'publish','posts_per_page'=>-1,'fields'=>'ids')) as $id ) {
        if ( trim( wp_strip_all_tags( (string) get_post_field('post_content',$id) ) ) ) continue;
        $content='<h2>Fit and details</h2><p>A considered wardrobe piece with practical fabric, fit and care information presented in one place.</p><h2>Styling notes</h2><p>Designed to layer easily with the Women, Men and Kids edits in the starter catalogue.</p><h2>Care</h2><p>Follow the garment care label and store clean and dry between wears.</p>';
        wp_update_post(array('ID'=>$id,'post_content'=>$content));
    }
}
add_action('wp_theme_after_demo_import','wpbb_clouthes_seed_product_content_v107',45,2);

/**
 * v3.8.10.20: keep editable Mega Menu content out of public discovery / SEO.
 * The parent already registers these objects as private; child filters make the
 * intent explicit for Core XML sitemaps and common SEO plugins too.
 */
function wpbb_child_private_megamenu_post_type_args( $args, $post_type ) {
    if ( 'megamenu' !== $post_type ) return $args;
    $args['public'] = false;
    $args['publicly_queryable'] = false;
    $args['exclude_from_search'] = true;
    $args['has_archive'] = false;
    $args['rewrite'] = false;
    $args['query_var'] = false;
    return $args;
}
add_filter( 'register_post_type_args', 'wpbb_child_private_megamenu_post_type_args', 20, 2 );

function wpbb_child_private_megamenu_taxonomy_args( $args, $taxonomy ) {
    if ( 'megamenu-cat' !== $taxonomy ) return $args;
    $args['public'] = false;
    $args['publicly_queryable'] = false;
    $args['rewrite'] = false;
    $args['query_var'] = false;
    return $args;
}
add_filter( 'register_taxonomy_args', 'wpbb_child_private_megamenu_taxonomy_args', 20, 2 );

function wpbb_child_core_sitemap_post_types( $post_types ) {
    unset( $post_types['megamenu'] );
    return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'wpbb_child_core_sitemap_post_types', 20 );

function wpbb_child_core_sitemap_taxonomies( $taxonomies ) {
    unset( $taxonomies['megamenu-cat'] );
    return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'wpbb_child_core_sitemap_taxonomies', 20 );

function wpbb_child_mega_robots( $robots ) {
    if ( is_singular( 'megamenu' ) || is_tax( 'megamenu-cat' ) ) {
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
    }
    return $robots;
}
add_filter( 'wp_robots', 'wpbb_child_mega_robots', 20 );

function wpbb_child_yoast_exclude_megamenu_post_type( $excluded, $post_type ) {
    return 'megamenu' === $post_type ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_post_type', 'wpbb_child_yoast_exclude_megamenu_post_type', 20, 2 );

function wpbb_child_yoast_exclude_megamenu_taxonomy( $excluded, $taxonomy ) {
    return 'megamenu-cat' === $taxonomy ? true : $excluded;
}
add_filter( 'wpseo_sitemap_exclude_taxonomy', 'wpbb_child_yoast_exclude_megamenu_taxonomy', 20, 2 );

function wpbb_child_yoast_mega_robots( $robots ) {
    if ( is_singular( 'megamenu' ) || is_tax( 'megamenu-cat' ) ) return 'noindex, nofollow';
    return $robots;
}
add_filter( 'wpseo_robots', 'wpbb_child_yoast_mega_robots', 20 );


/**
 * v3.8.10.21: global request-a-quote UI is opt-in by child theme.
 * Sector themes with their own quote journeys can keep it; the rest do not
 * expose an unrelated floating "My Quote" control or public route.
 */
if ( ! function_exists( 'wpbb_child_request_quote_enabled' ) ) {
    function wpbb_child_request_quote_enabled() {
        $enabled_themes = array(
            'wp-bbtheme-child-automotive',
            'wp-bbtheme-child-building-services',
            'wp-bbtheme-child-insurance',
            'wp-bbtheme-child-logistics',
            'wp-bbtheme-child-medicine',
            'wp-bbtheme-child-woo-tech-shop',
        );
        $enabled = in_array( get_stylesheet(), $enabled_themes, true );
        return (bool) apply_filters( 'wpbb_child_request_quote_enabled', $enabled, get_stylesheet() );
    }
}

function wpbb_child_request_quote_body_class( $classes ) {
    $classes[] = wpbb_child_request_quote_enabled() ? 'wpbb-request-quote-enabled' : 'wpbb-request-quote-disabled';
    return $classes;
}
add_filter( 'body_class', 'wpbb_child_request_quote_body_class', 30 );

function wpbb_child_request_quote_menu_items( $items ) {
    if ( wpbb_child_request_quote_enabled() ) return $items;
    $target = trim( (string) wp_parse_url( home_url( '/request-a-quote/' ), PHP_URL_PATH ), '/' );
    foreach ( $items as $key => $item ) {
        $path = trim( (string) wp_parse_url( $item->url, PHP_URL_PATH ), '/' );
        if ( $target && $path === $target ) unset( $items[ $key ] );
    }
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'wpbb_child_request_quote_menu_items', 30 );

function wpbb_child_request_quote_disable_route() {
    if ( wpbb_child_request_quote_enabled() ) return;
    $request = isset( $GLOBALS['wp']->request ) ? trim( (string) $GLOBALS['wp']->request, '/' ) : '';
    if ( ! is_page( 'request-a-quote' ) && 'request-a-quote' !== $request ) return;

    global $wp_query;
    if ( $wp_query ) $wp_query->set_404();
    status_header( 404 );
    nocache_headers();
    $template = get_404_template();
    if ( $template ) {
        include $template;
        exit;
    }
    wp_die( esc_html__( 'Page not found.', 'wp-bbtheme-child' ), esc_html__( 'Not found', 'wp-bbtheme-child' ), array( 'response' => 404 ) );
}
add_action( 'template_redirect', 'wpbb_child_request_quote_disable_route', 1 );

function wpbb_child_request_quote_sitemap_args( $args, $post_type ) {
    if ( wpbb_child_request_quote_enabled() || 'page' !== $post_type ) return $args;
    $page = get_page_by_path( 'request-a-quote' );
    if ( $page ) {
        $excluded = isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array();
        $excluded[] = (int) $page->ID;
        $args['post__not_in'] = array_values( array_unique( $excluded ) );
    }
    return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'wpbb_child_request_quote_sitemap_args', 30, 2 );

require_once get_stylesheet_directory() . '/inc/seo-guardrails.php';

/** v3.8.10.24: identify generated legal pages independently of translated slugs. */
function wpbb_child_legal_page_body_class_v381024( $classes ) {
    if ( ! is_page() ) return $classes;
    $post = get_queried_object();
    if ( ! $post instanceof WP_Post ) return $classes;

    $is_legal = function_exists( 'is_privacy_policy' ) && is_privacy_policy();
    if ( ! $is_legal && false !== strpos( (string) $post->post_content, 'wp-theme-legal-section' ) ) {
        $is_legal = true;
    }
    if ( $is_legal ) $classes[] = 'wpbb-legal-page';
    return array_values( array_unique( $classes ) );
}
add_filter( 'body_class', 'wpbb_child_legal_page_body_class_v381024', 40 );

/** v3.8.10.25: remove generated empty spacing without touching authored copy. */
if ( ! function_exists( 'wpbb_child_remove_empty_paragraphs_v381025' ) ) {
    function wpbb_child_remove_empty_paragraphs_v381025( $content ) {
        if ( is_admin() || ! is_string( $content ) || '' === $content ) return $content;
        return (string) preg_replace(
            '~<p(?:\\s[^>]*)?>(?:\\s|&nbsp;|&#160;|<br\\s*/?>)*</p>~i',
            '',
            $content
        );
    }
}
add_filter( 'the_content', 'wpbb_child_remove_empty_paragraphs_v381025', 120 );

/** v3.8.10.25: do not output a completely empty CTA block above the footer. */
if ( ! function_exists( 'wpbb_child_remove_empty_cta_v381025' ) ) {
    function wpbb_child_remove_empty_cta_v381025( $block_content, $block ) {
        if ( empty( $block['blockName'] ) || 'wpbb/cta-section' !== $block['blockName'] || ! is_string( $block_content ) ) return $block_content;
        if ( preg_match( '~<(?:img|picture|video|iframe|form|button|a)\\b~i', $block_content ) ) return $block_content;
        $plain = trim( html_entity_decode( wp_strip_all_tags( $block_content ), ENT_QUOTES | ENT_HTML5, get_bloginfo( 'charset' ) ) );
        return '' === $plain ? '' : $block_content;
    }
}
add_filter( 'render_block', 'wpbb_child_remove_empty_cta_v381025', 120, 2 );



/** v3.8.10.29: make demo switching/imports self-healing across child themes. */
if ( ! function_exists( 'wpbb_child_demo_refresh_on_activation_v381029' ) ) {
    function wpbb_child_demo_refresh_on_activation_v381029() {
        // The parent importer stores one global version/profile. When a different
        // child theme is activated, invalidate that marker so its own profile is
        // imported instead of reusing the previous child's demo state.
        delete_option( 'wp_theme_demo_import_version' );
        delete_option( 'wp_theme_demo_menu_profile' );
    }
    add_action( 'after_switch_theme', 'wpbb_child_demo_refresh_on_activation_v381029', 5 );
}

if ( ! function_exists( 'wpbb_child_demo_integrity_guard_v381029' ) ) {
    function wpbb_child_demo_integrity_guard_v381029( $page_id = 0, $profile = array() ) {
        $page_id = absint( $page_id ?: get_option( 'page_on_front' ) );
        if ( ! $page_id || 'page' !== get_post_type( $page_id ) ) return;

        $content = (string) get_post_field( 'post_content', $page_id );
        // Never rewrite a real imported or edited homepage. This is only a guard
        // for the genuinely empty/near-empty page seen after switching demos.
        if ( strlen( trim( $content ) ) >= 120 ) return;

        if ( ! is_array( $profile ) ) $profile = array();
        $eyebrow = (string) ( $profile['eyebrow'] ?? __( 'Welcome', 'wp-theme' ) );
        $title = (string) ( $profile['hero_title'] ?? get_bloginfo( 'name' ) );
        $intro = (string) ( $profile['hero_text'] ?? __( 'A practical WordPress starter site ready to edit.', 'wp-theme' ) );
        $primary_label = (string) ( $profile['primary_label'] ?? __( 'Get started', 'wp-theme' ) );
        $primary_url = (string) ( $profile['primary_url'] ?? home_url( '/contact/' ) );
        $secondary_label = (string) ( $profile['secondary_label'] ?? __( 'Explore', 'wp-theme' ) );
        $secondary_url = (string) ( $profile['secondary_url'] ?? home_url( '/services/' ) );
        $services_heading = (string) ( $profile['services_heading'] ?? __( 'Useful services, clearly presented.', 'wp-theme' ) );
        $about_title = (string) ( $profile['about_title'] ?? __( 'A flexible starting point for the real site.', 'wp-theme' ) );
        $about_text = (string) ( $profile['about_text'] ?? $intro );
        $hero_image = esc_url( (string) ( $profile['hero_image'] ?? '' ) );
        $about_image = esc_url( (string) ( $profile['about_image'] ?? $hero_image ) );
        $services = ! empty( $profile['services'] ) && is_array( $profile['services'] ) ? array_slice( $profile['services'], 0, 4 ) : array();
        $stats = ! empty( $profile['stats'] ) && is_array( $profile['stats'] ) ? array_slice( $profile['stats'], 0, 4 ) : array();

        $out = '<!-- wp:wpbb/bootstrap-div {"containerClass":"","utilityClasses":"wp-theme-section-shell wp-theme-sector-hero wp-theme-demo-repair","className":"wpbb-v62-section"} --><!-- wp:wpbb/row {"containerClass":"container","customClasses":"align-items-center"} --><!-- wp:wpbb/column {"xs":12,"lg":6} --><p class="wp-theme-sector-eyebrow">' . esc_html( $eyebrow ) . '</p><h1>' . esc_html( $title ) . '</h1><p class="wp-theme-sector-lead">' . esc_html( $intro ) . '</p><div class="wp-theme-demo-buttons"><a class="btn btn-primary" href="' . esc_url( $primary_url ) . '">' . esc_html( $primary_label ) . '</a><a class="btn btn-outline-primary" href="' . esc_url( $secondary_url ) . '">' . esc_html( $secondary_label ) . '</a></div><!-- /wp:wpbb/column -->';
        if ( $hero_image ) $out .= '<!-- wp:wpbb/column {"xs":12,"lg":6} --><figure class="wp-theme-sector-page-image"><img src="' . $hero_image . '" alt="" loading="eager" decoding="async"></figure><!-- /wp:wpbb/column -->';
        $out .= '<!-- /wp:wpbb/row --><!-- /wp:wpbb/bootstrap-div -->';

        if ( 'automotive' === ( $profile['id'] ?? '' ) ) {
            $out .= '<!-- wp:wpbb/bootstrap-div {"containerClass":"","utilityClasses":"wp-theme-section-shell wpbb-automotive-finder-section","className":"wpbb-v62-section"} --><!-- wp:wpbb/row {"containerClass":"container"} --><!-- wp:wpbb/column {"xs":12} --><!-- wp:wpbb/sector-finder {"context":"automotive","limit":8} /--><!-- /wp:wpbb/column --><!-- /wp:wpbb/row --><!-- /wp:wpbb/bootstrap-div -->';
        }

        $out .= '<!-- wp:wpbb/bootstrap-div {"containerClass":"","utilityClasses":"wp-theme-section-shell wp-theme-services-section","className":"wpbb-v62-section"} --><!-- wp:wpbb/row {"containerClass":"container"} --><!-- wp:wpbb/column {"xs":12} --><p class="wp-theme-sector-eyebrow">' . esc_html( (string) ( $profile['services_eyebrow'] ?? __( 'Services', 'wp-theme' ) ) ) . '</p><h2>' . esc_html( $services_heading ) . '</h2><!-- wp:wpbb/row {"gutterX":"gx-4","gutterY":"gy-4"} -->';
        foreach ( $services as $service ) {
            $service_title = is_array( $service ) ? (string) ( $service[0] ?? '' ) : '';
            $service_text = is_array( $service ) ? (string) ( $service[1] ?? '' ) : '';
            if ( '' === trim( $service_title ) ) continue;
            $out .= '<!-- wp:wpbb/column {"xs":12,"md":6,"lg":3} --><article class="wp-theme-sector-card"><h3>' . esc_html( $service_title ) . '</h3><p>' . esc_html( $service_text ) . '</p></article><!-- /wp:wpbb/column -->';
        }
        $out .= '<!-- /wp:wpbb/row --><!-- /wp:wpbb/column --><!-- /wp:wpbb/row --><!-- /wp:wpbb/bootstrap-div -->';

        $out .= '<!-- wp:wpbb/bootstrap-div {"containerClass":"","utilityClasses":"wp-theme-section-shell wp-theme-about-section","className":"wpbb-v62-section"} --><!-- wp:wpbb/row {"containerClass":"container","customClasses":"align-items-center"} -->';
        if ( $about_image ) $out .= '<!-- wp:wpbb/column {"xs":12,"lg":6} --><figure class="wp-theme-sector-page-image"><img src="' . $about_image . '" alt="" loading="lazy" decoding="async"></figure><!-- /wp:wpbb/column -->';
        $out .= '<!-- wp:wpbb/column {"xs":12,"lg":6} --><p class="wp-theme-sector-eyebrow">' . esc_html( (string) ( $profile['about_eyebrow'] ?? __( 'About', 'wp-theme' ) ) ) . '</p><h2>' . esc_html( $about_title ) . '</h2><p class="wp-theme-sector-lead">' . esc_html( $about_text ) . '</p><!-- /wp:wpbb/column --><!-- /wp:wpbb/row --><!-- /wp:wpbb/bootstrap-div -->';

        if ( $stats ) {
            $out .= '<!-- wp:wpbb/bootstrap-div {"containerClass":"","utilityClasses":"wp-theme-section-shell wp-theme-sector-proof","className":"wpbb-v62-section"} --><!-- wp:wpbb/row {"containerClass":"container","gutterX":"gx-3","gutterY":"gy-3"} -->';
            foreach ( $stats as $stat ) {
                $number = is_array( $stat ) ? (string) ( $stat[0] ?? '' ) : '';
                $label = is_array( $stat ) ? (string) ( $stat[1] ?? '' ) : '';
                $out .= '<!-- wp:wpbb/column {"xs":6,"lg":3} --><div class="wp-theme-sector-proof__item"><h3>' . esc_html( $number ) . '</h3><p>' . esc_html( $label ) . '</p></div><!-- /wp:wpbb/column -->';
            }
            $out .= '<!-- /wp:wpbb/row --><!-- /wp:wpbb/bootstrap-div -->';
        }

        $out .= '<!-- wp:wpbb/cta-section {"title":"' . esc_attr( (string) ( $profile['cta_title'] ?? __( 'Ready to make it yours?', 'wp-theme' ) ) ) . '","titleTag":"h2","text":"' . esc_attr( (string) ( $profile['cta_text'] ?? $intro ) ) . '","buttonText":"' . esc_attr( $primary_label ) . '","buttonUrl":"' . esc_url( $primary_url ) . '","className":"wp-theme-home-cta wp-theme-home-cta--bbuilder"} /-->';

        wp_update_post( array( 'ID' => $page_id, 'post_content' => $out ) );
        update_post_meta( $page_id, '_wp_theme_demo_repaired_381029', current_time( 'mysql' ) );
    }
    add_action( 'wp_theme_after_demo_import', 'wpbb_child_demo_integrity_guard_v381029', 99, 2 );
}


/* v3.8.10.30 visual icon configuration */
function wpbb_woo_clouthes_visual_icon_config() {
    $config = array( 'base' => get_stylesheet_directory_uri(), 'icons' => array('shirt', 'briefcase', 'truck', 'users', 'camera', 'map-pin', 'ruler-measure', 'shield') );
    echo '<script>window.wpbbChildVisuals=' . wp_json_encode( $config ) . ';</script>';
}
add_action( 'wp_footer', 'wpbb_woo_clouthes_visual_icon_config', 1 );


/* v3.8.10.30: realistic demo blog featured images. Runs only after the theme's explicit demo import. */
function wpbb_woo_clouthes_demo_blog_photo_attachment( $filename, $title ) {
    $slug = sanitize_title( pathinfo( $filename, PATHINFO_FILENAME ) );
    $existing = get_page_by_path( 'woo-clouthes-blog-' . $slug, OBJECT, 'attachment' );
    if ( $existing ) {
        if ( function_exists( 'wpbb_woo_clouthes_refresh_bundled_attachment_v381041' ) ) wpbb_woo_clouthes_refresh_bundled_attachment_v381041( (int) $existing->ID, 'assets/img/blog' );
        return (int) $existing->ID;
    }
    $source = get_stylesheet_directory() . '/assets/img/blog/' . basename( $filename );
    if ( ! is_readable( $source ) ) return 0;
    $uploads = wp_upload_dir();
    $dir = trailingslashit( $uploads['basedir'] ) . 'woo-clouthes-blog';
    wp_mkdir_p( $dir );
    $target = $dir . '/' . basename( $filename );
    if ( ! file_exists( $target ) ) copy( $source, $target );
    $filetype = wp_check_filetype( $target );
    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';
    $id = wp_insert_attachment( array(
        'post_mime_type' => $filetype['type'] ?: 'image/jpeg',
        'post_title' => $title,
        'post_name' => 'woo-clouthes-blog-' . $slug,
        'post_status' => 'inherit',
    ), $target );
    if ( $id && ! is_wp_error( $id ) ) {
        if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';
        $meta = wpbb_child_381048_generate_attachment_metadata( $id, $target );
        if ( $meta ) wp_update_attachment_metadata( $id, $meta );
        update_post_meta( $id, '_wp_attachment_image_alt', $title );
        return (int) $id;
    }
    return 0;
}
function wpbb_woo_clouthes_seed_demo_blog_photos( $page_id = 0, $profile = array() ) {
    $posts = get_posts( array( 'post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>12, 'orderby'=>'date', 'order'=>'DESC' ) );
    if ( ! $posts ) return;
    $images = array( 'blog-1.jpg','blog-2.jpg','blog-3.jpg','blog-4.jpg','blog-5.jpg','blog-6.jpg' );
    foreach ( $posts as $index => $post ) {
        $filename = $images[ $index % count( $images ) ];
        $attachment = wpbb_woo_clouthes_demo_blog_photo_attachment( $filename, get_the_title( $post ) );
        if ( $attachment ) set_post_thumbnail( $post->ID, $attachment );
    }
}
add_action( 'wp_theme_after_demo_import', 'wpbb_woo_clouthes_seed_demo_blog_photos', 70, 2 );


/** v3.8.10.31: apply bundled realistic media to already-imported demos after theme upgrade. */

/**
 * Refresh an already-imported demo attachment from the current child-theme asset.
 *
 * Image optimisation may have changed `_wp_attached_file` from e.g. item-1.jpg to
 * item-1.avif/webp. Resolve the bundled source by filename stem instead of requiring
 * the child theme to ship every generated format, then regenerate all WP sub-sizes.
 */
function wpbb_woo_clouthes_refresh_bundled_attachment_v381041( $attachment_id, $asset_dir ) {
    $attachment_id = absint( $attachment_id );
    if ( ! $attachment_id || 'attachment' !== get_post_type( $attachment_id ) ) return false;

    $attached = (string) get_post_meta( $attachment_id, '_wp_attached_file', true );
    $stem = pathinfo( basename( $attached ), PATHINFO_FILENAME );
    if ( '' === $stem ) return false;

    $base = trailingslashit( get_stylesheet_directory() ) . trailingslashit( $asset_dir ) . $stem;
    $source = '';
    foreach ( array( '.jpg', '.jpeg', '.png', '.webp', '.avif' ) as $extension ) {
        if ( is_readable( $base . $extension ) ) {
            $source = $base . $extension;
            break;
        }
    }
    if ( ! $source ) return false;

    $target = get_attached_file( $attachment_id );
    if ( ! $target ) return false;

    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';

    $source_ext = strtolower( (string) pathinfo( $source, PATHINFO_EXTENSION ) );
    $target_ext = strtolower( (string) pathinfo( $target, PATHINFO_EXTENSION ) );
    $written = false;

    if ( $source_ext === $target_ext ) {
        $written = (bool) @copy( $source, $target );
    } else {
        $target_type = wp_check_filetype( $target );
        $target_mime = ! empty( $target_type['type'] ) ? (string) $target_type['type'] : '';
        $editor = wp_get_image_editor( $source );
        if ( ! is_wp_error( $editor ) && 0 === strpos( $target_mime, 'image/' ) ) {
            $saved = $editor->save( $target, $target_mime );
            $written = ! is_wp_error( $saved ) && is_readable( $target );
        }
    }

    // Some hosts can read AVIF/WebP but cannot encode it. Fall back to the bundled
    // source extension and update WordPress to the new original file explicitly.
    if ( ! $written ) {
        $fallback = trailingslashit( dirname( $target ) ) . $stem . '.' . $source_ext;
        if ( ! @copy( $source, $fallback ) ) return false;
        update_attached_file( $attachment_id, $fallback );
        $filetype = wp_check_filetype( $fallback );
        if ( ! empty( $filetype['type'] ) ) {
            wp_update_post( array( 'ID' => $attachment_id, 'post_mime_type' => $filetype['type'] ) );
        }
        $target = $fallback;
    }

    // Remove old generated sizes first. Otherwise stale JPG thumbnails can remain
    // referenced after the original was converted to AVIF/WebP by an optimiser.
    $old_meta = wp_get_attachment_metadata( $attachment_id );
    if ( is_array( $old_meta ) && ! empty( $old_meta['sizes'] ) && is_array( $old_meta['sizes'] ) ) {
        foreach ( $old_meta['sizes'] as $old_size ) {
            if ( empty( $old_size['file'] ) ) continue;
            $old_file = trailingslashit( dirname( $target ) ) . basename( (string) $old_size['file'] );
            if ( is_file( $old_file ) && wp_normalize_path( $old_file ) !== wp_normalize_path( $target ) ) @unlink( $old_file );
        }
    }

    $meta = wpbb_child_381048_generate_attachment_metadata( $attachment_id, $target );
    if ( $meta ) wp_update_attachment_metadata( $attachment_id, $meta );
    clean_attachment_cache( $attachment_id );
    return true;
}

function wpbb_woo_clouthes_realistic_media_upgrade_v381041() {
    if ( ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) ) return;
    $done_key = 'wpbb_woo_clouthes_realistic_media_upgrade_v381041';
    if ( get_option( $done_key ) ) return;
    if ( ! current_user_can( 'manage_options' ) ) return;

    $pairs = array(array('woo-clouthes-blog','assets/img/blog'));
    foreach ( $pairs as $pair ) {
        $upload_prefix = $pair[0];
        $asset_dir = $pair[1];
        $ids = get_posts( array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => array( array( 'key'=>'_wp_attached_file', 'value'=>$upload_prefix . '/', 'compare'=>'LIKE' ) ),
        ) );
        foreach ( $ids as $attachment_id ) {
            wpbb_woo_clouthes_refresh_bundled_attachment_v381041( $attachment_id, $asset_dir );
        }
    }
    if ( function_exists( 'wpbb_woo_clouthes_seed_demo_blog_photos' ) ) wpbb_woo_clouthes_seed_demo_blog_photos( 0, array() );
    if ( post_type_exists( 'product' ) && function_exists( 'wpbb_clouthes_demo_products' ) && function_exists( 'wpbb_clouthes_demo_product_image' ) ) {
        $products = wpbb_clouthes_demo_products( array() );
        foreach ( $products as $index => $product_data ) {
            $title = isset( $product_data[1] ) ? (string) $product_data[1] : '';
            if ( '' === $title ) continue;
            $matches = get_posts( array( 'post_type'=>'product', 'post_status'=>'any', 'posts_per_page'=>1, 'title'=>$title ) );
            if ( ! $matches ) continue;
            $source = wpbb_clouthes_demo_product_image( '', $product_data, $index );
            if ( ! $source || ! is_readable( $source ) ) continue;
            $attachment_slug = 'wpbb-woo-clouthes-realistic-' . sanitize_title( $title );
            $existing = get_posts( array( 'post_type'=>'attachment', 'name'=>$attachment_slug, 'post_status'=>'inherit', 'posts_per_page'=>1, 'fields'=>'ids' ) );
            $attachment_id = $existing ? (int) $existing[0] : 0;
            if ( ! $attachment_id ) {
                $uploads = wp_upload_dir();
                $dir = trailingslashit( $uploads['basedir'] ) . 'wpbb-woo-clouthes-realistic';
                wp_mkdir_p( $dir );
                $target = $dir . '/' . basename( $source );
                if ( ! @copy( $source, $target ) ) continue;
                $filetype = wp_check_filetype( $target );
                if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';
                $attachment_id = wp_insert_attachment( array( 'post_mime_type'=>$filetype['type'] ?: 'image/jpeg', 'post_title'=>$title, 'post_name'=>$attachment_slug, 'post_status'=>'inherit' ), $target );
            } else {
                $target = get_attached_file( $attachment_id );
                if ( $target ) @copy( $source, $target );
            }
            if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                $target = get_attached_file( $attachment_id );
                if ( $target ) {
                    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';
                    $meta = wpbb_child_381048_generate_attachment_metadata( $attachment_id, $target );
                    if ( $meta ) wp_update_attachment_metadata( $attachment_id, $meta );
                }
                set_post_thumbnail( $matches[0]->ID, $attachment_id );
            }
        }
    }
    update_option( $done_key, current_time( 'mysql' ), false );
}
add_action( 'admin_init', 'wpbb_woo_clouthes_realistic_media_upgrade_v381041', 120 );


/* v3.8.10.42: full-width single-column demo rows + optional frontend demo protection. */
function wpbb_child_381042_repair_single_columns( $blocks ) {
    foreach ( $blocks as &$block ) {
        if ( 'wpbb/row' === ( $block['blockName'] ?? '' ) && ! empty( $block['innerBlocks'] ) ) {
            $column_indexes = array();
            foreach ( $block['innerBlocks'] as $index => $inner ) {
                if ( 'wpbb/column' === ( $inner['blockName'] ?? '' ) ) $column_indexes[] = $index;
            }
            if ( 1 === count( $column_indexes ) ) {
                $idx = $column_indexes[0];
                $attrs = $block['innerBlocks'][ $idx ]['attrs'] ?? array();
                if ( 12 === (int) ( $attrs['xs'] ?? 12 ) ) {
                    $attrs['xs'] = 12;
                    foreach ( array( 'sm', 'md', 'lg', 'xl', 'xxl' ) as $breakpoint ) unset( $attrs[ $breakpoint ] );
                    $block['innerBlocks'][ $idx ]['attrs'] = $attrs;
                }
            }
        }
        if ( ! empty( $block['innerBlocks'] ) ) $block['innerBlocks'] = wpbb_child_381042_repair_single_columns( $block['innerBlocks'] );
    }
    unset( $block );
    return $blocks;
}

function wpbb_child_381042_repair_demo_page_widths() {
    $pages = get_posts( array(
        'post_type' => 'page', 'post_status' => 'any', 'posts_per_page' => -1,
        'meta_key' => '_wp_theme_demo_managed', 'meta_value' => '1', 'fields' => 'ids',
    ) );
    foreach ( $pages as $page_id ) {
        $content = (string) get_post_field( 'post_content', $page_id );
        if ( false === strpos( $content, 'wpbb/column' ) ) continue;
        $blocks = parse_blocks( $content );
        $repaired = serialize_blocks( wpbb_child_381042_repair_single_columns( $blocks ) );
        if ( $repaired !== $content ) wp_update_post( array( 'ID' => $page_id, 'post_content' => $repaired ) );
    }
}
add_action( 'wp_theme_after_demo_import', 'wpbb_child_381042_repair_demo_page_widths', 140 );
function wpbb_child_381042_repair_demo_page_widths_once() {
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) return;
    $key = 'wpbb_381042_single_col_' . sanitize_key( get_stylesheet() );
    if ( get_option( $key ) ) return;
    wpbb_child_381042_repair_demo_page_widths();
    update_option( $key, 1, false );
}
add_action( 'admin_init', 'wpbb_child_381042_repair_demo_page_widths_once', 40 );

/**
 * v3.8.10.43: repair shared demo alignment and force one fresh media pass.
 *
 * The previous media migration was intentionally one-shot. This release uses a
 * new per-theme marker so sites that already ran v381041 receive the current
 * child-owned room/product/project/blog images as well.
 */
if ( ! function_exists( 'wpbb_child_381043_normalize_text' ) ) {
    function wpbb_child_381043_normalize_text( $value ) {
        $value = html_entity_decode( wp_strip_all_tags( (string) $value ), ENT_QUOTES | ENT_HTML5, get_bloginfo( 'charset' ) );
        return trim( preg_replace( '/\\s+/u', ' ', $value ) );
    }
}

if ( ! function_exists( 'wpbb_child_381043_dedupe_single_body' ) ) {
    function wpbb_child_381043_dedupe_single_body( $content, $excerpt = '' ) {
        $excerpt_text = wpbb_child_381043_normalize_text( $excerpt );
        if ( '' === $excerpt_text ) return $content;

        $content_text = wpbb_child_381043_normalize_text( $content );
        if ( $content_text === $excerpt_text ) return '';

        if ( preg_match( '~^\\s*<p(?:\\s[^>]*)?>(.*?)</p>~is', (string) $content, $match ) ) {
            if ( wpbb_child_381043_normalize_text( $match[1] ) === $excerpt_text ) {
                return ltrim( substr( (string) $content, strlen( $match[0] ) ) );
            }
        }
        return $content;
    }
}

if ( ! function_exists( 'wpbb_child_381043_repair_block_alignment' ) ) {
    function wpbb_child_381043_repair_block_alignment( $blocks ) {
        foreach ( $blocks as &$block ) {
            if ( 'wpbb/row' === ( $block['blockName'] ?? '' ) ) {
                $attrs = $block['attrs'] ?? array();
                $classes = preg_split( '/\\s+/', trim( (string) ( $attrs['customClasses'] ?? '' ) ) );
                $classes = array_values( array_filter( array_map( 'sanitize_html_class', $classes ) ) );
                if ( in_array( 'wp-theme-sector-media-text', $classes, true ) ) {
                    $classes = array_values( array_diff( $classes, array( 'align-items-center', 'align-items-end' ) ) );
                    if ( ! in_array( 'align-items-start', $classes, true ) ) $classes[] = 'align-items-start';
                    $attrs['customClasses'] = implode( ' ', $classes );
                    $block['attrs'] = $attrs;
                }
            }
            if ( ! empty( $block['innerBlocks'] ) ) {
                $block['innerBlocks'] = wpbb_child_381043_repair_block_alignment( $block['innerBlocks'] );
            }
        }
        unset( $block );
        return $blocks;
    }
}

if ( ! function_exists( 'wpbb_child_381043_repair_demo_pages' ) ) {
    function wpbb_child_381043_repair_demo_pages() {
        // Repair every page that actually contains the theme's media/text row.
        // This also covers front pages imported before the managed-page marker existed.
        $page_ids = get_posts( array(
            'post_type' => 'page',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ) );
        foreach ( $page_ids as $page_id ) {
            $content = (string) get_post_field( 'post_content', $page_id );
            if ( false === strpos( $content, 'wp-theme-sector-media-text' ) ) continue;
            $repaired = serialize_blocks( wpbb_child_381043_repair_block_alignment( parse_blocks( $content ) ) );
            if ( $repaired !== $content ) {
                wp_update_post( array( 'ID' => $page_id, 'post_content' => $repaired ) );
                clean_post_cache( $page_id );
            }
        }
    }
}

if ( ! function_exists( 'wpbb_child_381043_refresh_media_once' ) ) {
    function wpbb_child_381043_refresh_media_once( $page_id = 0, $profile = array() ) {
        if ( ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) ) return;
        if ( ! current_user_can( 'manage_options' ) ) return;

        $current_stylesheet = sanitize_key( get_stylesheet() );
        $done_key = 'wpbb_child_381043_media_' . $current_stylesheet;
        $owner_key = 'wpbb_child_381043_media_owner';
        // Demo posts are shared while child themes are switched. Refresh again
        // whenever a different child theme last supplied the active media.
        if ( get_option( $done_key ) && $current_stylesheet === (string) get_option( $owner_key ) ) return;

        $defined = get_defined_functions();
        foreach ( (array) ( $defined['user'] ?? array() ) as $function_name ) {
            if ( ! preg_match( '/^wpbb_[a-z0-9_]+_realistic_media_upgrade_v381041$/', $function_name ) ) continue;
            delete_option( $function_name );
            call_user_func( $function_name );
        }

        // Correct stale titles/alt text left behind when the same demo posts were
        // reused while switching child themes.
        $post_ids = get_posts( array(
            'post_type' => 'any',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'meta_key' => '_thumbnail_id',
            'fields' => 'ids',
        ) );
        foreach ( $post_ids as $post_id ) {
            $thumbnail_id = (int) get_post_thumbnail_id( $post_id );
            if ( ! $thumbnail_id ) continue;
            $attached = (string) get_post_meta( $thumbnail_id, '_wp_attached_file', true );
            $attachment_name = (string) get_post_field( 'post_name', $thumbnail_id );
            if ( false === strpos( $attached, '-blog/' ) && 0 !== strpos( $attachment_name, 'wpbb-' ) ) continue;
            $title = get_the_title( $post_id );
            if ( '' === trim( (string) $title ) ) continue;
            wp_update_post( array( 'ID' => $thumbnail_id, 'post_title' => $title ) );
            update_post_meta( $thumbnail_id, '_wp_attachment_image_alt', $title );
            clean_post_cache( $post_id );
            clean_attachment_cache( $thumbnail_id );
        }

        wpbb_child_381043_repair_demo_pages();
        update_option( $done_key, current_time( 'mysql' ), false );
        update_option( $owner_key, $current_stylesheet, false );
    }
}
add_action( 'wp_theme_after_demo_import', 'wpbb_child_381043_refresh_media_once', 180, 2 );
add_action( 'admin_init', 'wpbb_child_381043_refresh_media_once', 130 );

/**
 * v3.8.10.45: shared rhythm, contrast, sector-media and gallery repair.
 */
require_once __DIR__ . '/inc/sector-consistency.php';

/**
 * v3.8.10.47: win the final template_include pass for WooCommerce screens.
 * Some block-template resolvers run after the older priority-99 callback and
 * can otherwise replace the complete child-owned product shell with a generic
 * single-post template.
 */
if ( ! function_exists( 'wpbb_child_381047_force_woo_legacy_template' ) ) {
    function wpbb_child_381047_force_woo_legacy_template( $template ) {
        if ( is_admin() || wp_doing_ajax() || is_feed() || ! post_type_exists( 'product' ) ) {
            return $template;
        }
        $base = trailingslashit( get_stylesheet_directory() ) . 'woocommerce-legacy/';
        $candidate = '';
        if ( ( function_exists( 'is_product' ) && is_product() ) || is_singular( 'product' ) ) {
            $candidate = 'product.php';
        } elseif ( function_exists( 'is_cart' ) && is_cart() ) {
            $candidate = 'cart.php';
        } elseif ( function_exists( 'is_checkout' ) && is_checkout() ) {
            $candidate = 'checkout.php';
        } elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
            $candidate = 'account.php';
        } elseif ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) ) {
            $candidate = 'catalog.php';
        }
        return $candidate && is_readable( $base . $candidate ) ? $base . $candidate : $template;
    }
}
add_filter( 'template_include', 'wpbb_child_381047_force_woo_legacy_template', PHP_INT_MAX );


/** v3.8.10.64: reliable WooCommerce block-theme renderers. */
if ( ! function_exists( 'wpbb_clothes_woo_support_features_v64' ) ) {
    function wpbb_clothes_woo_support_features_v64( $features ) {
        if ( ! is_array( $features ) ) $features = array();
        foreach ( array( 'shortcodes','assets','archive','taxonomy_archives','single_product','gallery_slider','stock','product_filter','variation_swatches','quote_request','product_admin','ajax_search','mini_cart' ) as $feature ) $features[$feature] = true;
        return $features;
    }
    add_filter( 'wp_theme_woo_support_features', 'wpbb_clothes_woo_support_features_v64', 20 );
}

if ( ! function_exists( 'wpbb_clothes_render_native_products_v64' ) ) {
    function wpbb_clothes_render_native_products_v64() {
        if ( shortcode_exists( 'iws_product_filter' ) && shortcode_exists( 'iws_product_filter_results' ) ) {
            return do_shortcode( '[iws_product_filter posts_per_page="12"]' ) . do_shortcode( '[iws_product_filter_results posts_per_page="12"]' );
        }
        if ( shortcode_exists( 'products' ) ) return do_shortcode( '[products limit="12" columns="3" paginate="true" orderby="menu_order" order="ASC"]' );
        ob_start();
        if ( function_exists( 'woocommerce_content' ) ) woocommerce_content();
        return ob_get_clean();
    }
}

if ( ! function_exists( 'wpbb_clothes_shop_page_v64' ) ) {
    function wpbb_clothes_shop_page_v64() {
        $is_tax = function_exists( 'is_product_taxonomy' ) && is_product_taxonomy();
        $title = $is_tax ? single_term_title( '', false ) : __( 'Shop the collection.', 'wp-bbtheme-child-woo-clouthes' );
        $eyebrow = $is_tax ? __( 'Collection', 'wp-bbtheme-child-woo-clouthes' ) : __( 'Shop', 'wp-bbtheme-child-woo-clouthes' );
        ob_start(); ?>
        <main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--catalog wp-theme-woo-archive">
          <section class="wp-theme-woo-legacy__hero"><div class="container"><p class="wp-theme-sector-eyebrow"><?php echo esc_html( $eyebrow ); ?></p><h1><?php echo esc_html( $title ); ?></h1><?php if ( $is_tax && term_description() ) : ?><div class="wp-theme-woo-legacy__intro"><?php echo wp_kses_post( term_description() ); ?></div><?php else : ?><p><?php echo esc_html( __( 'Browse everyday pieces, compare fit and move to checkout without losing context.', 'wp-bbtheme-child-woo-clouthes' ) ); ?></p><?php endif; ?></div></section>
          <div class="container wp-theme-woo-legacy__body"><div class="woocommerce wp-theme-store-grid wpbb-woo-surface wpbb-woo-catalog-surface"><?php echo wpbb_clothes_render_native_products_v64(); ?></div></div>
        </main>
        <?php return ob_get_clean();
    }
    add_shortcode( 'wpbb_clothes_shop_page', 'wpbb_clothes_shop_page_v64' );
}

if ( ! function_exists( 'wpbb_clothes_single_product_v64' ) ) {
    function wpbb_clothes_single_product_v64() {
        if ( ! function_exists( 'wc_get_template_part' ) ) return '';
        global $post, $product;
        $product_id = is_singular( 'product' ) ? get_queried_object_id() : get_the_ID();
        if ( ! $product_id ) return '';
        $post = get_post( $product_id ); if ( ! $post ) return ''; setup_postdata( $post );
        $product = wc_get_product( $product_id ); if ( ! $product ) return '';
        ob_start(); echo '<main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--product"><div class="container wp-theme-woo-legacy__body wp-theme-woo-legacy__body--product"><div class="woocommerce">';
        $stable = get_stylesheet_directory() . '/woocommerce/content-single-product.php';
        if ( is_readable( $stable ) ) require $stable; else wc_get_template_part( 'content', 'single-product' );
        echo '</div></div></main>'; wp_reset_postdata(); return ob_get_clean();
    }
    add_shortcode( 'wpbb_clothes_single_product', 'wpbb_clothes_single_product_v64' );
}

if ( ! function_exists( 'wpbb_clothes_cart_page_v64' ) ) {
    function wpbb_clothes_cart_page_v64() {
        if ( ! function_exists( 'WC' ) ) return ''; ob_start(); ?>
        <main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--cart"><section class="wp-theme-woo-legacy__hero"><div class="container"><p class="wp-theme-sector-eyebrow"><?php echo esc_html( __( 'Basket', 'wp-bbtheme-child-woo-clouthes' ) ); ?></p><h1><?php echo esc_html( __( 'Review your basket.', 'wp-bbtheme-child-woo-clouthes' ) ); ?></h1><p><?php echo esc_html( __( 'Check sizes, quantities and totals before moving to checkout.', 'wp-bbtheme-child-woo-clouthes' ) ); ?></p></div></section><div class="container wp-theme-woo-legacy__body"><div class="woocommerce wp-theme-woo-cart-shell wpbb-woo-surface wpbb-woo-cart-surface"><?php echo do_shortcode('[woocommerce_cart]'); ?></div></div></main>
        <?php return ob_get_clean();
    }
    add_shortcode( 'wpbb_clothes_cart_page', 'wpbb_clothes_cart_page_v64' );
}

if ( ! function_exists( 'wpbb_clothes_checkout_page_v64' ) ) {
    function wpbb_clothes_checkout_page_v64() {
        if ( ! function_exists( 'WC' ) ) return ''; $received = function_exists('is_wc_endpoint_url') && is_wc_endpoint_url('order-received'); ob_start(); ?>
        <main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--checkout<?php echo $received ? ' wp-theme-woo-legacy--order-received' : ''; ?>"><section class="wp-theme-woo-legacy__hero"><div class="container"><p class="wp-theme-sector-eyebrow"><?php echo esc_html($received ? __('Order','wp-bbtheme-child-woo-clouthes') : __('Checkout','wp-bbtheme-child-woo-clouthes')); ?></p><h1><?php echo esc_html($received ? __('Order details.','wp-bbtheme-child-woo-clouthes') : __('Complete your order.','wp-bbtheme-child-woo-clouthes')); ?></h1><?php if(!$received): ?><p><?php echo esc_html( __( 'Billing, delivery and payment information in one clear flow.', 'wp-bbtheme-child-woo-clouthes' ) ); ?></p><?php endif; ?></div></section><div class="container wp-theme-woo-legacy__body"><div class="woocommerce wpbb-woo-surface wpbb-woo-checkout-surface"><?php echo do_shortcode('[woocommerce_checkout]'); ?></div></div></main>
        <?php return ob_get_clean();
    }
    add_shortcode( 'wpbb_clothes_checkout_page', 'wpbb_clothes_checkout_page_v64' );
}

if ( ! function_exists( 'wpbb_clothes_account_page_v64' ) ) {
    function wpbb_clothes_account_page_v64() {
        if ( ! function_exists( 'WC' ) ) return ''; ob_start(); ?>
        <main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--account"><section class="wp-theme-woo-legacy__hero"><div class="container"><p class="wp-theme-sector-eyebrow"><?php esc_html_e('Account','wp-bbtheme-child-woo-clouthes'); ?></p><h1><?php esc_html_e('Your account.','wp-bbtheme-child-woo-clouthes'); ?></h1></div></section><div class="container wp-theme-woo-legacy__body"><div class="woocommerce wpbb-woo-surface wpbb-woo-account-surface"><?php echo do_shortcode('[woocommerce_my_account]'); ?></div></div></main>
        <?php return ob_get_clean();
    }
    add_shortcode( 'wpbb_clothes_account_page', 'wpbb_clothes_account_page_v64' );
}

// v3.8.10.64 shared BBuilder/demo consistency layer.
require_once get_stylesheet_directory() . '/inc/bbuilder-system-v62.php';

/**
 * v3.8.10.64 PWA endpoint hardening.
 *
 * The parent theme links to ?wpbb-pwa=manifest and registers
 * ?wpbb-pwa=service-worker. Serve those endpoints before the normal template
 * loader so browsers always receive the expected MIME type and valid payload.
 * The service worker intentionally has no fetch handler: this prevents stale
 * worker-cached ES modules from causing Chromium cross-world preload warnings.
 */
if ( ! function_exists( 'wpbb_child_381063_serve_pwa_endpoint' ) ) {
    function wpbb_child_381063_serve_pwa_endpoint() {
        if ( empty( $_GET['wpbb-pwa'] ) ) return;
        $mode = sanitize_key( wp_unslash( $_GET['wpbb-pwa'] ) );
        if ( ! in_array( $mode, array( 'manifest', 'service-worker' ), true ) ) return;

        while ( ob_get_level() ) {
            @ob_end_clean();
        }
        nocache_headers();
        header( 'X-Content-Type-Options: nosniff' );

        if ( 'manifest' === $mode ) {
            header( 'Content-Type: application/manifest+json; charset=UTF-8' );
            $name = trim( (string) get_bloginfo( 'name' ) );
            if ( '' === $name ) $name = 'WP Base';
            $scope = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
            if ( '' === $scope ) $scope = '/';
            $icons = array();
            foreach ( array( 192, 512 ) as $size ) {
                $file = get_stylesheet_directory() . '/assets/icons/icon-' . $size . '.png';
                if ( is_readable( $file ) ) {
                    $icons[] = array(
                        'src' => get_stylesheet_directory_uri() . '/assets/icons/icon-' . $size . '.png',
                        'sizes' => $size . 'x' . $size,
                        'type' => 'image/png',
                        'purpose' => 'any maskable',
                    );
                }
            }
            echo wp_json_encode( array(
                'name' => $name,
                'short_name' => function_exists( 'mb_substr' ) ? mb_substr( $name, 0, 24 ) : substr( $name, 0, 24 ),
                'start_url' => home_url( '/' ),
                'scope' => $scope,
                'display' => 'standalone',
                'background_color' => '#ffffff',
                'theme_color' => '#3155D9',
                'icons' => $icons,
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
            exit;
        }

        header( 'Content-Type: application/javascript; charset=UTF-8' );
        header( 'Service-Worker-Allowed: /' );
        echo "self.addEventListener('install',function(event){self.skipWaiting();});\n";
        echo "self.addEventListener('activate',function(event){event.waitUntil((async function(){try{var keys=await caches.keys();await Promise.all(keys.filter(function(k){return /^(wpbb|wp-theme|wpbase)/i.test(k);}).map(function(k){return caches.delete(k);}));}catch(e){}await self.clients.claim();})());});\n";
        exit;
    }
    add_action( 'template_redirect', 'wpbb_child_381063_serve_pwa_endpoint', -9999 );
}
// v3.8.10.75 commerce consistency and managed-demo media repair.
require_once get_stylesheet_directory() . '/inc/v74-commerce.php';
// v3.8.10.75 structural/media/Woo repair.
require_once get_stylesheet_directory() . '/inc/v75-suite.php';

// v3.8.10.81: keep interactive wp-admin saves/updates fast.
require_once get_stylesheet_directory() . '/inc/v82-suite.php';
require_once get_stylesheet_directory() . '/inc/admin-performance.php';

// v3.8.10.83 premium Jobs-aligned sector presentation and multilingual managed-demo refresh.
require_once get_stylesheet_directory() . '/inc/v83-premium-suite.php';
// v3.8.10.97 final premium Jobs-aligned suite and mobile navigation.
require_once get_stylesheet_directory() . '/inc/v97-premium-suite.php';


/** 3.8.10.98 suite-wide layout/mobile finishing layer. */
function wpbb_suite_v98_enqueue(){
    $v = wp_get_theme()->get('Version');
    wp_enqueue_style('wpbb-suite-v98', get_stylesheet_directory_uri() . '/assets/suite-v98.css', array(), $v);
    wp_enqueue_script('wpbb-suite-v98', get_stylesheet_directory_uri() . '/assets/suite-v98.js', array(), $v, true);
}
add_action('wp_enqueue_scripts','wpbb_suite_v98_enqueue',999);

// v3.8.10.99 final suite-wide grid, branding, hero and mobile finish.
require_once get_stylesheet_directory() . '/inc/v99-finish.php';

// v3.8.11.00 cookie ownership, hero/colour and mobile navigation finish.
require_once get_stylesheet_directory() . '/inc/v100-finish.php';

// v3.8.11.02 navigation, legal, colour and media correction.
require_once get_stylesheet_directory() . '/inc/v101-finish.php';

// v3.8.11.04 deterministic mobile navigation and WooCommerce/alignment finish.
require_once get_stylesheet_directory() . '/inc/v104-finish.php';

// v3.8.11.05 legal/contact grid, mobile drawer and WooCommerce template finish.
require_once get_stylesheet_directory() . '/inc/v105-finish.php';

// v3.8.11.07 final search, WooCommerce, Jobs captcha/grid and responsive repair.
require_once get_stylesheet_directory() . '/inc/v107-finish.php';

// v3.8.11.08 WooCommerce layout/polish and packaging finish.
require_once get_stylesheet_directory() . '/inc/v108-finish.php';

// v3.8.11.09 WooCommerce, media and account finalisation.
require_once get_stylesheet_directory() . '/inc/v109-finish.php';

// v3.8.11.10 media, WooCommerce, managed-page and route-facing finish.
require_once get_stylesheet_directory() . '/inc/v110-finish.php';

// v3.8.11.11 hero finder, editorial grid, mega-menu and image-quality finish.
require_once get_stylesheet_directory() . '/inc/v111-finish.php';

// v3.8.11.12 editorial grid, hero clarity and media recovery.
require_once get_stylesheet_directory() . '/inc/v112-finish.php';

// v3.8.11.13 final hero edge/clarity and editorial-grid alignment.
require_once get_stylesheet_directory() . '/inc/v113-finish.php';

// v3.8.11.14 child-only settings, editor, legal, editorial and hero finish.
require_once get_stylesheet_directory() . '/inc/v114-finish.php';

// v3.8.11.15 final mega-menu, hero/media, quote and BBuilder repair.
require_once get_stylesheet_directory() . '/inc/v115-finish.php';

// v3.8.11.16 reset-safe layout/media, mega-menu, consent and BBuilder finish.
require_once get_stylesheet_directory() . '/inc/v116-finish.php';

// v3.8.11.17 exact mega-menu placement, reset-safe BBuilder grid and immediate media recovery.
require_once get_stylesheet_directory() . '/inc/v117-finish.php';


// v3.8.11.18 reset-safe gutters, direct hero assets, nav-trigger mega positioning and cache finish.
require_once get_stylesheet_directory() . '/inc/v118-finish.php';

// v3.8.11.19 live regression repair: closer mega menus, canonical gutters/grids and no-flash consent.
require_once get_stylesheet_directory() . '/inc/v119-finish.php';

// v3.8.11.20 stable v119 rollback, restored gutters/grids and deterministic hero pagination/quality repair.
require_once get_stylesheet_directory() . '/inc/v120-finish.php';

// v3.8.11.21 scoped BBuilder grid recovery; retire v119/v120 global geometry while preserving hero quality/pagination.
require_once get_stylesheet_directory() . '/inc/v121-finish.php';

// v3.8.11.22 component-only grid-gap finish; keep v121 alignment and restore stable card/media/stat spacing.
require_once get_stylesheet_directory() . '/inc/v122-finish.php';

// v3.8.11.23 remaining basic grids/gaps + authoritative hero source/pagination finish.
require_once get_stylesheet_directory() . '/inc/v123-finish.php';

// v3.8.11.24 final basic visual hardening: deterministic card gaps, full-width fun-facts and one compact hero pager.
require_once get_stylesheet_directory() . '/inc/v124-finish.php';

// v3.8.11.25 final scoped grid, hero clarity and WooCommerce shop/cart/account finish.
require_once get_stylesheet_directory() . '/inc/v125-final.php';

// v3.8.11.26 final cross-theme component grids, hero image/pagination and process-card recovery.
require_once get_stylesheet_directory() . '/inc/v126-final.php';

// v3.8.11.27 final live-regression hardening: robust card grids, process-card shape, hero pagination and Business/Building hero fade.
require_once get_stylesheet_directory() . '/inc/v127-final.php';

// v3.8.11.28 final live component recovery: commerce grids, cart/checkout, process cards and stable hero media/pagination.
require_once get_stylesheet_directory() . '/inc/v128-final.php';

// v3.8.11.34 final cross-theme hero, grid, process and WooCommerce ownership layer.
require_once get_stylesheet_directory() . '/inc/v134-final.php';


// v3.8.11.35 final duplicate/process/hero cleanup.
require_once get_stylesheet_directory() . '/inc/v135-final.php';

// v3.8.11.36 full-width hero, stable process and cross-theme grid ownership.
require_once get_stylesheet_directory() . '/inc/v136-final.php';


// v3.8.11.40 clean Clothes ownership: one stable frontend layer + native WooCommerce shells.
require_once get_stylesheet_directory() . '/inc/v140-clouthes-clean.php';

// v3.8.11.49 deterministic homepage template owner; v140 remains WooCommerce/template owner off the homepage.
require_once get_stylesheet_directory() . '/inc/v149-clouthes-deterministic-home.php';

// v3.8.11.50 Tech Shop parity finish for the deterministic Clothes homepage.
require_once get_stylesheet_directory() . '/inc/v150-clouthes-tech-parity.php';

// v3.8.11.51 fixed Tech Shop source-of-truth header/hero/media/colour finish.
require_once get_stylesheet_directory() . '/inc/v151-clouthes-tech-source.php';

// v3.8.11.52 restore editable parent mega-menu and finish newsletter consent/contrast.
require_once get_stylesheet_directory() . '/inc/v152-clouthes-mega-consent.php';

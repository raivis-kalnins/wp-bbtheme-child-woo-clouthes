<?php
defined('ABSPATH') || exit;
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


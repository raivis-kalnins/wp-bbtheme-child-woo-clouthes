<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.46.
 *
 * Targeted homepage rebuild on top of v140:
 * - v140 remains the WooCommerce/template owner;
 * - v146 owns homepage header/hero/known semantic rows only;
 * - no generic DOM/grid inference;
 * - exact values-row reconstruction and catalogue image recovery.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_clouthes_v146_body_classes' ) ) {
    function wpbb_clouthes_v146_body_classes( $classes ) {
        $classes[] = 'wpbb-v146';
        if ( is_front_page() ) $classes[] = 'wpbb-v146-home';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v146_body_classes', PHP_INT_MAX );

/**
 * Product thumbnails used by the homepage BBuilder catalogue. This is built
 * from the live catalogue query instead of hard-coding demo filenames, so the
 * recovery layer keeps working when products are edited.
 */
if ( ! function_exists( 'wpbb_clouthes_v146_product_images' ) ) {
    function wpbb_clouthes_v146_product_images() {
        static $items = null;
        if ( null !== $items ) return $items;
        $items = array();
        if ( ! post_type_exists( 'product' ) ) return $items;

        $products = get_posts( array(
            'post_type'              => 'product',
            'post_status'            => 'publish',
            'posts_per_page'         => 12,
            'orderby'                => array( 'menu_order' => 'ASC', 'ID' => 'ASC' ),
            'order'                  => 'ASC',
            'suppress_filters'       => false,
            'no_found_rows'          => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => false,
        ) );

        foreach ( $products as $product ) {
            if ( ! $product instanceof WP_Post ) continue;
            $thumb_id = get_post_thumbnail_id( $product );
            if ( ! $thumb_id ) continue;
            $url = wp_get_attachment_image_url( $thumb_id, 'medium_large' );
            if ( ! $url ) $url = wp_get_attachment_image_url( $thumb_id, 'large' );
            if ( ! $url ) $url = wp_get_attachment_image_url( $thumb_id, 'full' );
            if ( ! $url ) continue;
            $items[] = array(
                'id'    => (int) $product->ID,
                'title' => get_the_title( $product ),
                'slug'  => $product->post_name,
                'url'   => $url,
            );
        }
        return $items;
    }
}

/* Ensure image-led homepage rows do not wait for viewport intersection. */
if ( ! function_exists( 'wpbb_clouthes_v146_lazy_loading' ) ) {
    function wpbb_clouthes_v146_lazy_loading( $default, $tag_name, $context ) {
        if ( is_front_page() && 'img' === $tag_name ) return false;
        return $default;
    }
}
add_filter( 'wp_lazy_loading_enabled', 'wpbb_clouthes_v146_lazy_loading', PHP_INT_MAX, 3 );

if ( ! function_exists( 'wpbb_clouthes_v146_attachment_attributes' ) ) {
    function wpbb_clouthes_v146_attachment_attributes( $attr ) {
        if ( ! is_front_page() || ! is_array( $attr ) ) return $attr;
        $attr['loading']  = 'eager';
        $attr['decoding'] = 'async';
        return $attr;
    }
}
add_filter( 'wp_get_attachment_image_attributes', 'wpbb_clouthes_v146_attachment_attributes', PHP_INT_MAX );

/* Custom BBuilder blocks can print their own loading attribute. */
if ( ! function_exists( 'wpbb_clouthes_v146_render_block_media' ) ) {
    function wpbb_clouthes_v146_render_block_media( $content, $block ) {
        if ( is_admin() || ! is_front_page() || ! is_string( $content ) || '' === $content ) return $content;
        $name = isset( $block['blockName'] ) ? (string) $block['blockName'] : '';
        if ( ! in_array( $name, array( 'wpbb/swiper', 'wpbb/catalogue', 'core/image', 'core/media-text', 'core/post-featured-image' ), true ) ) return $content;
        if ( false === stripos( $content, '<img' ) ) return $content;

        $content = (string) preg_replace( '/\sloading=("|\')lazy\1/i', ' loading="eager"', $content );
        $content = (string) preg_replace( '/<img\b(?![^>]*\bloading=)/i', '<img loading="eager"', $content );
        return $content;
    }
}
add_filter( 'render_block', 'wpbb_clouthes_v146_render_block_media', PHP_INT_MAX, 2 );

/* Preload the hero and first four product thumbnails so full-page captures and
 * fast initial scrolls do not show the beige media placeholders. */
if ( ! function_exists( 'wpbb_clouthes_v146_preloads' ) ) {
    function wpbb_clouthes_v146_preloads() {
        if ( ! is_front_page() ) return;
        $hero = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/hero-v118/slide-1.jpg';
        echo '<link rel="preload" as="image" href="' . esc_url( $hero ) . '" fetchpriority="high">' . "\n";
        $i = 0;
        foreach ( wpbb_clouthes_v146_product_images() as $item ) {
            if ( $i++ >= 4 ) break;
            echo '<link rel="preload" as="image" href="' . esc_url( $item['url'] ) . '">' . "\n";
        }
    }
}
add_action( 'wp_head', 'wpbb_clouthes_v146_preloads', 3 );

if ( ! function_exists( 'wpbb_clouthes_v146_enqueue' ) ) {
    function wpbb_clouthes_v146_enqueue() {
        if ( ! is_front_page() ) return;
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = $dir . '/assets/suite-v146-clouthes-home-rebuild.css';
        $js  = $dir . '/assets/suite-v146-clouthes-home-rebuild.js';

        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v146-clouthes-home-rebuild',
                $uri . '/assets/suite-v146-clouthes-home-rebuild.css',
                array( 'wpbb-suite-v140-clouthes-clean' ),
                (string) filemtime( $css )
            );
        }
        if ( is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v146-clouthes-home-rebuild',
                $uri . '/assets/suite-v146-clouthes-home-rebuild.js',
                array( 'wpbb-suite-v140-clouthes-clean' ),
                (string) filemtime( $js ),
                true
            );
            wp_add_inline_script(
                'wpbb-suite-v146-clouthes-home-rebuild',
                'window.wpbbSuiteV146=' . wp_json_encode( array(
                    'version'    => '3.8.11.46',
                    'finderHtml' => function_exists( 'wpbb_clouthes_v140_finder_markup' ) ? wpbb_clouthes_v140_finder_markup() : '',
                    'products'   => wpbb_clouthes_v146_product_images(),
                ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v146_enqueue', PHP_INT_MAX );

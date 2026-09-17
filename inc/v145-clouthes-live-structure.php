<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.45.
 *
 * Live-structure owner based on the deployed homepage, attached DB and the
 * Woo Events/Automotive reference geometry. v140 remains WooCommerce owner.
 */
defined( 'ABSPATH' ) || exit;

/* v145 replaces the v144 homepage presentation owner. */
remove_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v144_enqueue', PHP_INT_MAX );
remove_filter( 'body_class', 'wpbb_clouthes_v144_body_classes', PHP_INT_MAX );
remove_filter( 'wp_get_attachment_image_attributes', 'wpbb_clouthes_v144_catalogue_image_attributes', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v145_body_classes' ) ) {
    function wpbb_clouthes_v145_body_classes( $classes ) {
        $classes[] = 'wpbb-v145';
        if ( is_front_page() ) $classes[] = 'wpbb-v145-home';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v145_body_classes', PHP_INT_MAX );

/*
 * Full-page captures and fast scrolls were leaving the hero and catalogue
 * media blank. The Clothes homepage is image-led and small enough that eager
 * loading here is preferable to an intermittently empty storefront. Woo/shop
 * routes are unaffected.
 */
if ( ! function_exists( 'wpbb_clouthes_v145_lazy_loading' ) ) {
    function wpbb_clouthes_v145_lazy_loading( $default, $tag_name, $context ) {
        if ( is_front_page() && 'img' === $tag_name ) return false;
        return $default;
    }
}
add_filter( 'wp_lazy_loading_enabled', 'wpbb_clouthes_v145_lazy_loading', PHP_INT_MAX, 3 );

if ( ! function_exists( 'wpbb_clouthes_v145_image_attributes' ) ) {
    function wpbb_clouthes_v145_image_attributes( $attr ) {
        if ( ! is_front_page() || ! is_array( $attr ) ) return $attr;
        $attr['loading']  = 'eager';
        $attr['decoding'] = 'async';
        return $attr;
    }
}
add_filter( 'wp_get_attachment_image_attributes', 'wpbb_clouthes_v145_image_attributes', PHP_INT_MAX );

/*
 * Custom BBuilder Swiper/Catalogue blocks may print loading="lazy" directly,
 * bypassing the normal attachment filters. Strip old hero finder markup at the
 * same point and force these block-owned media elements to render immediately.
 */
if ( ! function_exists( 'wpbb_clouthes_v145_render_block' ) ) {
    function wpbb_clouthes_v145_render_block( $block_content, $block ) {
        if ( is_admin() || ! is_front_page() || ! is_string( $block_content ) || '' === $block_content ) return $block_content;
        $name = isset( $block['blockName'] ) ? (string) $block['blockName'] : '';

        if ( 'wpbb/swiper' === $name && false !== strpos( $block_content, 'wpbb-swiper--hero' ) && function_exists( 'wpbb_clouthes_v140_strip_finders' ) ) {
            $block_content = wpbb_clouthes_v140_strip_finders( $block_content );
        }

        /* Core Media & Text, query cards and custom catalogue blocks do not all
         * use the same attachment pipeline. Normalise any rendered homepage
         * image tag here so a full-page capture cannot leave later rows blank. */
        if ( false !== stripos( $block_content, '<img' ) ) {
            $block_content = (string) preg_replace( '/\sloading=("|\')lazy\1/i', ' loading="eager"', $block_content );
        }
        return $block_content;
    }
}
add_filter( 'render_block', 'wpbb_clouthes_v145_render_block', PHP_INT_MAX, 2 );

if ( ! function_exists( 'wpbb_clouthes_v145_enqueue' ) ) {
    function wpbb_clouthes_v145_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        if ( is_front_page() ) {
            /* v140 stays loaded as PHP/Woo ownership, not as homepage CSS/JS. */
            foreach ( array(
                'wpbb-suite-v140-clouthes-clean',
                'wpbb-suite-v141-clouthes-parity',
                'wpbb-suite-v142-clouthes-visual-parity',
                'wpbb-suite-v143-clouthes-screenshot-hardfix',
                'wpbb-suite-v144-clouthes-events-db-hardfix',
            ) as $handle ) {
                wp_dequeue_style( $handle );
                wp_deregister_style( $handle );
                wp_dequeue_script( $handle );
                wp_deregister_script( $handle );
            }
        }

        $css = $dir . '/assets/suite-v145-clouthes-live-structure.css';
        $js  = $dir . '/assets/suite-v145-clouthes-live-structure.js';

        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v145-clouthes-live-structure',
                $uri . '/assets/suite-v145-clouthes-live-structure.css',
                is_front_page() ? array( 'wpbb-suite-v118' ) : array(),
                (string) filemtime( $css )
            );
        }

        if ( is_front_page() && is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v145-clouthes-live-structure',
                $uri . '/assets/suite-v145-clouthes-live-structure.js',
                array( 'wpbb-suite-v118' ),
                (string) filemtime( $js ),
                true
            );
            wp_add_inline_script(
                'wpbb-suite-v145-clouthes-live-structure',
                'window.wpbbSuiteV145=' . wp_json_encode( array(
                    'version'    => '3.8.11.45',
                    'finderHtml' => function_exists( 'wpbb_clouthes_v140_finder_markup' ) ? wpbb_clouthes_v140_finder_markup() : '',
                ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v145_enqueue', PHP_INT_MAX );

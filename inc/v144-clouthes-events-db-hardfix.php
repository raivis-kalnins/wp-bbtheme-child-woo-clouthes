<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.44.
 *
 * DB-grounded / Woo Events-parity homepage owner.
 *
 * The attached database shows that the homepage itself is structurally sound:
 * one two-slide hero, one three-card values row and one eight-product catalogue.
 * v144 therefore stops the v141-v143 presentation runtimes on the front page,
 * keeps v118 as the stable base, and owns only the actual rendered rows.
 * WooCommerce route/template ownership remains with v140.
 */
defined( 'ABSPATH' ) || exit;

/* v144 replaces the three screenshot/parity presentation layers. */
remove_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v141_enqueue', PHP_INT_MAX );
remove_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v142_enqueue', PHP_INT_MAX );
remove_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v143_enqueue', PHP_INT_MAX );
remove_filter( 'body_class', 'wpbb_clouthes_v141_body_classes', PHP_INT_MAX );
remove_filter( 'body_class', 'wpbb_clouthes_v142_body_classes', PHP_INT_MAX );
remove_filter( 'body_class', 'wpbb_clouthes_v143_body_classes', PHP_INT_MAX );
remove_filter( 'wp_lazy_loading_enabled', 'wpbb_clouthes_v143_lazy_loading', PHP_INT_MAX );
remove_filter( 'wp_get_attachment_image_attributes', 'wpbb_clouthes_v143_image_attributes', PHP_INT_MAX );

/*
 * The v140 finder was injected into rendered Swiper HTML. The live source shows
 * a copy after each hero slide, so v144 makes the finder a single runtime-owned
 * overlay outside every slide. The underlying stored page contains no finder.
 */
remove_filter( 'render_block', 'wpbb_clouthes_v140_render_hero_finder', PHP_INT_MAX );
remove_filter( 'the_content', 'wpbb_clouthes_v140_dedupe_home_content', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v144_body_classes' ) ) {
    function wpbb_clouthes_v144_body_classes( $classes ) {
        $classes[] = 'wpbb-v144';
        if ( is_front_page() ) $classes[] = 'wpbb-v144-home';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v144_body_classes', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v144_enqueue' ) ) {
    function wpbb_clouthes_v144_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        /*
         * Front page: one owner only. v140 remains active on Woo routes, but its
         * homepage CSS/JS is intentionally retired here so v144 does not have to
         * fight another final layer. v118 remains the stable sector foundation.
         */
        if ( is_front_page() ) {
            foreach ( array(
                'wpbb-suite-v140-clouthes-clean',
                'wpbb-suite-v141-clouthes-parity',
                'wpbb-suite-v142-clouthes-visual-parity',
                'wpbb-suite-v143-clouthes-screenshot-hardfix',
            ) as $handle ) {
                wp_dequeue_style( $handle );
                wp_deregister_style( $handle );
                wp_dequeue_script( $handle );
                wp_deregister_script( $handle );
            }
        }

        $css = $dir . '/assets/suite-v144-clouthes-events-db-hardfix.css';
        $js  = $dir . '/assets/suite-v144-clouthes-events-db-hardfix.js';

        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v144-clouthes-events-db-hardfix',
                $uri . '/assets/suite-v144-clouthes-events-db-hardfix.css',
                is_front_page() ? array( 'wpbb-suite-v118' ) : array(),
                (string) filemtime( $css )
            );
        }

        if ( is_front_page() && is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v144-clouthes-events-db-hardfix',
                $uri . '/assets/suite-v144-clouthes-events-db-hardfix.js',
                array( 'wpbb-suite-v118' ),
                (string) filemtime( $js ),
                true
            );
            wp_add_inline_script(
                'wpbb-suite-v144-clouthes-events-db-hardfix',
                'window.wpbbSuiteV144=' . wp_json_encode( array(
                    'version'    => '3.8.11.44',
                    'finderHtml' => function_exists( 'wpbb_clouthes_v140_finder_markup' ) ? wpbb_clouthes_v140_finder_markup() : '',
                ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v144_enqueue', PHP_INT_MAX );

/*
 * Do not globally disable lazy loading any more. Only the eight homepage
 * catalogue cards need eager attachment output; the JS also promotes lazy data
 * attributes when the catalogue block renders client-side.
 */
if ( ! function_exists( 'wpbb_clouthes_v144_catalogue_image_attributes' ) ) {
    function wpbb_clouthes_v144_catalogue_image_attributes( $attr ) {
        if ( ! is_front_page() || ! is_array( $attr ) ) return $attr;
        if ( isset( $attr['class'] ) && false !== strpos( (string) $attr['class'], 'wpbb-catalogue' ) ) {
            $attr['loading'] = 'eager';
            $attr['decoding'] = 'async';
        }
        return $attr;
    }
}
add_filter( 'wp_get_attachment_image_attributes', 'wpbb_clouthes_v144_catalogue_image_attributes', PHP_INT_MAX );

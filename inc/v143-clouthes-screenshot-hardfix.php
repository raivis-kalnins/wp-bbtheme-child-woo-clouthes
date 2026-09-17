<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.43.
 *
 * Screenshot-led final homepage owner:
 * - replaces the separate v141/v142 presentation assets with one consolidated layer;
 * - removes legacy nav pseudo collisions;
 * - binds the hero pager to the hero itself instead of a reserved strip;
 * - hardens the current #wpbb-row-35 values grid against BBuilder nesting;
 * - fixes the real Clothes editorial band contrast/rhythm;
 * - eagerly exposes homepage editorial/catalogue media without changing Woo templates.
 */
defined( 'ABSPATH' ) || exit;

/* v143 owns the v141/v142 presentation runtime and stylesheet. */
remove_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v141_enqueue', PHP_INT_MAX );
remove_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v142_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v143_body_classes' ) ) {
    function wpbb_clouthes_v143_body_classes( $classes ) {
        $classes[] = 'wpbb-v143';
        if ( is_front_page() ) $classes[] = 'wpbb-v143-home';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v143_body_classes', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v143_enqueue' ) ) {
    function wpbb_clouthes_v143_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        foreach ( array(
            'wpbb-suite-v141-clouthes-parity',
            'wpbb-suite-v142-clouthes-visual-parity',
        ) as $handle ) {
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }

        $css = $dir . '/assets/suite-v143-clouthes-screenshot-hardfix.css';
        $js  = $dir . '/assets/suite-v143-clouthes-screenshot-hardfix.js';

        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v143-clouthes-screenshot-hardfix',
                $uri . '/assets/suite-v143-clouthes-screenshot-hardfix.css',
                array( 'wpbb-suite-v140-clouthes-clean' ),
                (string) filemtime( $css )
            );
        }

        if ( is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v143-clouthes-screenshot-hardfix',
                $uri . '/assets/suite-v143-clouthes-screenshot-hardfix.js',
                array( 'wpbb-suite-v140-clouthes-clean' ),
                (string) filemtime( $js ),
                true
            );
            wp_add_inline_script(
                'wpbb-suite-v143-clouthes-screenshot-hardfix',
                'window.wpbbSuiteV143=' . wp_json_encode( array( 'version' => '3.8.11.43' ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v143_enqueue', PHP_INT_MAX );

/* Core lazy-loading can leave a long-page capture with unloaded catalogue media.
 * Limit the change to the front page; Woo archive/product pages keep normal lazy loading. */
if ( ! function_exists( 'wpbb_clouthes_v143_lazy_loading' ) ) {
    function wpbb_clouthes_v143_lazy_loading( $default, $tag_name, $context ) {
        if ( is_front_page() && 'img' === $tag_name ) return false;
        return $default;
    }
}
add_filter( 'wp_lazy_loading_enabled', 'wpbb_clouthes_v143_lazy_loading', PHP_INT_MAX, 3 );

if ( ! function_exists( 'wpbb_clouthes_v143_image_attributes' ) ) {
    function wpbb_clouthes_v143_image_attributes( $attr ) {
        if ( ! is_front_page() || ! is_array( $attr ) ) return $attr;
        $attr['loading'] = 'eager';
        $attr['decoding'] = 'async';
        if ( isset( $attr['style'] ) ) {
            $attr['style'] = preg_replace( '/(?:^|;)\s*(?:opacity\s*:\s*0|visibility\s*:\s*hidden)\s*;?/i', ';', (string) $attr['style'] );
        }
        return $attr;
    }
}
add_filter( 'wp_get_attachment_image_attributes', 'wpbb_clouthes_v143_image_attributes', PHP_INT_MAX, 1 );

<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.41.
 *
 * Automotive-parity frontend finish:
 * - restores measured homepage section alignment without reviving old v98-v139 assets;
 * - adds one accessible pager to each multi-slide hero;
 * - normalises #wpbb-row-35 and all card-led homepage grids to equal responsive cells;
 * - restores compact header/nav/action geometry using the Clothes palette;
 * - leaves v140 as the sole WooCommerce template/layout owner.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_clouthes_v141_body_classes' ) ) {
    function wpbb_clouthes_v141_body_classes( $classes ) {
        $classes[] = 'wpbb-v141';
        if ( is_front_page() ) $classes[] = 'wpbb-v141-home';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v141_body_classes', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v141_enqueue' ) ) {
    function wpbb_clouthes_v141_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = $dir . '/assets/suite-v141-clouthes-parity.css';
        $js  = $dir . '/assets/suite-v141-clouthes-parity.js';

        $css_deps = array();
        $js_deps  = array();
        if ( wp_style_is( 'wpbb-suite-v140-clouthes-clean', 'registered' ) || wp_style_is( 'wpbb-suite-v140-clouthes-clean', 'enqueued' ) ) $css_deps[] = 'wpbb-suite-v140-clouthes-clean';
        if ( wp_script_is( 'wpbb-suite-v140-clouthes-clean', 'registered' ) || wp_script_is( 'wpbb-suite-v140-clouthes-clean', 'enqueued' ) ) $js_deps[] = 'wpbb-suite-v140-clouthes-clean';

        if ( is_readable( $css ) ) {
            wp_enqueue_style( 'wpbb-suite-v141-clouthes-parity', $uri . '/assets/suite-v141-clouthes-parity.css', $css_deps, (string) filemtime( $css ) );
        }
        if ( is_readable( $js ) ) {
            wp_enqueue_script( 'wpbb-suite-v141-clouthes-parity', $uri . '/assets/suite-v141-clouthes-parity.js', $js_deps, (string) filemtime( $js ), true );
            wp_add_inline_script(
                'wpbb-suite-v141-clouthes-parity',
                'window.wpbbSuiteV141=' . wp_json_encode( array( 'version' => '3.8.11.41' ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v141_enqueue', PHP_INT_MAX );

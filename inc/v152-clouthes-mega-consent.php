<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.52.
 *
 * Restore the real parent header/mega-menu on the deterministic homepage and
 * finish newsletter contrast/consent alignment without changing v140 Woo.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_clouthes_v152_enqueue' ) ) {
    function wpbb_clouthes_v152_enqueue() {
        if ( is_admin() || ! is_front_page() ) return;

        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = $dir . '/assets/suite-v152-clouthes-mega-consent.css';

        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v152-clouthes-mega-consent',
                $uri . '/assets/suite-v152-clouthes-mega-consent.css',
                array( 'wpbb-suite-v151-clouthes-tech-source' ),
                (string) filemtime( $css )
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v152_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v152_body_classes' ) ) {
    function wpbb_clouthes_v152_body_classes( $classes ) {
        if ( ! is_front_page() ) return $classes;
        $classes[] = 'wpbb-v152';
        $classes[] = 'wpbb-v152-mega-consent';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v152_body_classes', PHP_INT_MAX );

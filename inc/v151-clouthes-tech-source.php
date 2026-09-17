<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.51.
 *
 * Tech Shop source-of-truth finish. The deterministic Clothes homepage keeps
 * v149 section structure, but replaces the unstable parent header, v150 finish,
 * hero runtime, partner media and product image fallback with one owner.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_clouthes_v151_product_image_url' ) ) {
    function wpbb_clouthes_v151_product_image_url( $product ) {
        if ( ! is_object( $product ) ) return '';
        $title = method_exists( $product, 'get_name' ) ? sanitize_title( $product->get_name() ) : '';
        $slug  = method_exists( $product, 'get_slug' ) ? sanitize_title( $product->get_slug() ) : '';
        $map = array(
            'kids-lightweight-overshirt' => 'utility-overshirt.jpg',
            'classic-oxford-shirt'       => 'classic-oxford-shirt.jpg',
            'essential-t-shirt'          => 'essential-t-shirt.jpg',
            'merino-crew-knit'           => 'merino-crew-knit.jpg',
            'straight-leg-trouser'       => 'straight-leg-trouser.jpg',
            'everyday-denim'             => 'everyday-denim.jpg',
            'canvas-weekend-bag'         => 'canvas-weekend-bag.jpg',
            'leather-card-holder'        => 'leather-card-holder.jpg',
            'relaxed-cotton-shirt'       => 'relaxed-cotton-shirt.jpg',
            'utility-overshirt'          => 'utility-overshirt.jpg',
        );
        foreach ( array( $title, $slug ) as $key ) {
            if ( ! $key || empty( $map[ $key ] ) ) continue;
            $file = $map[ $key ];
            $path = get_stylesheet_directory() . '/assets/img/products/' . $file;
            if ( is_readable( $path ) ) {
                return trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/products/' . $file;
            }
        }
        return trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/products/utility-overshirt.jpg';
    }
}

if ( ! function_exists( 'wpbb_clouthes_v151_enqueue' ) ) {
    function wpbb_clouthes_v151_enqueue() {
        if ( is_admin() || ! is_front_page() ) return;

        foreach ( array(
            'wpbb-suite-v150-clouthes-tech-parity',
            'wpbb-suite-v150-clouthes-tech-parity-js',
        ) as $handle ) {
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }
        /* v151 owns the deterministic hero runtime; keep only v149 CSS. */
        wp_dequeue_script( 'wpbb-suite-v149-clouthes-deterministic-home' );
        wp_deregister_script( 'wpbb-suite-v149-clouthes-deterministic-home' );

        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = $dir . '/assets/suite-v151-clouthes-tech-source.css';
        $js  = $dir . '/assets/suite-v151-clouthes-tech-source.js';

        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v151-clouthes-tech-source',
                $uri . '/assets/suite-v151-clouthes-tech-source.css',
                array( 'wpbb-suite-v149-clouthes-deterministic-home' ),
                (string) filemtime( $css )
            );
        }
        if ( is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v151-clouthes-tech-source',
                $uri . '/assets/suite-v151-clouthes-tech-source.js',
                array(),
                (string) filemtime( $js ),
                true
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v151_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v151_body_classes' ) ) {
    function wpbb_clouthes_v151_body_classes( $classes ) {
        if ( ! is_front_page() ) return $classes;
        $classes = array_values( array_filter( $classes, static function( $class ) {
            return ! in_array( $class, array( 'wpbb-v150', 'wpbb-v150-tech-parity' ), true );
        } ) );
        $classes[] = 'wpbb-v151';
        $classes[] = 'wpbb-v151-tech-source';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v151_body_classes', PHP_INT_MAX );

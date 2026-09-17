<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.50.
 *
 * Tech Shop 3.8.11.52 parity finish for the deterministic Clothes homepage.
 * Retires the generic v119-v148 frontend geometry/runtime stack on the front
 * page, keeps the stable v118 foundation, then applies one final Clothes owner.
 */
defined( 'ABSPATH' ) || exit;

/* Historic hero-finder renderers must never touch the deterministic front page. */
foreach ( array(
    array( 'render_block', 'wpbb_child_v97_render_hero_finder', 180 ),
    array( 'render_block', 'wpbb_clouthes_v139_render_hero_finder', 181 ),
    array( 'render_block', 'wpbb_clouthes_v140_render_hero_finder', PHP_INT_MAX ),
    array( 'the_content', 'wpbb_clouthes_v140_dedupe_home_content', PHP_INT_MAX ),
) as $hook ) {
    if ( function_exists( $hook[1] ) ) {
        remove_filter( $hook[0], $hook[1], $hook[2] );
    }
}

if ( ! function_exists( 'wpbb_clouthes_v150_enqueue' ) ) {
    function wpbb_clouthes_v150_enqueue() {
        if ( is_admin() || ! is_front_page() ) return;

        /* Match the fixed Tech Shop: v118 is the stable shared base and the
         * accumulated late geometry/runtime owners are retired. */
        foreach ( range( 119, 148 ) as $n ) {
            foreach ( array(
                'wpbb-suite-v' . $n,
                'wpbb-suite-v' . $n . '-js',
                'wpbb-suite-v' . $n . '-woo-hard',
                'wpbb-suite-v' . $n . '-clouthes-hard',
                'wpbb-suite-v' . $n . '-clouthes-clean',
                'wpbb-suite-v' . $n . '-clouthes-parity',
                'wpbb-suite-v' . $n . '-clouthes-visual-parity',
                'wpbb-suite-v' . $n . '-clouthes-screenshot-hardfix',
                'wpbb-suite-v' . $n . '-clouthes-events-db-hardfix',
                'wpbb-suite-v' . $n . '-clouthes-live-structure',
                'wpbb-suite-v' . $n . '-clouthes-home-rebuild',
                'wpbb-suite-v' . $n . '-clouthes-events-owner',
                'wpbb-suite-v' . $n . '-clouthes-events-structural',
            ) as $handle ) {
                wp_dequeue_style( $handle );
                wp_deregister_style( $handle );
                wp_dequeue_script( $handle );
                wp_deregister_script( $handle );
            }
        }

        /* v149 is the deterministic page/template owner; v150 is only the
         * final Tech-Shop parity layer and therefore loads after it. */
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = $dir . '/assets/suite-v150-clouthes-tech-parity.css';
        $js  = $dir . '/assets/suite-v150-clouthes-tech-parity.js';

        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v150-clouthes-tech-parity',
                $uri . '/assets/suite-v150-clouthes-tech-parity.css',
                array( 'wpbb-suite-v149-clouthes-deterministic-home' ),
                (string) filemtime( $css )
            );
            /* The fixed Tech Shop does the same for its final rail/pager layer;
             * this protects the correction from stale proxy/CDN HTML. */
            wp_add_inline_style( 'wpbb-suite-v150-clouthes-tech-parity', (string) file_get_contents( $css ) );
        }
        if ( is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v150-clouthes-tech-parity',
                $uri . '/assets/suite-v150-clouthes-tech-parity.js',
                array( 'wpbb-suite-v149-clouthes-deterministic-home' ),
                (string) filemtime( $js ),
                true
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v150_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v150_body_classes' ) ) {
    function wpbb_clouthes_v150_body_classes( $classes ) {
        if ( is_front_page() ) {
            $classes[] = 'wpbb-v150';
            $classes[] = 'wpbb-v150-tech-parity';
        }
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v150_body_classes', PHP_INT_MAX );

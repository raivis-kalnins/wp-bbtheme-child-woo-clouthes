<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.42.
 *
 * Visual parity hard-fix after live screenshot review:
 * - removes the v141 runtime axis measurement that narrowed the whole homepage;
 * - keeps the useful v141 presentation CSS, then corrects it with v142;
 * - gives contained BBuilder section rows (notably the values row / #wpbb-row-35)
 *   their own grid geometry instead of treating them as full-bleed shells;
 * - adds a larger, explicit hero pager and robust responsive grid discovery;
 * - restores Clothes header-action labels and neutralises legacy teal/navy colours;
 * - promotes homepage catalogue/editorial lazy images so long-page captures do not
 *   show empty product-card media areas.
 */
defined( 'ABSPATH' ) || exit;

/* v141's JS measured the current header and copied its narrow width to every
 * homepage section. Keep its useful CSS as a base, but replace the runtime. */
remove_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v141_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v142_body_classes' ) ) {
    function wpbb_clouthes_v142_body_classes( $classes ) {
        $classes[] = 'wpbb-v142';
        if ( is_front_page() ) $classes[] = 'wpbb-v142-home';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v142_body_classes', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v142_enqueue' ) ) {
    function wpbb_clouthes_v142_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        /* Defensive cleanup if another callback already queued the old runtime. */
        wp_dequeue_script( 'wpbb-suite-v141-clouthes-parity' );
        wp_deregister_script( 'wpbb-suite-v141-clouthes-parity' );

        $v141_css = $dir . '/assets/suite-v141-clouthes-parity.css';
        if ( is_readable( $v141_css ) ) {
            wp_dequeue_style( 'wpbb-suite-v141-clouthes-parity' );
            wp_deregister_style( 'wpbb-suite-v141-clouthes-parity' );
            wp_enqueue_style(
                'wpbb-suite-v141-clouthes-parity',
                $uri . '/assets/suite-v141-clouthes-parity.css',
                array( 'wpbb-suite-v140-clouthes-clean' ),
                (string) filemtime( $v141_css )
            );
        }

        $css = $dir . '/assets/suite-v142-clouthes-visual-parity.css';
        $js  = $dir . '/assets/suite-v142-clouthes-visual-parity.js';

        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v142-clouthes-visual-parity',
                $uri . '/assets/suite-v142-clouthes-visual-parity.css',
                array( 'wpbb-suite-v141-clouthes-parity' ),
                (string) filemtime( $css )
            );
        }

        if ( is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v142-clouthes-visual-parity',
                $uri . '/assets/suite-v142-clouthes-visual-parity.js',
                array( 'wpbb-suite-v140-clouthes-clean' ),
                (string) filemtime( $js ),
                true
            );
            wp_add_inline_script(
                'wpbb-suite-v142-clouthes-visual-parity',
                'window.wpbbSuiteV142=' . wp_json_encode( array( 'version' => '3.8.11.42' ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v142_enqueue', PHP_INT_MAX );

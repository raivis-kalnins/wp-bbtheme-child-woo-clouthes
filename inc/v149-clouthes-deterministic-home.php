<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.49.
 *
 * Deterministic homepage owner. The supplied DB confirmed that the content is
 * correct but the historic BBuilder presentation layers keep colliding. The
 * front page is therefore rendered by front-page.php using the same content,
 * live Woo products/posts and the proven Events-style layout rail.
 * WooCommerce routes remain owned by v140.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpbb_clouthes_v149_is_home' ) ) {
    function wpbb_clouthes_v149_is_home() {
        return ! is_admin() && is_front_page();
    }
}

if ( ! function_exists( 'wpbb_clouthes_v149_body_classes' ) ) {
    function wpbb_clouthes_v149_body_classes( $classes ) {
        if ( ! is_front_page() ) return $classes;
        $classes = array_values( array_filter( $classes, static function( $class ) {
            return ! in_array( $class, array(
                'wpbb-v140', 'wpbb-v140-home', 'wpbb-v147', 'wpbb-v147-home', 'wpbb-v148', 'wpbb-v148-home'
            ), true );
        } ) );
        $classes[] = 'wpbb-v149';
        $classes[] = 'wpbb-v149-home';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v149_body_classes', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v149_enqueue' ) ) {
    function wpbb_clouthes_v149_enqueue() {
        if ( ! is_front_page() ) return;

        /* v118 is the shared stable header/menu foundation. Everything newer
         * that tried to own Clothes homepage geometry is retired here. */
        foreach ( array(
            'wpbb-suite-v140-clouthes-clean',
            'wpbb-suite-v141-clouthes-parity',
            'wpbb-suite-v142-clouthes-visual-parity',
            'wpbb-suite-v143-clouthes-screenshot-hardfix',
            'wpbb-suite-v144-clouthes-events-db-hardfix',
            'wpbb-suite-v145-clouthes-live-structure',
            'wpbb-suite-v146-clouthes-home-rebuild',
            'wpbb-suite-v147-clouthes-events-owner',
            'wpbb-suite-v148-clouthes-events-structural',
        ) as $handle ) {
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }

        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = $dir . '/assets/suite-v149-clouthes-deterministic-home.css';
        $js  = $dir . '/assets/suite-v149-clouthes-deterministic-home.js';

        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v149-clouthes-deterministic-home',
                $uri . '/assets/suite-v149-clouthes-deterministic-home.css',
                array( 'wpbb-suite-v118' ),
                (string) filemtime( $css )
            );
        }
        if ( is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v149-clouthes-deterministic-home',
                $uri . '/assets/suite-v149-clouthes-deterministic-home.js',
                array(),
                (string) filemtime( $js ),
                true
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v149_enqueue', PHP_INT_MAX );

/* The template does not render the old BBuilder hero, so these homepage-only
 * filters are unnecessary and must not inject duplicate finders if another
 * plugin renders the page content in a widget. */
if ( function_exists( 'wpbb_clouthes_v140_render_hero_finder' ) ) {
    remove_filter( 'render_block', 'wpbb_clouthes_v140_render_hero_finder', PHP_INT_MAX );
}
if ( function_exists( 'wpbb_clouthes_v140_dedupe_home_content' ) ) {
    remove_filter( 'the_content', 'wpbb_clouthes_v140_dedupe_home_content', PHP_INT_MAX );
}

if ( ! function_exists( 'wpbb_clouthes_v149_preload_hero' ) ) {
    function wpbb_clouthes_v149_preload_hero() {
        if ( ! is_front_page() ) return;
        $src = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/hero-v118/slide-1.jpg';
        echo '<link rel="preload" as="image" href="' . esc_url( $src ) . '" fetchpriority="high">' . "\n";
    }
}
add_action( 'wp_head', 'wpbb_clouthes_v149_preload_hero', 2 );

if ( ! function_exists( 'wpbb_clouthes_v149_product_image_url' ) ) {
    function wpbb_clouthes_v149_product_image_url( $product ) {
        if ( ! is_object( $product ) ) return '';
        $slug = method_exists( $product, 'get_slug' ) ? sanitize_title( $product->get_slug() ) : '';
        $normalized = preg_replace( '/-[a-f0-9]{8,}$/', '', $slug );
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
        foreach ( array( $slug, $normalized ) as $key ) {
            if ( isset( $map[ $key ] ) ) {
                $file = $map[ $key ];
                if ( is_readable( get_stylesheet_directory() . '/assets/img/products/' . $file ) ) {
                    return trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/products/' . rawurlencode( $file );
                }
            }
        }
        $image_id = method_exists( $product, 'get_image_id' ) ? absint( $product->get_image_id() ) : 0;
        if ( $image_id ) {
            $url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
            if ( $url ) return $url;
        }
        return trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/products/utility-overshirt.jpg';
    }
}

if ( ! function_exists( 'wpbb_clouthes_v149_front_template' ) ) {
    function wpbb_clouthes_v149_front_template( $template ) {
        if ( is_admin() || ! is_front_page() ) return $template;
        $candidate = get_stylesheet_directory() . '/front-page.php';
        return is_readable( $candidate ) ? $candidate : $template;
    }
}
add_filter( 'template_include', 'wpbb_clouthes_v149_front_template', PHP_INT_MAX );

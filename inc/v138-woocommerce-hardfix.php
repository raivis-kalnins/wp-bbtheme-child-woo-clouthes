<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.38
 * Hard WooCommerce ownership layer.
 *
 * Woo pages had accumulated many historical CSS/JS finish layers and a
 * shortcode-based archive renderer. This layer makes Woo deterministic:
 * - one late Woo stylesheet;
 * - no historical suite DOM mutators on Woo screens;
 * - native WooCommerce archive/single-product loops;
 * - stable server-side page state classes for responsive layout.
 */
defined( 'ABSPATH' ) || exit;

/* Retire the four historical template routers; v138 is the only final router. */
remove_filter( 'template_include', 'wpbb_clouthes_woocommerce_legacy_template_v36', 99 );
remove_filter( 'template_include', 'wpbb_child_v75_force_woo_legacy_template', PHP_INT_MAX );
remove_filter( 'template_include', 'wpbb_child_v108_force_woo_template', PHP_INT_MAX );
remove_filter( 'template_include', 'wpbb_child_381047_force_woo_legacy_template', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v138_is_woo_request' ) ) {
    function wpbb_clouthes_v138_is_woo_request() {
        if ( ! function_exists( 'WC' ) ) return false;
        return ( function_exists( 'is_woocommerce' ) && is_woocommerce() )
            || ( function_exists( 'is_cart' ) && is_cart() )
            || ( function_exists( 'is_checkout' ) && is_checkout() )
            || ( function_exists( 'is_account_page' ) && is_account_page() );
    }
}

if ( ! function_exists( 'wpbb_clouthes_v138_body_classes' ) ) {
    function wpbb_clouthes_v138_body_classes( $classes ) {
        if ( ! wpbb_clouthes_v138_is_woo_request() ) return $classes;

        $classes[] = 'wpbb-v138-woo';
        if ( function_exists( 'is_product' ) && is_product() ) {
            $classes[] = 'wpbb-v138-woo-product';
        } elseif ( function_exists( 'is_cart' ) && is_cart() ) {
            $classes[] = 'wpbb-v138-woo-cart';
            if ( function_exists( 'WC' ) && WC()->cart && WC()->cart->is_empty() ) {
                $classes[] = 'wpbb-v138-cart-empty';
            }
        } elseif ( function_exists( 'is_checkout' ) && is_checkout() ) {
            $classes[] = 'wpbb-v138-woo-checkout';
            if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) {
                $classes[] = 'wpbb-v138-order-received';
            }
        } elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
            $classes[] = 'wpbb-v138-woo-account';
            $classes[] = is_user_logged_in() ? 'wpbb-v138-account-authenticated' : 'wpbb-v138-account-guest';
        } else {
            $classes[] = 'wpbb-v138-woo-catalog';
        }

        return array_values( array_unique( $classes ) );
    }
    add_filter( 'body_class', 'wpbb_clouthes_v138_body_classes', PHP_INT_MAX );
}

if ( ! function_exists( 'wpbb_clouthes_v138_assets' ) ) {
    function wpbb_clouthes_v138_assets() {
        if ( ! wpbb_clouthes_v138_is_woo_request() ) return;

        /*
         * The historical finish layers contain overlapping Woo rules and DOM
         * mutations. Keep the current global v136 owner, but remove v98-v135
         * and the superseded v137 Woo layer on Woo screens only. Base theme,
         * sector palette and WooCommerce's own assets remain intact.
         */
        foreach ( range( 98, 137 ) as $n ) {
            if ( 136 === $n ) continue;
            $handle = 'wpbb-suite-v' . $n;
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }
        wp_dequeue_style( 'wpbb-suite-v137-woo' );
        wp_deregister_style( 'wpbb-suite-v137-woo' );

        $path = get_stylesheet_directory() . '/assets/suite-v138-woo-hard.css';
        wp_enqueue_style(
            'wpbb-suite-v138-woo-hard',
            get_stylesheet_directory_uri() . '/assets/suite-v138-woo-hard.css',
            array( 'wpbb-suite-v136' ),
            is_file( $path ) ? (string) filemtime( $path ) : '3.8.11.38'
        );
    }
    add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v138_assets', PHP_INT_MAX );
}

if ( ! function_exists( 'wpbb_clouthes_v138_template' ) ) {
    function wpbb_clouthes_v138_template( $template ) {
        if ( is_admin() || wp_doing_ajax() || is_feed() || ! wpbb_clouthes_v138_is_woo_request() ) return $template;

        $base = trailingslashit( get_stylesheet_directory() ) . 'woocommerce-legacy/';
        $candidate = '';
        if ( ( function_exists( 'is_product' ) && is_product() ) || is_singular( 'product' ) ) {
            $candidate = 'product.php';
        } elseif ( function_exists( 'is_cart' ) && is_cart() ) {
            $candidate = 'cart.php';
        } elseif ( function_exists( 'is_checkout' ) && is_checkout() ) {
            $candidate = 'checkout.php';
        } elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
            $candidate = 'account.php';
        } elseif ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) ) {
            $candidate = 'catalog.php';
        }

        return $candidate && is_readable( $base . $candidate ) ? $base . $candidate : $template;
    }
    add_filter( 'template_include', 'wpbb_clouthes_v138_template', PHP_INT_MAX );
}

if ( ! function_exists( 'wpbb_clouthes_v138_loop_columns' ) ) {
    function wpbb_clouthes_v138_loop_columns( $columns ) {
        return wpbb_clouthes_v138_is_woo_request() ? 3 : $columns;
    }
    add_filter( 'loop_shop_columns', 'wpbb_clouthes_v138_loop_columns', PHP_INT_MAX );
}

if ( ! function_exists( 'wpbb_clouthes_v138_related_products' ) ) {
    function wpbb_clouthes_v138_related_products( $args ) {
        if ( ! wpbb_clouthes_v138_is_woo_request() ) return $args;
        $args['posts_per_page'] = 3;
        $args['columns'] = 3;
        return $args;
    }
    add_filter( 'woocommerce_output_related_products_args', 'wpbb_clouthes_v138_related_products', PHP_INT_MAX );
}

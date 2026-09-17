<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.40.
 *
 * Clean frontend ownership layer:
 * - one late stylesheet/runtime instead of the v98-v139 repair cascade;
 * - stable v118 geometry/media/menu foundation retained;
 * - server-side single homepage product finder;
 * - native WooCommerce archive/product/customer templates;
 * - one WooCommerce responsive layout owner;
 * - exact-demo-only Woo store visibility override.
 */
defined( 'ABSPATH' ) || exit;

/* Retire old template/finder owners that can race the final layer. */
remove_filter( 'template_include', 'wpbb_clouthes_woocommerce_legacy_template_v36', 99 );
remove_filter( 'template_include', 'wpbb_child_v75_force_woo_legacy_template', PHP_INT_MAX );
remove_filter( 'template_include', 'wpbb_child_v108_force_woo_template', PHP_INT_MAX );
remove_filter( 'template_include', 'wpbb_child_381047_force_woo_legacy_template', PHP_INT_MAX );
remove_filter( 'render_block', 'wpbb_child_v97_render_hero_finder', 180 );

/* The v110 inline colour used the old pink palette and printed after stylesheets. */
remove_action( 'wp_head', 'wpbb_child_v110_critical_css', PHP_INT_MAX );

/*
 * Stop the old late frontend repair callbacks themselves, not just their
 * registered asset handles. This prevents dependency chains and inline data
 * from reviving superseded v98-v136 layers before v140 runs.
 */
if ( ! function_exists( 'wpbb_clouthes_v140_disable_legacy_frontend' ) ) {
    function wpbb_clouthes_v140_disable_legacy_frontend() {
        $callbacks = array(
            'wpbb_suite_v98_enqueue'   => 999,
            'wpbb_child_v99_enqueue'   => 1200,
            'wpbb_child_v100_enqueue'  => 1900,
            'wpbb_child_v101_enqueue'  => 2500,
            'wpbb_child_v104_enqueue'  => 100000,
            'wpbb_child_v105_enqueue'  => 100500,
            'wpbb_child_v107_enqueue'  => 100700,
            'wpbb_child_v108_enqueue'  => 100800,
            'wpbb_child_v109_enqueue'  => 100900,
        );
        foreach ( $callbacks as $callback => $priority ) remove_action( 'wp_enqueue_scripts', $callback, $priority );
        foreach ( array( 110, 111, 112, 113, 114, 115, 116, 117, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 134, 135, 136 ) as $version ) {
            remove_action( 'wp_enqueue_scripts', 'wpbb_child_v' . $version . '_enqueue', PHP_INT_MAX );
        }

        /* These final body markers existed only to activate their retired CSS. */
        remove_filter( 'body_class', 'wpbb_child_v134_body_class', PHP_INT_MAX );
        remove_filter( 'body_class', 'wpbb_child_v135_body_class', PHP_INT_MAX );
        remove_filter( 'body_class', 'wpbb_child_v136_body_class', PHP_INT_MAX );

        /* v140 has the only homepage finder owner. */
        remove_filter( 'render_block', 'wpbb_child_v97_render_hero_finder', 180 );

        /*
         * Stop the old automatic demo/database repair workers. These were useful
         * while the shared demo system was evolving, but on an already-built
         * Clothes site they can rewrite the homepage or product media merely by
         * visiting wp-admin or by a scheduled background event firing.
         */
        remove_action( 'admin_init', 'wpbb_child_v62_rebuild_demo_pages', 80 );
        remove_action( 'admin_init', 'wpbb_child_v74_sync_commerce_demo', 140 );
        remove_action( 'admin_init', 'wpbb_child_v75_refresh_sector_media', 155 );
        remove_action( 'admin_init', 'wpbb_child_v75_refresh_woo_demo_products', 160 );
        remove_action( 'admin_init', 'wpbb_child_v82_cleanup_managed_demo', 230 );
        remove_action( 'admin_init', 'wpbb_child_v83_refresh_managed_demo', 245 );
        remove_action( 'admin_init', 'wpbb_child_v97_refresh_managed_demo', 330 );
        remove_action( 'init', 'wpbb_child_381046_schedule', 40 );
        remove_action( 'wp_theme_after_demo_import', 'wpbb_child_381046_schedule', 220 );
        remove_action( 'wpbb_child_381046_consistency_batch', 'wpbb_child_381046_consistency_batch' );
        remove_action( 'after_switch_theme', 'wpbb_child_381046_reset_on_switch', 25 );
        remove_action( 'admin_post_wpbb_child_381046_manual_run', 'wpbb_child_381046_manual_run' );
        remove_filter( 'wp_theme_general_settings_extension_markup', 'wpbb_child_381046_settings_status', 30 );
        if ( function_exists( 'wp_clear_scheduled_hook' ) ) wp_clear_scheduled_hook( 'wpbb_child_381046_consistency_batch' );

        /*
         * Old finish releases also registered one-time admin/import migrations.
         * Those callbacks can rewrite the stored homepage after the visual layer
         * has already been corrected, which makes a site appear to regress after
         * an admin visit, Starter Setup reset, or demo re-import. Keep the helper
         * functions available for backwards compatibility, but retire every
         * superseded content/media mutation. v118 remains the stable hero source.
         */
        $legacy_actions = array(
            array( 'admin_init', 'wpbb_child_v99_refresh_managed_demo', 500 ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v99_refresh_managed_demo', 500 ),
            array( 'admin_init', 'wpbb_child_v101_refresh_managed_demo', 1300 ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v101_refresh_managed_demo', 1300 ),
            array( 'admin_init', 'wpbb_child_v109_refresh_once', 100900 ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v109_refresh_once', 100900 ),
            array( 'admin_init', 'wpbb_child_v109_repair_demo_product_media_once', 100910 ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v109_repair_demo_product_media_once', 100910 ),
            array( 'admin_init', 'wpbb_child_v110_repair_once', 100950 ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v110_repair_once', 100950 ),
            array( 'admin_init', 'wpbb_child_v111_repair_once', 100960 ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v111_repair_once', 100960 ),
            array( 'admin_init', 'wpbb_child_v112_repair_once', 100970 ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v112_repair_once', 100970 ),
            array( 'admin_init', 'wpbb_child_v113_repair_once', 100980 ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v113_repair_once', 100980 ),
            array( 'admin_init', 'wpbb_child_v114_repair_once', 101000 ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v114_repair_once', 101000 ),
            array( 'admin_init', 'wpbb_child_v115_repair_once', 101100 ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v115_repair_once', 101100 ),
            array( 'admin_init', 'wpbb_child_v116_repair_once', PHP_INT_MAX ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v116_after_demo_import', PHP_INT_MAX ),
            array( 'admin_init', 'wpbb_child_v117_repair_once', PHP_INT_MAX ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v117_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_after_demo_reset', 'wpbb_child_v117_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_demo_reset_complete', 'wpbb_child_v117_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_starter_setup_complete', 'wpbb_child_v117_after_demo_import', PHP_INT_MAX ),
            array( 'admin_init', 'wpbb_child_v118_repair_once', PHP_INT_MAX ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v118_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_after_demo_reset', 'wpbb_child_v118_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_demo_reset_complete', 'wpbb_child_v118_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_starter_setup_complete', 'wpbb_child_v118_after_demo_import', PHP_INT_MAX ),
            array( 'admin_init', 'wpbb_child_v119_repair_once', PHP_INT_MAX ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v119_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_after_demo_reset', 'wpbb_child_v119_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_demo_reset_complete', 'wpbb_child_v119_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_starter_setup_complete', 'wpbb_child_v119_after_demo_import', PHP_INT_MAX ),
            array( 'admin_init', 'wpbb_child_v120_repair_once', PHP_INT_MAX ),
            array( 'wp_theme_after_demo_import', 'wpbb_child_v120_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_after_demo_reset', 'wpbb_child_v120_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_demo_reset_complete', 'wpbb_child_v120_after_demo_import', PHP_INT_MAX ),
            array( 'wp_theme_starter_setup_complete', 'wpbb_child_v120_after_demo_import', PHP_INT_MAX ),
        );
        foreach ( $legacy_actions as $hook ) remove_action( $hook[0], $hook[1], $hook[2] );

        foreach ( array(
            array( 'wpbb_child_v114_filter_post_data', 90 ),
            array( 'wpbb_child_v115_filter_post_data', 9999 ),
            array( 'wpbb_child_v116_filter_post_data', PHP_INT_MAX ),
            array( 'wpbb_child_v117_filter_post_data', PHP_INT_MAX ),
            array( 'wpbb_child_v118_filter_post_data', PHP_INT_MAX ),
            array( 'wpbb_child_v119_filter_post_data', PHP_INT_MAX ),
            array( 'wpbb_child_v120_filter_post_data', PHP_INT_MAX ),
        ) as $filter ) remove_filter( 'wp_insert_post_data', $filter[0], $filter[1] );

        /* v118 is the only retained late demo-profile/hero normalizer. */
        foreach ( array(
            array( 'wpbb_child_v99_profile', 2000 ),
            array( 'wpbb_child_v100_demo_profile', 3100 ),
            array( 'wpbb_child_v109_demo_profile', PHP_INT_MAX ),
            array( 'wpbb_child_v110_demo_profile', PHP_INT_MAX ),
            array( 'wpbb_child_v111_demo_profile', PHP_INT_MAX ),
            array( 'wpbb_child_v112_demo_profile', PHP_INT_MAX ),
            array( 'wpbb_child_v113_demo_profile', PHP_INT_MAX ),
            array( 'wpbb_child_v114_demo_profile', PHP_INT_MAX ),
            array( 'wpbb_child_v115_demo_profile', PHP_INT_MAX ),
            array( 'wpbb_child_v116_demo_profile', PHP_INT_MAX ),
            array( 'wpbb_child_v117_demo_profile', PHP_INT_MAX ),
        ) as $filter ) remove_filter( 'wp_theme_demo_profile', $filter[0], $filter[1] );

        /* Superseded account aliases can collide with Woo's own endpoint router. */
        remove_action( 'init', 'wpbb_child_v112_account_rewrites', 99 );
        remove_filter( 'query_vars', 'wpbb_child_v112_account_query_var' );
        remove_action( 'template_redirect', 'wpbb_child_v112_account_alias_redirect', 1 );
    }
}
wpbb_clouthes_v140_disable_legacy_frontend();
add_action( 'after_setup_theme', 'wpbb_clouthes_v140_disable_legacy_frontend', PHP_INT_MAX );
add_action( 'init', 'wpbb_clouthes_v140_disable_legacy_frontend', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v140_is_woo_request' ) ) {
    function wpbb_clouthes_v140_is_woo_request() {
        if ( ! function_exists( 'WC' ) && ! class_exists( 'WooCommerce' ) ) return false;
        $product_search = function_exists( 'is_search' ) && is_search() && (
            'product' === get_query_var( 'post_type' )
            || ( isset( $_GET['post_type'] ) && 'product' === sanitize_key( wp_unslash( $_GET['post_type'] ) ) )
        );
        return ( function_exists( 'is_woocommerce' ) && is_woocommerce() )
            || ( function_exists( 'is_cart' ) && is_cart() )
            || ( function_exists( 'is_checkout' ) && is_checkout() )
            || ( function_exists( 'is_account_page' ) && is_account_page() )
            || $product_search;
    }
}

if ( ! function_exists( 'wpbb_clouthes_v140_is_demo_host' ) ) {
    function wpbb_clouthes_v140_is_demo_host() {
        $host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( (string) wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
        $host = preg_replace( '/:\d+$/', '', $host );
        if ( ! $host ) $host = strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) );
        return 'demo-woo-clouthes.dev4.uk' === $host;
    }
}

if ( ! function_exists( 'wpbb_clouthes_v140_demo_store_visibility' ) ) {
    function wpbb_clouthes_v140_demo_store_visibility( $pre ) {
        return wpbb_clouthes_v140_is_demo_host() ? 'no' : $pre;
    }
}
add_filter( 'pre_option_woocommerce_coming_soon', 'wpbb_clouthes_v140_demo_store_visibility', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v140_body_classes' ) ) {
    function wpbb_clouthes_v140_body_classes( $classes ) {
        $classes[] = 'wpbb-v140';
        if ( is_front_page() ) $classes[] = 'wpbb-v140-home';

        if ( wpbb_clouthes_v140_is_woo_request() ) {
            $classes[] = 'wpbb-v140-woo';
            if ( function_exists( 'is_product' ) && is_product() ) {
                $classes[] = 'wpbb-v140-woo-product';
            } elseif ( function_exists( 'is_cart' ) && is_cart() ) {
                $classes[] = 'wpbb-v140-woo-cart';
                if ( function_exists( 'WC' ) && WC()->cart && WC()->cart->is_empty() ) $classes[] = 'wpbb-v140-cart-empty';
            } elseif ( function_exists( 'is_checkout' ) && is_checkout() ) {
                $classes[] = 'wpbb-v140-woo-checkout';
                if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) $classes[] = 'wpbb-v140-order-received';
            } elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
                $classes[] = 'wpbb-v140-woo-account';
                $classes[] = is_user_logged_in() ? 'wpbb-v140-account-authenticated' : 'wpbb-v140-account-guest';
            } else {
                $classes[] = 'wpbb-v140-woo-catalog';
            }
        }
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v140_body_classes', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v140_enqueue' ) ) {
    function wpbb_clouthes_v140_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        /*
         * Remove the accumulated repair cascade site-wide. The base compiled
         * theme, v62 system CSS, v83/v97 sector layer, Woo assets and v118 stay.
         * v118 is deliberately re-enqueued because v119 historically removed
         * its enqueue action during init.
         */
        foreach ( range( 98, 139 ) as $n ) {
            if ( 118 === $n ) continue;
            $handle = 'wpbb-suite-v' . $n;
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }
        foreach ( array( 'wpbb-suite-v137-woo', 'wpbb-suite-v138-woo-hard', 'wpbb-suite-v139-clouthes-hard' ) as $handle ) {
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }

        $base_css = $dir . '/assets/suite-v118.css';
        $base_js  = $dir . '/assets/suite-v118.js';
        if ( is_readable( $base_css ) ) {
            wp_dequeue_style( 'wpbb-suite-v118' );
            wp_deregister_style( 'wpbb-suite-v118' );
            wp_enqueue_style( 'wpbb-suite-v118', $uri . '/assets/suite-v118.css', array(), (string) filemtime( $base_css ) );
        }
        if ( is_readable( $base_js ) ) {
            wp_dequeue_script( 'wpbb-suite-v118' );
            wp_deregister_script( 'wpbb-suite-v118' );
            wp_enqueue_script( 'wpbb-suite-v118', $uri . '/assets/suite-v118.js', array(), (string) filemtime( $base_js ), false );
        }

        $css = $dir . '/assets/suite-v140-clouthes-clean.css';
        $js  = $dir . '/assets/suite-v140-clouthes-clean.js';
        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v140-clouthes-clean',
                $uri . '/assets/suite-v140-clouthes-clean.css',
                array( 'wpbb-suite-v118' ),
                (string) filemtime( $css )
            );
        }
        if ( is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v140-clouthes-clean',
                $uri . '/assets/suite-v140-clouthes-clean.js',
                array( 'wpbb-suite-v118' ),
                (string) filemtime( $js ),
                true
            );
            wp_add_inline_script(
                'wpbb-suite-v140-clouthes-clean',
                'window.wpbbSuiteV140=' . wp_json_encode( array( 'version' => '3.8.11.40' ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v140_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v140_template' ) ) {
    function wpbb_clouthes_v140_template( $template ) {
        if ( is_admin() || wp_doing_ajax() || is_feed() || ! wpbb_clouthes_v140_is_woo_request() ) return $template;

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
        } elseif ( ( function_exists( 'is_shop' ) && is_shop() )
            || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() )
            || ( function_exists( 'is_search' ) && is_search() && 'product' === get_query_var( 'post_type' ) ) ) {
            $candidate = 'catalog.php';
        }

        return $candidate && is_readable( $base . $candidate ) ? $base . $candidate : $template;
    }
}
add_filter( 'template_include', 'wpbb_clouthes_v140_template', PHP_INT_MAX );

/* Block-template fallback: use the current Woo main query, never [products]. */
if ( ! function_exists( 'wpbb_clouthes_v140_shop_shortcode' ) ) {
    function wpbb_clouthes_v140_shop_shortcode() {
        if ( ! function_exists( 'woocommerce_product_loop' ) ) return '';
        $is_tax = function_exists( 'is_product_taxonomy' ) && is_product_taxonomy();
        $title = $is_tax ? single_term_title( '', false ) : __( 'Shop the collection.', 'wp-bbtheme-child-woo-clouthes' );
        $eyebrow = $is_tax ? __( 'Collection', 'wp-bbtheme-child-woo-clouthes' ) : __( 'Shop', 'wp-bbtheme-child-woo-clouthes' );

        ob_start();
        ?>
        <main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--catalog wp-theme-woo-archive">
          <section class="wp-theme-woo-legacy__hero"><div class="container">
            <p class="wp-theme-sector-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
            <h1><?php echo esc_html( $title ); ?></h1>
            <?php if ( $is_tax && term_description() ) : ?><div class="wp-theme-woo-legacy__intro"><?php echo wp_kses_post( term_description() ); ?></div><?php else : ?><p><?php esc_html_e( 'Browse the collection, compare fit and move to checkout without losing context.', 'wp-bbtheme-child-woo-clouthes' ); ?></p><?php endif; ?>
          </div></section>
          <div class="container wp-theme-woo-legacy__body"><div class="woocommerce wpbb-woo-surface wpbb-woo-catalog-surface">
          <?php
          if ( woocommerce_product_loop() ) {
              echo '<div class="wpbb-woo-before-loop">';
              do_action( 'woocommerce_before_shop_loop' );
              echo '</div>';
              woocommerce_product_loop_start();
              if ( wc_get_loop_prop( 'total' ) ) {
                  while ( have_posts() ) {
                      the_post();
                      do_action( 'woocommerce_shop_loop' );
                      wc_get_template_part( 'content', 'product' );
                  }
              }
              woocommerce_product_loop_end();
              echo '<div class="wpbb-woo-after-loop">';
              do_action( 'woocommerce_after_shop_loop' );
              echo '</div>';
          } else {
              do_action( 'woocommerce_no_products_found' );
          }
          ?>
          </div></div>
        </main>
        <?php
        return ob_get_clean();
    }
}
remove_shortcode( 'wpbb_clothes_shop_page' );
add_shortcode( 'wpbb_clothes_shop_page', 'wpbb_clouthes_v140_shop_shortcode' );

if ( ! function_exists( 'wpbb_clouthes_v140_term_link' ) ) {
    function wpbb_clouthes_v140_term_link( $label, $shop_url ) {
        if ( ! taxonomy_exists( 'product_cat' ) ) return $shop_url;
        $slug = sanitize_title( $label );
        $term = get_term_by( 'slug', $slug, 'product_cat' );
        if ( ! $term ) $term = get_term_by( 'name', $label, 'product_cat' );
        if ( ! $term || is_wp_error( $term ) ) return add_query_arg( 'product_cat', $slug, $shop_url );
        $url = get_term_link( $term, 'product_cat' );
        return is_wp_error( $url ) ? $shop_url : $url;
    }
}

if ( ! function_exists( 'wpbb_clouthes_v140_finder_markup' ) ) {
    function wpbb_clouthes_v140_finder_markup() {
        $shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
        if ( ! $shop_url ) $shop_url = home_url( '/' );

        $options = '<option value="">' . esc_html__( 'All categories', 'wp-bbtheme-child-woo-clouthes' ) . '</option>';
        if ( taxonomy_exists( 'product_cat' ) ) {
            $terms = get_terms( array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'parent'     => 0,
                'number'     => 24,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ) );
            if ( ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    $options .= '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
                }
            }
        }

        $chips  = '<a href="' . esc_url( add_query_arg( 'orderby', 'date', $shop_url ) ) . '">' . esc_html__( 'New in', 'wp-bbtheme-child-woo-clouthes' ) . '</a>';
        foreach ( array( 'Women', 'Men', 'Accessories' ) as $label ) {
            $chips .= '<a href="' . esc_url( wpbb_clouthes_v140_term_link( $label, $shop_url ) ) . '">' . esc_html( $label ) . '</a>';
        }

        return '<div class="wpbb-v97-hero-finder wpbb-v140-hero-finder">'
            . '<form class="wpbb-v97-hero-finder__form" method="get" action="' . esc_url( $shop_url ) . '">'
            . '<label class="wpbb-v97-hero-finder__field"><span class="screen-reader-text">' . esc_html__( 'Search products', 'wp-bbtheme-child-woo-clouthes' ) . '</span><input type="search" name="s" placeholder="' . esc_attr__( 'Search products', 'wp-bbtheme-child-woo-clouthes' ) . '"></label>'
            . '<label class="wpbb-v97-hero-finder__field"><span class="screen-reader-text">' . esc_html__( 'Category', 'wp-bbtheme-child-woo-clouthes' ) . '</span><select name="product_cat" aria-label="' . esc_attr__( 'Product category', 'wp-bbtheme-child-woo-clouthes' ) . '">' . $options . '</select></label>'
            . '<input type="hidden" name="post_type" value="product">'
            . '<button class="wpbb-v97-hero-finder__submit" type="submit">' . esc_html__( 'Search', 'wp-bbtheme-child-woo-clouthes' ) . ' &rarr;</button>'
            . '</form><div class="wpbb-v97-hero-finder__chips"><span>' . esc_html__( 'Popular:', 'wp-bbtheme-child-woo-clouthes' ) . '</span>' . $chips . '</div></div>';
    }
}

if ( ! function_exists( 'wpbb_clouthes_v140_finder_pattern' ) ) {
    function wpbb_clouthes_v140_finder_pattern() {
        return '~<div\b(?=[^>]*class=(?:"[^"]*\bwpbb-v97-hero-finder\b[^"]*"|\'[^\']*\bwpbb-v97-hero-finder\b[^\']*\'))[^>]*>.*?<div\b(?=[^>]*class=(?:"[^"]*\bwpbb-v97-hero-finder__chips\b[^"]*"|\'[^\']*\bwpbb-v97-hero-finder__chips\b[^\']*\'))[^>]*>.*?</div>\s*</div>~is';
    }
}

if ( ! function_exists( 'wpbb_clouthes_v140_strip_finders' ) ) {
    function wpbb_clouthes_v140_strip_finders( $html ) {
        if ( ! is_string( $html ) || false === strpos( $html, 'wpbb-v97-hero-finder' ) ) return $html;
        return (string) preg_replace( wpbb_clouthes_v140_finder_pattern(), '', $html );
    }
}

/* Strip every historical finder from every hero, then add exactly one to hero #1. */
if ( ! function_exists( 'wpbb_clouthes_v140_render_hero_finder' ) ) {
    function wpbb_clouthes_v140_render_hero_finder( $block_content, $block ) {
        static $hero_index = 0;
        if ( is_admin() || ! is_front_page() || empty( $block['blockName'] ) || 'wpbb/swiper' !== $block['blockName'] ) return $block_content;
        if ( false === strpos( $block_content, 'wpbb-swiper--hero' ) ) return $block_content;

        $hero_index++;
        $block_content = wpbb_clouthes_v140_strip_finders( $block_content );
        $owner_class = 1 === $hero_index ? 'wpbb-v140-primary-hero' : 'wpbb-v140-secondary-hero';
        $block_content = (string) preg_replace( '/\bwpbb-swiper--hero\b/', 'wpbb-swiper--hero ' . $owner_class, $block_content, 1 );

        if ( 1 !== $hero_index ) return $block_content;
        $finder = wpbb_clouthes_v140_finder_markup();
        if ( '' === $finder ) return $block_content;

        $needle = '</div></article>';
        $pos = strrpos( $block_content, $needle );
        if ( false !== $pos ) return substr_replace( $block_content, $finder, $pos, 0 );

        $pos = strrpos( $block_content, '</article>' );
        if ( false !== $pos ) return substr_replace( $block_content, $finder, $pos, 0 );

        return $block_content . $finder;
    }
}
add_filter( 'render_block', 'wpbb_clouthes_v140_render_hero_finder', PHP_INT_MAX, 2 );

/* Last server-side guard: if cached/imported content already contains copies, keep only the first. */
if ( ! function_exists( 'wpbb_clouthes_v140_dedupe_home_content' ) ) {
    function wpbb_clouthes_v140_dedupe_home_content( $content ) {
        if ( is_admin() || ! is_front_page() || ! is_string( $content ) || false === strpos( $content, 'wpbb-v97-hero-finder' ) ) return $content;
        $seen = 0;
        return (string) preg_replace_callback(
            wpbb_clouthes_v140_finder_pattern(),
            static function( $match ) use ( &$seen ) {
                $seen++;
                return 1 === $seen ? $match[0] : '';
            },
            $content
        );
    }
}
add_filter( 'the_content', 'wpbb_clouthes_v140_dedupe_home_content', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v140_loop_columns' ) ) {
    function wpbb_clouthes_v140_loop_columns( $columns ) {
        return wpbb_clouthes_v140_is_woo_request() ? 3 : $columns;
    }
}
add_filter( 'loop_shop_columns', 'wpbb_clouthes_v140_loop_columns', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v140_related_products' ) ) {
    function wpbb_clouthes_v140_related_products( $args ) {
        if ( ! wpbb_clouthes_v140_is_woo_request() || ! is_array( $args ) ) return $args;
        $args['posts_per_page'] = 3;
        $args['columns'] = 3;
        return $args;
    }
}
add_filter( 'woocommerce_output_related_products_args', 'wpbb_clouthes_v140_related_products', PHP_INT_MAX );

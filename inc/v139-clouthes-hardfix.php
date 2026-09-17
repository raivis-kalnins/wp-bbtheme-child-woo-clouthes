<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.39.
 *
 * Final Clothes ownership layer:
 * - one homepage finder in the first hero only;
 * - Woo-aware homepage search (keyword + real product category);
 * - one authoritative WooCommerce asset/template owner;
 * - native Woo archive/single-product rendering;
 * - demo-only Woo Coming Soon bypass for demo-woo-clouthes.dev4.uk;
 * - stable mega-menu positioning and responsive commerce alignment.
 */
defined( 'ABSPATH' ) || exit;

/* v139 replaces the v138 frontend Woo owner. */
remove_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v138_assets', PHP_INT_MAX );
remove_filter( 'body_class', 'wpbb_clouthes_v138_body_classes', PHP_INT_MAX );
remove_filter( 'template_include', 'wpbb_clouthes_v138_template', PHP_INT_MAX );
remove_filter( 'loop_shop_columns', 'wpbb_clouthes_v138_loop_columns', PHP_INT_MAX );
remove_filter( 'woocommerce_output_related_products_args', 'wpbb_clouthes_v138_related_products', PHP_INT_MAX );

/* Retire competing Woo template routers. */
remove_filter( 'template_include', 'wpbb_clouthes_woocommerce_legacy_template_v36', 99 );
remove_filter( 'template_include', 'wpbb_child_v75_force_woo_legacy_template', PHP_INT_MAX );
remove_filter( 'template_include', 'wpbb_child_v108_force_woo_template', PHP_INT_MAX );
remove_filter( 'template_include', 'wpbb_child_381047_force_woo_legacy_template', PHP_INT_MAX );

/* v97 injected its finder into every hero swiper. v139 owns this path. */
remove_filter( 'render_block', 'wpbb_child_v97_render_hero_finder', 180 );

if ( ! function_exists( 'wpbb_clouthes_v139_is_woo_request' ) ) {
    function wpbb_clouthes_v139_is_woo_request() {
        if ( ! function_exists( 'WC' ) && ! class_exists( 'WooCommerce' ) ) return false;
        return ( function_exists( 'is_woocommerce' ) && is_woocommerce() )
            || ( function_exists( 'is_cart' ) && is_cart() )
            || ( function_exists( 'is_checkout' ) && is_checkout() )
            || ( function_exists( 'is_account_page' ) && is_account_page() );
    }
}

if ( ! function_exists( 'wpbb_clouthes_v139_is_demo_host' ) ) {
    function wpbb_clouthes_v139_is_demo_host() {
        $host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( (string) wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
        $host = preg_replace( '/:\d+$/', '', $host );
        if ( ! $host ) $host = strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) );
        return 'demo-woo-clouthes.dev4.uk' === $host;
    }
}

/* The public reference demo should expose its Woo pages even if Store Visibility was left in Coming Soon. */
if ( ! function_exists( 'wpbb_clouthes_v139_demo_store_visibility' ) ) {
    function wpbb_clouthes_v139_demo_store_visibility( $pre ) {
        return wpbb_clouthes_v139_is_demo_host() ? 'no' : $pre;
    }
}
add_filter( 'pre_option_woocommerce_coming_soon', 'wpbb_clouthes_v139_demo_store_visibility', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v139_body_classes' ) ) {
    function wpbb_clouthes_v139_body_classes( $classes ) {
        $classes[] = 'wpbb-v139';
        if ( is_front_page() ) $classes[] = 'wpbb-v139-home';

        if ( wpbb_clouthes_v139_is_woo_request() ) {
            $classes[] = 'wpbb-v139-woo';
            if ( function_exists( 'is_product' ) && is_product() ) {
                $classes[] = 'wpbb-v139-woo-product';
            } elseif ( function_exists( 'is_cart' ) && is_cart() ) {
                $classes[] = 'wpbb-v139-woo-cart';
                if ( function_exists( 'WC' ) && WC()->cart && WC()->cart->is_empty() ) $classes[] = 'wpbb-v139-cart-empty';
            } elseif ( function_exists( 'is_checkout' ) && is_checkout() ) {
                $classes[] = 'wpbb-v139-woo-checkout';
                if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) $classes[] = 'wpbb-v139-order-received';
            } elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
                $classes[] = 'wpbb-v139-woo-account';
                $classes[] = is_user_logged_in() ? 'wpbb-v139-account-authenticated' : 'wpbb-v139-account-guest';
            } else {
                $classes[] = 'wpbb-v139-woo-catalog';
            }
        }
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v139_body_classes', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v139_enqueue' ) ) {
    function wpbb_clouthes_v139_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();

        if ( wpbb_clouthes_v139_is_woo_request() ) {
            /*
             * Woo pages had overlapping finish layers. Keep v118 as the stable
             * header/menu/grid base and let v139 own the commerce surface.
             */
            foreach ( range( 98, 138 ) as $n ) {
                if ( 118 === $n ) continue;
                $handle = 'wpbb-suite-v' . $n;
                wp_dequeue_style( $handle );
                wp_deregister_style( $handle );
                wp_dequeue_script( $handle );
                wp_deregister_script( $handle );
            }
            wp_dequeue_style( 'wpbb-suite-v138-woo-hard' );
            wp_deregister_style( 'wpbb-suite-v138-woo-hard' );

            $base_css = $dir . '/assets/suite-v118.css';
            $base_js  = $dir . '/assets/suite-v118.js';
            if ( is_readable( $base_css ) ) {
                wp_enqueue_style( 'wpbb-suite-v118', $uri . '/assets/suite-v118.css', array(), (string) filemtime( $base_css ) );
            }
            if ( is_readable( $base_js ) ) {
                wp_enqueue_script( 'wpbb-suite-v118', $uri . '/assets/suite-v118.js', array(), (string) filemtime( $base_js ), false );
            }
        }

        $css = $dir . '/assets/suite-v139-clouthes-hard.css';
        $js  = $dir . '/assets/suite-v139-clouthes-hard.js';
        $css_deps = array();
        $js_deps  = array();

        if ( wpbb_clouthes_v139_is_woo_request() ) {
            if ( wp_style_is( 'wpbb-suite-v118', 'registered' ) || wp_style_is( 'wpbb-suite-v118', 'enqueued' ) ) $css_deps[] = 'wpbb-suite-v118';
            if ( wp_script_is( 'wpbb-suite-v118', 'registered' ) || wp_script_is( 'wpbb-suite-v118', 'enqueued' ) ) $js_deps[] = 'wpbb-suite-v118';
        } else {
            if ( wp_style_is( 'wpbb-suite-v136', 'registered' ) || wp_style_is( 'wpbb-suite-v136', 'enqueued' ) ) $css_deps[] = 'wpbb-suite-v136';
            if ( wp_script_is( 'wpbb-suite-v136', 'registered' ) || wp_script_is( 'wpbb-suite-v136', 'enqueued' ) ) $js_deps[] = 'wpbb-suite-v136';
        }

        if ( is_readable( $css ) ) {
            wp_enqueue_style( 'wpbb-suite-v139-clouthes-hard', $uri . '/assets/suite-v139-clouthes-hard.css', $css_deps, (string) filemtime( $css ) );
        }
        if ( is_readable( $js ) ) {
            wp_enqueue_script( 'wpbb-suite-v139-clouthes-hard', $uri . '/assets/suite-v139-clouthes-hard.js', $js_deps, (string) filemtime( $js ), true );
            wp_add_inline_script(
                'wpbb-suite-v139-clouthes-hard',
                'window.wpbbSuiteV139=' . wp_json_encode( array( 'version' => '3.8.11.39' ) ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v139_enqueue', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v139_template' ) ) {
    function wpbb_clouthes_v139_template( $template ) {
        if ( is_admin() || wp_doing_ajax() || is_feed() || ! wpbb_clouthes_v139_is_woo_request() ) return $template;

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
}
add_filter( 'template_include', 'wpbb_clouthes_v139_template', PHP_INT_MAX );


/*
 * Hybrid/block-template fallback: legacy archive-product.html files use the
 * wpbb_clothes_shop_page shortcode. Rebind that shortcode to the real current
 * Woo main query so taxonomy context, ordering, notices and pagination survive
 * even if WordPress chooses the HTML template before our PHP router.
 */
if ( ! function_exists( 'wpbb_clouthes_v139_shop_shortcode' ) ) {
    function wpbb_clouthes_v139_shop_shortcode() {
        if ( ! function_exists( 'woocommerce_product_loop' ) ) return '';
        $is_tax = function_exists( 'is_product_taxonomy' ) && is_product_taxonomy();
        $title = $is_tax ? single_term_title( '', false ) : __( 'Shop the collection.', 'wp-bbtheme-child-woo-clouthes' );
        $eyebrow = $is_tax ? __( 'Collection', 'wp-bbtheme-child-woo-clouthes' ) : __( 'Shop', 'wp-bbtheme-child-woo-clouthes' );

        ob_start();
        ?>
        <main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--catalog wp-theme-woo-archive">
          <section class="wp-theme-woo-legacy__hero">
            <div class="container">
              <p class="wp-theme-sector-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
              <h1><?php echo esc_html( $title ); ?></h1>
              <?php if ( $is_tax && term_description() ) : ?>
                <div class="wp-theme-woo-legacy__intro"><?php echo wp_kses_post( term_description() ); ?></div>
              <?php else : ?>
                <p><?php esc_html_e( 'Browse the collection, compare fit and move to checkout without losing context.', 'wp-bbtheme-child-woo-clouthes' ); ?></p>
              <?php endif; ?>
            </div>
          </section>
          <div class="container wp-theme-woo-legacy__body">
            <div class="woocommerce wpbb-woo-surface wpbb-woo-catalog-surface">
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
            </div>
          </div>
        </main>
        <?php
        return ob_get_clean();
    }
}
remove_shortcode( 'wpbb_clothes_shop_page' );
add_shortcode( 'wpbb_clothes_shop_page', 'wpbb_clouthes_v139_shop_shortcode' );

if ( ! function_exists( 'wpbb_clouthes_v139_term_link' ) ) {
    function wpbb_clouthes_v139_term_link( $label, $shop_url ) {
        if ( ! taxonomy_exists( 'product_cat' ) ) return $shop_url;
        $slug = sanitize_title( $label );
        $term = get_term_by( 'slug', $slug, 'product_cat' );
        if ( ! $term ) $term = get_term_by( 'name', $label, 'product_cat' );
        if ( ! $term || is_wp_error( $term ) ) return add_query_arg( 'product_cat', $slug, $shop_url );
        $url = get_term_link( $term, 'product_cat' );
        return is_wp_error( $url ) ? $shop_url : $url;
    }
}

if ( ! function_exists( 'wpbb_clouthes_v139_finder_markup' ) ) {
    function wpbb_clouthes_v139_finder_markup() {
        $shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
        if ( ! $shop_url ) $shop_url = home_url( '/' );

        $options = '<option value="">' . esc_html__( 'All categories', 'wp-bbtheme-child-woo-clouthes' ) . '</option>';
        if ( taxonomy_exists( 'product_cat' ) ) {
            $terms = get_terms( array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'parent'     => 0,
                'number'     => 18,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ) );
            if ( ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    $options .= '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
                }
            }
        }

        $chips = '';
        foreach ( array( 'New in', 'Women', 'Men', 'Accessories' ) as $label ) {
            $chips .= '<a href="' . esc_url( wpbb_clouthes_v139_term_link( $label, $shop_url ) ) . '">' . esc_html( $label ) . '</a>';
        }

        return '<div class="wpbb-v97-hero-finder wpbb-v139-hero-finder">'
            . '<form class="wpbb-v97-hero-finder__form" method="get" action="' . esc_url( $shop_url ) . '">'
            . '<label class="wpbb-v97-hero-finder__field"><span class="screen-reader-text">' . esc_html__( 'Search products', 'wp-bbtheme-child-woo-clouthes' ) . '</span><input type="search" name="s" placeholder="' . esc_attr__( 'Search products', 'wp-bbtheme-child-woo-clouthes' ) . '"></label>'
            . '<label class="wpbb-v97-hero-finder__field"><span class="screen-reader-text">' . esc_html__( 'Category', 'wp-bbtheme-child-woo-clouthes' ) . '</span><select name="product_cat" aria-label="' . esc_attr__( 'Product category', 'wp-bbtheme-child-woo-clouthes' ) . '">' . $options . '</select></label>'
            . '<input type="hidden" name="post_type" value="product">'
            . '<button class="wpbb-v97-hero-finder__submit" type="submit">' . esc_html__( 'Search', 'wp-bbtheme-child-woo-clouthes' ) . ' &rarr;</button>'
            . '</form><div class="wpbb-v97-hero-finder__chips"><span>' . esc_html__( 'Popular:', 'wp-bbtheme-child-woo-clouthes' ) . '</span>' . $chips . '</div></div>';
    }
}

/* Inject the finder once: first closing content wrapper in the first homepage hero only. */
if ( ! function_exists( 'wpbb_clouthes_v139_render_hero_finder' ) ) {
    function wpbb_clouthes_v139_render_hero_finder( $block_content, $block ) {
        static $injected = false;
        if ( $injected || is_admin() || ! is_front_page() || empty( $block['blockName'] ) || 'wpbb/swiper' !== $block['blockName'] ) return $block_content;
        if ( false === strpos( $block_content, 'wpbb-swiper--hero' ) ) return $block_content;
        if ( false !== strpos( $block_content, 'wpbb-v97-hero-finder' ) ) { $injected = true; return $block_content; }

        $finder = wpbb_clouthes_v139_finder_markup();
        if ( '' === $finder ) return $block_content;
        $needle = '</div></article>';
        $pos = strpos( $block_content, $needle );
        if ( false === $pos ) return $block_content;

        $injected = true;
        return substr_replace( $block_content, $finder, $pos, 0 );
    }
}
add_filter( 'render_block', 'wpbb_clouthes_v139_render_hero_finder', 181, 2 );

if ( ! function_exists( 'wpbb_clouthes_v139_loop_columns' ) ) {
    function wpbb_clouthes_v139_loop_columns( $columns ) {
        return wpbb_clouthes_v139_is_woo_request() ? 3 : $columns;
    }
}
add_filter( 'loop_shop_columns', 'wpbb_clouthes_v139_loop_columns', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v139_related_products' ) ) {
    function wpbb_clouthes_v139_related_products( $args ) {
        if ( ! wpbb_clouthes_v139_is_woo_request() || ! is_array( $args ) ) return $args;
        $args['posts_per_page'] = 3;
        $args['columns'] = 3;
        return $args;
    }
}
add_filter( 'woocommerce_output_related_products_args', 'wpbb_clouthes_v139_related_products', PHP_INT_MAX );

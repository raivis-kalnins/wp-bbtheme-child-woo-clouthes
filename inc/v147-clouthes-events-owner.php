<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.47.
 *
 * A single homepage presentation owner, modelled on the stable Events v140
 * layout architecture. v140 Clothes remains responsible for WooCommerce
 * routing/templates off the homepage; its homepage CSS/JS and finder renderer
 * are deliberately retired here so there is no competing cascade.
 */
defined( 'ABSPATH' ) || exit;

/* v147 owns the homepage finder. The v140 block/content filters are removed
 * globally because they only exist to decorate/dedupe the homepage hero. */
remove_filter( 'render_block', 'wpbb_clouthes_v140_render_hero_finder', PHP_INT_MAX );
remove_filter( 'the_content', 'wpbb_clouthes_v140_dedupe_home_content', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v147_body_classes' ) ) {
    function wpbb_clouthes_v147_body_classes( $classes ) {
        $classes[] = 'wpbb-v147';
        if ( is_front_page() ) $classes[] = 'wpbb-v147-home';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v147_body_classes', PHP_INT_MAX );

/**
 * Return a theme image fallback for the eight demo products. Product media from
 * WooCommerce is preferred; these files are only used when a product image is
 * genuinely missing or its attachment metadata is invalid.
 */
if ( ! function_exists( 'wpbb_clouthes_v147_product_fallback' ) ) {
    function wpbb_clouthes_v147_product_fallback( $slug ) {
        $map = array(
            'kids-lightweight-overshirt' => 'utility-overshirt.jpg',
            'classic-oxford-shirt'       => 'classic-oxford-shirt.jpg',
            'essential-t-shirt'          => 'essential-t-shirt.jpg',
            'merino-crew-knit'           => 'merino-crew-knit.jpg',
            'straight-leg-trouser'       => 'straight-leg-trouser.jpg',
            'everyday-denim'             => 'everyday-denim.jpg',
            'canvas-weekend-bag'         => 'canvas-weekend-bag.jpg',
            'leather-card-holder'        => 'leather-card-holder.jpg',
        );
        $file = isset( $map[ $slug ] ) ? $map[ $slug ] : '';
        if ( ! $file ) return '';
        $path = get_stylesheet_directory() . '/assets/img/products/' . $file;
        if ( ! is_readable( $path ) ) return '';
        return trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/products/' . rawurlencode( $file );
    }
}

/**
 * Render the homepage catalogue deterministically from WooCommerce itself.
 * This bypasses the BBuilder catalogue placeholder/lazy-media markup that has
 * repeatedly produced empty beige cards in long-page captures.
 */
if ( ! function_exists( 'wpbb_clouthes_v147_render_catalogue' ) ) {
    function wpbb_clouthes_v147_render_catalogue( $block_content, $block ) {
        if ( is_admin() || ! is_front_page() ) return $block_content;
        if ( empty( $block['blockName'] ) || 'wpbb/catalogue' !== $block['blockName'] ) return $block_content;

        $class_name = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';
        if ( false === strpos( $class_name, 'wp-theme-home-product-catalogue' ) ) return $block_content;
        if ( ! function_exists( 'wc_get_products' ) ) return $block_content;

        $limit = isset( $block['attrs']['postsToShow'] ) ? absint( $block['attrs']['postsToShow'] ) : 8;
        if ( $limit < 1 ) $limit = 8;
        $limit = min( 12, $limit );

        $products = wc_get_products( array(
            'status'  => 'publish',
            'limit'   => $limit,
            'orderby' => 'menu_order',
            'order'   => 'ASC',
            'return'  => 'objects',
        ) );
        if ( ! $products ) return $block_content;

        $html = '<div class="wp-theme-home-product-catalogue wpbb-v147-product-grid" data-wpbb-v147-catalogue="1">';
        foreach ( $products as $product ) {
            if ( ! is_object( $product ) || ! method_exists( $product, 'get_id' ) ) continue;

            $id        = (int) $product->get_id();
            $slug      = sanitize_title( method_exists( $product, 'get_slug' ) ? $product->get_slug() : get_post_field( 'post_name', $id ) );
            $title     = method_exists( $product, 'get_name' ) ? $product->get_name() : get_the_title( $id );
            $url       = method_exists( $product, 'get_permalink' ) ? $product->get_permalink() : get_permalink( $id );
            $short     = method_exists( $product, 'get_short_description' ) ? $product->get_short_description() : '';
            if ( '' === trim( wp_strip_all_tags( $short ) ) ) $short = get_post_field( 'post_excerpt', $id );
            $short     = wp_trim_words( wp_strip_all_tags( (string) $short ), 16, '…' );
            $image_id  = method_exists( $product, 'get_image_id' ) ? absint( $product->get_image_id() ) : get_post_thumbnail_id( $id );
            $image     = '';

            if ( $image_id ) {
                $image = wp_get_attachment_image(
                    $image_id,
                    'woocommerce_thumbnail',
                    false,
                    array(
                        'class'         => 'wpbb-v147-product-card__image',
                        'loading'       => 'eager',
                        'decoding'      => 'async',
                        'fetchpriority' => 'auto',
                        'alt'           => $title,
                    )
                );
            }
            if ( ! $image ) {
                $fallback = wpbb_clouthes_v147_product_fallback( $slug );
                if ( $fallback ) {
                    $image = '<img class="wpbb-v147-product-card__image" src="' . esc_url( $fallback ) . '" alt="' . esc_attr( $title ) . '" loading="eager" decoding="async">';
                }
            }

            $html .= '<article class="wpbb-v147-product-card">';
            $html .= '<a class="wpbb-v147-product-card__media" href="' . esc_url( $url ) . '" aria-label="' . esc_attr( $title ) . '">' . $image . '</a>';
            $html .= '<div class="wpbb-v147-product-card__body">';
            $html .= '<h3 class="wpbb-v147-product-card__title"><a href="' . esc_url( $url ) . '">' . esc_html( $title ) . '</a></h3>';
            if ( $short ) $html .= '<p class="wpbb-v147-product-card__excerpt">' . esc_html( $short ) . '</p>';
            $html .= '<a class="wpbb-v147-product-card__link" href="' . esc_url( $url ) . '">' . esc_html__( 'View product', 'wp-bbtheme-child-woo-clouthes' ) . '</a>';
            $html .= '</div></article>';
        }
        $html .= '</div>';
        return $html;
    }
}
add_filter( 'render_block', 'wpbb_clouthes_v147_render_catalogue', PHP_INT_MAX, 2 );

/* No homepage image should wait for viewport intersection. */
if ( ! function_exists( 'wpbb_clouthes_v147_lazy_loading' ) ) {
    function wpbb_clouthes_v147_lazy_loading( $default, $tag_name, $context ) {
        if ( is_front_page() && 'img' === $tag_name ) return false;
        return $default;
    }
}
add_filter( 'wp_lazy_loading_enabled', 'wpbb_clouthes_v147_lazy_loading', PHP_INT_MAX, 3 );

if ( ! function_exists( 'wpbb_clouthes_v147_image_attributes' ) ) {
    function wpbb_clouthes_v147_image_attributes( $attr ) {
        if ( ! is_front_page() || ! is_array( $attr ) ) return $attr;
        $attr['loading']  = 'eager';
        $attr['decoding'] = 'async';
        return $attr;
    }
}
add_filter( 'wp_get_attachment_image_attributes', 'wpbb_clouthes_v147_image_attributes', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v147_preload_hero' ) ) {
    function wpbb_clouthes_v147_preload_hero() {
        if ( ! is_front_page() ) return;
        $hero = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/hero-v118/slide-1.jpg';
        echo '<link rel="preload" as="image" href="' . esc_url( $hero ) . '" fetchpriority="high">' . "\n";
    }
}
add_action( 'wp_head', 'wpbb_clouthes_v147_preload_hero', 3 );

/**
 * Homepage: remove the v140 presentation assets after their enqueue callback
 * has run, retain only the stable v118 foundation, then load v147. Woo pages
 * continue to receive v140 normally because this function exits off front page.
 */
if ( ! function_exists( 'wpbb_clouthes_v147_enqueue' ) ) {
    function wpbb_clouthes_v147_enqueue() {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $is_home = is_front_page();

        if ( $is_home ) foreach ( array(
            'wpbb-suite-v140-clouthes-clean',
            'wpbb-suite-v141-clouthes-parity',
            'wpbb-suite-v142-clouthes-visual-parity',
            'wpbb-suite-v143-clouthes-screenshot-hardfix',
            'wpbb-suite-v144-clouthes-events-db-hardfix',
            'wpbb-suite-v145-clouthes-live-structure',
            'wpbb-suite-v146-clouthes-home-rebuild',
        ) as $handle ) {
            wp_dequeue_style( $handle );
            wp_deregister_style( $handle );
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }

        $css = $dir . '/assets/suite-v147-clouthes-events-owner.css';
        $js  = $dir . '/assets/suite-v147-clouthes-events-owner.js';
        if ( is_readable( $css ) ) {
            wp_enqueue_style(
                'wpbb-suite-v147-clouthes-events-owner',
                $uri . '/assets/suite-v147-clouthes-events-owner.css',
                array( 'wpbb-suite-v118' ),
                (string) filemtime( $css )
            );
        }
        if ( $is_home && is_readable( $js ) ) {
            wp_enqueue_script(
                'wpbb-suite-v147-clouthes-events-owner',
                $uri . '/assets/suite-v147-clouthes-events-owner.js',
                array( 'wpbb-suite-v118' ),
                (string) filemtime( $js ),
                true
            );
            wp_add_inline_script(
                'wpbb-suite-v147-clouthes-events-owner',
                'window.wpbbSuiteV147=' . wp_json_encode( array(
                    'version'    => '3.8.11.47',
                    'finderHtml' => function_exists( 'wpbb_clouthes_v140_finder_markup' ) ? wpbb_clouthes_v140_finder_markup() : '',
                ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . ';',
                'before'
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v147_enqueue', PHP_INT_MAX );

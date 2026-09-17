<?php
/**
 * WP BBTheme Child Woo Clothes 3.8.11.48.
 *
 * Structural homepage rebuild based on the attached Woo Events 3.8.11.40
 * owner: one 1180px content rail, deterministic hero/partner/value/product
 * rendering, and scoped grid ownership. WooCommerce routes remain owned by
 * the v140 native Woo layer.
 */
defined( 'ABSPATH' ) || exit;

/* Retire the failed v147 homepage owner. */
remove_filter( 'body_class', 'wpbb_clouthes_v147_body_classes', PHP_INT_MAX );
remove_filter( 'render_block', 'wpbb_clouthes_v147_render_catalogue', PHP_INT_MAX );
remove_filter( 'wp_lazy_loading_enabled', 'wpbb_clouthes_v147_lazy_loading', PHP_INT_MAX );
remove_filter( 'wp_get_attachment_image_attributes', 'wpbb_clouthes_v147_image_attributes', PHP_INT_MAX );
remove_action( 'wp_head', 'wpbb_clouthes_v147_preload_hero', 3 );
remove_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v147_enqueue', PHP_INT_MAX );

/* Restore the server-side single-finder owner that v147 had disabled. */
if ( function_exists( 'wpbb_clouthes_v140_render_hero_finder' ) ) {
    add_filter( 'render_block', 'wpbb_clouthes_v140_render_hero_finder', PHP_INT_MAX, 2 );
}
if ( function_exists( 'wpbb_clouthes_v140_dedupe_home_content' ) ) {
    add_filter( 'the_content', 'wpbb_clouthes_v140_dedupe_home_content', PHP_INT_MAX );
}

if ( ! function_exists( 'wpbb_clouthes_v148_body_classes' ) ) {
    function wpbb_clouthes_v148_body_classes( $classes ) {
        $classes[] = 'wpbb-v148';
        if ( is_front_page() ) $classes[] = 'wpbb-v148-home';
        return array_values( array_unique( $classes ) );
    }
}
add_filter( 'body_class', 'wpbb_clouthes_v148_body_classes', PHP_INT_MAX );

if ( ! function_exists( 'wpbb_clouthes_v148_product_image' ) ) {
    function wpbb_clouthes_v148_product_image( $product, $title ) {
        $slug = is_object( $product ) && method_exists( $product, 'get_slug' ) ? sanitize_title( $product->get_slug() ) : '';
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
        if ( isset( $map[ $slug ] ) ) {
            $file = $map[ $slug ];
            $path = get_stylesheet_directory() . '/assets/img/products/' . $file;
            if ( is_readable( $path ) ) {
                return '<img class="wpbb-v148-product-card__image" src="' . esc_url( trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/products/' . rawurlencode( $file ) ) . '" alt="' . esc_attr( $title ) . '" loading="eager" decoding="async">';
            }
        }
        $image_id = is_object( $product ) && method_exists( $product, 'get_image_id' ) ? absint( $product->get_image_id() ) : 0;
        if ( $image_id ) {
            $image = wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array(
                'class'    => 'wpbb-v148-product-card__image',
                'loading'  => 'eager',
                'decoding' => 'async',
                'alt'      => $title,
            ) );
            if ( $image ) return $image;
        }
        return '';
    }
}

/* The looping partner Swiper is replaced by six real non-looping tiles. */
if ( ! function_exists( 'wpbb_clouthes_v148_render_partners' ) ) {
    function wpbb_clouthes_v148_render_partners( $block_content, $block ) {
        if ( is_admin() || ! is_front_page() || empty( $block['blockName'] ) || 'wpbb/swiper' !== $block['blockName'] ) return $block_content;
        $attrs = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();
        if ( 'logos' !== ( $attrs['demoStyle'] ?? '' ) ) return $block_content;
        $slides = isset( $attrs['slides'] ) && is_array( $attrs['slides'] ) ? $attrs['slides'] : array();
        if ( ! $slides ) return $block_content;
        $seen = array();
        $html = '<div class="wpbb-v148-partners-grid" aria-label="Trusted partners">';
        foreach ( $slides as $slide ) {
            $title = trim( (string) ( $slide['title'] ?? '' ) );
            $image = trim( (string) ( $slide['image'] ?? '' ) );
            $key = strtolower( $title . '|' . $image );
            if ( '' === $title || isset( $seen[ $key ] ) ) continue;
            $seen[ $key ] = true;
            $html .= '<div class="wpbb-v148-partner">';
            if ( $image ) $html .= '<img src="' . esc_url( $image ) . '" alt="" loading="eager" decoding="async">';
            $html .= '<span>' . esc_html( $title ) . '</span></div>';
        }
        $html .= '</div>';
        return $html;
    }
}
add_filter( 'render_block', 'wpbb_clouthes_v148_render_partners', PHP_INT_MAX, 2 );

/* Replace the historically malformed values row with a deterministic editable-copy rendering. */
if ( ! function_exists( 'wpbb_clouthes_v148_render_values' ) ) {
    function wpbb_clouthes_v148_render_values( $block_content, $block ) {
        if ( is_admin() || ! is_front_page() || empty( $block['blockName'] ) || 'wpbb/row' !== $block['blockName'] ) return $block_content;
        $classes = (string) ( $block['attrs']['customClasses'] ?? '' );
        if ( false === strpos( $classes, 'clothes-values' ) ) return $block_content;

        $defaults = array(
            array( '01', 'Natural materials', 'Use product attributes to explain fabric, sourcing and care.' ),
            array( '02', 'Considered fit', 'Variation swatches and clear size information without theme-specific Woo logic.' ),
            array( '03', 'Easy returns', 'Reassuring fulfilment and transparent aftercare messaging.' ),
        );
        $titles = array();
        if ( preg_match_all( '~<h3\b[^>]*>(.*?)</h3>~is', (string) $block_content, $matches ) ) {
            foreach ( $matches[1] as $value ) $titles[] = trim( wp_strip_all_tags( html_entity_decode( $value, ENT_QUOTES, 'UTF-8' ) ) );
        }
        $paragraphs = array();
        if ( preg_match_all( '~<p\b[^>]*>(.*?)</p>~is', (string) $block_content, $matches ) ) {
            foreach ( $matches[1] as $value ) {
                $text = trim( wp_strip_all_tags( html_entity_decode( $value, ENT_QUOTES, 'UTF-8' ) ) );
                if ( '' !== $text && ! preg_match( '/^0?[1-9]$/', $text ) && ! preg_match( '/^0[1-9]$/', $text ) ) $paragraphs[] = $text;
            }
        }
        $html = '<section class="wp-theme-section-shell clothes-values wpbb-v148-values-section"><div class="wpbb-v148-values-grid">';
        for ( $i = 0; $i < 3; $i++ ) {
            $number = $defaults[ $i ][0];
            $title  = ! empty( $titles[ $i ] ) ? $titles[ $i ] : $defaults[ $i ][1];
            $text   = ! empty( $paragraphs[ $i ] ) ? $paragraphs[ $i ] : $defaults[ $i ][2];
            $html .= '<article class="wp-theme-sector-card wpbb-v148-value-card"><p class="wp-theme-card-number">' . esc_html( $number ) . '</p><h3>' . esc_html( $title ) . '</h3><p>' . esc_html( $text ) . '</p></article>';
        }
        $html .= '</div></section>';
        return $html;
    }
}
add_filter( 'render_block', 'wpbb_clouthes_v148_render_values', PHP_INT_MAX, 2 );

/* Render New Arrivals with bundled demo images first, avoiding broken imported attachment URLs. */
if ( ! function_exists( 'wpbb_clouthes_v148_render_catalogue' ) ) {
    function wpbb_clouthes_v148_render_catalogue( $block_content, $block ) {
        if ( is_admin() || ! is_front_page() || empty( $block['blockName'] ) || 'wpbb/catalogue' !== $block['blockName'] ) return $block_content;
        $class_name = (string) ( $block['attrs']['className'] ?? '' );
        if ( false === strpos( $class_name, 'wp-theme-home-product-catalogue' ) || ! function_exists( 'wc_get_products' ) ) return $block_content;
        $limit = min( 12, max( 1, absint( $block['attrs']['postsToShow'] ?? 8 ) ) );
        $products = wc_get_products( array( 'status' => 'publish', 'limit' => $limit, 'orderby' => 'menu_order', 'order' => 'ASC', 'return' => 'objects' ) );
        if ( ! $products ) return $block_content;
        $html = '<div class="wp-theme-home-product-catalogue wpbb-v148-product-grid">';
        foreach ( $products as $product ) {
            if ( ! is_object( $product ) || ! method_exists( $product, 'get_id' ) ) continue;
            $id    = (int) $product->get_id();
            $title = method_exists( $product, 'get_name' ) ? $product->get_name() : get_the_title( $id );
            $url   = method_exists( $product, 'get_permalink' ) ? $product->get_permalink() : get_permalink( $id );
            $short = method_exists( $product, 'get_short_description' ) ? $product->get_short_description() : '';
            if ( '' === trim( wp_strip_all_tags( (string) $short ) ) ) $short = get_post_field( 'post_excerpt', $id );
            $short = wp_trim_words( wp_strip_all_tags( (string) $short ), 15, '…' );
            $image = wpbb_clouthes_v148_product_image( $product, $title );
            $html .= '<article class="wpbb-v148-product-card"><a class="wpbb-v148-product-card__media" href="' . esc_url( $url ) . '">' . $image . '</a><div class="wpbb-v148-product-card__body"><h3><a href="' . esc_url( $url ) . '">' . esc_html( $title ) . '</a></h3>';
            if ( $short ) $html .= '<p>' . esc_html( $short ) . '</p>';
            $html .= '<a class="wpbb-v148-product-card__link" href="' . esc_url( $url ) . '">' . esc_html__( 'View product', 'wp-bbtheme-child-woo-clouthes' ) . '</a></div></article>';
        }
        $html .= '</div>';
        return $html;
    }
}
add_filter( 'render_block', 'wpbb_clouthes_v148_render_catalogue', PHP_INT_MAX, 2 );

/* Homepage media should be ready immediately; this is a demo/reference theme. */
if ( ! function_exists( 'wpbb_clouthes_v148_lazy_loading' ) ) {
    function wpbb_clouthes_v148_lazy_loading( $default, $tag_name, $context ) {
        return is_front_page() && 'img' === $tag_name ? false : $default;
    }
}
add_filter( 'wp_lazy_loading_enabled', 'wpbb_clouthes_v148_lazy_loading', PHP_INT_MAX, 3 );

if ( ! function_exists( 'wpbb_clouthes_v148_enqueue' ) ) {
    function wpbb_clouthes_v148_enqueue() {
        if ( ! is_front_page() ) return;
        foreach ( array(
            'wpbb-suite-v140-clouthes-clean',
            'wpbb-suite-v147-clouthes-events-owner',
            'wpbb-suite-v146-clouthes-home-rebuild',
            'wpbb-suite-v145-clouthes-live-structure',
            'wpbb-suite-v144-clouthes-events-db-hardfix',
            'wpbb-suite-v143-clouthes-screenshot-hardfix',
            'wpbb-suite-v142-clouthes-visual-parity',
            'wpbb-suite-v141-clouthes-parity',
        ) as $handle ) {
            wp_dequeue_style( $handle ); wp_deregister_style( $handle );
            wp_dequeue_script( $handle ); wp_deregister_script( $handle );
        }
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        $css = $dir . '/assets/suite-v148-clouthes-events-structural.css';
        $js  = $dir . '/assets/suite-v148-clouthes-events-structural.js';
        if ( is_readable( $css ) ) wp_enqueue_style( 'wpbb-suite-v148-clouthes-events-structural', $uri . '/assets/suite-v148-clouthes-events-structural.css', array( 'wpbb-suite-v118' ), (string) filemtime( $css ) );
        if ( is_readable( $js ) ) wp_enqueue_script( 'wpbb-suite-v148-clouthes-events-structural', $uri . '/assets/suite-v148-clouthes-events-structural.js', array( 'wpbb-suite-v118' ), (string) filemtime( $js ), true );
    }
}
add_action( 'wp_enqueue_scripts', 'wpbb_clouthes_v148_enqueue', PHP_INT_MAX );

<?php
/** Native WooCommerce catalogue/taxonomy shell - 3.8.11.40. */
defined( 'ABSPATH' ) || exit;
get_header();
$is_tax = function_exists( 'is_product_taxonomy' ) && is_product_taxonomy();
$title = $is_tax ? single_term_title( '', false ) : __( 'Shop the collection.', 'wp-bbtheme-child-woo-clouthes' );
$eyebrow = $is_tax ? __( 'Collection', 'wp-bbtheme-child-woo-clouthes' ) : __( 'Shop', 'wp-bbtheme-child-woo-clouthes' );
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
<?php get_footer(); ?>

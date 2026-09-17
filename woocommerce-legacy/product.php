<?php
/** Native WooCommerce single-product shell - 3.8.11.40. */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--product">
  <div class="container wp-theme-woo-legacy__body wp-theme-woo-legacy__body--product">
    <div class="woocommerce wpbb-woo-surface wpbb-woo-product-surface">
      <?php if ( function_exists( 'woocommerce_breadcrumb' ) ) woocommerce_breadcrumb(); ?>
      <?php
      while ( have_posts() ) :
          the_post();
          wc_get_template_part( 'content', 'single-product' );
      endwhile;
      ?>
    </div>
  </div>
</main>
<?php
if ( function_exists( 'wc_reset_loop' ) ) wc_reset_loop();
get_footer();

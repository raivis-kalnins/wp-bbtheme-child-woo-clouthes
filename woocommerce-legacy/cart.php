<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--cart">
  <section class="wp-theme-woo-legacy__hero">
    <div class="container">
      <p class="wp-theme-sector-eyebrow"><?php esc_html_e( 'Basket', 'wp-bbtheme-child-woo-clouthes' ); ?></p>
      <h1><?php esc_html_e( 'Review your basket.', 'wp-bbtheme-child-woo-clouthes' ); ?></h1>
      <p><?php esc_html_e( 'Check quantities, delivery details and totals before moving to checkout.', 'wp-bbtheme-child-woo-clouthes' ); ?></p>
    </div>
  </section>
  <div class="container wp-theme-woo-legacy__body">
    <div class="woocommerce wpbb-woo-surface wpbb-woo-cart-surface">
      <?php echo do_shortcode( '[woocommerce_cart]' ); ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>

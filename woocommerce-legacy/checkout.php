<?php
defined( 'ABSPATH' ) || exit;
get_header();
$is_received = function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' );
?>
<main id="wp-theme-main" class="wp-theme-main wp-theme-woo-legacy wp-theme-woo-legacy--checkout<?php echo $is_received ? ' wp-theme-woo-legacy--order-received' : ''; ?>">
  <section class="wp-theme-woo-legacy__hero">
    <div class="container">
      <p class="wp-theme-sector-eyebrow"><?php echo esc_html( $is_received ? __( 'Order', 'wp-bbtheme-child-woo-clouthes' ) : __( 'Checkout', 'wp-bbtheme-child-woo-clouthes' ) ); ?></p>
      <h1><?php echo esc_html( $is_received ? __( 'Order details.', 'wp-bbtheme-child-woo-clouthes' ) : __( 'Complete your order.', 'wp-bbtheme-child-woo-clouthes' ) ); ?></h1>
      <?php if ( ! $is_received ) : ?><p><?php esc_html_e( 'A focused checkout with delivery, billing and payment information in one clear flow.', 'wp-bbtheme-child-woo-clouthes' ); ?></p><?php endif; ?>
    </div>
  </section>
  <div class="container wp-theme-woo-legacy__body">
    <div class="woocommerce wpbb-woo-surface wpbb-woo-checkout-surface">
      <?php echo do_shortcode( '[woocommerce_checkout]' ); ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>

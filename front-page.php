<?php
/**
 * Deterministic Clothes homepage - 3.8.11.52.
 * Content mirrors the supplied 2026-09-17 database, while layout follows the
 * stable Woo Events owner instead of the historical BBuilder repair cascade.
 */
defined( 'ABSPATH' ) || exit;
get_header();

$theme_uri = trailingslashit( get_stylesheet_directory_uri() );
$shop_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$blog_url  = get_post_type_archive_link( 'post' );
if ( ! $blog_url ) $blog_url = home_url( '/blog/' );

$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
$cart_url    = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
$cart_count  = ( function_exists( 'WC' ) && WC() && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;

$cat_url = static function( $name, $fallback ) {
    if ( function_exists( 'wpbb_clouthes_product_category_url' ) ) return wpbb_clouthes_product_category_url( $name );
    return home_url( $fallback );
};

$hero_slides = array(
    array(
        'eyebrow' => 'The new collection',
        'title'   => 'Everyday pieces, made to stay in your wardrobe.',
        'text'    => 'A considered edit of versatile clothing, useful accessories and dependable materials.',
        'image'   => $theme_uri . 'assets/img/hero-v118/slide-1.jpg',
        'button'  => 'Shop the collection',
        'url'     => $shop_url,
        'position'=> 'center center',
    ),
    array(
        'eyebrow' => 'Built to edit',
        'title'   => 'A quieter wardrobe, built around repeat wear.',
        'text'    => 'Editorial storytelling, confident product imagery and restrained commerce UI keep the focus on material, fit and the collection.',
        'image'   => $theme_uri . 'assets/img/hero-v118/slide-2.jpg',
        'button'  => 'Our approach',
        'url'     => '#services',
        'position'=> 'center 38%',
    ),
);

$partners = array(
    'Northstar', 'Atlas', 'Horizon', 'Summit', 'Vertex', 'Harbour',
);

$values = array(
    array( '01', 'Natural materials', 'Use product attributes to explain fabric, sourcing and care.' ),
    array( '02', 'Considered fit', 'Variation swatches and clear size information without theme-specific Woo logic.' ),
    array( '03', 'Easy returns', 'Reassuring fulfilment and transparent aftercare messaging.' ),
);

$categories = array(
    array( 'Women', 'relaxed-cotton-shirt.jpg', $cat_url( 'Women', '/product-category/women/' ) ),
    array( 'Men', 'utility-overshirt.jpg', $cat_url( 'Men', '/product-category/men/' ) ),
    array( 'Kids', 'essential-t-shirt.jpg', $cat_url( 'Kids', '/product-category/kids/' ) ),
    array( 'Accessories', 'canvas-weekend-bag.jpg', $cat_url( 'Accessories', '/product-category/accessories/' ) ),
);

$services = array(
    array( 'Better materials', 'Fabrics selected for comfort, character and useful wear.' ),
    array( 'Considered fits', 'Clear sizing and shapes designed for repeat use.' ),
    array( 'Easy returns', 'Simple fulfilment and support before and after purchase.' ),
);

$wardrobe = array(
    array( 'Women', 'A focused route into this part of the offer.' ),
    array( 'Men', 'A focused route into this part of the offer.' ),
    array( 'Kids', 'A focused route into this part of the offer.' ),
    array( 'Accessories', 'A focused route into this part of the offer.' ),
);

$stats = array(
    array( '16', 'Curated pieces' ),
    array( '3', 'Women, men and kids edits' ),
    array( '3', 'Useful size and colour options' ),
    array( '1', 'Editorial storefront system' ),
);

$cases = array(
    array( '01', 'Workday capsule', 'A restrained edit built around layering, fabric and repeat wear.', '12', 'core pieces' ),
    array( '02', 'Weekend travel edit', 'A compact set of pieces designed to pack and combine easily.', '4', 'looks from one bag' ),
    array( '03', 'Material care programme', 'Clear care content designed to extend the useful life of garments.', '3', 'care guides' ),
);

$gallery = array(
    array( 'Relaxed cotton shirt', 'relaxed-cotton-shirt.jpg' ),
    array( 'Utility overshirt', 'utility-overshirt.jpg' ),
    array( 'Merino knit', 'merino-crew-knit.jpg' ),
    array( 'Canvas weekend bag', 'canvas-weekend-bag.jpg' ),
);

$process = array(
    array( '01', 'Discover', 'Move from collection stories into focused category edits.' ),
    array( '02', 'Choose', 'Use simple filters and tactile variation options without visual clutter.' ),
    array( '03', 'Keep', 'Support the purchase with clear sizing, delivery, returns and care information.' ),
);
?>
<!-- v3.8.11.52: parent header restored so the real WordPress mega-menu remains editable and functional. -->

<main id="wp-theme-main" class="wp-theme-main clothes149-home">
  <section class="clothes149-hero" aria-label="Featured collection" aria-roledescription="carousel" tabindex="0">
    <div class="clothes149-hero__slides">
      <?php foreach ( $hero_slides as $index => $slide ) : ?>
        <article class="clothes149-hero__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
          <div class="clothes149-hero__media" aria-hidden="true">
            <img src="<?php echo esc_url( $slide['image'] ); ?>" alt="" loading="eager" fetchpriority="<?php echo 0 === $index ? 'high' : 'auto'; ?>" style="object-position:<?php echo esc_attr( $slide['position'] ); ?>">
          </div>
          <div class="clothes149-rail clothes149-hero__rail">
            <div class="clothes149-hero__copy">
              <p class="clothes149-eyebrow"><?php echo esc_html( $slide['eyebrow'] ); ?></p>
              <?php if ( 0 === $index ) : ?><h1><?php echo esc_html( $slide['title'] ); ?></h1><?php else : ?><h2 class="clothes149-hero__title"><?php echo esc_html( $slide['title'] ); ?></h2><?php endif; ?>
              <p class="clothes149-hero__text"><?php echo esc_html( $slide['text'] ); ?></p>
              <a class="clothes149-button clothes149-button--primary" href="<?php echo esc_url( $slide['url'] ); ?>"><?php echo esc_html( $slide['button'] ); ?></a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="clothes149-rail clothes149-hero__tools">
      <form class="clothes149-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input type="hidden" name="post_type" value="product">
        <label class="screen-reader-text" for="clothes149-search-products"><?php esc_html_e( 'Search products', 'wp-bbtheme-child-woo-clouthes' ); ?></label>
        <input id="clothes149-search-products" type="search" name="s" placeholder="Search products" autocomplete="off">
        <label class="screen-reader-text" for="clothes149-product-category"><?php esc_html_e( 'Product category', 'wp-bbtheme-child-woo-clouthes' ); ?></label>
        <select id="clothes149-product-category" name="product_cat">
          <option value="">Category</option>
          <option value="women">Women</option>
          <option value="men">Men</option>
          <option value="kids">Kids</option>
          <option value="accessories">Accessories</option>
        </select>
        <button type="submit">Search <span aria-hidden="true">→</span></button>
      </form>
      <div class="clothes149-popular" aria-label="Popular collections">
        <span>Popular:</span>
        <a href="<?php echo esc_url( $shop_url ); ?>">New in</a>
        <a href="<?php echo esc_url( $cat_url( 'Women', '/product-category/women/' ) ); ?>">Women</a>
        <a href="<?php echo esc_url( $cat_url( 'Men', '/product-category/men/' ) ); ?>">Men</a>
        <a href="<?php echo esc_url( $cat_url( 'Accessories', '/product-category/accessories/' ) ); ?>">Accessories</a>
      </div>
    </div>

    <div class="clothes149-hero__pager" role="group" aria-label="Hero slides">
      <?php foreach ( $hero_slides as $index => $slide ) : ?>
        <button type="button" data-clothes149-slide="<?php echo esc_attr( $index ); ?>" class="<?php echo 0 === $index ? 'is-active' : ''; ?>" aria-label="<?php echo esc_attr( sprintf( 'Show slide %d', $index + 1 ) ); ?>"<?php echo 0 === $index ? ' aria-current="true"' : ''; ?>><span class="screen-reader-text"><?php echo esc_html( sprintf( 'Slide %d', $index + 1 ) ); ?></span></button>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="clothes149-partners" aria-label="Trusted partners">
    <div class="clothes149-rail">
      <p class="clothes149-partners__label">Trusted by teams, clients and delivery partners</p>
      <div class="clothes149-partners__grid">
        <?php foreach ( $partners as $partner_index => $partner ) : ?>
          <div class="clothes149-partner">
            <span class="clothes151-partner-mark clothes151-partner-mark--<?php echo esc_attr( (string) ( $partner_index + 1 ) ); ?>" aria-hidden="true"><?php echo esc_html( strtoupper( substr( $partner, 0, 1 ) ) ); ?></span>
            <span><?php echo esc_html( $partner ); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="clothes149-values">
    <div class="clothes149-rail clothes149-grid clothes149-grid--3">
      <?php foreach ( $values as $value ) : ?>
        <article class="clothes149-card clothes149-value">
          <span class="clothes149-card__number"><?php echo esc_html( $value[0] ); ?></span>
          <h3><?php echo esc_html( $value[1] ); ?></h3>
          <p><?php echo esc_html( $value[2] ); ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="clothes149-section clothes149-section--white">
    <div class="clothes149-rail">
      <header class="clothes149-heading">
        <p class="clothes149-eyebrow">Shop the edit</p>
        <h2>Built around pieces you can repeat.</h2>
      </header>
      <div class="clothes149-grid clothes149-grid--4 clothes149-categories">
        <?php foreach ( $categories as $category ) : ?>
          <article class="clothes149-category">
            <a class="clothes149-category__media" href="<?php echo esc_url( $category[2] ); ?>">
              <img src="<?php echo esc_url( $theme_uri . 'assets/img/products/' . $category[1] ); ?>" alt="<?php echo esc_attr( $category[0] ); ?>" loading="eager" decoding="async">
            </a>
            <h3><a href="<?php echo esc_url( $category[2] ); ?>"><?php echo esc_html( $category[0] ); ?></a></h3>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="services" class="clothes149-section clothes149-section--soft">
    <div class="clothes149-rail">
      <header class="clothes149-heading">
        <p class="clothes149-eyebrow">Atelier service</p>
        <h2>A quieter shopping experience built around fit, fabric and useful service.</h2>
      </header>
      <div class="clothes149-grid clothes149-grid--3">
        <?php foreach ( $services as $index => $service ) : ?>
          <article class="clothes149-card clothes149-service">
            <span class="clothes149-icon" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
            <h3><?php echo esc_html( $service[0] ); ?></h3>
            <p><?php echo esc_html( $service[1] ); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="clothes149-section clothes149-section--white">
    <div class="clothes149-rail clothes149-media-text">
      <div class="clothes149-media-text__media"><img src="<?php echo esc_url( $theme_uri . 'assets/img/products/utility-overshirt.jpg' ); ?>" alt="Olive utility overshirt" loading="eager" decoding="async"></div>
      <div class="clothes149-media-text__copy">
        <p class="clothes149-eyebrow">Material story</p>
        <h2>A quieter wardrobe, built around repeat wear.</h2>
        <p>Editorial storytelling, confident product imagery and restrained commerce UI keep the focus on material, fit and the collection.</p>
        <a class="clothes149-button clothes149-button--primary" href="#services">Our approach</a>
      </div>
    </div>
  </section>

  <section class="clothes149-section clothes149-section--soft">
    <div class="clothes149-rail">
      <header class="clothes149-heading">
        <p class="clothes149-eyebrow">Wardrobe edit</p>
        <h2>Considered pieces for work, weekends and everything between.</h2>
      </header>
      <div class="clothes149-grid clothes149-grid--4">
        <?php foreach ( $wardrobe as $index => $item ) : ?>
          <article class="clothes149-card clothes149-wardrobe-card">
            <span class="clothes149-icon" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
            <h3><?php echo esc_html( $item[0] ); ?></h3>
            <p><?php echo esc_html( $item[1] ); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="clothes149-stats">
    <div class="clothes149-rail clothes149-grid clothes149-grid--4">
      <?php foreach ( $stats as $stat ) : ?>
        <div class="clothes149-stat"><strong><?php echo esc_html( $stat[0] ); ?></strong><span><?php echo esc_html( $stat[1] ); ?></span></div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="clothes149-section clothes149-section--white">
    <div class="clothes149-rail clothes149-media-text clothes149-media-text--portrait">
      <div class="clothes149-media-text__media"><img src="<?php echo esc_url( $theme_uri . 'assets/img/products/utility-overshirt.jpg' ); ?>" alt="Olive utility overshirt" loading="eager" decoding="async"></div>
      <div class="clothes149-media-text__copy">
        <p class="clothes149-eyebrow">Made to repeat</p>
        <h2>A more considered wardrobe.</h2>
        <p>Tell the story behind materials, fit and construction, then connect directly to the relevant product collection.</p>
        <a class="clothes149-button clothes149-button--outline" href="<?php echo esc_url( $shop_url ); ?>">Shop the collection</a>
      </div>
    </div>
  </section>

  <section class="clothes149-newsletter">
    <div class="clothes149-rail clothes149-newsletter__grid">
      <div>
        <p class="clothes149-eyebrow">Notes from the studio</p>
        <h2>New pieces, material stories and useful wardrobe ideas.</h2>
      </div>
      <div class="clothes149-newsletter__form"><?php echo do_shortcode( '[newsletter_form]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
    </div>
  </section>

  <section class="clothes149-section clothes149-section--white">
    <div class="clothes149-rail">
      <header class="clothes149-heading">
        <p class="clothes149-eyebrow">Selected stories</p>
        <h2>Recent work and measurable outcomes.</h2>
      </header>
      <div class="clothes149-grid clothes149-grid--3">
        <?php foreach ( $cases as $case ) : ?>
          <article class="clothes149-card clothes149-case">
            <span class="clothes149-card__number"><?php echo esc_html( $case[0] ); ?></span>
            <h3><?php echo esc_html( $case[1] ); ?></h3>
            <p><?php echo esc_html( $case[2] ); ?></p>
            <div class="clothes149-case__metric"><strong><?php echo esc_html( $case[3] ); ?></strong><span><?php echo esc_html( $case[4] ); ?></span></div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="clothes149-section clothes149-section--soft">
    <div class="clothes149-rail">
      <header class="clothes149-heading">
        <p class="clothes149-eyebrow">Gallery</p>
        <h2>A closer look at the work, people and places behind the service.</h2>
      </header>
      <div class="clothes149-grid clothes149-grid--4 clothes149-gallery">
        <?php foreach ( $gallery as $item ) : ?>
          <figure class="clothes149-gallery__item">
            <img src="<?php echo esc_url( $theme_uri . 'assets/img/products/' . $item[1] ); ?>" alt="<?php echo esc_attr( $item[0] ); ?>" loading="eager" decoding="async">
            <figcaption><?php echo esc_html( $item[0] ); ?></figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="clothes149-section clothes149-section--soft clothes149-products-section">
    <div class="clothes149-rail">
      <header class="clothes149-heading">
        <p class="clothes149-eyebrow">New arrivals</p>
        <h2>A focused collection with useful filters and fewer distractions.</h2>
      </header>
      <div class="clothes149-grid clothes149-grid--4 clothes149-products">
        <?php
        $products = function_exists( 'wc_get_products' ) ? wc_get_products( array(
            'status' => 'publish', 'limit' => 8, 'orderby' => 'menu_order', 'order' => 'ASC', 'return' => 'objects'
        ) ) : array();
        foreach ( $products as $product ) :
            if ( ! is_object( $product ) ) continue;
            $title = method_exists( $product, 'get_name' ) ? $product->get_name() : '';
            $url   = method_exists( $product, 'get_permalink' ) ? $product->get_permalink() : $shop_url;
            $desc  = method_exists( $product, 'get_short_description' ) ? wp_trim_words( wp_strip_all_tags( $product->get_short_description() ), 15, '…' ) : '';
            $image = function_exists( 'wpbb_clouthes_v151_product_image_url' ) ? wpbb_clouthes_v151_product_image_url( $product ) : ( function_exists( 'wpbb_clouthes_v149_product_image_url' ) ? wpbb_clouthes_v149_product_image_url( $product ) : '' );
        ?>
          <article class="clothes149-product">
            <a class="clothes149-product__media" href="<?php echo esc_url( $url ); ?>"><img src="<?php echo esc_url( $image ); ?>" data-clothes151-fallback="<?php echo esc_url( $theme_uri . 'assets/img/products/utility-overshirt.jpg' ); ?>" alt="<?php echo esc_attr( $title ); ?>" width="376" height="504" loading="eager" decoding="async"></a>
            <div class="clothes149-product__body">
              <h3><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a></h3>
              <?php if ( $desc ) : ?><p><?php echo esc_html( $desc ); ?></p><?php endif; ?>
              <a class="clothes149-product__link" href="<?php echo esc_url( $url ); ?>">View product</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="clothes149-section clothes149-section--white">
    <div class="clothes149-rail">
      <header class="clothes149-heading">
        <p class="clothes149-eyebrow">Shopping, simplified</p>
        <h2>Discover a piece, choose the right option and keep the checkout calm.</h2>
      </header>
      <div class="clothes149-grid clothes149-grid--3 clothes149-process">
        <?php foreach ( $process as $item ) : ?>
          <article class="clothes149-card clothes149-process__card">
            <span class="clothes149-process__badge"><?php echo esc_html( $item[0] ); ?></span>
            <h3><?php echo esc_html( $item[1] ); ?></h3>
            <p><?php echo esc_html( $item[2] ); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="clothes149-section clothes149-section--white clothes149-faq">
    <div class="clothes149-rail">
      <header class="clothes149-heading">
        <p class="clothes149-eyebrow">FAQ</p>
        <h2>Fit, delivery and returns explained simply.</h2>
      </header>
      <div class="clothes149-faq__list">
        <details><summary>Can I change every section?</summary><p>Yes. The page is made from editable WordPress and WP BBuilder blocks.</p></details>
        <details><summary>Can the website grow with the organisation?</summary><p>Yes. Keep the editable structure and add only the integrations the active project actually needs.</p></details>
        <details><summary>Will the imported menus remain editable?</summary><p>Yes. Header, utility, footer and mega-menu content are normal WordPress objects managed in wp-admin.</p></details>
      </div>
    </div>
  </section>

  <section class="clothes149-section clothes149-section--white clothes149-blog-section">
    <div class="clothes149-rail">
      <header class="clothes149-heading clothes149-heading--split">
        <div><p class="clothes149-eyebrow">Latest thinking</p><h2>Latest guides, news and practical advice.</h2></div>
        <a class="clothes149-button clothes149-button--primary clothes149-button--small" href="<?php echo esc_url( $blog_url ); ?>">View all articles</a>
      </header>
      <div class="clothes149-grid clothes149-grid--3 clothes149-blog-grid">
        <?php
        $posts = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'ignore_sticky_posts' => true ) );
        $fallbacks = array( 'relaxed-cotton-shirt.jpg', 'canvas-weekend-bag.jpg', 'merino-crew-knit.jpg' );
        $post_index = 0;
        while ( $posts->have_posts() ) : $posts->the_post();
            $thumb = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            if ( ! $thumb ) $thumb = $theme_uri . 'assets/img/products/' . $fallbacks[ $post_index % count( $fallbacks ) ];
        ?>
          <article class="clothes149-blog-card">
            <a class="clothes149-blog-card__media" href="<?php the_permalink(); ?>"><img src="<?php echo esc_url( $thumb ); ?>" alt="" loading="eager" decoding="async"></a>
            <div class="clothes149-blog-card__body">
              <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 18, '…' ) ); ?></p>
              <a class="clothes149-text-link" href="<?php the_permalink(); ?>">Read article</a>
            </div>
          </article>
        <?php $post_index++; endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>

  <section class="clothes151-footer-signup">
    <div class="clothes149-rail clothes151-footer-signup__grid">
      <div class="clothes151-footer-signup__copy">
        <p class="clothes149-eyebrow">Stay in the loop</p>
        <h2>Useful updates, no noise.</h2>
        <p>Occasional collection notes, care guides and practical wardrobe ideas.</p>
      </div>
      <div class="clothes151-footer-signup__form"><?php echo do_shortcode( '[newsletter_form]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
    </div>
  </section>
</main>
<?php get_footer(); ?>

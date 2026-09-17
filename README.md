## 3.8.11.52 — fixed Tech Shop source-of-truth finish

- Replaced the unstable parent homepage header with a deterministic Clothes header based on the fixed Tech Shop geometry.
- Hero uses validated high-resolution bundled slides and simple dot-only pagination.
- Partner strip no longer depends on fragile parent-theme logo URLs.
- New Arrivals maps all eight known products directly to validated bundled images by title/slug, with runtime fallback.
- Added one high-contrast footer signup band and retired the legacy footer newsletter band on the homepage.
- Normalised Clothes burgundy/linen/ink colours and footer/newsletter contrast.
- v149 remains the deterministic section/template base; v150 presentation/runtime is retired on the homepage.

## 3.8.11.50 Tech Shop parity finish
- Ports the fixed Tech Shop 3.8.11.52 frontend ownership model to Clothes: v118 base + deterministic v149 homepage + one final v150 owner.
- Retires generic v119-v148 homepage geometry/runtime assets that were still fighting the Clothes header and hero.
- Uses the measured header rail for header, hero tools and all homepage sections.
- Replaces numbered hero controls with accessible dot pagination and 8.5-second autoplay.
- Hardens desktop/mobile menu geometry, mega-menu placement, dark-section contrast and footer/newsletter readability.

## 3.8.11.49 — deterministic Events-style homepage rebuild

This release stops rendering the historically repaired BBuilder front-page DOM and replaces it with a deterministic child-owned `front-page.php` based on the supplied 17 September database content and the attached Woo Events 3.8.11.40 layout model.

- Uses one child-owned homepage renderer, eliminating duplicate hero finders, malformed partner/value rows and grid mis-detection at source rather than hiding them with late CSS/JS.
- Keeps the parent header/footer and the stable v118 menu foundation, but applies one final Clothes header/menu owner with the Events-style desktop rail, predictable action controls and mobile drawer breakpoint.
- Renders a controlled two-slide fashion hero with one Woo product/category search, one pager and eager hero media.
- Renders six partner tiles, three values cards, four collection cards, services, wardrobe edits, statistics, two balanced media/text stories, cases, gallery, process, FAQ and blog with deterministic grids.
- Renders New Arrivals directly from WooCommerce and uses bundled Clothes product media as reliable fallbacks, avoiding the blank lazy-loaded catalogue placeholders seen on the deployed homepage.
- Uses one 1180px content rail / 1228px shell and explicit 4/3/2/1 responsive grids instead of generic BBuilder/Bootstrap column inference.
- Retires v147/v148 homepage owners and removes the v140 presentation body marker on the front page. v140 remains the native WooCommerce route/template owner for Shop, product, Cart, Checkout and My Account.
- Preserves dynamic WordPress menus, Woo products/categories, newsletter shortcode and latest posts. The demo homepage marketing composition is now owned by `front-page.php` to prevent legacy import/repair layers from changing its geometry.

## 3.8.11.48 Events structural homepage rebuild
- Uses the attached Woo Events 3.8.11.40 layout ownership model on the Clothes homepage: one 1180px content rail, 1228px shell and scoped real-row grids.
- Restores the v140 server-side hero finder owner so historical duplicate finders are stripped before output and exactly one finder is attached to the primary hero.
- Replaces the looping partner Swiper with six deterministic partner tiles and replaces the malformed values row with three deterministic horizontal cards.
- Renders New Arrivals server-side from WooCommerce while preferring bundled Clothes product images, eliminating broken imported/lazy media placeholders.
- Retires the v147 homepage owner entirely; v148 is the only homepage presentation/runtime layer while v140 continues to own native WooCommerce routes.
- Uses robust Events-style host detection only for the actual category/service/industry/stat/case/process/blog card rows.

## 3.8.11.37 WooCommerce alignment and template stability
- Restores the shop, product, basket, checkout and account layouts after v136 retired the v134 DOM repair script.
- Replaces JavaScript-added Woo layout classes with server-side route classes and CSS against native WooCommerce markup, so AJAX cart/checkout refreshes keep their alignment.
- Restores the native single-product hook stack so extensions attached to WooCommerce summary/gallery/lower-content hooks render normally.
- Adds explicit `.woocommerce` wrappers around classic cart, checkout and account output and makes My Account guest/authenticated layouts deterministic.
- Makes the block-template shop fallback taxonomy-aware and removes the redundant `woocommerce/single-product.php` core-template override to avoid stale-template warnings.

## 3.8.11.16 reset-safe final release fixes
- Demo reset/import now re-runs the canonical managed BBuilder page rebuild and all v116 repairs automatically.
- The old migration that removed legitimate responsive BBuilder column widths is disabled; desktop multi-column layouts survive a clean demo reset.
- Desktop mega menus use the measured header bottom plus a hover bridge, matching the close Jobs positioning across all children.
- Home hero sliders use three distinct child-owned images, visible pagination, 8.5-second autoplay and pause-on-hover with sharp natural-scale rendering.
- Managed demo/editorial/catalogue/gallery/Woo media is restored from bundled files; Woo Clothes keeps the complete bundled product-image pool.
- Quote drawers use resilient trigger detection and sit flush to the right viewport edge on quote-enabled themes.
- Cookie-consent acceptance persists across reloads using a stable browser marker.
- Legal pages remain left-aligned on the normal grid; Latest Thinking media/card edges are normalized.
- Partner/brand serialization is normalized idempotently to prevent repeated wrappers and the `wpbb/column` validation warning.
- Theme Settings retain child-owned controls for disabling dark mode and keeping English-only Polylang content.

## 3.8.11.14 child-only settings, editor, legal, editorial and hero finish

- Latest Thinking card rows now use the same 1320px grid as their headings; the 1440px row override that shifted the first card left has been removed.
- Hero images use a direct child-owned native source, stronger left-edge gradient masking and no CSS blur/viewport stretching.
- Appearance > Theme Settings adds switches to disable dark mode and disable translations/keep English only. Enabling English-only moves non-English Polylang Pages and Posts to Trash and hides the language switcher.
- Privacy/Terms/Cookies content spans the normal site grid and is left-aligned instead of being forced into a centered narrow column.
- The known raw partner-heading serialization defect is repaired in imported pages and on future page saves, resolving the wpbb/column validation error.
- Child editor CSS is moved from enqueue_block_editor_assets to enqueue_block_assets for the WordPress editor iframe.

## 3.8.11.13 child-only hero and editorial grid finish

- Latest Thinking / related editorial cards use the full 1440px site grid; the legacy outer BBuilder row and list start padding can no longer create a first-card left inset.
- Homepage heroes use a native-resolution child asset without viewport-width stretching.
- A stronger white-to-transparent hero gradient crosses the photograph's left edge so the image seam is hidden.
- Existing and translated managed hero blocks are refreshed from the same child-owned asset after upgrade.

## 3.8.11.12 child-only visual/media fixes

- Latest Thinking card grid now inherits the section grid with no first-card left inset.
- Sharper child-owned hero source and late frontend override.
- Managed catalogue and editorial media are re-synchronised from bundled child assets.
- Media/text CTA buttons align to the copy edge.

## 3.8.11.11 media and WooCommerce finalisation

- Repairs missing demo media from bundled local assets, including cloned-site WooCommerce product images and Automotive vehicle finder thumbnails.
- Forces WooCommerce filters, ranges, compare controls and product actions to the child theme accent instead of the plugin blue fallback.
- Uses a two-column desktop Basket, Checkout and My Account shell with mobile stacking only below 821px.
- Uses the highest-resolution bundled hero source during managed demo rebuilds; Business uses the 1600x1000 office source.
- Requires parent WP BBTheme 3.8.10.23 for reliable My Account header URLs on cloned sites.

## 3.8.10.82 suite consistency

Requires WP BBuilder 5.6.9+ for palette inheritance and the shared hCaptcha verifier. This release keeps the sector's individual brand colour while using the same 1440px canvas, card/form rhythm, dark-mode baseline and footer/newsletter hierarchy as the rest of the 15-theme suite. The one-time cleanup is restricted to records explicitly marked as theme-managed demo content.

## 3.8.10.65

- Fixes the Theme Settings frontend-protection panel so its CSS is loaded in the admin head instead of appearing as visible text.
- Makes sector media repair load the WordPress image API safely before generating attachment metadata.
- Refines shared card, directory, gallery and responsive alignment.
- Adds block-theme Single Product, Cart and Checkout templates while preserving the existing classic-template fallback.

## 3.8.10.47

- More compact and consistent section spacing, cards and responsive layouts.
- Smaller in-frame gallery thumbnail pagination and improved light/dark contrast.
- Reliable child-owned WooCommerce product shells where the theme includes commerce.

# WP BBTheme Child Woo Clothes 3.8.10.65
Editorial fashion WooCommerce child theme. Reusable ecommerce filtering/minicart functionality remains in **WP Theme Woo Support**; this theme owns presentation and Woo page shells.

## v3.7 classic Woo customer journey

The child now routes the front end through version-safe PHP shells for:

- Shop and product taxonomy catalogue (retaining the shared AJAX filter/results layer).
- Single product through the installed WooCommerce legacy product engine.
- Basket/Cart through `WC_Shortcode_Cart`.
- Checkout and order-received through `WC_Shortcode_Checkout`.
- My Account and endpoints through `WC_Shortcode_My_Account`.

This deliberately uses the legacy templates shipped by the **installed WooCommerce version** instead of bundling stale copies. The child adds the editorial tables, forms, checkout/order, account navigation, responsive behaviour and product-gallery theme support.

The latest supplied localhost DB reports WooCommerce 11.0.1 with HPOS enabled.

Run `yarn prod` only when rebuilding source assets. **For an existing site upgrade, do not run Starter Setup Import / Refresh after installing v3.8.11.40**; replace the child theme and purge caches so stored homepage/editor content is preserved. Starter Setup import is intended only for a deliberate fresh demo rebuild.

### 3.8.10.46
- Dashboard-safe, resumable sector media repair; no synchronous bulk image regeneration on `admin_init`.
- Password protection controls live under **Theme Settings → General**.
- Thumbnail navigation is overlaid inside the main gallery image.
- Active-sector Blog and directory media are repaired after child-theme switching.

## SCSS structure (3.8.10.9)

Frontend styles are split into `tokens`, `tools`, `base`, `header`, `footer`, `components`, `swiper`, `motion`, `forms`, `blog`, `quality`, `sector`, `responsive` and `features`. Fluid typography uses the suite `fluid-font()` mixin and explicit viewport guards rather than `clamp()`. The generated production CSS intentionally contains no `!important` declarations.

### Build compatibility

The child build is dependency-free and works with Yarn 1.22.x as well as newer Yarn versions. No Corepack step is required. Use:

```sh
yarn prod
```

The command runs `node tools/build.mjs` and rebuilds the hashed CSS/JS manifest directly.


### 3.8.10.45
- Consistent 80/64/52px section rhythm and explicit light/dark card contrast.
- Active-theme sector media repair for demo pages, blogs, directories and galleries.
- Top-aligned About imagery plus thumbnail and modal galleries on supported directory cards and single pages.

### 3.8.10.44
- Frontend password protection is enabled by default with password `wp@demo`.
- Administrators can disable it or set a new password in **Settings → Theme Settings** at `/wp-admin/options-general.php?page=wp-theme-settings`.
- Successful visitors receive a signed access cookie valid for 24 hours by default.
- Purge full-page/server/CDN caches after changing the protection setting.

### 3.8.10.42
- Replaced demo feature icons with Tabler Icons v3.46.0 outline SVGs, sized for normal UI use and coloured from the child-theme brand token.
- Single-column imported demo rows are repaired to 12 columns at every breakpoint.
- Dark-mode demo cards use explicit dark surfaces/readable text.
- Optional frontend-only demo password protection is available in Settings → Theme Settings (default password `wp@demo`).
### 3.8.10.43
- Shared alignment and dark-mode contrast fixes across service, solution, process, directory, blog and commerce cards.
- Current child-theme media is reapplied after child-theme switches, including optimised AVIF/WebP files.
- Visible slider/grid images are loaded deterministically and duplicate single-item summary text is removed.

## 3.8.10.65 BBuilder demo system
This release expects WP BBuilder 5.6.4+ and standardises demo editing around BBuilder Row/Column, Div, Icon Card, Swiper and selected native WordPress content blocks. Legacy Group/Columns demo markup is migrated automatically.


## 3.8.11.08
WooCommerce shop, basket, checkout and account layouts were normalised across the sector suite; theme preview artwork was refreshed and package documentation was reduced to this README.

## 3.8.11.38 WooCommerce hard ownership
- Replaced shortcode-driven shop/taxonomy rendering with the native WooCommerce main product loop, preserving category/tag queries, ordering and pagination.
- Replaced the custom single-product hook reconstruction with WooCommerce's native `content-single-product` template part.
- On Woo screens only, removes historical v98-v135/v137 suite assets so legacy `!important` rules and DOM mutators no longer compete with the current layout.
- Added one authoritative v138 Woo layer for catalogue, product, basket, checkout and account layouts with desktop/mobile breakpoints.

## 3.8.11.39 Homepage + Woo hard ownership
- Replaced the v97 every-hero finder injection with one first-hero-only product finder.
- Homepage finder now searches WooCommerce products and real product categories instead of a generic site/location query.
- Added a late mega-menu position owner and duplicate-finder runtime guard.
- On Woo screens, retires v98-v138 frontend finish assets except the stable v118 header/menu base, then loads one Clothes commerce layer.
- Keeps native WooCommerce archive/taxonomy and single-product rendering, with authoritative cart, checkout and account responsive alignment.
- Forces Woo Store Visibility live only on demo-woo-clouthes.dev4.uk; other domains retain their own Woo setting.
## 3.8.11.40 - Clean frontend ownership

- Retires the v98-v139 frontend repair asset cascade and keeps v118 as the stable geometry/media/menu foundation.
- Adds one Clothes-specific final stylesheet/runtime for homepage, header and WooCommerce.
- Removes the old v136 DOM rewriter from delivered frontend assets.
- Removes all historical hero finder markup server-side and injects exactly one Woo-aware finder into the first homepage hero.
- Restores a real secondary editorial hero instead of letting v136 hide later hero swipers.
- Keeps native WooCommerce archive/single-product loops and customer shortcodes inside stable child-owned shells.
- Rebinds the block archive fallback to the current WooCommerce main query.
- Uses a consistent burgundy/linen Clothes palette in the final owner.

- Retires the old automatic v62/v74/v75/v82/v83/v97 and v99-v120 admin repair callbacks, background consistency cron, and post-save rewrite filters that could mutate stored homepage/product content after a theme update or admin visit.
- Keeps WooCommerce's own account endpoint router authoritative instead of the superseded v112 aliases.
- Adds final Select2, variation, stock, checkout-review and lost-password responsive hardening.

## 3.8.11.41 — Clothes Automotive-parity frontend finish

- Restores the Automotive reference homepage behaviour without re-enabling the historical v98-v139 repair cascade.
- Adds a visible, accessible compact pager to every multi-slide homepage hero.
- Normalises the reported `#wpbb-row-35` row from its real child count and applies equal-height responsive cells.
- Applies the same grid owner to services, industries/collections, process, proof, stats, case studies, product catalogue, gallery and editorial card rows.
- Measures the live header/footer content edge and aligns homepage sections to that axis, matching the Automotive reference behaviour.
- Rebuilds desktop primary navigation and header action controls with the Clothes burgundy/linen palette and compact Automotive proportions.
- Normalises section rhythm, card media ratios, card heights, typography colours and homepage CTA buttons.
- WooCommerce remains owned by the v140 native Woo templates/layout layer; v141 does not revive legacy Woo overrides.


## 3.8.11.42 - screenshot-led visual parity hard-fix

This release is based on a fresh review of the deployed Clothes homepage and a full-page screenshot comparison against the Automotive demo.

- Removes the v141 JavaScript header-axis measurement. The previous runtime could read an already-narrow header container and then propagate that width to every homepage section.
- Restores a fixed 1320px Clothes alignment axis for header, hero copy, homepage sections and footer content.
- Correctly treats `.wp-theme-section-shell.container` rows as contained rows instead of full-bleed wrappers. This specifically repairs the collapsed `Natural materials / Considered fit / Easy returns` values row (`#wpbb-row-35` on the current import).
- Replaces tiny hero dots with a visible numbered `01 / 02 / ...` pager that works with the live Swiper instance and falls back to native pagination controls.
- Rebuilds card-led homepage grids from their real card ancestry so nested BBuilder columns no longer inherit broken Bootstrap widths.
- Restores readable desktop header action buttons and fixes the 992-1199px navigation breakpoint conflict that could hide the menu toggle while the navigation was still off-canvas.
- Removes surviving teal/navy Jobs-era presentation from the utility bar, footer newsletter and text hierarchy; the active Clothes palette is burgundy, linen, warm white and deep ink.
- Fixes the dark mid-page newsletter contrast so headings, copy, labels and consent text remain readable.
- Promotes catalogue/gallery/blog lazy image sources on the homepage so long-page captures and fast scrolls do not leave blank image areas.
- Keeps the v140 native WooCommerce archive/product/cart/checkout/account owner unchanged.

## 3.8.11.43 - live screenshot structural hard-fix

This release is based on the 17 September live full-page screenshot and targets the remaining structural regressions rather than adding another independent repair cascade.

- Consolidates the v141/v142 homepage presentation into one v143 stylesheet/runtime; v140 remains the native WooCommerce owner.
- Removes all desktop primary-menu `::before`/`::after` decoration collisions that produced stray dashes/markers below navigation labels; dropdown affordances are owned by the real submenu toggle.
- Removes the old v97 curved hero `::after` band and anchors a larger numbered hero pager directly over the slider surface.
- Fixes the exact `#wpbb-row-35` failure: `.clothes-values` can be an outer BBuilder container with one inner `.row`, so only the discovered inner card host is allowed to become a three-column grid.
- Targets the actual Clothes newsletter class `.clothes-editorial-band`, restoring white/light copy, inputs and CTA contrast on the dark band and removing the broken section transition.
- Promotes all homepage main-content images out of lazy placeholders, including `picture/source` data attributes, and forces product/gallery media visible with stable aspect ratios.
- Keeps the valid live product image URLs and Woo product data untouched; this is a rendering/media-loading fix, not a product import rewrite.
- Preserves the v140 Woo shop/category/product/cart/checkout/account native template architecture.

## 3.8.11.45 - live structure homepage repair

This release keeps the v140 native WooCommerce ownership layer and replaces the
homepage presentation/runtime layer with one live-structure owner based on the
current Clothes DOM, the attached database homepage markup, and the Events /
Automotive reference geometry.

- Restores a 1440px desktop rail for header, hero and homepage sections instead
  of inheriting narrow nested Bootstrap/BBuilder container widths.
- Makes desktop navigation explicitly horizontal at 1200px+, with consistent
  action buttons and mega-menu placement; keeps the real drawer below 1200px.
- Rebuilds the two-slide hero around the real media, one finder and one compact
  pager; removes legacy slider navigation tabs and forces hero media visible.
- Uses semantic row/card ownership for values, categories, services, industries,
  statistics, cases, process, blog and the eight-product New Arrivals catalogue.
- Restores the native WordPress Media & Text wardrobe block to a balanced
  two-column desktop composition so the jacket image no longer expands into a
  full-width tower.
- Disables lazy image loading on the homepage and promotes lazy/custom data
  sources at runtime so hero, gallery and catalogue media remain visible during
  fast scrolling and full-page captures.
- Leaves Shop, product, cart, checkout and My Account on the v140 native Woo
  templates and does not reactivate the retired v98-v139 repair stack.


## 3.8.11.47 — Events-parity homepage owner

This release replaces the failed v146 homepage presentation layer with a single owner modelled directly on the attached Woo Events 3.8.11.40 geometry. On the front page it removes the v140 Clothes presentation CSS/JS while preserving v140 WooCommerce routing/templates off the homepage. The homepage now uses one 1440px content rail, an Events-style header/hero/pager, one product finder, static partner-logo tiles, semantic grid marking without DOM reparenting, balanced media/text blocks, a full-width editorial newsletter band, and a server-rendered eight-product WooCommerce catalogue with real attachment images and theme-asset fallbacks.


## v3.8.11.52
- Restores the real WordPress parent header and editable mega-menu on the homepage.
- Makes footer signup eyebrow/title white and aligns checkbox consent text with proper spacing.

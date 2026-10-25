# Changelog

## 0.1.0

First release.

### Rector upgrade sets

- One set per WooCommerce minor release from 3.0 to 11.3 (`WooCommerceSetList`), plus cumulative level sets (`WooCommerceLevelSetList`). Deprecations from before 3.0 are in the 3.0 set.
- 527 rules, mined from the WooCommerce sources (3.0.9 through the 11.3 release branch) and limited to behaviour-preserving changes:
  - renamed functions, methods, static methods, classes and class constants;
  - methods moved to functions, and replacements with different arguments (`$product->get_price_including_tax()` → `wc_get_price_including_tax( $product, [...] )`);
  - reads of the legacy WooCommerce 3.0 CRUD properties (`$order->id` → `$order->get_id()`);
  - deprecated hooks whose replacement gets identical arguments;
  - removal of deprecated no-op calls.
- Method rules leave overrides in your subclasses alone, and calls are never dropped when an argument or receiver has side effects.
- 409 cases that need a human decision are listed as TODO comments in each set file.

### PHPStan feature compatibility checks

- New `extension.neon` reports code that stops working once a WooCommerce feature is the default, gated by `woocommerce.targetVersion`:
  - HPOS (8.2)
  - Cart and Checkout blocks (8.3)
  - Legacy REST API removal (9.0)
  - Brands in core (9.6)
  - product block editor removal (11.0)
- Code that already handles both paths is recognised and not reported.

### HPOS migration set

- Opt-in `WooCommerceFeatureSetList::HPOS`: `get_post_type( $id ) === 'shop_order'` → `OrderUtil::get_order_type()`, and screen-id checks extended to the HPOS order screen.

### Setup

- The WordPress and WooCommerce stubs are bundled and scanned automatically for both Rector and PHPStan.

# Rector Rules for WooCommerce

This package is a [Rector](https://github.com/rectorphp/rector) extension with upgrade rules for WooCommerce. It is based on [fsylum/rector-wordpress](https://github.com/fsylum/rector-wordpress) and works the same way.

## Requirements

- PHP 8.2 or higher
- Rector 2.0 or higher

## Install

Install `rector-woocommerce` as a dev dependency:

```bash
composer require morvy/rector-woocommerce --dev
```

## Use Sets

To add a set to your config, use the `Morvy\RectorWooCommerce\Set\WooCommerceSetList` class and pick one of its constants. For example, `WooCommerceSetList::WC_10_5` applies only the changes made in WooCommerce 10.5.

```php
use Morvy\RectorWooCommerce\Set\WooCommerceSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSets([
        WooCommerceSetList::WC_10_5,
    ]);
```

A level set includes every set from 3.0 up to the version you name. For example, `WooCommerceLevelSetList::UP_TO_WC_11_3` includes every rule from WooCommerce 3.0 to 11.3. Use a level set in most cases, because you only name the version once.

```php
use Morvy\RectorWooCommerce\Set\WooCommerceLevelSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSets([
        WooCommerceLevelSetList::UP_TO_WC_11_3,
    ]);
```

### Make WooCommerce classes known to Rector

Many rules match a method or property on its *declaring* class. For example, `get_cart_url()` lives on `WC_Legacy_Cart` and `$order->id` is handled on `WC_Order`. Rector can only see that `WC()->cart` is a `WC_Legacy_Cart`, or that `wc_get_order()` returns a `WC_Order`, if it can load the WooCommerce class tree. Without the class tree, only function renames and calls on the exact class still apply.

The simplest way is to load the stubs:

```bash
composer require --dev php-stubs/woocommerce-stubs
```

```php
return RectorConfig::configure()
    ->withBootstrapFiles([
        __DIR__ . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
        __DIR__ . '/vendor/php-stubs/woocommerce-stubs/woocommerce-stubs.php',
    ])
    ->withSets([WooCommerceLevelSetList::UP_TO_WC_11_3]);
```

## Supported WooCommerce Versions

There is one set for every minor release from 3.0 to 11.3:

- WooCommerce 3.x through 10.x (x.0 to x.9)
- WooCommerce 11.0 through 11.3

Deprecations made before 3.0 are included in the 3.0 set. Versions with no applicable change still have an empty set, so each version's set constant always exists.

## What gets changed

Rules are only added when the change preserves behaviour. They are generated from what WooCommerce itself does inside each deprecated function: when a deprecated function just calls its replacement with the same arguments, the call is renamed.

- Renamed functions, methods and static methods, for example `woocommerce_get_page_id()` → `wc_get_page_id()`, and `WC_Cache_Helper::incr_cache_prefix()` → `invalidate_cache_group()`.
- Methods moved to functions, for example `$cart->get_cart_url()` → `wc_get_cart_url()`.
- Replacements that take different arguments, when the deprecated function only dropped or added some. For example, `woocommerce_nav_menu_items($items, $args)` → `wc_nav_menu_items($items)` `$order->get_item_meta($id, $key)` → `get_metadata('order_item', $id, $key)` and `$product->get_price_including_tax(2)` → `wc_get_price_including_tax($product, ['qty' => 2, 'price' => ''])`.
- Reads of the legacy WooCommerce 3.0 CRUD properties become getters, for example `$order->id` → `$order->get_id()` and `$order->billing_email` → `$order->get_billing_email()`. This covers orders, products, coupons, customers and webhooks.
  - Only plain reads change. Writes, `isset()`, `unset()`, by-reference use and `$this->prop` inside subclasses are left alone.
  - Properties whose legacy value differs from the getter's value are skipped. Examples are product `id` on variations and the `'yes'`/`'no'` flags.
- Class aliases and facades are replaced by the real class, for example `Automattic\WooCommerce\Blocks\StoreApi\*` → `Automattic\WooCommerce\StoreApi\*`, and the `WC_Gateway_Paypal_Constants` constants.
- Calls to deprecated no-op functions and methods are removed.
- Callbacks on deprecated hooks move to the replacement hook, for example `add_filter('woocommerce_get_price', …)` → `add_filter('woocommerce_product_get_price', …)`. `add_action()`/`add_filter()` are renamed. `has_action()`/`has_filter()` are renamed only when they name a callback, because WooCommerce always hooks the new name itself. A `remove_action()`/`remove_filter()` statement removes the callback from both hooks, since another plugin may still add it on the old one.
  - A hook is only renamed when WooCommerce passes the old hook exactly the same arguments as the new one, at the same places, in every supported version. The new hook must also already exist in the set's release, and no WooCommerce template may still fire the old hook directly. Hooks whose arguments or firing points differ stay in the TODO list, such as `woocommerce_order_add_*` → `woocommerce_new_order_item`.
  - Code that fires a deprecated hook (`do_action`, `apply_filters`) and `remove_all_*()` calls are left alone, because changing them would also reach WooCommerce's own callbacks on the new hook.
  - WooCommerce used to run old-hook callbacks at priority -1000 of the new hook. After the rename they run at their own priority, so their order relative to other callbacks on the new hook can change.

Some changes are deliberately skipped:

- **Overrides.** Method rules only touch a call that resolves to WooCommerce's own deprecated method. If your subclass overrides that method (for example a gateway overriding `format_settings()`), its calls and its declaration are left alone.
- **Code that would be dropped.** A call is not removed or replaced if its receiver or a dropped argument has side effects, such as `make_cart()->remove_taxes()`.
- **Return values.** Some deprecated wrappers return nothing while their replacement returns a value (`woocommerce_mail()` → `wc_mail()`). These are only renamed where the result is unused, so `if (woocommerce_mail(...))` keeps its meaning.
- **Property checks and writes.** Legacy properties are left alone inside `isset()`, `empty()` and `??`, because those go through the legacy `__isset()`. They are also left alone in any write position, including `$o->p[] = …`, destructuring, references and `foreach`. If a function writes `$order->billing_email`, its reads of `$order->billing_email` stay unchanged too.
- **Pluggable functions.** Functions a theme can redefine, such as `woocommerce_hex_darker()`, are left for you to review.

Rules never point your code at a class in `Automattic\WooCommerce\Internal\*`, because WooCommerce does not offer those classes to extensions.

Action Scheduler function renames (`wc_schedule_single_action()` → `as_schedule_single_action()` and the others) are in the 3.5 set, the first release that bundles Action Scheduler 2.1.

Anything that needs a human decision is listed at the bottom of each set file under `TODO: these are not handled currently`. Examples are deprecated hooks, arguments, and replacements with a different signature.

## Feature compatibility checks (PHPStan)

Some WooCommerce changes are not deprecations. A feature that replaces an older mechanism becomes the default, and code written for the old one silently stops working. The package ships a PHPStan extension that reports this code with an explanation and a fix. Rector can't safely rewrite most of these cases, so they are reported rather than changed.

| Feature | Default since | What gets reported |
|---|---|---|
| HPOS (High-Performance Order Storage) | 8.2 (new stores) | `get_post_meta()`/`update_post_meta()`/`get_post()` and related functions on order ids, `WP_Query`/`get_posts()`/`wp_insert_post()` with `post_type => 'shop_order'`, `$wpdb` SQL on orders in `wp_posts`/`wp_postmeta`, meta boxes and screen checks for the `shop_order` screen, order-list and `save_post_shop_order` hooks, legacy order admin URLs, and a missing `custom_order_tables` compatibility declaration |
| Cart and Checkout blocks | 8.3 (new stores) | classic cart and checkout template hooks and `WC_Checkout` hooks (84 hooks, each checked as not fired by the Store API or blocks), payment gateways without a blocks integration, `[woocommerce_checkout]`/`[woocommerce_cart]` shortcode detection, and a missing `cart_checkout_blocks` declaration |
| Legacy REST API removed from core | 9.0 | `WC_API*` classes, `WC()->api` (except `get_endpoint_data()`), `wc-api/v1–v3` URLs, `get_woocommerce_api_url()`, and Legacy-API-only hooks |
| Brands in core | 9.6 | unguarded declarations of `WC_Brands*` classes and Brands functions (fatal redeclare) |
| Product block editor removed | 11.0 | the `product_block_editor` / `new_product_management_experience` feature flags |

Code that already handles both paths isn't reported:

- branches on `OrderUtil::custom_orders_table_usage_is_enabled()`, any `*hpos*` helper, flag variable or property, or the `woocommerce_custom_orders_table_enabled` option, including `elseif`, `match` and the early-`return` form;
- conditions that also check `wc-orders` or `wc_get_page_screen_id()`;
- legacy order-screen hooks and meta boxes registered next to their HPOS counterpart, and `shop_order` arrays that end up in `wc_get_orders()`;
- shortcode checks next to `has_block()`, and Legacy REST API code behind `class_exists( 'WC_API' )`;
- classic checkout hooks in a plugin that also integrates with the blocks through the Store API or `IntegrationInterface`.

With `phpstan/extension-installer` the extension is registered automatically (the package declares it in `extra.phpstan`). Otherwise include it:

```neon
includes:
    - vendor/morvy/rector-woocommerce/extension.neon

parameters:
    woocommerce:
        # The WooCommerce version you run or upgrade to; checks start once their feature is the default.
        targetVersion: '11.3'
```

Each check has its own identifier (`woocommerce.hpos.post`, `woocommerce.blocks.classicHook`, `woocommerce.legacyApi`, …), so you can ignore or baseline a whole group. Load the WordPress and WooCommerce stubs (`php-stubs/woocommerce-stubs`) so order types resolve.

### HPOS migration set (Rector, opt-in)

Two rewrites are exactly equivalent with legacy order storage and correct with HPOS. They live in a separate set, not in the level sets:

```php
use Morvy\RectorWooCommerce\Set\WooCommerceFeatureSetList;

return RectorConfig::configure()->withSets([WooCommerceFeatureSetList::HPOS]);
```

- `get_post_type($id) === 'shop_order'` → `OrderUtil::get_order_type($id) === 'shop_order'`
- `$screen->id === 'shop_order'` → `in_array($screen->id, ['shop_order', wc_get_page_screen_id('shop-order')], true)`. Under HPOS one screen id covers both the order list and the edit screen. `'edit-shop_order'` (the list) is left alone; pairing it would also match the legacy edit screen.

Both assume WooCommerce 6.9 or newer is loaded where the code runs.

## Updating for a new WooCommerce release

The rule data lives in `tools/deprecations.json`. Each entry is one rule, or one `todo` or `skip`, assigned to a set.

1. Run `php tools/extract.php <path-to-woocommerce> > raw.json` to list every deprecation call with its version and a guess of whether it delegates.
2. Classify the new records into `tools/deprecations.json`.
3. Run `composer generate`. It rewrites `config/sets`, the level sets, the set list classes and the set tests, including the up-to-latest level test, then runs Pint.
4. Run `composer phpunit`.

To add a new version, extend `LAST_MINOR` in `tools/generate.php`.

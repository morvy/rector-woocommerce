<?php

function hpos_checks(int $id, WP_Screen $screen, ?WP_Screen $maybe, object $other): void
{
    if (get_post_type($id) === 'shop_order') {
    }
    if ('shop_order_refund' !== get_post_type($id)) {
    }
    if ($screen->id === 'shop_order') {
    }
    if ($maybe?->id == 'edit-shop_order') {
    }
    if ($screen->id !== 'shop_order') {
    }

    // Unchanged.
    if (get_post_type() === 'shop_order') {
    }
    if (get_post_type($id) === 'product') {
    }
    if ($other->id === 'shop_order') {
    }
    if ($screen->id === 'product') {
    }
    if ($screen->id === 'edit-shop_order') {
    }
}

?>
-----
<?php

function hpos_checks(int $id, WP_Screen $screen, ?WP_Screen $maybe, object $other): void
{
    if (\Automattic\WooCommerce\Utilities\OrderUtil::get_order_type($id) === 'shop_order') {
    }
    if ('shop_order_refund' !== \Automattic\WooCommerce\Utilities\OrderUtil::get_order_type($id)) {
    }
    if (in_array($screen->id, ['shop_order', \wc_get_page_screen_id('shop-order')], true)) {
    }
    if ($maybe?->id == 'edit-shop_order') {
    }
    if (!in_array($screen->id, ['shop_order', \wc_get_page_screen_id('shop-order')], true)) {
    }

    // Unchanged.
    if (get_post_type() === 'shop_order') {
    }
    if (get_post_type($id) === 'product') {
    }
    if ($other->id === 'shop_order') {
    }
    if ($screen->id === 'product') {
    }
    if ($screen->id === 'edit-shop_order') {
    }
}

?>

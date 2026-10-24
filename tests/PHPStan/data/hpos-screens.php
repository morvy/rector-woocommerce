<?php

function hpos_screens(int $order_id, string $hook): void
{
    global $typenow, $post_type;

    $screen = get_current_screen();

    if ($screen->id === 'shop_order') {
    }

    if ('edit-shop_order' == $typenow) {
    }

    if (get_post_type($order_id) === 'shop_order') {
    }

    if ($_GET['post_type'] === 'shop_order') {
    }

    add_meta_box('box', 'Box', 'render_box', 'shop_order', 'side');
    remove_meta_box('box', 'shop_order', 'side');
    $url = admin_url('edit.php?post_type=shop_order');
}

function hpos_screens_dual(WP_Screen $screen): void
{
    global $post_type;

    // Fine: dual checks and dual registrations.
    if ($screen->id === 'shop_order' || $screen->id === wc_get_page_screen_id('shop-order')) {
    }

    $is_orders_page = ($post_type == 'shop_order') || (isset($_GET['page']) && $_GET['page'] === 'wc-orders');
    add_meta_box('box', 'Box', 'render_box', ['shop_order', wc_get_page_screen_id('shop-order')], 'side');
    add_meta_box('box', 'Box', 'render_box', wc_get_page_screen_id('shop-order'), 'side');

    if (get_post_type() === 'shop_order') {
    }

    if ($screen->id === 'product') {
    }
}

function hpos_screens_more(WP_Screen $screen, WP_Post $post, $post_or_order): void
{
    if (strpos($screen->id, 'woocommerce') === false && $screen->id !== 'shop_order') {
    }

    if ($post_or_order instanceof WP_Post) {
        $is_order = 'shop_order' === $post_or_order->post_type;
    }

    if ($post->post_type === 'shop_order') {
    }
}

function hpos_meta_boxes_separately(): void
{
    add_meta_box('box', 'Box', 'render_box', 'shop_order', 'side');
    add_meta_box('box', 'Box', 'render_box', 'woocommerce_page_wc-orders', 'side');
}

function hpos_meta_box_callback(string $post_type): void
{
    if ($post_type === wc_get_page_screen_id('shop-order')) {
        add_meta_box('box', 'Box', 'render_box', wc_get_page_screen_id('shop-order'), 'side');
    }

    if ($post_type == 'shop_order') {
        add_meta_box('box', 'Box', 'render_box', 'shop_order', 'side');
    }
}

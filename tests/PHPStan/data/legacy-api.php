<?php

class My_Legacy_Resource extends WC_API_Resource {}

function legacy_api(): void
{
    $server = new WC_API_Server('/');
    WC_API_Orders::get_order(1);
    $x = WC()->api->server;
    WC()->api->register_resources(null);
    $url = get_woocommerce_api_url('orders');
    update_option('woocommerce_api_enabled', 'yes');
    $endpoint = home_url('/wc-api/v3/orders');

    // Still works.
    WC()->api->get_endpoint_data('/wc/v3/orders');
    $callback = home_url('/wc-api/my_gateway/');
    $class = WC_API::class;
}

function legacy_api_guarded(): void
{
    if (class_exists('WC_API')) {
        $server = new WC_API_Server('/');
    }
}

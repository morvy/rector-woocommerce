<?php

declare(strict_types=1);

// Minimal WooCommerce class tree, so tests resolve subtypes and overrides like a real install does.

class WC_Data
{
    protected $id = 0;

    public function get_id(): void {}
}

class WC_Order extends WC_Data
{
    public function get_billing_email(): void {}
}

class WC_Product extends WC_Data
{
    public function get_sku(): void {}
}

class WC_Legacy_Cart
{
    public function get_cart_url(): void {}

    public function remove_taxes(): void {}

    public function init(): void {}
}

class WC_Cart extends WC_Legacy_Cart {}

abstract class WC_Settings_API
{
    public function format_settings($value): void {}

    public function validate_settings_fields($form_fields = []): void {}
}

class WooCommerce
{
    /**
     * @var WC_Cart
     */
    public $cart;
}

function WC(): WooCommerce {}

abstract class WC_Abstract_Legacy_Order
{
    public function get_item_meta($order_item_id, $key = '', $single = false): void {}
}

abstract class WC_Abstract_Legacy_Product
{
    public function get_price_including_tax($qty = 1, $price = ''): void {}

    public function get_display_price($price = '', $qty = 1): void {}

    public function get_related($limit = 5): void {}

    public function reduce_stock($amount = 1): void {}
}

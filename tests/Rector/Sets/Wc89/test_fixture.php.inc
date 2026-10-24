<?php

__experimental_woocommerce_blocks_register_checkout_field('foo');
if (__experimental_woocommerce_blocks_register_checkout_field('foo')) {
    echo 'kept';
}
add_filter('__experimental_woocommerce_blocks_sanitize_additional_field', 'my_callback');
add_filter('__experimental_woocommerce_blocks_validate_additional_field', 'my_callback');
add_filter('__experimental_woocommerce_blocks_validate_location_address_fields', 'my_callback');
add_filter('__experimental_woocommerce_blocks_validate_location_contact_fields', 'my_callback');
add_filter('__experimental_woocommerce_blocks_validate_location_order_fields', 'my_callback');
$wc6 = new \Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields();
$wc6->get_additional_fields_keys('foo');

?>
-----
<?php

\woocommerce_register_additional_checkout_field('foo');
if (__experimental_woocommerce_blocks_register_checkout_field('foo')) {
    echo 'kept';
}
add_filter('woocommerce_sanitize_additional_field', 'my_callback');
add_filter('woocommerce_validate_additional_field', 'my_callback');
add_filter('woocommerce_blocks_validate_location_address_fields', 'my_callback');
add_filter('woocommerce_blocks_validate_location_contact_fields', 'my_callback');
add_filter('woocommerce_blocks_validate_location_order_fields', 'my_callback');
$wc6 = new \Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields();
$wc6->get_order_fields_keys('foo');

?>

<?php

__experimental_woocommerce_blocks_register_checkout_field('foo');
if (__experimental_woocommerce_blocks_register_checkout_field('foo')) {
    echo 'kept';
}
$wc1 = new \Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields();
$wc1->get_additional_fields_keys('foo');

?>
-----
<?php

\woocommerce_register_additional_checkout_field('foo');
if (__experimental_woocommerce_blocks_register_checkout_field('foo')) {
    echo 'kept';
}
$wc1 = new \Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields();
$wc1->get_order_fields_keys('foo');

?>

<?php

$wc0 = new \Automattic\WooCommerce\Internal\DataStores\Orders\OrdersTableDataStore();
$wc0->get_orders('foo');
$wc1 = new \WC_Order_Data_Store_CPT();
$wc1->get_orders('foo');
$wc2 = wc_get_customer_avatar_url('foo');

?>
-----
<?php

$wc0 = new \Automattic\WooCommerce\Internal\DataStores\Orders\OrdersTableDataStore();
\wc_get_orders('foo');
$wc1 = new \WC_Order_Data_Store_CPT();
\wc_get_orders('foo');
$wc2 = get_avatar_url('foo');

?>

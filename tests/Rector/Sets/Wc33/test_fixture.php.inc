<?php

$wc0 = new \WC_Cart();
$wc0->get_item_data('foo');
$wc1 = new \WC_Cart();
$wc1->get_remove_url('foo');
$wc2 = new \WC_Cart();
$wc2->get_undo_url('foo');
$wc3 = new \WC_Webhook();
$wc3->get_delivery_log();
$wc4 = new \WC_Admin_Post_Types();
$wc4->process_product_file_download_paths();

?>
-----
<?php

$wc0 = new \WC_Cart();
\wc_get_formatted_cart_item_data('foo');
$wc1 = new \WC_Cart();
\wc_get_cart_remove_url('foo');
$wc2 = new \WC_Cart();
\wc_get_cart_undo_url('foo');
$wc3 = new \WC_Webhook();
$wc4 = new \WC_Admin_Post_Types();

?>

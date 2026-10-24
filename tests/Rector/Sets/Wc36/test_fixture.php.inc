<?php

wc_taxonomy_metadata_update_content_for_split_terms();
$wc1 = new \WC_Admin_Taxonomies();
$wc1->delete_term();
$wc2 = update_woocommerce_term_meta('foo');
$wc3 = add_woocommerce_term_meta('foo');
$wc4 = delete_woocommerce_term_meta('foo');
$wc5 = get_woocommerce_term_meta('foo');

?>
-----
<?php

$wc1 = new \WC_Admin_Taxonomies();
$wc2 = update_term_meta('foo');
$wc3 = add_term_meta('foo');
$wc4 = delete_term_meta('foo');
$wc5 = get_term_meta('foo');

?>

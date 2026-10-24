<?php

$wc0 = $value instanceof \WC_Settings_Rest_API;
add_filter('woocommerce_settings_account_page_options', 'my_callback');
add_filter('woocommerce_settings_account_page_options_after', 'my_callback');
add_filter('woocommerce_settings_account_page_options_end', 'my_callback');
add_filter('woocommerce_settings_api', 'my_callback');
add_filter('woocommerce_settings_checkout_page_options', 'my_callback');
add_filter('woocommerce_settings_checkout_page_options_after', 'my_callback');
add_filter('woocommerce_settings_checkout_page_options_end', 'my_callback');
add_filter('woocommerce_settings_tabs_api', 'my_callback');
$wc9 = new \WC_Widget_Product_Tag_Cloud();
$wc9->_get_current_taxonomy('foo');
$wc10 = new \WC_Widget_Product_Tag_Cloud();
$wc10->_topic_count_text('foo');

?>
-----
<?php

$wc0 = $value instanceof \WC_Settings_Advanced;
add_filter('woocommerce_settings_advanced_page_options', 'my_callback');
add_filter('woocommerce_settings_advanced_page_options_after', 'my_callback');
add_filter('woocommerce_settings_advanced_page_options_end', 'my_callback');
add_filter('woocommerce_settings_advanced', 'my_callback');
add_filter('woocommerce_settings_advanced_page_options', 'my_callback');
add_filter('woocommerce_settings_advanced_page_options_after', 'my_callback');
add_filter('woocommerce_settings_advanced_page_options_end', 'my_callback');
add_filter('woocommerce_settings_tabs_advanced', 'my_callback');
$wc9 = new \WC_Widget_Product_Tag_Cloud();
$wc9->get_current_taxonomy('foo');
$wc10 = new \WC_Widget_Product_Tag_Cloud();
$wc10->topic_count_text('foo');

?>

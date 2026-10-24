<?php

add_filter('woocommerce_get_price', 'my_price', 10, 2);
add_action('woocommerce_get_price', 'my_price');
remove_filter('woocommerce_get_price', 'my_price', 10);
if (has_filter('woocommerce_get_price')) {
}
apply_filters('woocommerce_get_price', $price, $product);
remove_all_filters('woocommerce_get_price');
add_filter($hook, 'my_price');
add_filter("woocommerce_get_price", 'my_price');
add_filter('woocommerce_get_price_html', 'my_price');

?>
-----
<?php

add_filter('woocommerce_product_get_price', 'my_price', 10, 2);
add_action('woocommerce_product_get_price', 'my_price');
remove_filter('woocommerce_product_get_price', 'my_price', 10);
if (has_filter('woocommerce_product_get_price')) {
}
apply_filters('woocommerce_get_price', $price, $product);
remove_all_filters('woocommerce_get_price');
add_filter($hook, 'my_price');
add_filter('woocommerce_product_get_price', 'my_price');
add_filter('woocommerce_get_price_html', 'my_price');

?>

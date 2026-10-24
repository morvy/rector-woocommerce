<?php

add_filter('__experimental_woocommerce_blocks_sanitize_additional_field', 'my_callback');
add_filter('__experimental_woocommerce_blocks_validate_additional_field', 'my_callback');

?>
-----
<?php

add_filter('woocommerce_sanitize_additional_field', 'my_callback');
add_filter('woocommerce_validate_additional_field', 'my_callback');

?>

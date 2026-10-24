<?php

add_filter('woocommerce_geolocation_update_database_periodically', 'my_callback');
$wc1 = \WC_Cache_Helper::incr_cache_prefix('foo');

?>
-----
<?php

add_filter('woocommerce_maxmind_geolocation_update_database_periodically', 'my_callback');
$wc1 = \WC_Cache_Helper::invalidate_cache_group('foo');

?>

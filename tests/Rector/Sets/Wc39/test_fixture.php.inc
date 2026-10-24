<?php

$wc0 = \WC_Cache_Helper::incr_cache_prefix('foo');

?>
-----
<?php

$wc0 = \WC_Cache_Helper::invalidate_cache_group('foo');

?>

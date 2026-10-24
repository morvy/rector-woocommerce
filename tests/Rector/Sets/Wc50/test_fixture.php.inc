<?php

$wc0 = \Automattic\WooCommerce\Admin\Loader::is_feature_enabled('foo');

?>
-----
<?php

$wc0 = \Automattic\WooCommerce\Admin\Features\Features::is_enabled('foo');

?>

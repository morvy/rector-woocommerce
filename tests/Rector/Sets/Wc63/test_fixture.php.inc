<?php

$wc0 = \Automattic\WooCommerce\Admin\Loader::is_admin_or_embed_page('foo');
$wc1 = \Automattic\WooCommerce\Admin\Loader::is_admin_page('foo');
$wc2 = \Automattic\WooCommerce\Admin\Loader::is_embed_page('foo');

?>
-----
<?php

$wc0 = \Automattic\WooCommerce\Admin\PageController::is_admin_or_embed_page('foo');
$wc1 = \Automattic\WooCommerce\Admin\PageController::is_admin_page('foo');
$wc2 = \Automattic\WooCommerce\Admin\PageController::is_embed_page('foo');

?>

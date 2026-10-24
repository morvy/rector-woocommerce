<?php

$wc0 = new \Automattic\WooCommerce\Internal\Features\ProductBlockEditor\ProductTemplates\Section();
$wc0->add_section('foo');

?>
-----
<?php

$wc0 = new \Automattic\WooCommerce\Internal\Features\ProductBlockEditor\ProductTemplates\Section();
$wc0->add_subsection('foo');

?>

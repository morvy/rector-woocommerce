<?php

$wc0 = new \Automattic\WooCommerce\Blocks\BlockTemplatesController();
$wc0r = $wc0->get_block_templates_from_db('a0', 'a1');

?>
-----
<?php

$wc0 = new \Automattic\WooCommerce\Blocks\BlockTemplatesController();
$wc0r = \Automattic\WooCommerce\Blocks\Utils\BlockTemplateUtils::get_block_templates_from_db('a0', 'a1');

?>

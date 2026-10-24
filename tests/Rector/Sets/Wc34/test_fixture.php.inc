<?php

$wc0 = new \WC_Settings_Rest_API();
$wc1 = new \WC_Widget_Product_Tag_Cloud();
$wc1->_get_current_taxonomy('foo');
$wc2 = new \WC_Widget_Product_Tag_Cloud();
$wc2->_topic_count_text('foo');

?>
-----
<?php

$wc0 = new \WC_Settings_Advanced();
$wc1 = new \WC_Widget_Product_Tag_Cloud();
$wc1->get_current_taxonomy('foo');
$wc2 = new \WC_Widget_Product_Tag_Cloud();
$wc2->topic_count_text('foo');

?>

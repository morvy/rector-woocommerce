<?php

$wc0 = new \WC_Admin_List_Table_Products();
$wc0 = $wc0->sku_search('foo');

?>
-----
<?php

$wc0 = new \WC_Admin_List_Table_Products();
$wc0 = 'foo';

?>

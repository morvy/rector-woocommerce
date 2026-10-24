<?php

$wc0 = new \WC_Gateway_Paypal_Notices();
$wc0->add_paypal_migration_notice_on_payments_settings_page('foo');

?>
-----
<?php

$wc0 = new \WC_Gateway_Paypal_Notices();
$wc0->add_paypal_notices_on_payments_settings_page('foo');

?>

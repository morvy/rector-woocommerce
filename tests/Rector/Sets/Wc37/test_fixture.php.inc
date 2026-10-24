<?php

$wc0 = new \WC_Abstract_Legacy_Order();
$wc0->get_used_coupons('foo');
$wc1 = new \WC_Emails();
$wc1->email_footer_replace_site_title('foo');

?>
-----
<?php

$wc0 = new \WC_Abstract_Legacy_Order();
$wc0->get_coupon_codes('foo');
$wc1 = new \WC_Emails();
$wc1->replace_placeholders('foo');

?>

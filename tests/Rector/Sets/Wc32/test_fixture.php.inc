<?php

$wc0 = new \WC_Webhook();
echo $wc0->id;
$wc1 = new \WC_Webhook();
echo $wc1->status;
$wc2 = new \WC_Webhook();
echo $wc2->delivery_url;
$wc3 = new \WC_Webhook();
echo $wc3->secret;
$wc4 = new \WC_Webhook();
echo $wc4->topic;
$wc5 = new \WC_Webhook();
echo $wc5->hooks;
$wc6 = new \WC_Webhook();
echo $wc6->resource;
$wc7 = new \WC_Webhook();
echo $wc7->event;
$wc8 = new \WC_Webhook();
echo $wc8->failure_count;
$wc9 = new \WC_Webhook();
echo $wc9->api_version;
$wc10 = new \WC_Legacy_Cart();
$wc10->remove_taxes();
$wc11 = new \WC_Query();
$wc11->remove_posts_where();
$wc12 = new \WC_Legacy_Cart();
$wc12->init('foo');
$wc13 = new \WC_Query();
$wc13 = $wc13->search_post_excerpt('foo');

?>
-----
<?php

$wc0 = new \WC_Webhook();
echo $wc0->get_id();
$wc1 = new \WC_Webhook();
echo $wc1->get_status();
$wc2 = new \WC_Webhook();
echo $wc2->get_delivery_url();
$wc3 = new \WC_Webhook();
echo $wc3->get_secret();
$wc4 = new \WC_Webhook();
echo $wc4->get_topic();
$wc5 = new \WC_Webhook();
echo $wc5->get_hooks();
$wc6 = new \WC_Webhook();
echo $wc6->get_resource();
$wc7 = new \WC_Webhook();
echo $wc7->get_event();
$wc8 = new \WC_Webhook();
echo $wc8->get_failure_count();
$wc9 = new \WC_Webhook();
echo $wc9->get_api_version();
$wc10 = new \WC_Legacy_Cart();
$wc11 = new \WC_Query();
$wc12 = new \WC_Legacy_Cart();
$wc12->get_cart_from_session('foo');
$wc13 = new \WC_Query();
$wc13 = 'foo';

?>

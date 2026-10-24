<?php

$wc0 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\WooCommerceAdminUpdatedRuleProcessor();
$wc1 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\WCAdminActiveForRuleProcessor();
$wc2 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\WCAdminActiveForProvider();
$wc3 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\TransformerService();
$wc4 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\TotalPaymentsVolumeProcessor();
$wc5 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\StoredStateSetupForProducts();
$wc6 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\StoredStateRuleProcessor();
$wc7 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\RuleEvaluator();
$wc8 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\PublishBeforeTimeRuleProcessor();
$wc9 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\PublishAfterTimeRuleProcessor();
$wc10 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\ProductCountRuleProcessor();
$wc11 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\PluginVersionRuleProcessor();
$wc12 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\PluginsActivatedRuleProcessor();
$wc13 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\PassRuleProcessor();
$wc14 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\OrRuleProcessor();
$wc15 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\OrdersProvider();
$wc16 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\OrderCountRuleProcessor();
$wc17 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\OptionRuleProcessor();
$wc18 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\OnboardingProfileRuleProcessor();
$wc19 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\NotRuleProcessor();
$wc20 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\NoteStatusRuleProcessor();
$wc21 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\IsWooExpressRuleProcessor();
$wc22 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\IsEcommerceRuleProcessor();
$wc23 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\GetRuleProcessor();
$wc24 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\FailRuleProcessor();
$wc25 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\EvaluationLogger();
$wc26 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\EvaluateAndGetStatus();
$wc27 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\ComparisonOperation();
$wc28 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\BaseLocationStateRuleProcessor();
$wc29 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\BaseLocationCountryRuleProcessor();
$wc30 = new \Automattic\WooCommerce\Admin\RemoteInboxNotifications\Transformers\ArrayColumn();

?>
-----
<?php

$wc0 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\WooCommerceAdminUpdatedRuleProcessor();
$wc1 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\WCAdminActiveForRuleProcessor();
$wc2 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\WCAdminActiveForProvider();
$wc3 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\Transformers\TransformerService();
$wc4 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\TotalPaymentsVolumeProcessor();
$wc5 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\StoredStateSetupForProducts();
$wc6 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\StoredStateRuleProcessor();
$wc7 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\RuleEvaluator();
$wc8 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\PublishBeforeTimeRuleProcessor();
$wc9 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\PublishAfterTimeRuleProcessor();
$wc10 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\ProductCountRuleProcessor();
$wc11 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\PluginVersionRuleProcessor();
$wc12 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\PluginsActivatedRuleProcessor();
$wc13 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\PassRuleProcessor();
$wc14 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\OrRuleProcessor();
$wc15 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\OrdersProvider();
$wc16 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\OrderCountRuleProcessor();
$wc17 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\OptionRuleProcessor();
$wc18 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\OnboardingProfileRuleProcessor();
$wc19 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\NotRuleProcessor();
$wc20 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\NoteStatusRuleProcessor();
$wc21 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\IsWooExpressRuleProcessor();
$wc22 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\IsEcommerceRuleProcessor();
$wc23 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\GetRuleProcessor();
$wc24 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\FailRuleProcessor();
$wc25 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\EvaluationLogger();
$wc26 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\EvaluateAndGetStatus();
$wc27 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\ComparisonOperation();
$wc28 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\BaseLocationStateRuleProcessor();
$wc29 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\BaseLocationCountryRuleProcessor();
$wc30 = new \Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\Transformers\ArrayColumn();

?>

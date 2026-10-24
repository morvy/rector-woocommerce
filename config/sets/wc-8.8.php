<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\BaseLocationCountryRuleProcessor'     => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\BaseLocationCountryRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\BaseLocationStateRuleProcessor'       => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\BaseLocationStateRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\ComparisonOperation'                  => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\ComparisonOperation',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\EvaluateAndGetStatus'                 => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\EvaluateAndGetStatus',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\EvaluationLogger'                     => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\EvaluationLogger',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\FailRuleProcessor'                    => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\FailRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\GetRuleProcessor'                     => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\GetRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\IsEcommerceRuleProcessor'             => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\IsEcommerceRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\IsWooExpressRuleProcessor'            => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\IsWooExpressRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\NotRuleProcessor'                     => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\NotRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\NoteStatusRuleProcessor'              => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\NoteStatusRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\OnboardingProfileRuleProcessor'       => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\OnboardingProfileRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\OptionRuleProcessor'                  => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\OptionRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\OrRuleProcessor'                      => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\OrRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\OrderCountRuleProcessor'              => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\OrderCountRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\OrdersProvider'                       => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\OrdersProvider',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\PassRuleProcessor'                    => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\PassRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\PluginVersionRuleProcessor'           => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\PluginVersionRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\PluginsActivatedRuleProcessor'        => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\PluginsActivatedRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\ProductCountRuleProcessor'            => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\ProductCountRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\PublishAfterTimeRuleProcessor'        => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\PublishAfterTimeRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\PublishBeforeTimeRuleProcessor'       => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\PublishBeforeTimeRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\RuleEvaluator'                        => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\RuleEvaluator',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\StoredStateRuleProcessor'             => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\StoredStateRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\StoredStateSetupForProducts'          => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\StoredStateSetupForProducts',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\TotalPaymentsVolumeProcessor'         => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\TotalPaymentsVolumeProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\TransformerService'                   => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\Transformers\TransformerService',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\Transformers\ArrayColumn'             => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\Transformers\ArrayColumn',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\WCAdminActiveForProvider'             => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\WCAdminActiveForProvider',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\WCAdminActiveForRuleProcessor'        => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\WCAdminActiveForRuleProcessor',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\WooCommerceAdminUpdatedRuleProcessor' => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\WooCommerceAdminUpdatedRuleProcessor',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - Automattic\WooCommerce\Admin\DataSourcePoller
     * - Automattic\WooCommerce\Admin\RemoteInboxNotifications\Transformers\ArrayFlatten (WC facade wrongly points to ArrayColumn)
     * - Automattic\WooCommerce\Admin\RemoteInboxNotifications\Transformers\ArrayKeys (WC facade wrongly points to ArrayColumn)
     * - Automattic\WooCommerce\Admin\RemoteInboxNotifications\Transformers\ArraySearch (WC facade wrongly points to ArrayColumn)
     * - Automattic\WooCommerce\Admin\RemoteInboxNotifications\Transformers\ArrayValues (WC facade wrongly points to ArrayColumn)
     * - Automattic\WooCommerce\Admin\RemoteInboxNotifications\Transformers\Count (WC facade wrongly points to ArrayColumn)
     * - Automattic\WooCommerce\Admin\RemoteInboxNotifications\Transformers\DotNotation (WC facade wrongly points to ArrayColumn)
     * - Automattic\WooCommerce\Admin\RemoteInboxNotifications\Transformers\PrepareUrl (WC facade wrongly points to ArrayColumn)
     *
     * METHODS
     * - Automattic\WooCommerce\Admin\DataSourcePoller::delete_specs_transient
     * - Automattic\WooCommerce\Admin\DataSourcePoller::get_specs_from_data_sources
     * - Automattic\WooCommerce\Admin\DataSourcePoller::read_specs_from_data_sources
     * - Automattic\WooCommerce\Admin\DataSourcePoller::set_specs_transient
     */
};

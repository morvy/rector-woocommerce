<?php

use Morvy\RectorWooCommerce\Rules\MethodCall\RemoveMethodCallRector;
use Morvy\RectorWooCommerce\Rules\MethodCall\RenameMethodRector;
use Morvy\RectorWooCommerce\Rules\MethodCall\ReturnFirstArgumentRector as MethodReturnFirstArgumentRector;
use Morvy\RectorWooCommerce\Rules\PropertyFetch\PropertyFetchToMethodCallRector;
use Morvy\RectorWooCommerce\ValueObject\PropertyFetchToMethodCall;
use Rector\Config\RectorConfig;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(PropertyFetchToMethodCallRector::class, [
        new PropertyFetchToMethodCall('WC_Webhook', 'api_version', 'get_api_version'),
        new PropertyFetchToMethodCall('WC_Webhook', 'delivery_url', 'get_delivery_url'),
        new PropertyFetchToMethodCall('WC_Webhook', 'event', 'get_event'),
        new PropertyFetchToMethodCall('WC_Webhook', 'failure_count', 'get_failure_count'),
        new PropertyFetchToMethodCall('WC_Webhook', 'hooks', 'get_hooks'),
        new PropertyFetchToMethodCall('WC_Webhook', 'id', 'get_id'),
        new PropertyFetchToMethodCall('WC_Webhook', 'resource', 'get_resource'),
        new PropertyFetchToMethodCall('WC_Webhook', 'secret', 'get_secret'),
        new PropertyFetchToMethodCall('WC_Webhook', 'status', 'get_status'),
        new PropertyFetchToMethodCall('WC_Webhook', 'topic', 'get_topic'),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        'remove_posts_where' => 'WC_Query',
        'remove_taxes'       => 'WC_Legacy_Cart',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('WC_Legacy_Cart', 'init', 'get_cart_from_session'),
    ]);

    $rectorConfig->ruleWithConfiguration(MethodReturnFirstArgumentRector::class, [
        'search_post_excerpt' => 'WC_Query',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * METHODS
     * - WC_Legacy_Cart::get_discounted_price
     * - WC_Legacy_Webhook::get_post_data
     * - WC_Legacy_Webhook::update_status
     */
};

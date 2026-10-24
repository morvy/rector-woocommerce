<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\RuleProcessorInterface' => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\RuleProcessorInterface',
        'Automattic\WooCommerce\Admin\RemoteInboxNotifications\TransformerInterface'   => 'Automattic\WooCommerce\Admin\RemoteSpecs\RuleProcessors\Transformers\TransformerInterface',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - Automattic\WooCommerce\Admin\RemoteInboxNotifications\RuleProcessorInterface
     * - Automattic\WooCommerce\Admin\RemoteInboxNotifications\TransformerInterface
     */
};

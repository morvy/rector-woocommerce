<?php

use Morvy\RectorWooCommerce\Rules\MethodCall\RenameMethodRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename('Automattic\WooCommerce\Internal\Features\ProductBlockEditor\ProductTemplates\Section', 'add_section', 'add_subsection'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * ACTIONS
     * - woocommerce_block_template_register → woocommerce_layout_template_after_instantiation
     *
     * FUNCTIONS
     * - wc_get_log_file_name
     * - wc_get_log_file_path
     * - wc_register_default_log_handler
     *
     * METHODS
     * - WC_Orders_Tracking::track_order_search
     */
};

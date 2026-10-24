<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
        'wc_current_theme_is_fse_theme' => 'wp_is_block_theme',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * ARGUMENTS
     * - WC_Emails::wrap_message
     *
     * CLASSES
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\Tasks\WooCommercePayments
     * - Automattic\WooCommerce\Internal\Admin\WCPayPromotion\Init
     * - Automattic\WooCommerce\Internal\Admin\WcPayWelcomePage
     */
};

<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameStaticMethodRector::class, [
        new RenameStaticMethod('Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils', 'is_overriden_by_custom_template_content', 'Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils', 'is_overridden_by_custom_template_content'),
    ]);

    /*
     * TODO: these are not handled currently
     *
     * ACTIONS
     * - woocommerce_block_template_after_add_block
     * - woocommerce_block_template_after_add_block_error
     * - woocommerce_block_template_after_add_hide_condition
     * - woocommerce_block_template_after_remove_block
     * - woocommerce_block_template_after_remove_block_error
     * - woocommerce_block_template_after_remove_hide_condition
     *
     * CLASSES
     * - Automattic\WooCommerce\Admin\API\Settings
     * - Automattic\WooCommerce\Admin\BlockTemplates\BlockContainerInterface
     * - Automattic\WooCommerce\Admin\BlockTemplates\BlockInterface
     * - Automattic\WooCommerce\Admin\BlockTemplates\BlockTemplateInterface
     * - Automattic\WooCommerce\Admin\BlockTemplates\ContainerInterface
     * - Automattic\WooCommerce\Admin\Features\ProductBlockEditor\BlockRegistry
     * - Automattic\WooCommerce\Admin\Features\ProductBlockEditor\BlockTemplateUtils
     * - Automattic\WooCommerce\Admin\Features\ProductBlockEditor\Init
     * - Automattic\WooCommerce\Admin\Features\ProductBlockEditor\ProductFormsController
     * - Automattic\WooCommerce\Admin\Features\ProductBlockEditor\ProductTemplate
     * - Automattic\WooCommerce\Admin\Features\ProductBlockEditor\ProductTemplates\GroupInterface
     * - Automattic\WooCommerce\Admin\Features\ProductBlockEditor\ProductTemplates\ProductFormTemplateInterface
     * - Automattic\WooCommerce\Admin\Features\ProductBlockEditor\ProductTemplates\SectionInterface
     * - Automattic\WooCommerce\Admin\Features\ProductBlockEditor\ProductTemplates\SubsectionInterface
     * - Automattic\WooCommerce\Admin\Features\ProductBlockEditor\RedirectionController
     * - Automattic\WooCommerce\Admin\Features\ProductBlockEditor\Tracks
     * - Automattic\WooCommerce\LayoutTemplates\LayoutTemplateRegistry
     *
     * CONSTANTS
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskList::REMINDER_BAR_HIDDEN_OPTION
     */
};

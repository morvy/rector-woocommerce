<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Automattic\WooCommerce\Admin\Notes\WC_Admin_Note'  => 'Automattic\WooCommerce\Admin\Notes\Note',
        'Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes' => 'Automattic\WooCommerce\Admin\Notes\Notes',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Facebook_Marketing_Expert → Automattic\WooCommerce\Admin\Notes\FacebookMarketingExpert
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Onboarding_Email_Marketing → Automattic\WooCommerce\Admin\Notes\OnboardingEmailMarketing
     *
     * FUNCTIONS
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Coupon_Page_Moved
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Customize_Store_With_Blocks
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_EU_VAT_Number
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Edit_Products_On_The_Move
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_First_Product
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Giving_Feedback_Notes
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Insight_First_Sale
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Install_JP_And_WCS_Plugins
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Launch_Checklist
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Migrate_From_Shopify
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Mobile_App
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_New_Sales_Record
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Onboarding_Payments
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Online_Clothing_Store
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Order_Milestones
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Performance_On_Mobile
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Personalize_Store
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Real_Time_Order_Alerts
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Selling_Online_Courses
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Test_Checkout
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Tracking_Opt_In
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_WooCommerce_Payments
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_WooCommerce_Subscriptions
     * - Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes_Woo_Subscriptions_Notes
     */
};

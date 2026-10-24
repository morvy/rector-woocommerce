<?php

use Morvy\RectorWooCommerce\Rules\MethodCall\RemoveMethodCallRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        'add_footer_scripts'           => 'WC_Admin_Setup_Wizard_Tracking',
        'add_step_save_events'         => 'WC_Admin_Setup_Wizard_Tracking',
        'enqueue_scripts'              => 'WC_Admin_Setup_Wizard',
        'init'                         => 'WC_Admin_Setup_Wizard_Tracking',
        'track_jetpack_activate'       => 'WC_Admin_Setup_Wizard_Tracking',
        'track_next_steps'             => 'WC_Admin_Setup_Wizard_Tracking',
        'track_payments'               => 'WC_Admin_Setup_Wizard_Tracking',
        'track_ready_next_steps'       => 'WC_Admin_Setup_Wizard_Tracking',
        'track_recommended'            => 'WC_Admin_Setup_Wizard_Tracking',
        'track_shipping'               => 'WC_Admin_Setup_Wizard_Tracking',
        'track_skip_step'              => 'WC_Admin_Setup_Wizard_Tracking',
        'track_start'                  => 'WC_Admin_Setup_Wizard_Tracking',
        'track_store_setup'            => 'WC_Admin_Setup_Wizard_Tracking',
        'wc_setup_activate_save'       => 'WC_Admin_Setup_Wizard',
        'wc_setup_new_onboarding_save' => 'WC_Admin_Setup_Wizard',
        'wc_setup_payment_save'        => 'WC_Admin_Setup_Wizard',
        'wc_setup_recommended_save'    => 'WC_Admin_Setup_Wizard',
        'wc_setup_shipping_save'       => 'WC_Admin_Setup_Wizard',
        'wc_setup_store_setup_save'    => 'WC_Admin_Setup_Wizard',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - WC_Admin_Setup_Wizard
     * - WC_Admin_Setup_Wizard_Tracking
     */
};

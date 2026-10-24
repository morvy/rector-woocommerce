<?php

use Morvy\RectorWooCommerce\Rules\FuncCall\RenameFunctionAsStatementRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;
use Rector\Transform\ValueObject\StaticCallToFuncCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
        'wc_get_scheduled_actions'     => 'as_get_scheduled_actions',
        'wc_next_scheduled_action'     => 'as_next_scheduled_action',
        'wc_schedule_cron_action'      => 'as_schedule_cron_action',
        'wc_schedule_recurring_action' => 'as_schedule_recurring_action',
        'wc_schedule_single_action'    => 'as_schedule_single_action',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameFunctionAsStatementRector::class, [
        'wc_unschedule_action' => 'as_unschedule_action',
    ]);

    $rectorConfig->ruleWithConfiguration(StaticCallToFuncCallRector::class, [
        new StaticCallToFuncCall('ActionScheduler', 'get_datetime_object', 'as_get_datetime_object'),
    ]);
};

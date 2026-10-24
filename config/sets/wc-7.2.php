<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    /*
     * TODO: these are not handled currently
     *
     * CLASSES
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskListSection
     *
     * CONSTANTS
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\Task::SNOOZED_OPTION
     *
     * METHODS
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\Task::get_level
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\Task::get_snoozed_until
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\Task::is_disabled
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\Task::is_snoozeable
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\Task::is_snoozed
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\Task::snooze
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\Task::undo_snooze
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskList::get_sections
     *
     * PROPERTIES
     * - Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskList::$task_class_id_map
     */
};

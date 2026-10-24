<?php

$wc0 = wc_schedule_single_action('foo');
$wc1 = wc_schedule_recurring_action('foo');
$wc2 = wc_schedule_cron_action('foo');
$wc3 = wc_next_scheduled_action('foo');
$wc4 = wc_get_scheduled_actions('foo');
wc_unschedule_action('foo');
if (wc_unschedule_action('foo')) {
    echo 'kept';
}
$wc6 = \ActionScheduler::get_datetime_object('foo');

?>
-----
<?php

$wc0 = as_schedule_single_action('foo');
$wc1 = as_schedule_recurring_action('foo');
$wc2 = as_schedule_cron_action('foo');
$wc3 = as_next_scheduled_action('foo');
$wc4 = as_get_scheduled_actions('foo');
\as_unschedule_action('foo');
if (wc_unschedule_action('foo')) {
    echo 'kept';
}
$wc6 = \as_get_datetime_object('foo');

?>

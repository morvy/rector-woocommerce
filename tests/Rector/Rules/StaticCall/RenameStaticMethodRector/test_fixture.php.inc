<?php

WC_Cache_Helper::incr_cache_prefix('orders');
$redact = WC_Gateway_Paypal_Helper::redact_data(...);

class Rename_Cache extends WC_Cache_Helper
{
    public static function run()
    {
        parent::incr_cache_prefix('orders');
        self::incr_cache_prefix('orders');
        Rename_Cache::redact_data([]);
    }
}

class Rename_Cache_Override extends WC_Cache_Helper
{
    public static function incr_cache_prefix($group)
    {
        return 'custom';
    }

    public static function run()
    {
        self::incr_cache_prefix('orders');
        static::incr_cache_prefix('orders');
        parent::incr_cache_prefix('orders');
    }
}

$helper = 'WC_Cache_Helper';
$helper::incr_cache_prefix('orders');
Unrelated::incr_cache_prefix('orders');

?>
-----
<?php

WC_Cache_Helper::invalidate_cache_group('orders');
$redact = \Automattic\WooCommerce\Gateways\PayPal\Helper::redact_data(...);

class Rename_Cache extends WC_Cache_Helper
{
    public static function run()
    {
        parent::invalidate_cache_group('orders');
        self::invalidate_cache_group('orders');
        Rename_Cache::redact_data([]);
    }
}

class Rename_Cache_Override extends WC_Cache_Helper
{
    public static function incr_cache_prefix($group)
    {
        return 'custom';
    }

    public static function run()
    {
        self::incr_cache_prefix('orders');
        static::incr_cache_prefix('orders');
        parent::invalidate_cache_group('orders');
    }
}

$helper = 'WC_Cache_Helper';
$helper::incr_cache_prefix('orders');
Unrelated::incr_cache_prefix('orders');

?>

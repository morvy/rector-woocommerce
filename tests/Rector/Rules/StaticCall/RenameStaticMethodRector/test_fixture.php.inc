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

class Mid_Cache extends WC_Cache_Helper
{
    public static function run()
    {
        static::incr_cache_prefix('orders');
    }
}

final class Final_Cache extends WC_Cache_Helper
{
    public static function run()
    {
        static::incr_cache_prefix('orders');
    }
}

function typed(WC_Cache_Helper $cache, WC_Gateway_Paypal_Helper $paypal)
{
    $cache::incr_cache_prefix('orders');
    $paypal::redact_data([]);
    wc_cache_helper::INCR_CACHE_PREFIX('orders');
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

class Mid_Cache extends WC_Cache_Helper
{
    public static function run()
    {
        static::incr_cache_prefix('orders');
    }
}

final class Final_Cache extends WC_Cache_Helper
{
    public static function run()
    {
        static::invalidate_cache_group('orders');
    }
}

function typed(WC_Cache_Helper $cache, WC_Gateway_Paypal_Helper $paypal)
{
    $cache::invalidate_cache_group('orders');
    $paypal::redact_data([]);
    wc_cache_helper::invalidate_cache_group('orders');
}

$helper = 'WC_Cache_Helper';
$helper::incr_cache_prefix('orders');
Unrelated::incr_cache_prefix('orders');

?>

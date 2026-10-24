<?php

$a = woocommerce_change_term_counts($terms, $taxonomies, $args);
$b = woocommerce_change_term_counts($terms, $taxonomies, compute_args());
$c = woocommerce_readfile_chunked($file, false);
$d = woocommerce_readfile_chunked(...$all);
$e = WCAdminHelper::is_store_page($url);

function order(WC_Abstract_Legacy_Order $order)
{
    $a = $order->get_item_meta($id);
    $b = $order->get_item_meta($id, '_qty', true);
    $c = load_order()->get_item_meta($id);
}

class My_Legacy_Order extends WC_Abstract_Legacy_Order
{
    public function get_item_meta($order_item_id, $key = '', $single = false)
    {
        return 'custom';
    }

    public function run()
    {
        return $this->get_item_meta(1);
    }
}

?>
-----
<?php

$a = \wc_change_term_counts($terms, $taxonomies);
$b = woocommerce_change_term_counts($terms, $taxonomies, compute_args());
$c = \WC_Download_Handler::readfile_chunked($file);
$d = woocommerce_readfile_chunked(...$all);
$e = \WCAdminHelper::is_current_page_store_page();

function order(WC_Abstract_Legacy_Order $order)
{
    $a = \get_metadata('order_item', $id);
    $b = \get_metadata('order_item', $id, '_qty', true);
    $c = load_order()->get_item_meta($id);
}

class My_Legacy_Order extends WC_Abstract_Legacy_Order
{
    public function get_item_meta($order_item_id, $key = '', $single = false)
    {
        return 'custom';
    }

    public function run()
    {
        return $this->get_item_meta(1);
    }
}

?>

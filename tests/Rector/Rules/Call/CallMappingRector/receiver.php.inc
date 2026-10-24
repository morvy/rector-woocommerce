<?php

function product(WC_Abstract_Legacy_Product $product, $qty)
{
    $a = $product->get_price_including_tax();
    $b = $product->get_price_including_tax(2, 10);
    $c = $product->get_display_price(10, $qty);
    $d = $product->get_display_price(next_price(), next_qty());
    $e = $product->get_related();
    $f = $product->get_related(3);
    $g = make_product()->get_related(3);
    $product->reduce_stock();
    $product->reduce_stock(4);
}

?>
-----
<?php

function product(WC_Abstract_Legacy_Product $product, $qty)
{
    $a = \wc_get_price_including_tax($product, ['qty' => 1, 'price' => '']);
    $b = \wc_get_price_including_tax($product, ['qty' => 2, 'price' => 10]);
    $c = \wc_get_price_to_display($product, ['qty' => $qty, 'price' => 10]);
    $d = $product->get_display_price(next_price(), next_qty());
    $e = \wc_get_related_products($product->get_id());
    $f = \wc_get_related_products($product->get_id(), 3);
    $g = make_product()->get_related(3);
    \wc_update_product_stock($product, 1, 'decrease');
    \wc_update_product_stock($product, 4, 'decrease');
}

?>

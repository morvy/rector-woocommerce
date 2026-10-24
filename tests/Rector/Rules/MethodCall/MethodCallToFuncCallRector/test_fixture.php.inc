<?php

function cart(WC_Cart $cart)
{
    $a = $cart->get_cart_url();
    $b = make_cart()->get_cart_url();
    $c = WC()->cart->get_cart_url();
}

class My_Cart extends WC_Cart
{
    public function get_cart_url()
    {
        return 'custom';
    }

    public function run()
    {
        return $this->get_cart_url();
    }
}

?>
-----
<?php

function cart(WC_Cart $cart)
{
    $a = \wc_get_cart_url();
    $b = make_cart()->get_cart_url();
    $c = \wc_get_cart_url();
}

class My_Cart extends WC_Cart
{
    public function get_cart_url()
    {
        return 'custom';
    }

    public function run()
    {
        return $this->get_cart_url();
    }
}

?>

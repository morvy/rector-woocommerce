<?php

function cart(WC_Cart $cart, ?WC_Cart $maybe)
{
    $cart->init();
    $maybe?->init();
}

class My_Cart extends WC_Cart
{
    public function init()
    {
        return 'custom';
    }

    public function run()
    {
        $this->init();
    }
}

?>
-----
<?php

function cart(WC_Cart $cart, ?WC_Cart $maybe)
{
    $cart->get_cart_from_session();
    $maybe?->get_cart_from_session();
}

class My_Cart extends WC_Cart
{
    public function init()
    {
        return 'custom';
    }

    public function run()
    {
        $this->init();
    }
}

?>

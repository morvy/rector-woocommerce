<?php

function cart(WC_Cart $cart)
{
    $cart->remove_taxes();
    $cart->remove_taxes(do_something());
    make_cart()->remove_taxes();
}

class Remove_Gateway extends WC_Settings_API
{
    public function validate_settings_fields($form_fields = [])
    {
        $this->errors[] = 'x';
    }

    public function run()
    {
        $this->validate_settings_fields();
    }
}

class Remove_Plain_Gateway extends WC_Settings_API
{
    public function run()
    {
        $this->validate_settings_fields();
    }
}

?>
-----
<?php

function cart(WC_Cart $cart)
{
    $cart->remove_taxes(do_something());
    make_cart()->remove_taxes();
}

class Remove_Gateway extends WC_Settings_API
{
    public function validate_settings_fields($form_fields = [])
    {
        $this->errors[] = 'x';
    }

    public function run()
    {
        $this->validate_settings_fields();
    }
}

class Remove_Plain_Gateway extends WC_Settings_API
{
    public function run()
    {
    }
}

?>

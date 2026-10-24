<?php

function reads(WC_Order $order, ?WC_Order $maybe)
{
    $id = $order->id;
    echo $order->billing_email;
    echo "id: {$order->id}";
    echo $maybe?->id;
}

function skipped(WC_Order $order, WC_Product $product, array $list)
{
    isset($order->id);
    isset($order->id[0]);
    isset($order->id->foo);
    empty($order->id);
    $a = $order->id ?? 0;
    $order->id ??= 5;
    unset($order->id[0]);
    $order->id[] = 5;
    $order->id[0]++;
    $ref = &$order->id[0];
    $order->id = &$list;
    [$x, [$order->id]] = [1, [2]];
    foreach ($list as $order->id) {
    }
    sort($order->id);
    echo $product->id;
}

function read_after_write(WC_Order $order)
{
    $order->billing_email = 'x';
    echo $order->billing_email;
}

class My_Order extends WC_Order
{
    public $billing_email;

    public function foo()
    {
        return $this->id;
    }
}

function declared_public(My_Order $order)
{
    echo $order->billing_email;
    echo $order->id;
}

function closure_scope(WC_Order $order)
{
    $order->billing_email = 'x';
    $fn = function () use ($order) {
        return $order->billing_email;
    };
}

class Holder
{
    private WC_Order $order;

    public function run()
    {
        $this->order->billing_email = 'x';
        echo $this->order->billing_email;
        echo $this->order->id;
    }
}

function by_ref_only(WC_Order $order)
{
    sort($order->billing_email);
    echo $order->id;
}

?>
-----
<?php

function reads(WC_Order $order, ?WC_Order $maybe)
{
    $id = $order->get_id();
    echo $order->get_billing_email();
    echo "id: {$order->get_id()}";
    echo $maybe?->get_id();
}

function skipped(WC_Order $order, WC_Product $product, array $list)
{
    isset($order->id);
    isset($order->id[0]);
    isset($order->id->foo);
    empty($order->id);
    $a = $order->id ?? 0;
    $order->id ??= 5;
    unset($order->id[0]);
    $order->id[] = 5;
    $order->id[0]++;
    $ref = &$order->id[0];
    $order->id = &$list;
    [$x, [$order->id]] = [1, [2]];
    foreach ($list as $order->id) {
    }
    sort($order->id);
    echo $product->id;
}

function read_after_write(WC_Order $order)
{
    $order->billing_email = 'x';
    echo $order->billing_email;
}

class My_Order extends WC_Order
{
    public $billing_email;

    public function foo()
    {
        return $this->id;
    }
}

function declared_public(My_Order $order)
{
    echo $order->billing_email;
    echo $order->get_id();
}

function closure_scope(WC_Order $order)
{
    $order->billing_email = 'x';
    $fn = function () use ($order) {
        return $order->billing_email;
    };
}

class Holder
{
    private WC_Order $order;

    public function run()
    {
        $this->order->billing_email = 'x';
        echo $this->order->billing_email;
        echo $this->order->get_id();
    }
}

function by_ref_only(WC_Order $order)
{
    sort($order->billing_email);
    echo $order->get_id();
}

?>

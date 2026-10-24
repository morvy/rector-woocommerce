<?php

class Return_Gateway extends WC_Settings_API
{
    public function format_settings($value)
    {
        return strtoupper($value);
    }

    public function run($v)
    {
        return $this->format_settings($v);
    }
}

class Return_Plain_Gateway extends WC_Settings_API
{
    public function run($v, array $args)
    {
        $a = $this->format_settings($v);
        $b = $this->format_settings(...$args);
        $c = $this->format_settings(value: $v);
    }
}

?>
-----
<?php

class Return_Gateway extends WC_Settings_API
{
    public function format_settings($value)
    {
        return strtoupper($value);
    }

    public function run($v)
    {
        return $this->format_settings($v);
    }
}

class Return_Plain_Gateway extends WC_Settings_API
{
    public function run($v, array $args)
    {
        $a = $v;
        $b = $this->format_settings(...$args);
        $c = $this->format_settings(value: $v);
    }
}

?>

<?php

$a = _wp_multiple_block_styles(...$args);
$b = _wp_multiple_block_styles($x, do_something());
$c = _wp_multiple_block_styles($x, 'foo');

?>
-----
<?php

$a = _wp_multiple_block_styles(...$args);
$b = _wp_multiple_block_styles($x, do_something());
$c = $x;

?>

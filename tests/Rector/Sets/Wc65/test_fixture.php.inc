<?php

add_filter('woocommerce_admin_newly_installed', 'my_callback');
add_filter('woocommerce_admin_updated', 'my_callback');
add_filter('woocommerce_product_csv_importer_check_import_file_path', 'my_callback');

?>
-----
<?php

add_filter('woocommerce_newly_installed', 'my_callback');
add_filter('woocommerce_updated', 'my_callback');
add_filter('woocommerce_csv_importer_check_import_file_path', 'my_callback');

?>

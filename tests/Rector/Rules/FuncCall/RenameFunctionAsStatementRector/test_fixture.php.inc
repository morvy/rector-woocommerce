<?php

woocommerce_mail($to, $subject, $message);

if (woocommerce_mail($to, $subject, $message)) {
    echo 'sent';
}

?>
-----
<?php

\wc_mail($to, $subject, $message);

if (woocommerce_mail($to, $subject, $message)) {
    echo 'sent';
}

?>

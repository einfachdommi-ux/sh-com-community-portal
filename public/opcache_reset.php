<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo 'OPCache wurde geleert!';
} else {
    echo 'OPCache nicht aktiv.';
}

?>
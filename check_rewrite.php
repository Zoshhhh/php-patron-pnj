<?php
if (in_array('mod_rewrite', apache_get_modules())) {
    echo "mod_rewrite est activé";
} else {
    echo "mod_rewrite n'est PAS activé";
}
?> 
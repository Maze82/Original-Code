<?php

$p = "test.pdf";
if (PDF_begin_document($p,  "", "compatibility 1.6") == 0) {
    die("Error: " . PDF_get_errmsg($p));
}

?>

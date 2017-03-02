<?php
global $student;
include "lib_real.php";
$fn="src/process/".basename($_REQUEST['actiontype'])."/".basename($_REQUEST['action']).".php";
if(file_exists($fn)) {
    echo "TEST SUCCESS!!";
    include($fn);
}
?>
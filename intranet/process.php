<?php
global $student;
include "lib_real.php";
$fn="src/process/".basename($_REQUEST['actiontype'])."/".basename($_REQUEST['action']).".php";
echo "<p>$fn</p>";
if(file_exists($fn)) {
    echo "<p>TEST SUCCESS!!</p>";
    include($fn);
}
?>
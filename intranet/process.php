<?php
include "intranet/lib_real.php";
$fn="intranet/src/process/".basename($_REQUEST['actiontype'])."/".basename($_REQUEST['action']).".php";
if(file_exists($fn)) {
    include($fn);
}
?>
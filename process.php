<?php
include "src/lib.php";
include "teacher/lib.php";
if(isset($_REQUEST['teacher']) && $_REQUEST['teacher'] == 'yes'){
    $fn = "teacher/process/" . basename($_REQUEST['actiontype']) . ".php";
    echo $fn;
} else {
    $fn="src/process/".basename($_REQUEST['actiontype'])."/".basename($_REQUEST['action']).".php";
}
if(file_exists($fn)) {
	include($fn);
}

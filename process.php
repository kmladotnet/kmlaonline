<?php
include "src/lib.php";
include "teacher/lib.php";
if (isset($_REQUEST['isteacher']) && $_REQUEST['isteacher'] == 'yes') {
    $fn = "teacher/process/" . basename($_REQUEST['actiontype']) . ".php";
} else {
    $fn = "src/process/" . basename($_REQUEST['actiontype']) . "/" . basename($_REQUEST['action']) . ".php";
}
if (file_exists($fn)) {
    include($fn);
}

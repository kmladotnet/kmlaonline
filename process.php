<?php
include "src/lib.php";
include "teacher/lib.php";
$fn = "src/process/" . basename($_REQUEST['actiontype']) . "/" . basename($_REQUEST['action']) . ".php";

if (file_exists($fn)) {
    include($fn);
}
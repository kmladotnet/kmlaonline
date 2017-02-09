<?php
date_default_timezone_set("Asia/Seoul");
include "presentool/PresentTool.php";

session_start();
$dbInit = true;
require "db-config.php";
session_write_close();
?>
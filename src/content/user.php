<?php
$sub=basename($_GET['sub']);
if(!file_exists("src/content/user/$sub.php")) return die404();
if(file_exists("js/content/user/$sub.js")) $includes[]="/js/content/user/$sub.js";
if($is_mobile && file_exists("css/content/user/$sub.mobile.css")) $includes[]="/css/content/user/$sub.mobile.css";
else if(file_exists("css/content/user/$sub.css")) $includes[]="/css/content/user/$sub.css";
include "src/content/user/$sub.php";
<?php
$sub=basename($_GET['sub']);
if(!file_exists("src/content/util/$sub.php")) return die404();
if(file_exists("js/content/util/$sub.js")) $includes[]="/js/content/util/$sub.js";
if(file_exists("css/content/util/$sub.css")) $includes[]="/css/content/util/$sub.css";
if(file_exists("js/converse/bootstrap/dist/css/$sub.css")) $includes[] = "/js/converse/bootstrap/dist/css/$sub.css";
include "src/content/util/$sub.php";
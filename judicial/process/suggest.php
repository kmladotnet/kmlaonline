<?php
    include("../lib.php");
    if($_GET['q']) $q = $_GET['q'];
    else $q = "";
    echo suggestMemberByQuery($q);
?>
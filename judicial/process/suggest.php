<?php
    include("../lib.php");
    if(isset($_GET['q'])) $q = $_GET['q'];
    else $q = "";
    echo suggestMemberByQuery($q);
?>
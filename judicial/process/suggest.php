<?php
    include("../lib.php");
    $q = $_GET['q'];
    if(!$q) $q = "";
    echo suggestMemberByQuery($q);
?>
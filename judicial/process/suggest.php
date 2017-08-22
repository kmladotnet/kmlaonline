<?php
    include("../lib.php");
    if(!($q = $_GET['q'])) $q = "";
    suggestMemberByQuery($q);
?>
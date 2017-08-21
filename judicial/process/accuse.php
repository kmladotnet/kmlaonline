<?php
    include("../lib.php");
    $data = json_decode(file_get_contents("php://input"));

    addCourtArticle(317, "2017-08-21", 75, 3);
?>
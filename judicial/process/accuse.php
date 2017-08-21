<?php
    include("../lib.php");
    $data = json_decode(file_get_contents("php://input"));

    $article->addCourtArticle(317, "2017-08-21", 75, 3);
    $member->accuserName2Id("김명순");
?>
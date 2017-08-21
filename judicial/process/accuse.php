<?php
    include("../lib.php");
    $data = json_decode(file_get_contents("php://input"));

    $article->addCourtArticle(317, "2017-08-21", 75, 3);
    $accuser->accuserName2Id("김명순");
    $article_kind->articleDesc2Id("Late for school");
    createNewArticle(12, "김현재", "김명순", "Late for school");
?>
<?php
    include("../lib.php");
    $temp = $article->getAllRawArticles();
    while($row = $temp->fetch_assoc()){
        echo $row['accuser_id'] . "<br/>";
    }
?>
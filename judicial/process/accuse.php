<?php
    include("../lib.php");
    $data = json_decode(file_get_contents("php://input"));
    $data_grade = $data->grade;
    $data_name = $data->name;
    $data_date = $data->accuse_date;
    $data_accuser = $data->accuser;
    $data_article = $data->article;
    $data_point = $data->point;

    createNewArticle($data_grade, $data_name, $data_accuser, $data_article);
?>
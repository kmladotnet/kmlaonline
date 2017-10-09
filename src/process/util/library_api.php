<?php
    if(isset($_SESSION['user'])){
        $opts = array(
            'http'=>array(
                'method'=>'GET',
                'header'=>'X-Naver-Client-Id: UBWiQy6YaPCYeziwL2JW' .
                    'X-Naver-Client-Secret: InvxlYEdmf'
            )
        );
        $context = stream_context_create($opts);

        if(isset($_GET['query'])) $query = $_GET['query'];
        else $query = '';

        $file = file_get_contents('https://openapi.naver.com/v1/search/book.json?query=' . $query, false, $context);

        echo json_encode($file);
    } else {
        http_response_code(403);
    }
?>
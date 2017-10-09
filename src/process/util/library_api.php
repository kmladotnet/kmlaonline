<?php
    if(isset($_SESSION['user'])){

        if(isset($_GET['query'])) $query = utf8_encode($_GET['query']);
        else $query = '';

        $ch = curl_init();
        $url = 'https://openapi.naver.com/v1/search/book.json?query=' . $query;

        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $headers = array(
            "X-Naver-Client-Id: UBWiQy6YaPCYeziwL2JW",
            "X-Naver-Client-Secret: InvxlYEdmf"
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        //echo $output;
        echo $query;
    } else {
        http_response_code(403);
    }
?>
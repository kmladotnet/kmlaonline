<?php
    if(isset($_SESSION['user'])){
        /*
        if(isset($_GET['query'])) $query = urlencode($_GET['query']);
        else $query = '';

        $ch = curl_init();
        $url = 'https://openapi.naver.com/v1/search/book.json?query=' . $query;

        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $headers = array(
            "Access-Control-Allow-Origin: *",
            "X-Naver-Client-Id: UBWiQy6YaPCYeziwL2JW",
            "X-Naver-Client-Secret: InvxlYEdmf"
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        print curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        echo $output;
        echo $response; */
        $_h = curl_init();
        curl_setopt($_h, CURLOPT_HEADER, 1);
        curl_setopt($_h, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($_h, CURLOPT_HTTPGET, 1);
        curl_setopt($_h, CURLOPT_URL, 'http://www.minjok.hs.kr' );
        curl_setopt($_h, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
        curl_setopt($_h, CURLOPT_DNS_CACHE_TIMEOUT, 2 );

        var_dump(curl_exec($_h));
        var_dump(curl_getinfo($_h));
        var_dump(curl_error($_h));
    } else {
        http_response_code(403);
    }

    /*
    Openssl default config => /etc/ssl/openssl.cnf

    Directive => Local Value => Master Value
openssl.cafile => no value => no value
openssl.capath => no value => no value

    */
?>


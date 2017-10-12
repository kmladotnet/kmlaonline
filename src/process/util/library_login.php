<?php
    if(isset($_SESSION['user'])){
        if(isset($_GET['pwd'])) $pwd = $_GET['pwd'];
        else $query = '';

        $ch = curl_init();
        $url = 'http://lib.minjok.hs.kr/usweb/set16/USMN012.asp?mnid=' . $member->getAdditionalData($me['n_id'], 'n_student_id') . "&mnpw=" . $pwd;

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $headers = array(
            "Access-Control-Allow-Origin: *",
            "Content-Length: 0"
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        print curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        var_dump(curl_getinfo($ch));
        //echo htmlspecialchars_decode($output) . "\n" . $url . "\n" . $httpCode . "\n" .  $member->getAdditionalData($me['n_id'], 'n_student_id');
        echo $output;
        /* for debug
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
        */
    } else {
        http_response_code(403);
    }
?>


<?php
    if(isset($_SESSION['user'])){
        if(isset($_GET['pwd'])) $pwd = $_GET['pwd'];
        else $query = '';

        $ch = curl_init();
        $n_student_id = $member->getAdditionalData($me['n_id'], 'n_student_id');
        $url = 'http://lib.minjok.hs.kr/usweb/set16/USMN012.asp?mnid=' . $n_student_id . "&mnpw=" . $pwd;

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');

        curl_setopt($ch, CURLOPT_URL, $url);
        //$test = '/tmp/library/' . $n_student_id . '.txt';
        //echo $test;
        //file_put_contents($test, "data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "$n_student_id");
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/library');
        $headers = array(
            "Access-Control-Allow-Origin: *",
            "Content-Length: 0",
            "Connection: Keep-Alive",
            "Content-type: application/x-www-form-urlencoded;charset=EUC-kr"
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($ch);


        curl_setopt($ch, CURLOPT_URL, 'http://lib.minjok.hs.kr/usweb/set16/USMN000_16.asp');
        curl_setopt($ch, CURLOPT_POST, false);
        $output = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        curl_setopt($ch, CURLOPT_URL, 'http://lib.minjok.hs.kr/usweb/set16/USMN510.asp');
        $output2 = curl_exec($ch);

        //var_dump(curl_error($ch));
        curl_close($ch);
        $encoded_output = mb_convert_encoding($output, "UTF-8", "EUC-KR");
        mb_convert_encoding($output2, "UTF-8", "EUC-KR");

        $dom = new DOMDocument('1.0', 'utf-8');
        @$dom->loadHTML($output);
        $login_box = $dom->getElementById('mbody32');
        echo $login_box->nodeValue;

        $dom2 = new DOMDocument('1.0', 'utf-8');
        @$dom2->loadHTML($output2);
        $table = $dom2->getElementsByTagName('table');
        $rows = $table->item(1)->getElementsByTagName('tr');

        var_dump($rows);
        $tmp_arr = array();
        for($i = 0; $i < 3; $i++){
            $tmp = array();
            $row = $rows->item($i + 1);
            var_dump($row);
            $items = $row->getElementsByTagName('td');
            var_dump($items);
            foreach($items as $item){
                array_push($tmp, $item);
            }
            array_push($tmp_arr, $tmp);
        }

        echo json_encode($tmp_arr);
        //var_dump($login_box);
        //echo $encoded_output;
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


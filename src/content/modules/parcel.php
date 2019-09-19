<?php
    echo "테스트";
    echo date("U", strtotime("-1 day"));
    
        $parcel = getTodayParcel();
        if($parcel)
            echo '오늘택배올라옴';
        else
            echo '아님';
    
?>
<?php
    if(isset($_SESSION['user'])){
        $parcel = getTodayParcel();
        if($parcel)
            echo '오늘택배올라옴';
        else
            echo '아님';

    }
?>
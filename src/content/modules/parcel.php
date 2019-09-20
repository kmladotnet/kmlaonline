<?php
    //echo date("U", strtotime("-1 day"));
    /*
        $parcel = getTodayParcel();
        if($parcel)
        echo '오늘택배올라옴';
        else
        echo '아님';
        */
    $parcel = getTodayParcel();
    if($parcel !== null)
    {
        //$numParcel = parcelNum($me['s_name'], $parcel);
        $numParcel = parcelNum($me['s_name'], $parcel);
        
            echo $numParcel;

    }
    else
    { 
        ?>
    <div style="line-height: 1em;" class="parcel-icon">
        ?
    </div>
    <div style="text-align: center; font-size: 13pt;">
        <a href="/board/everyday_parcel">택배리스트</a>
    </div>
<?php }
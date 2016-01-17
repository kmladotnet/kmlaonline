<ul id="js-news" class="js-hidden">
    <?php
    $f=file_get_contents("http://www.minjok.hs.kr/app/kumla_notice/kumla_all.html");
    if(preg_match_all('/<li>\[([^\]]+):([^\]]+)\](.*?)<br>/sim', $f, $m, PREG_SET_ORDER)){
        $length = count($m);
        for ($i = 0; $i < $length - 3; $i++) {
            $each = $m[$i];

            $type=trim($each[1]);
            $date=trim($each[2]);
            $msg=trim(strip_tags($each[3]));
            ?><li class="news-item"><i class="fa fa-caret-right"></i><a href="http://www.minjok.hs.kr/members/" target="_new" style="color:black"><?php echo "<b>[$date] $type</b>: $msg"?></a></li><?php
        }
    }
    ?>
</ul>

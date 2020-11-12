<div class="minjok-news">
    <?php
    $f=file_get_contents("https://www.minjok.hs.kr/app/kumla_notice/kumla_all.html");
    if(preg_match_all('/<li>\[([^\]]+):([^\]]+)\](.*?)<br>/sim', $f, $m, PREG_SET_ORDER)){
        $length = count($m);
        for ($i = 0; $i < $length - 2; $i++) {
            $each = $m[$i];

            $type=trim($each[1]);
            $date=trim($each[2]);
            $msg=str_replace('&amp;nbsp;', '', htmlspecialchars(trim(strip_tags($each[3])), ENT_IGNORE));
            echo "<b>[$date] $type</b>";
            ?>
            <a href="http://www.minjok.hs.kr/members/" target="_new" style="color: black; white-space: nowrap;">
<?php echo $msg;?></a>
            <br>
            <?php
        }
    }
    ?>
</div>

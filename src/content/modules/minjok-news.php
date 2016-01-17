<dl class="dl-horizontal">
    <?php
    $f=file_get_contents("http://www.minjok.hs.kr/app/kumla_notice/kumla_all.html");
    if(preg_match_all('/<li>\[([^\]]+):([^\]]+)\](.*?)<br>/sim', $f, $m, PREG_SET_ORDER)){
        $length = count($m);
        for ($i = 0; $i < $length - 2; $i++) {
            $each = $m[$i];

            $type=trim($each[1]);
            $date=trim($each[2]);
            $msg=trim(strip_tags($each[3]));
            ?>
            <dt style="width: 90px">
                <?php echo "[$date] $type";?>
            </dt>
            <dd style="margin-left: 100px">
                <a href="http://www.minjok.hs.kr/members/" target="_new"><?php echo "$msg"?></a>
            </dd>
            <?php
        }
    }
    ?>
</dl>

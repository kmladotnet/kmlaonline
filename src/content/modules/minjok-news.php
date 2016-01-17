<dl class="dl-horizontal" style="overflow: hidden;">
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
            <dt style="width: 90px; margin-bottom: 8px">
                <?php echo "[$date] $type";?>
            </dt>
            <dd style="margin-left: 100px">
                <a href="http://www.minjok.hs.kr/members/" target="_new" style="color: black; white-space: nowrap;"><?php echo str_replace('&nbsp;', '', htmlspecialchars($msg)); echo htmlspecialchars($msg);?></a>
            </dd>
            <?php
        }
    }
    ?>
</dl>

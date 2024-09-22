<?php
redirectLoginIfRequired();

$title="노래방 이용 신청 - " . $title;

function x_week_range($ts) {
    $start = (date('w', $ts) == 1) ? $ts : strtotime('last monday', $ts);
    return array($start, strtotime('next sunday', $start));
}

function getCurrentTable(){
    global $mysqli;
    $week_range = x_week_range(time());
    $query = "SELECT * FROM kmlaonline_karaoke_table";
    if($res = $mysqli->query($query)){
        $arr = array();
        while ($row = $res->fetch_array(MYSQLI_ASSOC)){
            $arr[$row['n_date']][$row['n_period']] = array($row['s_objective'], $row['n_who']);
        }
        $res->close();
        if($mysqli->more_results()){
            $mysqli->next_result();
        }
        return $arr;
    }
}

function printContent(){
    global $member, $me;
    $week_range = x_week_range(time());
    $day = $week_range[0];
    $dayNames = array("일", "월", "화", "수", "목", "금", "토");
    $periodNames = array("점심", "저녁", "오전 08시 - 오전 09시", "오전 09시 - 오전 10시", "오전 10시 - 오전 11시", "오전 11시 - 오후 12시", "오후 12시 - 오후 01시", "오후 01시 - 오후 02시", "오후 02시 - 오후 03시", "오후 03시 - 오후 04시", "오후 04시 - 오후 05시", "오후 05시 - 오후 06시", "오후 06시 - 오후 07시");
    $currentTable = getCurrentTable();
    ?>
    <h1 style="padding:9px;text-align:center;"><!--img alt="노래방 이용 신청" src="/data/boardimg/karaoke.png" /--></h1>
    <div style="float:left">
        <?php
        if(isUserPermitted($me['n_id'], "karaoke_manager")){
            ?>
            <table>
                <tr>
                    <td>
                        <form method="post" action="/proc/util/karaoke" onsubmit="if(confirm('정말로 초기화하시겠습니까?')) return saveAjax(this,'비우는 중...'); return false;">
                            <input type="hidden" name="util_action" value="clear_week" />
                            <input type="submit" value="초기화" /></td>
                        </form>
                    </td>
                </tr>
            </table>
            <?php
        }
        ?>
    </div>
    <div style="text-align:right;">
        노래방 마스터: 28기 이송하<br />
        매주 월요일 0시 5분에 초기화됩니다.<br />
        1. 만약 노래방을 사용하지 않는다면 혼란이 생기지 않게 바로 취소해주세요. <br />
        2. 노래방의 청결 상태가 문제된다면 예약자 명단을 통해 책임자를 적발할 예정입니다. <br />
        3. 노래방 이용은 석식신청의 사유에 해당되지 않습니다 <br />

    </div>
    <div style="clear:both;padding:5px;"></div>
    <table id="reservation_table">
        <thead>
            <tr style="background:#DDD">
                <th style="width:90px;text-align:center;">날짜</th>
                <th style="width:180px;text-align:center;">시간</th>
                <th style="text-align:center;">사용자 명단</th>
                <th style="width:100px;text-align:center;">신청자</th>
                <th style="width:110px;"></th>
            </tr>
        </thead>
        <tbody>
            <?php for($wk = 0; $wk < 5; $wk++){ ?>
                <?php for($tm = 0; $tm < 2; $tm++){ ?>
                    <tr style="background:#<?php echo $tm % 2 == 0? "FFF" : "F8F8F8" ?>;">
                        <?php if($tm == 0){ ?>
                            <td rowspan="2" style="text-align:center;padding:3px;background:<?php echo date("Y-m-d",$day) == date("Y-m-d") ? "gold" : ($wk % 2 == 0 ? "#FFF" : "#F8F8F8") ?>;text-align:right;">
<?php echo date("Y-m-d",$day) . "<br /><span style='text-align:center;font-size:12pt;font-weight:bold'>" . $dayNames[date("w",$day)] . "</span>"; ?></td>
                        <?php } ?>
                        <td rowspan="1" style="padding:3px;text-align:center;background:#<?php echo $tm % 2 == 0 ? "FFF" : "F8F8F8" ?>">
<?php echo $tm == 0 ? "점심 시간" : "저녁 시간"; ?></td>
                        <?php if(isset($currentTable[$wk][$tm])){
                            $usr = $member->getMember($currentTable[$wk][$tm][1]);
                            ?>
                            <td>
<?php echo htmlspecialchars($currentTable[$wk][$tm][0]); ?></td>
                            <td style='text-align:center;<?php if($usr['n_id'] == $me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>">
<?php putUserCard($usr); ?></a></td>
                            <td>
                                <?php if($me['n_id'] == $currentTable[$wk][$tm][1] || isUserPermitted($me['n_id'], "karaoke_manager")){ ?>
                                <form method="post" action="/proc/util/karaoke" onsubmit="if(confirm('정말로 신청을 취소하겠습니까?'))return saveAjax(this,'신청 취소 중...'); return false;">
                                    <input type="hidden" name="day" value="<?php echo $wk?>" />
                                    <input type="hidden" name="period" value="<?php echo $tm?>" />
                                    <input type="hidden" name="util_action" value="remove" />
                                    <input type="submit" value="취소" />
                                </form>
                                <?php } ?>
                            </td>
                        <?php }else{ ?>
                            <form method="post" action="/proc/util/karaoke" onsubmit="return saveAjax(this,'신청 중...');">
                                <input type="hidden" name="day" value="<?php echo $wk?>" />
                                <input type="hidden" name="period" value="<?php echo $tm?>" />
                                <input type="hidden" name="util_action" value="add" />
                                <td>
                                    <input type="text" name="s_objective" value="" onkeydown="this.onchange();" onkeyup="this.onchange();" onchange="$('#label_reservation_long_period_<?php echo "$day-$tm"?>, #reservation_add_btn_<?php echo "$day-$tm"?>').css('visibility',this.value.length==0?'hidden':'visible');" style="width:100%;box-sizing:border-box;background:none;border:1px solid gray;" />
                                </td>
                                <td></td>
                                <td>
                                    <input id="reservation_add_btn_<?php echo "$day-$tm"?>" type="submit" value="추가" style="visibility:hidden" />
                                </td>
                            </form>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php $day = strtotime("next day", $day); } ?>
            <?php for($wk = 5; $wk < 7; $wk++){ ?>
                <?php for($tm = 2; $tm < 13; $tm++){ ?>
                    <tr style="background:#<?php echo $tm % 2 == 0 ? "FFF" : "F8F8F8" ?>;">
                        <?php if($tm == 2){ ?>
                            <td rowspan="11" style="text-align:center;padding:3px;background:<?php echo date("Y-m-d",$day)==date("Y-m-d")?"gold":($wk % 2 == 0?"#FFF":"#F8F8F8") ?>;text-align:right;">
<?php echo date("Y-m-d",$day) . "<br /><span style='text-align:center;font-size:12pt;font-weight:bold'>" . $dayNames[date("w",$day)] . "</span>"; ?></td>
                        <?php } ?>
                        <td rowspan="1" style="padding:3px;text-align:center;background:#<?php echo ($tm + $wk + 1)%2==0?"FFF":"F8F8F8" ?>">
<?php echo $periodNames[$tm]; ?></td>
                        <?php if(isset($currentTable[$wk][$tm])){
                            $usr=$member->getMember($currentTable[$wk][$tm][1]);
                            ?>
                            <td>
<?php echo htmlspecialchars($currentTable[$wk][$tm][0]); ?></td>
                            <td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>">
<?php putUserCard($usr); ?></a></td>
                            <td>
                                <?php if($me['n_id']==$currentTable[$wk][$tm][1] || isUserPermitted($me['n_id'], "karaoke_manager")){ ?>
                                    <form method="post" action="/proc/util/karaoke" onsubmit="if(confirm('정말로 신청을 취소하겠습니까?'))return saveAjax(this,'신청 취소 중...'); return false;">
                                        <input type="hidden" name="day" value="<?php echo $wk?>" />
                                        <input type="hidden" name="period" value="<?php echo $tm?>" />
                                        <input type="hidden" name="util_action" value="remove" />
                                        <input type="submit" value="취소" />
                                    </form>
                                <?php } ?>
                            </td>
                        <?php }else{ ?>
                            <form method="post" action="/proc/util/karaoke" onsubmit="return saveAjax(this,'신청 중...');">
                                <input type="hidden" name="day" value="<?php echo $wk?>" />
                                <input type="hidden" name="period" value="<?php echo $tm?>" />
                                <input type="hidden" name="util_action" value="add" />
                                <td>
                                    <input type="text" name="s_objective" value="" onkeydown="this.onchange();" onkeyup="this.onchange();" onchange="$('#label_reservation_long_period_<?php echo "$day-$tm"?>, #reservation_add_btn_<?php echo "$day-$tm"?>').css('visibility',this.value.length==0?'hidden':'visible');" style="width:100%;box-sizing:border-box;background:none;border:1px solid gray;" />
                                </td>
                                <td></td>
                                <td>
                                    <input id="reservation_add_btn_<?php echo "$day-$tm"?>" type="submit" value="추가" style="visibility:hidden" />
                                </td>
                            </form>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php $day = strtotime("next day", $day); } ?>
        </tbody>
    </table>
    <?php
}

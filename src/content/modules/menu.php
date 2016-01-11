<div style="clear:right;display:block;height:7px;"></div>
<div style="text-align:center">
    <div style="font-size:9pt;float:right;height:15pt;padding-top:3pt;">
        <a <?php if($is_morning) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-breakfast');">아침</a> |
        <a <?php if($is_afternoon) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-lunch');">점심</a> |
        <a <?php if($is_night) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-dinner');">저녁</a> |
        <a href="/util/schedule?<?php echo "year=$curYear&amp;month=$curMonth&amp;mode=food:0"?>">모두 보기</a>
    </div>
    <?php
    $curYear=date("Y"); $curMonth=date("n"); $curDay=date("j");
    if($is_morning && date("H")>=22){
        $curYear = date("Y", strtotime("+1 day"));
        $curMonth = date("m", strtotime("+1 day"));
        $curDay = date("d", strtotime("+1 day"));
    }
    $query="SELECT s_mode, s_data FROM kmlaonline_schedule_table WHERE n_year=$curYear AND n_month=$curMonth AND n_day=$curDay";
    if($res=$mysqli->query($query)){
        while ($row = $res->fetch_array(MYSQLI_ASSOC)){
            $scheduleData[$row['s_mode']]=$row['s_data'];
        }
        $res->close();
        if($mysqli->more_results())$mysqli->next_result();
    }
    echo "<div style='font-weight:bold;font-size:11pt;padding:4px;'>{$curYear}년 {$curMonth}월 {$curDay}일</div>";
    ?>
    <div id="food-breakfast" class="morning"><?php echo isset($scheduleData['food:0'])?nl2br($scheduleData['food:0']):"<span style='color:#DDD'>(입력되지 않음)</span>"; ?></div>
    <div id="food-lunch" class="afternoon"><?php echo isset($scheduleData['food:1'])?nl2br($scheduleData['food:1']):"<span style='color:#DDD'>(입력되지 않음)</span>"; ?></div>
    <div id="food-dinner" class="night"><?php echo isset($scheduleData['food:2'])?nl2br($scheduleData['food:2']):"<span style='color:#DDD'>(입력되지 않음)</span>"; ?></div>
</div>

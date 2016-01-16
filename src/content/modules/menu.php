<?php
function printMenu($allDay = false) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $curYear, $curMonth, $curDay;
?>
    <div style="text-align:center">
        <?php
        if(!$allDay) {
        ?>
            <div>
                <a <?php if($is_morning) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-breakfast');">아침</a> |
                <a <?php if($is_afternoon) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-lunch');">점심</a> |
                <a <?php if($is_night) echo 'style="color:black"'; ?> onclick="main_changeFood(this, 'food-dinner');">저녁</a>
            </div>
        <?php
        }
        $query="SELECT s_mode, s_data FROM kmlaonline_schedule_table WHERE n_year=$curYear AND n_month=$curMonth AND n_day=$curDay";
        if($res=$mysqli->query($query)){
            while ($row = $res->fetch_array(MYSQLI_ASSOC)){
                $scheduleData[$row['s_mode']]=$row['s_data'];
            }
            $res->close();
            if($mysqli->more_results())$mysqli->next_result();
        }
        echo "<div style='font-weight:bold;font-size:11pt;padding:4px;'>{$curMonth}월 {$curDay}일</div>";
        ?>
        <div <?php if(!$allDay) echo 'id="food-breakfast" class="morning"'; else echo 'class="food"';?>>
            <?php
                if($allDay) {
                    ?>
                    <div class="food-header">
                        아침
                    </div>
                    <?php
                }
                echo isset($scheduleData['food:0'])?nl2br($scheduleData['food:0']):"<span style='color:#DDD'>(입력되지 않음)</span>";
            ?>
        </div>
        <div <?php if(!$allDay) echo 'id="food-lunch class="afternoon"'; else echo 'class="food"';?>>
            <?php
                if($allDay) {
                    ?>
                    <div class="food-header">
                        점심
                    </div>
                    <?php
                }
                echo isset($scheduleData['food:1'])?nl2br($scheduleData['food:1']):"<span style='color:#DDD'>(입력되지 않음)</span>";
            ?>
        </div>
        <div <?php if(!$allDay) echo 'id="food-dinner" class="night"'; else echo 'class="food"';?>>
            <?php
                if($allDay) {
                    ?>
                    <div class="food-header">
                        저녁
                    </div>
                    <?php
                }
                echo isset($scheduleData['food:2'])?nl2br($scheduleData['food:2']):"<span style='color:#DDD'>(입력되지 않음)</span>";
            ?>
        </div>
    </div>
    <?php
}
?>

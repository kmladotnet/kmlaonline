<?php

// 중간에 printFood() -> lib.php

function printMenu($allDay = false) {
	global $member, $me, $is_morning, $is_afternoon, $is_night, $mysqli, $curYear, $curMonth, $curDay, $foodJSON;
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
        } ?>
		<div style='font-weight:bold;font-size:11pt;'>
			<a class="btn btn-default btn-xs" style="
			    border: none;
			    color: transparent!important;
			    background: transparent!important;
			">분포</a>
        	<?php echo " {$curMonth}월 {$curDay}일 "; ?>
			<a class="btn btn-default btn-xs" onclick="
				if($(this).text().indexOf('분') != -1) {
				    $('ul.food-chart').velocity('slideDown', {duration: 200, easing: 'ease'});
				    $(this).html('닫기');
				} else {
				    $('ul.food-chart').velocity('slideUp', {duration: 200, easing: 'ease'});
				    $(this).html('분포');
				}">분포
			</a>
		</div>
        <div <?php if(!$allDay) echo 'id="food-breakfast" class="morning"'; else echo 'class="food"';?>>
            <?php
                if($allDay) {
                    ?>
                    <div class="food-header">
                        아침
                    </div>
                    <?php
                }
                $voteData = getFoodVoteData($curYear, $curMonth, $curDay, 1);
				$voteCount = getFoodVoteCount($curYear, $curMonth, $curDay, 1);
                ?>
                <div class="food-votes">
                    <?php if($voteData['count'] > 0) { ?>
                        <span style="font-size: 1.15em">
<?php echo(round($voteData['sum'] / $voteData['count'], 1));?>점</span>
                        (<?php echo $voteData['count']; ?>명)
                    <?php } else { ?>
                        평점 없음
                    <?php } ?>
                </div>
                <div class="rate" id="fv<?php echo $curYear,$curMonth,$curDay;?>1" style="inline-block"></div>
                <button type="button" id="b<?php echo $curYear,$curMonth,$curDay;?>1" onclick="foodVotes(1)" class="btn btn-default btn-xs">제출</button>
                <hr style="margin-top: 5px;margin-bottom: 5px;">
				<?php if($voteData['count'] > 0) { ?>
					<ul class="food-chart">
						<?php for($i = 5; $i >= 0; $i--) {
							echo '<li class="food-chart-item food-'.$i.'" style="width: '.intval(100 * $voteCount[$i] / $voteData['count']).'%"></li>';
						} ?>
					</ul>
                <?php }
                printFood($foodJSON, $curMonth, $curDay, "breakfast");
                // echo isset($scheduleData['food:0'])?nl2br($scheduleData['food:0']):"<span style='color:#DDD'>(입력되지 않음)</span>";
            ?>
        </div>
        <div <?php if(!$allDay) echo 'id="food-lunch" class="afternoon"'; else echo 'class="food"';?>>
            <?php
                if($allDay) {
                    ?>
                    <div class="food-header">
                        점심
                    </div>
                    <?php
                }
                $voteData = getFoodVoteData($curYear, $curMonth, $curDay, 2);
				$voteCount = getFoodVoteCount($curYear, $curMonth, $curDay, 2);
                ?>
                <div class="food-votes">
                    <?php if($voteData['count'] > 0) { ?>
                        <span style="font-size: 1.15em">
<?php echo(round($voteData['sum'] / $voteData['count'], 1));?>점</span>
                        (<?php echo $voteData['count']; ?>명)
                    <?php } else { ?>
                        평점 없음
                    <?php } ?>
                </div>
                <div class="rate" id="fv<?php echo $curYear,$curMonth,$curDay;?>2" style="inline-block"></div>
                <button type="button" id="b<?php echo $curYear,$curMonth,$curDay;?>2" onclick="foodVotes(2)" class="btn btn-default btn-xs">제출</button>
                <hr style="margin-top: 5px;margin-bottom: 5px;">
				<?php if($voteData['count'] > 0) { ?>
					<ul class="food-chart">
						<?php for($i = 5; $i >= 0; $i--) {
							echo '<li class="food-chart-item food-'.$i.'" style="width: '.intval(100 * $voteCount[$i] / $voteData['count']).'%"></li>';
						} ?>
					</ul>
                <?php }
                printFood($foodJSON, $curMonth, $curDay, "lunch");
                // echo isset($scheduleData['food:1'])?nl2br($scheduleData['food:1']):"<span style='color:#DDD'>(입력되지 않음)</span>";
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
                $voteData = getFoodVoteData($curYear, $curMonth, $curDay, 3);
				$voteCount = getFoodVoteCount($curYear, $curMonth, $curDay, 3);
                ?>
                <div class="food-votes">
                    <?php if($voteData['count'] > 0) { ?>
                        <span style="font-size: 1.15em">
<?php echo(round($voteData['sum'] / $voteData['count'], 1));?>점</span>
                        (<?php echo $voteData['count']; ?>명)
                    <?php } else { ?>
                        평점 없음
                    <?php } ?>
                </div>
                <div class="rate" id="fv<?php echo $curYear,$curMonth,$curDay;?>3" style="inline-block"></div>
                <button type="button" id="b<?php echo $curYear,$curMonth,$curDay;?>3" onclick="foodVotes(3)" class="btn btn-default btn-xs">제출</button>
                <hr style="margin-top: 5px;margin-bottom: 5px;">
				<?php if($voteData['count'] > 0) { ?>
					<ul class="food-chart">
						<?php for($i = 5; $i >= 0; $i--) {
							echo '<li class="food-chart-item food-'.$i.'" style="width: '.intval(100 * $voteCount[$i] / $voteData['count']).'%"></li>';
						} ?>
					</ul>
                <?php }
                printFood($foodJSON, $curMonth, $curDay, "dinner");
                // echo isset($scheduleData['food:2'])?nl2br($scheduleData['food:2']):"<span style='color:#DDD'>(입력되지 않음)</span>";
            ?>
        </div>
    </div>
    <script>
        curYear = <?php echo $curYear; ?>;
        curMonth = <?php echo $curMonth; ?>;
        curDay = <?php echo $curDay; ?>;
        rating = [ 0,
            <?php echo getMyFoodVote($curYear, $curMonth, $curDay, 1, $me['n_id']); ?>,
            <?php echo getMyFoodVote($curYear, $curMonth, $curDay, 2, $me['n_id']); ?>,
            <?php echo getMyFoodVote($curYear, $curMonth, $curDay, 3, $me['n_id']); ?>];
        $(function() {
            for(i = 1; i <= 3; i++) {
                $("#fv" + curYear + curMonth + curDay + i).rateYo({fullStar: true, starWidth: "16px", rating: rating[i]}).css({"display": "inline-block", "top":"3px"});
            }
        });
        function foodVotes(t) {
            $.post("ajax/user/foodvote", {
                "ajax": 1,
                "y": curYear,
                "m": curMonth,
                "d": curDay,
                "t": t,
                "stars": $("#fv" + curYear + curMonth + curDay + t).rateYo("rating")
            }, function() {
                $("#b" + curYear + curMonth + curDay + t).html("성공").removeClass("btn-default").addClass("btn-success");
                setTimeout(function() {
                    $("#b" + curYear + curMonth + curDay + t).html("제출").removeClass("btn-success").addClass("btn-default");
                }, 1000);
            });
        }
    </script>
<?php }

//later
/*
<ul class="food-chart">
	<li class="food-chart-item food-5" style="width: 80%;"></li>
	<li class="food-chart-item food-4" style="width: 8%;"></li>
	<li class="food-chart-item food-3" style="width: 4%;"></li>
	<li class="food-chart-item food-2" style="width: 5%;"></li>
	<li class="food-chart-item food-1" style="width: 7%;"></li>
	<li class="food-chart-item food-0" style="width: 11%;"></li>
</ul>
*/

?>

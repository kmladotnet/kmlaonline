<?php
$title="일정표 - $title";
function printContent(){
	global $member, $mysqli, $me, $is_android;
	$curYear=isset($_GET['year'])?$_GET['year']:date("Y");
	$curMonth=isset($_GET['month'])?$_GET['month']:date("n");
	$mode="normal";
	if(isset($_GET['mode'])){
		switch($_GET['mode']){
			case "food:0":case "food:1":case "food:2":case "normal":
			$mode=$_GET['mode'];
		}
	}
	if($curMonth<1 || $curMonth>12) $curMonth=date("n");
	if($curYear<1997) $curYear=1997;
	$firstWeekDayOfMonth=date("N", strtotime("$curYear-$curMonth-01"))%7;
	$daysOfMonth=date("t", strtotime("$curYear-$curMonth-01"));
	$currentDay=-$firstWeekDayOfMonth;
	$calender=array();
	for($i=-$firstWeekDayOfMonth, $j=0; $i<$daysOfMonth; $i++, $j++){
		if(!isset($calender[$j/7]))$calender[$j/7]=array();
		if($i>=0) $calender[$j/7][$j%7]=$i+1;
	}
	$scheduleData=array();
	$query="SELECT n_day, s_data FROM kmlaonline_schedule_table WHERE n_year=$curYear AND n_month=$curMonth AND s_mode='$mode'";
	if($res=$mysqli->query($query)){
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$scheduleData[$row['n_day']]=$row['s_data'];
		}
		$res->close();
		if($mysqli->more_results())$mysqli->next_result();
	}
	?>
    <script src="/js/content/util/schedule.js"></script>
	<div class="schedule-content">
        <?php if($is_android && substr($mode, 0, 5) == "food:" ) { ?>
            <div class="alert alert-info" role="alert">
                <h3>
                    <a href="https://play.google.com/store/apps/details?id=com.ldm2468.kmlafood">
                        <i class="fa fa-download" aria-hidden="true"></i> Google Play 스토어에서 큼온 식단 위젯을 받아보세요!
                    </a>
                </h3>
            </div>
        <?php } ?>

        <h2 style="text-align:center;"><img src="/images/food.png" style="width:64px;vertical-align:bottom;" />
            <div style="display: inline-block; vertical-align: text-bottom;">
                <select id="select-year" class="selectpicker" data-style="btn-default" data-width="80px" onchange="location = this.options[this.selectedIndex].value;">
                    <?php
                    for($i=$curYear - 5; $i <= date("Y"); $i++) {
                        if($i < 1997) continue;
                        ?>
                        <option value="/util/schedule?mode=<?php echo $mode?>&amp;year=<?php echo $i?>&amp;month=<?php echo $curMonth?>"
                                <?php if($i == $curYear) echo 'selected';?>>
                            <?php echo $i?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            년
            <div style="display: inline-block; vertical-align: text-bottom;">
                <select id="select-month" class="selectpicker" data-style="btn-default" data-width="60px" onchange="location = this.options[this.selectedIndex].value;">
                    <?php
                    for($i = 1; $i <= 12; $i++){
                        ?>
                        <option value="/util/schedule?mode=<?php echo $mode?>&amp;year=<?php echo $curYear?>&amp;month=<?php echo $i?>"
                                <?php if($i == $curMonth) echo 'selected';?>>
                            <?php echo $i?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            월
            <?php echo $mode=='food:0' ? '아침식단' : ($mode=="food:1" ? '점심식단' : ($mode=="food:2" ? '저녁식단' : '일정')); ?>
        </h2>
		<div style="float:right">
            <a href="http://hes.kwe.go.kr/sts_sci_md00_001.do?schulCode=K100000414&amp;schulCrseScCode=4&amp;schulKndScCode=04"> 나이스에서 식단 보기 </a> |
			<a style='font-weight:bold;<?php if($mode=="food:0") echo "color:black;"; ?>' href="/util/schedule?mode=food:0&amp;year=<?php echo $curYear?>&amp;month=<?php echo $curMonth?>">아침식단</a> | 
			<a style='font-weight:bold;<?php if($mode=="food:1") echo "color:black;"; ?>' href="/util/schedule?mode=food:1&amp;year=<?php echo $curYear?>&amp;month=<?php echo $curMonth?>">점심식단</a> | 
			<a style='font-weight:bold;<?php if($mode=="food:2") echo "color:black;"; ?>' href="/util/schedule?mode=food:2&amp;year=<?php echo $curYear?>&amp;month=<?php echo $curMonth?>">저녁식단</a>
		</div>
		<div style="float:left">
			<a style='font-weight:bold;<?php if($mode=="normal") echo "color:black;"; ?>' href="/util/schedule?mode=normal&amp;year=<?php echo $curYear?>&amp;month=<?php echo $curMonth?>">일정 보기</a>
		</div>
		<br />
		<table id="calender">
			<thead>
				<tr>
					<th>일요일</th>
					<th>월요일</th>
					<th>화요일</th>
					<th>수요일</th>
					<th>목요일</th>
					<th>금요일</th>
					<th>토요일</th>
				</tr>
			</thead>
			<tbody>
				<?php
				for($i=0;$i<count($calender);$i++){
					echo "<tr>";
					for($j=0;$j<7;$j++){
						$bg="";
						if(isset($calender[$i][$j])){
							$datename=$calender[$i][$j];
							if($curYear==date("Y") && $curMonth==date("n") && $datename==date("j"))
								$bg="background: gold";
						}
						if($j==6)
							echo "<td style='border-top:1px solid gray;'>";
						else
							echo "<td style='border-right:1px solid gray;border-top:1px solid gray;'>";
						if(!isset($calender[$i][$j])){
							echo "</td>";
							continue;
						}
						echo "<div style='$bg'>";
						echo "<span class='datename'>{$datename}</span>";
						
						if(substr($mode,0,5)!="food:" || isUserPermitted($me['n_id'], "edit_food_table")){ // (1) food가 아니거나(일정이거나) (2) edit_food_table 권한이 있는가?
							echo "<a class='edit' onclick='return util_schedule_goEdit(this);'>편집</a>";
						}
						
						echo "<div style='clear:both'></div>";
						echo "</div>";
						echo "<div class='divider'></div>";
						if($mode=="normal"){
							$minWave=date("Y")-1997;
							$births=array();
							foreach($member->listMembersBirth($curMonth, $datename) as $val){
								if($val['n_level']>=$minWave){
									$births[]="<a href='/user/view/{$val['n_id']}/{$val['s_id']}'>".putUserCard($val,0,false)."</a>";
								}
							}
							if(count($births)){
								echo "<div style='padding:3px 0 3px 0;'>";
								echo "<b>생일: </b>".implode(",",$births) . "<br />";
								echo "</div>";
								echo "<div class='divider'></div>";
							}
						}
						$curData=isset($scheduleData[$datename])?$scheduleData[$datename]:"";
						?>
						<form method='post' action='/ajax/util/schedule' onsubmit='return saveAjax(this,"저장 중...",null);' style='display:none'>
							<input type='hidden' name='util_action' value='editDate' />
							<input type="hidden" name="s_mode" value="<?php echo $mode?>" />
							<input type="hidden" name="n_year" value="<?php echo $curYear?>" />
							<input type="hidden" name="n_month" value="<?php echo $curMonth?>" />
							<input type="hidden" name="n_day" value="<?php echo $datename?>" />
							<textarea name='s_data' style='width:100%;height:100%;box-sizing:border-box;resize:none;' class='textareaautoresize'><?php echo htmlspecialchars($curData); ?></textarea>
							<div style='text-align:right'>
								<input type='button' value='취소' onclick='return util_schedule_cancelEdit(this);' />
								<input type='submit' value='저장' />
							</div>
							</form>
							<div style='width:100%;padding:0;margin:0;border:0;text-align:center;'><?php
								if(strlen($curData)>0)
									echo nl2br($curData);
								else if($mode=="normal")
									echo "<span style='color:#DDD'>(지정되지 않음)</span>";
								else
									echo "<span style='color:#DDD'>(입력되지 않음)</span>";
							?></div>
						</td>
						<?php
					}
					echo "</tr>";
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
}

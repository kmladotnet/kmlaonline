<?php
redirectLoginIfRequired();
$title="공동강의실 이용 신청 - " . $title;
function x_week_range($ts) {
    $start = (date('w', $ts) == 1) ? $ts : strtotime('last monday', $ts);
    return array($start, strtotime('next sunday', $start));
}
function getCurrentTable(){
	global $mysqli;
	$week_range=x_week_range(time());
	$query="SELECT * FROM kmlaonline_lectureroom_table";
	if($res=$mysqli->query($query)){
		$arr=array();$i=0;
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$arr[$row['n_date']][$row['n_period']][$row['n_floor']]=array($row['s_objective'], $row['n_who'], $row['n_long_period']);
		}
		$res->close();
		if($mysqli->more_results())$mysqli->next_result();
		return $arr;
	}
}
function printContent(){
	global $member, $me;
	$week_range=x_week_range(time());
	$day=$week_range[0];
	$dayNames=array("일","월","화","수","목","금","토");
	$floorNames=array("지하 1층", "2층", "4층", "10층", "충3");
	$currentTable=getCurrentTable();
	?>
	<h1 style="padding:9px;text-align:center;"><img alt="공동강의실 이용 신청" src="/data/boardimg/lectureroom.png" style="height: 100px"/></h1>
	<div style="float:left">
		<?php
		if(isUserPermitted($me['n_id'], "lectureroom_manager")){
			?>
			<table>
				<tr>
					<td>
						<form method="post" action="/proc/util/lectureroom" onsubmit="if(confirm('정말로 초기화하시겠습니까?')) return saveAjax(this,'비우는 중...'); return false;">
							<input type="hidden" name="util_action" value="clear_week" />
							<input type="submit" value="초기화" /></td>
						</form>
					</td>
					<td>
						<form method="post" action="/proc/util/lectureroom" onsubmit="if(confirm('정말로 전부 초기화하시겠습니까?')) return saveAjax(this,'전부 비우는 중...'); return false;">
							<input type="hidden" name="util_action" value="clear_week" />
							<input type="hidden" name="clear_everything" value="1" />
							<input type="submit" value="전부 초기화" /></td>
						</form>
					</td>
				</tr>
			</table>
			<?php
		}
		?>
	</div>
	<div style="text-align:right;">
		공강마스터: 26기 노준영<br />
		매주 월요일 0시에 초기화됩니다.
	</div>
	<div style="clear:both;padding:5px;"></div>
	<table id="reservation_table">
		<thead>
			<tr style="background:#DDD">
				<th style="width:90px;">날짜</th>
				<th style="width:50px;">시간</th>
				<th style="width:70px;">층</th>
				<th>목적</th>
				<th style="width:100px;">신청자</th>
				<th style="width:110px;"></th>
			</tr>
		</thead>
		<tbody>
			<?php for($wk=0;$wk<7;$wk++){ ?>
				<?php for($tm=0;$tm<2;$tm++){ ?>
					<?php for($floor=0;$floor<5;$floor++){ ?>
						<tr style="background:#<?php echo $floor%2==0?"FFF":"F8F8F8" ?>;">
							<?php if($tm==0 && $floor==0){ ?>
								<td rowspan="8" style="padding:3px;background:<?php echo date("Y-m-d",$day)==date("Y-m-d")?"gold":($wk%2==0?"#FFF":"#F8F8F8") ?>;text-align:right;">
<?php echo date("Y-m-d",$day) . "<br /><span style='font-size:12pt;font-weight:bold'>" . $dayNames[date("w",$day)] . "</span>"; ?></td>
							<?php } ?>
							<?php if($floor==0){ ?>
								<td rowspan="4" style="padding:3px;background:#<?php echo $tm%2==0?"FFF":"F8F8F8" ?>">
<?php echo ($tm+1)."자습"; ?></td>
							<?php } ?>
							<td style="padding:3px;">
<?php echo $floorNames[$floor]; ?></td>
							<?php if(isset($currentTable[$wk][$tm][$floor])){
								$usr=$member->getMember($currentTable[$wk][$tm][$floor][1]);
								?>
								<td>
<?php echo htmlspecialchars($currentTable[$wk][$tm][$floor][0]); if($currentTable[$wk][$tm][$floor][2]) echo " <span style='color:gray'>(장기)</span>"; ?></td>
								<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>">
<?php putUserCard($usr); ?></a></td>
								<td>
									<?php if($me['n_id']==$currentTable[$wk][$tm][$floor][1] || isUserPermitted($me['n_id'], "lectureroom_manager")){ ?>
										<form method="post" action="/proc/util/lectureroom" onsubmit="if(confirm('정말로 신청을 취소하겠습니까?'))return saveAjax(this,'신청 취소 중...'); return false;">
											<input type="hidden" name="day" value="<?php echo $wk?>" />
											<input type="hidden" name="period" value="<?php echo $tm?>" />
											<input type="hidden" name="floor" value="<?php echo $floor?>" />
											<input type="hidden" name="util_action" value="remove" />
											<input type="submit" value="취소" />
										</form>
									<?php } ?>
								</td>
							<?php }else{ ?>
								<form method="post" action="/proc/util/lectureroom" onsubmit="return saveAjax(this,'신청 중...');">
									<input type="hidden" name="day" value="<?php echo $wk?>" />
									<input type="hidden" name="period" value="<?php echo $tm?>" />
									<input type="hidden" name="floor" value="<?php echo $floor?>" />
									<input type="hidden" name="util_action" value="add" />
									<td>
										<input type="text" name="s_objective" value="" onkeydown="this.onchange();" onkeyup="this.onchange();" onchange="$('#label_reservation_long_period_<?php echo "$day-$tm-$floor"?>, #reservation_add_btn_<?php echo "$day-$tm-$floor"?>').css('visibility',this.value.length==0?'hidden':'visible');" style="width:100%;box-sizing:border-box;background:none;border:1px solid gray;" />
									</td>
									<td></td>
									<td>
										<input id="reservation_add_btn_<?php echo "$day-$tm-$floor"?>" type="submit" value="추가" style="visibility:hidden" />
										<label id="label_reservation_long_period_<?php echo "$day-$tm-$floor"?>" for="reservation_long_period_<?php echo "$day-$tm-$floor"?>" style="visibility:hidden"><input id="reservation_long_period_<?php echo "$day-$tm-$floor"?>" type="checkbox" name="n_long_period" value="1" />장기</label>
									</td>
								</form>
							<?php } ?>
						</tr>
					<?php } ?>
				<?php } ?>
			<?php $day=strtotime("next day",$day); } ?>
		</tbody>
	</table>
	<?php
}

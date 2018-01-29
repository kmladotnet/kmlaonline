<?php
redirectLoginIfRequired();
$title="교복 신청 - " . $title;
function getCurrentTable(){
	global $mysqli;
	$query="SELECT * FROM donation_test";
	if($res=$mysqli->query($query)){
		$arr=array();
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$arr[$row['n_category']][$row['n_num']] = array($row['s_title'], $row['n_who'], $row['s_status'], $row['s_type'], $row['s_owner']);
		}
		$res->close();
		if($mysqli->more_results())$mysqli->next_result();
		return $arr;
	}
}
function printContent(){
	global $member, $me;
	$currentTable=getCurrentTable();
	?>
	<font size=3 color="#FFB22222">
	</br>
	<b>기부물품 신청 페이지입니다.</b></br> 선배님들께 기부받은 물품들을 교내외에서 돈을  받고 팔거나, 기타 수단으로 사용되는 것을 금하며, 해당 활동 등이 적발될 시에는 학생회 차원을 넘어 엄히 처벌하겠습니다. 정각에 신청 가능합니다. </br>혹 오류로 인해 자정에 신청이 안보이실 경우 10분에 신청 가능하도록 조정하도록 하겠습니다.</br>
    <a href="/util/donation-book">서적 신청 목록</a>
	</font>
	</br>
	<style>
		td p{
			position: relative;
			top: 5px;
		}
	</style>
	<!-- category
		0 : 국내 문제집
		1 : 국제 문제집
		2 : 서적
		3 : 교과서
		4 : 생활용품
		5 : 여자 교복
		6 : 남자 교복
	 -->
	<div style="text-align:left;" id="5">
		<font size=5><b>여자 교복</b></font></br>
	</div>
	<?php $category = 5 ?>   <!--Category 5: 여자 교복 -->
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_etc" style="width: 100%;" class="table table-condensed table-striped">
		<thead>
			<tr>
				<th style="text-align: center; width:10%;">종류</th>
				<th style="text-align: center; width:40%;">색(장의+치마)</th>
				<th style="text-align: center; width:20%;">기부자</th>
                <th style="text-align: center; width:30%;">신청자</th>
			</tr>
		</thead>
		<tbody style="text-align: center;">
			<?php for($num = 1; $num <= sizeof($currentTable[$category]); $num++){ ?>
				<tr>
					<!-- 1. 종류 ex> 동복 -->
					<td><?php echo $currentTable[$category][$num][0]; ?></td>
					<!-- 2. 색깔 -->
					<td><?php echo $currentTable[$category][$num][3] ?></td>
					<!-- 3. owner -->
                    <td><?php echo $currentTable[$category][$num][4] ?></td>
					<!-- 4. 신청 or empty -->
					<?php if($currentTable[$category][$num][1] != 0){
					$usr=$member->getMember($currentTable[$category][$num][1]);
					?>
					<td style='text-align:center;<?php if($usr['n_id'] == $me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
					<td>
						<?php if($me['n_id']==$currentTable[$category][$num][1]){ ?>
							<form method="post" action="/proc/util/donation" onsubmit="if(confirm('정말로 신청을 취소하겠습니까?'))return saveAjax(this,'신청 취소 중...'); return false;">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="remove" />
								<input type="submit" value="취소" />
							</form>
						<?php } ?>
					</td>
					<?php } else { ?>
						<!-- if n_who is 0, 신청자가 없을 때 -->
						<td>신청자가 없습니다</td>
						<td>
		          		<?php $date1 = new DateTime("now"); $date2 = new DateTime("2016-03-04");
						if($date1 >= $date2) { ?>
							<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" class="btn btn-sm btn-default" value="신청" />
							</form>
						<?php } else { ?>
							<p style="width: 150px;">기한이 아닙니다</p>
						<?php } ?>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table
	</br>
    </br>

	<div style="text-align:left;" id="5">
		<font size=5><b>남자 교복</b></font></br>
	</div>
	<?php $category = 6 ?>   <!--Category 6: 남자 교복 -->
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_etc" style="width: 100%;" class="table table-condensed table-striped">
		<thead>
			<tr>
				<th style="text-align: center; width:10%;">종류</th>
				<th style="text-align: center; width:40%;">색(장의+바지)</th>
				<th style="text-align: center; width:20%;">기부자</th>
                <th style="text-align: center; width:30%;">신청자</th>
			</tr>
		</thead>
		<tbody style="text-align: center;">
			<?php for($num = 1; $num <= sizeof($currentTable[$category]); $num++){ ?>
				<tr>
					<!-- 1. 종류 ex> 동복 -->
					<td><?php echo $currentTable[$category][$num][0]; ?></td>
					<!-- 2. 색깔 -->
                    <td><?php echo $currentTable[$category][$num][3] ?></td>
					<!-- 3. owner -->
                    <td><?php echo $currentTable[$category][$num][4] ?></td>
					<!-- 4. 신청 or empty -->
					<?php if($currentTable[$category][$num][1] != 0){
					$usr=$member->getMember($currentTable[$category][$num][1]);
					?>
					<td style='text-align:center;<?php if($usr['n_id'] == $me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
					<td>
						<?php if($me['n_id']==$currentTable[$category][$num][1]){ ?>
							<form method="post" action="/proc/util/donation" onsubmit="if(confirm('정말로 신청을 취소하겠습니까?'))return saveAjax(this,'신청 취소 중...'); return false;">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="remove" />
								<input type="submit" value="취소" />
							</form>
						<?php } ?>
					</td>
					<?php } else { ?>
					<td>신청자가 없습니다</td>
					<td>
		          	<?php $date1 = new DateTime("now"); $date2 = new DateTime("2016-03-04");
					if($date1 >= $date2) { ?>
						<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
							<input type="hidden" name="category" value="<?php echo $category ?>" />
							<input type="hidden" name="num" value="<?php echo $num ?>" />
							<input type="hidden" name="util_action" value="add" />
							<input type="submit" class="btn btn-sm btn-default" value="신청" />
						</form>
					<?php } else { ?>
						<p style="width: 150px;">기한이 아닙니다</p>
					<?php } ?>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table
	</br>
    </br>
<?php } ?>

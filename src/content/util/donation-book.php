<?php
redirectLoginIfRequired();
$title="서적 신청 - " . $title;
function getCurrentTable(){
	global $mysqli;
	$query = "SELECT * FROM donation_test";
	// $query = "SELECT * FROM kmlaonline_donation_table";
    // fetch_array documentation http://php.net/manual/en/function.mysql-fetch-array.php
	if($res = $mysqli->query($query)){
		$arr = array();
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$arr[$row['n_category']][$row['n_num']]=array($row['s_title'], $row['n_who'], $row['s_status'], $row['s_type'], $row['s_owner']);
		}
		$res->close();
		if($mysqli->more_results())$mysqli->next_result();
		return $arr;
	}
}
function printContent(){
    // $member: $me:
	global $member, $me;
	$currentTable=getCurrentTable();
	?>
	<font size=3 color="#FFB22222">
	</br>
	<b>기부물품 신청 페이지입니다.</b></br> 선배님들께 기부받은 물품들을 교내외에서 돈을  받고 팔거나, 기타 수단으로 사용되는 것을 금하며, 해당 활동 등이 적발될 시에는 학생회 차원을 넘어 엄히 처벌하겠습니다. 정각에 신청 가능합니다. </br>혹 오류로 인해 자정에 신청이 안보이실 경우 10분에 신청 가능하도록 조정하도록 하겠습니다.</br>
    <a href="/util/donation-cloth">교복 신청 목록</a>
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
		3 : empty(deprecated, originally seems to have been uniform)
		4 : 교과서
		5 : 생활용품
	 -->
	<div style="text-align:left;" id="1">
		<font size=5><b name="1">문제집 (국내)</b></font></br>
	</div>
	<?php $category=0 ?>
	<div style="clear:both;padding:5px;"></div>
	<table style="text-align:center;" id="donation_table_dom" class="table table-condensed table-striped">
		<thead style>
			<tr>
				<th style="text-align:center; width:70%;">책제목</th>
				<!-- <th style="width:20%; text-align:center;">출판사</th> -->
				<th style="text-align:center; width:30%;">신청자</th>
			</tr>
		</thead>
		<tbody>
			<!-- 0: 's_title' 1: 's_publisher', 2: 's_author', 3: 'n_status', 4: 'n_height', 5: 'n_size', 6: 'n_who' 7: 's_status' -->
			<?php for($num=1;$num<=29;$num++){ ?>
				<tr>
					<!-- s_title 책 제목-->
					<td><?php echo $currentTable[$category][$num][0]; ?></td>

					<!-- s_publisher(출판사) column -->
					<!-- <td style='text-align:center'><?php echo $currentTable[$category][$num][1]; ?></td> -->

					<!-- 신청자 column -->
					<!-- if n_who is not 0, 신청자가 있을 때  -->
					<?php if($currentTable[$category][$num][1]!=0){
					$usr=$member->getMember($currentTable[$category][$num][1]);
					?>
					<!-- 그 신청자가 본인일 때, set text color to #d0d0f0 -->
					<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
					<td>
						<?php if($me['n_id'] == $currentTable[$category][$num][1]){ ?>
							<form method="post" action="/proc/util/donation" onsubmit="if(confirm('정말로 신청을 취소하겠습니까?'))return saveAjax(this,'신청 취소 중...'); return false;">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="remove" />
								<input type="submit" class="btn btn-default"  value="취소" />
							</form>
						<?php } ?>
					</td>
					<?php } else { ?>
					<!-- if n_who is 0, 신청자가 없을 때 -->
					<td>신청자가 없습니다</td>
					<td>
					<?php $date1 = new DateTime("now"); $date2 = new DateTime("2016-03-02"); // update DateTime constructor parameter
					if($date1 >=  $date2) { ?>
						<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
							<input type="hidden" name="category" value="<?php echo $category ?>" />
							<input type="hidden" name="num" value="<?php echo $num ?>" />
							<input type="hidden" name="util_action" value="add" />
							<input type="submit" class="btn btn-sm btn-default" value="신청" />
						</form>
					<?php }else{ ?>
						<p style="width: 150px;">기한이 아닙니다</p>
					<?php } ?>
					</td>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	</br>

	<div style="text-align:left;" id="2">
		<font size=5 ><b>문제집 (국제)</b></font></br>
	</div>
	<!-- database상에서의 국제 category: 1 -->
	<?php $category=1 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_int" style="text-align: center;" class="table table-condensed table-striped">
		<thead>
			<tr>
				<th style="text-align: center;  width:70%;">책제목</th>
		<!--		<th style="height: 50px; text-align: center;  width:300px;">상태</th>	-->
				<th style="text-align: center;  width:30%;">신청자</th>
			</tr>
		</thead>
		<tbody>
			<?php for($num=1;$num<=40;$num++){ ?>
				<tr>
					<!-- TODO 국제 category는 제목이 s_publisher에 저장되어 있음, 다른 데는 아님. 일관성 필요-->
					<td><?php echo $currentTable[$category][$num][0]; ?></td>
					<?php if($currentTable[$category][$num][1]!=0){
						$usr=$member->getMember($currentTable[$category][$num][1]);
					?>
					<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
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
					<?php }else{ ?>
					<td>신청자가 없습니다</td>
					<td>
					<?php $date1 = new DateTime("now"); $date2 = new DateTime("2016-03-02");
					if($date1 >= $date2) { ?>
						<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
							<input type="hidden" name="category" value="<?php echo $category ?>" />
							<input type="hidden" name="num" value="<?php echo $num ?>" />
							<input type="hidden" name="util_action" value="add" />
							<input type="submit" class="btn btn-sm btn-default" value="신청" />
						</form>
					<?php }else{ ?>
						<p style="width: 150px;">기한이 아닙니다</p>
					<?php } ?>
					</td>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	</br>

	<div style="text-align:left; " id="3">
		<font size=5><b>서적</b></font></br>
	</div>
	<!-- database상에서의 서적 category: 2 -->
	<?php $category=3 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_book" style="text-align: center;" class="table table-condensed table-striped">
		<thead>
			<tr style="text-align: center;">
				<th style="text-align:center; width:70%;">책제목</th>
		<!--		<th style="height: 50px; width:300px;">저자</th>	-->
				<th style="text-align:center; width:30%;">신청자</th>
			</tr>
		</thead>
		<tbody>
			<?php for($num=1;$num<=10;$num++){ ?>
				<tr>
					<!-- 책 제목 -->
					<td><?php echo $currentTable[$category][$num][0]; ?></td>

				<!--	<td style='text-align:center'><?php echo $currentTable[$category][$num][2]; ?></td> 	-->

					<?php if($currentTable[$category][$num][1]!=0){
					$usr=$member->getMember($currentTable[$category][$num][1]);
					?>
					<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
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
					<?php }else{ ?>
					<td>신청자가 없습니다</td>
					<td>
					<?php $date1 = new DateTime("now"); $date2 = new DateTime("2016-03-02");
					if($date1 >= $date2) { ?>
						<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" class="btn btn-sm btn-default" value="신청" />
						</form>
					<?php }else{ ?>
						<p style="width: 150px;">기한이 아닙니다</p>
					<?php } ?>
					</td>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	</br>

	<div style="text-align:left;" id="4">
		<font size=5 ><b>교과서</b></font></br>
	</div>
	<!-- database상에서의 교과서 category: 4 -->
	<?php $category=2 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_etc" class="table table-condensed table-striped">
		<thead>
			<tr>
				<th style="text-align: center; width:70%;">책제목</th>
			<!--	<th style="text-align: center; height: 50px; width:300px;">상태</th>	-->
				<th style="text-align: center; width :30%;">신청자</th>
			</tr>
		</thead>
		<tbody style="text-align: center;">
			<?php for($num=1;$num<=22;$num++){ ?>
				<tr>

					<td><?php echo $currentTable[$category][$num][0]; ?></td>

				<!--	<td><?php echo $currentTable[$category][$num][7]; ?></td>	-->

					<?php if($currentTable[$category][$num][1]!=0){
					$usr=$member->getMember($currentTable[$category][$num][1]);
					?>
					<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
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
					<?php }else{ ?>
					<td>신청자가 없습니다</td>
					<td>
					<?php $date1 = new DateTime("now"); $date2 = new DateTime("2016-03-02");
					if($date1 >= $date2) { ?>
						<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
							<input type="hidden" name="category" value="<?php echo $category ?>" />
							<input type="hidden" name="num" value="<?php echo $num ?>" />
							<input type="hidden" name="util_action" value="add" />
							<input type="submit" class="btn btn-sm btn-default" value="신청" />
						</form>
					<?php }else{ ?>
						<p style="width: 150px;">기한이 아닙니다</p>
					<?php } ?>
					</td>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	</br>
	<div style="text-align:left;" id="5">
		<font size=5><b>생활물품</b></font></br>
	</div>
	<!-- database상에서의 생활용품 category: 5 -->
	<?php $category=4 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_etc" class="table table-condensed table-striped">
		<thead>
			<tr style="background:#DDD">
				<th style="text-align: center; width:50%;">물품명</th>
				<th style="text-align: center; width:20%;">비고</th>
				<th style="text-align: center; width:30%;">신청자</th>
			</tr>
		</thead>
		<tbody style="text-align: center;">
			<?php for($num=1;$num<=60;$num++){ ?>
				<tr>
					<!-- TODO 생활용품 category도 제목이 s_publisher에 저장되어 있음, 다른 데는 아님. 일관성 필요-->
					<!-- 제목-->
					<td><?php echo $currentTable[$category][$num][0]; ?></td>
					<!--  -->
					<td>
					<?php echo $currentTable[$category][$num][2];?>
					</td>
					<!--Backup	<td>echo $currentTable[$category][$num][1]; ?></td>	-->

					<?php if($currentTable[$category][$num][1]!=0){
					$usr=$member->getMember($currentTable[$category][$num][1]);
					?>
					<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#D0D0F0";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
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
					<?php }else{ ?>
					<td>신청자가 없습니다</td>

					<td>
					<?php $date1 = new DateTime("now"); $date2 = new DateTime("2016-03-02");
					if($date1 >= $date2) { ?>
						<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
							<input type="hidden" name="category" value="<?php echo $category ?>" />
							<input type="hidden" name="num" value="<?php echo $num ?>" />
							<input type="hidden" name="util_action" value="add" />
							<input type="submit" class="btn btn-sm btn-default" value="신청" />
						</form>
					<?php }else{ ?>
						<p style="width: 150px;">기한이 아닙니다</p>
					<?php } ?>
					</td>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table
	</br>
<?php } ?>

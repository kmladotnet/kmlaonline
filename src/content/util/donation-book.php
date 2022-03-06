<?php
/********************************
 * HOW TO USE
 * donation-cloth.php와 함께 이 페이지는 기부 물품 관리 페이지입니다.
 * 기부물품은 kmlaonline_donation_new라는 데이터베이스에 모두 저장이 되어 있습니다.
 * 모든 물품은 7개의 category로 나누어져 있는데, 이는 밑을 참고하세요.
 * 기부물품은 행정위원회가 정리한 excel(.xlsx) 파일을 통해 전달을 받는데, 2018년부터는
 * excel 파일에 저장된 물품 목록을 database에 넣는 작업을 자동화하였습니다.
 * 이는 python과 openpyxl이라는 module을 사용하였습니다.
 * 이는 서버 상의 디렉토리 /srv/scripts/donation에 위치해 있습니다. excel 파일을 kmla/donation에
 * donations.xlsx라는 이름으로 넣은 이후(excel 파일의 시트 형식은 엄격하게 맞추어야 합니다. 이는
 * 전 해의 excel 파일을 참고하거나 22기 안주언에게 연락하세요) scripts/donation에 있는 excel.py를 실행(with root privelage)
 * 하면 donation_output.txt라는 파일이 생성됩니다. 이 파일에는 query문이 들어가 있는데,
 * https://kmlaonline.net/donation/donation.php?user=paco를 들어가면 그 query문이 실행되어 database가
 * initialize됩니다.
 *
*********************************/
redirectLoginIfRequired();
$title = "서적 신청 - " . $title;
function getCurrentTable(){
	global $mysqli;
	$query = "SELECT * FROM kmlaonline_donation_new";
    // fetch_array documentation http://php.net/manual/en/function.mysql-fetch-array.php
	if($res = $mysqli->query($query)){
		$arr = array();
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$arr[$row['n_category']][$row['n_num']] = array($row['s_title'], $row['n_who'], $row['s_status'], $row['s_type'], $row['s_owner']);
		}
		$res->close();
		if($mysqli->more_results()) $mysqli->next_result();
		return $arr;
	}
}
function printContent(){
	global $member, $me;
	$currentTable = getCurrentTable();
	?>
	<style>
	td p{
		position: relative;
		top: 5px;
	}
	a.btn {
		background-color: #6395f5;
		border-color: #6395f5;
		margin-bottom: 10px;
		margin-top: 10px;
	}
	</style>
	<font size=3 color="#fc6b6b">
	</br>
	<b>기부물품 신청 페이지입니다.</b></br> 선배님들께 기부받은 물품들을 교내외에서 돈을  받고 팔거나, 기타 수단으로 사용되는 것을 금하며, 해당 활동 등이 적발될 시에는 학생회 차원을 넘어 엄히 처벌하겠습니다. 정각에 신청 가능합니다. </br>
    <a class="btn btn-info" role="button" href="/util/donation-cloth">교복 신청 목록</a>
	</font>
	</br>
	<!-- category
		0 : 국내 문제집
		1 : 국제 문제집
		2 : 서적
		3 : 교과서
		4 : 생활용품
		5 : 여자 교복
		6 : 남자 교복
	 -->
	 <!--
	 id가 각각 donation_table_dom donation_table_int, donation_table_book, donation_table_etc였음.
	 반복문으로 고치며 모두 같은 id를 가지고 있지만, 이것이 어떤 역할을 하는지 잘 모르겠어서 안 넣었음. Paco-->
	<?php $category_title = array("문제집 (국내)", "문제집 (국제)", "서적", "교과서");?>
	<?php for($i = 0; $i < 4; $i++): ?>
		<div style="text-align:left;" id="<?php echo $i + 1 ?>">
			<font size=5><b name="1">
<?php echo $category_title[$i]; ?></b></font></br>
		</div>
		<?php $category = $i ?>
		<div style="clear:both;padding:5px;"></div>
		<table style="text-align:center;" id="donation_table_dom" class="table table-condensed table-striped">
			<thead style>
				<tr>
					<th style="text-align:center; width:70%;">책제목</th>
					<th style="text-align:center; width:30%;">신청자</th>
				</tr>
			</thead>
			<tbody>
				<?php for($num = 1; $num <= sizeof($currentTable[$category]); $num++){ ?>
					<tr>
						<!-- s_title 책 제목-->
						<td>
<?php echo $currentTable[$category][$num][0]; ?></td>

						<!-- 신청자 column -->
						<!-- if n_who is not 0, 신청자가 있을 때  -->
						<?php if($currentTable[$category][$num][1] != 0){
						$usr = $member->getMember($currentTable[$category][$num][1]);
						?>
						<!-- 그 신청자가 본인일 때, set text color to #d0d0f0 -->
						<td style='text-align:center;<?php if($usr['n_id'] == $me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>">
<?php putUserCard($usr); ?></a></td>
						<td>
							<?php if($me['n_id'] == $currentTable[$category][$num][1]){ ?>
								<form method="post" action="/proc/util/donation" onsubmit="if(confirm('정말로 신청을 취소하겠습니까?'))return saveAjax(this,'신청 취소 중...'); return false;">
									<input type="hidden" name="from" value="book">
									<input type="hidden" name="category" value="<?php echo $category ?>" />
									<input type="hidden" name="num" value="<?php echo $num ?>" />
									<input type="hidden" name="util_action" value="remove" />
									<input type="submit" class="btn btn-default" style="background-color: #ff8585; border: #ff8585" value="취소" />
								</form>
							<?php } ?>
						</td>
						<?php } else { ?>
						<!-- if n_who is 0, 신청자가 없을 때 -->
						<td>신청자가 없습니다</td>
						<td>
						<?php $date1 = new DateTime("now"); $date2 = new DateTime("2022-03-07 22:00:00"); // update DateTime constructor parameter
						if($date1 >=  $date2) { ?>
							<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="from" value="book">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" class="btn btn-sm btn-default" value="신청" />
							</form>
						<?php } else { ?>
							<p style="width: 150px;">기한이 아닙니다</p>
						<?php } ?>
						</td>
						<?php } ?>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		</br>
	<?php endfor; ?>
	<!-- database상에서의 생활용품 category: 4 -->
	<?php $category = 4 ?>
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
			<?php for($num = 1; $num <= sizeof($currentTable[$category]); $num++){ ?>
				<tr>
					<!-- TODO 생활용품 category도 제목이 s_publisher에 저장되어 있음, 다른 데는 아님. 일관성 필요-->
					<!-- 제목-->
					<td>
<?php echo $currentTable[$category][$num][0]; ?></td>
					<!--  -->
					<td>
					<?php echo $currentTable[$category][$num][2];?>
					</td>
					<!--Backup	<td>echo $currentTable[$category][$num][1]; ?></td>	-->

					<?php if($currentTable[$category][$num][1]!=0){
					$usr=$member->getMember($currentTable[$category][$num][1]);
					?>
					<td style='text-align:center;<?php if($usr['n_id'] == $me['n_id']) echo "background:#D0D0F0";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>">
<?php putUserCard($usr); ?></a></td>
					<td>
						<?php if($me['n_id'] == $currentTable[$category][$num][1]){ ?>
							<form method="post" action="/proc/util/donation" onsubmit="if(confirm('정말로 신청을 취소하겠습니까?'))return saveAjax(this,'신청 취소 중...'); return false;">
								<input type="hidden" name="from" value="book">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="remove" />
								<input type="submit" class="btn btn-default" style="background-color: #ff8585; border: #ff8585" value="취소" />
							</form>
						<?php } ?>
					</td>
					<?php }else{ ?>
					<td>신청자가 없습니다</td>

					<td>
					<?php $date1 = new DateTime("now"); $date2 = new DateTime("2022-03-07 22:00:00");
					if($date1 >= $date2) { ?>
						<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
							<input type="hidden" name="from" value="book">
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
<?php } ?>

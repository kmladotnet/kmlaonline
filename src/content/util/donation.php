<?php
redirectLoginIfRequired();
$title="기부물품 신청 - " . $title;
function getCurrentTable(){
	global $mysqli;
	$query="SELECT * FROM kmlaonline_donation_table";
	if($res=$mysqli->query($query)){
		$arr=array();$i=0;
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$arr[$row['n_category']][$row['n_num']]=array($row['s_title'], $row['s_publisher'], $row['s_author'], $row['n_status'], $row['n_height'], $row['n_size'], $row['n_who'], $row['s_status']);
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
	</font>
	</br>
	<style>
		td p{
			position: relative;
			top: 5px;
		}
	</style>
	<div style="text-align:left;" id="1">
		<font size=5><b name="1">문제집 (국내)</b></font></br>
	</div>
	<?php $category=0 ?>
	<div style="clear:both;padding:5px;"></div>
	<table style="text-align:center;" id="donation_table_dom">
		<thead style>
			<tr style="background:#DDD">
				<th style="height:50px; width:50%; text-align:center;">책제목</th>
				<th style="height:50px; width:20%; text-align:center;">출판사</th>
				<th style="height:50px; width:30%; text-align:center;">신청자</th>
			</tr>
		</thead>
		<tbody>
			<?php for($num=1;$num<=265;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][0]; ?></td> 
					
					<td style='text-align:center'><?php echo $currentTable[$category][$num][1]; ?></td>
 
					<?php if($currentTable[$category][$num][6]!=0){
						$usr=$member->getMember($currentTable[$category][$num][6]);
						?>
						<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
						<td>
							<?php if($me['n_id']==$currentTable[$category][$num][6]){ ?>
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
		<?php 	$date1 = new DateTime("now"); $date2 = new DateTime("2016-03-02");
			if($date1 >=  $date2) { ?>	<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" value="신청" />
							</form>
		<?php }else{ ?>
							<p style="width: 150px;">기한이 아닙니다</p>
		<?php } ?>
 						</form>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	</br>

	<div style="text-align:left;" id="2">
		<font size=5 ><b>문제집 (국제)</b></font></br>
	</div>
	<?php $category=1 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_int" style="text-align: center;">
		<thead>
			<tr style="background:#DDD">
				<th style="height: 50px; text-align: center;  width:70%;">책제목</th>
		<!--		<th style="height: 50px; text-align: center;  width:300px;">상태</th>	-->
				<th style="height: 50px; text-align: center;  width:30%;">신청자</th>
			</tr>
		</thead>
		<tbody>
			<?php for($num=1;$num<=107;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][1]; ?></td> 
					
		<!--			<td style='text-align:center'><?php switch($currentTable[$category][$num][3]){case(0): {echo "보통"; break;} case(1): {echo "양호"; break;} case(2): {echo "좋음"; break;}}?></td>	-->

					<?php if($currentTable[$category][$num][6]!=0){
						$usr=$member->getMember($currentTable[$category][$num][6]);
						?>
						<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
						<td>
							<?php if($me['n_id']==$currentTable[$category][$num][6]){ ?>
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
		<?php 	$date1 = new DateTime("now"); $date2 = new DateTime("2016-03-02");
			if($date1 >= $date2) { ?>	<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" value="신청" />
							</form>
		<?php }else{ ?>
							<p style="width: 150px;">기한이 아닙니다</p>
		<?php } ?>
 						</form>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	</br>

	<div style="text-align:left; " id="3">
		<font size=5><b>서적</b></font></br>
	</div>
	<?php $category=2 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_book" style="text-align: center;">
		<thead>
			<tr style="background:#DDD; text-align: center;">
				<th style="height: 50px; text-align:center; width:70%;">책제목</th>
		<!--		<th style="height: 50px; width:300px;">저자</th>	-->
				<th style="height: 50px; text-align:center; width:30%;">신청자</th>
			</tr>
		</thead>
		<tbody>
			<?php for($num=1;$num<=39;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][0]; ?></td> 
					
				<!--	<td style='text-align:center'><?php echo $currentTable[$category][$num][2]; ?></td> 	-->

					<?php if($currentTable[$category][$num][6]!=0){
						$usr=$member->getMember($currentTable[$category][$num][6]);
						?>
						<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
						<td>
							<?php if($me['n_id']==$currentTable[$category][$num][6]){ ?>
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
		<?php 	$date1 = new DateTime("now"); $date2 = new DateTime("2016-03-02");
			if($date1 >= $date2) { ?>	<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" value="신청" />
							</form>
		<?php }else{ ?>
							<p style="width: 150px;">기한이 아닙니다</p>
		<?php } ?>
 						</form>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	</br>

<!--	<div style="text-align:left;">
		<font size=5><b>교복</b></font></br>
	</div>
	<?php $category=3 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_hanbok">
		<thead>
			<tr style="background:#DDD">
				<th style="width:500px;">종류 (하의색, 상의색)</th>
				<th style="width:100px;">상태</th>
				<th style="width:100px;">키</th>
				<th style="width:100px;">사이즈</th>
				<th style="width:100px">신청자</th>
			</tr>
		</thead>
		<tbody>
			<?php for($num=1;$num<=32;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][0]; ?></td> 
					
					<td style='text-align:center'><?php switch($currentTable[$category][$num][3]){case(0): {echo "보통"; break;} case(1): {echo "양호"; break;} case(2): {echo "좋음"; break;}}?></td> 

					<td style='text-align:center'><?php if($currentTable[$category][$num][0]!="누비")switch($currentTable[$category][$num][4]){case(0): {echo "작음"; break;} case(1): {echo "보통"; break;} case(2): {echo "큼"; break;}}?></td> 

					<td style='text-align:center'><?php if($currentTable[$category][$num][0]!="누비")switch($currentTable[$category][$num][5]){case(0): {echo "작음"; break;} case(1): {echo "보통"; break;} case(2): {echo "큼"; break;} case(3): {echo "마름"; break;}}?></td> 

					<?php if($currentTable[$category][$num][6]!=0){
						$usr=$member->getMember($currentTable[$category][$num][6]);
						?>
						<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
						<td>
							<?php if($me['n_id']==$currentTable[$category][$num][6]){ ?>
								<form method="post" action="/proc/util/donation" onsubmit="if(confirm('정말로 신청을 취소하겠습니까?'))return saveAjax(this,'신청 취소 중...'); return false;">
									<input type="hidden" name="category" value="<?php echo $category ?>" />
									<input type="hidden" name="num" value="<?php echo $num ?>" />
									<input type="hidden" name="util_action" value="remove" />
									<input type="submit" value="취소" />
								</form>
							<?php } ?>
						</td>
					<?php }else{ ?>
						<td></td>
						<td>
							<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" value="신청" />
							</form>
						</form>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	</br>	-->

	<div style="text-align:left;" id="4">
		<font size=5 ><b>교과서</b></font></br>
	</div>
	<?php $category=4 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_etc">
		<thead>
			<tr style="background:#DDD">
				<th style="text-align: center; height: 50px; width:70%;">책제목</th>
			<!--	<th style="text-align: center; height: 50px; width:300px;">상태</th>	-->
				<th style="text-align: center; height: 50px; width :30%;">신청자</th>
			</tr>
		</thead>
		<tbody style="text-align: center;">
			<?php for($num=1;$num<=37;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][0]; ?></td> 
					
				<!--	<td><?php echo $currentTable[$category][$num][7]; ?></td>	-->

					<?php if($currentTable[$category][$num][6]!=0){
						$usr=$member->getMember($currentTable[$category][$num][6]);
						?>
						<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
						<td>
							<?php if($me['n_id']==$currentTable[$category][$num][6]){ ?>
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
		<?php 	$date1 = new DateTime("now"); $date2 = new DateTime("2016-03-02");
			if($date1 >= $date2) { ?>	<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" value="신청" />
							</form>
		<?php }else{ ?>
							<p style="width: 150px;">기한이 아닙니다</p>
		<?php } ?>
 						</form>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	</br>
	<div style="text-align:left;" id="5">
		<font size=5><b>생활물품</b></font></br>
	</div>
	<?php $category=5 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_etc">
		<thead>
			<tr style="background:#DDD">
				<th style="text-align: center; height: 50px; width:50%;">물품명</th>
				<th style="text-align: center; height: 50px; width:20%;">비고</th>
				<th style="text-align: center; height: 50px; width:30%;">신청자</th>
			</tr>
		</thead>
		<tbody style="text-align: center;">
			<?php for($num=1;$num<=63;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][1]; ?></td> 
					
					<td><?php if($currentTable[$category][$num][1]==""||$currentTable[$category][$num][1]==NULL){
						echo "없음";
					} else {
						echo $currentTable[$category][$num][0];
					}?>
					</td>
			<!--Backup	<td>echo $currentTable[$category][$num][1]; ?></td>	-->

					<?php if($currentTable[$category][$num][6]!=0){
						$usr=$member->getMember($currentTable[$category][$num][6]);
						?>
						<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
						<td>
							<?php if($me['n_id']==$currentTable[$category][$num][6]){ ?>
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
		<?php 	$date1 = new DateTime("now"); $date2 = new DateTime("2016-03-02");
			if($date1 >= $date2) { ?>	<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" value="신청" />
							</form>
		<?php }else{ ?>
							<p style="width: 150px;">기한이 아닙니다</p>
		<?php } ?>
 						</form>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table
	</br>

	<div style="text-align:left;" id="5">
		<font size=5><b>여자 교복</b></font></br>
	</div>
	<?php $category=6 ?>   <!--Category 6: 여자 교복 -->
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_etc" style="width: 100%;">
		<thead>
			<tr style="background:#DDD">
				<th style="text-align: center; height: 50px; width:10%;">종류</th>
				<th style="text-align: center; height: 50px; width:40%;">색(장의+치마)</th>
				<th style="text-align: center; height: 50px; width:20%;">기부자</th>
                <th style="text-align: center; height: 50px; wdith:30%;">신청자</th>
			</tr>
		</thead>
		<tbody style="text-align: center;">
			<?php for($num=1;$num<=87;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][2]; ?></td>

					<td><?php if($currentTable[$category][$num][1]==""||$currentTable[$category][$num][0]==NULL){
						echo "없음";
					} else {
						echo $currentTable[$category][$num][1];
					}?>

                    <td><?php echo $currentTable[$category][$num][0] ?></td>

					</td>
			<!--Backup	<td>echo $currentTable[$category][$num][1]; ?></td>	-->

					<?php if($currentTable[$category][$num][6]!=0){
						$usr=$member->getMember($currentTable[$category][$num][6]);
						?>
						<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
						<td>
							<?php if($me['n_id']==$currentTable[$category][$num][6]){ ?>
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
		          <?php 	$date1 = new DateTime("now"); $date2 = new DateTime("2016-03-04");
			if($date1 >= $date2) { ?>	<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" value="신청" />
							</form>
		<?php }else{ ?>
							<p style="width: 150px;">기한이 아닙니다</p>
		<?php } ?>
 						</form>
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
	<?php $category=7 ?>   <!--Category 7: 여자 교복 -->
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_etc" style="width: 100%;">
		<thead>
			<tr style="background:#DDD">
				<th style="text-align: center; height: 50px; width:10%;">종류</th>
				<th style="text-align: center; height: 50px; width:40%;">색(장의+바지)</th>
				<th style="text-align: center; height: 50px; width:20%;">기부자</th>
                <th style="text-align: center; height: 50px; wdith:30%;">신청자</th>
			</tr>
		</thead>
		<tbody style="text-align: center;">
			<?php for($num=1;$num<=3;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][2]; ?></td>

					<td><?php if($currentTable[$category][$num][1]==""||$currentTable[$category][$num][0]==NULL){
						echo "없음";
					} else {
						echo $currentTable[$category][$num][1];
					}?>

                    <td><?php echo $currentTable[$category][$num][0] ?></td>

					</td>
			<!--Backup	<td>echo $currentTable[$category][$num][1]; ?></td>	-->

					<?php if($currentTable[$category][$num][6]!=0){
						$usr=$member->getMember($currentTable[$category][$num][6]);
						?>
						<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
						<td>
							<?php if($me['n_id']==$currentTable[$category][$num][6]){ ?>
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
		          <?php 	$date1 = new DateTime("now"); $date2 = new DateTime("2016-03-04");
			if($date1 >= $date2) { ?>	<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" value="신청" />
							</form>
		<?php }else{ ?>
							<p style="width: 150px;">기한이 아닙니다</p>
		<?php } ?>
 						</form>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table
	</br>
    </br>

	<div style="text-align:left;" id="5">
		<font size=5><b>사복</b></font></br>
	</div>
	<?php $category=8 ?>   <!--Category 7: 여자 교복 -->
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_etc" style="width: 100%;">
		<thead>
			<tr style="background:#DDD">
				<th style="text-align: center; height: 50px; width:50%;">종류</th>
				<th style="text-align: center; height: 50px; width:20%;">비고</th>
                <th style="text-align: center; height: 50px; wdith:30%;">신청자</th>
			</tr>
		</thead>
		<tbody style="text-align: center;">
			<?php for($num=1;$num<=32;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][2]; ?></td>

					<td><?php if($currentTable[$category][$num][1]==""||$currentTable[$category][$num][0]==NULL){
						echo "없음";
					} else {
						echo $currentTable[$category][$num][1];
					}?>

					</td>
			<!--Backup	<td>echo $currentTable[$category][$num][1]; ?></td>	-->

					<?php if($currentTable[$category][$num][6]!=0){
						$usr=$member->getMember($currentTable[$category][$num][6]);
						?>
						<td style='text-align:center;<?php if($usr['n_id']==$me['n_id']) echo "background:#DDF";?>'><a href="/user/view/<?php echo $usr['n_id']."/".$usr['s_id']?>"><?php putUserCard($usr); ?></a></td>
						<td>
							<?php if($me['n_id']==$currentTable[$category][$num][6]){ ?>
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
		          <?php 	$date1 = new DateTime("now"); $date2 = new DateTime("2016-03-04");
			if($date1 >= $date2) { ?>	<form method="post" action="/proc/util/donation" onsubmit="return saveAjax(this,'신청중...');">
								<input type="hidden" name="category" value="<?php echo $category ?>" />
								<input type="hidden" name="num" value="<?php echo $num ?>" />
								<input type="hidden" name="util_action" value="add" />
								<input type="submit" value="신청" />
							</form>
		<?php }else{ ?>
							<p style="width: 150px;">기한이 아닙니다</p>
		<?php } ?>
 						</form>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table
	</br>
<?php } ?>

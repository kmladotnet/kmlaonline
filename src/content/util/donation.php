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
	그러실 일 없겠지만 혹.시.나 선배님들께 기부받은 물품을 교내외에서 돈 받고 판다던가, 기타 돈벌이 수단으로 사용하는 그런 파렴치한 일은 하지않겠죠?</br>
	절대 절대 그런일은 없으면 좋겠고 지금 행정위원회가 물건 모양, 리스트 다 알고 있는데 이런 거래활동이 적발될시에는 학생회 차원을 넘어서 엄히 처벌하겠습니다.</br>
	자신에게 꼭!!!! 필요한 물품만 신청해주세요!</br>
	</font>
	</br>
	<div style="text-align:left;">
		<font size=5><b>국내수능</b></font></br>
	</div>
	<?php $category=0 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_dom">
		<thead>
			<tr style="background:#DDD">
				<th style="width:500px;">책제목</th>
				<th style="width:300px;">출판사</th>
				<th style="width:100px">신청자</th>
			</tr>
		</thead>
		<tbody>
			<?php for($num=1;$num<=332;$num++){ ?>
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
	</br>

	<div style="text-align:left;">
		<font size=5><b>국제(AP,SAT,TOEFL)</b></font></br>
	</div>
	<?php $category=1 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_int">
		<thead>
			<tr style="background:#DDD">
				<th style="width:500px;">책제목</th>
				<th style="width:300px;">상태</th>
				<th style="width:100px">신청자</th>
			</tr>
		</thead>
		<tbody>
			<?php for($num=1;$num<=103;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][0]; ?></td> 
					
					<td style='text-align:center'><?php switch($currentTable[$category][$num][3]){case(0): {echo "보통"; break;} case(1): {echo "양호"; break;} case(2): {echo "좋음"; break;}}?></td>

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
	</br>

	<div style="text-align:left;">
		<font size=5><b>단행본</b></font></br>
	</div>
	<?php $category=2 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_book">
		<thead>
			<tr style="background:#DDD">
				<th style="width:500px;">책제목</th>
				<th style="width:300px;">저자</th>
				<th style="width:100px">신청자</th>
			</tr>
		</thead>
		<tbody>
			<?php for($num=1;$num<=49;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][0]; ?></td> 
					
					<td style='text-align:center'><?php echo $currentTable[$category][$num][2]; ?></td> 

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
	</br>

	<div style="text-align:left;">
		<font size=5><b>여자교복</b></font></br>
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
	</br>

	<div style="text-align:left;">
		<font size=5><b>기타물품</b></font></br>
	</div>
	<?php $category=4 ?>
	<div style="clear:both;padding:5px;"></div>
	<table id="donation_table_etc">
		<thead>
			<tr style="background:#DDD">
				<th style="width:500px;">종류</th>
				<th style="width:300px;">상태</th>
				<th style="width:100px">신청자</th>
			</tr>
		</thead>
		<tbody>
			<?php for($num=1;$num<=36;$num++){ ?>
				<tr style="background:#FFF">

					<td><?php echo $currentTable[$category][$num][0]; ?></td> 
					
					<td><?php echo $currentTable[$category][$num][7]; ?></td>

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
	</br>
<?php } ?>
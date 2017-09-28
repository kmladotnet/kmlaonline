<?php
redirectLoginIfRequired();
$title="연락망 - $title";
function printContent(){
	global $member, $max_level, $me;
	$gender_list=array("지정하지 않음", "남자", "여자", "기타");
	$clevel=isset($_GET['wave'])?$_GET['wave']:$me['n_level'];
	function _cmp($a,$b){
		$res=strcmp($a['s_name'], $b['s_name']);
		if($res!=0) return $res;
		if($a['n_student_id']==$b['n_student_id'])
			return 0;
		return $a['n_student_id']<$b['n_student_id']?-1:1;
	}
	$arr=$member->listMembers(0, 0, $clevel);
	foreach($arr as $k=>$v){
		$arr[$k]=array_merge($v, $member->getAdditionalData($v['n_id']));
	}
	//usort($arr,"_cmp");
	?>
    <div style="padding:10px;" class='contact-finder'>
        <h1><i class="fa fa-users"></i>연락처</h1>
        <div style="padding-top:10px;">
            <?php for($i=$max_level; $i>=1; $i--){ ?>
                <a rel="navigate" href="/contacts?wave=<?php echo $i?>" class="wavebutton" <?php if($i==$clevel) echo "style='background:#fceede'"; ?>><?php echo $i?>기</a>
                <?php } ?>
                    <table style="width:100%; word-break: keep-all;" class="table table-striped table-hover contacts" cellspacing="0">
                        <thead>
                            <tr style="height:32px;">
                                <th style="text-align:center">이름</th>
                                <th style="text-align:center">방</th>
                                <th style="text-align:center">학번</th>
                                <th style="text-align:center">학년 반</th>
                                <th style="text-align:center">성별</th>
                                <th style="text-align:center">핸드폰</th>
                                <th style="text-align:center">생일</th>
                                <th style="text-align:center">E-Mail</th>
                            </tr>
                        </thead>
                        <tbody class="userlist-body">
                            <?php foreach($arr as $m){
                                if(strpos($m['s_name'], '테스트') !== false) {
                                    continue;
                                }
						?>
                                <tr style="height:32px;">
                                    <td style="text-align:center">
                                        <a rel="closenow" href="/user/view/<?php echo $m['n_id']?>/<?php echo rawurlencode($m['s_id'])?>">
									<?php if($m['s_icon']) echo "<img src='".htmlspecialchars($m['s_icon'])."' style='width:11pt;height:11pt;vertical-align:middle;' />"; ?>
									<?php echo htmlspecialchars($m['s_name'])?>
								</a>
                                    </td>
                                    <td style="text-align:center">
                                        <?php echo htmlspecialchars($m['s_room'])?>
                                    </td>
                                    <td style="text-align:center">
                                        <?php echo htmlspecialchars($m['n_student_id'])?>
                                    </td>
                                    <td style="text-align:center">
                                        <?php
								if($m['n_grade'])
									echo htmlspecialchars($m['n_grade']) . "학년&nbsp;";
								if($m['s_class'])
									echo htmlspecialchars($m['s_class']) . "반";
								?>
                                    </td>
                                    <td style="text-align:center">
                                        <?php echo $gender_list[$m['n_gender']] ?>
                                    </td>
                                    <td style="text-align:center">
                                        <?php if($m['s_phone']!=""){ ?>
                                            <a href="tel:<?php echo rawurlencode($m['s_phone'])?>">
                                                <?php echo htmlspecialchars($m['s_phone'])?>
                                            </a>
                                            <?php }else{ ?><span style="color:gray">(없음)</span>
                                                <?php } ?>
                                    </td>
                                    <td style="text-align:center">
                                        <?php echo $m['n_birth_date_yr']."&#8209;".$m['n_birth_date_month']."&#8209;".$m['n_birth_date_day'];?>
                                    </td>
                                    <td style="text-align:center">
                                        <a href="mailto:<?php echo rawurlencode($m['s_email'])?>">
                                            <?php echo $m['s_email']; ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                        </tbody>
                    </table>
        </div>
    </div>
    <?php
}
?>

<?php
$title="큼라보드 - " . $title;
function printContent(){
	global $mysqli,$board, $member, $me;
	$pagestart=0; $pagecount=20;
	?>
	<h1 style="padding:10px;">큼라보드</h1>
	<p style="margin-left:15px;">
		<?php
		$dat="";
		if(file_exists("data/kmlaboard.txt") && filesize("data/kmlaboard.txt")>0){
			$dat=file_get_contents("data/kmlaboard.txt");
		}
		if(isUserPermitted($me['n_id'], "kmlaboard_changer")){
			?>
			<form action="/proc/util/kmlaboard" method="post" onsubmit="return saveAjax(this,'처리 중...');">
				<textarea name="data" id="s_data_ckeditor" style="width:98%;box-sizing:border-box"><?php echo htmlspecialchars($dat); ?></textarea>
				<input type="submit" value="저장" style="width:80px;height:32px;" />
			</form>
            <script>
                CKEDITOR.replace('s_data_ckeditor', {
                    language: 'ko',
                    font_names : '맑은 고딕;나눔고딕;나눔명조;나눔펜;굴림;바탕;돋움;궁서;Register;Sensation;Arial;Times New Roman;Verdana;Trebuchet MS;'
                });
            </script>
			<?php
		}else{
			$dat=nl2br(strip_tags($dat,"<b><big><small><i><u><strong><strike><a><font><img><q><s><sub><sup>"));
			echo filterContent($dat);
		}
		?>
	</p>
	<?php
}

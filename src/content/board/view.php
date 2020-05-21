<?php
define("upvote_threshold", -10);
function printVotes($id, $hide = true) {
    global $me, $member;
    $upvotes = getVotes($id);
    $upvoted = upvoted($id, $me['n_id']);
    if($upvoted) {
        $upvotes--;
    }
    if($hide) { ?>
        <button type="button" data-id="<?php echo $id;?>" id="collapse-<?php echo $id;?>" class="btn btn-default"
            data-tooltip="tooltip" trigger="hover" title="글 숨기기" style="color: royalblue; padding: 0px; height: 24px; width: 24px; float: left; margin-right: 4px"
                onclick='hidePost($(this).data("id"));'>
            <i class="fa fa-minus"></i>
        </button>
        <button type="button" data-id="<?php echo $id;?>" id="uncollapse-<?php echo $id;?>" class="btn btn-default"
            data-tooltip="tooltip" trigger="hover" title="글 보이기" style="color: royalblue; padding: 0px; height: 24px; width: 24px; float: left; display: none; margin-right: 4px"
                onclick='showPost($(this).data("id"));'>
            <i class="fa fa-plus"></i>
        </button>
    <?php } ?>
    <div style="display: inline-block; vertical-align: middle; <?php if(getTheme($me)['voteright']) echo 'float:right;';?>">
        <button type="button" data-id="<?php echo $id;?>" id="unvoted-<?php echo $id;?>" class="btn btn-default"
            style="color: black; padding: 0 4px; height: 24px;<?php if($upvoted) echo 'display: none;'; ?>"
                onclick='upvote($(this).data("id"), false);'>
            <?php  echo '&#43;'.$upvotes; ?>
        </button>
        <button type="button" data-id="<?php echo $id;?>" id="upvoted-<?php echo $id;?>" class="btn btn-default" style="font-weight: bold; color: forestgreen; padding: 0 4px; height: 24px;<?php if(!$upvoted) echo 'display: none;'; ?>"
                onclick='unvote($(this).data("id"), false);'>
            <?php echo '&#43;'.($upvotes + 1); ?>
        </button>
    </div>
    <a onclick='$("#likers-<?php echo $id;?>").css("display",$("#likers-<?php echo $id;?>").css("display")==="none"?"inline-block":"none");' style='font-size: 0.9em'>(명단)</a>
    <span id='likers-<?php echo $id;?>' style="display:none; vertical-align: top; font-size: 0.9em;">
        <div style='color: forestgreen'> <strong>좋아요:</strong>
<?php $uv = upvoters($id); if(empty($uv)) echo '-'; else foreach($uv as $p => $v) echo $member->getMember($p)['s_name'].' ';?> </div>
    </span>
    <?php
}

function votes($votes) {
    if($votes > 0) {
        echo '&#43;';
    }
    if(!$votes) {
        echo 0;
    } else {
        echo $votes;
    }
}

function formatVotes($up, $down) {
    ?>
    <span style="color: forestgreen;">
<?php echo votes($up);?></span>
    <span>|</span>
    <span style="color: crimson;">
<?php echo votes(-$down);?></span>
<?php
}

function printUpDownVotes($id, $hide = true) {
    global $me, $member;
    $upvotes = upvotes($id);
    $downvotes = downvotes($id);
    $votes = ((int)upvotes($id) - (int)downvotes($id));
    $upvoted = upvoted($id, $me['n_id']);
    $downvoted = downvoted($id, $me['n_id']);
    if($upvoted) {
        $votes--;
        $upvotes--;
    }
    if($downvoted) {
        $votes++;
        $downvotes--;
    }
    if($hide) { ?>
        <button type="button" data-id="<?php echo $id;?>" id="collapse-<?php echo $id;?>" class="btn btn-default"
            data-tooltip="tooltip" trigger="hover" title="글 숨기기" style="color: royalblue; padding: 0px; height: 24px; width: 24px; float: left; margin-right: 4px"
                onclick='hidePost($(this).data("id"));'>
            <i class="fa fa-minus"></i>
        </button>
        <button type="button" data-id="<?php echo $id;?>" id="uncollapse-<?php echo $id;?>" class="btn btn-default"
            data-tooltip="tooltip" trigger="hover" title="글 보이기" style="color: royalblue; padding: 0px; height: 24px; width: 24px; float: left; display: none; margin-right: 4px"
                onclick='showPost($(this).data("id"));'>
            <i class="fa fa-plus"></i>
        </button>
    <?php } ?>
    <div class="input-group" style="display: inline-table; vertical-align: middle; width: 1px;<?php if(getTheme($me)['voteright']) echo 'float:right;';?>">
        <span class="input-group-btn">
            <button type="button" data-id="<?php echo $id;?>" id="plus-<?php echo $id;?>" data-toggle="button" class="btn btn-default
                <?php if($upvoted) echo 'active';?>" style="font-weight: bold; color: forestgreen; padding: 0px; height: 24px; width: 24px;"
                    onclick='if($(this).hasClass("active")) unvote($(this).data("id"), true); else upvote($(this).data("id"), true);'>
                <i class="fa fa-arrow-up"></i>
            </button>
        </span>
        <span id="downvote-<?php echo $id;?>" class="input-group-btn" style="<?php if(!$downvoted) echo 'display:none';?>">
            <div class="form-control vote-count" disabled style="color: crimson!important;">
                <?php formatVotes($upvotes, $downvotes + 1);?>
            </div>
        </span>
        <span id="vote-<?php echo $id;?>" class="input-group-btn" style="<?php if($upvoted || $downvoted) echo 'display:none';?>">
            <div class="form-control vote-count" disabled style="color: black;">
                <?php formatVotes($upvotes, $downvotes);?>
            </div>
        </span>
        <span id="upvote-<?php echo $id;?>" class="input-group-btn" style="<?php if(!$upvoted) echo 'display:none';?>">
            <div class="form-control vote-count" disabled style="color: forestgreen!important;">
                <?php formatVotes($upvotes + 1, $downvotes);?>
            </div>
        </span>
        <span class="input-group-btn">
            <button type="button" data-id="<?php echo $id;?>" id="minus-<?php echo $id;?>" data-toggle="button" class="btn btn-default
                <?php if($downvoted) echo 'active';?>" data-tooltip="big-tooltip" trigger="hover" title="&quot;동의하지 않음&quot; 버튼이 아닙니다." style="font-weight: bold; color: crimson; padding: 0px; height: 24px; width: 24px;"
                    onclick='if($(this).hasClass("active")) unvote($(this).data("id"), true); else downvote($(this).data("id"));'>
                <i class="fa fa-arrow-down"></i>
            </button>
        </span>
    </div>
    <a onclick='$("#likers-<?php echo $id;?>").css("display",$("#likers-<?php echo $id;?>").css("display")==="none"?"inline-block":"none");' style='font-size: 0.9em'>(명단)</a>
    <span id='likers-<?php echo $id;?>' style="display:none; vertical-align: top; font-size: 0.9em">
        <div style='color: forestgreen'> <strong>좋아요:</strong>
<?php $uv = upvoters($id); if(empty($uv)) echo '-'; else foreach($uv as $p => $v) echo $member->getMember($p)['s_name'].' ';?> </div>
        <div style='color: crimson'> <strong>싫어요:</strong>
<?php $dv = downvoters($id); if(empty($dv)) echo '-'; else foreach($dv as $p => $v) echo $member->getMember($p)['s_name'].' ';?> </div>
    </span>
    <?php if(reportable($upvotes, $downvotes)) { ?>
        <a href='ajax/board/reporters?id=<?php echo $id;?>' data-toggle='lightbox' style='font-size: 0.9em; color:orangered'>신고자(<?php echo reportNum($id); ?>)</a>
        
    <?php }
}

function printAttachList($article, $cat, $mode=0){
	global $board, $is_mobile;
	if(!checkCategoryAccess($cat['n_id'], "attach download")) return false;
	$attaches=$board->getAttachments(false,$article['n_id']);
	$classname="each_attachment_$mode";
	$thumbmode="sizemode_$mode";
	$defaultshow=1; //$mode==1;
	if($attaches!==false && count($attaches)>0){
		if($mode==2) echo "<hr />";
		$fsize=0;
		foreach($attaches as $v){
			$fsize+=filesize($v['s_path']);
		}
		?>
		<div class="attach_container">
			<div style="width:100%;display:block;position:relative;">
				<div style="font-size:15pt;font-weight:bold;float:left">첨부파일</div>
				<a href="<?php echo "/files/bbs/{$cat['n_id']}/{$article['n_id']}/".rawurlencode($cat['s_name'] . " - " .  $article['n_id'] . " - " . sanitizeFileName($article['s_title'])).".zip";?>" class="btn btn-default" style="float:right; margin-top: -8px; margin-bottom: 6px;"><i class="fa fa-file-archive-o" aria-hidden="true"></i> 압축파일로 받기 (<?php echo convertFromBytes($fsize); ?>)</a>
			</div>
            <div style="clear: both;"></div>
			<div>
				<div style="<?php echo $defaultshow?"display:block;":"display:none;"?>">
					<?php
					$c_cnt=0;
					foreach($attaches as $v){
						?><div class="<?php echo $classname?>">
<?php
							$path="https://kmlaonline.net/files/bbs/{$cat['n_id']}/{$article['n_id']}/{$v['n_id']}/{$v['s_key']}/".rawurlencode($v['s_name']);
							$path_thumb="https://kmlaonline.net/files/bbs/{$cat['n_id']}/{$article['n_id']}/{$v['n_id']}/{$v['s_key']}/$thumbmode/".rawurlencode($v['s_name']);
							$path_force="https://kmlaonline.net/files/bbs/{$cat['n_id']}/{$article['n_id']}/{$v['n_id']}/{$v['s_key']}/force/".rawurlencode($v['s_name']);
							$file_types=array(
								"bmp;png;jpg;jpeg;tif;tiff;gif;svg;"=>
									"<a data-toggle=\"lightbox\" href=\"".htmlspecialchars($path)."\" title=\"".htmlspecialchars($v['s_name']).(htmlspecialchars($v['s_comment'])?": ".$v['s_comment']:"")."\"><img style=\"display:block;margin:0 auto;max-width:100%;\" src=\"".htmlspecialchars($path_thumb)."\" /></a>",
								"mp3;wav;ogg;mp2;mpa;flac;wavpack;ape;alac;ra;mid;"=>
									"+<i class='fas fa-music fa-2x' style='margin-top:48px;'></i>",
								"ppt;pptx;xls;xlsx;doc;docx;docm;dotm;xlsb;xlsm;ppsx;pps;pptm;potm;ppam;potx;ppsm;"=> // MS Office
									"<a class='block' style='min-height:126px;border:1px solid #DDD;display:block;' ".
												"href='https://view.officeapps.live.com/op/view.aspx?src=".htmlspecialchars($path_force)."' target='_blank' ".
												"onclick='window.open(this.href); return false;'>".
										"<i class='far fa-file fa-2x' style='margin-top:40px;'></i><br />".
										"미리보기".
									"</a>",
								"txt;pdf;"=> // Non-MS Office files
									"<a class='block' style='min-height:126px;border:1px solid #DDD;display:block;' ".
												"href='".htmlspecialchars($path)."' target='_blank' ".
												"onclick='window.open(this.href); return false;'>".
										"<i class='far fa-eye fa-2x' style='margin-top:40px;'></i><br />".
										"바로 보기".
									"</a>",
								"avi;mp4;mkv;flv;mov;mpeg;mpg;3gp;ts;wmv;asf;ogm;ogv;rm;rmvb;aac;ac3;m4a;"=>
									"+<i class='fas fa-video fa-2x' style='margin-top:48px;'></i>",
							);
							$fext=strtolower(@end(@explode('.', $path)));
							$temp="<div style='width:126px;min-height:126px;display:block;background:white;border:1px solid #DDD;margin:0 auto;text-align:center;'><!----></div>";
							foreach($file_types as $ftype=>$ftemp)
								if(strpos($ftype, $fext.";")!==false)
									$temp=(substr($ftemp,0,1)=="+")?str_replace("<!---->", substr($ftemp,1), $temp):$ftemp;
							$temp=str_replace("<!---->", "<a class='block' style='min-height:126px; display:block;' ".
												"href='".htmlspecialchars($path)."' target='_blank'>".
										"<i class='fa fa-download fa-2x' style='margin-top:40px;'></i><br />".
										"다운로드".
									"</a>", $temp);
							echo $temp . "<a title=\"".htmlspecialchars($v['s_name']).(htmlspecialchars($v['s_comment'])?": ".$v['s_comment']:"")."\" href=\"" . htmlspecialchars($path_force) . "\">".htmlspecialchars($v['s_name'])."</a>";
							if($mode==1){ // gallery
								echo '<div style="margin:10px;">'.htmlspecialchars($v['s_comment']).'</div>';
							}
						?></div>
<?php
					}
					?>
					<div style="clear:both"></div>
				</div>
			</div>
		</div>
		<?php
	}
}
function printDeletePage(){
	global $article, $member, $board, $board_cat;
	$m=$member->getMember($article['n_writer']);
	insertOnLoadScript("window.noAlertOnLeave=1;");
	$b_anonymous=$article['n_flag']&0x4;
	?>
	<div style="padding:10px;">
		<h1>삭제</h1>
		<div style="border:1px solid #DDD;margin:5px;padding:5px;">
			<div id="article_comment_sub_<?php echo $article['n_id']?>" class="acomment">
				<div>
<?php if($b_anonymous) echo "익명"; else{ ?><a href="<?php echo "/user/view/{$m['n_id']}/".htmlspecialchars($m['s_id'])?>">
<?php putUserCard($m)?></a>
<?php } ?></div>
				<div style="margin-top:14px;margin-bottom:6px;">
<?php filterContent($article['s_data']); ?></div>
				<div style="font-size:8pt;color:#DDD;float:left;">
<?php echo date("Y-m-d H:i:s", $article['n_writedate'])?></div>
				<div style="clear:both"></div>
			</div>
		</div>
		<div style="margin-top:5px;font-size:15pt;font-weight:bold">정말 삭제하시겠습니까?</div>
		<div style="margin-top:5px;margin-bottom:7px;">댓글이 있을 경우 모두 함께 삭제됩니다.</div>
		<form method="post" action="/ajax/board/delete" enctype="multipart/form-data" onsubmit="return saveAjax(this,'글 삭제 중...');">
			<input type="hidden" name="n_id" value="<?php echo $article['n_id']?>" />
			<input class="btn btn-default" type="submit" value="삭제" style="width:120px;height:32px;" />
			<input class="btn btn-default" type="button" value="취소" style="width:120px;height:32px;" onclick="return navigateBack();" />
		</form>
	</div>
	<?php
}
function putCommentTree($parent,$root){
	global $board, $member, $article, $me, $board_id, $board_cat, $is_mobile;
	?><div id="article_comment_<?php echo $parent?>" class="article_comment">
		<?php foreach($board->getArticleList(false, false, $parent, 0, 0, "n_id", false, 0, false, true, true, false, false, false, false, true) as $comment){ $m=$member->getMember($comment['n_writer']);?>
			<div id="article_comment_sub_<?php echo $comment['n_id']?>" class="new_acomment">
				<?php
				$b_comment_anonymous=$comment['n_flag']&0x4;
                ?>
                <div style="display:block;">
                    <?php
                    $votes = getVotes($comment['n_id']);
                    if($b_comment_anonymous) { ?>
                        <span style="font-weight:bold;color:crimson!important;vertical-align:middle">
                            <?php if($comment['n_writer'] == 1) echo '진짜 익명';
                            else {
                                if(reportNum($comment['n_id']) >= 10) {
                                    putUserCard($m);
                                } else {
                                    echo '익명';
                                }
                            } ?>
                        </span>
<?php
                    }
                    else { ?>
                        <span style="font-weight:bold;vertical-align:middle"><a style="color:black!important" href="<?php echo "/user/view/{$m['n_id']}/".htmlspecialchars($m['s_id'])?>">
<?php putUserCard($m)?></a></span>
                    <?php }
                    if($b_comment_anonymous) {
                        printUpDownVotes($comment['n_id']);
                    } else {
                        printVotes($comment['n_id']);
                    }
                    ?>
                    <span style="font-size:8pt;color:gray;">
<?php echo date("Y-m-d H:i:s", $comment['n_writedate'])?></span>
                    <?php if($board_id!='picexhibit') { ?>
                        <div id="item_contents_<?php echo $comment['n_id'];?>" style="margin-top: 6px">
                            <?php
                                if(reportNum($comment['n_id']) >= 10) {
                                    ?> 신고가 10개 이상 접수되어서 삭제되었습니다. <?php
                                } else {
                                    filterContent($comment['s_data']);
                                }
                            ?>
                        </div>
                        <div style="display:none" class="item_hidden" id="item_hidden_<?php echo $comment['n_id'];?>">(숨김) - 좌측 상단의 '+' 버튼을 눌러서 표시할 수 있습니다.</div>
                    <?php } ?>
                    <div style="font-size:0.8em; margin-top: -8px;">
                        <?php
                        if(doesAdminBypassEverythingAndIsAdmin($me['n_id']==$comment['n_writer'])){
                            if(checkCategoryAccess($board_cat['n_id'], "comment edit")&&!$b_comment_anonymous) {
                                echo "<a style='padding-right:4px' href='/board/$board_id/edit/{$comment['n_id']}'>편집</a>";
                            }
                            if(checkCategoryAccess($board_cat['n_id'], "comment delete")&&!$b_comment_anonymous) {
                                echo "<a style='padding-right:4px' href='/board/$board_id/delete/{$comment['n_id']}'>삭제</a>";
                            }
                        }
                        if(checkCategoryAccess($board_cat['n_id'], "comment write")) {
                            echo "<a style='padding-right:4px' onclick='return board_putCommentForm({$comment['n_id']});'>댓글 달기</a>";
                        }
                        ?>
                    </div>
                    <div style="clear:both"></div>
                </div>
			</div>
			<div style="margin-left: 20px; margin-bottom: 18px; padding-left: 8px; border-left: 1px dotted #CDE;">
<?php putCommentTree($comment['n_id'],$root); ?></div>
		<?php } ?>
	</div>
<?php
}

function printTagSplitted($tags, $board_id){
	$o_tags=array();
	foreach(explode(",",$tags) as $tag){
		$tag=htmlspecialchars($tag);
		$o_tags[]="<a href=\"/board/$board_id?search_mode=or&search_tag=true&search=$tag\">$tag</a>";
	}
	echo implode(", ",$o_tags);
}

function printViewPageHeader($usr, $cat){
	global $me, $article, $board_id, $board_cat, $mysqli;
	global $b_public_article, $b_no_comment, $b_anonymous, $b_bold_title;
	?>
	<div class="article_header">
		<div style="width:100%;height:100px;position:relative;">
			<div style="float:left;width:750px;">
				<span style="font-weight:bold">
<?php echo formatTitle($article["s_title"]); ?></span> <span style="color:gray">| <a style="color:gray" href="/board/<?php echo $cat['s_id']?>">
<?php echo $cat['s_name']?></a></span>
				<div style="margin-top:5px;color:#DDD">
<?php printTagSplitted($article["s_tag"], $board_id); ?></div>
                <?php
                    if($b_anonymous) {
                        printUpDownVotes($article['n_id'], false);
                    } else {
                        printVotes($article['n_id'], false);
                    }
                ?>
			</div>
			<?php
			if($usr['s_pic'] && !$b_anonymous)
				echo '<a href="'.htmlspecialchars(str_replace("picture/","picture_full/",$usr['s_pic'])).'" data-toggle="lightbox"><img style="float:right;width:50px;height:50px;margin-left:7px;" src="'.htmlspecialchars($usr['s_pic']).'" /></a>';
			else
				echo '<img src="/images/no-profile.png" style="float:right;width:50px;height:50px;margin-left:7px;" />';
			?>
			<div style="float:right;height:100px;text-align:right;position:relative;">
				<?php
				if($b_anonymous){
					echo "익명";
				}else{
					echo "<a href='/user/view/{$usr['n_id']}/{$usr['s_id']}'>";
					putUserCard($usr);
					echo "</a>";
				}
				echo "<br />";
				echo "{$article['n_total_views']} ({$article['n_views']} 내부/{$article['n_out_views']} 외부)";
				echo "<br />";
				echo date("Y-m-d H:i:s", $article['n_writedate']);
				echo "<br />";
				if($article['n_editdate']!=$article['n_writedate']){
					echo "수정일: " . date("Y-m-d H:i:s", $article['n_editdate']);
					echo "<br />";
				};
				$res=$mysqli->query("SELECT * FROM import_matches WHERE n_id={$article['n_id']}");
				while($row=$res->fetch_array())
					echo "<a href='".dirname($row['s_dat'])."/".htmlspecialchars(basename($row['s_dat']))."' style='font-weight:bold'>임시 파일 받기: ".htmlspecialchars(basename($row['s_dat']))."</a><br />";
				?>
				<div style="position:absolute;bottom:0;right:0;">
<?php echo $b_anonymous?"":htmlspecialchars($usr['s_status_message'])?> - </div>
			</div>
            <div style='position:absolute;left:0;bottom:0'>
                <?php
                if(doesAdminBypassEverythingAndIsAdmin($me['n_id']==$article['n_writer']) || checkCategoryAccess($board_cat['n_id'], "manage modify") || isUserPermitted($me['n_id'], "important_article_chooser")){
                    if(checkCategoryAccess($board_cat['n_id'], "edit")&&(!$b_anonymous||$board_cat['n_id']==77)) {
                        echo "<a style='padding-right:4px' href='/board/$board_id/edit/{$article['n_id']}'>편집</a>";
                    }
                    if(checkCategoryAccess($board_cat['n_id'], "delete")&&(!$b_anonymous||$board_cat['n_id']==77)) {
                        echo "<a style='padding-right:4px' href='/board/$board_id/delete/{$article['n_id']}'>삭제</a>";
                    }
                    $exists=false;
                    if ($res = $mysqli->query("SELECT 1 FROM `kmlaonline_important_notices_table` WHERE n_article={$article['n_id']} LIMIT 1")) {
                        $exists = $res->num_rows > 0;
                    }
                    $res->close();
                    if($exists) {
                        echo "<span style='color:gray; padding-right:4px'>필공 요청함</span>";
                    }
                    else {
                        echo "<a style='padding-right:4px'  onclick='return board_askImportant(this,{$article['n_id']});'>필공 신청</a>";
                    }
                }
                ?>
            </div>
		</div>
		<div style="clear:both"></div>
	</div>
	<?php
}
function parseSurveyForm($aid, $b64data){
	/*
	[
		[survey][name]				=Survey Name 																						(default SURVEYKEY)
		[survey][key]					=1																										(default AUTOINCREASE)
		[survey][anonymous]		=t/1/true or f/0/false																				(default FALSE)
		[survey][oneperuser]		=true																									(default TRUE)
		[items][key]					=item4																									(default TIME)
		[items][dupefirst]			=newest / oldest																					(default OLDEST FIRST)
		[items][condition]			=PHP Expresions: ex) item1+item2+item3==3											(default TRUE)
		[items][0][name]			=Item Names: AAA
		[items][0][type]				=numeric																								(default STRING)
		[items][0][min]				=0																										(default NOT SET)
		[items][0][max]				=3																										(default NOT SET)
		[items][1][name]			=Item Names: BBB
		[items][1][type]				=numeric
		[items][2][name]			=Item Names: CCC
		[items][2][type]				=numeric
		[items][3][name]			=Item Names: Room Number
		[items][3][type]				=numeric
		[items][3][regexp]			=[0-9]{3,4}																								(default .*)
		[items][4][name]			=Special Instructions
		[items][4][regexp]			=.{0,128}
		[items][4][optional]		=true																									(default FALSE)
	]
	*/
	$survey=@json_decode(@base64_decode($b64data),true);
	if($survey===NULL) echo "Bad Survey Form!";
	$items=array();
	for($i=0; isset($survey["items[$i]"]); $i++){
		$item_type=strtolower(isset($survey["items[$i].type"])?$survey["items[$i].type"]:"string");
		$sv_type=strtolower(isset($survey["items[$i].type"])?$survey["items[$i].type"]:"string");
	}
	?>
	<form method="post" action="/ajax/board/survey" onsubmit="return saveAjax(this,'자료 저장 중...');">
		<input name="n_parent" value="<?php echo $aid?>" type="hidden" />
		<input name="prev_url" value="/board/student_judicial/view/428880<?php echo $aid?>" type="hidden" />
		<?php
		?>
		<input class="btn btn-default" value="저장" style="box-sizing: border-box;width:80px;height:32px;float:right;" type="submit">
		<div style="clear:both"></div>
	</form>
	<?php
}
function printViewPageModeBoard($usr, $cat){
	global $article, $board, $member;
	global $board_id, $board_act, $board_cat, $me;
	global $b_public_article, $b_no_comment, $b_anonymous, $b_bold_title;
	printViewPageHeader($usr, $cat);
	?>
	<div class="article_data">
<?php filterContent($article['s_data']); ?></div>
	<?php printAttachList($article, $cat, 0); ?>
	<?php if(doesAdminBypassEverythingAndIsAdmin(!$b_no_comment && checkCategoryAccess($board_cat['n_id'], "comment view"))){ ?>
		<div class="comment_area_info">
			<div style="float:left;font-size:15pt;font-weight:bold">
<?php echo $article['n_comments']?>개의 댓글이 있습니다.</div>
			<?php if(checkCategoryAccess($board_cat['n_id'], "comment write")){ ?><div style="float:right;"><a onclick='return board_putCommentForm(<?php echo $article['n_id']?>);' class='btn btn-default'>댓글 달기</a></div>
<?php } ?>
			<div style="clear:both"></div>
		</div>
		<div class="comment_area">
<?php putCommentTree($article['n_id'],$article['n_id']); ?></div>
	<?php }
}
function printViewPageModeGallery($usr, $cat){
	global $article, $board, $member;
	global $board_id, $board_act, $board_cat, $me;
	global $b_public_article, $b_no_comment, $b_anonymous, $b_bold_title;
	printViewPageHeader($usr, $cat);
	?>
	<?php printAttachList($article, $cat, 1); ?>
	<div class="article_data">
<?php filterContent($article['s_data']); ?></div>
	<?php if(doesAdminBypassEverythingAndIsAdmin(!$b_no_comment && checkCategoryAccess($board_cat['n_id'], "comment view"))){ ?>
		<div class="comment_area_info">
			<div style="float:left;font-size:15pt;font-weight:bold">
<?php echo $article['n_comments']?>개의 댓글이 있습니다.</div>
			<?php if(checkCategoryAccess($board_cat['n_id'], "comment write")){ ?><div style="float:right;"><a onclick='return board_putCommentForm(<?php echo $article['n_id']?>);' class='btn btn-default'>댓글 달기</a></div>
<?php } ?>
			<div style="clear:both"></div>
		</div>
		<div class="comment_area">
<?php putCommentTree($article['n_id'],$article['n_id']); ?></div>
	<?php }
}
function printOneForumItem($article,$root,$suppress_comments=false) {
	global $board_cat, $member, $me, $is_mobile;
	$flag=$article['n_flag']; $b_public_article=$flag&0x1; $b_no_comment=$flag&0x2; $b_anonymous=$flag&0x4; $b_bold_title=$flag&0x8;
	$b_public_article=$b_public_article && checkCategoryAccess($board_cat['n_id'], "flag public");
	$b_no_comment=$b_no_comment && checkCategoryAccess($board_cat['n_id'], "flag no comment");
	$b_bold_title=$b_bold_title && checkCategoryAccess($board_cat['n_id'], "flag bold title");
	$m=$member->getMember($article['n_writer']);
	?>
	<li class="items" id="article_comment_sub_<?php echo $article['n_id']?>">
		<?php if($is_mobile) { ?>
			<div class="item_head" style="padding:6px">
				<?php
                $votes = getVotes($article['n_id']);
                if($b_anonymous) { ?>
                    <span style="font-weight:bold;color:crimson!important;vertical-align:middle">
                        <?php if($article['n_writer'] == 1) echo '진짜 익명';
                        else {
                            if(reportNum($article['n_id']) >= 10) {
                                putUserCard($m);
                            } else {
                                echo '익명';
                            }
                        } ?>
                    </span>
<?php
				} else {
                    ?>
                    <span style="font-weight:bold">
                        <a style="color:black!important;vertical-align:middle" href="<?php echo "/user/view/{$m['n_id']}/{$m['s_id']}" ?>">
<?php putUserCard($m); ?></a>
                    </span>
                <?php }

                if($b_anonymous) {
                    printUpDownVotes($article['n_id']);
                } else {
                    printVotes($article['n_id']);
                }
                ?>

                <span style='font-size:8pt;color:gray'>
                    <?php
                    echo date("Y-m-d H:i:s", $article['n_writedate']);
                    if($article['n_writedate']!=$article['n_editdate'])
                        echo " (".date("Y-m-d H:i:s", $article['n_editdate'])."에 수정함)";
                ?></span>

                <?php
					$boardbilities=array();
					if(doesAdminBypassEverythingAndIsAdmin($me['n_id']==$article['n_writer'])){
						if(checkCategoryAccess($board_cat['n_id'], "edit")&&!$b_anonymous)
							$boardbilities[]="<a href='/board/{$board_cat['s_id']}/edit/{$article['n_id']}'>편집</a>";
						if(checkCategoryAccess($board_cat['n_id'], "delete")&&!$b_anonymous)
							$boardbilities[]="<a href='/board/{$board_cat['s_id']}/delete/{$article['n_id']}'>삭제</a>";
					}
					if(checkCategoryAccess($board_cat['n_id'], "comment write"))
						$boardbilities[]="<a onclick='return board_putCommentForm({$article['n_id']});'>댓글 달기</a>";
					echo "<div style='float:right;'>".implode(" | ",$boardbilities)."</div>";
				?>
			</div>
            <div class="item_contents" style="padding:10px;padding-bottom:7px;overflow:auto">
                <div id="item_contents_<?php echo $article['n_id'];?>">
<?php
                    if(reportNum($article['n_id']) >= 10) {
                        ?> 신고가 10개 이상 접수되어서 삭제되었습니다. <?php
                    } else {
                        filterContent($article['s_data']);
                    }
                ?></div>
                <div style="display:none" class="item_hidden" id="item_hidden_<?php echo $article['n_id'];?>">(숨김) - 좌측 상단의 '+' 버튼을 눌러서 표시할 수 있습니다.</div>
                <?php
				printAttachList($article, $board_cat, 0);
				?>
				<div class="forum_comment_area">
					<?php if($article['n_comments']>0 && !$suppress_comments){ putCommentTree($article['n_id'],$article['n_id']); } ?>
				</div>
				<div id="article_comment_<?php echo $article['n_id']?>"></div>
			</div>
		<?php } else { ?>
            <div class="item_head" style="padding:6px">
                <?php
                $votes = getVotes($article['n_id']);
                if($b_anonymous) { ?>
                    <span style="font-weight:bold;color:crimson!important;vertical-align:middle">
                        <?php if($article['n_writer'] == 1) echo '진짜 익명';
                        else {
                            if(reportNum($article['n_id']) >= 10) {
                                putUserCard($m);
                            } else {
                                echo '익명';
                            }
                        } ?>
                    </span>
<?php
				} else { ?>
                    <span style="font-weight:bold">
                        <a style="color:black!important;vertical-align:middle" href="<?php echo "/user/view/{$m['n_id']}/{$m['s_id']}" ?>">
<?php putUserCard($m); ?></a>
                    </span>
                <?php }

                if($b_anonymous) {
                    printUpDownVotes($article['n_id']);
                } else {
                    printVotes($article['n_id']);
                }
                ?>
                <span style='font-size:8pt;color:gray'>
                    <?php
                    echo date("Y년 m월 d일 H시 i분 s초", $article['n_writedate']);
                    if($article['n_writedate']!=$article['n_editdate'])
                        echo " (".date("Y년 m월 d일 H시 i분 s초", $article['n_editdate'])."에 수정함)";
                ?></span>
<?php
                $boardbilities=array();
                if(doesAdminBypassEverythingAndIsAdmin($me['n_id']==$article['n_writer'])){
                    if(checkCategoryAccess($board_cat['n_id'], "edit")&&!$b_anonymous)
                        $boardbilities[]="<a href='/board/{$board_cat['s_id']}/edit/{$article['n_id']}'>편집</a>";
                    if(checkCategoryAccess($board_cat['n_id'], "delete")&&!$b_anonymous)
                        $boardbilities[]="<a href='/board/{$board_cat['s_id']}/delete/{$article['n_id']}'>삭제</a>";
                }
                if(checkCategoryAccess($board_cat['n_id'], "comment write"))
                    $boardbilities[]="<a onclick='return board_putCommentForm({$article['n_id']});'>댓글 달기</a>";
                echo "<div style='float:right'>".implode(" | ",$boardbilities)."</div>";
                ?>
            </div>
            <div class="item_contents" style="padding:10px;padding-bottom:7px;overflow:auto">
                <div id="item_contents_<?php echo $article['n_id'];?>">
<?php
                    if(reportNum($article['n_id']) >= 10) {
                        ?> 신고가 10개 이상 접수되어서 삭제되었습니다. <?php
                    } else {
                        filterContent($article['s_data']);
                    }
                ?></div>
                <div style="display:none" class="item_hidden" id="item_hidden_<?php echo $article['n_id'];?>">(숨김) - 좌측 상단의 '+' 버튼을 눌러서 표시할 수 있습니다.</div>
                <?php
                printAttachList($article, $board_cat, 0);
                ?>
                <div class="new_forum_comment_area">
                    <?php if($article['n_comments']>0 && !$suppress_comments){ putCommentTree($article['n_id'],$root); } ?>
                </div>
                <div id="article_comment_<?php echo $article['n_id']?>"></div>
                <div style="clear:both"></div>
            </div>
        <?php } ?>
	</li>
	<?php
}

function printViewPageModeForum($usr, $cat){
	global $article, $board, $member, $is_mobile, $mysqli;
	global $board_id, $board_act, $board_cat, $me;
	global $b_public_article, $b_no_comment, $b_anonymous, $b_bold_title;
	$search=checkCategoryAccess($board_cat['n_id'], "search") && ((isset($_GET['search']) && $_GET['search']!="")?$_GET['search']:false);
	$search_mode_and=isset($_GET['search_mode']) && $_GET['search_mode']=="and";
	$search_submode_and=false; $search_title=isset($_GET['search_title']); $search_data=isset($_GET['search_data']); $search_tag=isset($_GET['search_tag']); $search_writer=isset($_GET['search_writer']);
	$pagenumber=isset($_GET['bcmt'])?$_GET['bcmt']:0;
	$articleperpage=20;
	$orderby_name="n_id";
	$orderby_desc=false;
	$incl_text=0;
	$article_count=$board->getArticleCount(false, false, $article['n_id'], $search, $search_mode_and, $search_submode_and, $search_title, $search_data, $search_tag, $search_writer);
	if(!is_numeric($pagenumber)) $pagenumber=0;
	else if($pagenumber!=0) $pagenumber--;
	if($pagenumber<0) $pagenumber=0;
	$page_count=intval(($article_count+$articleperpage-1)/$articleperpage);
	$comment_data=$board->getArticleList(false, false, $article['n_id'], $pagenumber, $articleperpage, $orderby_name, $orderby_desc, $incl_text, $search, $search_mode_and, $search_submode_and, $search_title, $search_data, $search_tag, $search_writer, true);
	$pagenumber++;
	$k=$_GET; unset($k['action'], $k['bid'], $k['bact'], $k['page'], $k['bitm'], $k['bcmt']);
	$additional_query_string=array();
	foreach($k as $a=>$b)
		$additional_query_string[]=rawurlencode($a)."=".rawurlencode($b);
	$additional_query_string=implode("&",$additional_query_string);
	if($additional_query_string)$additional_query_string="?$additional_query_string";
	?>
	<div class="forum_article_list">
		<ul style="padding: 0">
			<li class="head">
				<div class="total_title">
					<?php echo formatTitle($article['s_title']); ?><span style="color:#DDD"> | <a style="color:#DDD" href="/board/<?php echo $cat['s_id']?>">
<?php echo $cat['s_name']?></a>
					<?php
					if(doesAdminBypassEverythingAndIsAdmin($me['n_id']==$article['n_writer']) || checkCategoryAccess($board_cat['n_id'], "manage modify")){
						$boardbilities=array();
						$exists=false;
						if($res = $mysqli->query("SELECT 1 FROM `kmlaonline_important_notices_table` WHERE n_article={$article['n_id']} LIMIT 1")) {
                            $exists = $res->num_rows > 0;
						}
						$res->close();
						if($exists)
							echo "<span style='color:gray'>(필공 요청함)</span>";
						else
							echo "<a onclick='return board_askImportant(this,{$article['n_id']});'>(필공 신청)</a>";
					}
					?>
                    </span>
				</div>
			</li>
			<?php
			if($pagenumber==1)
				printOneForumItem($article,$article['n_id'],true);
			if(doesAdminBypassEverythingAndIsAdmin(!$b_no_comment && checkCategoryAccess($board_cat['n_id'], "comment view"))){
				foreach($comment_data as $comment)
					printOneForumItem($comment,$article['n_id']);
			}
			?>
		</ul>
	</div>
	<div style="text-align:center">
		<?php $disp=array(1=>true); ?>
		<a href="<?php echo "/board/$board_id/view/{$article['n_id']}/1$additional_query_string" ;?>" <?php if($pagenumber==1) echo "style='color:black'" ?>>[1]</a>
		<?php if(2<$pagenumber-10) echo "..."; ?>
		<?php for($i=max(2,$pagenumber-10); $i<=min($page_count-1, $pagenumber+10); $i++){ ?>
			<a href="<?php echo "/board/$board_id/view/{$article['n_id']}/$i$additional_query_string" ;?>" <?php if($pagenumber==$i) echo "style='color:black'" ?>>[<?php echo $i; $disp[$i]=true;?>]</a>
		<?php } ?>
		<?php if($i<$page_count && $i!=max(2,$pagenumber-10)) echo "..."; ?>
		<?php if(!isset($disp[$page_count]) && $page_count>1){ ?><a href="<?php echo "/board/$board_id/view/{$article['n_id']}/$page_count$additional_query_string" ;?>" <?php if($pagenumber==$page_count) echo "style='color:black'" ?>>[<?php echo $page_count?>]</a>
<?php } ?>
	</div>
	<div style="margin:0 auto;width:120px;text-align:center;">총 <?php echo $article_count+1?>개</div>
	<?php if(checkCategoryAccess($board_cat['n_id'], "search")){ ?>
		<?php if($is_mobile){ ?>
			<div style="clear:both;"></div>
		<?php } ?>
		<div style="float:right;position:relative;">
			<form method="get" action="/board/<?php echo htmlspecialchars($board_id)?>/view/<?php echo $article['n_id']?>">
				<div id="div_search_method">
					<input type="radio" onclick="board_checkSearchMethod();" id="chk_search_mode_and" name="search_mode" value="and" <?php echo $search_mode_and?"checked='checked'":""?> /> <label for="chk_search_mode_and">모든 조건 만족　　</label><br />
					<input type="radio" onclick="board_checkSearchMethod();" id="chk_search_mode_or" name="search_mode" value="or" <?php echo !$search_mode_and?"checked='checked'":""?> /> <label for="chk_search_mode_or">한 조건이라도 만족</label><br />
				</div>
				<div id="div_search_from">
					<input type="checkbox" onclick="board_checkSearchFrom();" id="chk_search_title" name="search_title" value="true" <?php echo ((!$search_title && !$search_data && !$search_tag && !$search_writer) || $search_title)?"checked='checked'":""?> /> <label for="chk_search_title">제목　</label><br />
					<input type="checkbox" onclick="board_checkSearchFrom();" id="chk_search_data" name="search_data" value="true" <?php echo $search_data?"checked='checked'":""?> /> <label for="chk_search_data">내용　</label><br />
					<input type="checkbox" onclick="board_checkSearchFrom();" id="chk_search_tag" name="search_tag" value="true" <?php echo $search_tag?"checked='checked'":""?> /> <label for="chk_search_tag">태그　</label><br />
					<input type="checkbox" onclick="board_checkSearchFrom();" id="chk_search_writer" name="search_writer" value="true" <?php echo $search_writer?"checked='checked'":""?> /> <label for="chk_search_writer">글쓴이</label><br />
				</div>
				<a id="search_from_toggler" onclick="smoothToggleVisibility('#div_search_method', 1); return smoothToggleVisibility('#div_search_from');">제목에서</a>
				<a id="search_method_toggler" onclick="smoothToggleVisibility('#div_search_from', 1); return smoothToggleVisibility('#div_search_method');">모든 조건 만족 시</a>
				<?php if($is_mobile) echo "<br />" ?>
				<input type="text" class="form-control" name="search" value="<?php echo $search?htmlspecialchars($search):""?>" style="margin-left:3px;width:120px;vertical-align:middle; display: inline-block;" />
				<input type="submit" class="btn btn-default" id="search_button" value="검색" style="width:80px;height:32px;vertical-align:middle;" />
			</form>
		</div>
		<?php
		insertOnLoadScript('board_checkSearchFrom();board_checkSearchMethod();');
	}
	?>
	<?php
}
function printViewPage(){
	global $article, $board, $member;
	global $board_id, $board_act, $board_cat, $me;
	global $b_public_article, $b_no_comment, $b_anonymous, $b_bold_title;
	$usr=$member->getMember($article['n_writer']);
	$cat=$board->getCategory($article['n_cat']);
	$mode=$cat['n_viewmode'];
	switch($mode){
	case 0: printViewPageModeBoard($usr, $cat); break;
	case 1: printViewPageModeGallery($usr, $cat); break;
	case 2: printViewPageModeForum($usr, $cat); break;
	}
	/*
	$search=checkCategoryAccess($board_cat['n_id'], "search") && ((isset($_GET['search']) && $_GET['search']!="")?$_GET['search']:false);
	$search_mode_and=isset($_GET['search_mode']) && $_GET['search_mode']=="and";
	$search_submode_and=false; $search_title=isset($_GET['search_title']); $search_data=isset($_GET['search_data']); $search_tag=isset($_GET['search_tag']); $search_writer=isset($_GET['search_writer']);
	$pagenumber=$board->getPageNumber($article['n_id'], 20, "n_id", true, array($board_cat['n_id']), 0, 0, $search, $search_mode_and, $search_submode_and, $search_title, $search_data, $search_tag, $search_writer);
	$_GET['page']=$pagenumber+1;
	printArticleList();
	//*/
}
?>

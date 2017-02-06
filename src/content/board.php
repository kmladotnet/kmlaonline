<?php
include "src/content/board/editor.php";
include "src/content/board/view.php";
$board_id=json_decode($_GET['bid'], true);
if($board_id===null) {
    $board_id=$_GET['bid'];
}
$board_act=isset($_GET['bact'])?$_GET['bact']:"";
$board_item=isset($_GET['bitm'])?$_GET['bitm']:"";
if(is_array($board_id)) {
    if(count($board_id["cat"]) === 1) {
        $board_id = $board->getCategory($board_id["cat"][0])["s_id"];
    }
}
if($board_id=="special:list-all"){
	include "src/content/board/list-all.php";
	$board_act="list";
	$title="모든 게시판 - $title";
}else if($board_id=="special:list-mine"){
	include "src/content/board/list-mine.php";
	$board_act="list";
	$title="내 게시판 - $title";
}else if(is_array($board_id)) {
	$title=getOrDefault($board_id["title"], "여러가지");
	include "src/content/board/list-multi.php";
	$board_act="list";
}else{
	include "src/content/board/list.php";
	if(($board_cat=$board->getCategory(false,$board_id))===false)
		redirectAlert(false,lang("board","category","nonexist").$board_id);
	switch($board_act){
		case "write":
			redirectLoginIfRequired();
			checkCategoryAccess($board_cat['n_id'], "write", true);
			$title=lang("link titles", "board", "write") . " - $title";
			break;
		case "view": 
			if(($article=$board->getArticle($board_item))===false){
				checkCategoryAccess($board_cat['n_id'], "view", true);
				redirectAlert(false, lang("board","article","nonexist"));
			}else{
				if($article['n_cat']!=$board_cat['n_id']){
					$board_cat=$board->getCategory($article['n_cat']);
					redirectTo("/board/{$board_cat['s_id']}/view/{$article['n_id']}");
				}
				checkCategoryAccess($board_cat['n_id'], "view", true);
			}
			$thisone=$article;
			$last_article=false;
			while($article['n_parent']){
				$last_article=$article;
				$article=$board->getArticle($article['n_parent']);
			}
			if($last_article){
				$pagenumber=$board->getPageNumber($last_article['n_id'], 20, "n_id", false, false, false, $article['n_id'])+1;
				redirectTo("/board/$board_id/view/{$article['n_id']}/$pagenumber#article_comment_sub_".$thisone['n_id']);
			}
			
			if(isset($me)){
				if($board->setViewFlag($article['n_id'], false, $me['n_id'])===true)
					$article=$board->getArticle($board_item);
			}else{
				if($board->setViewFlag($article['n_id'], session_id())===true)
					$article=$board->getArticle($board_item);
			}
			$flag=$article['n_flag']; $b_public_article=$flag&0x1; $b_no_comment=$flag&0x2; $b_anonymous=$flag&0x4; $b_bold_title=$flag&0x8;
			$b_public_article=$b_public_article && checkCategoryAccess($board_cat['n_id'], "flag public");
			$b_no_comment=$b_no_comment && checkCategoryAccess($board_cat['n_id'], "flag no comment");
			$b_anonymous=$b_anonymous && checkCategoryAccess($board_cat['n_id'], "flag anonymous");
			$b_bold_title=$b_bold_title && checkCategoryAccess($board_cat['n_id'], "flag bold title");
			$title="{$article["s_title"]} - $title";
			// Prepare
			break;
		case "edit":
			checkCategoryAccess($board_cat['n_id'], "edit", true);
			redirectLoginIfRequired();
			if(($article=$board->getArticle($board_item))===false) redirectAlert(false, lang("board","article","nonexist"));
			if((!isset($me) || $article['n_writer']!=$me['n_id']) && !checkCategoryAccess($board_cat['n_id'], "manage modify")) redirectAlert(false,lang("board","article","notmine"));
			$title=lang("link titles", "board", "edit") . " - {$article["s_title"]} - $title";
			//Prepare
			break;
		case 'comment':
			checkCategoryAccess($board_cat['n_id'], "comment write", true);
			redirectLoginIfRequired();
			if(($article=$board->getArticle($board_item))===false) redirectAlert(false, lang("board","article","nonexist"));
			$title=lang("link titles", "board", "comment write") . " - {$article["s_title"]} - $title";
			break;
		case "delete":
			redirectLoginIfRequired();
			if(($article=$board->getArticle($board_item))===false) redirectAlert(false, lang("board","article","nonexist"));
			if((!isset($me) || $article['n_writer']!=$me['n_id']) && !checkCategoryAccess($board_cat['n_id'], "manage modify")) redirectAlert(false, lang("board","article","notmine"));
			if($article['n_parent'])
				checkCategoryAccess($board_cat['n_id'], "comment delete", true);
			else
				checkCategoryAccess($board_cat['n_id'], "delete", true);
			$title=lang("link titles", "board", "delete confirm") . " - {$article["s_title"]} - $title";
			break;
		default:
			$board_act="list";
			checkCategoryAccess($board_cat['n_id'], "list", true);
			$title="{$board_cat['s_name']} - $title";
	}
}
function printContent(){
	global $board_act;
	printTemplates();
	switch($board_act){
		case "list": printArticleList(); break;
		case "write": printWritePage(); break;
		case "view": printViewPage(); break;
		case "edit": printEditPage(); break;
		case "comment": printCommentPage(); break;
		case "delete": printDeletePage(); break;
	}
}
function printTemplates(){
	global $article, $board_cat, $board_id;
	?>
	<script type="text/html" id="uploadedItemTemplete">
		<li id="uploaded_item_<%=key%>" class="uploaded_item_li">
			<div class="uploaded_item">
				<a id="uploadedItemImgA_<%=key%>" href="" target="_blank">
					<img id="uploadedItemImg_<%=key%>" style="z-index:0;width:100px;position:absolute;left:0;top:0;right:0;bottom:0;" />
				</a>
				<img id="uploadedItemImgDesc_<%=key%>" style="z-index:0;position:absolute;left:50%;top:50%;margin-top:-11px;margin-left:-11px;" />
				<input type="hidden" id="uploadedItemUrl_<%=key%>" name="f_uploaded_files[]" value="" />
				<input type="hidden" id="uploadedItemComment_<%=key%>" name="f_uploaded_files_comment[]" value="" />
				<input type="hidden" id="uploadedItemName_<%=key%>" name="f_uploaded_files_origname[]" value="" />
				<input type="hidden" id="uploadedItemAction_<%=key%>" />
				<div class="info">
					<div id="fileName_<%=key%>"></div>
				</div>
				<div class="act">
					<div class="time_left"><input class="val" type="hidden" /><span class="disp"></span> <?php echo lang("generic","left")?></div>
					<a onclick="return board_uploadedItemAction('<%=key%>',0);"><?php echo lang("generic","insert short")?></a> | 
					<a onclick="return board_uploadedItemAction('<%=key%>',1);"><?php echo lang("generic","remove short")?></a> | 
					<a onclick="return board_uploadedItemAction('<%=key%>',2);"><?php echo lang("generic","description short")?></a>
				</div>
			</div>
		</li>
	</script>
	<?php if(isset($article)){ ?>
		<script type="text/html" id="article_comment_template">
			<div id="article_comment_write_<%=ARTICLEID%>" class="acomment">
				<div style="font-size:15pt;font-weight:bold;margin-top:5px;margin-bottom:5px;"><?php echo lang("board","article","write comment")?></div>
				<form method="post" action="/ajax/board/write" onsubmit="return saveAjax(this,'<?php echo lang("board","article","writing comment")?>');">
					<input type="hidden" id="hidden_n_parent" name="n_parent" value="<%=ARTICLEID%>" />
					<input type="hidden" name="n_top_parent" value="<?php echo $article['n_id']?>" />
					<input type="hidden" name="s_cat" value="<?php echo $board_cat['s_id']?>" />
					<input type="hidden" name="b_auto_html" value="true" />
					<input type="hidden" name="prev_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
					<textarea name="s_data" class="form-control" style="width:100%;height:120px;"><?php if(isset($_POST['s_data'])) echo htmlspecialchars($_POST['s_data']); ?></textarea>
					<input type="submit" class="btn btn-default" value="<?php echo lang("board","article","write comment")?>" style="box-sizing: border-box;width:80px;height:32px;float:right;" />
					<input type="button" class="btn btn-default" onclick="location.href='<?php echo "/board/$board_id/comment/<%=ARTICLEID%>"; ?>';" value="고급" style="box-sizing: border-box;width:80px;height:32px;float:left;" />
					<div style="margin:0 auto">익명으로 글을 쓸 때에는 <b>고급</b>에서 써 주셔야 합니다.</div>
					<div style="clear:both"></div>
				</form>
			</div>
		</script>
	<?php } ?>
	<?php
}

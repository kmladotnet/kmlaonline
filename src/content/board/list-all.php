<?php
function printArticleListTypeBoard($board_data, $additional_query_string)
{
	global $board, $member;
?>
	<table style="width:100%">
		<thead>
			<tr style="height:32px;">
				<th class="no-mobile" style="width:48px; text-align:center">번호</th>
				<th class="no-mobile" style="width:120px;">분류</th>
				<th>제목</th>
				<th style="width:120px; text-align:center">글쓴이</th>
				<th style="width:80px; text-align:center">날짜</th>
				<th class="no-mobile" style="width:48px; text-align:center">조회수</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($board_data as $item) {
				$board_cat = $board->getCategory($item['n_cat']);
				$board_id = $board_cat['s_id'];
				$memb = $member->getMember($item['n_writer']);
				$flag = $item['n_flag'];
				$b_public_article = $flag & 0x1;
				$b_no_comment = $flag & 0x2;
				$b_anonymous = $flag & 0x4;
				$b_bold_title = $flag & 0x8;
				$b_public_article = $b_public_article && checkCategoryAccess($board_cat['n_id'], "flag public");
				$b_no_comment = $b_no_comment && checkCategoryAccess($board_cat['n_id'], "flag no comment");
				$b_bold_title = $b_bold_title && checkCategoryAccess($board_cat['n_id'], "flag bold title");
			?>
				<tr class="board_list_item" style="<?php echo (isset($_GET['bitm']) && $_GET['bitm'] == $item['n_id']) ? "font-weight:bold;" : "" ?>">
					<td class="no-mobile" onclick="return changeLinkTo('<?php echo htmlspecialchars(addslashes("/board/$board_id/view/{$item['n_id']}$additional_query_string")) ?>');" style="font-size:8pt;text-align:center;">
						<?php echo htmlspecialchars($item['n_id']); ?>
					</td>
					<td class="no-mobile" onclick="return changeLinkTo('<?php echo htmlspecialchars(addslashes("/board/$board_id")) ?>');" style="font-size:8pt;text-align:center;">
						<?php echo htmlspecialchars($board_cat['s_name']); ?>
					</td>
					<td onclick="return changeLinkTo('<?php echo htmlspecialchars(addslashes("/board/$board_id/view/{$item['n_id']}$additional_query_string")) ?>');" style="padding-left:5px;">
						<a onclick="return false;" href="<?php echo htmlspecialchars("/board/$board_id/view/" . $item['n_id']) ?>" style="color:black;<?php echo $b_bold_title ? "font-weight:bold;" : ""; ?>">
							<?php
							echo formatTitle($item['s_title']);
							if (($item['n_comments'] != 0 && doesAdminBypassEverythingAndIsAdmin(!$b_no_comment))) {
								echo " <span class='comment-num'>{$item['n_comments']}</span>";
							}

							?>
						</a>
					</td>
					<?php if ($b_anonymous) { ?>
						<td style="text-align:center;" onclick="return changeLinkTo('<?php echo htmlspecialchars(addslashes("/board/$board_id/view/{$item['n_id']}$additional_query_string")) ?>');">
							익명
						</td>
					<?php } else { ?>
						<td style="text-align:center;" onclick="return changeLinkTo('<?php echo htmlspecialchars(addslashes("/user/view/" . $memb['n_id'] . "/" . $memb['s_id'])) ?>');">
							<a href="<?php echo htmlspecialchars("/user/view/" . $memb['n_id'] . "/" . $memb['s_id']) ?>" style="color:black">
								<?php putUserCard($memb); ?></a>
						</td>
					<?php } ?>
					<td style="text-align:center;" onclick="return changeLinkTo('<?php echo htmlspecialchars(addslashes("/board/$board_id/view/{$item['n_id']}$additional_query_string")) ?>');">
						<?php
						if (time() - $item['n_writedate'] < 86400) {
							echo htmlspecialchars(date("H:i:s", $item['n_writedate']));
						} else {
							echo htmlspecialchars(date("y-m-d", $item['n_writedate']));
						}

						?></td>
					<td class="no-mobile" style="text-align:center;" onclick="return changeLinkTo('<?php echo htmlspecialchars(addslashes("/board/$board_id/view/{$item['n_id']}$additional_query_string")) ?>');">
						<?php echo htmlspecialchars($item['n_total_views']); ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php
	insertOnLoadScript('
		$(".board_list_item").mouseenter(function(){
			$(this).css("backgroundColor", "#EEE");
		}).mouseleave(function(){
			$(this).css("backgroundColor", "transparent");
		});
	');
}
function printArticleListTypeForum($board_data, $additional_query_string)
{
}
function printArticleListTypeGallery($board_data, $additional_query_string)
{
}
function printArticleList()
{
	//http://soree-kmla.com/board/all_announce?search_title=true&search_data=true&search_tag=true&search_writer=true&search=asdf
	global $board_id, $board_act, $board, $board_cat, $member, $is_mobile, $me;
	$accessible_categories = getCategoriesWithFixes();
	$articleperpage = 20;
	$orderby_name = "n_id";
	$orderby_desc = true;
	$incl_text = 0;
	$search = (isset($_GET['search']) && $_GET['search'] != "") ? $_GET['search'] : false;
	$search_mode_and = isset($_GET['search_mode']) && $_GET['search_mode'] == "and";
	$search_submode_and = false;
	$search_title = isset($_GET['search_title']);
	$search_data = isset($_GET['search_data']);
	$search_tag = isset($_GET['search_tag']);
	$search_writer = isset($_GET['search_writer']);
	$pagenumber = isset($_GET['page']) ? $_GET['page'] : 0;
	$article_count = $board->getArticleCount($accessible_categories, false, 0, $search, $search_mode_and, $search_submode_and, $search_title, $search_data, $search_tag, $search_writer);
	if (!is_numeric($pagenumber)) {
		$pagenumber = 0;
	} else if ($pagenumber != 0) {
		$pagenumber--;
	}

	if ($pagenumber < 0) {
		$pagenumber = 0;
	}

	$page_count = intval(($article_count + $articleperpage - 1) / $articleperpage);
	if ($board_id === "student_council_election") {
		$orderby_name = "s_title";
	}

	$board_data = $board->getArticleList($accessible_categories, false, 0, $pagenumber, $articleperpage, $orderby_name, $orderby_desc, $incl_text, $search, $search_mode_and, $search_submode_and, $search_title, $search_data, $search_tag, $search_writer);
	if ($search !== false) {
		foreach ($board_data as $key => $val) {
			if ($val['n_flag'] & 0x4) {
				unset($board_data[$key]);
			}
		}
	}
	$pagenumber++;
	$k = $_GET;
	unset($k['action'], $k['bid'], $k['bact'], $k['page'], $k['bitm']);
	$additional_query_string = array();
	foreach ($k as $a => $b) {
		$additional_query_string[] = rawurlencode($a) . "=" . rawurlencode($b);
	}

	$additional_query_string = implode("&", $additional_query_string);
	if ($additional_query_string) {
		$additional_query_string = "?$additional_query_string";
	}

?>
	<h1 style="padding:9px;text-align:left;">모두 보기</h1>
	<?php
	printArticleListTypeBoard($board_data, $additional_query_string);
	?>
	<div style="clear:both"></div>
	<?php if (count($board_data) == 0) { ?>
		<div style="padding-top:60px;padding-bottom:60px;width:100%;text-align:center;font-size:18pt;">
			글이 없습니다.
		</div>
	<?php } ?>
	<div style="text-align:center">
		<?php $disp = array(1 => true); ?>
		<a href="<?php echo "/board/$board_id/page/1$additional_query_string"; ?>" <?php if ($pagenumber == 1) {
																																									echo "style='color:black'";
																																								}
																																								?>>[1]</a>
		<?php if (2 < $pagenumber - 10) {
			echo "...";
		}
		?>
		<?php for ($i = max(2, $pagenumber - 10); $i <= min($page_count - 1, $pagenumber + 10); $i++) { ?>
			<a href="<?php echo "/board/$board_id/page/$i$additional_query_string"; ?>" <?php if ($pagenumber == $i) {
																																										echo "style='color:black'";
																																									}
																																									?>>[<?php echo $i;
						$disp[$i] = true; ?>]</a>
		<?php } ?>
		<?php if ($i < $page_count && $i != max(2, $pagenumber - 10)) {
			echo "...";
		}
		?>
		<?php if (!isset($disp[$page_count]) && $page_count > 1) { ?><a href="<?php echo "/board/$board_id/page/$page_count$additional_query_string"; ?>" <?php if ($pagenumber == $page_count) {
																																																																												echo "style='color:black'";
																																																																											}
																																																																											?>>[<?php echo $page_count ?>]</a>
		<?php } ?>
	</div>
	<div style="margin:0 auto;width:120px;text-align:center;">총 <?php echo $article_count ?>개</div>
	<?php if ($is_mobile) { ?>
		<div style="clear:both;"></div>
	<?php } ?>
	<div style="float:right;position:relative;">
		<form method="get" action="/board/<?php echo htmlspecialchars($board_id) ?>">
			<div id="div_search_method">
				<input type="radio" onclick="board_checkSearchMethod();" id="chk_search_mode_and" name="search_mode" value="and" <?php echo $search_mode_and ? "checked='checked'" : "" ?> /> <label for="chk_search_mode_and">모든 조건 만족　　</label><br />
				<input type="radio" onclick="board_checkSearchMethod();" id="chk_search_mode_or" name="search_mode" value="or" <?php echo !$search_mode_and ? "checked='checked'" : "" ?> /> <label for="chk_search_mode_or">한 조건이라도 만족</label><br />
			</div>
			<div id="div_search_from">
				<input type="checkbox" onclick="board_checkSearchFrom();" id="chk_search_title" name="search_title" value="true" <?php echo ((!$search_title && !$search_data && !$search_tag && !$search_writer) || $search_title) ? "checked='checked'" : "" ?> /> <label for="chk_search_title">제목　</label><br />
				<input type="checkbox" onclick="board_checkSearchFrom();" id="chk_search_data" name="search_data" value="true" <?php echo $search_data ? "checked='checked'" : "" ?> /> <label for="chk_search_data">내용　</label><br />
				<input type="checkbox" onclick="board_checkSearchFrom();" id="chk_search_tag" name="search_tag" value="true" <?php echo $search_tag ? "checked='checked'" : "" ?> /> <label for="chk_search_tag">태그　</label><br />
				<input type="checkbox" onclick="board_checkSearchFrom();" id="chk_search_writer" name="search_writer" value="true" <?php echo $search_writer ? "checked='checked'" : "" ?> /> <label for="chk_search_writer">글쓴이</label><br />
			</div>
			<a id="search_from_toggler" onclick="smoothToggleVisibility('#div_search_method', 1); return smoothToggleVisibility('#div_search_from');">제목에서</a>
			<a id="search_method_toggler" onclick="smoothToggleVisibility('#div_search_from', 1); return smoothToggleVisibility('#div_search_method');">모든 조건 만족 시</a>
			<?php if ($is_mobile) {
				echo "<br />";
			}
			?>
			<input type="text" class="form-control" name="search" value="<?php echo $search ? htmlspecialchars($search) : "" ?>" style="margin-left:3px;width:120px;vertical-align:middle; display: inline-block;" />
			<input type="submit" class="btn btn-default" id="search_button" value="검색" style="width:80px;height:32px;vertical-align:middle;" />
		</form>
	</div>
	<?php
	insertOnLoadScript('board_checkSearchFrom();board_checkSearchMethod();');
	?>
<?php
}

<?php
function printContent(){
	if(isset($_GET['search']) && preg_replace("/(\\s)/","",$_GET['search'])!=""){
		printSearchForm($_GET['search']);
		printSearchResult($_GET['search']);
	}else{
		printSearchForm();
	}
}
function printSearchForm($srch=""){
	?>
	<form method="get" action="/searchall">
		<div style="text-align:center;width:100%">
			<h1><img alt="전체 검색" src="/data/boardimg/searchall.png" style="max-width:100%" /></h1>
			<div style="width:340px;margin:0 auto;display:block;">
				<table style="width:100%">
					<tr style="height:32px;">
						<th style="width:130px;">검색</th>
						<td><input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($srch) ?>" style="width:100%" /></td>
					</tr>
					<!--
					<tr style="height:32px;">
						<th style="width:130px;">순서</th>
						<td>
							<input type="radio" name="orderby" value="score" id="radio_orderby_score" <?php if(!isset($_GET['orderby']) || $_GET['orderby']=="score") echo "checked='checked'" ?> /><label for="radio_orderby_score">정확순</label>
							<input type="radio" name="orderby" value="time" id="radio_orderby_time" <?php if(isset($_GET['orderby']) && $_GET['orderby']!="tie") echo "checked='checked'" ?> /><label for="radio_orderby_time">최신순</label>
						</td>
					</tr>
					-->
				</table>
				<div style="float:right;text-align:right;"><input type="submit" class="btn btn-default" value="검색" style="width:80px;height:32px;" /></div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</form>
	<?php
}
function printSearchResult($search){
	global $board, $member;
	$articleperpage=20;
	$pagenumber=isset($_GET['page'])?$_GET['page']:0;
	$orderby=(isset($_GET['orderby'])?$_GET['orderby']:"")=="time";
	if(!is_numeric($pagenumber)) $pagenumber=0;
	else if($pagenumber!=0) $pagenumber--;
	if($pagenumber<0) $pagenumber=0;
	$result=array_merge(
		searchMember($search, $articleperpage, $pagenumber),
		searchBoard($search, $articleperpage, $pagenumber),
		searchCategories($search, $articleperpage, $pagenumber),
		searchAttachments($search, $articleperpage, $pagenumber)
	);
	if($orderby)
		$GLOBALS['_SORT_BY_']="time";
	else
		$GLOBALS['_SORT_BY_']="score";
	uasort($result, 'search_sort_results');
	?><table style="width:100%" id="found_results" class="table table-hover">
		<thead><tr><th>찾은 곳</th><th>이름</th><th>올린이</th></tr></thead>
		<tbody>
			<?php
			foreach($result as $itm){
                ?>
                <tr>
                    <td><?php echo $itm['found']; ?></td>
                    <td>
                        <a href="<?php echo htmlspecialchars(addslashes($itm['link']))?>">
                            <?php echo $itm['desc'];?>
                            <span style='color:gray;font-size:8pt'><?php
                                echo ($itm['time']==0 ? "(지정되지 않음)" : date("(y-m-d H:i:s)",$itm['time']));
                                ?>
                            </span>
                        </a>
                    </td>
                    <td>
                        <?php echo $itm['writer'];?>
                    </td>
                </tr>
                <?php
			}
			?>
		</tbody>
	</table>
	<?php if(count($result)>0){ ?>
		<div style="text-align:center">
			<form method="get" action="/searchall/<?php echo $pagenumber+2?>">
				<input type="hidden" name="search" value="<?php echo htmlspecialchars($search)?>" />
				<input type="submit" class="btn btn-default" value="계속 찾아보기" style="width:160px;height:32px;" />
			</form>
		</div>
	<?php }else{ ?>
		<div style="text-align:center;padding:14px;font-weight:bold;">검색을 완료했습니다.</div>
	<?php }
}
function searchAttachments($search, $articleperpage, $pagenumber){
	global $board, $member;
	$ret=array();
	foreach($board->getAttachments(false,false,false,$search,false, $pagenumber, $articleperpage, "n_created", false) as $val){
		$article=$board->getArticle($val['n_parent']);
		$category=$board->getCategory($article['n_cat']);
		if(!checkCategoryAccess($category['n_id'], "view")) continue;
		$b_anonymous=($article['n_flag']&0x4) && checkCategoryAccess($category['n_id'], "flag anonymous");
		$val['link']="/board/{$category['s_id']}/view/{$article['n_id']}#__button__open";
		$val['desc']=htmlspecialchars($val['s_name']);
		$val['found']="첨부 파일";
		$val['writer']=$b_anonymous?"익명":putUserCard($member->getMember($article['n_writer']),0,false);
		$val['time']=$val['n_created'];
		$ret[]=$val;
	}
	return search_saveequivalence($search, $ret, array("s_name"));
}
function searchCategories($search, $articleperpage, $pagenumber){
	global $board;
	$ret=array();
	foreach($board->getCategoryList($pagenumber,$articleperpage,$search, true,true) as $val){
		if(false===checkCategoryAccess($val['n_id'],"list")) continue;
		$val['link']="/board/".$val['s_id'];
		$val['desc']=htmlspecialchars($val['s_name']);
		$val['found']="전체 카테고리";
		$val['writer']="(관리자)";
		$val['time']=0;
		$ret[]=$val;
	}
	return search_saveequivalence($search, $ret, array("s_id", "s_name"));
}
function searchMember($search, $articleperpage, $pagenumber){
	global $member;
	$ret=array();
	foreach($member->listMembers($pagenumber,$articleperpage,false,$search, false,false,true,true,true,true,true,true,true,true) as $val){
		if($val['n_id']==1) continue;
		$val['link']="/user/view/".$val['n_id']."/".$val['s_id'];
		$val['desc']=$val['n_level'] . "기 " . htmlspecialchars($val['s_name']);
		$val['found']="전체 사용자";
		$val['writer']="(각각)";
		$val['time']=$val['n_reg_date'];
		$ret[]=$val;
	}
	$ret=search_saveequivalence($search, $ret, array("s_id", "s_name", "s_homepage", "s_email", "s_phone", "s_real_name", "s_interest", "s_status_message"));
	foreach($ret as $key=>$val){
		if($val['n_level'].$val['s_name']==$search)
			$ret[$key]['score']+=200;
		if($val['s_name']==$search)
			$ret[$key]['score']+=170;
	}
	return $ret;
}
function searchBoard($search, $articleperpage, $pagenumber){
	global $board, $member;
	$categories_to_search=array();
	foreach($board->getCategoryList(0,0) as $val){
		if(checkCategoryAccess($val['n_id'], "list") && checkCategoryAccess($val['n_id'], "search")){
			$categories_to_search[]=$val['n_id'];
		}
	}
	$ret=array();
	foreach($board->getArticleList($categories_to_search, false, false, $pagenumber, $articleperpage, "n_id", true, 0, $search, false,false,true,true,true,true, true) as $val){
		$b_no_comment=($val['n_flag']&0x2);
		$b_anonymous=($val['n_flag']&0x4);
		if($b_anonymous && (strpos($member->getMember($val['n_writer'])['s_name'], $search) !== false)) continue;
		$val['link']="/board/{$val['cat']['s_id']}/view/{$val['n_id']}";
		$val['desc']=htmlspecialchars(strip_tags($val['s_title']));
		if($val['n_comments']!=0 && doesAdminBypassEverythingAndIsAdmin(!$b_no_comment))
			$val['desc'].= " <span style='font-size:9pt;color:#008800'>[{$val['n_comments']}]</span>";
		if(isset($val['n_parent']))
			$val['found']="댓글 - " . $val['cat']['s_name'];
		else
			$val['found']="게시판 - " . $val['cat']['s_name'];
		$val['writer']=$b_anonymous?"익명":putUserCard($member->getMember($val['n_writer']),0,false);
		$val['time']=$val['n_writedate'];
		$ret[]=$val;
	}
	return search_saveequivalence($search, $ret, array("s_data", "s_title", "s_tag", "s_writer"));
}
?>

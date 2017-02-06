<?php
set_time_limit(0);
session_write_close();
$article=$board->getArticle($_GET['aid']);
$err=false;
if($article===false) $err=true;
if($err){
	die404();
}else{
	$cat=$board->getCategory($article['n_cat']);
	if(!doesAdminBypassEverythingAndIsAdmin(checkCategoryAccess($cat['n_id'], "attach download")))
		die403();
	else{
		$attaches=$board->getAttachments(false,$article['n_id']);
		//header("Content-Type: text/text"); print_r($article); print_r($cat); print_r($attaches); die();
		$zip=new ZipStream(); //"", "text/text");
		$fpaths=array();
		//public function addDirectory($directoryPath, $timestamp = 0, $fileComment = null) {
		$zip->setComment(html_entity_decode(strip_tags($article['s_data']),ENT_HTML5));
		foreach($attaches as $val){
			//public function addLargeFile($dataFile, $filePath, $timestamp = 0, $fileComment = null)   {
			if(!isset($fpaths[$val['s_name']])){
				$fpaths[$val['s_name']]=1;
				$zip->addLargeFile($val['s_path'], $val['s_name'], $val['n_created'], $val['s_comment']);
			}else{
				$fpaths[$val['s_name']]++;
				$zip->addLargeFile($val['s_path'], "name_duplicates_".$fpaths[$val['s_name']]."/".$val['s_name'], $val['n_created'], $val['s_comment']);
			}
		}
		$zip->finalize();
	}
}
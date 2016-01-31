<?php
set_time_limit(0);
session_write_close();
//RewriteRule ^files/bbs/([^\./]+)/([0-9]+)/([a-z0-9_]+)/(.*)$ process.php?actiontype=file&action=attach&bid=$1&aid=$2&fkey=$3&fname=$4 [L]
// 3. Check Article ID
// 4. Check File Key
$finfo=$board->getAttachments($_GET['fid'], $_GET['aid'], $_GET['fkey'], $_GET['fname'], true);
$article=$board->getArticle($_GET['aid']);
$err=false;
if($finfo===false || $article===false || count($finfo)==0) $err=true;
if(!$err){
	$finfo=$finfo[0];
	// TODO: Check if public file
	if(!checkCategoryAccess($article['n_cat'], "attach download")) die403();
	if($article['n_cat']!=$_GET['bid']) $err=true;
	//if($finfo['s_name']!=$_GET['fname']) $err=true;
}
if($err){
	die404();
}else{
	if(false && !doesAdminBypassEverythingAndIsAdmin(checkCategoryAccess($article['n_cat'], "attach download"))){ // Allow download even without session...
		die403();
	}else{
		$path=$finfo['s_path'];
		$fn=$finfo['s_name'];
		
		$sizex=$sizey=0;
		switch($_GET['mode']){
			case "sizemode_0": $sizex=$sizey=128; break;
			case "sizemode_1": $sizex=640;$sizey=0; break;
			case "sizemode_2": $sizex=240;$sizey=180; break;
			case "sizemode_160": $sizex=160;$sizey=160; break;
		}
		if($sizex!=0 || $sizey!=0){
			
			$thumb_name=$path.".".$_GET['mode'].".jpg";
			if(file_exists($thumb_name) && !isset($_GET['refresh']))
				$path=$thumb_name;
			else{
				resizeImage($path, $thumb_name, $sizex, $sizey);
			}
			if(file_exists($thumb_name))
				$path=$thumb_name;
		}
		
		$fext=strtolower(@end(@explode('.', $fn)));
		$ctype="application/octet-stream";
		if($_GET['mode']!="force" && filesize($path)<16*1024*1024){ // Force download if bigger than 16MB
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$ctype=finfo_file($finfo, $path);
		}
		$etag=$finfo['s_key'];
		$last_modified_time = filemtime($path);
		header_remove('x-powered-by');
		header_remove('expires');
		header_remove('cache-control');
		header_remove("pragma");
        header_remove('Content-Type');
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT"); 
		header("Etag: \"$etag\""); 
		header("Content-Type: $ctype");
		if ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && @strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time) || (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag)) { 
			header("HTTP/1.1 304 Not Modified"); 
			header("Status: 304 Not Modified");
			die();
		}
		outRange($path);
	}
}

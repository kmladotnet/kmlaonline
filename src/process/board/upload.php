<?php
redirectLoginIfRequired();
if(count($_FILES)==0)
	die(json_encode(array("error"=>"파일 크기가 너무 큽니다.")));
$file_path="data/temp/";
@mkdir($file_path,0777,true);
$file_real_name=sanitizeFileName(@end(explode('/', $_FILES['Filedata']['name'])));
$fn=uniqid("up_",true);
$rfile=$file_path.$fn;
if(@move_uploaded_file($_FILES['Filedata']['tmp_name'], $rfile)===false){
	die(json_encode(array("error"=>"알 수 없는 오류가 발생하였습니다.", "debug_rfile"=>$rfile, "debug_"=>$_FILES['Filedata']['tmp_name'])));
}else
	echo json_encode(array(
		"error"=>0,
		"filename"=>"/".$file_path.rawurlencode($fn),
		"disp_filename"=>$file_real_name
	));
if ($dh = opendir($file_path)) {
	while (($file = readdir($dh)) !== false) {
		if(str_replace(".","",$file)=="") continue;
		if(filemtime($file_path . $file)+86400<time())
			unlink($file_path.$file);
	}
	closedir($dh);
}
<?php
header("Content-Type: text/javascript");
$ff=@fopen("./combined.js","w");
fwrite($ff,"/*TEST*/");
function js($path) {
	global $ff;
	if ($handle = opendir($path)) {
		while (false !== ($entry = readdir($handle))) {
			if($entry==="." || $entry===".." || $entry==='combined.js') continue;
			if(is_dir($path."/".$entry)) js("$path/$entry");
			if(substr($entry,-3,3)!==".js") continue;
			$data=file_get_contents("$path/$entry");
			$data=preg_replace("%(//[^\\r\\n]+)[\\r\\n]%","",$data);
			//$data=str_replace("\r","",$data);
			//$data=str_replace("\n","",$data);
			//$data=str_replace("\t","",$data);
			$data="/* [$path/$entry] */$data";
			@fwrite($ff,$data);
			echo $data;
		}
		closedir($handle);
	}
}
js('.');
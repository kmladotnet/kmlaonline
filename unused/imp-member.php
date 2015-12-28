<?php
die("");
require("src/lib.php");
$d=file_get_contents("data/member.txt");
$d=iconv("euc-kr","utf-8",$d);
$d=explode("\r\n",$d);
$_i=0;
date_default_timezone_set("Asia/Seoul");
function mine($t){
	//int mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )
	preg_match("/([0-9]+)-([0-9]+)-([0-9]+) (오전|오후) ([0-9]+):([0-9]+):([0-9]+)/",$t,$m);
	return mktime($m[5]+($m[4]==="오후"?12:0),$m[6], $m[7], $m[2], $m[3], $m[1]);
}
foreach($d as $v){
	if($_i++==0) continue;
	$v=explode("\t",$v);
	print_r($v);
	if($v[16]!=="TRUE") continue;
	$s_email=$s_id=$v[0]; $s_pw=$v[1];
	$s_kor_name=$v[2];
	$n_level=$v[3];
	$s_eng_name=$v[4]." ".$v[5]." ".$v[6];
	$n_stu_id=$v[7];
	$n_gender=$v[8]==="FALSE"?1:2;
	preg_match("/([0-9]+)-([0-9]+)-([0-9]+).*/",$v[9],$matches);
	$b_yr=$matches[1];
	$b_m=$matches[2];
	$b_d=$matches[3];
	$s_phone=substr("000".$v[10],-3,3)."-".substr("0000".$v[11],-4,4)."-".substr("0000".$v[12],-4,4);
	$s_homepage=$v[15];
	$s1=mine($v[13]);
	$s2=mine($v[14]);
	echo "<br>";
	echo "$b_yr-$b_m-$b_d";
	echo "<br>";
	echo $s_kor_name.": ".$s_phone."/".date("Y-m-d H:i:s", $s1)."/".date("Y-m-d H:i:s", $s2) . "/".$s_homepage;
	echo "<hr>";
	//*
	$mid=$member->addMember($s_id, $s_pw, $s_kor_name, $s_email, 0,$n_level,
					$s_homepage, $s_phone, "", "", "", $s_eng_name, $b_yr, $b_m, $b_d,
					$n_gender, "", "");
	$mysqli->query("UPDATE kmlaonline_member_data SET n_reg_date=$s1, n_access_date=$s2");
	//*/
}

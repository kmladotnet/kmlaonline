<?php
require_once(dirname(__FILE__)."/Soreemember.php");
require_once(dirname(__FILE__)."/Soreeboard.php");
require_once(dirname(__FILE__)."/Soreecaptcha.php");
function initializeSoreeTools($server, $id, $pw, $dbname, $force_renew=false){
	
	if($force_renew){
		$mysqli=new mysqli($server, $id, $pw);
		$mysqli->query("drop database " . $dbname);
		$mysqli->close();
	}
		
	global $board, $member, $mysqli;
	$mysqli=@new mysqli($server, $id, $pw, $dbname);
	$newdb=false;
	if($mysqli->connect_error){
		$newdb=true;
		$mysqli=new mysqli($server, $id, $pw);
		if($mysqli->connect_error){
			return false;
		}
		$mysqli->query("create database " . $dbname);
		$mysqli->query("use " . $dbname);
		$mysqli->set_charset("utf8");
	}
	$member=new Soreemember($mysqli, "{$dbname}_member");
	$board=new Soreeboard($mysqli,"{$dbname}_board", $member);
	if($newdb || $force_renew){
		$member->prepareFirstUse();
		$board->prepareFirstUse();
	}
	return $mysqli;
}
?>
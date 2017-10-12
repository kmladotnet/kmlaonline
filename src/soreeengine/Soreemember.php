<?php
require_once(dirname(__FILE__)."/searchlib.php");
class Soreemember{
	private $mysqli, $table_prefix;
	private $attach_path="./data/member/";
	private $table_data, $table_additional_data, $table_note, $table_block, $table_notice;
	private $member_cache=array();

	private function escape($str){ // shortcut for Mysqli real escape string
		return $this->mysqli->real_escape_string($str);
	}

	public function getTableData(){ return $this->table_data; }

	function prepareFirstUse(){
		$query=array();
		array_push($query,"CREATE TABLE IF NOT EXISTS `$this->table_data` (".
								"n_id BIGINT NOT NULL AUTO_INCREMENT,".

								/* Required */
								"s_email char(255), KEY s_email (s_email), ".
								"s_id char(64) NOT NULL, UNIQUE KEY s_id (s_id), ".
								"s_pw VARCHAR(1024) NOT NULL,".
								"s_pw_salt VARCHAR(1024) NOT NULL,".
								"s_pw_hash TINYTEXT NOT NULL,".
								"s_name TINYTEXT NOT NULL,".

								/* Internal */
								"n_reg_date BIGINT NOT NULL DEFAULT 0,".
								"n_access_date BIGINT NOT NULL DEFAULT 0,".

								"n_posts_started BIGINT NOT NULL DEFAULT 0,".
								"n_posts_participated BIGINT NOT NULL DEFAULT 0,".
								"n_point BIGINT NOT NULL DEFAULT 0,".
								"n_level BIGINT NOT NULL DEFAULT 0,".
								"n_admin INT NOT NULL DEFAULT 0,".

								/* Optional */
								"s_real_name TINYTEXT,".

								"n_birth_date_yr INT NOT NULL DEFAULT 0,".
								"n_birth_date_month TINYINT NOT NULL DEFAULT 0,".
								"n_birth_date_day TINYINT NOT NULL DEFAULT 0,".

								"n_gender TINYINT NOT NULL DEFAULT 0,". // 0: Unspecified 1: Male 2: Female 3: Other

								"s_homepage TEXT,".
								"s_phone VARCHAR(32),".
								"s_selfintro TEXT,".
								"s_status_message TEXT,".
								"s_interest TEXT,".
								"s_pic TEXT,".
								"s_icon TEXT,".

								/* Notes & Notices */ // TODO
								"n_notices INT NOT NULL DEFAULT 0,".
								"n_note_new INT NOT NULL DEFAULT 0,".
								"n_note_got INT NOT NULL DEFAULT 0,".
								"n_note_sent INT NOT NULL DEFAULT 0,".

								"PRIMARY KEY (n_id))");
		array_push($query,"INSERT INTO `$this->table_data` (s_email, s_id, s_pw, s_pw_salt, s_pw_hash, s_name) VALUES ('',':anonymous','','','sha512','Anonymous')");
		array_push($query,"CREATE TABLE IF NOT EXISTS `$this->table_additional_data` (".
								"n_id BIGINT, KEY n_id (n_id), FOREIGN KEY (n_id) REFERENCES `$this->table_data`(n_id) ON DELETE CASCADE, ".
								"s_name char(64), KEY s_name (s_name), ".
								"s_value TEXT".
								")");
		array_push($query,"CREATE TABLE IF NOT EXISTS `$this->table_note` (".
								"n_id BIGINT NOT NULL AUTO_INCREMENT,".
								"n_owner BIGINT NOT NULL, KEY n_owner (n_owner), FOREIGN KEY (n_owner) REFERENCES `$this->table_data`(n_id) ON DELETE CASCADE, ".
								"n_from BIGINT NOT NULL, KEY n_from (n_from), FOREIGN KEY (n_from) REFERENCES `$this->table_data`(n_id) ON DELETE CASCADE, ".
								"n_to BIGINT NOT NULL, KEY n_to (n_to), FOREIGN KEY (n_to) REFERENCES `$this->table_data`(n_id) ON DELETE CASCADE, ".
								"n_date BIGINT NOT NULL,".
								"s_title TEXT,".
								"s_data MEDIUMTEXT,".
								"n_read TINYINT NOT NULL DEFAULT 0,".
								"PRIMARY KEY (n_id))");
		array_push($query,"CREATE TABLE IF NOT EXISTS `$this->table_block` (".
								"n_id BIGINT NOT NULL AUTO_INCREMENT,".
								"n_from BIGINT, KEY n_from (n_from), FOREIGN KEY (n_from) REFERENCES `$this->table_data`(n_id) ON DELETE CASCADE, ".
								"n_to BIGINT, KEY n_to (n_to), FOREIGN KEY (n_to) REFERENCES `$this->table_data`(n_id) ON DELETE CASCADE, ".
								"s_note TEXT,".
								"PRIMARY KEY (n_id))");
		array_push($query,"CREATE TABLE IF NOT EXISTS `$this->table_notice` (".
								"n_id BIGINT NOT NULL AUTO_INCREMENT,".
								"n_to BIGINT, KEY n_to (n_to), FOREIGN KEY (n_to) REFERENCES `$this->table_data`(n_id) ON DELETE CASCADE, ".
								"n_time BIGINT,".
								"s_fnkey TEXT,".
								"s_desc TEXT,".
								"s_url TEXT,".
								"n_seen TINYINT NOT NULL DEFAULT 0,".
								"PRIMARY KEY (n_id))");
		$this->mysqli->autocommit(false);
		foreach($query as $val){
			if($this->mysqli->query($val)===false){
				echo $val . ": " . $this->mysqli->error;
				$this->mysqli->rollback(); $this->mysqli->autocommit(true);
				return false;
			}
		}
		$this->mysqli->commit(); $this->mysqli->autocommit(true);
		return true;
	}
	function __construct($db, $tableprefix) {
		$this->table_prefix=$tableprefix;
		$this->mysqli=$db;
		$this->table_data=$this->escape($this->table_prefix . "_data");
		$this->table_note=$this->escape($this->table_prefix . "_note");
		$this->table_block=$this->escape($this->table_prefix . "_block");
		$this->table_notice=$this->escape($this->table_prefix . "_notice");
		$this->table_additional_data=$this->escape($this->table_prefix . "_additional_data");
	}
	function __destruct() {
	}

	function modifyPostsStarted($id, $how){
		if(!is_numeric($id) || !is_numeric($how)) return false;
		return $this->mysqli->query("UPDATE `$this->table_data` SET n_posts_started=n_posts_started+$how WHERE n_id=$id");
	}
	function modifyPostsParticipated($id, $how){
		if(!is_numeric($id) || !is_numeric($how)) return false;
		return $this->mysqli->query("UPDATE `$this->table_data` SET n_posts_participated=n_posts_participated+$how WHERE n_id=$id");
	}

	function setAdditionalData($id, $key, $value){
		if(!is_numeric($id) || $key=="") return false;
		$key=$this->escape($key);
		$value=$this->escape($value);
		$this->mysqli->autocommit(false);
		if($this->mysqli->query("DELETE FROM `$this->table_additional_data` WHERE n_id=$id AND s_name='$key'")===true){
			if($this->mysqli->query("INSERT INTO `$this->table_additional_data` (n_id, s_name, s_value) VALUES ($id, '$key', '$value')")===true){
				$this->mysqli->commit();$this->mysqli->autocommit(true);
				return true;
			}
		}
		$this->mysqli->rollback();$this->mysqli->autocommit(true);
		return false;
	}

	function getAdditionalData($id, $key=false){
		if(!is_numeric($id)) return false;
		$query="SELECT * FROM `$this->table_additional_data` WHERE n_id=$id";
		if($key!==false) $query.=" AND s_name='$key'";
		$arr=array(); $val=null;
		if($res=$this->mysqli->query($query)){
			while ($row = $res->fetch_array(MYSQLI_BOTH)){
				$val=$row['s_value'];
				$arr[$row['s_name']]=$row['s_value'];
			}
		}
		if(!isset($arr['s_room']))$arr['s_room']="";
		if(!isset($arr['n_grade']))$arr['n_grade']="";
		if(!isset($arr['s_class']))$arr['s_class']="";
		if(!isset($arr['n_student_id']))$arr['n_student_id']="";
		if($key===false) return $arr;
		return $val;
	}

	//재학생 구별용 (학년 데이터를 가지고 있는지 확인)
	function getCurrentMembers () {
		$query = "SELECT * FROM `$this->table_additional_data` WHERE s_name = 'n_grade'";
		$arr=array();
		if($res = $this->mysqli->query($query)){
			while ($row = $res->fetch_array(MYSQLI_ASSOC)){
				$temp_id = (int) $row['n_id'];
				$temp_member = $this->getMemberWithEssentials($temp_id);
				$temp = array_merge($temp_member, $this->getAdditionalData($temp_id));
				array_push($arr, $temp);
			}
		}
		return $arr;
	}

	function addMember($id, $pw, $name, $email, $point=0, $level=0, $homepage="", $phone="", $selfintro="", $pic="", $icon="", $s_real_name="", $n_birth_date_yr=0, $n_birth_date_month=0, $n_birth_date_day=0, $n_gender=0, $s_status_message="", $s_interest=""){
		if(!is_numeric($point) || !is_numeric($level)) return false;
		$pw_hash="sha512"; // ripe320 is better
		$pw_salt="";
		$avail='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,./;\'[]\\`-=~!@#$%^&*()_+{}|:"<>?';
		for($i=0;$i<768;$i++) $pw_salt.=substr($avail,rand(0,strlen($avail)-1),1);
		$pw_encoded=hash($pw_hash, $pw_salt . "|" . hash($pw_hash, $pw) . "|" . $pw_salt);
		$this->mysqli->autocommit(false);
		$query="INSERT INTO `$this->table_data` (s_id, s_pw, s_pw_salt, s_pw_hash, s_name, n_reg_date, n_access_date, n_point, n_level, s_email, s_homepage, s_phone, s_selfintro, s_pic, s_icon, s_real_name, n_birth_date_yr, n_birth_date_month, n_birth_date_day, n_gender, s_status_message, s_interest) VALUES (" .
					"'" . $this->escape($id) . "', ".
					"'" . $this->escape($pw_encoded) . "', ".
					"'" . $this->escape($pw_salt) . "', ".
					"'" . $this->escape($pw_hash) . "', ".
					"'" . $this->escape($name) . "', ".
					time() . ", " .
					time() . ", " .
					$point . ", " .
					$level . ", " .
					"'" . $this->escape($email) . "', ".
					"'" . $this->escape($homepage) . "', ".
					"'" . $this->escape($phone) . "', ".
					"'" . $this->escape($selfintro) . "', ".
					"'" . $this->escape($pic) . "', ".
					"'" . $this->escape($icon) . "', ".
					"'" . $this->escape($s_real_name) . "', ".
					$n_birth_date_yr . ", " .
					$n_birth_date_month . ", " .
					$n_birth_date_day . ", " .
					$n_gender.", ".
					"'" . $this->escape($s_status_message) ."', ".
					"'" . $this->escape($s_interest) . "'".
					")";
		//echo nl2br($query) . "<br />";//return false;
		if($this->mysqli->query($query)===true){
			$ins_id=$this->mysqli->insert_id;
			$this->mysqli->commit(); $this->mysqli->autocommit(true);
			return $ins_id;
		}else{
			//echo $this->mysqli->error;
			$this->mysqli->rollback(); $this->mysqli->autocommit(true);
			return false;
		}
	}

	function getMemberNameById($id){
		if(!is_numeric($id)) return false;

		$query = "SELECT s_name FROM `$this->table_data` WHERE n_id = $id";

		if($res = $this->mysqli->query($query)){
			while($row = $res->fetch_assoc()){
				return $row['s_name'];
			}
		} else {
			return false;
		}
	}

	function recordMemberAccess($member){
		if(!is_numeric($member)) return false;
		$query="UPDATE `$this->table_data` SET n_access_date=".time()." WHERE n_id=$member";
		if($this->mysqli->query($query)===true){
			return true;
		}else{
			echo $this->mysqli->error;
			return false;
		}
	}
	function authMember($id, $pw=false, $pw_enc=false){
		if($id=="") return -2;
		$id=$this->escape($id);
		$query="SELECT * FROM `$this->table_data` WHERE LCASE(s_id)=LCASE('$id') OR LCASE(s_email)=LCASE('$id')";
		if($res=$this->mysqli->query($query)){
			while ($row = $res->fetch_array(MYSQLI_BOTH)){
				$res->close();
				if($this->mysqli->more_results())$this->mysqli->next_result();
				if($pw_enc!==false && $row['s_pw']==hash($row['s_pw_hash'],$row['s_pw_salt']."|".$pw_enc."|".$row['s_pw_salt']))
					return 0; // Succeed
				if($pw!==false && $row['s_pw']==hash($row['s_pw_hash'],$row['s_pw_salt']."|".hash($row["s_pw_hash"],$pw)."|".$row['s_pw_salt']))
					return 0; // Succeed
				return -3; // Password error
			}
			return -2; // ID Error
		}
		return -1; // Something went wrong
	}
	function getMember($member,$by=0,$withpw=false){
		if($by==0 && !is_numeric($member)) return false;
		else if($by==1 && strlen($member)==0) return false;
		else if($by>2) return false;

		$query="SELECT * FROM `$this->table_data` WHERE ";
		$member=$this->escape($member);
		switch($by){
			case 0: if(isset($this->member_cache["n_id:".$member])){return $this->member_cache["n_id:".$member];} $query.="n_id=$member"; break;
			case 1: if(isset($this->member_cache["s_id:".$member])){return $this->member_cache["s_id:".$member];} $query.="s_id='$member'"; break;
			case 2: if(isset($this->member_cache["s_email:".$member])){ return $this->member_cache["s_email:".$member];} $query.="s_email='$member'"; break;
		}
		if($res=$this->mysqli->query($query)){
			while ($row = $res->fetch_array(MYSQLI_ASSOC)){
				$res->close();
				if($this->mysqli->more_results())$this->mysqli->next_result();
				if($withpw===false){
					unset($row['s_pw'], $row['s_pw_salt'], $row['s_pw_hash']);
				}
				$this->member_cache["n_id:".$row['n_id']]=$this->member_cache["s_id:".$row['s_id']]=$this->member_cache["s_email:".$row['s_email']]=$row;
				return $row;
			}
		}
		return false;
	}
	// 중요 정보만 받음
	function getMemberWithEssentials($member,$by=0,$withpw=false){
		if($by==0 && !is_numeric($member)) return false;
		else if($by==1 && strlen($member)==0) return false;
		else if($by>2) return false;

		$member=$this->escape($member);

		$query="SELECT n_id, s_name, n_level, s_phone FROM `$this->table_data` WHERE n_id = $member";

		if($res=$this->mysqli->query($query)){
			while ($row = $res->fetch_array(MYSQLI_ASSOC)){
				$res->close();
				if($this->mysqli->more_results())$this->mysqli->next_result();
				return $row;
			}
		}
		return false;
	}
	function getAdminPermission($member){
		if(!is_numeric($member)) return false;
		$query="SELECT n_admin FROM `$this->table_data`";
		if($this->mysqli->query($query)===true){
			while ($row = $res->fetch_array(MYSQLI_BOTH)){
				$res->close();
				if($this->mysqli->more_results())$this->mysqli->next_result();
				return $row[0];
			}
			return true;
		}else{
			echo $this->mysqli->error;
			return false;
		}
	}
	function setAdminPermission($member, $permission=0){
		if(!is_numeric($permission) || !is_numeric($member)) return false;
		$query="UPDATE `$this->table_data` SET n_admin=$permission WHERE n_id=$member";
		if($this->mysqli->query($query)===true){
			return true;
		}else{
			echo $this->mysqli->error;
			return false;
		}
	}
	function editMember($member, $new_id=false, $pw=false, $name=false, $email=false, $point=false, $level=false, $homepage=false, $phone=false, $selfintro=false, $pic=false, $icon=false, $s_real_name=false, $n_birth_date_yr=false, $n_birth_date_month=false, $n_birth_date_day=false, $n_gender=false, $s_status_message=false, $s_interest=false){
		if(!is_numeric($member)) return false;
		if($point!==false && !is_numeric($point)) return false;
		if($level!==false && !is_numeric($level)) return false;
		$a=array();
		if($new_id!==false) $a['s_id']=$new_id;
		if($pw!==false){
			$pw_hash="sha512"; // ripe320 is better
			$pw_salt="";
			$avail='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,./;\'[]\\`-=~!@#$%^&*()_+{}|:"<>?';
			for($i=0;$i<768;$i++) $pw_salt.=substr($avail,rand(0,strlen($avail)-1),1);
			$pw_encoded=hash($pw_hash, $pw_salt . "|" . hash($pw_hash, $pw) . "|" . $pw_salt);
			$a['s_pw']=$pw_encoded;
			$a['s_pw_salt']=$pw_salt;
			$a['s_pw_hash']=$pw_hash;
		}
		if($name!==false) $a['s_name']=$name;
		if($email!==false) $a['s_email']=$email;
		if($point!==false) $a['n_point']=$point;
		if($level!==false) $a['n_level']=$level;
		if($homepage!==false) $a['s_homepage']=$homepage;
		if($phone!==false) $a['s_phone']=$phone;
		if($selfintro!==false) $a['s_selfintro']=$selfintro;
		if($pic!==false) $a['s_pic']=$pic;
		if($icon!==false) $a['s_icon']=$icon;
		if($s_interest!==false) $a['s_interest']=$s_interest;
		if($s_status_message!==false) $a['s_status_message']=$s_status_message;
		if($n_birth_date_yr!==false) $a['n_birth_date_yr']=$n_birth_date_yr;
		if($n_birth_date_month!==false) $a['n_birth_date_month']=$n_birth_date_month;
		if($n_birth_date_day!==false) $a['n_birth_date_day']=$n_birth_date_day;
		if(count($a)==0) return false;
		$b=array();
		foreach($a as $key=>$val){
			array_push($b, $key . "='" . $this->escape($val) . "'");
		}
		$query="UPDATE `$this->table_data` SET " . implode(", ",$b) . " WHERE n_id=$member";
		//echo $query;
		if($this->mysqli->query($query)===true){
			return true;
		}else{
			echo $this->mysqli->error;
			return false;
		}
	}
	function removeMember($member){
		if(!is_numeric($member)) return false;
		if($member==1) return false;
		$usr=getMember($member);
		// TODO remove s_pic, s_icon
		return editMember($member, "", "", "", "", 0, 0, "", "", "", "", "");
	}
	function sendNote($member_from, $member_to, $data, $title=""){
		if(!is_numeric($member_from) || !is_numeric($member_to)) return false;
		if($data=="") return false;
		$title=$this->escape($title); $data=$this->escape($data);
		$date=time();
		if($member_from!=$member_to){
			if(
				($this->mysqli->query("INSERT INTO `$this->table_note` (n_owner, n_from, n_to, n_date, s_title, s_data) VALUES ($member_from, $member_from, $member_to, $date, '$title', '$data')")===true) &&
				($this->mysqli->query("INSERT INTO `$this->table_note` (n_owner, n_from, n_to, n_date, s_title, s_data) VALUES ($member_to, $member_from, $member_to, $date, '$title', '$data')")===true)
				)
				return $this->mysqli->insert_id;
			else
				return false;
		}else{
			return
				($this->mysqli->query("INSERT INTO `$this->table_note` (n_owner, n_from, n_to, n_date, s_title, s_data) VALUES ($member_from, $member_from, $member_to, $date, '$title', '$data')")===true);
		}
	}
	function removeNote($idx){
		if(!is_numeric($idx)) return false;
		return $this->mysqli->query("DELETE FROM `$this->table_note` WHERE n_id=$idx")===true;
	}
	function removeNoteOfUser($owner){
		if(!is_numeric($owner)) return false;
		return $this->mysqli->query("DELETE FROM `$this->table_note` WHERE n_owner=$owner")===true;
	}
	function readNote($idx){
		if(!is_numeric($idx)) return false;
		$query="SELECT * FROM `$this->table_note` WHERE n_id=$idx";
		if($res=$this->mysqli->query($query)){
			while ($row = $res->fetch_array(MYSQLI_BOTH)){
				$res->close();
				if($this->mysqli->more_results())$this->mysqli->next_result();
				return $row;
			}
		}
		return false;
	}
	function checkNoteRead($idx){
		if(!is_numeric($idx)) return false;
		$query="UPDATE `$this->table_note` SET n_read=1 WHERE n_id=$idx";
		return $this->mysqli->query($query)===true;
	}
	function getNotesCount($owner=false, $to_member=false, $from_member=false, $search=false, $search_mode_and=true, $search_submode_and=true, $search_title=false,$search_data=false){
		if($owner!==false && !is_numeric($owner)) return false;
		if($to_member!==false && $to_member!==true && !is_numeric($to_member)) return false;
		if($from_member!==false && $from_member!==true && !is_numeric($from_member)) return false;
		$wheres=array();
		if($owner!==false) $wheres[] = "n_owner=$owner";
		if($to_member!==false) $wheres[] = "n_to=$to_member";
		if($from_member!==false) $wheres[] = "n_from=$from_member";
		if($search){
			$where=array();
			if($search_title) $where[]="s_title";
			if($search_data) $where[]="s_data";
			$wheres[]=search_wherequery($search,$where,$search_mode_and?"AND":"OR",$search_submode_and?"AND":"OR");
		}
		$whereq=implode(" AND ",$wheres);
		if($whereq) $whereq="WHERE $whereq";
		$what="*";
		if($to_member===true)
			$what="DISTINCT n_to";
		else if($from_member===true)
			$what="DISTINCT n_from";
		$query="SELECT count($what) FROM `$this->table_note` $whereq";
		if($res=$this->mysqli->query($query)){
			while ($row = $res->fetch_array(MYSQLI_BOTH))
				return $row[0];
			$res->close();
			if($this->mysqli->more_results())$this->mysqli->next_result();
			return false;
		}
		echo $this->mysqli->error;
		return false;
	}
	function getNotesList($owner=false, $to_member=false, $from_member=false, $pagenumber=0, $pagecount=20, $search=false, $search_mode_and=true, $search_submode_and=true, $search_title=false,$search_data=false){
		if($owner!==false && !is_numeric($owner)) return false;
		if($to_member!==false && $to_member!==true && !is_numeric($to_member)) return false;
		if($from_member!==false && $from_member!==true && !is_numeric($from_member)) return false;
		$wheres=array();
		if($owner!==false) $wheres[] = "n_owner=$owner";
		if($to_member!==false) $wheres[] = "n_to=$to_member";
		if($from_member!==false) $wheres[] = "n_from=$from_member";
		if($search){
			$where=array();
			if($search_title) $where[]="s_title";
			if($search_data) $where[]="s_data";
			$wheres[]=search_wherequery($search,$where,$search_mode_and?"AND":"OR",$search_submode_and?"AND":"OR");
		}
		$whereq=implode(" AND ",$wheres);
		if($whereq) $whereq="WHERE $whereq";
		$what="*";
		if($to_member===true)
			$what="DISTINCT n_to";
		else if($from_member===true)
			$what="DISTINCT n_from";
		$query="SELECT $what FROM `$this->table_note` $whereq ORDER BY n_id DESC LIMIT " . ($pagenumber*$pagecount) . "," . $pagecount;
		if($res=$this->mysqli->query($query)){
			$arr=array();$i=0;
			while ($row = $res->fetch_array(MYSQLI_ASSOC)){
				$arr[$i++]=$row;
			}
			$res->close();
			if($this->mysqli->more_results())$this->mysqli->next_result();
			return $arr;
		}
		echo $this->mysqli->error;
		return false;
	}
	function listMembersBirth($month, $day){
		if(!is_numeric($month) || !is_numeric($day)) return false;
		$query="SELECT * FROM `$this->table_data` WHERE n_birth_date_month=$month AND n_birth_date_day=$day";
		if($res=$this->mysqli->query($query)){
			$arr=array();$i=0;
			while ($row = $res->fetch_array(MYSQLI_ASSOC)){
				unset($row['s_pw'], $row['s_pw_hash'], $row['s_pw_salt']);
				$row['data_type']="member";
				$arr[$row['n_id']]=$row;
			}
			$res->close();
			if($this->mysqli->more_results())$this->mysqli->next_result();
			return $arr;
		}
		echo $this->mysqli->error;
		return false;
	}
	function listMembers($page=0, $count=20, $level=false, $search=false, $search_mode_and=true, $search_submode_and=true, $search_id=false, $search_name=false, $search_homepage=false, $search_email=false, $search_phone=false, $search_real_name=false, $search_interest=false ,$search_status_message=false){
		if(!is_numeric($page) || !is_numeric($count)) return false;
		if($level!==false && !is_numeric($level)) return false;
		$whereq="";
		if($search!==false){
			if($search_id) $where[]="s_id";
			if($search_name) $where[]="s_name";
			if($search_homepage) $where[]="s_homepage";
			if($search_email) $where[]="s_email";
			if($search_phone) $where[]="s_phone";
			if($search_real_name) $where[]="s_real_name";
			if($search_interest) $where[]="s_interest";
			if($search_status_message) $where[]="s_status_message";
			$where=array("(".search_wherequery($search,$where,$search_mode_and?"AND":"OR",$search_submode_and?"AND":"OR").")");
		}
		if($level!==false) $where[]="(n_level=$level)";

		if(isset($where) && count($where)) $whereq="WHERE " . implode("AND", $where);
		$query="SELECT * FROM `$this->table_data` $whereq";
		if($count!=0) $query.=" LIMIT " . ($page*$count) . "," . $count;
		if($res=$this->mysqli->query($query)){
			$arr=array();$i=0;
			while ($row = $res->fetch_array(MYSQLI_ASSOC)){
				unset($row['s_pw'], $row['s_pw_hash'], $row['s_pw_salt']);
				$row['data_type']="member";
				$arr[$row['n_id']]=$row;
			}
			$res->close();
			if($this->mysqli->more_results())$this->mysqli->next_result();
			return $arr;
		}
		echo $this->mysqli->error;
		return false;
	}
	function getNoticeCount($member){
		$whereq="WHERE n_to=$member AND n_seen=0";
		$query="SELECT count(*) FROM `$this->table_notice` $whereq";
		if($res=$this->mysqli->query($query)){
			while ($row = $res->fetch_array(MYSQLI_ASSOC)){
				$ret=$row['count(*)'];
			}
			$res->close();
			if($this->mysqli->more_results())$this->mysqli->next_result();
			return $ret;
		}
		return false;
	}
	function getNotices($member, $count=20, $since_id=-1, $max_id=-1){
		$whereq="WHERE n_to=$member";
		if($since_id!=-1) $whereq.=" AND n_id<$max_id";
		if($max_id!=-1) $whereq.=" AND n_id>$since_id";
		if($since_id!=-1 && $max_id!=-1) return false;
		$query="SELECT * FROM `$this->table_notice` $whereq ORDER BY n_id DESC LIMIT 0,$count";
		if($res=$this->mysqli->query($query)){
			$arr=array();$i=0;
			while ($row = $res->fetch_array(MYSQLI_ASSOC)){
				$arr[$i++]=$row;
			}
			$res->close();
			if($this->mysqli->more_results())$this->mysqli->next_result();
			$this->mysqli->query("UPDATE `$this->table_notice` SET n_seen=1 $whereq");
			return $arr;
		}
		echo $this->mysqli->error;
		return false;
	}
	function removeNotice($member, $fnkey=false){
		if(!is_numeric($member)) return false;
		if($fnkey===false){
			return $this->mysqli->query("DELETE FROM `$this->table_notice` WHERE n_to=$member")!==false;
		}else{
			return $this->mysqli->query("DELETE FROM `$this->table_notice` WHERE n_to=$member AND s_fnkey='".$this->escape($fnkey)."'")!==false;
		}
	}
	function addNotice($member, $fnkey, $desc, $url){
		if(!is_numeric($member)) return false;
		$desc=$this->escape($desc);
		$fnkey=$this->escape($fnkey);
		$url=$this->escape($url);
		$noAdd=false;
		// check if fnkey already exists -> "comment-on-152" "taekbae-2012-10-30-tk1"
		if($res=$this->mysqli->query("SELECT count(*) FROM `$this->table_notice` WHERE n_to=$member AND s_fnkey='$fnkey'")){
			if($row = $res->fetch_array(MYSQLI_BOTH)){
				if($row[0]>0) $noAdd=true;
			}
			$res->close();
			if($this->mysqli->more_results())$this->mysqli->next_result();
		}
		if($noAdd==true)
			$q="UPDATE `$this->table_notice` SET n_time=".time().", s_desc='$desc', s_url='$url', n_seen=0 WHERE n_to=$member AND s_fnkey='$fnkey'";
		else
			$q="INSERT INTO `$this->table_notice` (n_to, n_time, s_fnkey, s_desc, s_url) VALUES ($member, ".time().", '$fnkey', '$desc', '$url')";
		return $this->mysqli->query($q)===true;
	}
	function purgeOldNotices($member=-1, $count=100){
		// n_seen:1,
	}

}
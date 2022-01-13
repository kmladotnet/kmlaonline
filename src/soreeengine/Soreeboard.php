<?php
require_once(dirname(__FILE__) . "/searchlib.php");
if (!function_exists("__Soreeboard__removeSpaces")) {
	function __Soreeboard__removeSpaces($k)
	{
		return str_replace(" ", "_", $k);
	}
}
class Soreeboard
{
	private $mysqli, $table_prefix;
	private $attach_path = "./data/bbs/";
	private $table_data, $table_view, $table_attach, $table_category, $table_category_access, $table_category_access_defaults, $table_category_access_levels;
	private $category_cache = array(), $category_default_action_cache = array();
	private $category_actions = false, $category_actions_orig = array( // max 16
		"list",
		"search",
		"view",
		"write",
		"edit",
		"delete",
		"manage modify",
		"manage permission",
		"comment view",
		"comment write",
		"comment edit",
		"comment delete",
		"attach upload",
		"attach download",
		"flag anonymous",
		"flag bold title",
		"flag public",
		"flag no comment"
	);

	private $member;
	public $last_errno, $last_error;

	private function escape($str)
	{ // shortcut for Mysqli real escape string
		return $this->mysqli->real_escape_string($str);
	}
	function prepareFirstUse()
	{
		$query = array();
		$query[] = "CREATE TABLE IF NOT EXISTS `$this->table_category` (
				n_id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (n_id),
				s_id char(48), UNIQUE KEY s_id (s_id),
				s_name TINYTEXT,
				s_desc TEXT,
				n_viewmode INT NOT NULL DEFAULT 0,
				n_count BIGINT NOT NULL DEFAULT 0
				)";
		$query[] = "INSERT INTO `$this->table_category` (s_name) VALUES ('Unspecified')";
		$t1 = "CREATE TABLE IF NOT EXISTS `$this->table_category_access_defaults` (
				n_cat BIGINT NOT NULL DEFAULT 1, KEY n_cat (n_cat), FOREIGN KEY (n_cat) REFERENCES `$this->table_category`(n_id) ON DELETE CASCADE";
		$t2 = "CREATE TABLE IF NOT EXISTS `$this->table_category_access_levels` (
				n_cat BIGINT NOT NULL DEFAULT 1, KEY n_cat (n_cat), FOREIGN KEY (n_cat) REFERENCES `$this->table_category`(n_id) ON DELETE CASCADE,
				n_level BIGINT, KEY n_level (n_level)";
		$t3 = "CREATE TABLE IF NOT EXISTS `$this->table_category_access` (
				n_cat BIGINT NOT NULL DEFAULT 1, KEY n_cat (n_cat), FOREIGN KEY (n_cat) REFERENCES `$this->table_category`(n_id) ON DELETE CASCADE,
				n_member BIGINT, KEY n_member (n_member), FOREIGN KEY (n_member) REFERENCES `{$this->member->getTableData()}`(n_id) ON DELETE CASCADE";
		foreach ($this->getCategoryActionList() as $v) {
			$t1 .= ", val_$v INT NOT NULL DEFAULT 0";
			$t2 .= ", val_$v INT NOT NULL DEFAULT 0";
			$t3 .= ", val_$v INT NOT NULL DEFAULT 0";
		}
		$query[] = $t1 . ")";
		$query[] = $t2 . ")";
		$query[] = $t3 . ")";
		$query[] = "CREATE TABLE IF NOT EXISTS `$this->table_data` (
				n_id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (n_id),
				n_parent BIGINT, KEY n_parent (n_parent), FOREIGN KEY (n_parent) REFERENCES `$this->table_data`(n_id) ON DELETE CASCADE,
				n_sticky TINYINT NOT NULL DEFAULT 0,
				n_cat BIGINT NOT NULL DEFAULT 1, KEY n_cat (n_cat), FOREIGN KEY (n_cat) REFERENCES `$this->table_category`(n_id) ON DELETE CASCADE,
				n_writedate BIGINT NOT NULL,
				n_editdate BIGINT NOT NULL,
				n_views BIGINT NOT NULL DEFAULT 0,
				n_out_views BIGINT NOT NULL DEFAULT 0,
				n_total_views BIGINT NOT NULL DEFAULT 0,
				n_comments BIGINT NOT NULL DEFAULT 0,
				s_title TEXT,
				s_data MEDIUMTEXT,
				n_attach1 BIGINT,
				s_tag MEDIUMTEXT,
				n_writer BIGINT NOT NULL DEFAULT 0, KEY n_writer (n_writer),
				s_writer TINYTEXT, FOREIGN KEY (n_writer) REFERENCES `{$this->member->getTableData()}`(n_id) ON DELETE CASCADE,
				n_flag BIGINT NOT NULL DEFAULT 0
				)";
		$query[] = "CREATE TABLE IF NOT EXISTS `$this->table_view` (
				n_id BIGINT, KEY n_id (n_id), FOREIGN KEY (n_id) REFERENCES `$this->table_data`(n_id) ON DELETE CASCADE,
				n_member BIGINT, KEY n_member (n_member), FOREIGN KEY (n_member) REFERENCES `{$this->member->getTableData()}`(n_id) ON DELETE CASCADE,
				s_userkey char(41) NOT NULL, KEY s_userkey (s_userkey)
				)";
		$query[] = "CREATE TABLE IF NOT EXISTS `$this->table_attach` (
				n_id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (n_id),
				n_parent BIGINT, KEY n_parent(n_parent), FOREIGN KEY (n_parent) REFERENCES `$this->table_data`(n_id) ON DELETE CASCADE,
				n_created BIGINT,
				s_path TINYTEXT,
				s_key TINYTEXT,
				s_name TINYTEXT,
				n_order INT NOT NULL DEFAULT 0,
				s_comment TEXT
				)";
		array_reverse($query);
		foreach ($query as $val) {
			if (!($this->mysqli->query($val))) {
				echo $val . " : " . $this->mysqli->error . "\r\n<br />";
				return false;
			}
			$this->mysqli->commit();
		}
		return true;
	}

	function __construct($db, $tableprefix, $member)
	{
		$this->table_prefix = $tableprefix;
		$this->mysqli = $db;
		$this->table_data = $this->escape($this->table_prefix . "_data");
		$this->table_view = $this->escape($this->table_prefix . "_view");
		$this->table_attach = $this->escape($this->table_prefix . "_attach");
		$this->table_category = $this->escape($this->table_prefix . "_category");
		$this->table_category_access = $this->escape($this->table_prefix . "_category_access");
		$this->table_category_access_defaults = $this->escape($this->table_prefix . "_category_access_defaults");
		$this->table_category_access_levels = $this->escape($this->table_prefix . "_category_access_levels");
		$this->member = $member;
	}

	function __destruct()
	{
	}

	function getCategoryActionList($original = false)
	{
		if ($original) {
			return $this->category_actions_orig;
		}

		if ($this->category_actions === false) {
			$this->category_actions = array_map("strtolower", array_map("__Soreeboard__removeSpaces", $this->category_actions_orig));
		}

		return $this->category_actions;
	}

	function getCategoryActionInt($wat)
	{
		$wat = __Soreeboard__removeSpaces($wat);
		$arr = $this->getCategoryActionList();
		if (isset($arr[$wat])) return $wat;
		$arr = array_flip($arr);
		if (isset($arr[$wat])) return $arr[$wat];
		return false;
	}

	function getCategoryActionStr($wat)
	{
		$wat = __Soreeboard__removeSpaces($wat);
		$arr = $this->getCategoryActionList();
		if (isset($arr[$wat])) {
			return $arr[$wat];
		}
		$arr = array_flip($arr);
		if (isset($arr[$wat])) {
			return $wat;
		}
		return false;
	}
	function setCategoryPermission($cat, $action, $permission)
	{
		if (false === ($action = $this->getCategoryActionStr($action))) return false;
		if (!is_numeric($permission)) return false;
		if ($this->getCategory($cat) === false) return false;
		unset($this->category_default_action_cache["$cat"]);
		$this->mysqli->query("UPDATE `$this->table_category_access_defaults` SET val_$action=-1 WHERE n_cat=$cat");
		$this->mysqli->query("UPDATE `$this->table_category_access_defaults` SET val_$action=$permission WHERE n_cat=$cat");
		//echo $this->mysqli->affected_rows;
		if ($this->mysqli->affected_rows == 0) {
			if ($this->mysqli->query("INSERT INTO `$this->table_category_access_defaults` (n_cat, val_$action) VALUES ($cat, $permission)") === true) {
				return true;
			}
		} else {
			return true;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function getCategoryPermissionList($cat, $falseIfNotFound = false)
	{
		if (!is_numeric($cat)) return false;
		if (isset($this->category_default_action_cache["$cat"])) return $this->category_default_action_cache["$cat"];
		$query = "SELECT * FROM `$this->table_category_access_defaults` WHERE n_cat=$cat";
		if ($res = $this->mysqli->query($query)) {
			$arr = false;
			while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
				$arr = $row;
			}
			if ($arr === false) {
				if ($falseIfNotFound) return false;
				$arr = array();
				for ($i = 0; false !== ($actName = $this->getCategoryActionStr($i)); $i++) {
					$arr[$actName] = 0;
				}
			} else {
				$this->category_default_action_cache["$cat"] = $arr;
			}
			$res->close();
			if ($this->mysqli->more_results()) $this->mysqli->next_result();
			return $arr;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function getCategoryPermission($cat, $action)
	{
		if (false === ($action = $this->getCategoryActionStr($action))) return false;
		$k = $this->getCategoryPermissionList($cat, true);
		if ($k === false) return false;
		return $k["val_$action"];
	}
	function setUserPermission($cat, $action, $member, $permission)
	{
		if (false === ($action = $this->getCategoryActionStr($action))) return false;
		if (!is_numeric($permission)) return false;
		if ($member == false) $member = 1;
		if ($this->getCategory($cat) === false) return false;
		unset($this->category_default_action_cache["$cat\nm$member"]);
		$this->mysqli->query("UPDATE `$this->table_category_access` SET val_$action=-1 WHERE n_cat=$cat AND n_member=$member");
		$this->mysqli->query("UPDATE `$this->table_category_access` SET val_$action=$permission WHERE n_cat=$cat AND n_member=$member");
		if ($this->mysqli->affected_rows == 0) {
			if ($this->mysqli->query("INSERT INTO `$this->table_category_access` (n_cat, n_member, val_$action) VALUES ($cat, $member, $permission)") === true) {
				return true;
			}
		} else {
			return true;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function getUserPermission($cat, $member, $action)
	{
		if (false === ($action = $this->getCategoryActionStr($action))) {
			return false;
		}
		$k = $this->getUserPermissionList($cat, $member, true);
		if ($k === false) {
			return false;
		}
		return $k["val_$action"];
	}
	function getUserPermissionList($cat, $member, $falseIfNotFound = false)
	{
		if ($member == false) $member = 1;
		if (!is_numeric($member) || !is_numeric($cat)) return false;
		if (isset($this->category_default_action_cache["$cat\nm$member"])) return $this->category_default_action_cache["$cat\nm$member"];
		$query = "SELECT * FROM `$this->table_category_access` WHERE n_cat=$cat AND n_member=$member";
		if ($res = $this->mysqli->query($query)) {
			$arr = false;
			while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
				$arr = $row;
			}
			if ($arr === false) {
				if ($falseIfNotFound) return false;
				$arr = array();
				for ($i = 0; false !== ($actName = $this->getCategoryActionStr($i)); $i++) {
					$arr[$actName] = 0;
				}
			} else {
				$this->category_default_action_cache["$cat\nm$member"] = $arr;
			}
			$res->close();
			if ($this->mysqli->more_results()) $this->mysqli->next_result();
			return $arr;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function setLevelPermission($cat, $action, $level, $permission)
	{
		if (false === ($action = $this->getCategoryActionStr($action))) return false;
		if (!is_numeric($permission) || !is_numeric($level)) return false;
		if ($this->getCategory($cat) === false) return false;
		unset($this->category_default_action_cache["$cat\nl$level"]);
		$this->mysqli->query("UPDATE `$this->table_category_access_levels` SET val_$action=-1 WHERE n_cat=$cat AND n_level=$level");
		$this->mysqli->query("UPDATE `$this->table_category_access_levels` SET val_$action=$permission WHERE n_cat=$cat AND n_level=$level");
		if ($this->mysqli->affected_rows == 0) {
			if ($this->mysqli->query("INSERT INTO `$this->table_category_access_levels` (n_cat, n_level, val_$action) VALUES ($cat, $level, $permission)") === true) {
				return true;
			}
		} else {
			return true;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function getLevelPermission($cat, $level, $action)
	{
		if (false === ($action = $this->getCategoryActionStr($action))) {
			return false;
		}
		$k = $this->getLevelPermissionList($cat, $level, true);
		if ($k === false) {
			return false;
		}
		return $k["val_$action"];
	}
	function getLevelPermissionList($cat, $level, $falseIfNotFound = false)
	{
		if (!is_numeric($level) || !is_numeric($cat)) {
			return false;
		}
		$query = "SELECT * FROM `$this->table_category_access_levels` WHERE n_cat=$cat AND n_level=$level";
		if (isset($this->category_default_action_cache["$cat\nl$level"])) {
			return $this->category_default_action_cache["$cat\nl$level"];
		}
		if ($res = $this->mysqli->query($query)) {
			$arr = false;
			while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
				$arr = $row;
			}
			if ($arr === false) {
				if ($falseIfNotFound) return false;
				$arr = $this->getCategoryPermissionList($cat);
			} else {
				$this->category_default_action_cache["$cat\nl$level"] = $arr;
			}
			$res->close();
			if ($this->mysqli->more_results()) $this->mysqli->next_result();
			return $arr;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}

	function isUserAllowed($cat, $member, $action, $consider_admin = true)
	{
		if (!is_array($member) && $member !== false) {
			if (is_numeric($member)) {
				if (false === ($member = $this->member->getMember($member))) {
					return false;
				}
			} else {
				return false;
			}
		}

		if (!is_numeric($action = $this->getCategoryActionInt($action))) {
			return false;
		}

		$perm = $this->getUserPermission($cat, $member === false ? false : $member['n_id'], $action);
		if ($perm === false && $member !== false) // Use level settings IF LOGGED IN
		{
			$perm = $this->getLevelPermission($cat, $member['n_level'], $action);
		}
		if ($perm === false) // Use global settings
		{
			$perm = $this->getCategoryPermission($cat, $action);
		}
		if ($consider_admin) {
			if ($member['n_admin'] != 0 && $perm == false) {
				$perm = -1;
			}
		}
		return $perm;
	}

	function setArticleFlags($article, $flags)
	{
		if (!is_numeric($article) || !is_numeric($flags)) return false;
		$query = "UPDATE `$this->table_data` SET n_flag=$flags WHERE n_id=$article";
		if ($this->mysqli->query($query) === true)
			return true;
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function getArticleFlags($article)
	{
		if (!is_numeric($article)) return false;
		$query = "SELECT n_flag FROM `$this->table_data` WHERE n_id=$article";
		if ($res = $this->mysqli->query($query)) {
			while ($row = $res->fetch_array(MYSQLI_BOTH)) {
				$res->close();
				if ($this->mysqli->more_results()) $this->mysqli->next_result();
				return $row['n_flag'];
			}
			return false;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function addCategory($cat_id, $cat_name, $cat_desc = null, $default_viewmode = 0)
	{
		if ($this->getCategory(false, $cat_id, $cat_name, false) !== false) return false;
		if (!is_numeric($default_viewmode)) return false;
		if ($cat_id == "" || $cat_name == "") return false;
		$cat_desc = is_null($cat_desc) ? "null" : "'" . $this->escape($cat_desc) . "'";
		$cat_id = is_null($cat_id) ? "null" : "'" . $this->escape($cat_id) . "'";
		$cat_name = is_null($cat_name) ? "null" : "'" . $this->escape($cat_name) . "'";
		if ($this->mysqli->query("INSERT INTO `$this->table_category` (s_id, s_name, s_desc, n_viewmode) VALUES ($cat_id, $cat_name, $cat_desc, $default_viewmode)") === true)
			return $this->mysqli->insert_id;
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function editCategory($cat_idx, $cat_id = false, $cat_name = false, $cat_desc = false, $default_viewmode = false)
	{
		if ($cat_id !== false && $this->getCategory(false, $cat_id) !== false) return false;
		if (($the_cat = $this->getCategory($cat_idx)) === false) return false;
		unset($this->category_cache["s_id:" . $the_cat['s_id']], $this->category_cache["n_idx:" . $the_cat['n_id']], $this->category_cache["s_name:" . $the_cat['s_name']]);
		$query = array();
		if ($cat_id !== false) $query[] = "s_id='" . $this->escape($cat_id) . "'";
		if ($cat_name !== false) $query[] = "s_name='" . $this->escape($cat_name) . "'";
		if ($cat_desc !== false) $query[] = "s_desc=" . (is_null($cat_desc) ? "null" : "'" . $this->escape($cat_desc) . "'");
		if ($default_viewmode !== false) {
			if (!is_numeric($default_viewmode)) return false;
			$query[] = "n_viewmode=$default_viewmode";
		}
		if (count($query) == 0) return false;
		$query = implode(", ", $query);
		if ($this->mysqli->query("UPDATE `$this->table_category` SET $query WHERE n_id=$cat_idx")) {
			return true;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function clearCategory($cat_idx)
	{
		if (!is_numeric($cat_idx)) return false;
		$the_cat = $this->getCategory($cat_idx);
		if ($the_cat === false) return false;
		if ($this->mysqli->query("DELETE FROM `$this->table_data` WHERE n_cat=$cat_idx")) {
			$this->mysqli->query("UPDATE `$this->table_category` SET n_count=0 WHERE n_id=$cat_idx");
			return true;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function moveCategoryData($cat_idx, $to_idx)
	{
		if (!is_numeric($cat_idx)) return false;
		$the_cat = $this->getCategory($cat_idx);
		$the_cat_to = $this->getCategory($to_idx);
		if ($the_cat === false || $the_cat_to === false) return false;
		if ($this->mysqli->query("UPDATE `$this->table_data` SET n_cat=$to_idx WHERE n_cat=$cat_idx")) {
			$this->mysqli->query("UPDATE `$this->table_category` SET n_count=0 WHERE n_id=$cat_idx");
			$this->mysqli->query("UPDATE `$this->table_category` SET n_count=n_count+{$the_cat['n_count']} WHERE n_id=$to_idx");
			return true;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function delCategory($cat_idx, $change_to_cat = 0)
	{
		if (!is_numeric($cat_idx) || !is_numeric($change_to_cat)) return false;
		$the_cat = $this->getCategory($cat_idx);
		if ($the_cat === false) return false;
		unset($this->category_cache["s_id:" . $the_cat['s_id']], $this->category_cache["n_idx:" . $the_cat['n_id']], $this->category_cache["s_name:" . $the_cat['s_name']]);
		$this->mysqli->autocommit(false);
		$suc = true;
		$suc &= true === $this->mysqli->query("UPDATE `$this->table_data` SET n_cat=$change_to_cat WHERE n_cat=$cat_idx");
		$suc &= true === $this->mysqli->query("DELETE FROM `$this->table_category` WHERE n_id=$cat_idx");
		$suc &= true === $this->mysqli->query("UPDATE `$this->table_category` SET n_count=n_count+{$the_cat['n_count']} WHERE n_id=$change_to_cat");
		if ($suc) {
			$this->mysqli->commit();
			$this->mysqli->autocommit(true);
			return true;
		} else {
			$this->last_error = $this->mysqli->error;
			$this->last_errno = $this->mysqli->errno;
			$this->mysqli->rollback();
			$this->mysqli->autocommit(true);
			return false;
		}
	}
	function getCategoryList($page = 0, $count = 0, $search = false, $search_id = false, $search_name = false)
	{
		if (!is_numeric($page) || !is_numeric($count)) return false;
		$whereq = $lim = "";
		if ($search_id || $search_name) {
			$where = array();
			if ($search_id) array_push($where, "s_id");
			if ($search_name) array_push($where, "s_name");
			$whereq = search_wherequery($search, $where, "OR", "OR");
		}
		if ($whereq != "") $whereq = "WHERE $whereq";

		if ($count > 0)
			$lim = "LIMIT " . ($page * $count) . ", $count";
		$query = "SELECT * FROM `$this->table_category` $whereq $lim";
		//echo "[".$query."]";
		if ($res = $this->mysqli->query($query)) {
			$arr = array();
			$i = 0;
			while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
				$row['data_type'] = "category";
				$arr[$i++] = $row;
			}
			$res->close();
			if ($this->mysqli->more_results()) $this->mysqli->next_result();
			return $arr;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		//echo $this->last_error;
		return false;
	}
	function getCategory($idx = false, $id = false, $name = false, $andmode = true)
	{
		if ($idx !== false && !is_numeric($idx)) return false;

		$array = array();
		if ($idx !== false) {
			if (isset($this->category_cache["n_id:$idx"])) return $this->category_cache["n_id:$idx"];
			array_push($array, "n_id=$idx");
		}
		if ($id !== false) {
			if (isset($this->category_cache["s_id:$id"])) return $this->category_cache["s_id:$id"];
			array_push($array, "s_id='{$this->escape($id)}'");
		}
		if ($name !== false) {
			if (isset($this->category_cache["s_name:$name"])) return $this->category_cache["s_name:$name"];
			array_push($array, "s_name='{$this->escape($name)}'");
		}
		if (count($array) == 0) return false;
		$whereq = implode($andmode ? " AND " : " OR ", $array);
		$query = "SELECT * FROM `$this->table_category` WHERE $whereq";
		if ($res = $this->mysqli->query($query)) {
			while ($row = $res->fetch_array(MYSQLI_BOTH)) {
				$res->close();
				if ($this->mysqli->more_results()) $this->mysqli->next_result();
				$this->category_cache["s_id:" . $row['s_id']] = $row;
				$this->category_cache["n_idx:" . $row['n_id']] = $row;
				$this->category_cache["s_name:" . $row['s_name']] = $row;
				return $row;
			}
			return false;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function editAttachment($id, $filename = false, $comment = false, $order = false)
	{
		if (!is_numeric($id)) return false;
		if ($order !== false && !is_numeric($order)) return false;
		$query = array();
		if ($filename !== false) $query[] = "s_name='" . $this->escape($filename) . "'";
		if ($comment !== false) $query[] = "s_comment='" . $this->escape($comment) . "'";
		if ($order !== false) $query[] = "n_order=$order";
		if (count($query) == 0) return false;
		$query = "UPDATE `$this->table_attach` SET " . implode(", ", $query) . " WHERE n_id=$id";
		if ($this->mysqli->query($query)) {
			return true;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		echo $this->last_error;
		return false;
	}
	function addAttachment($filepath, $parent = null, $to_filename = false, $keyhelper = "", $comment = "", $order = 0)
	{
		if (!is_file($filepath)) return false;
		if (!is_null($parent) && !is_numeric($parent)) return false;
		$filename = ($to_filename === false) ? basename($filepath) : $to_filename;
		$sha1 = sha1_file($filepath);
		$key = $sha1 . "_" . $keyhelper . "_" . filesize($filepath);
		$path_dir = $this->attach_path . substr($key, 0, 2) . "/" . substr($key, 2, 2) . "/" . substr($key, 4, 2) . "/";
		$path = $path_dir . substr($key, 6);

		//$test=$this->getAttachments(false, $parent, $key, true);
		//if($test!=false && count($test)>0){ return $test[0]; }

		$cretime = time();
		$query = "INSERT INTO `$this->table_attach` (n_parent, n_created, s_path, s_key, s_name, s_comment, n_order) VALUES (" .
			(is_null($parent) ? "null" : $parent) . "," .
			$cretime . "," .
			"'" . $this->escape($path) . "'," .
			"'" . $this->escape($key) . "'," .
			"'" . $this->escape($filename) . "'," .
			"'" . $this->escape($comment) . "'," .
			$order . ")";
		$this->mysqli->autocommit(false);
		$suc = $this->mysqli->query($query) === true;
		if ($suc) $iid = $this->mysqli->insert_id;
		if ($suc && !file_exists($path)) {
			@mkdir($path_dir, 0777, true);
			$suc &= copy($filepath, $path);
		}
		if ($suc) {
			$this->mysqli->commit();
			$this->mysqli->autocommit(true);
			return array(
				"n_id" => $iid,
				"s_path" => $path,
				"s_name" => $filename,
				"n_parent" => $parent,
				"s_key" => $key,
				"s_comment" => $comment,
				"n_created" => $cretime
			);
		} else {
			$this->last_error = $this->mysqli->error;
			$this->last_errno = $this->mysqli->errno;
			$this->mysqli->rollback();
			$this->mysqli->autocommit(false);
			return false;
		}
	}
	function getAttachments($idx = false, $parent = false, $key = false, $name = false, $andmode = false, $pagenumber = 0, $pagecount = 0, $orderby = "n_order", $ascending = true)
	{
		if ($idx !== false && !is_numeric($idx)) return false;
		if (!is_null($parent) && $parent !== false && !is_numeric($parent)) return false;
		if ($orderby !== "n_order" && $orderby !== "n_id" && $orderby !== "n_parent" && $orderby !== "n_created" && $orderby !== "s_path" && $orderby !== "s_key" && $orderby !== "s_comment") return false;
		$array = array();
		if ($idx !== false) array_push($array, "n_id=$idx");
		if ($key !== false) array_push($array, "s_key='{$this->escape($key)}'");
		if (is_null($parent)) array_push($array, "n_parent IS NULL");
		else if ($parent !== false) array_push($array, "n_parent=$parent");

		if ($name !== false) {
			$where = array("s_name");
			$wheres[] = "(" . search_wherequery($name, $where, $andmode ? "AND" : "OR", $andmode ? "AND" : "OR") . ")";
			array_push($array, implode(" AND ", $wheres));
		}
		$limits = "";
		if ($pagecount != 0) {
			$p = $pagecount * $pagenumber;
			$limits = " LIMIT $p,$pagecount";
		}

		$whereq = implode($andmode ? " AND " : " OR ", $array);
		if (count($array) > 0) $whereq = "WHERE $whereq";
		$query = "SELECT * FROM `$this->table_attach` $whereq ORDER BY $orderby " . ($ascending ? "ASC" : "DESC") . " $limits";
		if ($res = $this->mysqli->query($query)) {
			$arr = array();
			$i = 0;
			while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
				$arr[$i++] = $row;
			}
			$res->close();
			if ($this->mysqli->more_results()) $this->mysqli->next_result();
			return $arr;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function removeAttachmentWithoutParents($before_time = false)
	{
		if ($before_time !== false && !is_numeric($before_time)) return false;
		$query = "DELETE FROM `$this->table_attach` WHERE n_parent IS NULL";
		if ($before_time !== false) $query .= " AND n_created<$before_time";
		if ($this->mysqli->query($query) === true) {
			return true;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function removeAttachment($id)
	{
		if (($a = $this->getAttachments($id)) === false) return false;
		$query = "DELETE FROM `$this->table_attach` WHERE n_id=" . $id;
		if ($this->mysqli->query($query) === true) {
			if (count($this->getAttachments(false, false, $a[0]['s_key'])) == 0)
				@unlink($a[0]['s_path']);
			return true;
		}
		return false;
	}
	function articleExists($idx)
	{
		if (!is_numeric($idx)) return false;
		if ($res = $this->mysqli->query("SELECT * FROM `$this->table_data` WHERE n_id=$idx")) {
			while ($row = $res->fetch_array(MYSQLI_BOTH)) {
				$res->close();
				if ($this->mysqli->more_results()) $this->mysqli->next_result();
				return $row;
			}
			return false;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	function addArticle($category, $title, $data, $writer_s, $writer_n = 1, $parent = 0, $sticky = 0, $attach = array(), $tag = "")
	{
		if (!is_numeric($parent) || !is_numeric($writer_n) || !is_numeric($sticky)) return false;
		if ($category) if (!is_numeric($category)) return false;
		if (($parent > 0 && (($parent_article = $this->articleExists($parent)) === false) || ($parent == 0 && $this->getCategory($category) === false))) return false;
		foreach ($attach as $value) {
			if (!is_numeric($value)) return false;
		}
		// has parent but no parent->out
		// no parent and nonexist category->out
		$attach_1 = -1;
		foreach ($attach as $value) {
			if ($attach_1 == -1) {
				$attach_1 = $value;
				break;
			}
		}
		if ($writer_n > 1 && $writer_s == false) {
			$writer_s = $this->member->getMember($writer_n);
			$writer_s = $writer_s['s_id'];
		}
		if ($parent > 0) $category = $parent_article['n_cat'];
		else $parent = "null";
		$title = $this->escape($title);
		$data = $this->escape($data);
		$tag = $this->escape($tag);
		$writer_s = $this->escape($writer_s);
		$curr = time();
		$query = "INSERT INTO `$this->table_data` (n_parent, n_sticky, n_cat, n_writedate, n_editdate, n_views, n_out_views, n_comments, s_title, s_data, n_attach1, s_tag, s_writer, n_writer) VALUES(
					$parent, $sticky, $category, $curr, $curr, 0, 0, 0, '$title', '$data', $attach_1, '$tag', '$writer_s', $writer_n)";
		if ($this->mysqli->query($query) === true) {
			$insert_id = $this->mysqli->insert_id;
			$this->mysqli->autocommit(false);
			$query = array();
			foreach ($attach as $value) {
				$query[] = "UPDATE `$this->table_attach` SET n_parent=$insert_id WHERE n_id=$value";
			}
			if (is_null($parent) || $parent == "null") { // Considering for count cache
				$query[] = "UPDATE `$this->table_category` SET n_count=n_count+1 WHERE n_id=$category";
			} else {
				$tmp_parent = $parent;
				while (!is_null($tmp_parent)) {
					$query[] = "UPDATE `$this->table_data` SET n_comments=n_comments+1 WHERE n_id=$tmp_parent";
					$tmp_parent = $this->getArticle($tmp_parent);
					$tmp_parent = $tmp_parent['n_parent'];
				}
			}
			$suc = true;
			while ($suc && count($query)) {
				if ($this->mysqli->query(array_pop($query)) === false) {
					$suc = false;
					break;
				}
			}
			if ($suc) {
				$this->mysqli->commit();
				$this->mysqli->autocommit(true);
				if ($parent != 0)
					$this->member->modifyPostsParticipated($writer_n, 1);
				else
					$this->member->modifyPostsStarted($writer_n, 1);
				return $insert_id;
			} else {
				$this->last_error = $this->mysqli->error;
				$this->last_errno = $this->mysqli->errno;
				$this->mysqli->rollback();
				$this->mysqli->autocommit(true);
				$this->removeArticle($insert_id);
				return false;
			}
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		//echo $this->last_error;
		$this->mysqli->rollback();
		$this->mysqli->autocommit(true);
		return false;
	}
	function editArticle($id, $category = false, $title = false, $data = false, $attach = null, $tag = false, $sticky = false)
	{
		if (!is_numeric($id) || ($sticky && !is_numeric($sticky))) return false;
		if ($category && $this->getCategory($category) === false) return false;
		$attach_1 = -1;
		$this->mysqli->autocommit(false);
		if ($attach) {
			foreach ($attach as $value) {
				if ($attach_1 == -1) {
					$attach_1 = $value;
					break;
				}
			}
		}
		$array = array("n_editdate=" . time());
		if ($title !== false) $array[] = "s_title='" . $this->escape($title) . "'";
		if ($category !== false) $array[] = "n_cat='" . $this->escape($category) . "'";
		if ($data !== false) $array[] = "s_data='" . $this->escape($data) . "'";
		if ($attach !== false) $array[] = "n_attach1='" . $this->escape($attach_1) . "'";
		if ($tag !== false) $array[] = "s_tag='" . $this->escape($tag) . "'";
		if ($sticky !== false) $array[] = "n_sticky='" . $this->escape($sticky) . "'";
		if (count($array) == 0) return false;
		$query = implode(", ", $array);
		$query = "UPDATE `$this->table_data` SET $query WHERE n_id=$id";
		if ($this->mysqli->query($query) === true) {
			$query = array();
			if ($attach !== false) {
				if (is_array($attach) || is_object($attach)){
					foreach ($attach as $value) {
						$query[] = "UPDATE `" . $this->table_attach . "` SET n_parent=$id WHERE n_id=$value";
					}
				}
			}
			$suc = true;
			while ($suc && count($query)) {
				if ($this->mysqli->query(array_pop($query)) === false) {
					$suc = false;
					break;
				}
			}
			if ($suc) {
				$this->mysqli->commit();
				$this->mysqli->autocommit(true);
				return true;
			}
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		$this->mysqli->rollback();
		$this->mysqli->autocommit(true);
		return false;
	}
	function removeArticle($id)
	{
		if (!is_numeric($id)) return false;
		if ($res = $this->mysqli->query("SELECT * FROM `$this->table_data` WHERE n_id=" . $id)) {
			$arr = array();
			$i = 0;
			$article = false;
			while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
				$article = $row;
				break;
			}
			$res->close();
			if ($this->mysqli->more_results()) $this->mysqli->next_result();
			if ($article === false) return false;
		} else {
			$this->last_error = $this->mysqli->error;
			$this->last_errno = $this->mysqli->errno;
			return false;
		}
		$this->mysqli->autocommit(false);
		$arr = $this->getAttachments(false, $id);
		while (--$i >= 0)
			$this->removeAttachment($arr[$i]);
		$query = array();
		$query[] = "DELETE FROM `$this->table_view` WHERE n_id=" . $id;
		if ($article['n_parent'] == 0) $query[] = "UPDATE `$this->table_category` SET n_count=n_count-1 WHERE n_id={$article['n_cat']}";

		$k = true;
		foreach ($query as $val) {
			$k &= $this->mysqli->query($val) === true;
		}
		if ($k) {
			$this->mysqli->commit();
			$this->mysqli->autocommit(true);
			$children = $this->getArticleList(false, false, $id, 0, 0);
			foreach ($children as $child) {
				$this->removeArticle($child['n_id']);
			}
			$query = "DELETE FROM `$this->table_data` WHERE n_id=" . $id;
			$res = $this->mysqli->query($query);
			$top = $article;
			$query = array();
			while ($top['n_parent']) {
				$top = $this->getArticle($top['n_parent']);
				$query[] = "UPDATE `$this->table_data` SET n_comments=n_comments-1 WHERE n_id=" . $top['n_id'];
			}
			$k = true;
			foreach ($query as $val) {
				$k &= $this->mysqli->query($val) === true;
			}
			if ($k) {
				if ($article['n_parent'] != 0)
					$this->member->modifyPostsParticipated($article['n_writer'], -1);
				else
					$this->member->modifyPostsStarted($article['n_writer'], -1);
				return true;
			} else {
				$this->last_error = $this->mysqli->error;
				$this->last_errno = $this->mysqli->errno;
				return false;
			}
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		$this->mysqli->rollback();
		$this->mysqli->autocommit(true);
		return false;
	}
	function getArticleList($category = null, $sticky = false, $parent = false, $pagenumber = 0, $pagecount = 20, $orderby_name = "n_id", $orderby_desc = true, $incl_text = 0, $search = false, $search_mode_and = true, $search_submode_and = true, $search_title = false, $search_data = false, $search_tag = false, $search_writer = false, $with_data = false)
	{
		if (!is_numeric($parent) && $parent !== false) {
			return false;
		}
		if ($category) {
			foreach ($category as $value) {
				if (!is_numeric($value)) {
					return false;
				}
			}
		}

		$whereq = $this->genWhereQuery($category, $sticky, $parent, $search, $search_mode_and, $search_submode_and, $search_title, $search_data, $search_tag, $search_writer);

		if ($orderby_desc) {
			$orderby = "$orderby_name DESC";
		} else {
			$orderby = "$orderby_name ASC";
		}

		if ($pagecount == 0) {
			$limits = "";
		} else {
			$limits = "LIMIT " . ($pagenumber * $pagecount) . ", " . $pagecount;
		}

		$incl_data = ($incl_text > 0 ? (", LEFT(s_data, " . $incl_text . ")") : ($incl_text < 0 ? ", s_data" : ""));
		$select_what = "n_id, n_parent, n_sticky, n_cat, n_writedate, n_editdate, n_total_views, n_views, n_out_views, n_comments, s_title, n_attach1, s_tag, s_writer, n_writer, n_flag" . ($with_data ? ", s_data" : "");
		$query = "SELECT $select_what $incl_data FROM `$this->table_data` $whereq ORDER BY $orderby $limits";
		//echo htmlspecialchars($query);
		if ($res = $this->mysqli->query($query)) {
			$arr = array();
			$i = 0;
			while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
				$row['cat'] = $this->getCategory($row['n_cat']);
				$row['data_type'] = "article";
				$arr[$i++] = $row;
			}
			$res->close();
			if ($this->mysqli->more_results()) $this->mysqli->next_result();
			return $arr;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		//echo $this->last_error;
		return false;
	}

	private function genWhereQuery($category = null, $sticky = false, $parent = false, $search = false, $search_mode_and = true, $search_submode_and = true, $search_title = false, $search_data = false, $search_tag = false, $search_writer = false)
	{
		$wheres = array();
		if ($parent !== false) {
			if ($parent == 0) {
				$parr = "n_parent IS NULL AND ";
			} else {
				$parr = "n_parent=$parent AND ";
			}
		} else {
			$parr = "";
		}
		if ($sticky !== false) {
			$wheres[] = "n_sticky=" . ($sticky ? 1 : 0);
		}
		if ($search_title || $search_data || $search_tag || $search_writer) {
			$where = array();
			if ($search_title) {
				array_push($where, "s_title");
			}
			if ($search_data) {
				array_push($where, "s_data");
			}
			if ($search_tag) {
				array_push($where, "s_tag");
			}
			if ($search_writer) {
				array_push($where, "s_writer");
			}
			$wheres[] = "(" . search_wherequery($search, $where, $search_mode_and ? "AND" : "OR", $search_submode_and ? "AND" : "OR") . ")";
		}
		if ($category) {
			$catwhere = array();
			foreach ($category as $cat) {
				$catwhere[] = "n_cat=$cat";
			}
			$wheres[] = "(" . implode(" OR ", $catwhere) . ")";
		}
		if (count($wheres) == 0) $wheres[] = "1=1";
		$q = "WHERE " . $parr . "(" . implode(" AND ", $wheres) . ")";
		return $q;
	}
	function getArticleCount($category = null, $sticky = false, $parent = false, $search = false, $search_mode_and = true, $search_submode_and = true, $search_title = false, $search_data = false, $search_tag = false, $search_writer = false)
	{
		if ($category) {
			foreach ($category as $value) {
				if (!is_numeric($value)) {
					return false;
				}
			}
		}
		if ($search !== false || $parent !== false) {
			$whereq = $this->genWhereQuery($category, $sticky, $parent, $search, $search_mode_and, $search_submode_and, $search_title, $search_data, $search_tag, $search_writer);
			$query = "SELECT count(*) FROM `$this->table_data` " . $whereq;
		} else if ($category === false) {
			$query = "SELECT sum(n_count) FROM `$this->table_category`";
		} else {
			$query = "SELECT sum(n_count) FROM `$this->table_category` WHERE n_id=" . array_pop($category);
			foreach ($category as $value) {
				$query .= " OR n_id=" . $value;
			}
		}
		if ($res = $this->mysqli->query($query)) {
			while ($row = $res->fetch_array(MYSQLI_BOTH)) {
				$res->close();
				if ($this->mysqli->more_results()) $this->mysqli->next_result();
				return $row[0] ? $row[0] : 0;
			}
			return 0;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return 0;
	}
	function getArticle($id)
	{
		if (!is_numeric($id)) return false;
		$res = $this->mysqli->query("SELECT * FROM `$this->table_data` WHERE n_id=$id");
		if ($row = $res->fetch_array(MYSQLI_ASSOC)) {
			$res->close();
			return $row;
		} else {
			$this->last_error = $this->mysqli->error;
			$this->last_errno = $this->mysqli->errno;
			return false;
		}
	}
	function getPageNumber($id, $pagecount = 20, $orderby_name = "n_id", $orderby_desc = true, $category = null, $sticky = 0, $parent = 0, $search = false, $search_mode_and = true, $search_submode_and = true, $search_title = false, $search_data = false, $search_tag = false, $search_writer = false)
	{
		if (!is_numeric($parent) || !is_numeric($id)) {
			return false;
		}
		if ($category) {
			foreach ($category as $value) {
				if (!is_numeric($value)) {
					return false;
				}
			}
		}
		$whereq = $this->genWhereQuery($category, $sticky, $parent, $search, $search_mode_and, $search_submode_and, $search_title, $search_data, $search_tag, $search_writer);
		$query = "SELECT count(*) FROM `$this->table_data` $whereq AND $orderby_name " . ($orderby_desc ? ">" : "<") . " (SELECT $orderby_name FROM `$this->table_data` WHERE n_id=$id) ORDER BY $orderby_name " . ($orderby_desc ? "DESC" : "ASC");
		if ($res = $this->mysqli->query($query)) {
			while ($row = $res->fetch_array(MYSQLI_BOTH)) {
				$res->close();
				if ($this->mysqli->more_results()) $this->mysqli->next_result();
				$k = $row[0];
				$k = (int)($k / $pagecount);
				return $k;
			}
			return 0;
		}
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		return false;
	}
	/*
		id
			Article ID
		member
			-1: Not logged in
			Else: Logged in
		key
			Might be sha1(ip + user agent + ...)
			Or User ID

		return
			false: already viewed
			true: new view
	*/
	function setViewFlag($id, $key = false, $member = false)
	{
		if (!is_numeric($id) || ($member !== false && ($this->member->getMember($member) === false))) return false;
		if ($member === false && $key === false) return false;
		if ($key !== false) $key = $this->escape($key);
		$this->mysqli->query($query = "UPDATE `$this->table_data` SET n_total_views=n_total_views+1 WHERE n_id=$id");
		if ($member === false) {
			$query = "SELECT n_id FROM `$this->table_view` WHERE n_id=$id AND s_userkey='$key'";
		} else {
			$query = "SELECT n_id FROM `$this->table_view` WHERE n_id=$id AND n_member=$member";
		}
		if ($res = $this->mysqli->query($query)) {
			while ($row = $res->fetch_array(MYSQLI_BOTH)) {
				$res->close();
				if ($this->mysqli->more_results()) $this->mysqli->next_result();
				return false;
			}
		}
		$this->mysqli->autocommit(false);
		if ($member == false) $memberq = "null";
		else $memberq = $member;
		$query = "INSERT INTO `$this->table_view` (n_id, s_userkey, n_member) VALUES ($id, '$key', $memberq)";
		if ($this->mysqli->query($query) !== false) {
			if ($member === false) {
				$query = "UPDATE `$this->table_data` SET n_out_views=n_out_views+1 WHERE n_id=$id";
			} else {
				$query = "UPDATE `$this->table_data` SET n_views=n_views+1 WHERE n_id=$id";
			}
			if ($this->mysqli->query($query) === true) {
				$this->mysqli->commit();
				$this->mysqli->autocommit(true);
				return true;
			}
		}
		$this->mysqli->rollback();
		$this->mysqli->autocommit(true);
		$this->last_error = $this->mysqli->error;
		$this->last_errno = $this->mysqli->errno;
		echo $this->last_error;
		return false;
	}
}

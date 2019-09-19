<?php die();
require("src/lib.php");
set_time_limit(0);
header("Content-Type: text/plain");
$board_ref = array(
	'/^boardwave$/' => 'wave7_announce',
	'/^boardwave([0-9]+)$/' => 'wave$1_announce',
	'/^pdswave([0-9]+)$/' => 'wave$1_free',
	'/^photowave([0-9]+)$/' => 'wave$1_gallery',
	'/^intro([0-9]+)$/' => 'wave$1_selfintro',
	'/^boardall$/' => 'all_announce',
	'/^photowave0$/' => 'all_gallery',
	'/^pdsall$/' => 'all_pds',
	'/^forum$/' => 'forum',
	'/^delivery$/' => 'everyday_parcel',
	'/^eopnotice$/' => 'everyday_eop',
	'/^guidance$/' => 'everyday_guidance',
	'/^envir$/' => 'department_environment',
	'/^legislative$/' => 'student_legislative',
	'/^judicial$/' => 'student_judicial',
	'/^executive$/' => 'student_executive',
	'/^class$/' => 'student_discuss',
	'/^mptnotice$/' => 'student_mpt',
	'/^sammeon$/' => 'student_3m',
	'/^suggestbug$/' => 'site_suggestions',
	'/^clubadmin$/' => 'student_clubs',
);
do {
	$dlc = 500;
	$cidx = intval(@file_get_contents("data/cidx"));
	file_put_contents("data/cidx", $cidx + $dlc);
	echo "Downloading article data ($cidx)...\n";
	$url = "http://www.kmlaonline.net/member/bbsexport.aspx?minid=$cidx&maxid=" . ($cidx + $dlc);

	$article_array = json_decode(file_get_contents($url), true);

	$iii = 0;
	$iiii = count($article_array);
	foreach ($article_array as $article) {
		echo $article['title'] . "... ($iii/$iiii)\n";
		$iii++;
		$article['writer'] = $member->getMember($article['writerEmail'], 2);
		if ($article['writer'] === false) continue;
		foreach ($board_ref as $k => $v) {
			if (preg_match($k, $article['category'])) {
				$article['category'] = preg_replace($k, $v, $article['category']);
				break;
			}
		}
		if (($category = $board->getCategory(false, $article['category'])) === false) {
			$res = $mysqli->query("SELECT `name`,`dsc` FROM bbsList WHERE bid='" . $mysqli->real_escape_string($article['category']) . "'");
			$cat_name = $cat_desc = $article['category'];
			if ($res) {
				while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
					$cat_name = $row['name'];
					$cat_desc = $row['dsc'];
				}
				$res->close();
			}
			$category = $board->addCategory($article['category'], $cat_name, $cat_desc, 0);
			if ($category === false) {
				echo $article['category'] . "/" . $cat_name . "/" . $cat_desc;
				die($mysqli->error);
			}
		} else {
			$category = $category['n_id'];
		}
		//echo $article['writer']['s_name'] . ": " . $article['title'] . " ({$article['category']}): " . date("Y-m-d H:i:s", $article['regDate']) . "<br>";
		$aid = $board->addArticle(
			$category,
			$article['title'],
			$article['data'],
			$article['writer']['s_name'],
			$article['writer']['n_id'],
			0,
			0,
			array()
		);
		if ($aid === false) {
			echo  $article['category'] . "/";
			echo $category . "/" . $article['title'] . "/" . $article['data'] . "/" . $article['writer']['s_name'] . "/" . $article['writer']['n_id'];
			file_put_contents("data/cidx", $article['id']);
			echo $mysqli->error;
			die($board->last_error);
		}
		$mysqli->query("UPDATE kmlaonline_board_data SET n_total_views={$article['readCnt']}, n_writedate={$article['regDate']}, n_editdate={$article['regDate']} WHERE n_id=$aid");
		echo "Wrote: $aid\n";
		if ($article['attachment'])
			$mysqli->query("insert into import_matches (n_id,s_dat) VALUES ($aid,'" . $mysqli->real_escape_string($article['attachment']) . "')");
		foreach ($article['comments'] as $comment) {
			$comment['writer'] = $member->getMember($comment['writerEmail'], 2);
			$cid = $board->addArticle(
				$category,
				mb_substr($comment['data'], 0, 15),
				$comment['data'],
				$comment['writer']['s_name'],
				$comment['writer']['n_id'],
				$aid,
				0,
				array()
			);
			$mysqli->query("UPDATE kmlaonline_board_data SET n_writedate={$comment['regDate']}, n_editdate={$article['regDate']} WHERE n_id=$cid");
		}
	}
} while (!file_exists("stop"));

<?php
// http://www.iamcal.com/publish/articles/php/search/
// 2004년에 작성된 글이다.. 무섭다.

function search_split_terms($terms){

		$terms = preg_replace_callback("/\"(.*?)\"/", function($a) {return search_transform_term($a);}, $terms);
		$terms = preg_split("/\s+|,/", $terms);

		$out = array();

		foreach($terms as $term){

			$term = preg_replace_callback("/\{WHITESPACE-([0-9]+)\}/", function($a) {return chr($a);}, $term);
			$term = preg_replace("/\{COMMA\}/", ",", $term);

			$out[] = $term;
		}

		return $out;
	}

	function search_transform_term($term){
		$term = preg_replace_callback("/(\s)/", function($a) { return '{WHITESPACE-'.ord($a).'}';}, $term);
		$term = preg_replace("/,/", "{COMMA}", $term);
		return $term;
	}

	function search_escape_rlike($string){
		return preg_replace("/([.\[\]*^\$])/", '\\\$1', $string);
	}

	function search_db_escape_terms($terms){
		$out = array();
		foreach($terms as $term){
			$out[] = '[[:<:]]'.AddSlashes(search_escape_rlike($term)).'[[:>:]]';
		}
		return $out;
	}
	/*
	function search_perform($terms){

		$terms = search_split_terms($terms);
		$terms_db = search_db_escape_terms($terms);
		$terms_rx = search_rx_escape_terms($terms);

		$parts = array();
		foreach($terms_db as $term_db){
			$parts[] = "content_body RLIKE '$term_db'";
		}
		$parts = implode(' AND ', $parts);

		$sql = "SELECT * FROM Content WHERE $parts";

		$rows = array();

		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){

			$row[score] = 0;

			foreach($terms_rx as $term_rx){
				$row[score] += preg_match_all("/$term_rx/i", $row[content_body], $null);
			}

			$rows[] = $row;
		}

		uasort($rows, 'search_sort_results');

		return $rows;
	}
	//*/
	function search_saveequivalence($terms, $rows_inp, $cols_search){

		$terms = search_split_terms($terms);
		$terms_rx = search_rx_escape_terms($terms);
		$rows=array();
		foreach($rows_inp as $row){
			$row['score'] = 0;
			foreach($cols_search as $col){
				foreach($terms_rx as $term_rx)
					$row['score'] += preg_match_all("/$term_rx/i", $row[$col], $null);
				foreach($terms as $term)
					$row['score'] += (strtolower($term)==strtolower($row[$col]))?1:0;
			}
			$rows[] = $row;
		}
		return $rows;
	}
	function search_wherequery($terms, $cols, $mode='AND', $submode='AND'){

		$terms = search_split_terms($terms);
		$terms_db = search_db_escape_terms($terms);

		$parts = array();
		foreach($terms as $term_db){
			$parts2=array();
			$term_db=strtolower($term_db);
			foreach($cols as $col){
				$parts2[] = "`$col` LIKE '%$term_db%'";
			}
			$parts[]="(".implode(" $submode ", $parts2).")";
		}
		$parts = implode(" $mode ", $parts);
		return $parts;
	}

	function search_rx_escape_terms($terms){
		$out = array();
		foreach($terms as $term){
			$out[] = '.*'.preg_quote($term, '/').'.*';
		}
		return $out;
	}

	function search_sort_results($a, $b){

		$ax = $a[$GLOBALS['_SORT_BY_']];
		$bx = $b[$GLOBALS['_SORT_BY_']];

		if ($ax == $bx){ return 0; }
		return ($ax > $bx) ? -1 : 1;
	}

	function search_html_escape_terms($terms){
		$out = array();

		foreach($terms as $term){
			if (preg_match("/\s|,/", $term)){
				$out[] = '"'.HtmlSpecialChars($term).'"';
			}else{
				$out[] = HtmlSpecialChars($term);
			}
		}

		return $out;	
	}

	function search_pretty_terms($terms_html){

		if (count($terms_html) == 1){
			return array_pop($terms_html);
		}

		$last = array_pop($terms_html);

		return implode(', ', $terms_html)." and $last";
	}
/*

	#
	# do the search here...
	#

	$results = search_perform($HTTP_GET_VARS[q]);
	$term_list = search_pretty_terms(search_html_escape_terms(search_split_terms($HTTP_GET_VARS[q])));


	#
	# of course, we're using smarty ;)
	#

	$smarty->assign('term_list', $term_list);

	if (count($results)){

		$smarty->assign('results', $results);
		$smarty->display('search_results.txt');
	}else{

		$smarty->display('search_noresults.txt');
	}
//*/
?>

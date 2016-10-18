<?php
// テーブル名をプリフィック付きのテーブル名にする
function DBtableName($name) {
	$db = Database::getInstance();
	return $db->prefix($name);
}

// キーワードをlike検索時の条件式の値に変換する SQLエスケープも行なう
function keyword2condition($keyword, $matchingMethod = "partial") {
	$keyword = mysqlEscape($keyword);
	$matchingMethod = strtolower($matchingMethod);
	if($matchingMethod == "partial") {
		$keyword = "%{$keyword}%";
	} else if($matchingMethod == "prefix") {
		$keyword = "{$keyword}%";
	} else if($matchingMethod == "suffix") {
		$keyword = "%{$keyword}";
	}
	return $keyword;
}

function allKeywords2conditions($keywords, $matchingMethod = "partial") {
	$results = array();
	foreach($keywords as $word) {
		$results[] = keyword2condition($word, $matchingMethod);
	}
	return $results;
}

function mysqlEscape($str) {
	if ( get_magic_quotes_gpc() ) {
		$str = stripslashes( $str );
	}
	return mysql_real_escape_string($str);
}

function getMicrotime() {
	list($msec, $sec) = explode(" ", microtime());
	return ((float)$sec + (float)$msec);
}

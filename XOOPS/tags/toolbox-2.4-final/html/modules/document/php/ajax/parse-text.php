<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //
require_once('../../../../mainfile.php');
require_once('../Validator.php');
try{
	if (!isset($_POST['sourceLang'])) {
		throw new Exception("Paramater 'Source Language Code' is required.");
	}
	if (!isset($_POST['source'])) {
		throw new Exception("Paramater 'Source Contents' is required.");
	}

	$dLang = $_POST['sourceLang'];
	$dSource = $_POST['source'];
	$dParseMode = array_key_exists('parseMode', $_POST) ? $_POST['parseMode'] : "SENTENCE";
//	$dParseMode = array_key_exists('parseMode', $_POST) ? $_POST['parseMode'] : "RETURN";
	if (get_magic_quotes_gpc()) {
		$dSource = stripslashes($dSource);
		$dLang = stripslashes($dLang);
		$dParseMode = stripslashes($dParseMode);
	}

//	$lang = str_replace("&amp;", "&", htmlspecialchars($dLang, ENT_QUOTES, "UTF-8"));
//	$source = str_replace("&amp;", "&", htmlspecialchars($dSource, ENT_QUOTES, "UTF-8"));
	$lang = $dLang;
	$source = $dSource;
	$parseMode = $dParseMode;
	
	$validater = new Validator();
	if (!$validater->validateSupportedSourceLanguage($dLang)) {
		throw new Exception("Parameter 'Soruce Language Code' is not supported.");
	}

	$contents = array();
	$results = array();
	if ($parseMode == "RETURN") {
		$contents = str_replace("&nbsp;"," ",$source);
		$contents = strip_tags($contents);
		$contents = preg_split("/((\r\n?)|(\r?\n))/", $contents);		
		for($i = 0; $i < count($contents); $i++) {
			$results[] = $contents[$i];
			if($contents[$i] != "") {
				$results[] = "";
			} 
		}
	} else if($parseMode == "SENTENCE") {
		require_once('../text-processor.php');
		$sentences = preprocessOriginal($lang, $source);
		
		// Ignore dot "." if other stop marks is used on sentence.
		if (in_array($lang, array("ja", "zh-CN", "zh-TW"))) {
			$matches = array();
			$subject = preg_replace("/\".*?\"/", "", $sentences);
			if ((preg_match_all("/。/", $subject, $matches)) > 0) {
				ExceptionWord::updateSeparator($lang, array('。','？','！','?','!'));
			} else if ((preg_match_all("/．/", $subject, $matches)) > 0) {
				ExceptionWord::updateSeparator($lang, array('．','？','！','?','!'));
			} else if ((preg_match_all("/\./", $subject, $matches)) > 0) {
				ExceptionWord::updateSeparator($lang, array('.','？','！','?','!'));
			}
		}
		
		while ($sentences != '') {
			$parsed = get_first_sentence($lang, $sentences);
			$contents[]  = $parsed['first'];
			$sentences = $parsed['remain'];
		}
		$results = $contents;
	}
	echo json_encode(array($results));
} catch (Exception $e) {
	$contentes = array();
	$contents['message'] = $e->getMessage();
	echo json_encode(array($contents));
}
?>

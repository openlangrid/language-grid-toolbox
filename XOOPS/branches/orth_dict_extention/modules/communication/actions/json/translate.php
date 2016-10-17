<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

require_once(XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php');

header('Content-Type: text/html; charset=utf-8;');

$sourceLang = $_POST['sourceLanguageCode'];
$targetLang = $_POST['targetLanguageCode'];
$source = $_POST['sourceText'];


$sourceLines = explode("\n",$source);
$targetList = array();
$backTransList = array();

foreach ($sourceLines as $line) {
    $line = unescape_magic_quote($line);
    if (strlen($line) > 0) {
    	$result = translateLine($sourceLang, $targetLang, $line);
    	if(!$result) break;
    	
        $targetList[] = $result['target'];
        $backTransList[] = $result['backTran'];
    } else {
        $targetList[] = '';
        $backTransList[] = '';
    }
}

$retobj = array(
    'status' => $result['status'],
    'message' => $result['message'],
    'contents' => array(
        'targetText' => array(
            'contents' => implode("\n", $targetList)
            ),
        'backTranslateText' => array(
            'contents' => implode("\n", $backTransList)
            )));

echo json_encode($retobj);
exit;

function translateLine($sourceLang, $targetLang, $line) {
	$result = array( "target" => "",	"backTran" => "" );
    foreach(splitIgnoreTags($line) as $i => $text) {
    	if($i % 2 == 0) {
			$translationClient =& new LangridAccessClient();
        	$response = $translationClient->backTranslate($sourceLang, $targetLang, $text, "COMMUNICATION");
        	$result["target"]   .= $response['contents'][0]->intermediateResult;
        	$result["backTran"] .= $response['contents'][0]->targetResult;
        	$result["status"] = $response["status"];
        	$result["message"] = $response["message"];
    	} else {
    		$result["target"]   .= $text;
    		$result["backTran"] .= $text;
    	}
    }
    return $result;
}

function splitIgnoreTags($text) {
	return preg_split("/\[tag\](.*?)\[\/tag\]/", $text, -1, PREG_SPLIT_DELIM_CAPTURE);
}

/*
function ignoreTags($text) {
	if(preg_match_all("/\[tag\](.*?)\[\/tag\]/", $text, $matches)) {
		$restore = array();
		foreach($matches[0] as $i => $ptn) {
			$replaceVar = "----$i----";
			$text = str_replace($ptn, $replaceVar, $text);
			$restore[$replaceVar] = $matches[1][$i];
		}
		return array($text, $restore);
	} else {
		return array($text, array());	
	}
}
function restoreIgnoreTags($text, $expressions) {
	foreach($expressions as $var => $str) {
		$text = str_replace($var, $str, $text);
	}
	return $text;
}
*/

?>
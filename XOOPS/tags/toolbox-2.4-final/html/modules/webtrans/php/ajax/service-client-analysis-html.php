<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009  NICT Language Grid Project
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
require_once XOOPS_MODULE_PATH.'/langrid/php/service/other/HtmlTextExtractorClient.class.php';


$html = "";
if(isset($_POST['html'])){
	$html = $_POST['html'];
}
$html = htmlspecialchars_decode($html,ENT_QUOTES);

$hteClient = new HtmlTextExtractorClient();
$response = $hteClient->separate($html);
if($response['status'] == 'OK' && $response['contents']['status'] == 'OK'){
	$sentenceAndCodes = array();
	$sentenceCodes = array();
	$sourceText = array();
	foreach($response['contents']['contents']->codesAndTexts as $codetxt){
		$sentenceAndCodes[] = new SentenceAndCode($codetxt->text);
		$sentenceCodes[] = $codetxt->code;
		$sourceText[] = $codetxt->text;
	}
	
	$result = array(
		'status' => 'OK',
		'message' => 'success',
		'contents' => array(
			'result' => array(
				'sentenceAndCodes' => $sentenceAndCodes,
				'sentenceCodes' => $sentenceCodes,
				'sourceText' => $sourceText,
				'skeletonHtml' => $response['contents']['contents']->skeletonHtml
			)
		)
	);
}else{
	$result = array(
		'status' => 'ERROR',
		'message' => 'html analyze error',
		'contents' => array()
	);
	
}
header('Content-Type: text/html; charset=utf-8;');
echo json_encode($result);

class SentenceAndCode {
	function SentenceAndCode($st) {
		$this -> analysisText = $st;
		$this -> codes = array();
		$this -> sourceText = $st;
		$this -> words = array();
	}
}
?>
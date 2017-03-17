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

function webupload(){
	$response = array('status'=>'OK', 'message' => 'Successful Web Translation Upload', 'contents' => array());

	$tmpFilePath = $_FILES['uploadFileName']['tmp_name'];
	$tmpFileLines = file($tmpFilePath);
	if(is_array($tmpFileLines)){
		//* FFFE->"UTF16-LE"
		//* FEFF->"UTF16-BE"
		//* otherwise->false
		$code = mb_detect_encoding(implode('',$tmpFileLines),"ASCII,EUC-JP,SJIS-win,UTF-8");

		if(!$code){
			if(ord($tmpFileLines[0]{0}) == 255 && ord($tmpFileLines[0]{1}) == 254)
				$code = "UTF-16LE";
			else if(ord($tmpFileLines[0]{0}) == 254 && ord($tmpFileLines[0]{1}) == 255)
				$code = "UTF-16BE";
			else $error = "Invalid Encoding.";
		}


		foreach($tmpFileLines as $aline)
			$tmpFileContent .= $aline;

		$utf8content = mb_convert_encoding($tmpFileContent, 'UTF-8', $code);

		//$enc = mb_convert_variables('UTF-8', $code, $tmpFileLines);
		//$utf8content = implode('',$tmpFileLines);

		if (ord($utf8content{0}) == 0xef && ord($utf8content{1}) == 0xbb && ord($utf8content{2}) == 0xbf) {
			$utf8content = substr($utf8content, 3);
		}

		if(trim($utf8content) != ""){
			$hteClient = new HtmlTextExtractorClient();
			$sepcnt = $hteClient->separate($utf8content);
			if($response['status'] == 'OK' && $response['contents']['status'] == 'OK'){
				$html = $response['contents']['contents']->skeletonHtml;
				$temp = "";
				$codesAndTexts = array_reverse($response['contents']['contents']->codesAndTexts);
				foreach($codesAndTexts as $codetxt){
					$html = str_replace($codetxt->code,$codetxt->text,$html);
				}
				$response['contents'] = $html;
			}else{
				$response['contents'] = $utf8content;
			}
		}else{
			$response['contents'] = "";
		}
	}else{
		$response['contents'] = "";
	}

	echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
	echo "<title>-</title></head><body>";
	echo "<script language='JavaScript' type='text/javascript'>"."\n";
	if( isset($_POST['callback']) && $_POST['callback'] == "translated" ){
		echo "window.parent.WebTranslationWorkspace.prototype.uploadTranslated(" . json_encode($response) . ");";
	}else{
		echo "window.parent.WebTranslationWorkspace.prototype.uploadOriginal(" . json_encode($response) . ");";
	}
	echo "\n"."</script>";
	echo "</body></html>";
}

webupload();

?>
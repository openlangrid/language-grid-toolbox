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
require_once(dirname(__FILE__).'/../class/GetWebPageContentsClass.php');
require_once XOOPS_MODULE_PATH.'/langrid/php/service/other/HtmlTextExtractorClient.class.php';
error_reporting(0);

$url = $_POST['url'];

$GetWebCont = new GetWebPageContentsClass();
$url = $GetWebCont->getValidUri($url);
$res = $GetWebCont->ImportWebPage($url);
if(!$res){
	echo _MI_WEBTRANS_MSG_URL_INVALID;
}else{
	$hteClient = new HtmlTextExtractorClient();
	$response = $hteClient->separate($res,$url);
	if($response['status'] == 'OK' && $response['contents']['status'] == 'OK'){
		$html = $response['contents']['contents']->skeletonHtml;
		$temp = "";
		$codesAndTexts = array_reverse($response['contents']['contents']->codesAndTexts);
		foreach($codesAndTexts as $codetxt){
			$html = str_replace($codetxt->code,$codetxt->text,$html);
		}
		echo $html;
	}else{
		echo $res;
	}
}
?>
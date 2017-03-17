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

$instance = new ServiceClientFormatHtml();
echo $instance->format();

class ServiceClientFormatHtml
{
	function __construct() {
	}

	function format(){
		$html = $_POST['html'];
		$html = stripslashes($html);
		$ret = $this->doFormat($html);
		return json_encode( $ret );
	}

	function doFormat($source){
		$hteClient = new HtmlTextExtractorClient();
		$response = $hteClient->separate($source);
		if($response['status'] == 'OK' && $response['contents']['status'] == 'OK'){
			$html = $response['contents']['contents']->skeletonHtml;
			$temp = "";
			$codesAndTexts = array_reverse($response['contents']['contents']->codesAndTexts);
			foreach($codesAndTexts as $codetxt){
				$html = str_replace($codetxt->code,$codetxt->text,$html);
			}
		}else{
			$html = $source;
		}

		$result = array();
		$result['status'] = 'OK';
		$result['contents'] = array('result' => $html);
		return $result;
	}
}
?>

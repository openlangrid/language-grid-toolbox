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
$instance = new ServiceClientApplyTemplate();
header('Content-Type: text/html; charset=utf-8;');
echo $instance->apply();

class PairTemplate {
	function PairTemplate($s, $t) {
		$this -> source = $s;
		$this -> target = $t;
	}
}
class ServiceClientApplyTemplate 
{
	function __construct() {}
	
	function apply(){
		$html = "";
		$template = "";
		if(isset($_POST['html'])){
			$html = $_POST['html'];
		}
		if(isset($_POST['template'])){
			$template = $_POST['template'];
		}
		$ret = $this->doApply($html, $template);
		return json_encode( $ret );
	}
	
	function doApply($source, $template){
		if(!is_array($template)){
			$template = array();
		}
		
		$apply = $source;
		$skeleton = $source;
		$templateInstanceArray = array();
		$templateCode = array();
		$TempCnt = 0;
		foreach($template as $item){
			if($item['SOURCE_TEXT'] == null || $item['SOURCE_TEXT'] == ""){
				continue;
			}
			if(strpos($skeleton,$item['SOURCE_TEXT']) !== false){
				$Key = "<TEMPLATE:".$TempCnt.">";
				$skeleton = str_replace($item['SOURCE_TEXT'],$Key,$skeleton);
				$apply = str_replace($item['SOURCE_TEXT'],$item['TARGET_TEXT'],$apply);
				
				$templateInstanceArray[] = new PairTemplate($item['SOURCE_TEXT'], $item['TARGET_TEXT']);
				$templateCode[] = $Key;
				$TempCnt++;
			}
		}
		$res['applyHtml'] = $apply;
		$res['skeletonHtml'] = $skeleton;
		$res['templateCodes'] = $templateCode;
		$res['templates'] = $templateInstanceArray;
		
		$result['status'] = 'OK';
		$result['contents'] = array('result' => $res);

		return $result;
	}
}
?>

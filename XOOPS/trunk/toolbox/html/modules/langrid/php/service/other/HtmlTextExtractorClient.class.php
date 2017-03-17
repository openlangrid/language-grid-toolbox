<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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
require_once(dirname(__FILE__).'/../../service/ServiceClient.class.php');

class HtmlTextExtractorClient extends ServiceClient {

	public function __construct() {
		parent::__construct('wsdl/HtmlTextExtractor');
	}

	public function separate($htmlDocument,$baseUrl = "") {
		$parameters = array(
			'htmlDocument' => $htmlDocument,
			'baseUrl' => $baseUrl
		);
		$res = parent::call('separate', $parameters);
		
		if ( $res['status'] == 'ERROR' ) {
			$result['status'] = 'ERROR';
			$result['message'] = $res['message'];
			//$result['contents'] = array();
			$result['contents'] = array('res' => print_r($res,true));

		} else {
			$result['status'] = 'OK';
			$result['message'] = 'html separate successed.';
			$result['contents'] = $this->formatInlineTags($res);
		}
		return $result;
	}
	
	private function formatInlineTags(&$res){
		$inline_tags = array("span","em","strong","abbr","acronym","dfn","q","cite","sup",
			"sub","code","var","kbd","samp","bdo","font","big","small",
			"b","i","s","strike","u","tt","a","label",
			"textarea","basefont","img","br","input","script","style","map");
		
		$sentenceCodes = array();
		$sourceText = array();
		foreach($res['contents']->codesAndTexts as $k => $codetxt){
			$sentenceCodes[$k] = $codetxt->code;
			$sourceText[$k] = $codetxt->text;
		}

		$html = $res['contents']->skeletonHtml;
		$html = str_replace("\r\n","\n",$html);
		$html = str_replace("\r","\n",$html);
		
		foreach($inline_tags as $tag){
			$html = preg_replace('/<'.$tag.'(( |\/)[^>]*>|>)/i',"\n<".$tag."\\1",$html);
		}
		$html = str_replace(" \n"," ",$html);
	
		$lines = explode("\n",$html);

		foreach($lines as $k => $line){
			foreach($inline_tags as $tag){
				if(strpos($line,"<".$tag) !== false){
					if($k != 0){
						if(strpos($line,"<".$tag) === 0){
							$space = "";
							$pre_line = $lines[$k-1];
							for($i=0;$i<strlen($pre_line);$i++){
								$s = substr($pre_line,$i,1);
								if($s == " "){
									$space .= $s;
								}else{
									break;
								}
							}
							$line = $space.$line;
						}
					}
					if($tag == "script" || $tag == "style" || $tag == "map"){
						foreach($sentenceCodes as $Key => $code){
							if(strpos($line,">".$code."<") > 1){
								$line = str_replace(">".$code."<",">".$sourceText[$Key]."<",$line);
								unset($res['contents']->codesAndTexts[$Key]);
							}
						}
					}
				}
			}
			$lines[$k] = $line;
		}
		$html = implode("\n",$lines);
		
		$res['contents']->skeletonHtml = $html;
		return $res;
	}

	protected function makeBindingHeader($parameters){
		return '';
	}

	public function getServiceId() {
		return $this->serviceId;
	}

	public function getSoapBindings() {
		return 'This service is Atomic.';
	}
}

?>
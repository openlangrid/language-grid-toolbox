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
$instance = new WebPageTranslationMultiPost();
echo $instance->translate();

class WebPageTranslationMultiPost {
	protected $moduleConfig = null;

	function __construct() {
		$this->moduleConfig = $this->_getXoopsModuleConfig();
	}

	function translate(){
		$from = $_POST['from'];
		$to = $_POST['to'];
		$html = $_POST['html'];

		if(!is_array($html)){
			$html = array();
		}

		$ret = $this->doTranslate($from, $to, $html);
		return json_encode($ret);
	}

	function doTranslate($from, $to, $html){
		$curly = array();
		$result = array();
		$mh = curl_multi_init();


		for($i=0; $i<count($html); $i++){
			$param = array(
				session_name() => session_id(),
				'from' => $from,
				'to' => $to,
				'html' => $html[$i]
			);

			$curly[$i] = curl_init();

			$mydirname = basename(realpath(dirname(__FILE__)."/../../"));
			$MyUrl = XOOPS_URL.'/modules/'.$mydirname;
			curl_setopt($curly[$i],CURLOPT_URL,$MyUrl.'/?page=service-client-translation');

			curl_setopt($curly[$i],CURLOPT_HEADER,false);
			curl_setopt($curly[$i],CURLOPT_POST,true);
			curl_setopt($curly[$i],CURLOPT_RETURNTRANSFER,true);

			if ($param != ""){
				curl_setopt($curly[$i],CURLOPT_POSTFIELDS,$param);
			}
/*
			if ( trim($this->moduleConfig["proxy_host"]) != "") {
				curl_setopt($curly[$i], CURLOPT_HTTPPROXYTUNNEL, 1);
				$proxy = 'http://'.trim($this->moduleConfig["proxy_host"]);
				if(trim($this->moduleConfig["proxy_port"]) != "" && is_numeric(trim($this->moduleConfig["proxy_port"]))) {
					$proxy .= ":".trim($this->moduleConfig["proxy_port"]);
					curl_setopt($curly[$i], CURLOPT_PROXY, $proxy);
					curl_setopt($curly[$i], CURLOPT_PROXYPORT, trim($this->moduleConfig["proxy_port"]));
				}else{
					curl_setopt($curly[$i], CURLOPT_PROXY, $proxy);
				}
			}
*/
			curl_multi_add_handle($mh, $curly[$i]);
		}

		$running = null;
		do {
			$mrc = curl_multi_exec($mh, $running);
		} while($running > 0);

		foreach($curly as $id => $c) {
			$result[$id] = $this->test(curl_multi_getcontent($c));
			curl_multi_remove_handle($mh, $c);
		}

		curl_multi_close($mh);

		return json_encode($result);
		//return array('results' => $result, 'profile' => "");
	}

	function test($in){
		return $in;
		$txt = base64_decode($in);
//		$dist = json_decode($txt, false);
		$dist = unserialize($txt);
//		print_r($dist);
		return $dist;
	}

	// load to langrid module config.
	private function _getXoopsModuleConfig() {
		$module_handler= & xoops_gethandler('module');
		$psModule = $module_handler->getByDirname('langrid');
		$config_handler =& xoops_gethandler('config');
		$config =& $config_handler->getConfigsByCat(0, $psModule->mid());

		if ($config == null) {
			die('Failed to retrieve config.['.__FILE__.'('.__LINE__.')]');
		}
		return $config;
	}
}
?>

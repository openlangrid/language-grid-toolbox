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
require_once "HTTP/Request.php";

class GetWebPageContentsClass {
	protected $moduleConfig = null;

	function __construct() {
		$this->moduleConfig = $this->_getXoopsModuleConfig();
	}

	function ImportWebPage($url){
		//return print_r($this->moduleConfig,true);

		$url = trim($url);
		if($url == ""){return false;}

		$proxy = "";
		if ( trim($this->moduleConfig["proxy_host"]) != "") {
			$proxy = 'tcp://'.trim($this->moduleConfig["proxy_host"]);
			if(trim($this->moduleConfig["proxy_port"]) != "" && is_numeric(trim($this->moduleConfig["proxy_port"]))) {
				$proxy .= ":".trim($this->moduleConfig["proxy_port"]);
			}
		}

		$context = null;
		if($proxy != ""){
			$default_opts = array(
				'http'=>array(
					'method'=>"GET",
					'proxy'=>$proxy
				)
			);
			$default = stream_context_get_default($default_opts);
			$context = stream_context_create($default_opts);
		}

		$uri = $this->getValidUri($url);
		if(!$uri){return false;}

		//***********************************
		$option = array(
		    "timeout" => "10",
		    "allowRedirects" => true,
		    "maxRedirects" => 3
		);

		$http =& new HTTP_Request($uri,$option);
		
		if ($this->moduleConfig["proxy_host"] != '' && $this->moduleConfig["proxy_port"] != '') {
			$http->setProxy($this->moduleConfig["proxy_host"], $this->moduleConfig["proxy_port"]);
		}

		$http->sendRequest();

		$response = $http->sendRequest();
		if (!PEAR::isError($response)) {
			$ret3 = $http->getResponseBody();
			//$ret3 = $http->getResponseCode();
			//$ret3 = $http->getResponseHeader();

			$enc = mb_convert_variables('UTF-8', "ASCII,EUC-JP,SJIS-win,UTF-8",$ret3);

			//return print_r($ret3,true);

			$contents = explode("\n",$ret3);

			$contents = array_map(array('GetWebPageContentsClass','_replaceTab'),$contents);

			return implode("\r\n",$contents);
		}else{
			//return $response;
			return false;
		}

		//*******************************************

		/*
		$contents = @file($uri, 0,$context);

		if($contents){
			$enc = mb_convert_variables('UTF-8', "ASCII,EUC-JP,SJIS-win,UTF-8", $contents);

			$contents = array_map(array('GetWebPageContentsClass','_replaceTab'),$contents);

			return implode("\r\n",$contents);
		}else{
			return false;
		}
		*/
	}

	function getValidUri($url){
		if(is_array($url)){$url = $url[0];}

		if (preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $url)) {
			return $url;
		} else {
			return false;
		}

		/*
		if($headers = @get_headers($url,1)){
			if(strstr($headers[0],'301 Moved Permanently')){
				return $this->getValidUri($headers['Location']);
			}else{
				if(strstr($headers[0],'404 Not Found')){
					echo "404 Not Found";
					return false;
				}else{
					if(strstr(print_r($headers['Content-Type'],true),'text/html')){
						return $url;
					}else{
						return false;
					}
				}
			}
		}else{
			echo "URL file-access is disabled. \n";
			return false;
		}
		*/
	}

	private function _replaceTab($line){
		$line = str_replace("\t",' ',rtrim($line));
		return $line;
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
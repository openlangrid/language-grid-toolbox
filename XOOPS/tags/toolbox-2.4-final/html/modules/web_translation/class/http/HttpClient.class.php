<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

class HttpClient {
	
	protected $moduleConfig = null;
	private $url = null;

	/**
	 * Constructor
	 * @param unknown_type $url
	 */
	public function __construct() {
		$this->moduleConfig = $this->_getXoopsModuleConfig();
	}

	/**
	 * 
	 */
	public function getContents($url){
		$http = new HTTP_Request($url, $this->getOption());
		
		if ($this->useProxy()) {
			$http->setProxy($this->getProxtHost(), $this->getProxyPort());
		}

		$response = $http->sendRequest();

		if (PEAR::isError($response)) {
			throw new Exception('HTTP Error.');
		}

		$body = $http->getResponseBody();
		$this->url = $http->getUrl();

		$this->encode($body);

		return $body;
	}
	
	private function encode(&$contents) {
		mb_convert_variables('UTF-8', "ASCII,EUC-JP,SJIS-win,UTF-8", $contents);
	}

	/**
	 * @return bool whether using proxy or not
	 */
	private function useProxy() {
		return $this->getProxtHost() && $this->getProxyPort();
	}
	
	private function getProxtHost() {
		return $this->moduleConfig["proxy_host"];
	}
	
	private function getProxyPort() {
		return $this->moduleConfig["proxy_port"];
	}
	
	private function getOption() {
		$option = array(
		    "timeout" => "10",
		    "allowRedirects" => true,
		    "maxRedirects" => 3
		);
		
		return $option;
	}

	private function _replaceTab($line){
		$line = str_replace("\t",' ',rtrim($line));
		return $line;
	}

	// load langrid module config.
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

	public function getUrl() {
		return $this->url;
	}
}
?>
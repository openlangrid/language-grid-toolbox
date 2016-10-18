<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
require_once('HTTP/Request2.php');

class QAManager {

	function __construct() {

	}

	// Qを検索
	function search($params) {
		return $this->sendHttp(APP_BACKEND_URL.'search.php', 'POST', $params);
	}

	// １件のQAを取得
	function load($id, $useLang) {
		return $this->sendHttp(APP_BACKEND_URL.'load.php?id='.$id.'&use_lang='.$useLang);
	}

	function post($params) {
		return $this->sendHttp(APP_BACKEND_URL.'post.php', 'POST', $params);
	}

	function postAnswer($params) {
		return $this->sendHttp(APP_BACKEND_URL.'post_answer.php', 'POST', $params);
	}

	function getUseLanguages() {
		return $this->sendHttp(APP_BACKEND_URL.'getlanguage_search.php');
	}

	function getQaLanguages($name) {
		return $this->sendHttp(APP_BACKEND_URL.'getlanguage_search.php?name='.$name);
	}

	function getPostLanguages() {
		return $this->sendHttp(APP_BACKEND_URL.'getlanguage_post.php');
	}

	function getCategory($categoryId = null) {
		if ($categoryId) {
			return $this->sendHttp(APP_BACKEND_URL.'getcategory.php?category_id='.$categoryId);
		} else {
			return $this->sendHttp(APP_BACKEND_URL.'getcategory.php');
		}
	}
	
	function getPostingConfig_exist() {
		return $this->sendHttp(APP_BACKEND_URL.'getconfig_exist.php','POST',array("mode"=>1));
	}

	function getSearchConfig_exist() {
		return $this->sendHttp(APP_BACKEND_URL.'getconfig_exist.php','POST',array("mode"=>2));
	}

	function getBBSForumConfig_exist() {
		return $this->sendHttp(APP_BACKEND_URL.'getconfig_exist.php','POST',array("mode"=>3));
	}
	

	function sendHttp($url, $method = 'GET', $postData = null) {
		$http = new HTTP_Request2();
		if ($method == 'GET') {
			$http->setMethod(HTTP_Request2::METHOD_GET);
		} else {
			$http->setMethod(HTTP_Request2::METHOD_POST);
		}
		$http->setURL($url);
		if ($postData) {
			foreach ($postData as $key => $val) {
				$http->addPostParameter($key, $val);
			}
		}

		try {
			$response = $http->send();
			if ($response->getStatus() == '200') {
				$text = $response->getBody();
				$json = json_decode($text, true);
				return $json;
			}
		} catch (HTTP_Request2_Exception $e) {
			die($e->getMessage());
		} catch (Exception $e) {
			die($e->getMessage());
		}
		return null;
	}
}
?>

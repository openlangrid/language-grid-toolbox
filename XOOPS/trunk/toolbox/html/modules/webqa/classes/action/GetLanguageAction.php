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
require_once(dirname(__FILE__).'/../Abstract_WebQABackendAction.class.php');

class GetLanguageAction extends Abstract_WebQABackendAction {

	public function SearchAction() {

	}

	protected function getName() {
		return null;
	}

	protected function getNames() {
		return null;
	}
	
	public function sort($a, $b) {
		if ($a == $b) {
			return 0;
		}
		require(XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php');
		foreach ($LANGRID_LANGUAGE_ARRAY as $key => $value) {
			if ($key == $a) {
				return -1;
			}
			if ($key == $b) {
				return 1;
			}
		}
		return 0;
	}

	public function dispatch() {
		require_once(XOOPS_ROOT_PATH.'/api/class/client/ResourceClient.class.php');
		require(XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php');
		$api = new ResourceClient();
		$names = $this->getNames();
		$languages = array();
		foreach ($names as $name) {
			if (!$name) {
				continue;
			}

			$result =& $api->getLanguageResource($name);

			if ($result['status'] == 'OK') {
				$langs = $result['contents']->languages;
				foreach ($langs as $lang) {
					$languages[$lang] = $LANGRID_LANGUAGE_ARRAY[$lang];
				}
			}
		}
		uksort($languages, array($this, 'sort'));
		return $languages;
	}
	
	public function getLanguages() {
		
	}
}

class GetSearchLanguageAction extends GetLanguageAction {
	protected function getName() {
		$config = $this->getModuleConfig();
		return $config['webqa_search'];
	}
	
	protected function getNames() {
		$config = $this->getModuleConfig();
		return explode(',', $config['webqa_search']);
	}
}

class GetQaLanguageAction extends GetLanguageAction {
	protected function getNames() {
		return array($this->getParameter('name'));
	}
}

class GetPostLanguageAction extends GetLanguageAction {
	protected function getName() {
		$config = $this->getModuleConfig();
		return $config['webqa_posting'];
	}
	
	protected function getNames() {
		$config = $this->getModuleConfig();
		return array($config['webqa_posting']);
	}
}

?>

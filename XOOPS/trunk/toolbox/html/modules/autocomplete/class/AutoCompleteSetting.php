<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides accurate
// translation using the autocomplete feature based on parallel texts and
// translation template.
// Copyright (C) 2010  CITY OF KYOTO
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
/** $Id: AutoCompleteSetting.php 3550 2010-03-25 07:36:17Z yoshimura $ */

require_once(XOOPS_ROOT_PATH.'/api/class/client/ResourceClient.class.php');
require_once(XOOPS_ROOT_PATH.'/api/class/manager/Toolbox_AbstractManager.class.php');
require_once(dirname(__FILE__).'/AutoCompleteSettingHandler.php');

class AutoCompleteSetting extends Toolbox_AbstractManager {

	private $resourceApiClient = null;
	private $settingHandler = null;

	public function AutoCompleteSetting() {
		parent::__construct();
		$this->resourceApiClient = new ResourceClient();
		$this->settingHandler = new AutoCompleteSettingHandler($this->db);
	}

	public function get() {
		$response = array();

		$paralleltextResults = $this->resourceApiClient->getAllLanguageResources('PARALLELTEXT');
		if ($paralleltextResults['status'] != 'OK') {
			die($paralleltextResults['message']);
		}
		$translationtempleteResults = $this->resourceApiClient->getAllLanguageResources('TRANSLATION_TEMPLATE');
		if ($translationtempleteResults['status'] != 'OK') {
			die($translationtempleteResults['message']);
		}

		foreach ($paralleltextResults['contents'] as $item) {
			$response[] = $item->name;
		}
		foreach ($translationtempleteResults['contents'] as $item) {
			$response[] = $item->name;
		}

		return $response;
	}

	public function load() {
		$objects = $this->settingHandler->searchByUserId($this->uid);
		$response = array();
		foreach ($objects as $obj) {
			$response[] = $obj->get('search_target');
		}
		return $response;
	}

	public function update($datas) {
		if ($this->settingHandler->deleteByUserId($this->uid) === false) {
			return false;
		}
		$row = 1;
		foreach ($datas as $data) {
			$new = $this->settingHandler->create(true);
			$new->set('user_id', $this->uid);
			$new->set('row_id', $row++);
			$new->set('search_target', $data);
			$new->set('create_time', time());
			if ($this->settingHandler->insert($new, true) === false) {
				return false;
			}
		}
		return true;
	}
}
?>

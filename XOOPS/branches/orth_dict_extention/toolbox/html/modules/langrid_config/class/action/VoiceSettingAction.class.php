<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2010  NICT Language Grid Project
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
/* $Id: IndexAction.class.php 3973 2010-08-24 05:16:57Z yoshimura $ */

require_once MY_MODULE_PATH.'/class/action/IndexAction.class.php';
require_once MY_MODULE_PATH.'/class/manager/VoiceSettingManager.class.php';

class VoiceSettingAction extends IndexAction {

	protected $languages;
	
	public function execute() {
		parent::execute();
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$setId = ($_GET['set'] == 'user') ? 7 : 8;
			VoiceSettingManager::save($_POST, $setId);
		}
	}
	
	/**
	 *
	 * @param unknown_type $render
	 */
	public function executeView($render) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$render->setAttribute('saveMessage', _MI_LANGRID_CONFIG_VOICE_SAVED);
		}
		$render->setTemplateName('langrid_config-voice.html');
		$render->setAttribute('resource', json_encode($this->getResource()));
		$render->setAttribute('module_img_url', XOOPS_URL.'/modules/langrid_config/images/');
		if ($_GET['set'] == 'user') {
			$setId = 7;
			$render->setAttribute('tabTitle', _MI_LANGRID_CONFIG_TAB_NAME_USER);
		} else {
			$setId = 8;
			$render->setAttribute('tabTitle', _MI_LANGRID_CONFIG_TAB_NAME_SITE);
		}
		$render->setAttribute('page', $_GET['set']);
		$render->setAttribute('data', VoiceSettingManager::load($setId));
		$this->load($render);
	}
	
	/**
	 *
	 * @param unknown_type $render
	 */
	protected function loadJs($render) {
	}
}
?>
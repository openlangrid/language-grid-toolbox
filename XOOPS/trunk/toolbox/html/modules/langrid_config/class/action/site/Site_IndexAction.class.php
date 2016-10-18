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
/* $Id: Site_IndexAction.class.php 4099 2010-09-03 08:05:25Z yoshimura $ */

require_once MY_MODULE_PATH.'/class/action/IndexAction.class.php';

class Site_IndexAction extends IndexAction {

	protected function getResource() {
		$resource = parent::getResource();

		$resource['url'] = array(
			'loadSetting' => './?page=site&action=LoadSetting',
			'saveSetting' => './?page=site&action=SaveSetting',
			'saveDictionary' => './?page=site&action=SaveDictionary',
			'loadServiceInfo' => './?action=LoadServiceInfo'
		);

		return $resource;
	}

	protected function getTabTitle() {
		return _MI_LANGRID_CONFIG_TAB_NAME_SITE;
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	protected function loadHowToUse($render) {
		$render->setAttribute('howToUse', XOOPS_URL.'/modules/langrid_config/how-to-use/'._MI_LANGRID_CONFIG_SITE_HOW_TO_USE_LINK);
	}
}
?>
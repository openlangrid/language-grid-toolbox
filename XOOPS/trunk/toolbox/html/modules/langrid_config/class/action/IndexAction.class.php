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
/* $Id: IndexAction.class.php 6226 2011-12-07 10:22:24Z mtanaka $ */

require_once MY_MODULE_PATH.'/class/action/AbstractAction.class.php';

class IndexAction extends AbstractAction {

	protected $languages;

	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 */
	public function execute() {
		parent::execute();
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	public function executeView($render) {
		$setId = 0;

		if ($this->getParameter('page') == 'user') {
			$render->setTemplateName('langrid_config-personal.html');
			
			require_once dirname(__FILE__).'/user/User_LoadSettingAction.class.php';
			$loadSettingAction = new User_LoadSettingAction();
		} else if ($this->getParameter('page') == 'client_control_shared') {
			$render->setTemplateName('langrid_config-personal.html');
			
			require_once dirname(__FILE__).'/client_control_shared/ClientControlShared_LoadSettingAction.class.php';
			$loadSettingAction = new ClientControlShared_LoadSettingAction();
		} else {
			// server_control_shared
			$render->setTemplateName('langrid_config-main.html');
			
			require_once dirname(__FILE__).'/server_control_shared/ServerControlShared_LoadSettingAction.class.php';
			$loadSettingAction = new ServerControlShared_LoadSettingAction();
		}

		$contents = $loadSettingAction->load();
		
		$render->setAttribute('set', $this->getParameter('page'));
		$render->setAttribute("INIT_DATA", json_encode(array('status' => 'OK', 'message' => 'Success', 'contents' => $contents)));

		$render->setAttribute('tabTitle', $this->getTabTitle());
		$render->setAttribute('resource', json_encode($this->getResource()));
		$render->setAttribute('max_dict_count', 5);
		$render->setAttribute('module_img_url', XOOPS_URL.'/modules/langrid_config/images/');

		$this->load($render);
	}

	protected function getTabTitle() {
		return '';
	}

	protected function getResource() {
		$resource = array();

		$constants = get_defined_constants(true);
		$prefix = '_MI_LANGRID_CONFIG_';

		$exp = array('NAME');
		foreach ($constants['user'] as $key => $value) {
			if (strpos($key, $prefix) === 0) {
				$key = '@@'.$key;
				$key = str_replace('@@'.$prefix, '', $key);

				if (!in_array($key, $exp)) {
					$resource[$key] = $value;
				}
			}
		}

		return $resource;
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	protected function load($render) {
		$this->loadJs($render);
		$this->loadCss($render);
		$this->loadHowToUse($render);
		$this->loadUserCss($render);
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	protected function loadJs($render) {
		require_once MY_MODULE_PATH.'/include/js.php';

		$suffix = $this->getSuffix();

		$header = $render->getAttribute('xoops_module_header');

		$header .= <<< EOF
<script><!--
jQuery.noConflict();
//--></script>
EOF;

		foreach ($js as $j) {
			$header .= '<script src="'.$j.$suffix.'"></script>'."\n";
		}

		$render->setAttribute('xoops_module_header', $header);
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	protected function loadUserCss($render) {
		$header = '<link rel="stylesheet" type="text/css" media="screen" href="./css/user_style.css"></link>';
		$render->setAttribute('user_define_header', $header);
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	protected function loadCss($render) {
		require_once MY_MODULE_PATH.'/include/css.php';

		$suffix = $this->getSuffix();

		$header = $render->getAttribute('xoops_module_header');
		foreach($css as $c) {
			$header .= '<link rel="stylesheet" type="text/css" media="screen" href="./css'.$c.$suffix.'"></link>';
		}

		$render->setAttribute('xoops_module_header', $header);
	}

	protected function getSuffix() {
//		return (APP_DEBUG_MODE) ? '?'.time() : '';
		return '?'.time();
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	protected function loadHowToUse($render) {
		$render->setAttribute('howToUse', _MI_LANGRID_TEXT_HOW_TO_USE_LINK);
	}
}
?>
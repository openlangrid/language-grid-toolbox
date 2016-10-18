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
/* $Id: Import_IndexAction.class.php 4654 2010-10-28 06:37:35Z yoshimura $ */

require_once MY_MODULE_PATH.'/class/action/IndexAction.class.php';

class Import_IndexAction extends IndexAction {

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
		$render->setTemplateName('langrid_config-imported_services.html');

		$userIsAdmin = true;

		$render->setAttribute('module_img_url', XOOPS_URL.'/modules/langrid_config/images/');
		$render->setAttribute('xoops_url', XOOPS_URL);
		$render->setAttribute('userIsAdmin', $userIsAdmin);
		$render->setAttribute('xoops_pagetitle', _MD_LANGRID_IMPORTED_SERVICES);

		$this->load($render);
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
		$javaScripts = array(
			'imported-services-config.php',
			'templates.js',
			'panel.js',
			'imported-services-language-selectors-panel.js',
			'imported-services-language-paths-panel.js',
			'light-popup-panel.js',
			'imported-services-add-service-popup-panel.js',
			'imported-services-edit-service-popup-panel.js',
			'table-panel.js',
			'imported-services-table-panel.js',
			'imported-services-panel.js',
			'imported-services-main.js'
		);

		$suffix = $this->getSuffix();

		$header = $render->getAttribute('xoops_module_header');
		foreach ($javaScripts as $j) {
			$header .= '<script src="./js/imported-services/'.$j.$suffix.'"></script>'."\n";
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
		$csss = array(
			'imported-services.css'
		);

		$suffix = $this->getSuffix();

		$header = $render->getAttribute('xoops_module_header');
		foreach($csss as $c) {
			$header .= '<link rel="stylesheet" type="text/css" media="screen" href="./css/'.$c.$suffix.'"></link>';
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
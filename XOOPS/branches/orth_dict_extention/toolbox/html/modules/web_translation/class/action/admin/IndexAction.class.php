<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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

require_once APP_ROOT_PATH.'/class/action/AbstractAction.class.php';
require_once APP_ROOT_PATH.'/class/toolbox/TranslatorSettingAdapter.class.php';

class IndexAction extends AbstractAction {

	private $languages;

	/**
	 *
	 */
	public function __construct() {

	}

	/**
	 *
	 */
	public function execute() {
		parent::execute();
		$handler= xoops_gethandler('module');
		$module = $handler->getByDirname(APP_DIR_NAME);
		$moduleId = $module->mid();

		$url = XOOPS_URL.'/modules/legacy/admin/index.php?action=PreferenceEdit&confmod_id='.$moduleId;
		header('Location: '.$url);
		die();
	}

	public function executeView($render) {
		$render->setTemplateName('web_translation_main.html');
	}
}
?>
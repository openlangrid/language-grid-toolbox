<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox.
//
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
/* $Id: AbstractPageController.php 3662 2010-06-16 02:22:17Z yoshimura $ */

error_reporting(0);

class AbstractPageController {

	protected $mActionName = '';

	public function __construct() {
	}

	public function perpare() {
		if (isset($_GET['action'])) {
			$this->mActionName = preg_replace( '/[^a-zA-Z0-9_\-]/' , '' , @$_GET['action'] );
			return 'ajax';
		}
		return 'page';
	}

	public function executeAjax($actionName) {}

	public function executePage() {}

	public function execute() {
		if ($this->perpare() == 'ajax') {
			$this->executeAjax($this->mActionName);
			exit();
		}

		$this->includeModuleHeader();
		$this->executePage();
	}


	protected function includeModuleHeader() {
		global $xoopsModule, $xoopsTpl, $xoopsOption;
		require dirname(__FILE__).'/../include/'.$this->mConfig['javascripts'];

		$jstag = PHP_EOL.'<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/js%s"></script>';
		$csstag = PHP_EOL.'<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/css%s" />';

		$xoops_module_header = $xoopsTpl->get_template_vars( "xoops_module_header" );
		foreach ($javascripts as $javascript) {
			$xoops_module_header .= sprintf($jstag, $javascript);
		}
		$xoops_module_header .= sprintf($csstag, '/style.css');
		$xoops_module_header .= sprintf($csstag, '/glayer.css');
		$xoops_module_header .= sprintf($csstag, '/popup.css');
		$user_define_header = sprintf($csstag, '/user_style.css');

		$xoopsTpl->assign(
			array(
				'user_define_header' => $user_define_header,
				'xoops_module_header' => $xoops_module_header,
				'pageConfig' => $this->mConfig,
				'howToUse' => _MI_UIC_HOW_TO_USE
			)
		);
		$xoopsTpl->assign('subpagetemplate', $this->mConfig['template']);
		$xoopsOption['template_main'] = 'uicustomize-frame.html';
	}
}
?>

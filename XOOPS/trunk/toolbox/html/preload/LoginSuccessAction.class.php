<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009-2010  NICT Language Grid Project
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
/* $Id: $ */
/**
 *
 * delete Screen Info Maite
 * translation path setting check user login.
 *
 */
if (!defined('XOOPS_ROOT_PATH')) exit();
include_once XOOPS_ROOT_PATH . '/modules/infoMainte/php/class/InfoDataControlClass.php';

class LoginSuccessAction extends XCube_ActionFilter
{
	//function preBlockFilter(){
	function preFilter(){
		//$root =& XCube_Root::getSingleton();
		$this->mRoot->mDelegateManager->add("Site.CheckLogin.Success", array(&$this,'deleteInfoMainte'));
		$this->mRoot->mDelegateManager->add("Site.CheckLogin.Success", array(&$this,'checkPathSetting'));
		//$root->mDelegateManager->add("Site.CheckLogin.Success", array(&$this,'deleteInfoMainte'));
	}
	function deleteInfoMainte(&$xoopsUser) {
		if(file_exists(XOOPS_ROOT_PATH.'/modules/infoMainte/php/class/InfoDataControlClass.php')) {
			if (!is_object($xoopsUser)) {
				return;
			}

			$InfoDataCtl = new InfoDataControlClass();
			//$uid = $xoopsUser->getVar('uid');
			$InfoDataCtl->clearAllData();
		}
	}
	function checkPathSetting(&$xoopsUser) {
		$file = XOOPS_ROOT_PATH . '/modules/langrid_config/class/manager/UserSettingRegistor.class.php';
		if (file_exists($file)) {
			require_once($file);
			$registor = new UserSettingRegistor();
			$registor->copyUserSettingFromAdmin($xoopsUser);
		}
	}
}
?>
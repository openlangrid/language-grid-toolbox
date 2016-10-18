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
/* $Id: User_LoadSettingAction.class.php 4686 2010-11-08 06:10:44Z kitajima $ */

require_once MY_MODULE_PATH.'/class/action/LoadSettingAction.class.php';
require_once MY_MODULE_PATH.'/class/manager/DefaultDictionaryAdapter.class.php';
require_once MY_MODULE_PATH.'/class/manager/TranslationPathSettingAdapter.class.php';
require_once MY_MODULE_PATH.'/class/action/user/User_common.php';

class User_LoadSettingAction extends LoadSettingAction {

	protected function loadDefaultDictionary() {
		$a = new DefaultDictionaryAdapter();
		$b = $a->loadDefaultDictionary(BINDING_SET_NAME, ToolboxUtil::getUserId());
		return $b;
    }

    protected function loadSetting() {
    	$a = new TranslationPathSettingAdapter();
    	try {
	    	$b = $a->loadTranslationSetting(BINDING_SET_NAME, ToolboxUtil::getUserId());
    	} catch (TranslationPathSettingAdapter_SetObjectNotFoundException $e) {
    		$a->createTranslationSetting(BINDING_SET_NAME, ToolboxUtil::getUserId());
    		$b = array();
    	}
    	return $b;
    }
}
?>
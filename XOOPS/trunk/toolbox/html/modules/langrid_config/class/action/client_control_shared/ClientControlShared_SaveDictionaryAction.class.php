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
/* $Id: Site_SaveDictionaryAction.class.php 4036 2010-08-31 02:19:12Z yoshimura $ */

require_once(MY_MODULE_PATH.'/class/action/SaveDictionaryAction.class.php');
require_once MY_MODULE_PATH.'/class/manager/DefaultDictionaryAdapter.class.php';
require_once MY_MODULE_PATH.'/class/manager/TranslationPathSettingAdapter.class.php';
require_once MY_MODULE_PATH.'/class/action/client_control_shared/ClientControlShared_common.php';

class ClientControlShared_SaveDictionaryAction extends SaveDictionaryAction {

    protected function save($data) {
    	$a = new DefaultDictionaryAdapter();
    	$a->saveDefaultDictionary(BINDING_SET_NAME, ToolboxUtil::getAdminUserId(), $data);

    	$b = new TranslationPathSettingAdapter();
    	$b->updateDefaultDictionary(BINDING_SET_NAME, ToolboxUtil::getAdminUserId(), $data);
    }
}
?>
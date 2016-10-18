<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
/**
 * @author kitajima
 */
require_once dirname(__FILE__).'/../class/factory/client-factory.php';
require_once dirname(__FILE__).'/../class/manager/qa-permission-manager.php';
require_once dirname(__FILE__).'/../class/util/qa-record-util.php';

try {
	$name = $_POST['name'];
	$newFlag = $_POST['newFlag'];
//	$language = $_POST['language'];
	$wordSetId = $_POST['wordSetId'];
	$expressions = $_POST['expressions'];
	
	$permissionManager = new QaPermissionManager();
	$permission = $permissionManager->getMyPermission($name);
	
	if ($permission < QaEnumPermission::EDIT) {
		throw new Exception('Permission denied.');
	}

	$expressionVos = QaRecordUtil::expressions2toolboxVos($expressions);
	
	$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
	$moduleClient = $factory->createModuleClient();
	
	if ($newFlag) {
		$result = $moduleClient->addBoundWordSet($name, $expressionVos);
	} else {
		$result = $moduleClient->updateBoundWordSet($name, $wordSetId, $expressionVos);
	}
	
} catch (Exception $e) {
	$result = array(
		'status' => 'ERROR',
		'message' => $e->getMessage(),
		'contents' => null
	);
}

echo json_encode($result);
?>
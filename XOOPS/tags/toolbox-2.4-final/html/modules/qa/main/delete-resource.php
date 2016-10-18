<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// Q&As.
// Copyright (C) 2010  CITY OF KYOTO
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

$result = array(
	'status' => 'OK',
	'message' => 'Success!',
	'contents' => array()
);

try {
	$name = $_POST['name'];
	
	$permissionManager = new QaPermissionManager();
	$permission = $permissionManager->getMyPermission($name);
	
	if ($permission < QaEnumPermission::SU) {
		throw new Exception('Permission denied.');
	}
	
	$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
	$resourceClient = $factory->createResourceClient();
	$resourceClient->deleteLanguageResource($name);
} catch (Exception $e) {
	$result['status'] = 'ERROR';
	$result['message'] = $e->getMessage();
}

echo json_encode($result);
?>
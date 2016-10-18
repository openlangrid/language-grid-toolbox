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
require_once dirname(__FILE__).'/../class/util/qa-category-util.php';
require_once dirname(__FILE__).'/../class/util/qa-record-util.php';
require_once dirname(__FILE__).'/../class/util/qa-resource-util.php';

$result = array(
	'status' => 'OK',
	'message' => 'Success!',
	'contents' => array()
);

try {
	$name = $_POST['name'];
	
	$permissionManager = new QaPermissionManager();
	$permission = $permissionManager->getMyPermission($name);
	
	if ($permission < QaEnumPermission::READ) {
		throw new Exception('Permission denied.');
	}

	$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
	$resourceClient = $factory->createResourceClient();
	$moduleClient = $factory->createModuleClient();
	
	$resourceResult = $resourceClient->getLanguageResource($name);
	$resource = $resourceResult['contents'];
	
	$categoryIds = array();
	$allCategories = $moduleClient->getAllCategories($resource->name);
	$result['contents']['categories'] = array();
	foreach ($allCategories['contents'] as $category) {
		$categoryIds[] = $category->id;
		$result['contents']['categories'][$category->id] = QaCategoryUtil::buildCategory($category);
	}
	
	$allRecordsResult = $moduleClient->getAllRecords($name);
	$allRecords = $allRecordsResult['contents'];
	$records = array();
	foreach ($allRecords as $record) {
		$records[] = QaRecordUtil::buildRecord($record);
	}

	$result['contents']['resource'] = QaResourceUtil::buildResource($resource, $records, $categoryIds);
} catch (Exception $e) {
	$result['status'] = 'ERROR';
	$result['message'] = $e->getMessage();
}
	
echo json_encode($result);
?>
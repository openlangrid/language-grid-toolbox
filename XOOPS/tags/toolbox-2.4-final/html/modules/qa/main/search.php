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
require_once dirname(__FILE__).'/../class/util/qa-record-util.php';

$result = array(
	'status' => 'OK',
	'message' => 'Success!',
	'contents' => array()
);
try {
	$keyword = $_POST['keyword'];
	$keywordLanguage = $_POST['keywordLanguage'];
	$matchingMethod = $_POST['matchingMethod'];
	
	$resources = (isset($_POST['resources']) ? $_POST['resources'] : array());
	
	$category = $_POST['category'];
	$categories = (isset($_POST['categories']) ? $_POST['categories'] : array());
	
	$order = $_POST['order'];
	$orderLanguage = $_POST['orderLanguage'];

	$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
	$resourceClient = $factory->createResourceClient();
	$moduleClient = $factory->createModuleClient();

	if ($category == 'all') {
		$categories = null;
	}

	$sortOrder = 'asec';
	$orderBy = $orderLanguage;
	if ($order == 'update') {
		$sortOrder = 'desc';
		$orderBy = 'updateDate';
		$resourcesResult = $resourceClient->getAllLanguageResources(__TOOLBOX_MODULE_NAME__);
		$hashMap = array();
		foreach ($resourcesResult['contents'] as $r) {
			$hashMap[$r->name] = $r->lastUpdate;
		}
		function rCompare($a, $b) {
			global $hashMap;
			if ($hashMap[$a] == $hashMap[$b]) {
				return 0;
			}
			return ($hashMap[$a] < $hashMap[$b]) ? 1 : -1;
		}
		usort($resources, 'rCompare');
	}
	
	
	$count = 0;
	foreach ($resources as $resourceName) {

		$recordsResult = $moduleClient->searchRecord($resourceName, $keyword, $keywordLanguage, $matchingMethod
		, $categories, 'qa', $sortOrder, $orderBy);

		$records = array();
		foreach ($recordsResult['contents'] as $record) {
			$records[] = QaRecordUtil::buildRecord($record);
		}

		$result['contents']['resources'][] = array(
			'name' => $resourceName,
			'records' => $records
		);
		$count += count($recordsResult['contents']);
	}
	$result['contents']['results'] = $count;
} catch (Exception $e) {
	$result['status'] = 'ERROR';
	$result['message'] = $e->getMessage();
}
echo json_encode($result);
?>
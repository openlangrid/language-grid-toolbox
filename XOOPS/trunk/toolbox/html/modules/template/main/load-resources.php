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
require_once dirname(__FILE__).'/../class/manager/bound-words-manager.php';
require_once dirname(__FILE__).'/../class/util/bound-word-set-util.php';
require_once dirname(__FILE__).'/../class/util/qa-category-util.php';
require_once dirname(__FILE__).'/../class/util/qa-resource-util.php';
require_once dirname(__FILE__).'/../class/util/bound-word-util.php';

$result = array(
	'status' => 'OK',
	'message' => 'Success!',
	'contents' => array()
);

try {
	$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
	$resourceClient = $factory->createResourceClient();
	$moduleClient = $factory->createModuleClient();
	
	$resourcesResult = $resourceClient->getAllLanguageResources(__TOOLBOX_MODULE_NAME__);
	$globalCategories = array();
	$globalWordSets = array();
	$globalWords = array();
	$result['contents']['resources'] = array();
	
	foreach ($resourcesResult['contents'] as $resource) {
		$categoryIds = array();
		$allCategories = $moduleClient->getAllCategories($resource->name);
		foreach ($allCategories['contents'] as $category) {
			$categoryIds[] = $category->id;
			$globalCategories[$category->id] = QaCategoryUtil::buildCategory($category);
		}

		$allWordSets = $moduleClient->getAllBoundWordSets($resource->name);
		$wordSetIds = array();
		foreach ($allWordSets['contents'] as $wordSet) {
			$wordSetIds[] = $wordSet->id;
			$globalWordSets[$wordSet->id] = BoundWordSetUtil::buildBoundWordSet($wordSet);
		}
	
		// add words
		$manager = new BoundWordsManager();
		$allWords = $manager->getAllBoundWordsByResourceName($resource->name);
		foreach ($allWords as $word) {
			$globalWords[$word->id] = BoundWordUtil::buildBoundWord($word);
		}
		
		$result['contents']['resources'][] = QaResourceUtil::buildResource($resource, array(), $categoryIds, $wordSetIds);
	}
	
	usort($result['contents']['resources'], array('QaResourceUtil', 'sortByNameAsc'));
	$result['contents']['categories'] = $globalCategories;
	$result['contents']['wordSets'] = $globalWordSets;
	$result['contents']['words'] = $globalWords;
} catch (Exception $e) {
	$result['status'] = 'ERROR';
	$result['message'] = $e->getMessage();
}

echo json_encode($result);
?>
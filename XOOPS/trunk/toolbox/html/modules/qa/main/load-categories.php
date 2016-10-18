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

$result = array(
	'status' => 'OK',
	'message' => 'Success!',
	'contents' => array()
);

try {
	$name = $_POST['name'];
	
	$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
	$moduleClient = $factory->createModuleClient();

	$allCategoriesResult = $moduleClient->getAllCategories($name);
	$result['contents']['categories'] = array();
	foreach ($allCategoriesResult['contents'] as $category) {
		$result['contents']['categories'][$category->id] = array(
			'language' => $category->language,
			'count' => $category->qCount
		);
		foreach ($category->name as $exp) {
			$result['contents']['categories'][$category->id][$exp->language] = $exp->expression;
		}
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
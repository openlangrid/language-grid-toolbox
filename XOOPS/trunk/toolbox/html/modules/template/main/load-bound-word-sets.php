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
require_once dirname(__FILE__).'/../class/util/qa-record-util.php';

$result = array(
	'status' => 'OK',
	'message' => 'Success!',
	'contents' => array()
);

try {
	$name = $_POST['name'];
	
	$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
	$moduleClient = $factory->createModuleClient();

	$allBoundWordSetsResult = $moduleClient->getAllBoundWordSets($name);
	$result['contents']['boundWordSets'] = array();
	foreach ($allBoundWordSetsResult['contents'] as $boundWordSet) {
		$result['contents']['boundWordSets'][$boundWordSet->id] = array(
			'count' => $boundWordSet->recordCount
		);
		$result['contents']['boundWordSets'][$boundWordSet->id] = QaRecordUtil::toolboxVos2expressions($boundWordSet->name);
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
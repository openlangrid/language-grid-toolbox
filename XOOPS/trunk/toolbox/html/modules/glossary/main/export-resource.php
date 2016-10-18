<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// glossaries.
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
 * $Id: export-resource.php 3629 2010-04-23 04:31:14Z yoshimura $
 */
require_once dirname(__FILE__).'/../class/factory/client-factory.php';

require_once dirname(__FILE__).'/../class/manager/qa-permission-manager.php';

$name = $_GET['name'];

$permissionManager = new QaPermissionManager();
$permission = $permissionManager->getMyPermission($name);

if ($permission < QaEnumPermission::READ) {
	die();
}

$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
$resourceClient = $factory->createResourceClient();
$moduleClient = $factory->createModuleClient();

$resourceResult = $resourceClient->getLanguageResource($name);
$resource = $resourceResult['contents'];

$keys = array(
	'id', 'type', 'definitions', 'categories'
);

foreach ($resource->languages as $language) {
	$keys[] = $language;
}

$recordsResult = $moduleClient->getAllRecords($name);
$records = $recordsResult['contents'];

$cells = array();
$i = 1;
foreach ($records as $record) {
	$answerIds = array();
	for ($j = $i, $length = $i + count($record->definition); $j < $length; $j++) {
		$answerIds[] = $j + 1;
	}
	$cells[$i] = array(
		'id' => $i,
		'type' => 'term',
		'definitions' => implode(',', $answerIds),
		'categories' => implode(',', $record->categoryIds)
	);
	foreach ($record->term as $question) {
		$cells[$i][$question->language] = $question->expression;
	}
	foreach ($record->definition as $answerExps) {
		$i++;
		$cells[$i] = array(
			'id' => $i,
			'type' => 'definition',
			'categories' => ''
		);
		foreach ($answerExps->expression as $answer) {
			$cells[$i][$answer->language] = $answer->expression;
		}
	}
	$i++;
}
$categoriesResult = $moduleClient->getAllCategories($name);
$categories = $categoriesResult['contents'];
$categoriesHash = array();
foreach ($categories as $category) {
	$cells[$i] = array(
		'id' => $i,
		'type' => 'category',
		'definitions' => '',
		'categories' => ''
	);
	foreach ($category->name as $categoryName) {
		$cells[$i][$categoryName->language] = $categoryName->expression;
	}
	$categoriesHash[$category->id] = $i;
	$i++;
}

// Create TSV data
$tsv = array();

$hrow = array('id', 'type', 'definitions', 'categories');
foreach ($resource->languages as $key => $language) {
	$hrow[] = $language;
}
$tsv[] = implode("\t", $hrow) . PHP_EOL;


foreach ($cells as $rowNumber => $row) {
	$brow = array();
	$categoryIds = array();
	if ($row['categories'] != '') {
		foreach (explode(',', $row['categories']) as $cId) {
			$categoryIds[] = $categoriesHash[trim($cId)];
		}
	}
	$brow[] = $row['id'];
	$brow[] = $row['type'];
	$brow[] = isset($row['definitions']) ? $row['definitions'] : '';
	$brow[] = implode(',', $categoryIds);
	foreach ($resource->languages as $key => $language) {
		$v = isset($row[$language]) ? $row[$language] : '';
		$v = preg_replace('/\r\n|\r|\n/iu', '\\n', $v);
		$v = preg_replace('/\t/iu', '\\t', $v);
		$brow[] = $v;
	}
	$tsv[] = implode("\t", $brow) . PHP_EOL;
}

$utf16LEcontent = chr(255).chr(254).mb_convert_encoding(implode('', $tsv), "UTF-16LE", "UTF-8");
header('Content-Type: text/plain');
header('Content-Disposition: attachment;filename="'.str_replace(' ', '_', $name).'.txt"');
header('Cache-Control: max-age=0');
echo $utf16LEcontent;
die();
?>
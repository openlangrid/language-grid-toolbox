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
 * $Id: import-resource.php 5485 2011-02-25 08:47:19Z uehara $
 */
setlocale(LC_ALL,'en_US.UTF-8');
require_once dirname(__FILE__).'/../class/factory/client-factory.php';

require_once dirname(__FILE__).'/../class/manager/qa-permission-manager.php';
require_once dirname(__FILE__).'/../class/manager/qa-resource-manager.php';
require_once dirname(__FILE__).'/../class/util/qa-record-util.php';

$permissionManager = new QaPermissionManager();
$resourceManager = new QaResourceManager();

$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
$resourceClient = $factory->createResourceClient();
$moduleClient = $factory->createModuleClient();

$name = $_POST['name'];
$read = $_POST['read'];
$edit = $_POST['edit'];

try {
	if ($resourceManager->isResourceExist($name)) {
		throw new Exception(_MI_QA_ERROR_RESOURCE_NAME_ALREADY_IN_USE);
	}
	if (!isFileValid()) {
		throw new Exception(_MI_QA_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s line.</pre>', __LINE__));
	}
	$fileContents = openFileAndValidEncode();
	if ($fileContents === false) {
		throw new Exception(_MI_QA_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s line.</pre>', __LINE__));
	}
	$firstRow = array_shift($fileContents);
	if (!isFirstRowValid($firstRow)) {
		throw new Exception(_MI_QA_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s line.</pre>', __LINE__));
	}
	try {
		$readPermission = $permissionManager->createToolboxVO($read);
		$editPermission = $permissionManager->createToolboxVO($edit);

		$languagesBindColNumber = getLanguages($firstRow);
		$languages = array_values($languagesBindColNumber);
		$result = $resourceClient->createLanguageResource($name, __TOOLBOX_MODULE_NAME__, $languages, $readPermission, $editPermission);
		$cells = convertTsv2Matrix($fileContents, $languagesBindColNumber);
		$categoriesHash = array();// excelId => realId
		foreach ($cells as $row) {
			if ($row['type'] != 'category') {
				continue;
			}
			$exps = array();
			foreach ($languages as $language) {
				$exp = new ToolboxVO_Resource_Expression();
				$exp->language = $language;
				$exp->expression = $row[$language];
				$exps[] = $exp;
			}
			$categoryResult = $moduleClient->addCategory($name, $exps, $languages[0]);
			$categoriesHash[$row['id']] = $categoryResult['contents']->id;
		}
		foreach ($cells as $row) {
			if ($row['type'] != 'term') {
				continue;
			}
			$question = array();
			foreach ($languages as $language) {
				$exp = new ToolboxVO_Resource_Expression();
				$exp->language = $language;
				$exp->expression = $row[$language];
				$question[] = $exp;
			}
			$answers = array();
			foreach ($row['definitions'] as $answerId) {
				if ($answerId == '') {
					continue;
				}
				$answerRow = $cells[$answerId];
				if (!$answerRow) {
					throw new Exception(_MI_QA_ERROR_INVALID_FILE_FORMAT);
				}
				if ($answerRow['type'] != 'definition') {
					throw new Exception(_MI_QA_ERROR_INVALID_FILE_FORMAT);
				}
				$answer = new ToolboxVO_Glossary_Definition();
				foreach ($languages as $language) {
					$exp = new ToolboxVO_Resource_Expression();
					$exp->language = $language;
					$exp->expression = $answerRow[$language];
					$answer->expression[] = $exp;
				}
				$answers[] = $answer;
			}
			$categoryIds = array();
			foreach ($row['categories'] as $categoryId) {
				if ($categoryId == '') {
					continue;
				}
				$categoryRow = $cells[$categoryId];
				if (!$categoryRow) {
					throw new Exception(_MI_QA_ERROR_INVALID_FILE_FORMAT);
				}
				if ($categoryRow['type'] != 'category') {
					throw new Exception(_MI_QA_ERROR_INVALID_FILE_FORMAT);
				}
				$categoryIds[] = $categoriesHash[$categoryId];
			}
			$moduleClient->addRecord($name, $question, $answers, $categoryIds);
		}
		$html = getSuccessHtml($name);
	} catch (Exception $e) {
		$resourceClient->deleteLanguageResource($name);
		throw new Exception($e->getMessage());
	}
} catch (Exception $e) {
	$html = gutErrorHtml($e->getMessage());
}
echo $html;

function getSuccessHtml($name) {
	$html = <<<EOH
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Toolbox</title>
	</head>
	<body>
	<script language="JavaScript" type="text/javascript">
	with(window.parent) {
		document.fire('import:success');
		Global.location = '{$name}';
		document.fire('state:edit');
	}
	</script>
	</body>
	</html>
EOH;
	return $html;
}

function gutErrorHtml($message) {
	$html = <<<EOH
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Toolbox</title>
	</head>
	<body>
	<script language="JavaScript" type="text/javascript">
	with(window.parent) {
		document.fire('import:failure', {
			message : '{$message}'
		});
	}
	</script>
	</body>
	</html>
EOH;
	return $html;
}


function convertTsv2Matrix($fileContents, $languages) {
	$cells = array();

	$validcolnum = 4 + count($languages);

	$i = 2;
	foreach ($fileContents as $cols) {
//		if (mb_strlen($line) == 0) {
//			continue;
//		}
		if ($cols == null || is_array($cols) === false || count($cols) == 0) {
			continue;
		}

		//$cols = split("\t", $line);

		if (count($cols) !== $validcolnum) {
			throw new Exception(_MI_QA_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s php-line.[tsv-data-line-no is %s]</pre>', __LINE__, $i));
		}

		$id = $cols[0];
		if ($id == '') {
			throw new Exception(_MI_QA_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s php-line.[tsv-data-line-no is %s]</pre>', __LINE__, $i));
		}
		if (isset($cells[$id])) {
			throw new Exception(_MI_QA_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s php-line.[tsv-data-line-no is %s]</pre>', __LINE__, $i));
		}

		$cells[$id] = array(
			'id' => $id,
			'type' => strtolower($cols[1]),
			'definitions' => preg_split('/, ?/', $cols[2]),
			'categories' => preg_split('/, ?/', $cols[3])
		);
		foreach ($languages as $key => $language) {
			$v = $cols[$key];
			$v = preg_replace('/\\\\t/iu', chr(0x09), $v);
			$v = preg_replace('/\\\\n/iu', PHP_EOL, $v);
			$cells[$id][$language] = $v;
		}
		$i++;
	}
	return $cells;
}

function getLanguages($firstRow) {
//	$cols = split("\t", $firstRow);
	$cols = $firstRow;
	$languages = array();
	require XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';
	for ($i = 4; $i < count($cols); $i++) {
		$langTag = $cols[$i];
		if (array_key_exists($langTag, $LANGRID_LANGUAGE_ARRAY)) {
			$languages[$i] = $langTag;
		}
	}
	return $languages;

}

function isFileValid() {
	if ($_FILES['file']['error'] != 0 || $_FILES['file']['size'] == 0) {
		return false;
	}

	$whiteList = array(
			'text/plain',
			'application/octet-stream'
	);
	if (!in_array($_FILES['file']['type'], $whiteList)) {
		return false;
	}

	return true;
}

function openFileAndValidEncode() {
	$tmpFileLines = file($_FILES['file']['tmp_name']);
	$code = mb_detect_encoding($tmpFileLines[0]);

	if (ord($tmpFileLines[0]{0}) == 255 && ord($tmpFileLines[0]{1}) == 254) {
		$code = "UTF-16LE";
	} else if (ord($tmpFileLines[0]{0}) == 254 && ord($tmpFileLines[0]{1}) == 255) {
		$code = "UTF-16BE";
	} else {
		return false;
	}
	$tmpFileContent = '';
	foreach($tmpFileLines as $aline) {
		$tmpFileContent .= $aline;
	}

	$utf8content = mb_convert_encoding($tmpFileContent, 'UTF-8', $code);
	if (ord($utf8content{0}) == 0xef && ord($utf8content{1}) == 0xbb && ord($utf8content{2}) == 0xbf) {
		$utf8content = substr($utf8content, 3);
	}

//	$lines = split("\r\n|\r|\n", $utf8content);
	$lines = str_gettsv($utf8content, chr(0x09));	// chr(0x09) == \t

	return $lines;
}

function isFirstRowValid($firstRow) {
//	$cols = split("\t", $firstRow);
	$cols = $firstRow;
	if (count($cols) < 6) {		// head 4 item + 2 languages
		return false;
	}
	if ('id' != strtolower($cols[0])) {
		return false;
	}
	if ('type' != strtolower($cols[1])) {
		return false;
	}
	if ('definitions' != strtolower($cols[2])) {
		return false;
	}
	if ('categories' != strtolower($cols[3])) {
		return false;
	}
	return true;
}

function str_gettsv($input, $delimiter='\t', $enclosure='"', $escape='\\') {
	$temp = fopen('php://memory', 'rw');
	fwrite($temp, $input);
	fseek($temp, 0);
	$sheet = array();

	while (($cells = fgetcsv($temp, 10240, $delimiter, $enclosure)) !== false) {
		$sheet[] = $cells;
	}

	fclose($temp);
	return $sheet;
}
?>
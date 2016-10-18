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

/* $Id: import-resource.php 5551 2011-03-15 08:23:17Z mtanaka $ */
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
		throw new Exception(_MI_TEMPLATE_ERROR_RESOURCE_NAME_ALREADY_IN_USE);
	}
	if (!isFileValid()) {
		throw new Exception(_MI_TEMPLATE_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s line.</pre>', __LINE__));
	}
	$fileContents = openFileAndValidEncode();
	if ($fileContents === false) {
		throw new Exception(_MI_TEMPLATE_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s line.</pre>', __LINE__));
	}
	$firstRow = array_shift($fileContents);
	if (!isFirstRowValid($firstRow)) {
		throw new Exception(_MI_TEMPLATE_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s line.</pre>', __LINE__));
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
			$categoryResult = $moduleClient->addCategory($name, $exps);
			$categoriesHash[$row['id']] = $categoryResult['contents']->id;
		}

		$wordSetHash = array();// tsvId => dbId
		foreach ($cells as $row) {
			if ($row['type'] != 'parameter') {
				continue;
			}
			$exps = array();
			foreach ($languages as $language) {
				$exp = new ToolboxVO_Resource_Expression();
				$exp->language = $language;
				$exp->expression = $row[$language];
				$exps[] = $exp;
			}
			$wordSetResult = $moduleClient->addBoundWordSet($name, $exps);
			$wordSetHash[$row['id']] = $wordSetResult['contents']->id;
		}

		foreach ($cells as $row) {
			if ($row['type'] != 'word') {
				continue;
			}
			$exps = array();
			foreach ($languages as $language) {
				$exp = new ToolboxVO_Resource_Expression();
				$exp->language = $language;
				$exp->expression = $row[$language];
				$exps[] = $exp;
			}
			$wordResult = $moduleClient->addBoundWord($name, $wordSetHash[$row['id']], $exps);
		}

		foreach ($cells as $row) {
			if ($row['type'] != 'parallel_text') {
				continue;
			}
			$exps = array();
			$wordSetIds = array();
			$categoryIds = array();
			foreach ($languages as $language) {
				$exp = new ToolboxVO_Resource_Expression();
				$exp->language = $language;
				$text = $row[$language];
				$exp->expression = preg_replace('@\<param id="([0-9]+)"[^\/]*\/\>@', '[$1]', $text);
				$exps[] = $exp;

				$wordSetIds = array_merge($wordSetIds, getWordSetIds($row[$language]));
			}
			$wordSetIds = array_unique($wordSetIds);
			$_wordSetIds = array();
			foreach ($wordSetIds as $id) {
				$_wordSetIds[] = isset($wordSetHash[$id]) ? $wordSetHash[$id] : $id;
			}

			foreach (explode(',', $row['cat']) as $catId) {
				if ($catId != '' && isset($categoriesHash[$catId])) {
					$categoryIds[] = $categoriesHash[$catId];
				}
			}
			$recordResult = $moduleClient->addRecord($name, $exps, $_wordSetIds, $categoryIds);
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

	$validcolnum = 3 + count($languages);

	$i = 0;
	foreach ($fileContents as $cols) {
//		if (mb_strlen($line) == 0) {
//			continue;
//		}
		if ($cols == null || is_array($cols) === false || count($cols) == 0) {
			continue;
		}

		//$cols = split("\t", $line);

		if (count($cols) !== $validcolnum) {
			throw new Exception(_MI_TEMPLATE_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s php-line. tsv-data-line-no=%s, [count($cols)='.count($cols).', $validcolnum='.$validcolnum.']</pre>', __LINE__, $i+2));
		}

		$id = $cols[0];
		if ($id == '') {
			throw new Exception(_MI_TEMPLATE_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s php-line. tsv-data-line-no=%s, </pre>', __LINE__, $i+2));
		}
//		if (isset($cells[$id])) {
//			throw new Exception(_MI_TEMPLATE_ERROR_INVALID_FILE_FORMAT.sprintf('<pre style="display:none;">Error message output by %s line.</pre>', __LINE__));
//		}

		$cells[$i] = array(
			'id' => $id,
			'type' => strtolower($cols[1]),
			'cat' => $cols[2]
		);
		foreach ($languages as $key => $language) {
			$v = $cols[$key];
			$v = preg_replace('/\\\\t/iu', chr(0x09), $v);
			$v = preg_replace('/\\\\n/iu', PHP_EOL, $v);
			$cells[$i][$language] = $v;
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
	for ($i = 3; $i < count($cols); $i++) {
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

	$controlChars = '/[\x7F\x00-\x08\x0B\x0C\x0E-\x1F]/'; // [:control:] cannot be used for TSV.

//	$lines = split("\r\n|\r|\n", $utf8content);
	$lines = str_gettsv(preg_replace($controlChars, '', $utf8content),
						chr(0x09));	// chr(0x09) == \t
	return $lines;
}

function isFirstRowValid($firstRow) {
//	$cols = split("\t", $firstRow);
//	var_dump($cols);
	$cols = $firstRow;
	if (count($cols) < 5) {		// head 4 item + 2 languages
		return false;
	}
	if ('id' != strtolower($cols[0])) {
		return false;
	}
	if ('type' != strtolower($cols[1])) {
		return false;
	}
	if ('cat/par' != strtolower($cols[2])) {
		return false;
	}
	return true;
}

function getWordSetIds($text) {
	$return = array();
	if (preg_match_all('@\<param id="([0-9]+)" (type|domains)="([^"]+)" \/\>@ui', $text, $matches, PREG_SET_ORDER)) {
		foreach ($matches as $match) {
			$setId = 0;
			if ($match[2] == 'type') {
				$setId = _convertTypeName2WordSetId($match[3]);
			} else {
				$setId = $match[3];
			}
			$return[$match[1]] = $setId;
		}
	}
	ksort($return);
	return $return;
}

function _convertTypeName2WordSetId($type) {
	$setId = 0;
	switch ($type) {
		case 'text'          : $setId = 1; break;
		case 'month'         : $setId = 2; break;
		case 'day_of_month'  : $setId = 3; break;
		case 'hour'          : $setId = 4; break;
		case 'minute'        : $setId = 5; break;
		case 'float'         : $setId = 6; break;
		default:
			break;
	}
	return $setId;
}

function str_gettsv($input, $delimiter='\t', $enclosure='"', $escape='\\') {
	$temp = fopen('php://memory', 'rw');
	fwrite($temp, $input);
	fseek($temp, 0);
	$sheet = array();

	while (($cells = fgetcsv($temp, 0, $delimiter, $enclosure)) !== false) {
		$sheet[] = $cells;
	}

	fclose($temp);
	return $sheet;
}

?>
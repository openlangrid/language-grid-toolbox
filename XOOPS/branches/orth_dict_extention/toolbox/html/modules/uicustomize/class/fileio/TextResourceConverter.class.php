<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox.
//
// Copyright (C) 2010  NICT Language Grid Project
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
/* $Id: TextResourceConverter.class.php 5012 2011-01-07 11:25:14Z yoshimura $ */
/**
 * ファイル共有上のテキストリソースファイルをXoops言語リソースファイルに置き換える
 */

require_once(dirname(__FILE__).'/FileIO.class.php');
require_once(XOOPS_ROOT_PATH.'/class/template.php');

require_once(XOOPS_ROOT_PATH.'/api/class/client/FileSharingClient.class.php');
require_once(dirname(__FILE__).'/../manager/UICustomizeTextResourceFilesManager.php');

class TextResourceConverter extends FileIO {

	protected $mClient = null;

    function TextResourceConverter() {
    	parent::FileIO();
    	$this->mClient = new FileSharingClient();
    }

	function modifyResource($moduleId, $fileId, $language) {
		$dataArray = $this->_loadFile($fileId);
		$newContents = $this->_createOutput($moduleId, $dataArray, $language);

		$modifyPath = $this->_getModuleResourcePath($moduleId, $language);

		FileIO::overwriteFile($modifyPath, $newContents);

		$manager = new UICustomizeTextResourceFilesManager();
		$manager->setModuleInfo($moduleId, $language, $fileId, $this->fileName);

		return true;
	}

	function createTemplate($moduleId) {
		$language = 'en';

		$path = $this->_getModuleResourcePath($moduleId, $language);
//		$data = FileIO::openFile($path);
		$data = file($path, FILE_IGNORE_NEW_LINES);

        // Theme user_xxxxx.php
        if ($moduleId == UICustomizeTextResourceFilesManager::THEME_MID_ID) {
            $userdatafile = XOOPS_ROOT_PATH.'/themes/default/language/user_english.php';
            if (file_exists($userdatafile)) {
                $userdata = file($userdatafile, FILE_IGNORE_NEW_LINES);
                if ($userdata) {
                    $data = array_merge($data, $userdata);
                }
            }
        }

		if ($data) {
			$content = $this->_createResource($data);
			$utf16LEcontent = chr(255).chr(254).mb_convert_encoding($content, "UTF-16LE", "UTF-8");
			return $utf16LEcontent;
		}
		return false;
	}

	private function _loadFile($fileId) {
		$f = $this->mClient->getFile($fileId);
		if ($f == null || $f['status'] != 'OK') {
			return null;
		}

		$contents = FileIO::openFile($f['contents']->path);
		$this->fileName = $f['contents']->name;

		$lines = split("\r\n|\r|\n", $contents);
		if (!$this->_validHeaderLine($lines)) {
			return null;
		}
		$validColNums = null;
		$dataArray = array();
		foreach($lines as $aline){
			if (mb_strlen($aline) == 0) {
				continue;
			}
			$rowArray = explode("\t", $aline);

			if(!$validColNums){
				$validColNums = array();
				for($i=0; $i<count($rowArray); $i++){
					if(mb_strlen($rowArray[$i])>0){
						$validColNums[] = $i;
					}
				}
			}

			$tableRow = array();
			foreach($validColNums as $colNum){
				$tableRow[] = $rowArray[$colNum] ? $rowArray[$colNum] : "";
			}

			$dataArray[] = $tableRow;
		}
		return $dataArray;
	}

	private function _validHeaderLine($lines) {
		if ($lines == null || is_array($lines) == false || count($lines) < 1) {
			throw new TextResourceFileHeaderInvalidFormatException('(file is empty.)');
		}
		$head = explode("\t", $lines[0]);
		if (count($head) != 2 || $head[0] != 'define' || $head[1] != 'resource') {
			throw new TextResourceFileHeaderInvalidFormatException('(invalid head line [dump --> '.print_r($head, true).'])');
		}
		return true;
	}

	private function _createOutput($moduleId, $dataArray, $language) {
		$text = '<?php'.PHP_EOL;
		$text .= '// create by uicustomize module at '.date(DateTime::ATOM) . PHP_EOL.PHP_EOL;
//		$text .= $this->_fileHeader($moduleId);

		$headLine = array_flip(array_shift($dataArray));

//		if (!isset($headLine[$language])) {
//			return null;
//		}
//		$lgIdx = $headLine[$language];
		$lgIdx = 1;		// ２列目固定

		foreach ($dataArray as $line) {
			$text .= sprintf('define(\'%s\', \'%s\');', $line[0], $line[$lgIdx]);
			$text .= PHP_EOL;
		}

		$text .= '?>'.PHP_EOL;
		return $text;
	}

	private function _createResource($definesData) {
		$data = array();
		$data[] = "define\tresource";

		foreach ($definesData as $line) {
			$a = $this->_picup($line);
			if ($a) {
				$data[] = $a['key']."\t".$a['val'];
			}
		}
		$data[] = '';
		return implode(PHP_EOL, $data);
	}

	private function _picup($line) {
//		preg_match('/^define\\(\'([^\']+?)\', \'([^\']+?)\'\\)/', $line, $matches);
		preg_match('/^define\\(\\s*\'([^\']+?)\'\\s*,\\s*\'([^\']+?)\'\\s*\\)/', $line, $matches);
		if (count($matches) !== 3) {
			return false;
		}
		return array(
			'key' => $matches[1],
			'val' => $matches[2]
		);
	}

	private function _getModuleResourcePath($moduleId, $language) {
		if (UICustomizeTextResourceFilesManager::THEME_MID_ID == $moduleId) {
			$filename = $language;
			$list = UICustomizeTextResourceFilesManager::getAdhocDirnameLists();
			if (array_key_exists($language, $list)) {
				$filename = $list[$language];
			}
			return sprintf(XOOPS_ROOT_PATH.'/themes/default/language/%s.php', $filename);
		}
		$criteria = new CriteriaCompo;
		$criteria->setStart(0);
		$criteria->setLimit(0);
		$criteria->add(new Criteria('mid', $moduleId));

		$modules = array();

		$moduleHandler = xoops_gethandler('module');
		$modObjects = $moduleHandler->getObjects($criteria);

		$dirname = '';

		if ($modObjects) {
			$m = $modObjects[0];
			$dirname = $m->get('dirname');
		}

		if ($dirname == '') {
			return null;
		}

		$codes = array_flip(explode(',', CUBE_UTILS_ML_LANGS));
		$dirs = explode(',', CUBE_UTILS_ML_LANGNAMES);
		$lang = $dirs[$codes[$language]];

		$path = sprintf(XOOPS_ROOT_PATH.'/modules/%s/language/%s/', $dirname, $lang);

		if (is_dir($path) === false) {
			mkdir($path);
		}

		return $path . 'main.php';
	}

//	private function _fileHeader($mid) {
//		switch ($mid) {
//			case UICustomizeTextResourceFilesManager::THEME_MID_ID:
//				return 'require_once dirname(__FILE__).\'/user_\'.basename(__FILE__);'.PHP_EOL;
//				break;
//			default:
//				break;
//		}
//		return '';
//	}

}

class TextResourceFileHeaderInvalidFormatException extends Exception {
}
?>
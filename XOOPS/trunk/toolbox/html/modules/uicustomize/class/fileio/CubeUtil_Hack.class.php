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
/**
 * CubeUtilsモジュールのconf_ml.phpファイルを直接編集することでToolboxUIからのUIサポート言語の追加削除を行う。
 */

require_once(dirname(__FILE__).'/FileIO.class.php');
require_once(XOOPS_ROOT_PATH.'/class/template.php');

class CubeUtil_Hack extends FileIO {

    function CubeUtil_Hack() {
    	parent::FileIO();
    	$this->conf_ml_filepath = XOOPS_ROOT_PATH . '/modules/cubeUtils/include';
    	$this->conf_ml_filename = '/conf_ml.php';
    	$this->conf_ml_backup = '/conf_ml.php.'.time();
    }

    function getUISupportLanguages() {
    	$manager = new UICustomizeSupportLanguageManager();
    	$a = $manager->getAll();
		$codes = array();
    	foreach ($a as $o) {
    		$codes[] = $o->get('language_code');
    	}
    	return $codes;
    }

	/**
	 *
	 */
    function addUISupportLanguage($code, $name, $dir) {

		if ($this->validLanguageDirectory($dir) !== true) {
			return false;
		}

    	$manager = new UICustomizeSupportLanguageManager();
    	if (!$manager->add($code, $name, $dir)) {
    		return false;
    	}

		$this->hackCubeUtils();
    }

    function removeUISupportLanguage($code) {
    	$manager = new UICustomizeSupportLanguageManager();
    	$manager->delete($code);

		$this->hackCubeUtils();
    }

    private function hackCubeUtils() {
    	$manager = new UICustomizeSupportLanguageManager();

    	$codes = array();
    	$names = array();
    	$dirs = array();
    	$images = array();
		$objects = $manager->getAll();
		foreach ($objects as $o) {
			$codes[] = $o->get('language_code');
			$names[] = $o->get('language_name');
			$dirs[] = $o->get('language_dir');
			$images[] = 'non';
		}

		$xoopsTpl = new XoopsTpl();
		$xoopsTpl->assign('languageCodes', implode(',', $codes));
		$xoopsTpl->assign('languageNames', implode(',', $names));
		$xoopsTpl->assign('languageDirs', implode(',', $dirs));
		$xoopsTpl->assign('languageImages', implode(',', $images));
    	$contents = $xoopsTpl->fetch('db:uicustomize_support_languages_conf_ml.html');

		$filePath = $this->conf_ml_filepath.$this->conf_ml_filename;
		FileIO::overwriteFile($filePath, $contents);
    }

	/**
	 *
	 */
    private function validLanguageDirectory($dir) {
    	$path = XOOPS_ROOT_PATH.'/language/'.$dir;
    	if (is_dir($path) === false) {
    		FileIO::copyDirectory(XOOPS_ROOT_PATH.'/language/english', $path);
    	}
    	return true;
    }
}

/**
 * UI support language manager.
 */
require_once(XOOPS_ROOT_PATH.'/api/class/manager/Toolbox_AbstractManager.class.php');
class UICustomizeSupportLanguageManager extends Toolbox_AbstractManager {

	private $mHandler = null;

	function UICustomizeSupportLanguageManager() {
		parent::__construct();
		$this->mHandler = new UICustomizeSupportLanguagesHandler($this->db);
	}

	function getAll() {
		$criteria = new CriteriaCompo();
		return $this->mHandler->getObjects($criteria, 0, 999);
	}

	function add($languageCode, $languageName, $languageDir) {
		$object = $this->mHandler->create(true);
		$object->set('language_code', $languageCode);
		$object->set('language_name', $languageName);
		$object->set('language_dir', $languageDir);
		$object->set('creation_time', time());
		$object->set('creation_uid', $this->uid);
		return $this->mHandler->insert($object, true);
	}

	function delete($languageCode) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('language_code', $languageCode));
		$objects = $this->mHandler->getObjects($criteria);
		foreach ($objects as $o) {
			$this->mHandler->delete($o, true);
		}
		return true;
	}
}

/**
 * uicustomize_support_languages one record object.
 */
class UICustomizeSupportLanguageObject extends XoopsSimpleObject {

	function UICustomizeSupportLanguageObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('language_code', XOBJ_DTYPE_STRING, 10, false);
		$this->initVar('language_name', XOBJ_DTYPE_STRING, 30, false);
		$this->initVar('language_dir', XOBJ_DTYPE_STRING, 30, false);
		$this->initVar('creation_time', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('creation_uid', XOBJ_DTYPE_INT, 0, false);
	}
}

/**
 * uicustomize_support_languages table handler.
 */
class UICustomizeSupportLanguagesHandler extends XoopsObjectGenericHandler {

	var $mTable = "uicustomize_support_ui_languages";
	var $mPrimary = "id";
	var $mClass = "UICustomizeSupportLanguageObject";

}
?>
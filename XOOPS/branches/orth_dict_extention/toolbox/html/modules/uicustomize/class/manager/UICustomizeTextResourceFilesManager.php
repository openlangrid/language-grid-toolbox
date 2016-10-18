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
/* $Id: UICustomizeTextResourceFilesManager.php 3879 2010-08-02 10:15:16Z yoshimura $ */

require_once(XOOPS_ROOT_PATH.'/api/class/manager/Toolbox_AbstractManager.class.php');
class UICustomizeTextResourceFilesManager extends Toolbox_AbstractManager {

	const THEME_MID_ID = '-1';

	private $mHandler = null;

	private $ignoreModules = array(
		'altsys', 'cubeUtils', 'hdLegacy', 'legacy', 'legacyRender', 'protector', 'stdCache', 'user'
	);

	function UICustomizeTextResourceFilesManager() {
		parent::__construct();
		$this->mHandler = new UICustomizeTextResourceFilesHandler($this->db);
	}

	function getModules($language) {
		$a = $this->getBaseModules($language);
		$b = $this->mHandler->getModuleTextResourceByLanguage($language);

		$modules = array();

		foreach ($a as $c) {
			if (isset($b[$c['mid']])) {
				$d = $b[$c['mid']];
				$d['name'] = $c['name'];
				$modules[] = $d;
			} else {
				$modules[] = $c;
			}
		}
		ksort($modules, SORT_NUMERIC);
		return $modules;
	}

	function setModuleInfo($moduleId, $language, $fileId, $fileName) {
		$been = null;

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('module_id', $moduleId));
		$criteria->add(new Criteria('language', $language));

		$objects = $this->mHandler->getObjects($criteria);
		if ($objects == null || count($objects) == 0) {
			$been = $this->mHandler->create(true);
			$been->set('module_id', $moduleId);
			$been->set('language', $language);
		} else {
			$been = $objects[0];
		}

		$been->set('shared_file_id', $fileId);
		$been->set('file_name', $fileName);
		$been->set('creation_time', time());
//		$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;

		$root =& XCube_Root::getSingleton();
		$userId = 0;
		if ($root->mContext->mXoopsUser) {
			$userId = $root->mContext->mXoopsUser->get('uid');
		}

		$been->set('creation_uid', $userId);

		return $this->mHandler->insert($been, true);
	}

	private function getBaseModules($language) {
		$criteria = new CriteriaCompo;
		$criteria->setStart(0);
		$criteria->setLimit(0);

		$modules = array();

		$moduleHandler = xoops_gethandler('module');
		$modulesInfo = $moduleHandler->getObjects($criteria);

		foreach ($modulesInfo as $info) {
			if (!in_array($info->get('dirname'), $this->ignoreModules)) {
				$modules[$info->get('mid')] = array(
					'mid' => $info->get('mid'),
					//'name' => cubeUtil_MLConvert($info->get('name')),
					'name' => $this->languageFilter($info->get('name'), $language),
					'file' => '',
					'shared_file_id' => '',
					'user' => '',
					'lastUpdate' => ''
				);
			}
		}

		// テーマ用（モジュールじゃないけどモジュールの１個として扱う）
		$modules[UICustomizeTextResourceFilesManager::THEME_MID_ID] = array(
			'mid' => UICustomizeTextResourceFilesManager::THEME_MID_ID,
			'name' => $this->languageFilter('[Theme]', $language),
			'file' => '',
			'shared_file_id' => '',
			'user' => '',
			'lastUpdate' => ''
		);

		return $modules;
	}

	private function languageFilter($str, $languageCode = null) {
		if (empty($languageCode)) {
			$tag = preg_replace('/[^a-zA-Z-]+/u', '', (isset($_COOKIE['ml_lang']) ? $_COOKIE['ml_lang'] : 'en'));
		} else {
			$tag = $languageCode;
		}

		$moji = preg_match('@\['.$tag.'\]([^\[\]]+)\[/'.$tag.'\]@', $str, $matches);
		if ($matches != null && count($matches) >=2) {
			return $matches[1];
		}
		$moji = preg_match('@\[en\]([^\[\]]+)\[/en\]@', $str, $matches);
		if ($matches != null && count($matches) >=2) {
			return $matches[1];
		}
		return $str;
	}

	public static function getAdhocDirnameLists() {
		return array(
			'cs' => 'czech',
			'en' => 'english',
			'fr' => 'french',
			'el' => 'greek',
			'ja' => 'ja_utf8',
			'ko' => 'korean',
			'pt' => 'portuguese',
			'ru' => 'russian',
			'zh-CN' => 'zh-CN_utf8',
		);
	}

}

class UICustomizeTextResourceFileObject extends XoopsSimpleObject {

	function UICustomizeTextResourceFileObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('module_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('language', XOBJ_DTYPE_STRING, 10, false);
		$this->initVar('file_name', XOBJ_DTYPE_STRING, 255, false);
		$this->initVar('shared_file_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('creation_time', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('creation_uid', XOBJ_DTYPE_INT, 0, false);
	}
}

class UICustomizeTextResourceFilesHandler extends XoopsObjectGenericHandler {

	var $mTable = "uicustomize_text_resource_files";
	var $mPrimary = "id";
	var $mClass = "UICustomizeTextResourceFileObject";

	function getModuleTextResourceByLanguage($language) {
		$ut = $this->db->prefix('users');

		$_sql = '';
		$_sql .= 'SELECT ';
		$_sql .= ' T.module_id, ';
		$_sql .= ' T.shared_file_id, ';
		$_sql .= ' T.file_name, ';
		$_sql .= ' T.creation_time, ';
		$_sql .= ' U.name, ';
		$_sql .= ' U.uname ';
		$_sql .= ' FROM ' . $this->mTable . ' AS T';
		$_sql .= ' LEFT JOIN ' . $ut . ' AS U ON T.creation_uid = U.uid ';
		$_sql .= ' WHERE T.language = \'%s\' ';

		$sql = sprintf($_sql, $language);

		$rs = $this->db->query($sql);
		if (!$rs) {
			throw new Exception($this->db->error());
		}
		$modules = array();
		while ($row = $this->db->fetchArray($rs)) {
			$modules[$row['module_id']] = array(
				'mid' => $row['module_id'],
				'name' => '',
				'file' => $row['file_name'],
				'shared_file_id' => $row['shared_file_id'],
				'user' => empty($row['name']) ? $row['uname'] : $row['name'],
				'lastUpdate' => date(_MI_UIC_TR_DATE_FORMAT, $row['creation_time'])
			);
		}
		return $modules;
	}

}
?>
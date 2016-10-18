<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
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
var_dump(debug_backtrace());
die('呼ばれたらあかんもんが呼ばれたんです。'.__FILE__);
require_once(dirname(__FILE__).'/../include/Functions.php');
require_once(dirname(__FILE__).'/LangridServicesClass.php');
class TranslationPathSettingClass {

	private $db = null;
	private $TBL = null;

 	function __construct() {
		global $xoopsDB;
		$this->db = $xoopsDB;
		$this->TBL = $xoopsDB->prefix('translation_path_setting');
	}

	function searchByUserId($userId) {
		$ret = $this->search(array('user_id'=>$userId, 'tool_type'=>'all'));
		if (count($ret) == 0 && $userId != '1') {
			$res = $this->search(array('user_id'=>'1', 'tool_type'=>'all'));
			return $res;
		}
		return $ret;
	}
	function searchByUserIdByTop($userId) {
		$ret = $this->search(array('user_id'=>$userId, 'tool_type'=>'all'), '', 30);
		if (count($ret) == 0 && $userId != '1') {
			$res = $this->search(array('user_id'=>'1', 'tool_type'=>'all'));
			$res2 = array();
			foreach ($res as &$row) {
				$row['id'] = '-'.$row['id'];
				$res2[] = $row;
			}
			return $res2;
		}
		return $ret;
	}
	function searchByUserIdAndLanguages($userId, $srcLang, $tgtLang, $toolType = 'all') {

		if ($userId == '1') {
			// for admin.
			return $this->search(array('user_id'=>$userId, 'source_lang'=>$srcLang, 'target_lang'=>$tgtLang, 'tool_type' => $toolType));
		} else {
			if($this->hasUserSetting($userId)) {
				return $this->search(array('user_id'=>$userId, 'source_lang'=>$srcLang, 'target_lang'=>$tgtLang, 'tool_type' => $toolType));
			} else {
				return $this->search(array('user_id'=>'1', 'source_lang'=>$srcLang, 'target_lang'=>$tgtLang));
			}
		}
	}

	function searchByBBS() {
		return $this->search(array('user_id'=>'1', 'tool_type'=>'bbs'), 'id');
	}

	function searchSelectedTranslatorByUserId($userId) {
		$currents = $this->search(array('user_id'=>$userId, 'tool_type' => 'all'));
		if (count($currents) > 0) {
			return $currents[0]['translator_service_1'];
		} else {
			if ($userId != 1) {
				return $this->searchSelectedTranslatorByUserId('1');
			}
			return array();
		}
	}

	function searchSelectedDictionarysByUserId($userId) {
		$currentDicts = array();
		$currents = $this->search(array('user_id'=>$userId, 'tool_type'=>'all'));
		if (count($currents) > 0) {
			$row = $currents[0];
			$currentDicts['bind_global_dict_ids'] = $row['bind_global_dict_ids'];
			$currentDicts['bind_local_dict_ids'] = $row['bind_local_dict_ids'];
			$currentDicts['bind_user_dict_ids'] = $row['bind_user_dict_ids'];
		} else {
			$currentDicts['bind_global_dict_ids'] = '';
			$currentDicts['bind_local_dict_ids'] = '';
			$currentDicts['bind_user_dict_ids'] = '';
		}
		return $currentDicts;
	}

	private function search($wheres, $order = '', $limit = 0) {
		$sql = '';
		$sql .= 'select * from '.$this->TBL.' where delete_flag = \'0\' and';
		foreach ($wheres as $key => $value) {
			$sql .= '`'.$key.'` = \'' . mysql_real_escape_string($value) . '\' and';
		}
		$sql = substr($sql, 0, -4);
		if ($order == '') {
			$sql .= 'order by user_id, source_lang, target_lang, id';
		} else {
			$sql .= 'order by '.$order;
		}
		if ($limit > 0) {
			$sql .= ' limit 0, '.$limit;
		}

		$result = array();
		if ($rs = $this->db->query($sql)) {
			while ($row = $this->db->fetchArray($rs)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	function saveTranslator($userId, $translatorServideId, $toolType = 'all') {

		$currentDicts = $this->searchSelectedDictionarysByUserId($userId);

		$sql = 'delete from '.$this->TBL.' where user_id = \''.$userId.'\' and tool_type = \''.$toolType.'\'';
		$this->db->queryf($sql);

		$sev =& new LangridServicesClass();
		$service = $sev->searchTranslation($translatorServideId);
		$langPaths = explode(',', $service['supported_languages_paths']);
		foreach ($langPaths as $langPath) {
			$pair = explode('2', $langPath);
			$data = array(
				'tool_type' => $toolType,
				'user_id' => $userId,
				'source_lang' => $pair[0],
				'target_lang' => $pair[1],
				'translator_service_1' => $translatorServideId);
			$data = array_merge($data, $currentDicts);
			$this->insert($data);
		}
	}

	function saveDictionary($userId, $dictionaryId, $active, $dictionaryType, $toolType = 'all') {
		if (!$this->hasUserSetting($userId)) {
			$currentTranslator = $this->searchSelectedTranslatorByUserId($userId);
			$this->saveTranslator($userId, $currentTranslator);
		}

		$currentDicts = $this->searchSelectedDictionarysByUserId($userId);
		if ($active == 'on') {
			if ($dictionaryType == 'GLOBAL') {
				$currentDicts['bind_global_dict_ids'] = $this->appendDictionary($currentDicts['bind_global_dict_ids'], $dictionaryId);
			} else if ($dictionaryType == 'LOCAL') {
				$currentDicts['bind_local_dict_ids'] = $this->appendDictionary($currentDicts['bind_local_dict_ids'], $dictionaryId);
			} else if ($dictionaryType == 'USER') {
				$currentDicts['bind_user_dict_ids'] = $this->appendDictionary($currentDicts['bind_user_dict_ids'], $dictionaryId);
			}
		} else {
			if ($dictionaryType == 'GLOBAL') {
				$currentDicts['bind_global_dict_ids'] = $this->removeDictionary($currentDicts['bind_global_dict_ids'], $dictionaryId);
			} else if ($dictionaryType == 'LOCAL') {
				$currentDicts['bind_local_dict_ids'] = $this->removeDictionary($currentDicts['bind_local_dict_ids'], $dictionaryId);
			} else if ($dictionaryType == 'USER') {
				$currentDicts['bind_user_dict_ids'] = $this->removeDictionary($currentDicts['bind_user_dict_ids'], $dictionaryId);
			}
		}
		$this->update($currentDicts, array('user_id'=>$userId, 'tool_type'=>$toolType));
	}

	private function hasUserSetting($userId) {
		$sql = '';
		$sql .= 'select count(*) as CNT from '.$this->TBL.' where user_id = \''.$userId.'\' and delete_flag = \'0\'';
		$result = array();
		if ($rs = $this->db->query($sql)) {
			$row = $this->db->fetchArray($rs);
			if ($row['CNT'] > 0) {
				return true;
			} else {
				return false;
			}
		}

	}

	private function appendDictionary($current, $dict) {
		if (is_null($current) || $current == '') {
			return $dict;
		} else {
			return $current.','.$dict;
		}
	}
	private function removeDictionary($current, $dict) {
		if (is_null($current)) {
			return '';
		} else {
			$dits = array();
			$array = explode(',', $current);
			foreach ($array as $item) {
				if ($item != $dict) {
					$dist[] = $item;
				}
			}
			return implode(',', $dist);
		}
	}

	function saveBBSSetting($data) {

		$obj = array(
			'user_id' => '1',
			'tool_type' => 'bbs',
			'translator_service_1' => $data['service1'],
			'translator_service_2' => $data['service2'],
			'translator_service_3' => $data['service3'],
			'bind_global_dict_ids' => $data['global_dict_ids'],
			'bind_user_dict_ids' => $data['user_dict_ids'],
			'dictionary_flag' => $data['dict_flag']
		);
		if (isset($data['service3']) && $data['service3'] != '') {
			$obj['source_lang'] = $data['lang1'];
			$obj['inter_lang_1'] = $data['lang2'];
			$obj['inter_lang_2'] = $data['lang3'];
			$obj['target_lang'] = $data['lang4'];
		} else if (isset($data['service2']) && $data['service2'] != '') {
			$obj['source_lang'] = $data['lang1'];
			$obj['inter_lang_1'] = $data['lang2'];
			$obj['target_lang'] = $data['lang3'];
		} else {
			$obj['source_lang'] = $data['lang1'];
			$obj['target_lang'] = $data['lang2'];
		}

		if ($data['id']) {
			$currents = $this->search(array('id' => $data['id']));
			if ($data['isDelete'] == 'yes') {
				$this->realDelete($data['id']);
				return '';
			} else {
				if (count($currents) == 0) {
					$this->insert($obj);
					return mysql_insert_id();
				} else {
					$this->update($obj, array('id' => $data['id']));
					return $data['id'];
				}
			}
		} else {
			if ($data['isDelete'] != 'yes') {
				$this->insert($obj);
				return mysql_insert_id();
			}
		}
	}

	private function insert($data) {
		$isInsert = true;
		$sql = '';
		$sql .= 'select count(*) as c from '.$this->TBL.' ';
		$sql .= 'where `user_id` = \'%s\' ';
		$sql .= ' and `tool_type` = \'%s\' ';
		$sql .= ' and `source_lang` = \'%s\' ';
		$sql .= ' and `target_lang` = \'%s\' ';
		$sql = sprintf($sql, $data['user_id'], $data['tool_type'], $data['source_lang'], $data['target_lang']);
		if ($rs = $this->db->query($sql)) {
			if ($row = $this->db->fetchArray($rs)) {
				if ($row['c'] > 0) {
					$isInsert = false;
				}
			}
		} else {
			die('SQLError.'.__FILE__.'('.__LINE__.')');
		}

		if ($isInsert) {
			$sql = '';
			$sql .= 'insert into '.$this->TBL.' set ';
			foreach ($data as $key => $value) {
				$sql .= '`'.$key.'` = \'' . mysql_real_escape_string($value) . '\', ';
			}
			$sql = substr($sql, 0, -2);
			$this->db->queryf($sql);
		} else {
			$where = array();
			$where['user_id'] = $data['user_id'];
			$where['tool_type'] = $data['tool_type'];
			$where['source_lang'] = $data['source_lang'];
			$where['target_lang'] = $data['target_lang'];

			$this->update($data, $where);
		}
	}

	private function update($data, $where) {
		$sql = '';
		$sql .= 'update '.$this->TBL.' set edit_date = Now(), ';
		foreach ($data as $key => $value) {
			$sql .= '`'.$key.'` = \'' . mysql_real_escape_string($value) . '\', ';
		}
		$sql = substr($sql, 0, -2);
		$sql .= ' where ';
		foreach ($where as $key => $value) {
			$sql .= '`'.$key.'` = \'' . mysql_real_escape_string($value) . '\' and';
		}
		$sql = substr($sql, 0, -4);
		$this->db->queryf($sql);
	}

	private function realDelete($id) {
		if ($id != null) {
			$sql = '';
			$sql .= 'delete from '.$this->TBL.' where id = \''.$id.'\'';
			$this->db->queryf($sql);
		}
	}

	function saveUserSetting($uid, $data) {

		$obj = array(
			'user_id' => $uid,
			'tool_type' => 'all',
			'translator_service_1' => $data['service1'],
			'translator_service_2' => $data['service2'],
			'translator_service_3' => $data['service3'],
			'bind_global_dict_ids' => $data['global_dict_ids'],
			'bind_user_dict_ids' => $data['user_dict_ids'],
			'dictionary_flag' => $data['dict_flag']
		);
		if (isset($data['service3']) && $data['service3'] != '') {
			$obj['source_lang'] = $data['lang1'];
			$obj['inter_lang_1'] = $data['lang2'];
			$obj['inter_lang_2'] = $data['lang3'];
			$obj['target_lang'] = $data['lang4'];
		} else if (isset($data['service2']) && $data['service2'] != '') {
			$obj['source_lang'] = $data['lang1'];
			$obj['inter_lang_1'] = $data['lang2'];
			$obj['target_lang'] = $data['lang3'];
		} else {
			$obj['source_lang'] = $data['lang1'];
			$obj['target_lang'] = $data['lang2'];
		}

		if ($data['id']) {
			$currents = $this->search(array('id' => $data['id']));
			if ($data['isDelete'] == 'yes') {
				$this->realDelete($data['id']);
				return '';
			} else {
				if (count($currents) == 0) {
					$this->insert($obj);
					return mysql_insert_id();
				} else {
					$this->update($obj, array('id' => $data['id']));
					return $data['id'];
				}
			}
		} else {
			if ($data['isDelete'] != 'yes') {
				$this->insert($obj);
				return mysql_insert_id();
			}
		}
	}
}
?>

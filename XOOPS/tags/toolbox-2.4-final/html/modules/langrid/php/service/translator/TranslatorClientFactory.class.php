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
class TranslatorClientFactory {

	/**
	 * This is a Singleton.
	 */
	public function &getInstance() {
		static $_singleton_;
		if (!isset($_singleton_)) {
			$_singleton_ = new TranslatorClientFactory();
		}
		return $_singleton_;
	}

//	public function createClient($sourceLang, $targetLang) {
//		$root =& XCube_Root::getSingleton();
//		$db = $root->mController->mDB;
//		if ($root->mContext->mModule == null) {
//			$modName = "translate";
//		} else {
//			$modName = $root->mContext->mModule->mXoopsModule->get('dirname');
//		}
//		$uid = $root->mContext->mXoopsUser->getVar('uid');
//		if ($modName == null) {
//			$this->root->mController->redirectHeader(XOOPS_URL, 0);
//			return;
//		}
//
//		$table = $db->prefix('translation_path_setting');
//
//		$where = array();
//		$where['source_lang'] = $sourceLang;
//		$where['target_lang'] = $targetLang;
//		if ($modName == 'forum') {
//			$where['tool_type'] = 'bbs';
//		} else {
//			$hasUserSetting = false;
//			$sqlCheck = 'select count(*) as c from '.$table.' where user_id = '.$uid.' and delete_flag = 0';
//			if ($rs = $db->query($sqlCheck)) {
//				if ($r = $db->fetchArray($rs)) {
//					if ($r['c'] > 0) {
//						$hasUserSetting = true;
//					}
//				}
//			} else {
//				die('SQL Error.'.__FILE__.'('.__LINE__.')');
//			}
//
//			$where['tool_type'] = 'all';
//			if ($hasUserSetting) {
//				$where['user_id'] = $uid;
//			} else {
//				$where['user_id'] = '1';
//			}
//		}
//		$sql = $this->_makeSql($table, $where);
//
//		$setting = null;
//
//		if ($rs = $db->query($sql)) {
//			if ($row = $db->fetchArray($rs)) {
//				$setting = $this->_row2setting($row);
//			}
//		} else {
//			die('SQL Error.'.__FILE__.'('.__LINE__.')');
//		}
//
//		if ($setting == null) {
//			//return null;
//			require_once(dirname(__FILE__).'/NullTranslatorClient.class.php');
//			$dist = new NullTranslatorClient($sourceLang, $targetLang);
//			return $dist;
//		}
//
//		// check to N-hop?
//		if ($setting['translatorService2']) {
//			// The new BPEL service invoke.
//				require_once(dirname(__FILE__).'/TranslationWithBilingualDictionaryLM_NPassClient.class.php');
//				$dist = new TranslationWithBilingualDictionaryLM_NPassClient($setting);
//				return $dist;
//		} else {
//			// 1-pass translate
//			if (empty($setting['globalDictionaryIds']) == false || empty($setting['userDictionaryIds']) == false) {
//				// Dictionary binding translate.
//				require_once(dirname(__FILE__).'/TranslationWithBilingualDictionaryLMClient.class.php');
//				$dist = new TranslationWithBilingualDictionaryLMClient($setting);
//				return $dist;
//			} else {
//				// atomic translate.
//				require_once(dirname(__FILE__).'/AtomicTranslatorClient.class.php');
//				$dist = new AtomicTranslatorClient($setting);
//				return $dist;
//			}
//		}
//	}

//	private function _makeSql($table, $wheres) {
//		$sql = '';
//		$sql .= 'select * from '.$table.' where delete_flag = \'0\' and';
//		foreach ($wheres as $key => $value) {
//			$sql .= '`'.$key.'` = \'' . mysql_real_escape_string($value) . '\' and';
//		}
//		$sql = substr($sql, 0, -4);
//		return $sql;
//	}

//	private function _row2setting(&$row) {
//		$setting = array();
//		$setting['translatorService1'] = $row['translator_service_1'];
//		$setting['translatorService2'] = $row['translator_service_2'];
//		$setting['translatorService3'] = $row['translator_service_3'];
//		$setting['globalDictionaryIds'] = $row['bind_global_dict_ids'];
//		$setting['localDictionaryIds'] = $row['bind_local_dict_ids'];
//		$setting['userDictionaryIds'] = $row['bind_user_dict_ids'];
//		$setting['dictFlag'] = $row['dictionary_flag'];
//		$setting['interLang1'] = $row['inter_lang_1'];
//		$setting['interLang2'] = $row['inter_lang_2'];
//		$setting['sourceLang'] = $row['source_lang'];
//		$setting['targetLang'] = $row['target_lang'];
//		return $setting;
//	}
//
//	private function _getAccessModule() {
//		$modName = '';
//		$root =& XCube_Root::getSingleton();
//		$db = $root->mController->mDB;
//		if (!$root->mContext->mModule == null) {
//			$modName = $root->mContext->mModule->mXoopsModule->get('dirname');
//		}
//		return $modName;
//	}

	public function createClient($sourceLang, $targetLang) {
		$file = XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/TranslationServiceSetting.class.php';
		if (!file_exists($file)) {die('TranslationServiceSetting file not found.');}
		require_once($file);
		$setting =& new TranslationServiceSetting();
		$setObj = $setting->getSetIdByRequestModule();
		//print_r($setObj);die();
		if ($setObj == null) {
			return null;
		}
		$uid = $setObj->get('user_id');
		$setId = $setObj->get('set_id');
//		$modName = $this->_getAccessModule();
//		$root =& XCube_Root::getSingleton();
//		$uid = 0;
//		if ($root->mContext->mXoopsUser) {
//			$uid = $root->mContext->mXoopsUser->get('uid');
//		}
//		$setId = '1';
//		$setName = '';
//		switch ( $modName ) {
//			case 'forum':
//				$setName = 'BBS';
//				break;
//			default:
//				$setName = 'ALL';
//				break;
//		}
//
//		$file = XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/TranslationServiceSetting.class.php';
//		if (!file_exists($file)) {die('TranslationServiceSetting file not found.');}
//		require_once($file);
//		$setting =& new TranslationServiceSetting();
//		$setHandler =& $setting->getSetHandler();
//		$mc =& new CriteriaCompo();
//		$mc->add(new Criteria('set_name', $setName));
//		$mc->add(new Criteria('user_id', $uid));
//		$objects =& $setHandler->getObjects($mc);
//		if (($objects == null || count($objects) == 0) && $uid == '1') {
//			$mc =& new CriteriaCompo();
//			$mc->add(new Criteria('set_name', $setName));
//			$mc->add(new Criteria('user_id', '1'));
//			$objects =& $setHandler->getObjects($mc);
//		}
//		if ($objects != null && count($objects) > 0) {
//			$setId = $objects[0]->get('set_id');
//		} else {
//			die('Translation Set is not found.|'.__FILE__.'('.__LINE__.')');
//		}

		$translationPathAry =& $setting->getServiceSettings($uid, $setId, $sourceLang, $targetLang);
		if ($translationPathAry == null || count($translationPathAry) == 0) {
			// translation path not found. to NullTranslator created.
			require_once(dirname(__FILE__).'/NullTranslatorClient.class.php');
			$dist = new NullTranslatorClient($sourceLang, $targetLang);
			return $dist;
		}

		$pathObj = $translationPathAry[0];

		if (count($pathObj->getExecs()) == 1) {
			// 1-pass
			$execObj = $pathObj->getExecs();
//			if (count($execObj[0]->getBinds()) == 0) {
			if ($this->isNoBinding($pathObj->getExecs())) {
				// Atomic
				require_once(dirname(__FILE__).'/NoBindingTranslatorClient.class.php');
				$dist =& new NoBindingTranslatorClient($execObj[0]);
			} else {
				// Compsit
				require_once(dirname(__FILE__).'/BindingTranslatorClient.class.php');
				$dist =& new BindingTranslatorClient($execObj[0]);
			}
		} else {
			// N-pass
			if ($this->isNoBinding($pathObj->getExecs())) {
				require_once(dirname(__FILE__).'/NoBindingTranslatorClient.class.php');
				$dist =& new NoBindingTranslatorClient_MultiHop($pathObj->getExecs());
			} else {
				require_once(dirname(__FILE__).'/BindingTranslatorClient.class.php');
				$dist =& new BindingTranslatorClient_MultiHop($pathObj->getExecs());
			}
		}
		return $dist;
	}

	public function createClientWithSetId($sourceLang, $targetLang,$setId) {
		$root =& XCube_Root::getSingleton();
		$uid = 0;
		if ($root->mContext->mXoopsUser) {
			$uid = $root->mContext->mXoopsUser->get('uid');
		}
		if($uid == 0 || $setId == null){return null;}

		$file = XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/TranslationServiceSetting.class.php';
		if (!file_exists($file)) {die('TranslationServiceSetting file not found.');}
		require_once($file);
		$setting =& new TranslationServiceSetting();

		$translationPathAry =& $setting->getServiceSettings($uid, $setId, $sourceLang, $targetLang);
		if ($translationPathAry == null || count($translationPathAry) == 0) {
			// translation path not found. to NullTranslator created.
			require_once(dirname(__FILE__).'/NullTranslatorClient.class.php');
			$dist = new NullTranslatorClient($sourceLang, $targetLang);
			return $dist;
		}

		$pathObj = $translationPathAry[0];

		if (count($pathObj->getExecs()) == 1) {
			// 1-pass
			$execObj = $pathObj->getExecs();
//			if (count($execObj[0]->getBinds()) == 0) {
			if ($this->isNoBinding($pathObj->getExecs())) {
				// Atomic
				require_once(dirname(__FILE__).'/NoBindingTranslatorClient.class.php');
				$dist =& new NoBindingTranslatorClient($execObj[0]);
			} else {
				// Compsit
				require_once(dirname(__FILE__).'/BindingTranslatorClient.class.php');
				$dist =& new BindingTranslatorClient($execObj[0]);
			}
		} else {
			// N-pass
			if ($this->isNoBinding($pathObj->getExecs())) {
				require_once(dirname(__FILE__).'/NoBindingTranslatorClient.class.php');
				$dist =& new NoBindingTranslatorClient_MultiHop($pathObj->getExecs());
			} else {
				require_once(dirname(__FILE__).'/BindingTranslatorClient.class.php');
				$dist =& new BindingTranslatorClient_MultiHop($pathObj->getExecs());
			}
		}
		return $dist;
	}

	private function isNoBinding($execs) {
		foreach ($execs as $exec) {
			foreach ($exec->getBinds() as $bind) {
				if ($bind->get('bind_type') != 9) {
					return false;
				}
			}
		}
		return true;
	}
}
?>
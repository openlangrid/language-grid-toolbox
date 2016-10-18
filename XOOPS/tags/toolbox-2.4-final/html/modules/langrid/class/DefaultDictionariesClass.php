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
require_once(dirname(__FILE__).'/../include/Functions.php');
require_once(XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/TranslationServiceSetting.class.php');
require_once(XOOPS_ROOT_PATH.'/modules/dictionary/php/lib/user-dictionary-controller.php');
require_once(dirname(__FILE__).'/LangridServicesClass.php');

class DefaultDictionariesSetting {
	private $svcSetting = null;
	private $deploy_dicts = null;

	function __construct() {
		$this->svcSetting =& new TranslationServiceSetting();
		$UDController =& new UserDictionaryController();
		$this->deploy_dicts = array();
		$ret = $UDController->load();
		if($ret["status"] == "OK"){
			foreach($ret["contents"] as $UDict){
				if($UDict["deployFlag"]){
					$this->deploy_dicts[] = $UDict["name"];
				}
			}
		}
	}

	function searchByUserId($userId) {
		$res = $this->search($userId, 'TEXT_TRANSLATION');
		if(!$res && $userId != 1){
			$res = $this->search('1','TEXT_TRANSLATION');
		}
		if(!$res){
			$res = array('bind_global_dict_ids'=>'','bind_local_dict_ids'=>'','bind_user_dict_ids'=>'');
		}

		return $res;
	}
	function searchByUserId4Web($userId) {
		$res = $this->search($userId, 'WEB_TRANSLATION');
		if(!$res && $userId != 1){
			$res = $this->search('1','WEB_TRANSLATION');
		}
		if(!$res){
			$res = array('bind_global_dict_ids'=>'','bind_local_dict_ids'=>'','bind_user_dict_ids'=>'');
		}

		return $res;
	}

	function searchByUserId4Trans($userId) {
		$res = $this->search($userId, 'COLLABTRANS');
		if(!$res && $userId != 1){
			$res = $this->search('1','COLLABTRANS');
		}
		if(!$res){
			$res = array('bind_global_dict_ids'=>'','bind_local_dict_ids'=>'','bind_user_dict_ids'=>'');
		}
		return $res;
	}

	function searchByBBS() {
		$res = $this->search('1','BBS');
		if(!$res){
			$res = array('bind_global_dict_ids'=>'','bind_local_dict_ids'=>'','bind_user_dict_ids'=>'');
		}
		return $res;
	}
	function searchByCommunication() {
		$res = $this->search('1','COMMUNICATION');
		if(!$res){
			$res = array('bind_global_dict_ids'=>'','bind_local_dict_ids'=>'','bind_user_dict_ids'=>'');
		}
		return $res;
	}
	
	function searchByStorefront() {
		$res = $this->search('1','STORE_FRONT');
		if(!$res){
			$res = array('bind_global_dict_ids'=>'','bind_local_dict_ids'=>'','bind_user_dict_ids'=>'');
		}
		return $res;
	}
	
	private function search($user_id,$tool_type) {
		$sev =& new LangridServicesClass();
		$result = array('bind_global_dict_ids'=>'','bind_local_dict_ids'=>'','bind_user_dict_ids'=>'');
		$set_id = $this->svcSetting->getSetIdByName($user_id,$tool_type);
		if($set_id){
			$ret = $this->svcSetting->loadDefaultDictionary($user_id,$set_id);
			if($ret != null){
				$binds = $ret->getBinds();
				$ids = array(1=>array(),2=>array(),3=>array());
				if(is_array($binds)){
					foreach($binds as $bind){
						$btype = $bind->get('bind_type');
						if($btype == 2){
							$DictInfo = $sev->searchLocalDictionaryByEndpoint($bind->get('bind_value'));
							if(is_array($DictInfo) && count($DictInfo) > 0){
								$val = $DictInfo[0]['service_id'];
							}else{
								$val = '';
								foreach($this->deploy_dicts as $dd){
									if(trim(DICTIONARY_ENTPOINT_URL_BASE.str_replace(' ', '_',$dd)) == trim($bind->get('bind_value'))){
										$val = $dd;
										break;
									}
								}
							}
						}else{
							$val = $bind->get('bind_value');
						}
						$ids[$btype][] = $val;
					}
				}
				$result['bind_global_dict_ids'] = implode(",",$ids[1]);
				$result['bind_local_dict_ids'] = implode(",",$ids[2]);
				$result['bind_user_dict_ids'] = implode(",",$ids[3]);
			}else{
				return false;
			}
		}else{
			return false;
		}
		return $result;
	}

	function saveUserDictionaries($uid, $data) {
		$set_id = $this->svcSetting->getSetIdByName($uid,'TEXT_TRANSLATION');
		if($set_id != null){
			$this->saveDafaultDictionary($uid,$set_id,$data);
		}
		return true;
	}
	function saveTransDictionaries($uid, $data) {
		$set_id = $this->svcSetting->getSetIdByName($uid,'COLLABTRANS');
		if($set_id != null){
			$this->saveDafaultDictionary($uid,$set_id,$data);
		}
		return true;
	}
	function saveWebDictionaries($uid, $data) {
		$set_id = $this->svcSetting->getSetIdByName($uid,'WEB_TRANSLATION');
		if($set_id != null){
			$this->saveDafaultDictionary($uid,$set_id,$data);
		}
		return true;
	}
	function saveBBSDictionaries($data) {
		$set_id = $this->svcSetting->getSetIdByName(1,'BBS');
		if($set_id != null){
			$this->saveDafaultDictionary(1,$set_id,$data);
		}
		return true;
	}

	function saveCommunicationDictionaries($data) {
		$set_id = $this->svcSetting->getSetIdByName(1,'COMMUNICATION');
		if($set_id != null){
			$this->saveDafaultDictionary(1,$set_id,$data);
		}
		return true;
	}

	function saveStoreFrontDictionaries($data) {
		$set_id = $this->svcSetting->getSetIdByName(1,'STORE_FRONT');
		if($set_id != null){
			$this->saveDafaultDictionary(1,$set_id,$data);
		}
		return true;
	}
	
	function removeStoreFrontDictionaries() {
		$set_id = $this->svcSetting->getSetIdByName(1,'STORE_FRONT');

		if($set_id != null){
			$Setting = $this->svcSetting->loadDefaultDictionary(1, $set_id);
			if($Setting != null){
				$this->svcSetting->removeDefaultDictionaryBind($Setting->get('setting_id'));
			}			
		}
		return true;
	}

	function saveDafaultDictionary($uid,$set_id,$data){
		$sev =& new LangridServicesClass();
		
		$Setting = $this->svcSetting->loadDefaultDictionary($uid,$set_id);
		if($Setting == null){
			$Setting = $this->svcSetting->addDefaultDictionarySetting($uid,$set_id);
		}else{
			$this->svcSetting->removeDefaultDictionaryBind($Setting->get('setting_id'));
		}

		$dicts = array(1=>'',2=>'',3=>'');
		$dicts[1] = $data['global_dict_ids'];
		$dicts[2] = $data['local_dict_ids'];
		$dicts[3] = $data['user_dict_ids'];

		foreach($dicts as $Key => $id_str){
			if(trim($id_str) != ''){
				$ids = explode(",",$id_str);
				foreach($ids as $dict_id){
					if($Key == 2){
						$SrvInfo = $sev->searchLocalDictionary($dict_id);
						if(is_array($SrvInfo) && count($SrvInfo) > 0){
							$dict_id = $SrvInfo[0]['endpoint_url'];
						}else{
							$dict_id = DICTIONARY_ENTPOINT_URL_BASE.str_replace(' ', '_',$dict_id);
						}
					}
					$bind = $this->svcSetting->addDefaultDictionaryBind($Setting->get('setting_id'),$Key,$dict_id);
				}
			}
		}

		$ret = $this->updatePathSetting($uid,$set_id);
		return true;
	}

	private function updatePathSetting($uid,$set_id) {
		$this->svcSetting->updateDefaultDict4TranslationPath($uid,$set_id);
	}
}

/* $Id: DefaultDictionariesClass.php 3941 2010-08-12 10:57:50Z infonic $ */
?>

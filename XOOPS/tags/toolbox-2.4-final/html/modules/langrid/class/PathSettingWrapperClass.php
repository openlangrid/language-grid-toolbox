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
class PathSettingWapperClass {
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
		$ret = $this->search('',$userId,'TEXT_TRANSLATION');
		if (count($ret) == 0 && $userId != '1') {
			$res = $this->search('','1','TEXT_TRANSLATION');
			return $res;
		}
		return $ret;
	}
	function searchByUserIdByTop($userId) {
		$ret = $this->search('',$userId,'TEXT_TRANSLATION');
		if (count($ret) == 0 && $userId != '1') {
			$res = $this->search('','1','TEXT_TRANSLATION');
			$res2 = array();
			foreach ($res as &$row) {
				$row['id'] = '-'.$row['id'];
				$res2[] = $row;
			}
			return $res2;
		}
		return $ret;
	}
	function searchByUserIdByTop4Web($userId) {
		$ret = $this->search('',$userId,'WEB_TRANSLATION');
		if (count($ret) == 0 && $userId != '1') {
			$res = $this->search('','1','WEB_TRANSLATION');
			$res2 = array();
			foreach ($res as &$row) {
				$row['id'] = '-'.$row['id'];
				$res2[] = $row;
			}
			return $res2;
		}
		return $ret;
	}

	function searchByUserIdByTop4Trans($userId) {
		$ret = $this->search('',$userId,'COLLABTRANS');
		if (count($ret) == 0 && $userId != '1') {
			$res = $this->search('','1','COLLABTRANS');
			$res2 = array();
			foreach ($res as &$row) {
				$row['id'] = '-'.$row['id'];
				$res2[] = $row;
			}
			return $res2;
		}
		return $ret;
	}

	function searchByBBS() {
		return $this->search('','1','BBS');
	}
	function searchByCommunication() {
		return $this->search('','1','COMMUNICATION');
	}

	private function search($id,$uid = null,$tool_type = null, $limit = null) {
		$result = array();

		if(is_numeric($id)){
			$result[] = $this->svcSetting->loadServiceSetting($id);
		}else{
			if(!empty($uid)){
				$set_id = $this->svcSetting->getSetIdByName($uid,$tool_type);
				if(is_numeric($uid) && is_numeric($set_id)){
					$res = $this->svcSetting->getServiceSettings($uid,$set_id);
					$cnt = 1;
					if(is_array($res)){
						foreach($res as $path){
							$result[] = $this->svcSetting->loadServiceSetting($path->get('path_id'));;
							if(is_numeric($limit)){
								$cnt++;
								if($cnt > $limit){break;}
							}
						}
					}
				}
			}
		}

		return $this->formatLoadData($result);
	}

	function saveBBSSetting($data) {
		$set_id = $this->svcSetting->getAndCreateSetIdByName(1,'BBS');

		return $this->saveTranslationSetting(1,$set_id,$data);
	}
	function saveCommunicationSetting($data) {
		$set_id = $this->svcSetting->getAndCreateSetIdByName(1,'COMMUNICATION');
		return $this->saveTranslationSetting(1,$set_id,$data);
	}

	function saveUserSetting($uid, $data) {
		$set_id = $this->svcSetting->getAndCreateSetIdByName($uid,'TEXT_TRANSLATION');

		return $this->saveTranslationSetting($uid,$set_id,$data);
	}

	function saveTransSetting($uid, $data) {
		$set_id = $this->svcSetting->getAndCreateSetIdByName($uid,'COLLABTRANS');
		return $this->saveTranslationSetting($uid,$set_id,$data);
	}

	function saveWebSetting($uid, $data) {
		$set_id = $this->svcSetting->getAndCreateSetIdByName($uid,'WEB_TRANSLATION');

		return $this->saveTranslationSetting($uid,$set_id,$data);
	}

	function saveTranslationSetting($uid,$set_id,$data){
		$sev =& new LangridServicesClass();

		if(is_numeric($data['id'])){
			$path = $this->svcSetting->loadServiceSetting($data['id']);
		}else{
			$path = null;
		}

		if ($data['isDelete'] == 'yes') {
			if($path != null){
				$path = $this->svcSetting->removeTranslationPath($data['id']);
				return '';
			}
		}else{
			$exe_dara = array();
			$exe_dara[1]['source_lang'] = $data['lang1'];
			$exe_dara[1]['target_lang'] = $data['lang2'];
			$exe_dara[1]['service_id'] = $data['service1'];
			$exe_dara[1]['service_type'] = 0;
			$exe_dara[1]['dict_flag'] = $data['dict_flag_1'];
			$exe_dara[1]['analyzer'] = $data['morph_analyzer1'];
			$exe_dara[1]['global_dicts'] = $data['global_dict_1'];
			$exe_dara[1]['local_dicts'] = $data['local_dict_1'];
			$exe_dara[1]['temp_dicts'] = $data['temp_dict_1'];

			$exe_dara[2]['source_lang'] = $data['lang2'];
			$exe_dara[2]['target_lang'] = $data['lang3'];
			$exe_dara[2]['service_id'] = $data['service2'];
			$exe_dara[2]['service_type'] = 0;
			$exe_dara[2]['dict_flag'] = $data['dict_flag_2'];
			$exe_dara[2]['analyzer'] = $data['morph_analyzer2'];
			$exe_dara[2]['global_dicts'] = $data['global_dict_2'];
			$exe_dara[2]['local_dicts'] = $data['local_dict_2'];
			$exe_dara[2]['temp_dicts'] = $data['temp_dict_2'];

			$exe_dara[3]['source_lang'] = $data['lang3'];
			$exe_dara[3]['target_lang'] = $data['lang4'];
			$exe_dara[3]['service_id'] = $data['service3'];
			$exe_dara[3]['service_type'] = 0;
			$exe_dara[3]['dict_flag'] = $data['dict_flag_3'];
			$exe_dara[3]['analyzer'] = $data['morph_analyzer3'];
			$exe_dara[3]['global_dicts'] = $data['global_dict_3'];
			$exe_dara[3]['local_dicts'] = $data['local_dict_3'];
			$exe_dara[3]['temp_dicts'] = $data['temp_dict_3'];

			for($i=1;$i<=3;$i++){
				$SrvInfo = $sev->searchLocalTranslators($exe_dara[$i]['service_id']);
				if(is_array($SrvInfo) && count($SrvInfo) > 0){
					if($SrvInfo[0]['service_type'] == 'IMPORTED_TRANSLATION'){
						$exe_dara[$i]['service_id'] = $SrvInfo[0]['endpoint_url'];
						$exe_dara[$i]['service_type'] = 1;
					}
				}
			}

			$loopCnt=0;
			if (isset($data['service3']) && $data['service3'] != '') {
				$source_lang = $data['lang1'];
				$target_lang = $data['lang4'];
				$loopCnt = 3;
			} else if (isset($data['service2']) && $data['service2'] != '') {
				$source_lang = $data['lang1'];
				$target_lang = $data['lang3'];
				$loopCnt = 2;
			} else {
				$source_lang = $data['lang1'];
				$target_lang = $data['lang2'];
				$loopCnt = 1;
			}

			if($path != null){
				$execs = $path->getExecs();
				foreach($execs as $exec){
					$this->svcSetting->removeTranslationExec($path->get('path_id'),$exec->get('exec_id'));
				}
				$path->set('source_lang', $source_lang);
				$path->set('target_lang', $target_lang);
				$path->set('revs_path_id', 0);
				$path->setExecs(array());
				$this->svcSetting->update($path,false);
			}else{
				$path = $this->svcSetting->addTranslationPath($uid,$set_id,$source_lang,$target_lang);
			}

			for($lp = 1;$lp <= $loopCnt;$lp++){
				$edata = $exe_dara[$lp];
				if($edata['dict_flag'] != 1 && $edata['dict_flag'] != 2){$edata['dict_flag'] = 0;}
				$exec = $this->svcSetting->addTranslationExec($path->get('path_id'),$edata['source_lang'],$edata['target_lang'],$edata['service_id'],$edata['service_type'],$edata['dict_flag']);

				if(trim($edata['analyzer']) != ''){
					$bind = $this->svcSetting->addTranslationBind($path->get('path_id'), $exec->get('exec_id'), '9',trim($edata['analyzer']));
				}
				if(trim($edata['global_dicts']) != ''){
					$dicts = explode(",",$edata['global_dicts']);
					foreach($dicts as $dic){
						$bind = $this->svcSetting->addTranslationBind($path->get('path_id'),$exec->get('exec_id'),'1',$dic);
					}
				}
				if(trim($edata['local_dicts']) != ''){
					$dicts = explode(",",$edata['local_dicts']);
					foreach($dicts as $dic){
						$SrvInfo = $sev->searchLocalDictionary($dic);
						if(is_array($SrvInfo) && count($SrvInfo) > 0){
							$dic = $SrvInfo[0]['endpoint_url'];
						}else{
							$dic = DICTIONARY_ENTPOINT_URL_BASE.str_replace(' ', '_',$dic);
						}
						$bind = $this->svcSetting->addTranslationBind($path->get('path_id'),$exec->get('exec_id'),'2',$dic);
					}
				}
				if(trim($edata['temp_dicts']) != ''){
					$dicts = explode(",",$edata['temp_dicts']);
					foreach($dicts as $dic){
						$bind = $this->svcSetting->addTranslationBind($path->get('path_id'),$exec->get('exec_id'),'3',$dic);
					}
				}
			}

			return $path->get('path_id');
		}
	}

	function linkTranslation($id1,$id2){
		$this->svcSetting->linkReverse($id1,$id2);
	}

	function formatLoadData($settings){
		$sev =& new LangridServicesClass();
		$response = array();

		$tmp = array();
		foreach($settings as $path){
			$rs = array();
			$rs['id'] = $path->get('path_id');
			$rs['name'] = $path->get('path_name');
			$rs['uid'] = $path->get('user_id');
			$rs['revs_id'] = $path->get('revs_path_id');
			$rs['isDelete'] = 0;
			$rs['source_lang'] = $path->get('source_lang');
			$rs['target_lang'] = $path->get('target_lang');
			$rs['flow'] = 'left';
			$rs['inter_lang_1'] = '';
			$rs['inter_lang_2'] = '';
			for($i=1;$i<=3;$i++){
				$rs['lang'.$i] = '';
				$rs['translator_service_'.$i] = '';
				$rs['global_dict_'.$i] = '';
				$rs['local_dict_'.$i] = '';
				$rs['temp_dict_'.$i] = '';
				$rs['dict_flag_'.$i] = '';
				$rs['morph_analyzer'.$i] = '';
			}
			$rs['lang4'] = '';
			$rs['morph_analyzer4'] = '';

			$execs = $path->getExecs();
			foreach($execs as $exec){
				$cnt = $exec->get('exec_order');

				$rs['lang'.$cnt] = $exec->get('source_lang');
				$rs['lang'.($cnt+1)] = $exec->get('target_lang');

				if($exec->get('service_type') == 1){
					$SrvInfo = $sev->searchLocalTranslatorsByEndpoint($exec->get('service_id'));
					if(is_array($SrvInfo) && count($SrvInfo) > 0){
						$rs['translator_service_'.$cnt] = $SrvInfo[0]['service_id'];
					}else{
						$rs['translator_service_'.$cnt] = $exec->get('service_id');
					}
				}else{
					$rs['translator_service_'.$cnt] = $exec->get('service_id');
				}
				$rs['dict_flag_'.$cnt] = $exec->get('dictionary_flag');

				if($cnt >= 2){
					$rs['inter_lang_'.($cnt-1)] = $exec->get('source_lang');
				}
				$binds = $exec->getBinds();
				$ids = array(1=>array(),2=>array(),3=>array());
				foreach($binds as $bind){
					$btype = intval($bind->get('bind_type'));
					if($btype == 9){
						$rs['morph_analyzer'.$cnt] = $bind->get('bind_value');
					}else{
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
				$rs['global_dict_'.$cnt] = implode(",",$ids[1]);
				$rs['local_dict_'.$cnt] = implode(",",$ids[2]);
				$rs['temp_dict_'.$cnt] = implode(",",$ids[3]);
			}
			$tmp[] = $rs;
		}

		foreach($tmp as $key => &$rs){
			if($rs['revs_id'] > 0){
				foreach($tmp as $key2 => &$rs2){
					if($rs2['id'] == $rs['revs_id']){
						$rs['id'] .= ",".$rs2['id'];
						$rs2['id'] = "";

						$rs['flow'] = 'both';
						if($rs2['lang1'] == $rs['lang4']){
							$rs['morph_analyzer4'] = $rs2['morph_analyzer1'];
						}elseif($rs2['lang1'] == $rs['lang3']){
							$rs['morph_analyzer3'] = $rs2['morph_analyzer1'];
						}elseif($rs2['lang1'] == $rs['lang2']){
							$rs['morph_analyzer2'] = $rs2['morph_analyzer1'];
						}
						unset($tmp[$key2]);
						break;
					}
				}
			}
			if($rs['id'] != ""){
				$response[] = $rs;
			}
		}

		return $response;
	}
}
?>

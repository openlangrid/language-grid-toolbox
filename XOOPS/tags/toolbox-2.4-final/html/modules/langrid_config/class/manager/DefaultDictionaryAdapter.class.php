<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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
/* $Id: DefaultDictionaryAdapter.class.php 4654 2010-10-28 06:37:35Z yoshimura $ */

/*
 * service_grid以下のDefaultDictinary系２個のDAOのアダプタ
 */

require_once(XOOPS_ROOT_PATH.'/service_grid/db/adapter/DaoAdapter.class.php');
require_once(XOOPS_ROOT_PATH.'/modules/dictionary/services/defines.php');

class DefaultDictionaryAdapter {

	private $mTranslationSetDaoImpl = null;
	private $mDDSettingDaoImpl = null;
	private $mDDBindDaoImpl = null;

    public function __construct() {
    	$da = DaoAdapter::getAdapter();
    	$this->mTranslationSetDaoImpl = $da->getTranslationSetDao();
    	$this->mDDSettingDaoImpl = $da->getDefaultDictionarySettingDao();
    	$this->mDDBindDaoImpl = $da->getDefaultDictionaryBindDao();
    }

    public function loadDefaultDictionary($bindingSetName, $userId) {
		$result = array('bind_global_dict_ids'=>'','bind_local_dict_ids'=>'','bind_user_dict_ids'=>'');

//		$sets = $this->mTranslationSetDaoImpl->queryBySetName($bindingSetName, $userId);
		$sets = $this->mTranslationSetDaoImpl->findByBindingSetNameAndUserId($bindingSetName, $userId);
		if ($sets == false) {
			return $result;
		}
		$setId = $sets[0]->getSetId();

		$ddSettings = $this->mDDSettingDaoImpl->queryBySetIdUserId($setId, $userId);
		if ($ddSettings == null || is_array($ddSettings) === false || count($ddSettings) != 1) {
			return $result;
		}
		$settingId = $ddSettings[0]->getSettingId();

		$ddBinds = $this->mDDBindDaoImpl->queryBySettingId($settingId);
		if ($ddBinds == null || is_array($ddBinds) === false) {
			return $result;
		}

		$ids = array(1=>array(),2=>array(),3=>array());
		foreach ($ddBinds as $ddBind) {
			$btype = $ddBind->getBindType();
			$val = $ddBind->getBindValue();

			if ($btype == '2') {
		    	$lsDao = DaoAdapter::getAdapter()->getLangridServicesDao();
		    	$objs = $lsDao->queryGetByEndPoint($val, 'IMPORTED', 'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH');
		    	if ($objs != null && is_array($objs) && count($objs) > 0) {
		    		$val = $objs[0]->getServiceId();
		    	} else {
		    		$val = str_replace('_', ' ', str_replace(DICTIONARY_ENTPOINT_URL_BASE, '', $val));
		    	}
			}

			$ids[$btype][] = $val;
		}

		$result['bind_global_dict_ids'] = implode(",",$ids[1]);
		$result['bind_local_dict_ids'] = implode(",",$ids[2]);
		$result['bind_user_dict_ids'] = implode(",",$ids[3]);

		return $result;
    }

    public function saveDefaultDictionary($bindingSetName, $userId, $data) {
//    	$set = $this->mTranslationSetDaoImpl->queryBySetName($bindingSetName, $userId);
		$set = $this->mTranslationSetDaoImpl->findByBindingSetNameAndUserId($bindingSetName, $userId);
    	if ($set == false) {
    		throw new DefaultDictionaryAdapterException('set is null.', __METHOD__, func_get_args());
    	}
    	$setId = $set[0]->getSetId();

    	$ddSettings = $this->mDDSettingDaoImpl->queryBySetIdUserId($setId, $userId);
    	if ($ddSettings == null || is_array($ddSettings) === false || count($ddSettings) != 1) {
    		$ddSettings = array( $this->mDDSettingDaoImpl->insert($setId, $userId) );
    	}
    	$settingId = $ddSettings[0]->getSettingId();

    	if (!$this->mDDBindDaoImpl->deleteBySettingId($settingId)) {
    		throw new DefaultDictionaryAdapterException('sql error.', __METHOD__, func_get_args());
    	}

		$dicts = array(1=>'',2=>'',3=>'');
		$dicts[1] = $data['global_dict_ids'];
		$dicts[2] = $data['local_dict_ids'];
		$dicts[3] = $data['user_dict_ids'];
		$bindId = 1;
		foreach($dicts as $bindType => $id_str){
			if(trim($id_str) != ''){
				$ids = explode(",",$id_str);
				foreach($ids as $bindValue){

			    	if ($bindType == '2') {
				    	$lsDao = DaoAdapter::getAdapter()->getLangridServicesDao();
				    	$objs = $lsDao->queryGetByServiceId($bindValue, 'IMPORTED', 'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH');
				    	if ($objs != null && is_array($objs) && count($objs) > 0) {
				    		$bindValue = $objs[0]->getEndpointUrl();
				    	} else {
				    		$bindValue = DICTIONARY_ENTPOINT_URL_BASE.str_replace(' ', '_', $bindValue);
				    	}
			    	}

					if (!$this->mDDBindDaoImpl->insert($settingId, $bindId++, $bindType, $bindValue)) {
						throw new DefaultDictionaryAdapterException('dd insert sql error.', __METHOD__, func_get_args());
					}
				}
			}
		}

		// TODO:updatePathSetting();
    }
}

class DefaultDictionaryAdapterException extends Exception {
	public function __construct($message, $method, $arguments) {
		parent::__construct($method.'('.print_r($arguments).') '.$message);
	}
}
?>
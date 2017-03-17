<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009-2010  NICT Language Grid Project
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
/* $Id: UserSettingRegistor.class.php 4632 2010-10-21 04:11:35Z yoshimura $ */

require_once(XOOPS_ROOT_PATH.'/service_grid/db/adapter/DaoAdapter.class.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/class/action/user/User_common.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/class/manager/VoiceSettingManager.class.php');

class UserSettingRegistor {

    public function __construct() {
    }

    public function copyUserSettingFromAdmin($loginUser) {
    	try {
    		debugLog('LoginSuccessAction::UserSettingRegistor->copyUserSettingFromAdmin()');
    		if ($loginUser == null || $loginUser->get('uid') <= 1) {
				debugLog('loginUserId='.$loginUser->get('uid').' is administrator user.');
    			return;
    		}
			if ($this->hasUserSettings($loginUser->get('uid')) === true) {
				debugLog('loginUserId='.$loginUser->get('uid').' is registored translation path settings.');
				return;
			}
    		debugLog('loginUserId='.$loginUser->get('uid').'LoginSuccessAction::UserSettingRegistor->copyUserSettingFromAdmin() Start Copy.');
			$this->_copy($loginUser->get('uid'));
    		debugLog('loginUserId='.$loginUser->get('uid').'LoginSuccessAction::UserSettingRegistor->copyUserSettingFromAdmin() Complet Copy.');
    	} catch (Exception $e) {
    		debugLog(print_r($e, true));
    	}
    	// 音声設定
    	VoiceSettingManager::copySettingAdminToUser();
    }

    private function hasUserSettings($userId) {
		$setDao = DaoAdapter::getAdapter()->getTranslationSetDao();
		$set = $setDao->findByBindingSetNameAndUserId(BINDING_SET_NAME, $userId);
		if ($set) {
			return true;
		}
		return false;
    }

    private function _copy($userId) {
    	$da = DaoAdapter::getAdapter();
    	$setDao = $da->getTranslationSetDao();
    	$pathDao = $da->getTranslationPathDao();
    	$execDao = $da->getTranslationExecDao();
    	$bindDao = $da->getTranslationBindDao();
    	$ddSetDao = $da->getDefaultDictionarySettingDao();
    	$ddBindDao = $da->getDefaultDictionaryBindDao();

		$adminSets = $setDao->findByBindingSetNameAndUserId(BINDING_SET_NAME, 1);

    	$set = $setDao->insertNew(BINDING_SET_NAME, $userId);
    	$setId = $set->getSetId();

		$adminPaths = $pathDao->queryBySetId(1, $adminSets[0]->getSetId());

		$revsIds = array();// ('en2ja'=>'pathId')
		$pathList = array();

		foreach ($adminPaths as $pathObj) {
			$adminExecs = $execDao->queryByPathId($pathObj->getPathId());


			$pathObj->setSetId($setId);
			$pathObj->setUserId($userId);
			$pathObj->setRevsPathId('');
			$path = $pathDao->insert($pathObj);
			$pathList[] = $path;

			$langKey = $pathObj->getSourceLang().'2'.$pathObj->getTargetLang();
			$revsIds[$langKey] = $path->getPathId();

			foreach ($adminExecs as $execObj) {
				$adminBinds = $bindDao->queryByExecObject($execObj);

				$execObj->setPathId($path->getPathId());
				$exec = $execDao->insert($execObj);

				foreach ($adminBinds as $bindObj) {
					$bindObj->setPathId($path->getPathId());
					$bindObj->setExecId($execObj->getExecId());
					$bind = $bindDao->insert($bindObj);
				}
			}
		}

		foreach ($pathList as $path) {
			$rKey = $path->getTargetLang().'2'.$path->getSourceLang();
			if (array_key_exists($rKey, $revsIds)) {
				$path->setRevsPathId($revsIds[$rKey]);
				$pathDao->update($path->getPathId(), $path);
			}
		}

		$adminDDSettings = $ddSetDao->queryBySetIdUserId($adminSets[0]->getSetId(), 1);
		foreach ($adminDDSettings as $ddSettingObj) {
			$adminDDBinds = $ddBindDao->queryBySettingId($ddSettingObj->getSettingId());
			$ddSetting = $ddSetDao->insert($setId, $userId);
			foreach ($adminDDBinds as $ddBindObj) {
				$ddBind = $ddBindDao->insert($ddSetting->getSettingId(), $ddBindObj->getBindId(), $ddBindObj->getBindType(), $ddBindObj->getBindValue());
			}
		}
    }
}
?>
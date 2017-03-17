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
/* $Id: TranslationPathSettingAdapter.class.php 5919 2011-08-01 04:36:25Z mtanaka $ */

/*
 * /dao/ServiceGridTranslationServiceSettingのアダプタ
 * @リファクタリング前のPathSettingWrapperClassに相当
 */

// 今のところ使用していない
class BindType {
	const TRANSLATOR = '0';
	const GLOBAL_DICTIONARY = '1';
	const LOCAL_DICTIONARY = '2';
	const TEMPORAL_DICTIONARY = '3';
	const PARALLEL_TEXT = '4';
	const TRANSLATOIN_TEMPLATE = '5';
	const SIMILARITY_CALCULATION = '6';
}

require_once(XOOPS_ROOT_PATH.'/service_grid/db/adapter/DaoAdapter.class.php');
//require_once(XOOPS_ROOT_PATH.'/service_grid/db/dao/ServiceGridTranslationServiceSetting.class.php');
//↑怪しいから使わない。
require_once(dirname(__FILE__).'/../utils/PathDataConvertUtils.class.php');
require_once(XOOPS_ROOT_PATH.'/modules/dictionary/services/defines.php');

class TranslationPathSettingAdapter {

//	private $mSettingClass = null;
	private $mTranslationSetDaoImpl = null;

    public function __construct() {
//    	$this->mSettingClass = new ServiceGridTranslationServiceSetting();
    	$this->mTranslationSetDaoImpl = DaoAdapter::getAdapter()->getTranslationSetDao();
    }

    public function loadTranslationSetting($bindingSetName, $userId) {
		debugLog('bindingSetName = '.$bindingSetName);
	 
		$setId = $this->_getSetId($bindingSetName, $userId);
		if ($setId == null) {
			throw new TranslationPathSettingAdapter_SetObjectNotFoundException(__METHOD__."($bindingSetName, $userId) is returned null.");
		}

		$settings = $this->_loadPathsExecsBinds($setId, $userId);
		if ($settings) {
			return PathDataConvertUtils::format($settings);
		}

		return array();		// 翻訳パス設定が無い.
    }

    public function saveTranslationSettings($bindingSetName, $userId, $data) {
    	$pathDao = DaoAdapter::getAdapter()->getTranslationPathDao();
    	$execDao = DaoAdapter::getAdapter()->getTranslationExecDao();
    	$bindDao = DaoAdapter::getAdapter()->getTranslationBindDao();

		$setId = $this->_getSetId($bindingSetName, $userId);
		if ($setId == null) {
			$setId = $this->createTranslationSetting($bindingSetName, $userId);
		}

		$isEntry = true;

		$fPathId = null;
		$rPathId = null;
		$fPath = null;
		$rPath = null;

		if ($data['id']) {
			$ids = explode(',', $data['id']);
			if (count($ids) == 2) {
				$fPathId = $ids[0];
				$rPathId = $ids[1];
				$fPath = $pathDao->queryByPathId($fPathId);
				$rPath = $pathDao->queryByPathId($rPathId);
			} else {
				$fPathId = $ids[0];
				$fPath = $pathDao->queryByPathId($fPathId);
			}
		}

		if ($fPathId) {
			$this->clearExecsBinds($fPathId);
		}
		if ($rPathId) {
			$this->clearExecsBinds($rPathId);
		}

		if ($data['isDelete'] == 'yes') {
			if ($fPathId) {
				$this->clearPath($fPathId);
			}
			if ($rPathId) {
				$this->clearPath($rPathId);
			}
			return array();
		}

		$dataLangs = $this->_getPostByLanguages($data);
		$dataService = $this->_getPostByService($data);

		if ($fPath == null) {
			$fPath = $this->addPath($setId, $userId, $dataLangs['forward']['sourceLang'], $dataLangs['forward']['targetLang'], null);
			$fPathId = $fPath->getPathId();
		} else {
			$fPath->setSourceLang($dataLangs['forward']['sourceLang']);
			$fPath->setTargetLang($dataLangs['forward']['targetLang']);
			$fPath = $this->modifyPath($fPathId, $fPath);
		}
		foreach ($dataService['forward'] as $serviceData) {
			$execId = $serviceData['index'];
			
			if (count($serviceData['service']) == 1) {
				$service = $serviceData['service'][0];
			} else {
				//$service = 'DUMMY';
				$service = $serviceData['service'][0];
			}
			
			$exec = $this->addExec($fPathId, $execId, $execId, $serviceData['sourceLang'], $serviceData['targetLang']
				, $serviceData['serviceType'], $service, $serviceData['dictFlag']);
				
			$bindId = 1;
			foreach ($serviceData['dictionary'] as $dict) {
				$this->addBind($fPathId, $execId, $bindId++, $dict['type'], $dict['value']);
			}
			foreach ($serviceData['parallel'] as $value) {
				$this->addBind($fPathId, $execId, $bindId++, '4', $value);
			}
			foreach ($serviceData['translationTemplates'] as $value) {
				$this->addBind($fPathId, $execId, $bindId++, '5', $value);
			}
			$this->addBind($fPathId, $execId, $bindId++, '9', $serviceData['analyzer']);

			if ($serviceData['similarity']) {
				$this->addBind($fPathId, $execId, $bindId++, '6', $serviceData['similarity']);
			}
			
			foreach ($serviceData['service'] as $service) {
				$this->addBind($fPathId, $execId, $bindId++, '0', $service);
			}
		}

		if ($data['flow'] == 'both') {

			if ($rPath == null) {
				$rPath = $this->addPath($setId, $userId, $dataLangs['revers']['sourceLang'], $dataLangs['revers']['targetLang'], null);
				$rPathId = $rPath->getPathId();
			} else {
				$rPath->setSourceLang($dataLangs['revers']['sourceLang']);
				$rPath->setTargetLang($dataLangs['revers']['targetLang']);
				$rPath = $this->modifyPath($rPathId, $rPath);
			}
			foreach ($dataService['revers'] as $serviceData) {
				$execId = $serviceData['index'];
			
				if (count($serviceData['service']) == 1) {
					$service = $serviceData['service'][0];
				} else {
					//$service = 'DUMMY';
					$service = $serviceData['service'][0];
				}
				
				$exec = $this->addExec($rPathId, $execId, $execId, $serviceData['sourceLang'], $serviceData['targetLang']
					, $serviceData['serviceType'], $service, $serviceData['dictFlag']);
				$bindId = 1;
				foreach ($serviceData['dictionary'] as $dict) {
					$this->addBind($rPathId, $execId, $bindId++, $dict['type'], $dict['value']);
				}
				foreach ($serviceData['parallel'] as $value) {
					$this->addBind($rPathId, $execId, $bindId++, '4', $value);
				}
				foreach ($serviceData['translationTemplates'] as $value) {
					$this->addBind($rPathId, $execId, $bindId++, '5', $value);
				}
				$this->addBind($rPathId, $execId, $bindId++, '9', $serviceData['analyzer']);
				if ($serviceData['similarity']) {
					$this->addBind($rPathId, $execId, $bindId++, '6', $serviceData['similarity']);
				}

				foreach ($serviceData['service'] as $service) {
					$this->addBind($rPathId, $execId, $bindId++, '0', $service);
				}
			}

			$fPath->setRevsPathId($rPathId);
			$rPath->setRevsPathId($fPathId);
			$this->modifyPath($fPathId, $fPath);
			$this->modifyPath($rPathId, $rPath);

			$return = array($fPathId, $rPathId);
		} else {
			$fPath->setRevsPathId('0');
			$this->modifyPath($fPathId, $fPath);
			$return = array($fPathId);
		}

		return $return;

    }

	/**
	 * バインディングセットを新設（TranslationSetを追加）
	 * @param $bindingSetName
	 * @param $userId
	 */
    public function createTranslationSetting($bindingSetName, $userId, $sharedFlag = null) {
    	if (!$this->mTranslationSetDaoImpl->insertNew($bindingSetName, $userId, null, $sharedFlag)) {
			throw new TranslationPathSettingAdapter_InsertQueryException(__METHOD__."($bindingSetName, $userId) in insert query error.");
    	}
		return $this->_getSetId($bindingSetName, $userId);
    }

    /**
     * デフォルト辞書が更新された際、Path設定に反映する
     */
    public function updateDefaultDictionary($bindingSetName, $userId, $data) {
		$setId = $this->_getSetId($bindingSetName, $userId);
		if ($setId == null) {
			throw new TranslationPathSettingAdapterException('set_id is null.', __METHOD__, func_get_args());
		}
		$paths = $this->_loadPathsExecsBinds($setId, $userId);
		if ($paths == null) {
			return;
		}

		$dictArray = $this->_parseDictionaryData($data, '_ids');

		foreach ($paths as $path) {
			foreach ($path->getTranslationExecs() as $exec) {
				switch ($exec->getDictionaryFlag()) {
					case 0:
					case 1:
						$bindId = $this->clearDictionaryBindByExecId($exec);
						foreach ($dictArray as $dict) {
							$this->addBind($exec->getPathId(), $exec->getExecId(), $bindId++, $dict['type'], $dict['value']);
						}
						break;
				}
			}
		}
    }

	/* SetIDを返す */
    private function _getSetId($bindingSetName, $userId) {
    	$setId = null;
//    	$sets = $this->mTranslationSetDaoImpl->queryBySetName($bindingSetName, $userId);
		$sets = $this->mTranslationSetDaoImpl->findByBindingSetNameAndUserId($bindingSetName, $userId);
    	if ($sets) {
    		$setId = $sets[0]->getSetId();
    	}
    	return $setId;
    }

	/* setId, userIdを条件にPath以下の階層データを取得 */
    private function _loadPathsExecsBinds($setId, $userId) {
    	$pathDao = DaoAdapter::getAdapter()->getTranslationPathDao();
    	$execDao = DaoAdapter::getAdapter()->getTranslationExecDao();
    	$bindDao = DaoAdapter::getAdapter()->getTranslationBindDao();
    	$lsDao = DaoAdapter::getAdapter()->getLangridServicesDao();

    	$paths = $pathDao->queryBySetId($userId, $setId);
    	if ($paths == null || is_array($paths) === false || count($paths) == 0) {
    		return null;
    	}
    	foreach ($paths as $path) {
    		$execs = $execDao->queryByPathId($path->getPathId());
			if ($execs == null || is_array($execs) === false || count($execs) == 0) {
				$execs = array();
			}
			foreach ($execs as $exec) {
				$impTrns = $lsDao->queryGetByEndPoint($exec->getServiceId(), 'IMPORTED', 'TRANSLATION');
				if ($impTrns != null && is_array($impTrns) && count($impTrns) > 0) {
					$exec->setServiceId($impTrns[0]->getServiceId());
					$exec->setServiceType('2');
				}
				$binds = $bindDao->queryByExecObject($exec);
				if ($binds == null || is_array($binds) === false || count($binds) == 0) {
					$binds = array();
				}
				foreach ($binds as $bind) {
                    if ($bind->getBindType() == '0') {
						$objs = $lsDao->queryGetByEndPoint($bind->getBindValue(), 'IMPORTED', 'TRANSLATION');
				    	if ($objs != null && is_array($objs) && count($objs) > 0) {
				    		$bind->setBindValue($objs[0]->getServiceId());
				    	}
                    }
					if ($bind->getBindType() == '2') {
						$objs = $lsDao->queryGetByEndPoint($bind->getBindValue(), 'IMPORTED', 'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH');
				    	if ($objs != null && is_array($objs) && count($objs) > 0) {
				    		$bind->setBindValue($objs[0]->getServiceId());
				    	} else {
				    		$bind->setBindValue(str_replace('_', ' ', str_replace(DICTIONARY_ENTPOINT_URL_BASE, '', $bind->getBindValue())));
				    	}
					}
				}
				$exec->setTranslationBinds($binds);
			}
    		$path->setTranslationExecs($execs);
    	}
    	return $paths;
    }

	/* 辞書のBind情報を削除 */
    private function clearDictionaryBindByExecId($exec) {
    	$bindDao = DaoAdapter::getAdapter()->getTranslationBindDao();
    	$binds = $bindDao->queryByExecObject($exec);
    	$noDictBinds = array();
    	foreach ($binds as $bind) {
    		switch ($bind->getBindType()) {
				case 1:
				case 2:
				case 3:
					break;
				default:
					$noDictBinds[] = $bind;
					break;
			}
			$bindDao->delete($bind->getPathId(), $bind->getExecId(), $bind->getBindId());
    	}
    	$bindId = 1;
    	foreach ($noDictBinds as $bind) {
    		$bind->setBindId($bindId++);
    		$bindDao->insert($bind);
    	}

    	return $bindId;
    }

    /* Exec,Bind情報を削除 */
    private function clearExecsBinds($pathId) {
		$execDao = DaoAdapter::getAdapter()->getTranslationExecDao();
		$bindDao = DaoAdapter::getAdapter()->getTranslationBindDao();

		$execs = $execDao->queryByPathId($pathId);
		foreach ($execs as $exec) {
			$binds = $bindDao->queryByExecObject($exec);
			foreach ($binds as $bind) {
				$bindDao->delete($bind->getPathId(), $bind->getExecId(), $bind->getBindId());
			}
			$execDao->delete($exec->getPathId(), $exec->getExecId());
		}
    }

    /* Path情報を削除 */
    private function clearPath($pathId) {
    	$pathDao = DaoAdapter::getAdapter()->getTranslationPathDao();
    	return $pathDao->delete($pathId);
    }

	/* Bind情報を登録 */
    private function addBind($pathId, $execId, $bindId, $bindType, $bindValue) {
    	$bindDao = DaoAdapter::getAdapter()->getTranslationBindDao();
        if ($bindType == '0') {
            $lsDao = DaoAdapter::getAdapter()->getLangridServicesDao();
            $objs = $lsDao->queryGetByServiceId($bindValue, 'IMPORTED', 'TRANSLATION');
            if ($objs != null && is_array($objs) && count($objs) > 0) {
                $bindValue = $objs[0]->getEndpointUrl();
            }
        }
    	if ($bindType == '2') {
	    	$lsDao = DaoAdapter::getAdapter()->getLangridServicesDao();
	    	$objs = $lsDao->queryGetByServiceId($bindValue, 'IMPORTED', 'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH');
	    	if ($objs != null && is_array($objs) && count($objs) > 0) {
	    		$bindValue = $objs[0]->getEndpointUrl();
	    	} else {
	    		$bindValue = DICTIONARY_ENTPOINT_URL_BASE.str_replace(' ', '_', $bindValue);
	    	}
    	}

    	$bind = new ServiceGridTranslationBind();
    	$bind->setPathId($pathId);
    	$bind->setExecId($execId);
    	$bind->setBindId($bindId);
    	$bind->setBindType($bindType);
    	$bind->setBindValue($bindValue);
    	return $bindDao->insert($bind);
    }

    /* Exec情報を登録 */
    private function addExec($pathId, $execId, $execOrder, $sourceLang, $targetLang, $serviceType, $serviceId, $dictionaryFlag) {
    	$execDao = DaoAdapter::getAdapter()->getTranslationExecDao();
    	$lsDao = DaoAdapter::getAdapter()->getLangridServicesDao();
		$objs = $lsDao->queryGetByServiceId($serviceId, 'IMPORTED', 'TRANSLATION');
    	if ($objs != null && is_array($objs) && count($objs) > 0) {
    		$serviceId = $objs[0]->getEndpointUrl();
    		$serviceType = '2';
    	}

    	$exec = new ServiceGridTranslationExec();
    	$exec->setPathId($pathId);
    	$exec->setExecId($execId);
    	$exec->setExecOrder($execOrder);
    	$exec->setSourceLang($sourceLang);
    	$exec->setTargetLang($targetLang);
    	$exec->setServiceType($serviceType);
    	$exec->setServiceId($serviceId);
    	$exec->setDictionaryFlag($dictionaryFlag);
    	return $execDao->insert($exec);
    }

	/* Path情報を登録 */
    private function addPath($setId, $userId, $sourceLang, $targetLang, $revsPathId = null) {
    	$pathDao = DaoAdapter::getAdapter()->getTranslationPathDao();
    	$path = new ServiceGridTranslationPath();
    	$path->setSetId($setId);
    	$path->setUserId($userId);
    	$path->setSourceLang($sourceLang);
    	$path->setTargetLang($targetLang);
    	if ($revsPathId) {
    		$path->setRevsPathId($revsPathId);
    	}
    	return $pathDao->insert($path);
    }

	/* Path情報を更新 */
    private function modifyPath($pathId, $path) {
    	$pathDao = DaoAdapter::getAdapter()->getTranslationPathDao();
    	return $pathDao->update($pathId, $path);
    }

	/* デフォルト辞書のデータ加工 */
    private function _parseDictionaryData($data, $keySuffix = '') {
		$list = array();
		foreach (explode(',', $data['global_dict'.$keySuffix]) as $a) {
			if ($a) {
				$list[] = array('type'=>'1', 'value'=>$a);
			}
		}
		foreach (explode(',', $data['local_dict'.$keySuffix]) as $a) {
			if ($a) {
				$list[] = array('type'=>'2', 'value'=>$a);
			}
		}
		$tmp = isset($data['user_dict'.$keySuffix]) ? $data['user_dict'.$keySuffix] : $data['temp_dict'.$keySuffix];
		foreach (explode(',', $tmp) as $a) {
			if ($a) {
				$list[] = array('type'=>'3', 'value'=>$a);
			}
		}
		return $list;
    }

    private function _getPostByLanguages($data) {

    	$forward = array(
    		'sourceLang' => '',
    		'targetLang' => '',
    	);
    	$revers = array(
    		'sourceLang' => '',
    		'targetLang' => '',
    	);

		$forward['sourceLang'] = $data['lang1'];
		$revers['targetLang'] = $data['lang1'];
    	if ($data['service3']) {
    		$forward['targetLang'] = $data['lang4'];
    		$revers['sourceLang'] = $data['lang4'];
    	} else if ($data['service2']) {
    		$forward['targetLang'] = $data['lang3'];
    		$revers['sourceLang'] = $data['lang3'];
    	} else {
    		$forward['targetLang'] = $data['lang2'];
    		$revers['sourceLang'] = $data['lang2'];
    	}

    	return array('forward' => $forward, 'revers' => $revers);
    }

	private function _parseParallelTextData($data) {
		$return = array();
		foreach (explode(',', urldecode($data)) as $d) {
			if ($d) {
				$return[] = $d;
			}
		}
		return $return;
	}

	/*
	 * PostデータからPath>Exec>Bindの情報を抜き出す
	 */
    private function _getPostByService($data) {
		debugLog("### Setting data start ###");
		debugLog(print_r($data, 1));
		debugLog("### Setting data end ###");
		
    	$forward = array();
    	$revers = array();

		$dict1 = $this->_parseDictionaryData($data, '_1');
		$dict2 = $this->_parseDictionaryData($data, '_2');
		$dict3 = $this->_parseDictionaryData($data, '_3');

		$parallel1 = $this->_parseParallelTextData($data['parallel1']);
		$parallel2 = $this->_parseParallelTextData($data['parallel2']);
		$parallel3 = $this->_parseParallelTextData($data['parallel3']);

		$translationTemplates1 = $this->_parseParallelTextData($data['translationTemplates1']);
		$translationTemplates2 = $this->_parseParallelTextData($data['translationTemplates2']);
		$translationTemplates3 = $this->_parseParallelTextData($data['translationTemplates3']);

    	if ($data['service1']) {
    		$forward['service1'] = array(
    			'index' => '1',
    			'service' => explode(',', urldecode($data['service1'])),
    			'serviceType' => '0',	// exec::service_type
    			'dictFlag' => $data['dict_flag_1'],
    			'sourceLang' => $data['lang1'],
    			'targetLang' => $data['lang2'],
    			'dictionary' => $dict1,
    			'analyzer' => $data['morph_analyzer1'],
    			'similarity' => $data['similarity1'],
    			'parallel' => $parallel1,
    			'translationTemplates' => $translationTemplates1
    		);
    	}

    	if ($data['service2']) {
    		$forward['service2'] = array(
    			'index' => '2',
    			'service' => explode(',', urldecode($data['service2'])),
    			'serviceType' => '0',	// exec::service_type
    			'dictFlag' => $data['dict_flag_2'],
    			'sourceLang' => $data['lang2'],
    			'targetLang' => $data['lang3'],
    			'dictionary' => $dict2,
    			'analyzer' => $data['morph_analyzer2'],
    			'similarity' => $data['similarity2'],
    			'parallel' => $parallel2,
    			'translationTemplates' => $translationTemplates2
    		);
    	}

    	if ($data['service3']) {
    		$forward['service3'] = array(
    			'index' => '3',
    			'service' => explode(',', urldecode($data['service3'])),
    			'serviceType' => '0',	// exec::service_type
    			'dictFlag' => $data['dict_flag_3'],
    			'sourceLang' => $data['lang3'],
    			'targetLang' => $data['lang4'],
    			'dictionary' => $dict3,
    			'analyzer' => $data['morph_analyzer3'],
    			'similarity' => $data['similarity3'],
    			'parallel' => $parallel3,
    			'translationTemplates' => $translationTemplates3
    		);
    	}

		if ($data['service3']) {
			$revers['service1'] = array(
    			'index' => '1',
    			'service' => explode(',', urldecode($data['service3'])),
    			'serviceType' => '0',	// exec::service_type
    			'dictFlag' => $data['dict_flag_3'],
    			'sourceLang' => $data['lang4'],
    			'targetLang' => $data['lang3'],
    			'dictionary' => $dict3,
    			'analyzer' => $data['morph_analyzer4'],
    			'similarity' => $data['similarity3'],
    			'parallel' => $parallel3,
    			'translationTemplates' => $translationTemplates3
			);
			$revers['service2'] = array(
    			'index' => '2',
    			'service' => explode(',', urldecode($data['service2'])),
    			'serviceType' => '0',	// exec::service_type
    			'dictFlag' => $data['dict_flag_2'],
    			'sourceLang' => $data['lang3'],
    			'targetLang' => $data['lang2'],
    			'dictionary' => $dict2,
    			'analyzer' => $data['morph_analyzer3'],
    			'similarity' => $data['similarity2'],
    			'parallel' => $parallel2,
    			'translationTemplates' => $translationTemplates2
			);
			$revers['service3'] = array(
    			'index' => '3',
    			'service' => explode(',', urldecode($data['service1'])),
    			'serviceType' => '0',	// exec::service_type
    			'dictFlag' => $data['dict_flag_1'],
    			'sourceLang' => $data['lang2'],
    			'targetLang' => $data['lang1'],
    			'dictionary' => $dict1,
    			'analyzer' => $data['morph_analyzer2'],
    			'similarity' => $data['similarity1'],
    			'parallel' => $parallel1,
    			'translationTemplates' => $translationTemplates1
			);
		} else if ($data['service2']) {
			$revers['service1'] = array(
    			'index' => '1',
    			'service' => explode(',', urldecode($data['service2'])),
    			'serviceType' => '0',	// exec::service_type
    			'dictFlag' => $data['dict_flag_2'],
    			'sourceLang' => $data['lang3'],
    			'targetLang' => $data['lang2'],
    			'dictionary' => $dict2,
    			'analyzer' => $data['morph_analyzer3'],
    			'similarity' => $data['similarity2'],
    			'parallel' => $parallel2,
    			'translationTemplates' => $translationTemplates2
			);
			$revers['service2'] = array(
    			'index' => '2',
    			'service' => explode(',', urldecode($data['service1'])),
    			'serviceType' => '0',	// exec::service_type
    			'dictFlag' => $data['dict_flag_1'],
    			'sourceLang' => $data['lang2'],
    			'targetLang' => $data['lang1'],
    			'dictionary' => $dict1,
    			'analyzer' => $data['morph_analyzer2'],
    			'similarity' => $data['similarity1'],
    			'parallel' => $parallel1,
    			'translationTemplates' => $translationTemplates1
			);
		} else {
			$revers['service1'] = array(
    			'index' => '1',
    			'service' => explode(',', urldecode($data['service1'])),
    			'serviceType' => '0',	// exec::service_type
    			'dictFlag' => $data['dict_flag_1'],
    			'sourceLang' => $data['lang2'],
    			'targetLang' => $data['lang1'],
    			'dictionary' => $dict1,
    			'analyzer' => $data['morph_analyzer2'],
    			'similarity' => $data['similarity1'],
    			'parallel' => $parallel1,
    			'translationTemplates' => $translationTemplates1
			);
		}

    	return array('forward' => $forward, 'revers' => $revers);
    }
}


class TranslationPathSettingAdapter_SetObjectNotFoundException extends Exception {
}
class TranslationPathSettingAdapter_InsertQueryException extends Exception {
}


class TranslationPathSettingAdapterException extends Exception {
	public function __construct($message, $method, $arguments) {
		parent::__construct($method.'('.print_r($arguments).') '.$message);
	}
}
?>

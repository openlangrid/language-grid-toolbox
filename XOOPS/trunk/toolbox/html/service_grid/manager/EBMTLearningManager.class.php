<?php

require_once(dirname(__FILE__).'/../../service_grid/db/adapter/DaoAdapter.class.php');
require_once(dirname(__FILE__).'/../../service_grid/ServiceGridClient.class.php');
require_once(dirname(__FILE__).'/../../service_grid/client/example_based_translator/ExampleBasedTranslator.class.php');

/* $Id: EBMTLearningManager.class.php 5146 2011-01-25 13:24:19Z Masaaki Kamiya $ */

class EBMTLearningManager {

	private $mEbmtLearningDao = null;

    public function __construct() {
		$this->mEbmtLearningDao = DaoAdapter::getAdapter()->getServiceGridEbmtLearningDaoImpl();
    }

	/**
	 * <#if locale="ja">
	 * 指定した条件のトークンを返す。
	 * @param $ebmtServiceId string
	 * @param $resourceId int USER_DICTIONARY::USER_DICTIONARY_ID
	 * @param $sourceLang string
	 * @param $targetLang string
	 * </#if>
	 */
    public function getToken($ebmtServiceId, $resourceId, $sourceLang, $targetLang) {
		return '1df746e368e793e9302e41e3c646d2d1';
//		$token = $this->mEbmtLearningDao->queryForSearch($ebmtServiceId, $resourceId, $sourceLang, $targetLang);
//		if ($token) {
//			return $token;
//		}
//		return false;
    }

	/**
	 * <#if locale="ja">
	 * 学習を予約する。
	 * @param $resourceName 用例対訳の資源名
	 * </#if>
	 */
	public function reservationLearning($resourceName) {
		$resourceHeader = $this->getResourceHeader($resourceName);
		$ebmtServices = $this->getEbmtServices();
        $resourceId = $resourceHeader['resourceId'];
		$sourceLangArray = $resourceHeader['languages'];
		$targetLangArray = $resourceHeader['languages'];

        $alreadyPair = array();

        // EBMTサービスID固定
        $ebmtServiceId = 'kyotou.langrid:KyotoEBMT-nlparser_KNP_EDICT';
//		foreach ($ebmtServices as $ebmt) {
			foreach ($sourceLangArray as $sourceLang) {
				foreach ($targetLangArray as $targetLang) {
					$pair = $sourceLang . '2' . $targetLang;
					if ($sourceLang != $targetLang && in_array($pair, $alreadyPair) == false) {
						$alreadyPair[] = $pair;
						$alreadyPair[] = $targetLang . '2' . $sourceLang;

						$vo = $this->mEbmtLearningDao->queryForSearch($ebmtServiceId, $resourceId, $sourceLang, $targetLang);
						
						if ($vo === false) {
							$vo = $this->mEbmtLearningDao->queryForSearch($ebmtServiceId, $resourceId, $targetLang, $sourceLang);
						}
						
						if ($vo !== false) {
							// 更新
							$vo->setStatus('NEW');
							$this->mEbmtLearningDao->update($vo);
						} else {
							// 新規
							$vo = new ServiceGridEbmtLearning();
							$vo->setEbmtService($ebmtServiceId);
							$vo->setUserDictionaryId($resourceId);
							$vo->setUserDictionaryName($resourceName);
							$vo->setSourceLang($sourceLang);
							$vo->setTargetLang($targetLang);
							$vo->setStatus('NEW');
							$this->mEbmtLearningDao->insert($vo);
						}
					}
				}
			}
//		}
	}

	/**
	 * <#if locale="ja">
	 * (予約された)学習する。
	 * </#if>
	 */
	public function learning() {
		$reservationObjects = $this->mEbmtLearningDao->queryFindByLearningTargets();
		debugLog('This line is learning target data(s) ,'.print_r($reservationObjects, true));
		foreach ($reservationObjects as $dto) {
			$token = $dto->getToken();
			$ebmtSoapClient = new ExampleBasedTranslatorImpl($dto->getEbmtService());
			if (!$token) {
				$createTokenResult = $ebmtSoapClient->createToken($dto->getSourceLang(), $dto->getTargetLang());
				debugLog('$createTokenResult = '.print_r($createTokenResult, true));
				if ($createTokenResult != null && $createTokenResult['status'] == 'OK') {
					$token = $createTokenResult['contents'];
				}
			}
// 2010.12.22 トークン発行だけで、学習はスキップ。。。
//			$statusResult = $ebmtSoapClient->getStatus($token);
//			debugLog('$statusResult = '.print_r($statusResult, true));
//			if ($this->_pollingEbmtStatus($ebmtSoapClient, $token)) {
//				$contents = $this->getResourceBodys($dto->getUserDictionaryId(), $dto->getUserDictionaryName());
//				debugLog(print_r($contents, 1));
//				$parallelTexts = $this->getMaterialParallelTexts($contents, $dto->getSourceLang(), $dto->getTargetLang());
//				$addParallelTextsResult = $ebmtSoapClient->addParallelText($token, $dto->getSourceLang(), $dto->getTargetLang(), $parallelTexts);
//				debugLog('$addParallelTextsResult = '.print_r($addParallelTextsResult, true));
//				if ($addParallelTextsResult != null && $addParallelTextsResult['status'] == 'OK') {
					$this->saveToken($dto, $token);
//				}
//			} else {
//				debugLog(sprintf('%sサービスの%sトークンのステータスがREADYになりませんでした.', $dto->getEbmtService(), $token));
//			}
		}
	}

	private function _pollingEbmtStatus($ebmtSoapClient, $token, $maxTrial = 10) {
		for ($i = 0; $i < $maxTrial; $i++) {
			$statusResult = $ebmtSoapClient->getStatus($token);
			debugLog('$statusResult = '.print_r($statusResult, true));
			if ($statusResult != null && $statusResult['status'] == 'OK') {
				$status = $statusResult['contents'];
				if ($status == 'READY') {
					return true;
				}
			}
			sleep(1);
		}
		return false;
	}

	private function getResourceHeader($resourceName) {
		$udDao = DaoAdapter::getAdapter()->getUserDictionaryDao();
		$udcDao = DaoAdapter::getAdapter()->getUserDictionaryContentsDao();
		$resourceId = $udDao->getUserDictionaryIdByName($resourceName);
		$resource = $udDao->get($resourceId, false);
		$languages = $udcDao->getLanguages($resourceId);
		return array(
			'resourceId' => $resource->get('user_dictionary_id'),
			'resourceName' => $resource->get('dictionary_name'),
			'languages' => $languages
		);
	}

	private function getResourceBodys($resourceId, $resourceName = null) {
//		$udDao = DaoAdapter::getAdapter()->getUserDictionaryDao();
		$udcDao = DaoAdapter::getAdapter()->getUserDictionaryContentsDao();
//		$resourceId = $udDao->getUserDictionaryIdByName($resourceName);
		$contents = $udcDao->getContents($resourceId);
		unset($contents['-1']);
		if ($resourceName && empty($contents)) {
			$contents = $this->getTranslationTemplateBodys($resourceName);
		}
		return $contents;
	}

	private function getTranslationTemplateBodys($resourceName) {
		require_once XOOPS_ROOT_PATH.'/api/class/client/TranslationTemplateClient.class.php';
		require_once XOOPS_ROOT_PATH.'/api/class/manager/translation_template/Toolbox_TranslationTemplate_RecordReadManager.class.php';
		$manager = new Toolbox_TranslationTemplate_RecordReadManager();
		$records = $manager->getAllRecords($resourceName);
		$return = array();
		foreach ($records as $record) {
			$row = array('row' => count($return) + 1);
			foreach ($record->expressions as $exp) {
				$row[$exp->language] = $exp->expression;
			}
			$return[] = $row;
		}
		return $return;
	}

	private function getMaterialParallelTexts($contents, $sourceLang, $targetLang) {
		$list = array();
		foreach ($contents as $record) {
			if (!isset($record[$sourceLang]) || !isset($record[$targetLang])) {
				break;
			}
			$srcWord = $record[$sourceLang];
			$tgtWord = $record[$targetLang];
			$list[] = array($srcWord, $tgtWord);
		}
		return $list;
	}

	private function getEbmtServices() {
		$dao = DaoAdapter::getAdapter()->getLangridServicesDao();
		$services = $dao->queryFindServicesByTypeAndProvisions('EXAMPLEBASEDMACHINETRANSLATION', 'CLIENT_CONTROL');
		return $services;
	}

//	private function saveToken($ebmtServiceId, $resourceId, $sourceLang, $targetLang, $token) {
//		$vo = $this->mEbmtLearningDao->queryForSearch($ebmtServiceId, $resourceId, $sourceLang, $targetLang);
//		if ($vo !== false) {
//			// 更新
//			$vo->setToken($token);
//			$vo->setStatus('READY');
//			$this->mEbmtLearningDao->update($vo);
//		}
//	}
	private function saveToken($dto, $token) {
		$dto->setToken($token);
		$dto->setStatus('READY');
		$this->mEbmtLearningDao->update($dto);
	}

	private function _asyncRequest($host, $port) {}
}
?>
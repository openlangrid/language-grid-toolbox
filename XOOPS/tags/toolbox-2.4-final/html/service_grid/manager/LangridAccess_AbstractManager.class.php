<?php
require_once(dirname(__FILE__).'/AbstractManager.class.php');
require_once(dirname(__FILE__).'/../handler/TranslationPathDbHandler.class.php');
abstract class LangridAccess_AbstractManager extends AbstractManager {
	/** This class is DB Wrapper */
	protected $ServiceSetting = null;
	public function __construct() {
		parent::__construct();
		$this->ServiceSetting =& new TranslationPathDbHandler();
	}

	/**
	 * <#if locale="en">
	 * The method returns a binding set ID from a binding set name
	 * <#elseif locale="ja">
	 * バインディングセット名からバインディングセットIDを取得する
	 * </#if>
	 */
	protected function getBindingSetIdByName($bindingSetName) {
		$handler =& /* @@@DBAccess@@@ */ $this->ServiceSetting->getSetHandler();
		$object =& $handler->getByName($bindingSetName);
		return $object == null ? null : $object->get('set_id');
	}

	/**
	 * <#if locale="en">
	 * The method transforms a DAO object of a binding set information into the object defined for API
	 * <#elseif locale="ja">
	 * バインディングセット情報のDAOオブジェクトをAPI定義のオブジェクトに変換
	 * </#if>
	 */
	protected function TranslationSetObject2ResponseVO($object) {
		$bindingSet =& new VO_LangridAccess_BindingSet();
		$bindingSet->name = $object->get('set_name');
		$bindingSet->bindingType = 'translation';
		$bindingSet->setType = $object->get('shared_flag') ? 'shared' : 'personal';
		return $bindingSet;
	}

	/**
	 * <#if locale="en">
	 * The method transforms a DAO object of translation settings into the object defined for API
	 * <#elseif locale="ja">
	 * 翻訳設定情報のDAOオブジェクトをAPI定義のオブジェクトに変換
	 * </#if>
	 */
	protected function TranslationPath2ResponseVO($pathObj) {
		$multihopTranslationBinding =& new VO_LangridAccess_MultihopTranslationBinding();
		$multihopTranslationBinding->id = $pathObj->get('path_id');

		$translationBindingAry = array();
		$langPathAry = array();
		$langPathAry[] = $pathObj->get('source_lang');
		$execObjAry =& $pathObj->getExecs();
		foreach ($execObjAry as $execObj) {
			$langPathAry[] = $execObj->get('target_lang');
			$translationBinding =& new VO_LangridAccess_TranslationBinding();
			$translationBinding->sourceLang = $execObj->get('source_lang');
			$translationBinding->targetLang = $execObj->get('target_lang');
			$translationBinding->translationServiceId = $execObj->get('service_id');

			$morAnaId = '';
			$globalDict = array();
			$localDict = array();
			$tempDict = array();
			$bindObjAry =& $execObj->getBinds();
			foreach ($bindObjAry as $bindObj) {
				$value = $bindObj->get('bind_value');
				switch ( $bindObj->get('bind_type') ) {
					case '1':
						$globalDict[] = $value;
						break;
					case '2':
						$name = $this->_convertEndPoint2LocalName($value);
						if ($name != null) {
							$localDict[] = $name;
						}
						break;
					case '3':
						$tempDict[] = $value;
						break;
					case '9':
						$morAnaId = $value;
						break;
					default:
						break;
				}
			}
			$translationBinding->morphologicalAnalysisServiceId = $morAnaId;
			$translationBinding->globalDictionaryServiceIds = $globalDict;
			$translationBinding->localDictionaryServiceIds = $localDict;
			$translationBinding->temporalDictionaryNames = $tempDict;

			$translationBindingAry[] = $translationBinding;
		}

		$multihopTranslationBinding->path = $langPathAry;
		$multihopTranslationBinding->translationBindings = $translationBindingAry;

		return $multihopTranslationBinding;
	}

	/**
	 * <#if locale="en">
	 * The method registers Exec and Bind specified by translation setting ID as an argument
	 * The translation setting specified by the second argument is assumes the object defined for API
	 * <#elseif locale="ja">
	 * 引数で指定された翻訳設定IDの実データ（ExecとBind）を連鎖的に登録する
	 * 第二引数で受け取る翻訳設定情報は、API定義のオブジェクトを想定
	 * </#if>
	 */
	protected function entryTranslationBindings($pathId, $translationBindings) {
		foreach ($translationBindings as $binding) {
			$sourceLang = $binding->sourceLang;
			$targetLang = $binding->targetLang;
			$serviceId = $binding->translationServiceId;

			$serviceType = $this->_isLangridTranslationService($serviceId) == true ? '0' : '1';
			$dictFlag = (
						count($binding->globalDictionaryServiceIds) +
						count($binding->localDictionaryServiceIds) +
						count($binding->temporalDictionaryNames)
						) == 0 ? '0' : '2';

			$execObj =& /* @@@DBAccess@@@ */ $this->ServiceSetting->addTranslationExec($pathId, $sourceLang, $targetLang, $serviceId, $serviceType, $dictFlag);

			$execId = $execObj->get('exec_id');

			/* @@@DBAccess@@@ */ $this->ServiceSetting->addTranslationBind($pathId, $execId, '9', $binding->morphologicalAnalysisServiceId);

			foreach ($binding->globalDictionaryServiceIds as $dict) {
				/* @@@DBAccess@@@ */ $this->ServiceSetting->addTranslationBind($pathId, $execId, '1', $dict);
			}
			foreach ($binding->localDictionaryServiceIds as $dict) {
				$endPoint = $this->_convertLocalName2EndPoint($dict);
				if ($endPoint != null) {
					/* @@@DBAccess@@@ */ $this->ServiceSetting->addTranslationBind($pathId, $execId, '2', $endPoint);
				}
			}
			foreach ($binding->temporalDictionaryNames as $dict) {
				/* @@@DBAccess@@@ */ $this->ServiceSetting->addTranslationBind($pathId, $execId, '3', $dict);
			}
		}
	}

	/**
	 * <#if locale="en">
	 * This method returns if the specified translation service ID is registered on the Language Grid or not.
	 * <#elseif locale="ja">
	 * 翻訳器サービスIDが言語グリッドに登録されているか否かを判定
	 * </#if>
	 */
	protected function _isLangridTranslationService($serviceId) {
		$dist =& $this->_getLangridService($serviceId, 'TRANSLATION');
		if ($dist == null) {
			return false;
		}
		return true;
	}

	/**
	 * <#if locale="en">
	 * This method returns the profile of the specified language service.
	 * <#elseif locale="ja">
	 * 言語サービスのプロファイル情報を返す
	 * </#if>
	 */
	protected function _getLangridService($serviceId, $type) {
		$handler =& /* @@@DBAccess@@@ */ $this->ServiceSetting->getLangridServiceHandler();
		$params = array();
		$params['service_id'] = $serviceId;
		$params['service_type'] = $type;
		$params['delete_flag'] = '0';
		$obj =& $handler->search($params);
		if ($obj == null || count($obj) == 0) {
			return null;
		}
		return $obj[0];
	}

	/**
	 * <#if locale="en">
	 * This method returns the endpoint URL from service ID of a local service.
	 * <#elseif locale="ja">
	 * ローカルサービスのサービスIDを元に、サービスのエンドポイントURLを求める
	 * </#if>
	 */
	protected function _convertLocalName2EndPoint($name) {
		return $name;
	}

	/**
	 * <#if locale="en">
	 * This method returns the service ID from the endpoint of a local service.
	 * <#elseif locale="ja">
	 * ローカルサービスのエンドポイントからサービスIDを求める
	 * </#if>
	 */
	protected function _convertEndPoint2LocalName($endPoint) {
		return $endPoint;
	}
}
?>
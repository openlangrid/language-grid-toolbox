<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once XOOPS_ROOT_PATH.'/modules/langrid/php/langrid-client.php';
require_once dirname(__FILE__).'/CTTranslatorClientFactory.php';

class CTLangridClient extends LangridClient {
	protected $params;
	protected $config;
	protected $pathObj;
	
 	function __construct($params = array(), $config = array()) {
 		parent::__construct($params, $config);
		$this->config = array_merge(array(
									'appName' => '__no_name__',
									'loginUserId' => getLoginUserUID(),
									'key01' => '0',
									'key02' => '0',
									'key03' => '0',
									'key04' => '0',
									'key05' => '0',
									'mtFlg' => '1',
									'note1' => 'NOTE-1_Default',
									'note2' => 'NOTE-2_Default')
			,$config);
 	}
	
	public function setParams($params = array()) {
		$this -> params = $params;
	}
	
	public function setPathObj($pathObj) {
		$this -> pathObj = $pathObj;
	}
	
	public function getPathObj() {
		return $this -> pathObj;
	}

	public function translate($source, $hasBackTrans = false) {
		$singleFlg = true;
		$tgts = array();
		if (is_array($this->params['targetLang'])) {
			$tgts = $this->params['targetLang'];
			$singleFlg = false;
		} else {
			$tgts = array($this->params['targetLang']);
		}
		$sources = array();
		if (is_array($source)) {
			$sources = $source;
			$singleFlg = false;
		} else {
			$sources = array($source);
		}
		
		if(isset($this->params['setId']) && $this->params['setId'] != null){
			$setID = $this->params['setId'];
		}else{
			$setID = null;
		}
		
		$src = $this->params['sourceLang'];
		$response = array();
		$status = 'OK';
		$lastMsg = 'cross translation succesed.';
		
		foreach ($sources as $text) {
			foreach ($tgts as $tgt) {
				if ($text == null || trim($text) == '') {
					$res = array('status' => 'WARNING', 'message'=>'source text paramter is empty.',
							'contents'=>array('targetLanguage'=>$tgt, 'targetText'>='source text paramter is empty.'));
				} else {
					$res = null;
					if(isset($this -> params['pathId'])) {
						$res = $this->doTranslate($text, $src, $tgt ,$setID , $this -> params['pathId']);
						
					} else {
						$res = $this->doTranslate($text, $src, $tgt ,$setID);	
					}

					if ($singleFlg && $hasBackTrans == false) {
						return $res;
					}
					if ($singleFlg) {
						return $res;
					}
				}
				$response['contents'][$tgt][] = $res;
			}
		}
		$response['status'] = $status;
		$response['message'] = $lastMsg;
		return $response;
	}
	
 	protected function doTranslate($source, $src, $tgt ,$setID = null, $pathId = null) {
		$client = $this->createClientWithSetId($src, $tgt ,$setID, $pathId);

		$return = $client->translate($source);
		$this->params['serviceId'] = $client->getServiceId();
		$this->params['bindingString'] = $client->getSoapBindings();
		$this->params['source'] = $source;
 		$logId = $this->_archive($return);
		$return['contents']['logId'] = $logId;
		return $return;
 	}
 	
	private function _archive($result, $config = null) {
		if ($config == null) {
			$config = $this->config;
		}
		$dao = new TranslationLogs();
		$insLogId = $dao->translateLog($this->params, $result, $this->config);
		return $insLogId;
	}
	
	
	protected function createClientWithSetId($sourceLang, $targetLang,$setId, $pathId = null, $uid = null) {
		
		if (is_null($this -> pathObj)) {
			// translation path not found. to NullTranslator created.
			require_once(dirname(__FILE__).'/NullTranslatorClient.class.php');
			$dist = new NullTranslatorClient($sourceLang, $targetLang);
			return $dist;
		}
		
		if (count($this -> pathObj->getExecs()) == 1) {
			// 1-pass
			$execObj = $this -> pathObj->getExecs();
//			if (count($execObj[0]->getBinds()) == 0) {
			if ($this->isNoBinding($this -> pathObj->getExecs())) {
				// Atomic
				require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/NoBindingTranslatorClient.class.php');
				$dist =& new NoBindingTranslatorClient($execObj[0]);
			} else {
				// Compsit
				require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/BindingTranslatorClient.class.php');
				$dist =& new BindingTranslatorClient($execObj[0]);
			}
		} else {
			// N-pass
			if ($this->isNoBinding($this -> pathObj->getExecs())) {
				require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/NoBindingTranslatorClient.class.php');
				$dist =& new NoBindingTranslatorClient_MultiHop($this -> pathObj->getExecs());
			} else {
				require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/service/translator/BindingTranslatorClient.class.php');
				$dist =& new BindingTranslatorClient_MultiHop($this -> pathObj->getExecs());
			}
		}
		
		return $dist;
	}
	
//	protected function getPathObject($sourceLang, $targetLang,$setId, $pathId, $uid) {
//		$file = XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/TranslationServiceSetting.class.php';
//		if (!file_exists($file)) {die('TranslationServiceSetting file not found.');}
//		require_once($file);
//		$setting =& new TranslationServiceSetting();
//		$translationPathAry =& $setting->getServiceSettings($uid, $setId, $sourceLang, $targetLang);
//		
//		if ($translationPathAry == null || count($translationPathAry) == 0) {
//			return null;
//		}
//		
//		if(!is_null($pathId)) {
//			foreach($translationPathAry as $path) {
//				if($path -> mVars['path_id']['value'] == $pathId) {
//					return $path;					
//				}
//			}
//		}
//		
//		return $translationPathAry[0];
//	}
	
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

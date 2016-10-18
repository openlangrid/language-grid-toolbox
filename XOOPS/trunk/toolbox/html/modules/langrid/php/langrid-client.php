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
// XOOPS
require_once(dirname(__FILE__).'/../../../mainfile.php');
require_once(dirname(__FILE__).'/service/translator/TranslatorClientFactory.class.php');
require_once(dirname(__FILE__).'/client/translation-logs.php');

if (file_exists(XOOPS_ROOT_PATH . '/modules/legacy/kernel/Legacy_LanguageManager.class.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/legacy/kernel/Legacy_LanguageManager.class.php';
}

class LangridClient {

	private $config = array();
	private $params = array();

 	function __construct($params = array(), $config = array()) {
 		global $xoopsUser;
 		$uid = $xoopsUser->getVar('uid');
 		$this->params = array_merge(array('dictIds' => array()),$params);
		$this->config = array_merge(array(
									'appName' => '__no_name__',
									'loginUserId' => $uid,
									'key01' => '0',
									'key02' => '0',
									'key03' => '0',
									'key04' => '0',
									'key05' => '0',
									'mtFlg' => '1',
									'note1' => 'NOTE-1_Default',
									'note2' => 'NOTE-2_Default')
			,$config);

		// for language setup
		if (!defined("_MD_LANGRID_TAB1_NAME")) {
			$languageManager = new Legacy_LanguageManager();
			$languageManager->loadModuleMessageCatalog('langrid');
		}
 	}
 	function setParams($params) {
 		$this->params = $params;
 	}
 	function setSourceLanguage($sourceLang) {
 		$this->params['sourceLang'] = $sourceLang;
 	}
 	function setTargetLanguage($targetLang) {
 		$this->params['targetLang'] = $targetLang;
 	}
	function setSource($source) {
		$this->params['source'] = $source;
	}
	function setDictIds($ids) {
		$this->params['dictIds'] = $ids;
	}
	function setUserDictIds($ids) {
		$this->params['userDictIds'] = $ids;
	}

 	function setConfig($config) {
 		$this->config = array_merge($this->config, $config);
 	}
 	function setApplicationName($name) {
 		$this->config['appName'] = $name;
 	}
 	function setLoginUserId($loginUserId) {
 		$this->config['loginUserId'] = $loginUserId;
 	}
 	function setKey01($key01) {
 		$this->config['key01'] = $key01;
 	}
 	function setKey02($key02) {
 		$this->config['key02'] = $key02;
 	}
 	function setNote($note) {
 		$this->config['note'] = $note;
 	}
 	function setSetId($setId) {
 		$this->params['setId'] = $setId;
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
					$res = $this->doTranslate($text, $src, $tgt ,$setID);
					if ($singleFlg && $hasBackTrans == false) {
						return $res;
					}
					if ($hasBackTrans) {
						if ($res['status'] == 'OK') {
							$txt = $res['contents']['targetText']['contents'];
							$bak = $this->doTranslate($txt, $tgt, $src ,$setID);

							$res['back-translation'] = $bak;
						} else {
							$res['back-translation'] = array('status' => 'ERROR', 'message' => '', 'contents' => array( 'targetLanguage' => $src, 'targetText' =>array('status'=>'ERROR', 'contents'=>'')));
							$status = 'ERROR';
							$message = $res['message'];
						}
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

 	private function doTranslate($source, $src, $tgt ,$setID = null) {
		$factory = TranslatorClientFactory::getInstance();
		if($setID != null && $setID != ""){
			$client = $factory->createClientWithSetId($src, $tgt ,$setID);
		}else{
			$client = $factory->createClient($src, $tgt);
		}
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
 }
?>

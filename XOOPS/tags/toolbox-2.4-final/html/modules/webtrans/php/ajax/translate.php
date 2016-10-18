<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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
//require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/langrid-client.php');
require_once(dirname(__FILE__).'/../Validator.php');
require_once(dirname(__FILE__).'/../../language/' . $xoopsConfig['language'] . '/main.php');
require_once(XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php');
require_once(XOOPS_ROOT_PATH.'/api/class/client/extras/Develope_LangridAccessClient.class.php');
require_once(XOOPS_ROOT_PATH.'/api/class/manager/extras/Toolbox_Develope_LangridAccess_TranslationManager.class.php');

error_reporting(0);

header('Content-Type: application/json; charset=utf-8;');
$instance = new WebPageTranslationMultiPost();
echo $instance->translate();

class WebPageTranslationMultiPost
{
	function __construct() {
	}

	function translate(){
		$ret = $this->doTranslate();
		return json_encode($ret);
	}

	function doTranslate(){
		$id = json_decode($_POST['id']);
		try{
			if(!isset($_POST['sourceLang'])){
				throw new Exception("Paramater 'Source Language Code' is required.");
			}
			if(!isset($_POST['targetLang'])){
				throw new Exception("Paramater 'Target Language Code' is required.");
			}
			if(!isset($_POST['content'])){
				throw new Exception("Paramater 'Content' is required.");
			}

			$dSource = $_POST['content'];
			$dSourceLang = $_POST['sourceLang'];
			$dTargetLang = $_POST['targetLang'];
			if(get_magic_quotes_gpc()) {
				$dSource = stripslashes($dSource);
				$dSourceLang = stripslashes($dSourceLang);
				$dTargetLang = stripslashes($dTargetLang);
			}
			$sourceLang = str_replace("&amp;", "&", htmlspecialchars($dSourceLang));
			$targetLang = str_replace("&amp;", "&", htmlspecialchars($dTargetLang));

			$sourceArray = json_decode($dSource);

			$validater = new Validator();
			if(!$validater->validateSupportedSourceLanguage($sourceLang)){
				throw new Exception("No translator is assigned for this translation path.");
			}
			if(!$validater->validateSupportedTargetLanguage($targetLang)){
				throw new Exception("No translator is assigned for this translation path.");
			}
			if(count($sourceArray) == 0){
				throw new Exception("source is empty");
			}

			$langridAccessClient = new Develope_LangridAccessClient();
			$translateResult =
				$langridAccessClient->multisentenceBackTranslate($sourceLang, $targetLang, $sourceArray, 'USER', Toolbox_Develope_SourceTextJoinStrategyType::Normal);

			$contents = (array)$translateResult['contents'];

			$result = array(
				"id"=>$id,
				"status"=>$translateResult['status'],
				"message"=>$translateResult['message'],
				"targetText"=>$contents['intermediateResult'],
				"backText"=>$contents['targetResult']
			);

			$licenseArray = array();
			foreach ($contents['translationInvocationInfo'] as $serviceId => $info) {
				$licenseArray[$serviceId] = (array)$info;
			}

		}catch(Exception $e){
			$result = array();
			$result['id'] = $id;
			$result['status'] = 'ERROR';
			$result['translate'] = $e->getMessage();
			$result['backtranslate'] = $e->getMessage();
		}
		return array('results' => $result,'licenseInfo'=>$licenseArray);
	}
}
?>

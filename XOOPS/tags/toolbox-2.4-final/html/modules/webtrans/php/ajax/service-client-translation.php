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
require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/langrid-client.php');
require_once(dirname(__FILE__).'/../Validator.php');
require_once(dirname(__FILE__).'/../../language/' . $xoopsConfig['language'] . '/main.php');

$instance = new Translator();
echo $instance->translate();

class Translator {
	function __construct() {
	}

	function translate(){
		try{
			if(!isset($_POST['from'])){
				throw new Exception("Paramater 'Source Language Code' is required.");
			}
			if(!isset($_POST['to'])){
				throw new Exception("Paramater 'Target Language Code' is required.");
			}
			if(!isset($_POST['html'])){
				throw new Exception("Paramater 'Content' is required.");
			}

			$source = $_POST['html'];
			$dSourceLang = $_POST['from'];
			$dTargetLang = $_POST['to'];
			if(get_magic_quotes_gpc()) {
				$dSource = stripslashes($dSource);
				$dSourceLang = stripslashes($dSourceLang);
				$dTargetLang = stripslashes($dTargetLang);
			}
			$sourceLang = str_replace("&amp;", "&", htmlspecialchars($dSourceLang));
			$targetLang = str_replace("&amp;", "&", htmlspecialchars($dTargetLang));

			$validater = new Validator();
			if(!$validater->validateSupportedSourceLanguage($sourceLang)){
				throw new Exception("No translator is assigned for this translation path.");
			}
			if(!$validater->validateSupportedTargetLanguage($targetLang)){
				throw new Exception("No translator is assigned for this translation path.");
			}
			$client = new LangridClient(array(
				'sourceLang' => $sourceLang,
				'targetLang' => $targetLang
			));

			$result = array();
			$response = $client->translate($source);
			$status = "ERROR";
			$targetText = "";
			$backText = "";

			if($response == null || $response['status'] == 'ERROR'){
				$status = "ERROR";
				$targetText = "";
				$backText = "";
			}else{
				$targetText = $response['contents']['targetText']['contents'];

				if (isset($response['licenseInformation']) && is_array($response['licenseInformation'])) {
					$contents['licenseInformation'] = $response['licenseInformation'];
				}
				
				if (isset($_POST['backTranslate']) && $_POST['backTranslate']) {
					$client->setSourceLanguage($targetLang);
					$client->setTargetLanguage($sourceLang);
					$backResponse = $client->translate($targetText);
					
					if($backResponse == null || $backResponse['status'] == 'ERROR'){
						$status = "ERROR";
						$targetText = "";
						$backText = "";
					}else{
						$status = "OK";
						$backText = $backResponse['contents']['targetText']['contents'];

						if (is_array($contents['licenseInformation']) && isset($backResponse['licenseInformation']) && is_array($backResponse['licenseInformation'])) {
							$contents['licenseInformation'] = array_merge($contents['licenseInformation'], $backResponse['licenseInformation']);
						}
					}
				}else{
					$status = "OK";
					$backText = $targetText;
				}
			}
			
			$result = array(
				"id"=>$id,
				"status"=>$status,
				"targetText"=>$targetText,
				"backText"=>$backText
			);
		}catch(Exception $e){
			$result = array();
			$result['id'] = $id;
			$result['status'] = 'ERROR';
			$result['translate'] = $e->getMessage();
			$result['backtranslate'] = $e->getMessage();
		}
		return json_encode(array('results' => $result,'licenseInfo'=>$contents['licenseInformation']));
	}
}
?>

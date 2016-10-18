<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
error_reporting(0);
require_once('../../../../mainfile.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/langrid-client.php');
require_once('../Validator.php');
require_once('../../language/' . $xoopsConfig['language'] . '/main.php');
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

	$source = '';
	$dSource = $_POST['content'];
	$dSourceLang = $_POST['sourceLang'];
	$dTargetLang = $_POST['targetLang'];
	if(get_magic_quotes_gpc()) {
		$dSource = stripslashes($dSource);
		$dSourceLang = stripslashes($dSourceLang);
		$dTargetLang = stripslashes($dTargetLang);
	}
	$source = $dSource;
	$sourceLang = str_replace("&amp;", "&", htmlspecialchars($dSourceLang));
	$targetLang = str_replace("&amp;", "&", htmlspecialchars($dTargetLang));

	$validater = new Validator();
	if(!$validater->validateSupportedSourceLanguage($sourceLang)){
//		throw new Exception("Parameter 'Soruce Language Code' is not supported.");
		throw new Exception("No translator is assigned for this translation path.");
	}
	if(!$validater->validateSupportedTargetLanguage($targetLang)){
//		throw new Exception("Parameter 'Target Language Code' is not supported.");
		throw new Exception("No translator is assigned for this translation path.");
	}
	$client = new LangridClient(array(
			'sourceLang' => $sourceLang,
			'targetLang' => $targetLang
		));

	$response = $client->translate($source);
	$contents = array();
	if($response == null || $response['status'] == 'ERROR' && $response['contents']['targetText']['contents'] == null){
		throw new Exception(htmlspecialchars(_MI_VOICE_TOOL_ERROR_TIMEOUT_MESSAGE, ENT_QUOTES));
	}

	if(($response['status']) == 'ERROR' && trim(print_r($response['message'],true)) != trim('Translation successed.')){
		throw new Exception(htmlspecialchars(_MI_VOICE_NO_TRANSLATION, ENT_QUOTES));
	}

	$contents['translate'] = $response['contents']['targetText']['contents'];

	$contents['licenseInformation'] = array();
	if (isset($response['licenseInformation']) && is_array($response['licenseInformation'])) {
		$contents['licenseInformation'] = $response['licenseInformation'];
	}

	if (isset($_POST['backTranslate']) && $_POST['backTranslate']) {
		$client->setSourceLanguage($targetLang);
		$client->setTargetLanguage($sourceLang);
		$backResponse = $client->translate($contents['translate']);
		if($backResponse == null || $backResponse['status'] == 'ERROR' && $backResponse['contents']['targetText']['contents'] == null){
			throw new Exception(htmlspecialchars(_MI_VOICE_TOOL_ERROR_TIMEOUT_MESSAGE, ENT_QUOTES));
		}

		if(($backResponse['status']) == 'ERROR' && trim(print_r($backResponse['message'],true)) != trim('Translation successed.')){
			throw new Exception(htmlspecialchars(_MI_VOICE_NO_TRANSLATION, ENT_QUOTES));
		}

		$contents['backtranslate'] = $backResponse['contents']['targetText']['contents'];
		$contents['status'] = 'complete';
		if (is_array($contents['licenseInformation']) && isset($backResponse['licenseInformation']) && is_array($backResponse['licenseInformation'])) {
			$contents['licenseInformation'] = array_merge(
				$contents['licenseInformation']
				, $backResponse['licenseInformation']);
		}
	}
//	var_dump($contents);

	header('Content-Type: application/json; charset=utf-8;');
	echo json_encode(array($contents));
}catch(Exception $e){
	$contents = array();
	$contents['status'] = 'error';
	$contents['translate'] = $e->getMessage();
	$contents['backtranslate'] = $e->getMessage();
	echo json_encode(array($contents));
}
?>

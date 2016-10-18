<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
/**
 * This script does back translation by "Langrid Access API".
 * Response is JSON(translation and back translation result, used service information).
 */
error_reporting(0);
require_once('../../../../mainfile.php');
require_once('../Validator.php');
require_once(dirname(__FILE__)."/../../../../api/class/client/extras/Develope_LangridAccessClient.class.php");

try{
	// Validate request parameters
	if(!isset($_POST['sourceLang'])){
		throw new Exception("Paramater 'Source Language Code' is required.");
	}
	if(!isset($_POST['targetLang'])){
		throw new Exception("Paramater 'Target Language Code' is required.");
	}
	if(!isset($_POST['content'])){
		throw new Exception("Paramater 'Content' is required.");
	}

	$source = array();
	$dSource = $_POST['content'];
	$dSourceLang = $_POST['sourceLang'];
	$dTargetLang = $_POST['targetLang'];
	if(get_magic_quotes_gpc()) {
		for($i = 0; $i < count($dSource); $i++) {
			$dSource[$i] = stripslashes($dSource[$i]);
		}
		$dSourceLang = stripslashes($dSourceLang);
		$dTargetLang = stripslashes($dTargetLang);
	}
	$source = $dSource;
	$sourceLang = str_replace("&amp;", "&", htmlspecialchars($dSourceLang));
	$targetLang = str_replace("&amp;", "&", htmlspecialchars($dTargetLang));
	$validater = new Validator();
	if(!$validater->validateSupportedSourceLanguage($sourceLang)){
		throw new Exception("No translator is assigned for this translation path.");
	}
	if(!$validater->validateSupportedTargetLanguage($targetLang)){
		throw new Exception("No translator is assigned for this translation path.");
	}

	// Do back translation
	$responses = array();
	$client = new Develope_LangridAccessClient();
//	$response = $client->multisentenceBackTranslate(
//			$sourceLang, $targetLang, $source, "TEXT_TRANSLATION"
//			, Toolbox_Develope_SourceTextJoinStrategyType::Normal
//		);
// TODO:use langrid_config
	$response = $client->multisentenceBackTranslate(
			$sourceLang, $targetLang, $source, 'USER'
			, Toolbox_Develope_SourceTextJoinStrategyType::Normal
			, array('type' => $_POST['type'])
		);
	// check error
	if(strtoupper($response['status']) == 'ERROR' && trim(print_r($response['message'],true)) != trim('NoError')){
		throw new Exception(htmlspecialchars($response['message'], ENT_QUOTES));
	}
	if(! isset($response['contents'])){
		throw new Exception(htmlspecialchars(_MI_DOCUMENT_NO_TRANSLATION, ENT_QUOTES));
	}
	$contents['translate'] = $response['contents']->intermediateResult;
	if (isset($_POST['backTranslate']) && $_POST['backTranslate']) {
		$contents['backtranslate'] = $response['contents']->targetResult;
	}
	$contents['licenseInformation'] = array();
	if (isset($response['contents']->translationInvocationInfo) && is_array($response['contents']->translationInvocationInfo)) {
		foreach($response['contents']->translationInvocationInfo as $obj) {
			$contents['licenseInformation'][$obj->serviceName] = array(
					'serviceName' => $obj->serviceName
					, 'serviceCopyright' => $obj->copyright
					, 'serviceLicense' => $obj->license
					, 'errorMessage' => $obj->errorMessage
				);
		}
	}
	// for WARNING
	if ($response['status'] == 'WARNING') {
		$contents['status'] = 'warning';
	} else {
		$contents['status'] = 'complete';
	}
	// Send response
	header('Content-Type: application/json; charset=utf-8;');
	echo json_encode(array($contents));
}catch(Exception $e){
	// Create error response data
	$contents = array();
	$contents['status'] = 'error';
	$contents['translate'] = $e->getMessage();
	$contents['backtranslate'] = $e->getMessage();
	echo json_encode(array($contents));
}
?>

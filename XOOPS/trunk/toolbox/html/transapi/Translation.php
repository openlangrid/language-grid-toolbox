<?php
require_once "Auth.php";
require_once XOOPS_ROOT_PATH."/class/xoopsuser.php";
require_once(XOOPS_ROOT_PATH."/api/class/client/extras/Develope_LangridAccessClient.class.php");

function translate($sourceLang, $targetLang, $sourceText) {
	$uid = getCurrentUserId();

	$root = XCube_Root::getSingleton();
	$root->mContext->mXoopsUser = new XoopsUser($uid);

	$responses = array();
	$client = new Develope_LangridAccessClient();

	$response = $client->multisentenceTranslate(
			$sourceLang, $targetLang, array($sourceText), 'USER'
			, Toolbox_Develope_SourceTextJoinStrategyType::Normal
			, array('type' => 'normal')
		);
	// check error
	if(strtoupper($response['status']) == 'ERROR' && trim(print_r($response['message'],true)) != trim('NoError')){
		throw new Exception(htmlspecialchars($response['message'], ENT_QUOTES));
	}
	if(! isset($response['contents'])){
		throw new Exception(htmlspecialchars(_MI_DOCUMENT_NO_TRANSLATION, ENT_QUOTES));
	}
	$contents['translate'] = $response['contents'][0]->result;

	// for WARNING
	if ($response['status'] == 'WARNING') {
		$contents['status'] = 'warning';
	} else {
		$contents['status'] = 'complete';
	}

	// Send response
	header("Content-Type: text/xml; charset=utf-8");
	return $contents['translate'][0];
}

//ini_set("soap.wsdl_cache_enabled", "0");

$server = new SoapServer('Translation.wsdl');
$server->addFunction("translate");

try {
	$server->handle();
}
catch (Exception $e) {
	$server->fault('Sender', $e->getMessage());
}




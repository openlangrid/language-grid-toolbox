<?php
error_reporting(0);
require_once('../../../../mainfile.php');
require_once('./TextToSpeechClient.class.php');

$lang = $_POST['lang'];
$sentence = $_POST['sentence'];

if($lang != 'en' &&
	$lang != 'ja' &&
	$lang != 'zh') {
	
	// Create error response data
	$contents = array();
	$contents['status'] = 'error';
	$contents['data'] = 'Unsupported Language:' . $lang;
	echo json_encode(array($contents));
	
	return;
}

try {
	$client = new TextToSpeechClient();

	
	$params = array(
		'language'=>$lang,
		'text'=>stripslashes($sentence),
		'voiceType'=>'woman',
	 	'audioType'=>'audio/x-wav'
	);


	$result=$client->getVoice('speak', $params);
	
	
	$contents = array();
	$contents['status'] = 'complete';
	$contents['data'] = $result;
	
	echo json_encode(array($contents));

}catch(Exception $e){
	// Create error response data
	$contents = array();
	$contents['status'] = 'error';
	$contents['data'] = $e->getMessage();
	echo json_encode(array($contents));
}
	

?>
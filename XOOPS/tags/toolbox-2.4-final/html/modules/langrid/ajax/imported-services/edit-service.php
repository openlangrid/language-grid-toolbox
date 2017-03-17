<?php

require_once dirname(__FILE__).'/../../class/imported-services.php';

$return = array(
	'status' => 'OK',
	'message' => 'success',
	'contents' => array(
		'service' => array()
	)
);

$importedServices = new ImportedServices();
try {
//	$root = XCube_Root::getSingleton();
//	if (!$root->mContext->mXoopsUser->isAdmin()) {
//		throw new Exception();
//	}
	$service = $importedServices->editService(
		$_POST['serviceId'], $_POST['endpointUrl'], $_POST['languages']
		, $_POST['provider'], $_POST['copyright'], $_POST['license']);
	$return['contents']['service'] = $service;
} catch (SQLException $e) {
	$return = array(
		'status' => 'ERROR',
		'message' => 'Internal error.',
		'contents' => array(
			'service' => array()
		)
	);
} catch (IllegalArgumentException $e) {
//	echo $e->message;
	$return = array(
		'status' => 'ERROR',
		'message' => 'Internal error.',
		'contents' => array(
			'service' => array(),
			'exception' => $e
		)
	);
} catch (Exception $e) {
//	echo $e->message;
	$return = array(
		'status' => 'ERROR',
		'message' => $e->getMessage(),
		'contents' => array(
			'service' => array(),
			'exception' => $e
		)
	);
}
echo json_encode($return);
die();
//$return = array(
//	'status' => 'OK',
//	'message' => 'success',
//	'contents' => array(
//		'service' => array(
//				'id' => 'id3',
//				'name' => 'Name3 edit',
//				'type' => 'DICTIONARY',
//				'endpointUrl' => 'http://www.msn.com/',
//				'provider' => 'Provider3',
//				'copyright' => 'copyright3',
//				'license' => 'license3',
//				'languages' => array(
//					array(
//						'code' => 'ja',
//						'name' => 'Japanese'
//					),
//					array(
//						'code' => 'en',
//						'name' => 'English'
//					),
//					array(
//						'code' => 'zh',
//						'name' => 'Chinese'
//					)
//				)
//		)
//	)
//);
//
//echo json_encode($return);
?>
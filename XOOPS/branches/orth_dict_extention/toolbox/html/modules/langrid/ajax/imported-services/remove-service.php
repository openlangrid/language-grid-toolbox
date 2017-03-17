<?php
require_once dirname(__FILE__).'/../../class/imported-services.php';

$return = array(
	'status' => 'OK',
	'message' => 'success',
	'contents' => array(
		'service' => array(
			'id' => $_POST['serviceId']
		)
	)
);

$importedServices = new ImportedServices();
try {
//	$root = XCube_Root::getSingleton();
//	if (!$root->mContext->mXoopsUser->isAdmin()) {
//		throw new Exception();
//	}
	$importedServices->removeService($_POST['serviceId']);
} catch (SQLException $e) {
//	echo $e->message;
	$return = array(
		'status' => 'ERROR',
		'message' => 'error',
		'service' => array(
			'id' => $_POST['serviceId']
		)
	);
} catch (IllegalArgumentException $e) {
//	echo $e->message;
	$return = array(
		'status' => 'ERROR',
		'message' => 'error',
		'service' => array(
			'id' => $_POST['serviceId']
		)
	);
} catch (Exception $e) {
//	echo $e->message;
	$return = array(
		'status' => 'ERROR',
		'message' => 'error',
		'service' => array(
			'id' => $_POST['serviceId']
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
<?php

require_once dirname(__FILE__).'/../../class/imported-services.php';

$importedServices = new ImportedServices();
$languages = $importedServices->getSupportedLanguages();

$return = array(
	'status' => 'OK'
	, 'message' => 'success'
	, 'contents' => array(
		'languages' => $languages
	)
);
echo json_encode($return);

//$return = array(
//	'status' => 'OK',
//	'message' => 'success',
//	'contents' => array(
//		'languages' => array(
//			array(
//				'code' => 'ja',
//				'name' => 'Japanese'
//			),
//			array(
//				'code' => 'en',
//				'name' => 'English'
//			),
//			array(
//				'code' => 'zh',
//				'name' => 'Chinese'
//			),
//			array(
//				'code' => 'ko',
//				'name' => 'Korean'
//			),
//			array(
//				'code' => 'fr',
//				'name' => 'France'
//			)
//		)
//	)
//);
//
//echo json_encode($return);
?>
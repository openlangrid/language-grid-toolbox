<?php
	 /* Services
	 *
	 * status :
	 * message :
	 * contents : {
	 * 	services : [{
	 * 		id :
	 * 		name :
	 * 		type :
	 * 		endpointUrl : ''
	 * 		provider : ''
	 * 		copyright : ''
	 * 		license : ''
	 * 		languagePaths : [
	 * 			{
	 * 				from : {name : Japanese, code : ja},
	 * 				to : {name : English, code : en},
	 * 				bidirectional : true or false
	 * 			}
	 * 		]
	 * 		languages : [
	 * 			{
	 * 				code : ja,
	 * 				name : Japanese
	 * 			},
	 * 		]
	 * }]
	 * }
	 */
?>
<?php
require_once dirname(__FILE__).'/../../class/imported-services.php';

$importedServices = new ImportedServices();
$services = $importedServices->loadServices();

$return = array(
	'status' => 'OK'
	, 'message' => 'success'
	, 'contents' => array(
		'services' => $services
	)
);
//$return = array(
//	'status' => 'OK',
//	'message' => 'success',
//	'contents' => array(
//		'services' => array(
//			array(
//				'id' => 'id1',
//				'name' => 'Name1',
//				'type' => 'TRANSLATOR',
//				'endpointUrl' => 'http://www.google.com/',
//				'provider' => 'Provider1',
//				'copyright' => 'copyright1',
//				'license' => 'license1',
//				'registrationDate' => '2009-10-11',
//				'languagePaths' => array(
//					array(
//						'from' => array(
//							'code' => 'ja',
//							'name' => 'Japanese'
//						),
//						'to' => array(
//							'code' => 'en',
//							'name' => 'English'
//						),
//						'bidirectional' => true
//					),
//					array(
//						'from' => array(
//							'code' => 'ja',
//							'name' => 'Japanese'
//						),
//						'to' => array(
//							'code' => 'ko',
//							'name' => 'Korean'
//						),
//						'bidirectional' => false
//					),
//					array(
//						'from' => array(
//							'code' => 'zh',
//							'name' => 'Chinese'
//						),
//						'to' => array(
//							'code' => 'en',
//							'name' => 'English'
//						),
//						'bidirectional' => true
//					)
//				)
//			),
//			array(
//				'id' => 'id2',
//				'name' => 'Name2',
//				'type' => 'DICTIONARY',
//				'endpointUrl' => 'http://www.yahoo.com/',
//				'provider' => 'Provider2',
//				'copyright' => 'copyright2',
//				'license' => 'license2',
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
//			)
//		)
//	)
//);
//
echo json_encode($return);
?>
<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/work_document.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/common_util.php';
require_once XOOPS_ROOT_PATH.'/api/class/client/FileSharingClient.class.php';

echo '<p>WorkDocument Test start</p>';

try{

echo '<p>---------------------------- Test createFromParams() ---------------------</p>' . PHP_EOL;

$doc = WorkDocument::createFromParams(array(
	'source_lang' => 'ja', 
	'target_lang' => 'en', 
	'creator' => getLoginUserUID(),
	'file_name' => 'collabtrans_test',
	'folderId' => '1',
	'permission' => 0,
	'sentence' => array(
		'1' => array(
			'source' => 'abcde',
			'target' => 'あいうえお',
			'work_status' => 'working'
		),
		'2' => array(
			'source' => 'abcde',
			'target' => 'あいうえお',
			'work_status' => 'working'
		)
	),
	'histories' => array(
		"1" => array(
			'source' => 'インフォニック',
			'target' => 'infonic',
			'status' => '作業中',
			'loginId' => 'toolbox',
			'create_date' => '1266217121320'
		)
	)
));
$doc -> save();


//$doc2 = WorkDocument::findById($doc -> getId());
//
//assertEquals($doc -> getId(), $doc2 -> getId());
//assertEquals($doc -> getSourceLanguage(), $doc2 -> getSourceLanguage());
//assertEquals($doc -> getTargetLanguage(), $doc2 -> getTargetLanguage());
//assertEquals($doc -> getUID(), $doc2 -> getUID());
//assertEquals($doc -> getUserName(), $doc2 -> getUserName());
//
//$doc -> update();
//
//$doc -> update();
//
//$histories = $doc -> getHistories();
//assertEquals(3, count($histories));
//
//$doc -> insert();
//$doc -> insert();
//$doc -> insert();
//
//echo '<p>---------------------------- Test update() ---------------------</p>' . PHP_EOL;
//$doc -> setAttributes(array(
//	'source_lang' => 'ja', 
//	'target_lang' => 'en',
//	'sentence' => array(
//		'1' => array(
//			'source' => 'gggggg',
//			'target' => 'あいうえお',
//			'work_status' => 'working'
//		)
//	)
//));
//$doc -> update();
//assertEquals(1, count($doc -> getSentences()));
//$sentences = $doc -> getSentences();
//assertEquals('gggggg', $sentences[0] -> getSource());
//
//
//echo '<p>---------------------------- Test createFromXML() ---------------------</p>' . PHP_EOL;
//$xml = <<<XML
//	<root>
//		<source_lang>en</source_lang>
//		<target_lang>cn</target_lang>
//		<sentences>
//			<sentence work_status="working">
//				<source>english</source>
//				<target>英語</target>
//			</sentence>
//			<sentence>
//				<source>hoge</source>
//				<target>ほげ</target>
//			</sentence>
//		</sentences>
//	</root>
//XML;
//
//$doc3 = WorkDocument::createFromXML($xml);
//
//assertEquals('en', $doc3 -> getSourceLanguage());
//assertEquals('cn', $doc3 -> getTargetLanguage());
//assertEquals(2, count($doc3 -> getSentences()));
//
////print nl2br(htmlspecialchars($doc3 -> toXML()));
//
//$docs2 = WorkDocument::findAll(array(
//	"offset" => 1,
//	"limit" => 2
//));
//assertEquals(2, count($docs2));
//
//$docs3 = WorkDocument::findAll(array(
//	"limit" => 1
//));
//assertEquals(1, count($docs3));
//
//$docs = WorkDocument::findAll(array());
//assertEquals(4, count($docs));


echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';

} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}

?>

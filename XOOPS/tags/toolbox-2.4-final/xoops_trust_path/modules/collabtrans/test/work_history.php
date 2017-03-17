<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/work_document.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/common_util.php';

try{
echo '<p>WorkHistory Test start</p>';

echo '<p>---------------------------- Test createFromXML() ---------------------</p>' . PHP_EOL;

$xmlstr = <<<XML
	<root>
		<histories>
			<history>
				<source>aaaa</source>
				<target>bbbb</target>
				<loginId>toolbox</loginId>
				<create_date>1266217119629</create_date>
				<status>作業中</status>
			</history>
			<history>
				<source>cccc</source>
				<target>dddd</target>
				<loginId>toolbox</loginId>
				<create_date>1266217119629</create_date>
				<status>作業中</status>
			</history>
			<history>
				<source>eeee</source>
				<target>ffff</target>
				<loginId>toolbox</loginId>
				<create_date>1266217121320</create_date>
				<status>未作業</status>
			</history>
			<history>
				<source>ああああ</source>
				<target>いいいい</target>
				<loginId>toolbox</loginId>
				<create_date>1266217121320</create_date>
				<status>作業済</status>
			</history>
		</histories>
	</root>
XML;

$histories = WorkHistory::createFromXML($xmlstr);

assertEquals(4, count($histories));
assertEquals('aaaa', $histories[0] -> getSource());
assertEquals('bbbb', $histories[0] -> getTarget());
assertEquals('toolbox', $histories[0] -> getLoginId());
assertEquals('作業中', $histories[0] -> getStatus());



$histories = WorkHistory::createFromParams(array(
	"1" => array(
		'source' => 'インフォニック',
		'target' => 'infonic',
		'status' => '作業中',
		'loginId' => 'toolbox',
		'create_date' => '1266217121320'
	),
	"2" => array(
		'source' => '言語',
		'target' => 'language',
		'status' => '未作業',
		'loginId' => 'toolbox',
		'create_date' => '1266217119629'
	),
	"3" => array(
		'source' => 'コーヒー',
		'target' => 'coffee',
		'status' => '作業中',
		'loginId' => 'toolbox',
		'create_date' => '1266217119629'
	)
));

assertEquals(3, count($histories));
assertEquals('インフォニック', $histories[0] -> getSource());
assertEquals('infonic', $histories[0] -> getTarget());
assertEquals('toolbox', $histories[0] -> getLoginId());
assertEquals('作業中', $histories[0] -> getStatus());


echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';

} catch(Exception $e) {
	
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}

echo '<br>'
?>

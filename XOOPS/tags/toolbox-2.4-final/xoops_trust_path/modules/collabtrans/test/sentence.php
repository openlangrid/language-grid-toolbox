<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/sentence.php';

echo '<p>Sentence Test start</p>';
try{
echo '<p>---------------------------- Test createFromXML() ---------------------</p>' . PHP_EOL;

$xmlstr = <<<XML
	<root>
		<sentences>
			<sentence work_status="not_working">
				<source>aaaa</source>
				<target>bbbb</target>
			</sentence>
			<sentence work_status="work_out">
				<source>cccc</source>
				<target>dddd</target>
			</sentence>
			<sentence>
				<source>eeee</source>
				<target>ffff</target>
			</sentence>
			<sentence work_status="working">
				<source>ああああ</source>
				<target>いいいい</target>
			</sentence>
		</sentences>
	</root>
XML;

$ary = Sentence::createFromXML($xmlstr);

assertEquals(4, count($ary));
assertEquals('aaaa', $ary[0]->getSource());
assertEquals('bbbb', $ary[0]->getTarget());
assertEquals('not_working', $ary[0]->getStatus());
assertEquals('cccc', $ary[1]->getSource());
assertEquals('dddd', $ary[1]->getTarget());
assertEquals('work_out', $ary[1]->getStatus());
assertEquals('eeee', $ary[2]->getSource());
assertEquals('ffff', $ary[2]->getTarget());
assertEquals('not_working', $ary[2]->getStatus());
assertEquals('ああああ', $ary[3]->getSource());
assertEquals('いいいい', $ary[3]->getTarget());
assertEquals('working', $ary[3]->getStatus());

$ary = Sentence::createFromParams(array(
	"1" => array(
		'source' => 'インフォニック',
		'target' => 'infonic',
		'work_status' => ''
	),
	"2" => array(
		'source' => '言語',
		'target' => 'language',
		'work_status' => 'work_out'
	),
	"3" => array(
		'source' => 'コーヒー',
		'target' => 'coffee',
		'work_status' => 'working'
	)
));

assertEquals(3, count($ary));
assertEquals('インフォニック', $ary[0]->getSource());
assertEquals('infonic', $ary[0]->getTarget());
assertEquals('not_working', $ary[0]->getStatus());
assertEquals('言語', $ary[1]->getSource());
assertEquals('language', $ary[1]->getTarget());
assertEquals('work_out', $ary[1]->getStatus());
assertEquals('コーヒー', $ary[2]->getSource());
assertEquals('coffee', $ary[2]->getTarget());
assertEquals('working', $ary[2]->getStatus());

//nl2br(htmlspecialchars($ary[0]->toXML()));

echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';

} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}

?>

<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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
error_reporting(E_ALL);
require_once dirname(__FILE__).'/../../mainfile.php';
require_once dirname(__FILE__).'/../class/client/GlossaryClient.class.php';
require_once dirname(__FILE__).'/../class/client/ResourceClient.class.php';
header('Content-Type: text/html; charset=utf-8;');

$resourceClient = new ResourceClient();
$qaClient = new GlossaryClient();

echo '<h1>getAllRecords no args</h1>';
$result = $qaClient->getAllRecords('allala2');
echo count($result['contents']);

echo '<h1>getAllRecords no args</h1>';
$result = $qaClient->getAllRecords('allala2', 'desc', 'creationDate');
$prev = 99999999999;
foreach ($result['contents'] as $r) {
	if ($r->creationDate > $prev) {
		echo ('ERROR creation date desc');
	}
	echo $r->creationDate;
	echo '<br>';
	$prev = $r->creationDate;
}

echo '<h1>getAllRecords no args</h1>';
$result = $qaClient->getAllRecords('allala2', 'desc', 'creationDate', 1, 3);
echo count($result['contents']);

// creation date asec
$result = $qaClient->searchRecord('allala2', 'a', 'en', 'partial', null, 'all', 'asec', 'creationDate', null, null);
$prev = 0;
echo '<h1>creation date asec</h1>';
foreach ($result['contents'] as $r) {
	if ($r->creationDate < $prev) {
		echo ('ERROR creation date asec');
	}
	echo $r->creationDate;
	echo '<br>';
	$prev = $r->creationDate;
}

// creation date desc
$result = $qaClient->searchRecord('allala2', 'a', 'en', 'partial', null, 'all', 'desc', 'creationDate', null, null);
$prev = 9999999999;
echo '<h1>creation date desc</h1>';
foreach ($result['contents'] as $r) {
	if ($r->creationDate > $prev) {
		echo ('ERROR creation date desc');
	}
	echo $r->creationDate;
	echo '<br>';
	$prev = $r->creationDate;
}

// update date asec
$result = $qaClient->searchRecord('allala2', 'a', 'en', 'partial', null, 'all', 'asec', 'updateDate', null, null);
$prev = 0;
echo '<h1>update date asec</h1>';
foreach ($result['contents'] as $r) {
	if ($r->updateDate < $prev) {
		echo ('ERROR update date asec');
	}
	echo $r->updateDate;
	echo '<br>';
	$prev = $r->updateDate;
}

// update date desc
$result = $qaClient->searchRecord('allala2', 'a', 'en', 'partial', null, 'all', 'desc', 'updateDate', null, null);
$prev = 9999999999;
echo '<h1>update date desc</h1>';
foreach ($result['contents'] as $r) {
	if ($r->updateDate > $prev) {
		echo ('ERROR update date desc');
	}
	echo $r->updateDate;
	echo '<br>';
	$prev = $r->updateDate;
}

// offset, limit
$result = $qaClient->searchRecord('allala2', 'a', 'en', 'partial', null, 'all', 'desc', 'updateDate', null, null);
echo count($result['contents']);
$total = count($result['contents']);

// offset, limit
$result = $qaClient->searchRecord('allala2', 'a', 'en', 'partial', null, 'all', 'desc', 'updateDate', 0, 1);
if (count($result['contents']) != 1) {
	var_dump($result['contents']);
		echo ('ERROR offset, limit 0,1');
}

// offset, limit
$result = $qaClient->searchRecord('allala2', 'a', 'en', 'partial', null, 'all', 'desc', 'updateDate', 2, 1);
if (count($result['contents']) != 1) {
		echo ('ERROR offset, limit 2, 1');
}

// offset, limit
$result = $qaClient->searchRecord('allala2', 'a', 'en', 'partial', null, 'all', 'desc', 'updateDate', 1, 5);
echo count($result['contents']);

// offset, limit
$result = $qaClient->searchRecord('allala2', 'a', 'en', 'partial', null, 'all', 'desc', 'updateDate', 1);
if (count($result['contents']) != $total - 1) {
		echo ('ERROR offset, limit');
}

//$result = $qaClient->searchRecord('allala2', 'a', 'en', 'partial', null, 'all', 'desc', 'en');
//foreach ($result['contents'] as $r) {
//	var_dump($r->question);
//	var_dump($r->answers);
//}

echo 'SUCCESS';
?>
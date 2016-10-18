<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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
require('../../mainfile.php');
header('Content-Type: text/html; charset=utf-8;');
//include(XOOPS_ROOT_PATH.'/header.php');

require_once(dirname(__FILE__).'/../class/client/ParallelTextClient.class.php');
require_once(dirname(__FILE__).'/../class/client/ResourceClient.class.php');

$rClient = new ResourceClient();

$client = new ParallelTextClient();

echo '<hr>';
echo '<h2>createLanguageResource()</h2>';
echo '<pre>';
$name = 'ALLALLDICT'.time();
$type = 'PARALLELTEXT';
$languages = array('ja', 'en');
$readPermission = new ToolboxVO_Resource_Permission();
$readPermission->type = 'ALL';
$editPermission = new ToolboxVO_Resource_Permission();
$editPermission->type = 'ALL';
print_r($rClient->createLanguageResource($name, $type, $languages, $readPermission, $editPermission));
echo '</pre>';

echo '<h2>setRecords()</h2>';
echo '<pre>';
$e1ja = new ToolboxVO_Resource_Expression();
$e1ja->language = 'ja';
$e1ja->expression = 'いちばん';
$e1en = new ToolboxVO_Resource_Expression();
$e1en->language = 'en';
$e1en->expression = '1ST';

$rec1 = new ToolboxVO_ParalleText_ParallelTextRecord();
$rec1->id = 1;
$rec1->expressions = array($e1ja, $e1en);

$e2ja = new ToolboxVO_Resource_Expression();
$e2ja->language = 'ja';
$e2ja->expression = 'にばん';
$e2en = new ToolboxVO_Resource_Expression();
$e2en->language = 'en';
$e2en->expression = '2ND';

$rec2 = new ToolboxVO_ParalleText_ParallelTextRecord();
$rec2->id = 2;
$rec2->expressions = array($e2ja, $e2en);

print_r($client->setRecords($name, array($rec1, $rec2)));
echo '</pre>';

echo '<h2>addRecord()</h2>';
echo '<pre>';
$e1ja = new ToolboxVO_Resource_Expression();
$e1ja->language = 'ja';
$e1ja->expression = '追加'.time();
$e1en = new ToolboxVO_Resource_Expression();
$e1en->language = 'en';
$e1en->expression = 'AdD'.time();
//
print_r($client->addRecord($name, array($e1ja, $e1en)));
echo '</pre>';


echo '<h2>deleteRecord()</h2>';
echo '<pre>';
print_r($client->deleteRecord($name, 2));
echo '</pre>';


echo '<h2>updateRecord()</h2>';
echo '<pre>';
$e1ja = new ToolboxVO_Resource_Expression();
$e1ja->language = 'ja';
$e1ja->expression = '更新'.time();
$e1en = new ToolboxVO_Resource_Expression();
$e1en->language = 'en';
$e1en->expression = 'EDIt'.time();

print_r($client->updateRecord($name, 4, array($e1ja, $e1en)));
echo '</pre>';

echo '<h2>getAllRecords()</h2>';
echo '<pre>';
print_r($client->getAllRecords($name));
echo '</pre>';

echo '<h2>searchRecord()</h2>';
echo '<pre>';
print_r($client->searchRecord($name, '追加', 'ja', 'prefix'));
echo '</pre>';

?>

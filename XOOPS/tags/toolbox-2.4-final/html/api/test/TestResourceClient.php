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
ini_set('memory_limit', '128M');
require('../../mainfile.php');
header('Content-Type: text/html; charset=utf-8;');
//include(XOOPS_ROOT_PATH.'/header.php');

require_once(dirname(__FILE__).'/../class/client/ResourceClient.class.php');

$client =& new ResourceClient();

echo '<h2>$response = $client->getAllLanguageResources("DICTIONARY");</h2>';
echo '<pre>';
$response = $client->getAllLanguageResources("DICTIONARY");
print_r($response);
echo '</pre>';

//echo '<h2>getAllLanguageResources()</h2>';
//echo '<pre>';
//print_r($client->getAllLanguageResources('dictionary'));
//echo '</pre>';
//echo '<h2>getResource()</h2>';
//echo '<pre>';
//print_r($client->getLanguageResource('ENJA'));
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>searchLanguageResource()</h2>';
//echo '<pre>';
//print_r($client->searchLanguageResource('E', 'prefix', '0', array('en', 'ja', 'ko')));
//echo '</pre>';

//echo '<hr>';
//echo '<h2>createLanguageResource()</h2>';
//echo '<pre>';
//$name = 'ALLALLDICT'.time();
//$type = 'Dictionary';
//$languages = array('ja', 'en');
//$readPermission =& new ToolboxVO_Resource_Permission();
//$readPermission->type = 'ALL';
//$editPermission =& new ToolboxVO_Resource_Permission();
//$editPermission->type = 'ALL';
//print_r($client->createLanguageResource($name, $type, $languages, $readPermission, $editPermission));
//echo '</pre>';
//echo '<hr>';
//echo '<h2>createLanguageResource()</h2>';
//echo '<pre>';
//$name = 'USERUSERPARA'.time();
//$type = 'ParallelText';
//$languages = array('ja', 'en');
//$readPermission =& new ToolboxVO_Resource_Permission();
//$readPermission->type = 'USER';
//$editPermission =& new ToolboxVO_Resource_Permission();
//$editPermission->type = 'USER';
//print_r($client->createLanguageResource($name, $type, $languages, $readPermission, $editPermission));
//echo '</pre>';

//echo '<hr>';
//echo '<h2>deleteResource()</h2>';
//echo '<pre>';
//print_r($client->deleteLanguageResource('ALLALLDICT1253777060'));
//echo '</pre>';

//echo '<hr>';
//echo '<h2>addLanguage()</h2>';
//echo '<pre>';
//print_r($client->addLanguage('ALLALLDICT1253777173', 'ko'));
//echo '</pre>';
//echo '<hr>';
//echo '<h2>deleteLanguage()</h2>';
//echo '<pre>';
//print_r($client->deleteLanguage('ALLALLDICT1253777173', 'ko'));
//echo '</pre>';


//echo '<hr>';
//echo '<h2>setPermission()</h2>';
//echo '<pre>';
//$readPermission =& new ToolboxVO_Resource_Permission();
//$readPermission->type = 'USER';
//$editPermission =& new ToolboxVO_Resource_Permission();
//$editPermission->type = 'USER';
//print_r($client->setPermission('ALLALLDICT1253777173', $readPermission, $editPermission));
//echo '</pre>';

//echo '<hr>';
//echo '<h2>setPermission()</h2>';
//echo '<pre>';
//print_r($client->deploy('ALLALLDICT1253777173'));
//echo '</pre>';

?>



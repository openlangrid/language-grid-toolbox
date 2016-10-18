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

require_once(dirname(__FILE__).'/../class/client/BBSClient.class.php');

$client = new BBSClient('forum');

echo '<h2>getAllTagSets()</h2>';
echo '<pre>';
print_r($client->getAllTagSets());
echo '</pre>';
//
//echo '<h2>getTagSet()</h2>';
//echo '<pre>';
//print_r($client->getTagSet(1));
//echo '</pre>';
//
//echo '<h2>getAllTags()</h2>';
//echo '<pre>';
//print_r($client->getAllTags(1));
//echo '</pre>';
//
//echo '<h2>getTag()</h2>';
//echo '<pre>';
//print_r($client->getTag(1, 1));
//echo '</pre>';

//echo '<h2>addTagSet()</h2>';
//echo '<pre>';
//$setExps = array();
//$setExp1 = new ToolboxVO_BBS_TagExpression();
//$setExp1->language = 'en';
//$setExp1->expression = 'EnExpression';
//$setExps[] = $setExp1;
//$setExp2 = new ToolboxVO_BBS_TagExpression();
//$setExp2->language = 'ja';
//$setExp2->expression = 'JaExpression';
//$setExps[] = $setExp2;
//print_r($client->addTagSet($setExps));
//echo '</pre>';

//echo '<h2>updateTagSet()</h2>';
//echo '<pre>';
//$setExps = array();
//$setExp1 = new ToolboxVO_BBS_TagExpression();
//$setExp1->language = 'en';
//$setExp1->expression = 'EnExpression-update';
//$setExps[] = $setExp1;
//$setExp2 = new ToolboxVO_BBS_TagExpression();
//$setExp2->language = 'zh';
//$setExp2->expression = 'JaExpression';
//$setExps[] = $setExp2;
//print_r($client->updateTagSet(4, $setExps));
//echo '</pre>';


//echo '<h2>deleteTagSet()</h2>';
//echo '<pre>';
//print_r($client->deleteTagSet(2));
//echo '</pre>';


//echo '<h2>addTag()</h2>';
//echo '<pre>';
//$Exps = array();
//$Exp1 = new ToolboxVO_BBS_TagExpression();
//$Exp1->language = 'en';
//$Exp1->expression = 'text';
//$Exps[] = $Exp1;
//$Exp2 = new ToolboxVO_BBS_TagExpression();
//$Exp2->language = 'ja';
//$Exp2->expression = 'TEXT';
//$Exps[] = $Exp2;
//print_r($client->addTag(4, $Exps));
//echo '</pre>';

//echo '<h2>updateTag()</h2>';
//echo '<pre>';
//$Exps = array();
//$Exp1 = new ToolboxVO_BBS_TagExpression();
//$Exp1->language = 'en';
//$Exp1->expression = 'Update';
//$Exps[] = $Exp1;
//$Exp2 = new ToolboxVO_BBS_TagExpression();
//$Exp2->language = 'zh';
//$Exp2->expression = 'Update';
//$Exps[] = $Exp2;
//print_r($client->updateTag(4, 3, $Exps));
//echo '</pre>';



//echo '<h2>deleteAllTagSets()</h2>';
//echo '<pre>';
//print_r($client->deleteAllTagSets());
//echo '</pre>';

?>

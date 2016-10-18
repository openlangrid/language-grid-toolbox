<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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
require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mydirname.'/class/'.basename(__FILE__);
echo '<p>'.camelize(basename(__FILE__)).' Test start</p>';

try {
Com_Message::truncate();

$message = Com_Message::createFromParams(1, array(
	'message' => array(
		'ja' => 'こんにちは',
		'en' => 'Hello'
	),
	'contentId' => 1,
	'marker' => array(
		'x_coordinate' => 125,
		'y_coordinate' => 225
	)
));

$message -> insert();
$msg = Com_Message::findById($message -> getId());

assertEquals($message -> getId(), $msg -> getId() );
assertEquals(1, $msg -> getTopicId());
assertEquals(true, $msg -> isSelectedLanguageOriginal());
assertEquals(true, $msg -> isOwner(getLoginUserUID()));
assertEquals(true, $msg -> canEditOriginal());
assertEquals(false, $msg -> canEditTranslation());
assertEquals(!$msg -> canEditOriginal(), $msg -> canEditTranslation());
assertEquals(true, $msg -> canDelete());
assertEquals(true, $msg -> canEdit());
assertEquals(false, $msg -> isDeleted());
assertEquals(true, $msg -> hasContent());
assertEquals(true, $msg -> hasContentMarker());
assertEquals(getLoginUserUID(), $msg -> getUser() -> getID());
assertEquals(getLoginUserUID(), $msg -> getUserId());
assertEquals(false, $msg -> hasParentMessage());


echo $msg-> getOriginalLanguage().'='.$msg->getOriginalLanguageAsName().'<br>';
echo $msg-> getDescriptionForOriginal().'<br>';
echo $msg-> getDescriptionForSelectedLanguage().'<br>';
echo $msg-> getCreateDateAsFormatString().'<br>';
echo $msg-> getPostOrder().'<br>';

echo 'test reply message'.'<br>';
$parent = $msg;

$child = Com_Message::createFromParams(1, array(
	'parentId' => $parent->getId(),
	'message' => array(
		'ja' => '子供',
		'en' => 'Child'
	)
));
$child->insert();
$child2 = Com_Message::createFromParams(1, array(
	'parentId' => $parent->getId(),
	'message' => array(
		'ja' => '子供2',
		'en' => 'Child2'
	),
	'contentId' => 1
));
$child2->insert();

assertEquals($child-> getParentMessage()-> getId(), $parent-> getId());
assertEquals($child2-> getParentMessage()-> getId(), $parent-> getId());
assertEquals(false, $child -> hasContent());
assertEquals(true, $child2 -> hasContent());
assertEquals(false, $child2 -> hasContentMarker());
assertEquals(true, $child -> hasParentMessage());
assertEquals(true, $child2 -> hasParentMessage());
assertEquals($msg -> getPostOrder(), $child -> getParentPostOrder());


echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';
} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}

?>
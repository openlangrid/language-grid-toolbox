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
require_once XOOPS_TRUST_PATH.'/modules/'.$mydirname.'/class/com-attach-content.php';

echo '<p>'.camelize(basename(__FILE__)).' Test start</p>';
try {
	/*
Com_Attach_Content::truncate("Com_Attach_Content");
Com_Content_Marker::truncate("Com_Content_Marker");

$attach = Com_Attach_Content::createFromParams(array(
	"post_id" => "1",
	"content_id" => "1",
	'marker' => array(
		'x_coordinate' => 125,
		'y_coordinate' => 225
	)
));

$attach -> insert();

assertEquals(true, $attach -> hasMarker());

$attach2 = Com_Attach_Content::findByMessageId("1");
assertEquals($attach -> getPostId(), $attach2 -> getPostId());
assertEquals($attach -> getMessageId(), $attach2 -> getMessageId());
assertEquals($attach -> getContentId(), $attach2 -> getContentId());


$attach = Com_Attach_Content::createFromParams(array(
	"post_id" => "2",
	"content_id" => "1"
));
$attach -> insert();
assertEquals(false, $attach -> hasMarker());
$id = $attach -> getId();
$attach -> delete();
assertEquals(true, is_null(Com_Attach_Content::findById($id)));


$attach = Com_Attach_Content::createFromParams(array(
	"post_id" => "3",
	"content_id" => "1",
	"marker" => array(
		'x_coordinate' => 100,
		'y_coordinate' => 100
	)
));
$attach -> insert();
$id = $attach -> getId();
$attach -> delete();
assertEquals(true, is_null(Com_Attach_Content::findById($id)));

*/
//$attach = Com_AttachContent::findByMessageId(214);

//$content = $attach -> getContent();
$marker = Com_Content_Marker::findById(76);
var_dump($marker->getXCoordinate4zoom());
var_dump($marker->getYCoordinate4zoom());

echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';
} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}

?>
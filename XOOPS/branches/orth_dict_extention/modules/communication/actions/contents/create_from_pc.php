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

require_once XOOPS_TRUST_PATH.'/modules/' . $GLOBALS['mytrustdirname'] . '/class/contents/contents-update.php';
require_once dirname(__FILE__).'/../../class/com-content.php';
require_once(XOOPS_ROOT_PATH.'/api/class/client/FileSharingClient.class.php');

$topicId = @$_GET['topicId'] ? $_GET['topicId'] : @$_POST['topicId'];

$tmp_path = $_FILES['upfile']['tmp_name'];

$data = file_get_contents($tmp_path);

list($width, $height) = getimagesize($tmp_path);

$content = null;
if (is_uploaded_file($tmp_path)) {
	$content = Com_ContentImage::createWithParams($topicId, array(
		'content_title' => $_POST['content_title'],
		'uid'           => getLoginUserUid(),
		'category_id'   => $GLOBALS['xoopsModuleConfig']['content_file_category_id'],	
		'mime_type'     => $_FILES['upfile']['type'],
		'file_name'     => $_FILES['upfile']['name'],	
		'file_path'	    => $tmp_path,	
		'data'          => $data,
		'image_width'   => $width,
		'image_height'  => $height
	));
	$content -> insert();

} else {
	$contents = Com_Content::findAvailableContentsByTopicId($topicId);
	$content = reset($contents);
}

redirect_header(XOOPS_URL.'/modules/'.$GLOBALS['mytrustdirname'].'/contents/?action=reload&contentId=' . $content->getContentId());
	
?>

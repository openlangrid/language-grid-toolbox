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

$contentId = @$_GET['contentId'];
if($contentId) {
	$content = Com_Content::findAvailableContentById($contentId);
	$xoopsTpl->assign('content', $content);
	//$xoopsTpl->assign('contentList', Com_ContentList::getAvailableListByTopicId($content->getTopicId()));
}

if(@$_GET['messageId']) {
	$message = Com_Message::findById($_GET['messageId']);
	if($message -> hasContentMarker()) {
		$xoopsTpl->assign('marker', $message -> getContentMarker());
	}
} else if (@$_GET['x'] && @$_GET['y']) {
	$xoopsTpl->assign('marker',	new Com_Content_Marker(array(
		"x_coordinate" => $_GET['x'], 
		"y_coordinate" => $_GET['y']))
	);

//	if (($content->getType() == 'image')) {
//		$util = new Com_Content_util($content->getImageWidth(), $content->getImageHeight());
//		$util->changeMarkerPositionForZoom($content, $marker);
//		$xoopsTpl->assign('resizeContent', 	$util->multipleZoomRatio($content));
//	}
//	$xoopsTpl->assign('attachContent', $marker);
	
}


/*
 else if ($postId) {
	
	$attachContent = new Com_Attach_Content(array(
											"post_id" => $postId,
											"content_id" => $contentId,
											"image_id" => $imageId));

	$marker = $attachContent -> getContentMarkerById($postId);
	if (($content->getType() == 'image') && $marker) {
		$util = new Com_Content_util($content->getImageWidth(), $content->getImageHeight());
		$util->changeMarkerPositionForZoom($content, $marker);
	}
	$xoopsTpl->assign('attachContent', $marker);
//	$xoopsTpl->assign('resizeContent', 	$util->multipleZoomRatio($content));
}

echo $xoopsTpl -> fetch( 'db:'. $mytrustdirname . '_contents_zoom.html' );
*/
?>
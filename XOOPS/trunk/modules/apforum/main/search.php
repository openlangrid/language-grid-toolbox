<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
require_once dirname(__FILE__).'/../class/jumpbox/JumpBox.class.php';
$jumpBox = new NaviSelectorBox();
$xoopsTpl->assign('naviSelectorResource', json_encode($jumpBox->loadAssignValues()));

require_once dirname(__FILE__).'/../class/tag/Tag.class.php';
$tag = new Tag();
$xoopsTpl->assign('tagResource', json_encode($tag->loadTag()));

$paramTags = (isset($_GET['tag']) ? $_GET['tag'] : array());

$tagIds = array_values($paramTags);

$xoopsTpl->assign('tagSelected', json_encode($tagIds));

$url = './?search_result';
if ($topicId) {
	$url .= '&topicId='.$topicId;
} else if ($forumId) {
	$url .= '&forumId='.$forumId;
} else if ($categoryId) {
	$url .= '&categoryId='.$categoryId;
}

if ($searchWord) {
	$url .= '&word='.$searchWord;
}

if ($tags != null && is_array($tags) == true && count($tags) > 0) {
	foreach ($tags as $setId => $tagId) {
		$url .= sprintf('&tag[%s]=%s', $setId, $tagId);
	}
}

$xoopsTpl->assign('pagingUrl', $url);

?>
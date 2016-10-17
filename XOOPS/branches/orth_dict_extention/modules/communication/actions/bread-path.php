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

require_once dirname(__FILE__).'/../class/com-message.php';
require_once dirname(__FILE__).'/../class/com-topic.php';
require_once dirname(__FILE__).'/../class/com-forum.php';
require_once dirname(__FILE__).'/../class/com-category.php';


function get_bread_pathes($topicId) {
	
	$results = array();

	$topic = Com_Topic::findByTopicId($topicId);
	$forum = $topic -> getForum();
	$category = $forum -> getCategory();
	
	// Category
	array_push($results, new Com_BreadPath("categoryId=".$category->id, $category->getTitleForSelectedLanguage()));

	// Forum
	array_push($results, new Com_BreadPath("forumId=".$forum->id, $forum->getTitleForSelectedLanguage()));
	
	// Topic
	array_push($results, new Com_BreadPath("topicId=".$topic->id, $topic->getTitleForSelectedLanguage(), true));

	return $results;
}


class Com_BreadPath {
	public $href;
	public $label;
	public $last;
	
	public function __construct($href, $label, $last = false) {
		$this -> href = $href;
		$this -> label = $label;
		$this -> last = $last;
	}
}

$topicId = null;
if(@$_GET['topicId'] || @$_POST['topicId']) {
	$topicId = @$_GET['topicId'];
} else if(@$_GET['messageId'] || @$_POST['messageId']){
	$mes = Com_Message::findByMessageId(@$_GET['messageId'] || @$_POST['messageId']);
	$topicId = $mes->topicId;
}

$xoopsTpl->assign('bread_pathes', get_bread_pathes($topicId));

?>
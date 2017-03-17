<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once dirname(__FILE__).'/../../class/category-manager.php';

// task's ID
$id = @$_GET['id'];
if (!$id) {
	exit;
}

// get task
$task = Task::findById($GLOBALS['xoopsDB'], $id);
$history = $task->getLatestTaskHistory();

// language
$lang = @$_GET['lang'];
if (!$lang) {
	$lang = 'en';
}

$categoryId = @$_GET['categoryId'];
$forumManager = new ForumManager($lang);
$forums;

if ($categoryId > 0) {
	$forums = $forumManager->getForumsByCatId($categoryId);
} else {
	// no category's ID
	$forums = $forumManager->getForums();
}

$forumList = array();
$topicManager = new TopicManager($lang);
$postManager = new PostManager($lang);

// search latest posted dates and users
foreach ($forums as $forum) {
	/* @var $forum Forum */

	$record = array(
		'id' => $forum->getId(),
		'title' => $forum->getName(),
		'postsCount' => $forum->getPostsCount(),
		'lastPostedDate' => $forum->getLastPostedDateAsString(),
		'lastPostedUser' => null
	);

	$topicList = $topicManager->getTopicsByForumId($forum->getId());
	$lastPostedDate = $forum->getLastPostedDate();
	$latestUser = null;

	// (*)
	foreach ($topicList as $topic) {
		/* @var $topic Topic */

		if ($topic->getLastPostedDate() != $lastPostedDate) {
			continue;
		}

		$postList = $postManager->getPostsByTopicId($topic->getId());

		foreach ($postList as $post) {
			/* @var $post Post */

			if ($post->getCreatedDate() == $lastPostedDate) {
				$record['lastPostedUser'] = $post->getUserName();
				break 2; // (*)
			}
		}
	}

	$forumList[] = $record;
}

$xoopsTpl->assign(array(
	'id' => $id,
	'task' => $task,
	'history' => $history,
	'forumList' => $forumList
));

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

require_once(dirname(__FILE__).'/../class/client/BBSClient.class.php');

//$name = 'TEST-DICTIONARY';
//
//$client =& new BBSClient();
//
//echo '<h2>getAllCategories()</h2>';
//echo '<pre>';
//print_r($client->getAllCategories());
//echo '</pre>';
//
//echo '<hr>';
//
//echo '<h2>getAllForums(categoryId=1)</h2>';
//echo '<pre>';
//print_r($client->getAllForums('1'));
//echo '</pre>';
//
//echo '<hr>';
//
//echo '<h2>getAllTopics(forumId=3)</h2>';
//echo '<pre>';
//print_r($client->getAllTopics('3'));
//echo '</pre>';
//
//echo '<hr>';
//
//echo '<h2>getAllMessages(topicId=4)</h2>';
//echo '<pre>';
//print_r($client->getAllMessages('4'));
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>createCategory()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_CategoryExpression();
//$expja->language = 'ja';
//$expja->title = 'かてごりタイトル';
//$expja->description = 'かてごり説明';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_CategoryExpression();
//$expen->language = 'en';
//$expen->title = 'CategoryTitle';
//$expen->description = 'CategoryDescription';
//$expArray[] = $expen;
//$distBBS =& $client->createCategory($expArray);
//print_r($distBBS);
//echo '</pre>';
//$categoryId = $distBBS['contents']->id;
//
//echo '<hr>';
//echo '<h2>editCategory()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_CategoryExpression();
//$expja->language = 'ja';
//$expja->title = 'かてごりタイトル(Edit)';
//$expja->description = 'かてごり説明(Edit)';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_CategoryExpression();
//$expen->language = 'en';
//$expen->title = 'CategoryTitle(Edit)';
//$expen->description = 'CategoryDescription(Edit)';
//$expArray[] = $expen;
//print_r($client->editCategory($categoryId, $expArray));
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>modifyCategory()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_CategoryExpression();
//$expja->language = 'ja';
//$expja->title = 'かてごりタイトル(Modify)';
//$expja->description = 'かてごり説明(Modify)';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_CategoryExpression();
//$expen->language = 'en';
//$expen->title = 'CategoryTitle(Modify)';
//$expen->description = 'CategoryDescription(Modify)';
//$expArray[] = $expen;
//print_r($client->modifyCategory($categoryId, $expArray));
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>createForum()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_ForumExpression();
//$expja->language = 'ja';
//$expja->title = 'ふぉーらむタイトル';
//$expja->description = 'ふぉーらむ説明';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_ForumExpression();
//$expen->language = 'en';
//$expen->title = 'ForumTitle';
//$expen->description = 'ForumDescription';
//$expArray[] = $expen;
//$distBBS =& $client->createForum($categoryId, $expArray);
//print_r($distBBS);
//echo '</pre>';
//$forumId = $distBBS['contents']->id;
//
//echo '<hr>';
//echo '<h2>editForum()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_ForumExpression();
//$expja->language = 'ja';
//$expja->title = 'ふぉーらむタイトル(Edit)';
//$expja->description = 'ふぉーらむ説明(Edit)';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_ForumExpression();
//$expen->language = 'en';
//$expen->title = 'ForumTitle(Edit)';
//$expen->description = 'ForumDescription(Edit)';
//$expArray[] = $expen;
//$distBBS =& $client->editForum($forumId, $expArray);
//print_r($distBBS);
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>modifyForum()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_ForumExpression();
//$expja->language = 'ja';
//$expja->title = 'ふぉーらむタイトル(Modify)';
//$expja->description = 'ふぉーらむ説明(Modify)';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_ForumExpression();
//$expen->language = 'en';
//$expen->title = 'ForumTitle(Modify)';
//$expen->description = 'ForumDescription(Modify)';
//$expArray[] = $expen;
//$distBBS =& $client->modifyForum($forumId, $expArray);
//print_r($distBBS);
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>createTopic()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_TopicExpression();
//$expja->language = 'ja';
//$expja->title = 'とぴっくタイトル';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_ForumExpression();
//$expen->language = 'en';
//$expen->title = 'TopicTitle';
//$expArray[] = $expen;
//$distBBS =& $client->createTopic($forumId, $expArray);
//print_r($distBBS);
//echo '</pre>';
//$topicId = $distBBS['contents']->id;
//
//echo '<hr>';
//echo '<h2>editTopic()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_TopicExpression();
//$expja->language = 'ja';
//$expja->title = 'とぴっくタイトル(Edit)';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_ForumExpression();
//$expen->language = 'en';
//$expen->title = 'TopicTitle(Edit)';
//$expArray[] = $expen;
//$distBBS =& $client->editTopic($topicId, $expArray);
//print_r($distBBS);
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>modifyTopic()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_TopicExpression();
//$expja->language = 'ja';
//$expja->title = 'とぴっくタイトル(Modify)';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_TopicExpression();
//$expen->language = 'en';
//$expen->title = 'TopicTitle(Modify)';
//$expArray[] = $expen;
//$distBBS =& $client->modifyTopic($topicId, $expArray);
//print_r($distBBS);
//echo '</pre>';
//echo '<h2>deleteTopic()</h2>';
//echo '<pre>';
//$distBBS =& $client->deleteTopic("1");
//print_r($distBBS);
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>postMessage()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_MessageExpression();
//$expja->language = 'ja';
//$expja->body = 'メッセージ本文';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_MessageExpression();
//$expen->language = 'en';
//$expen->body = 'Message body.';
//$expArray[] = $expen;
//$distBBS =& $client->postMessage($topicId, $expArray);
//print_r($distBBS);
//echo '</pre>';
//$postId = $distBBS['contents']->id;
//
//echo '<hr>';
//echo '<h2>editMessage()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_MessageExpression();
//$expja->language = 'ja';
//$expja->body = 'メッセージ本文(Edit)';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_MessageExpression();
//$expen->language = 'en';
//$expen->body = 'Message body.(Edit)';
//$expArray[] = $expen;
//$distBBS =& $client->editMessage($postId, $expArray);
//print_r($distBBS);
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>modifyMessage()</h2>';
//echo '<pre>';
//$expArray = array();
//$expja =& new ToolboxVO_BBS_MessageExpression();
//$expja->language = 'ja';
//$expja->body = 'メッセージ本文(Modify)';
//$expArray[] = $expja;
//$expen =& new ToolboxVO_BBS_MessageExpression();
//$expen->language = 'en';
//$expen->body = 'Message body.(Modify)';
//$expArray[] = $expen;
//$distBBS =& $client->modifyMessage($postId, $expArray);
//print_r($distBBS);
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>deleteMessage()</h2>';
//echo '<pre>';
//$distBBS =& $client->deleteMessage($postId);
//print_r($distBBS);
//echo '</pre>';
//
//
//echo '<hr>';
//echo '<h2>getCategoryRevisions('.$categoryId.')</h2>';
//echo '<pre>';
//$distBBS =& $client->getCategoryRevisions($categoryId);
//print_r($distBBS);
//echo '</pre>';
//echo '<hr>';
//echo '<h2>getForumRevisions('.$forumId.')</h2>';
//echo '<pre>';
//$distBBS =& $client->getForumRevisions($forumId);
//print_r($distBBS);
//echo '</pre>';
//echo '<hr>';
//echo '<h2>getTopicRevisions('.$topicId.')</h2>';
//echo '<pre>';
//$distBBS =& $client->getTopicRevisions($topicId);
//print_r($distBBS);
//echo '</pre>';
//echo '</pre>';
//echo '<hr>';
//echo '<h2>getPostRevisions('.$postId.')</h2>';
//echo '<pre>';
//$distBBS =& $client->getMessageRevisions($postId);
//print_r($distBBS);
//echo '</pre>';
//
//
//echo '<hr>';
//echo '<h2>searchCategory(PARTIAL カテゴリ)</h2>';
//echo '<pre>';
//$distBBS =& $client->searchCategories('カテゴリ', 'PARTIAL');
//print_r($distBBS);
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>searchForum(PARTIAL タイトル)</h2>';
//echo '<pre>';
//$distBBS =& $client->searchForums('タイトル', 'PARTIAL');
//print_r($distBBS);
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>searchTopic(PARTIAL タイトル)</h2>';
//echo '<pre>';
//$distBBS =& $client->searchTopics('タイトル', 'PARTIAL');
//print_r($distBBS);
//echo '</pre>';
//
//echo '<hr>';
//echo '<h2>searchMessages(PARTIAL 本文)</h2>';
//echo '<pre>';
//$distBBS =& $client->searchMessages('本文', 'PARTIAL');
//print_r($distBBS);
//echo '</pre>';
//
//getCategoryRevisions
//echo '<hr>';
//echo '<h2>deleteCategory()</h2>';
//echo '<pre>';
//print_r($client->deleteCategory(3));
//echo '</pre>';

//getUpdatedMessages
//echo '<hr>';
//echo '<h2>getUpdatedMessages()</h2>';
//echo '<pre>';
//print_r($client->getUpdatedMessages(3, 1258536261));
//echo '</pre>';

?>

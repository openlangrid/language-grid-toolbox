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

//Used by Toolbox
/**
 * Common
 */
define('_MD_D3FORUM_BBS_TOP', 'ディスカッショントップ');
define('_MD_D3FORUM_BBS', 'ディスカッション');
define('_MD_D3FORUM_FORUM', 'フォーラム');
define('_MD_D3FORUM_CATEGORY', 'カテゴリ');
define('_MD_D3FORUM_DESCRIPTION', '説明');
define('_MD_D3FORUM_TOTALFORUMSCOUNT', 'フォーラム数');
define('_MD_D3FORUM_LASTPOST', '最新の投稿');
define('_MD_D3FORUM_TOPICTITLE', 'トピック名');
define('_MD_D3FORUM_ON', '投稿日');
define('_MD_D3FORUM_NOW_TRANSLATING', '翻訳中…');
define('_MD_D3FORUM_DOPOST', '投稿');
define('_MD_D3FORUM_OK', 'OK');
define('_MD_D3FORUM_COMMON_CANCEL', 'キャンセル');
define('_MD_D3FORUM_COMMON_CREATE', '新規作成');
define('_MD_D3FORUM_COMMON_MODIFY', '訳文編集');
define('_MD_D3FORUM_COMMON_REPLY', '返信');
define('_MD_D3FORUM_COMMON_EDIT', '原文編集');
define('_MD_D3FORUM_COMMON_DELETE', '削除');
define('_MD_D3FORUM_COMMON_OK', 'OK');
define('_MD_D3FORUM_COMMON_ORIGINAL_MESSAGE', '元のメッセージ');
define('_MD_D3FORUM_COMMON_ORIGINAL_TITLE', '元のタイトル');
define('_MD_D3FORUM_POSTASNEWTOPIC', 'トピックを作成');
define('_MD_D3FORUM_POST_MODIFICATION_HISTORIES', '修正履歴');
define('_MD_D3FORUM_CANCEL', 'キャンセル');
define('_MD_D3FORUM_POSTED_ON', '投稿日　%s');
define('_MD_D3FORUM_MODIFIED_ON', '修正日　%s');
define('_MD_D3FORUM_EDITED_ON', '編集日　%s');
//20090919 add
define('_MD_D3FORUM_COMMON_TRANSLATE', '翻訳');
define('_MD_D3FORUM_COMMON_TRANSLATION', '翻訳');
define('_MD_D3FORUM_COMMON_BACK_TRANSLATION', '折り返し翻訳');
define('_MD_D3FORUM_COMMON_NOW_TRANSLATING', '翻訳中…');
define('_MI_D3FORUM_HOW_TO_USE_LINK', 'ja.html');
define('_MD_D3FORUM_PREVIEW_NO_SOURCE', '%sが未入力です。');
define('_MD_D3FORUM_PREVIEW_NO_TRANSLATOIN_RESULT', '{1}の翻訳結果（言語: {0}）が未入力です。');
define('_MD_D3FORUM_COMMON_COMMIT', '確定');
define('_MD_D3FORUM_COMMON_POST', '投稿');
//20090928 add
define('_MD_D3FORUM_TRANSLATION_LANGRID_ERROR', '言語グリッドでエラーが発生しました。');
define('_MD_D3FORUM_MODIFY_ERROR_MESSAGE_IS_EMPTY', '%sが未入力です。');
//20091013 add
define('_MD_D3FORUM_COMMON_LICENSE_INFORMATION', 'ライセンス情報');
define('_MD_D3FORUM_COMMON_SERVICE_NAME', 'サービス名');
define('_MD_D3FORUM_COMMON_COPYRIGHT', '著作権情報');

// comment integration
define('_MD_D3FORUM_POSTASCOMMENTTOP', 'メッセージを投稿');

// module top (done)
define('_MD_D3FORUM_TOTALTOPICSCOUNT', 'トピック数');
define('_MD_D3FORUM_TOTALPOSTSCOUNT', 'メッセージ数');

// postform (done)
define('_MD_D3FORUM_POSTREPLY', '返信');
define('_MD_D3FORUM_POSTEDIT', '原文編集');
define('_MD_D3FORUM_POSTDELETE', '削除');
define('_MD_D3FORUM_ERR_NOMESSAGE', 'メッセージが未入力です。');

// makeforum and forummanager
define('_MD_D3FORUM_TH_FORUMTITLE', 'フォーラム名');
define('_MD_D3FORUM_TH_FORUMDESC', 'フォーラムの内容');

// makecategory and categorymanager
define('_MD_D3FORUM_TH_CATEGORYTITLE', 'カテゴリ名');
define('_MD_D3FORUM_TH_CATEGORYDESC', 'カテゴリ説明');

// add
define('_MD_D3FORUM_POST_TRANSLATION_MODIFY', '訳文編集');
define('_MD_D3FORUM_CREATE_TOPIC', 'トピックを作成');
define('_MD_D3FORUM_POST_TRANSLATE', '翻訳');
define('_MD_D3FORUM_POST_TRANSLATION_RESULT', '翻訳結果');
define('_MD_D3FORUM_POST_BACK_TRANSLATION_RESULT', '折り返し翻訳結果');
define('_MD_D3FORUM_PREVIEW', 'プレビュー');
define('_MD_D3FORUM_MODIFY_MODE', '訳文編集モード');
define('_MD_D3FORUM_ERR_NOT_EXIST_MENU', 'アクセス可能なカテゴリまたはフォーラムがありません。');
define('_MD_D3FORUM_RETURN_PAGE_TOP', 'トップに戻る');
define('_MD_D3FORUM_RETURN_COMMUNITY_TOP', 'ディスカッショントップ');
define('_MD_D3FORUM_ORIGINAL_TEXT', '元のテキスト');
define('_MD_D3FORUM_TRANSLATION_RESULT', '翻訳');
define('_MD_D3FORUM_BACK_TRANSLATION_RESULT', '折り返し翻訳');
define('_MD_D3FORUM_FROM', 'From');
define('_MD_D3FORUM_TO', 'To');
define('_MD_D3FORUM_POST_DELETED', '投稿が削除されました。');
define('_MD_D3FORUM_CATEGORY_DELETE', 'このカテゴリを削除');
define('_MD_D3FORUM_FORUM_DELETE', 'このフォーラムを削除');

/**
 * Modify Mode
 */
define('_MD_D3FORUM_MODIFY_COMMON_ORIGINAL_INFORMATION', '元の情報');
define('_MD_D3FORUM_MODIFY_COMMON_ORIGINAL_MESSAGE', '元のメッセージ');
define('_MD_D3FORUM_MODIFY_COMMON_TRANSLATION', '翻訳');
define('_MD_D3FORUM_NO_TRANSLATION', '翻訳できません。');
define('_MD_D3FORUM_POST_ORIGINAL_TEXT', '元のメッセージ');
define('_MD_D3FORUM_POST_MODIFY_TEXT', '翻訳結果');
define('_MD_D3FORUM_EDIT_TOPIC', '原文編集');
define('_MD_D3FORUM_MODIFY_TOPIC', '訳文編集');
define('_MD_D3FORUM_DELETE_TOPIC', '削除');
define('_MD_D3FORUM_MESSAGE', 'メッセージ');
define('_MD_D3FORUM_CREATE', '作成');
define('_MD_D3FORUM_EDIT', '原文編集');
define('_MD_D3FORUM_MODIFY', '訳文編集');
define('_MD_D3FORUM_DELETE', '削除');
define('_MD_D3FORUM_CREATE_A_NEW_CATEGORY', 'カテゴリを作成');
define('_MD_D3FORUM_CREATE_A_NEW_FORUM', 'フォーラムを作成');
define('_MD_D3FORUM_CREATE_A_NEW_TOPIC', 'トピックを作成');
define('_MD_D3FORUM_MODIFY_OK', 'OK');
define('_MD_D3FORUM_DELETE_CONFIRM_CATEGORY', 'このカテゴリを削除しますか？');
define('_MD_D3FORUM_DELETE_CONFIRM_FORUM', 'このフォーラムを削除しますか？');
define('_MD_D3FORUM_DELETE_CONFIRM_TOPIC', 'このトピックを削除しますか？');
define('_MD_D3FORUM_DELETE_CONFIRM_MESSAGE', 'このメッセージを削除しますか？');
define('_MD_D3FORUM_CATEGORY_MODIFY_TITLE', '訳文編集');
define('_MD_D3FORUM_REPLY', '返信');


//Use is not confirmed by Toolbox
/**
 * Common
 */
define('_MD_D3FORUM_RECENT_POST_POST', 'Results %s');
define('_MD_D3FORUM_LASTMODIFIED','Last modified');
define('_MD_D3FORUM_HISTORIES','Histories');
define('_MD_D3FORUM_BY','Posted by');
define('_MD_D3FORUM_POSTERFROM','From');
define('_MD_D3FORUM_POSTERPOSTS','Posts');
define('_MD_D3FORUM_TOPICSCOUNT','Topics');
define('_MD_D3FORUM_POSTSCOUNT','Posts');
define('_MD_D3FORUM_JUMPTOBOTTOM','Jump to bottom');
define('_MD_D3FORUM_JUMPTOTModify modeOP','Jump to top');
define('_MD_D3FORUM_POSTSTREE','Posts tree');
define('_MD_D3FORUM_BTN_JUMPTOFORUM','Jump to a forum');
define('_MD_D3FORUM_BTN_JUMPTOCATEGORY','Jump to a category');
define('_MD_D3FORUM_BTN_UPDATE','Update');
define('_MD_D3FORUM_MSG_UPDATED','Updated successfully');
define('_MD_D3FORUM_BTN_REFRESHTOPICS','Refresh');
define('_MD_D3FORUM_POST','Post');
define('_MD_D3FORUM_TOPIC','Topic');
define('_MD_D3FORUM_FORUM_LIST', 'Forum List');
define('_MD_D3FORUM_CATEGORY_LIST','Category List');
//define('_MD_D3FORUM_LASTPOST','Latest post');
define('_MD_D3FORUM_MODERATOR','Moderator');
define('_MD_D3FORUM_ALT_NEWPOSTS','New posts');
define('_MD_D3FORUM_ALT_NONEWPOSTS','No new posts');
define('_MD_D3FORUM_TITLE_SEARCH','Advanced search');
define('_MD_D3FORUM_TITLE_SEARCHRESULTS','Results');
define('_MD_D3FORUM_LABEL_SEARCH','Search');
define('_MD_D3FORUM_BTN_SEARCH','Search');
define('_MD_D3FORUM_LINK_ADVSEARCH','Advanced search');
define('_MD_D3FORUM_SUBJECT','Subject');
define('_MD_D3FORUM_BODY','Body');
define('_MD_D3FORUM_ALT_INVISIBLE','(invisible)');
define('_MD_D3FORUM_ALT_UNAPPROVAL','(not approved)');
define('_MD_D3FORUM_LINK_NEXTTOPIC','Next topic');
define('_MD_D3FORUM_LINK_PREVTOPIC','Previous topic');
define('_MD_D3FORUM_LINK_NEXTPOST','Next post');
define('_MD_D3FORUM_LINK_PREVPOST','Previous post');
define('_MD_D3FORUM_LINK_LISTPOSTS','List posts in the topic');
define('_MD_D3FORUM_LINK_LISTTOPICS','List topics in the forum');
define('_MD_D3FORUM_LISTALLTOPICS','List all topics');
define('_MD_D3FORUM_LISTTOPICSINCATEGORY','List topics in the category');
define('_MD_D3FORUM_LISTTOPICSINCATEGORIES','List topics in the specified categories');
define('_MD_D3FORUM_FMT_TOPICHITS','hits %s items');
define('_MD_D3FORUM_MSG_CONFIRMOK','Are you OK?');
define('_MD_D3FORUM_MSG_CONFIRMDELETE','Are you sure to delete it?');
define('_MD_D3FORUM_MSG_CONFIRMDELETERECURSIVE','All data belongs this record will be also deleted. Are you sure to delete it?');
define('_MD_D3FORUM_POSTASNEWTOPICTOTHISFORUM','You can create a topic into this forum');
define('_MD_D3FORUM_POSTASSAMETOPIC','Post into this topic');
define('_MD_D3FORUM_REPLYTHISPOST','Reply to this post');
define('_MD_D3FORUM_LINK_ALLRSS','RSS over all categories');
define('_MD_D3FORUM_LINK_CATEGORYRSS','RSS of this category');
define('_MD_D3FORUM_LINK_FORUMRSS','RSS of this forum');
define('_MD_D3FORUM_CANTPOSTTHISFORUM','You cannot post into this forum');
define('_MD_D3FORUM_CANTCREATENEWTOPICTHISFORUM','You cannot create a new topic into this forum');
define('_MD_D3FORUM_GUESTSCANPOST_DESC','Guests can post into this forum');
define('_MD_D3FORUM_GUESTSCANNOTPOST_DESC','Guests cannot post into this forum');
define('_MD_D3FORUM_FORUMASCOMMENT','Forum for commentation');
define('_MD_D3FORUM_FORUMASCOMMENT_DESC','As this forum is only for commentation, you cannot create a new topic');
define('_MD_D3FORUM_ERR_FORUMASCOMMENT','You cannot create new topic in this forum directly.');
define('_MD_D3FORUM_ERR_INVALIDEXTERNALLINKID','You have commented to invalid target');
define('_MD_D3FORUM_REPLIES','Replies');
define('_MD_D3FORUM_POSTER','Poster');
define('_MD_D3FORUM_VIEWS','Views');
define('_MD_D3FORUM_LEGEND','Legend');
define('_MD_D3FORUM_TOPICEXTERNALLINKID','External Link ID (Comment)');
define('_MD_D3FORUM_TOP','Top');
define('_MD_D3FORUM_WHOLE','Whole');
define('_MD_D3FORUM_ALT_MARKEDYES','Marked');
define('_MD_D3FORUM_ALT_MARKEDNO','Not marked');
define('_MD_D3FORUM_MARKEDYES_DESC','You have checked this topic as <em class="d3f_attn" title="MARKED topics are displayed in the top of list">MARKED</em>');
define('_MD_D3FORUM_MARKEDNO_DESC','You can check this topic as <em class="d3f_attn" title="MARKED topics are displayed in the top of list">MARKED</em>');
define('_MD_D3FORUM_ALT_SOLVEDYES','Solved topic');
define('_MD_D3FORUM_ALT_SOLVEDNO','Unsolved topic');
define('_MD_D3FORUM_SOLVEDYES_DESC','This topic is <em class="d3f_attn" title="SOLVED topics are treated as closed topics by admins or moderators">SOLVED</em>');
define('_MD_D3FORUM_SOLVEDNO_DESC','This topic is <em class="d3f_attn" title="Responses are welcome">UNSOLVED</em>');
define('_MD_D3FORUM_MARK_TURNON','Mark this topic');
define('_MD_D3FORUM_MARK_TURNOFF','Unmark this topic');
define('_MD_D3FORUM_SOLVED_TURNON','Turn solved on');
define('_MD_D3FORUM_SOLVED_TURNOFF','Turn solved off');
define('_MD_D3FORUM_LINK_TOPICHISTORIES','Refer histries about this topic');
define('_MD_D3FORUM_A_TOPOFTHETOPIC','Top of the posts'); //jidaikobo
define('_MD_D3FORUM_A_BOTTOMOFTHETOPIC','Bottom of the posts'); //jidaikobo

// comment integration
define('_MD_D3FORUM_LINK_COMMENTSOURCE','target of the comment');
define('_MD_D3FORUM_LINK_RICHERCOMMENTFORM','Go to richer form');
define('_MD_D3FORUM_LINK_LISTALLCOMMENTS','View more comments...');
define('_MD_D3FORUM_FMT_POSTHITSINFO','%d hits');
define('_MD_D3FORUM_FMT_POSTDISPLAYSINFO','%d displayed');
define('_MD_D3FORUM_FMT_COMMENTSUBJECT','Re: %s');
define('_MD_D3FORUM_COMMENTSLIST','Comments list');
define('_MD_D3FORUM_COM_TARGETMODULE','Target');
define('_MD_D3FORUM_COM_SUBJECT','Subject');
define('_MD_D3FORUM_COM_SUMMARY','Summary');

// topic,forum,category controller
define('_MD_D3FORUM_SHORTCUT','Short cut');
define('_MD_D3FORUM_CONTROLLER_WHOLE','Controller');
define('_MD_D3FORUM_CONTROLLER_CATEGORY','Category controller');
define('_MD_D3FORUM_CONTROLLER_FORUM','Forum controller');
define('_MD_D3FORUM_CONTROLLER_TOPIC','Topic controller');
define('_MD_D3FORUM_LINK_POSTORDERTREEASC','Tree order');
define('_MD_D3FORUM_LINK_POSTORDERTREEDESC','Tree order reversal');
define('_MD_D3FORUM_LINK_POSTORDERTIMEASC','Older is upper');
define('_MD_D3FORUM_LINK_POSTORDERTIMEDESC','Newer is upper');

// D3forumMessageValidator.class.php
define('_MD_D3FORUM_ERR_TOOMANYDIVBEGIN','Too many [quote] or <div>');
define('_MD_D3FORUM_ERR_TOOMANYDIVEND','Too many [/quote] or </div>');

// D3forumAntispam classes
define('_MD_D3FORUM_ERR_TURNJAVASCRIPTON','Post again after turning JavaScript of your browser.');
define('_MD_D3FORUM_LABEL_JAPANESEINPUTYOMI','');
define('_MD_D3FORUM_ERR_JAPANESENOTINPUT','');
define('_MD_D3FORUM_ERR_JAPANESEINCORRECT','');

// inc_eachpost.html
define('_MD_D3FORUM_UNIQUEPATHPREFIX','msg#');
define('_MD_D3FORUM_PARENTPOST','Parent');
define('_MD_D3FORUM_CHILDPOSTS','Children');
define('_MD_D3FORUM_NOCHILDPOSTS','No child');

// order options
define('_MD_D3FORUM_ODR_LASTPOSTDSC','Last posted desc');
define('_MD_D3FORUM_ODR_LASTPOSTASC','Last posted asc');
define('_MD_D3FORUM_ODR_CREATETOPICDSC','Topic created desc');
define('_MD_D3FORUM_ODR_CREATETOPICASC','Topic created asc');
define('_MD_D3FORUM_ODR_REPLIESDSC','Replies desc');
define('_MD_D3FORUM_ODR_REPLIESASC','Replies asc');
define('_MD_D3FORUM_ODR_VIEWSDSC','Views desc');
define('_MD_D3FORUM_ODR_VIEWSASC','Views asc');
define('_MD_D3FORUM_ODR_VOTESDSC','Votes desc');
define('_MD_D3FORUM_ODR_VOTESASC','Votes asc');
define('_MD_D3FORUM_ODR_POINTSDSC','Points desc');
define('_MD_D3FORUM_ODR_POINTSASC','Points asc');
define('_MD_D3FORUM_ODR_AVGDSC','Avg desc');
define('_MD_D3FORUM_ODR_AVGASC','Avg asc');

// extract options
define('_MD_D3FORUM_OPT_SOLVEDYES','Solved');
define('_MD_D3FORUM_OPT_SOLVEDNO','Unsolved');

// search
define('_MD_D3FORUM_LABEL_KEYWORDS','Keywords');
define('_MD_D3FORUM_LABEL_SEARCHOR','Search for ANY of the terms');
define('_MD_D3FORUM_LABEL_SEARCHAND','Search for ALL of the terms');
define('_MD_D3FORUM_LABEL_TARGETBOTH','Both of subject and body');
define('_MD_D3FORUM_LABEL_SORTBY','Sorted by');
define('_MD_D3FORUM_LABEL_USERNAME','User Name');
define('_MD_D3FORUM_LEGEND_SEARCHIN','Search in');
define('_MD_D3FORUM_LEGEND_WORDSMEANING','Words mean');
define('_MD_D3FORUM_FMT_BYTE','(%d byte)');
define('_MD_D3FORUM_MSG_NOMATCH','No match');
define('_MD_D3FORUM_FMT_SEARCHHITS','hits %s items');

// module top (done)
define('_MD_D3FORUM_TIMENOW','The time now is');
define('_MD_D3FORUM_LASTVISIT','You last visited');

// topic attributes (done)
define('_MD_D3FORUM_TOPICLOCKED','Locked topic');
define('_MD_D3FORUM_TOPICLOCKED_DESC','This topic is<em class="d3f_attn" title="You cannot reply nor edit posts">LOCKED</em> by administrators or moderators');
define('_MD_D3FORUM_TOPICSTICKY','Sticky topic');
define('_MD_D3FORUM_TOPICPOPULAR','Popular topic');
define('_MD_D3FORUM_TOPICNEWPOSTS','Topic with new posts');
define('_MD_D3FORUM_TOPICNONEWPOSTS','Topic without new posts');
define('_MD_D3FORUM_TOPICINVISIBLE','Invisible topic (Only admins and moderators can read this)');

// PERMISSION ERRORS (check done)
define('_MD_D3FORUM_ERR_SPECIFYFORUM','Forum must be specified');
define('_MD_D3FORUM_ERR_EXISTSFORUM','Invalid id of forum specified');
define('_MD_D3FORUM_ERR_EXISTSCATEGORY','Invalid id of category specified');
define('_MD_D3FORUM_ERR_SQL','SQL Error occurred in ');
define('_MD_D3FORUM_ERR_READPOST','You cannot access the specified post');
define('_MD_D3FORUM_ERR_READTOPIC','You cannot access the specified topic');
define('_MD_D3FORUM_ERR_READFORUM','You cannot access the specified forum');
define('_MD_D3FORUM_ERR_READCATEGORY','You cannot access the specified category');
define('_MD_D3FORUM_ERR_POSTTOPIC','You cannot post into the specified topic');
define('_MD_D3FORUM_ERR_POSTFORUM','You cannot post into the specified forum');

define('_MD_D3FORUM_ERR_CREATE_CATEGORY','You have no permission to create a category');
define('_MD_D3FORUM_ERR_CREATE_FORUM','You have no permission to create a forum');
define('_MD_D3FORUM_ERR_CREATE_TOPIC','You have no permission to create a topic');

define('_MD_D3FORUM_ERR_EDIT_CATEGORY','You cannot edit the specified category');
define('_MD_D3FORUM_ERR_EDIT_FORUM','You cannot edit the specified forum');
define('_MD_D3FORUM_ERR_EDIT_TOPIC','You cannot edit the specified topic');

define('_MD_D3FORUM_ERR_MODCATEGORY','You cannot modify the specified category');
define('_MD_D3FORUM_ERR_MODFORUM','You cannot modify the specified forum');
define('_MD_D3FORUM_ERR_MODTOPIC','You cannot modify the specified topic');

define('_MD_D3FORUM_ERR_DELETE_CATEGORY','You cannot delete the specified category');
define('_MD_D3FORUM_ERR_DELETE_FORUM','You cannot delete the specified forum');
define('_MD_D3FORUM_ERR_DELETE_TOPIC','You cannot delete the specified topic');
define('_MD_D3FORUM_ERR_DELETE_POST','You cannot delete the specified post');

define('_MD_D3FORUM_ERR_EDITPOST','You cannot modify the specified post');
define('_MD_D3FORUM_ERR_REPLYPOST','You cannot reply to the specified post');
define('_MD_D3FORUM_ERR_DELETEPOST','You cannot delete the specified post');
define('_MD_D3FORUM_ERR_MODERATETOPIC','You cannot moderate the specified topic');
define('_MD_D3FORUM_ERR_MODERATEFORUM','You cannot moderate the specified forum');
define('_MD_D3FORUM_ERR_MODERATECATEGORY','You cannot moderate the specified category');
define('_MD_D3FORUM_ERR_CREATETOPIC','You cannot create a new topic');
define('_MD_D3FORUM_ERR_CREATEFORUM','You cannot create a new forum');
define('_MD_D3FORUM_ERR_CREATECATEGORY','You cannot create a new category');

// postform (done)
define('_MD_D3FORUM_LABEL_INPUTHELPER','Input Helper ON/OFF');
define('_MD_D3FORUM_LABEL_ADVANCEDOPTIONS','Advanced Options');
define('_MD_D3FORUM_REFERENCEPOST','Reference');
define('_MD_D3FORUM_FORMTITLEINPREVIEW','Post from preview');
define('_MD_D3FORUM_MSG_THANKSPOST','Thanks for your submission!');
define('_MD_D3FORUM_MSG_THANKSPOSTNEEDAPPROVAL','Thanks for your submission! Wait till your post is approved.');
define('_MD_D3FORUM_MSG_THANKSEDIT','Modified successfully');
define('_MD_D3FORUM_USERWROTE','%s wrotes:');
define('_MD_D3FORUM_BTN_QUOTE','Quote');
define('_MD_D3FORUM_EDITMODEC','Edit mode');
define('_MD_D3FORUM_TH_UNAME','Username');
define('_MD_D3FORUM_TH_GUESTNAME','guestname');
define('_MD_D3FORUM_TH_GUESTEMAIL','email');
define('_MD_D3FORUM_TH_GUESTURL','site url');
define('_MD_D3FORUM_TH_GUESTPASSWORD','password');
define('_MD_D3FORUM_TH_GUESTTRIP','trip');
define('_MD_D3FORUM_FMT_UNAME','%s');
define('_MD_D3FORUM_MESSAGEICON','Message icon');
define('_MD_D3FORUM_TH_BODY','Body');
define('_MD_D3FORUM_OPTIONS','Options');
define('_MD_D3FORUM_ENABLESMILEY','Enable smiley');
define('_MD_D3FORUM_ENABLEHTML','Enable HTML');
define('_MD_D3FORUM_ENABLEXCODE','Enable modifiers of XOOPS (BBCode/auto-link etc.)');
define('_MD_D3FORUM_ENABLEBR','Enable auto wrap lines');
define('_MD_D3FORUM_ENABLENUMBERENTITY','Enable number-entity');
define('_MD_D3FORUM_ENABLESPECIALENTITY','Enable special-entity');
define('_MD_D3FORUM_LABEL_NEWPOSTNOTIFY','Notify me of new posts in this topic');
define('_MD_D3FORUM_LABEL_HIDEUID','Hide uid');
define('_MD_D3FORUM_LABEL_POSTINVISIBLE','Invisible');
define('_MD_D3FORUM_LABEL_DOAPPROVAL','Approval');
define('_MD_D3FORUM_LABEL_ATTACHSIG','Attach signature');
define('_MD_D3FORUM_EDITTIMELIMITED','Sorry, it has been expired to edit this post');
define('_MD_D3FORUM_NOTICE_YOUAREEDITING','You are editing the post now');

// topicmanager (check done)
define('_MD_D3FORUM_TOPICMANAGER','Topic Manager');
define('_MD_D3FORUM_TOPICMANAGER_DESC','You can change topic\'s title, status of lock, status of visible, and etc.');
define('_MD_D3FORUM_TOPICMANAGERDONE','Topic is modified successfully');
define('_MD_D3FORUM_TH_STICKY','Sticky');
define('_MD_D3FORUM_TH_LOCK','Lock');
define('_MD_D3FORUM_TH_INVISIBLE','Invisible');
define('_MD_D3FORUM_TH_SOLVED','Solved');
define('_MD_D3FORUM_BTN_SYNCTHISTOPIC','Sync this topic');
define('_MD_D3FORUM_BTN_MOVETOPICTOOTHERFORUM','Move this topic into the other forum');
define('_MD_D3FORUM_BTN_COPYTOPICTOOTHERFORUM','Copy this topic into the other forum');

// delete (check done)
define('_MD_D3FORUM_DELNOTALLOWED','You cannot delete this post');
define('_MD_D3FORUM_DELTIMELIMITED','Sorry, it has been expired to delete this post');
define('_MD_D3FORUM_DELCHILDEXISTS','Sorry, any parent posts cannot be removed.');
define('_MD_D3FORUM_CONFIRM_AREUSUREDEL','Are you sure you want to delete this post and all its child posts?');
define('_MD_D3FORUM_CONFIRM_AREUSUREDELONE','Are you sure you want to delete this post?');
define('_MD_D3FORUM_MSG_POSTSDELETED','Selected post and all its child posts deleted.');
define('_MD_D3FORUM_ERR_GUESTPASSMISMATCH','Invaild password');

// cut&paste posts (check done)
define('_MD_D3FORUM_ERR_NOSPECIFICID','post_id or forum_id should be specified.') ;
define('_MD_D3FORUM_ERR_PIDNOTEXIST','Specified post_id does not exist') ;
define('_MD_D3FORUM_CUTPASTEBYFORUMID_DEST','Select a forum') ;
define('_MD_D3FORUM_CUTPASTEBYFORUMIDSBJ','Move into the other forum') ;
define('_MD_D3FORUM_CUTPASTEBYFORUMIDDSC','This topic will belong the specified forum. topic_id will be kept.') ;
define('_MD_D3FORUM_CUTPASTETOPICDIVSBJ','Divide the topic(posts)') ;
define('_MD_D3FORUM_CUTPASTETOPICDIVDSC','This post will be a new topic in the specified forum with new topic_id') ;
define('_MD_D3FORUM_CUTPASTESUCCESS','The post has been cut and/or pasted successfully') ;

// makeforum and forummanager
define('_MD_D3FORUM_FORUMMANAGER','Forum Manager');
define('_MD_D3FORUM_LINK_MAKEFORUM','Create a forum');
define('_MD_D3FORUM_LINK_FORUMMANAGER','Modify this forum');
define('_MD_D3FORUM_LINK_FORUM_MOD','Modify this forum');
define('_MD_D3FORUM_LINK_FORUM_EDIT','Edit this forum');
define('_MD_D3FORUM_LINK_FORUMACCESS','Set permissions for this forum');
define('_MD_D3FORUM_MSG_FORUMMADE','A forum is created successfully');
define('_MD_D3FORUM_MSG_FORUMUPDATED','The forum is modified successfully');
define('_MD_D3FORUM_MSG_FORUMDELETED','The forum is deleted successfully');
define('_MD_D3FORUM_TH_FORUMOPTIONS','Forum options');
define('_MD_D3FORUM_TH_EXTERNALLINKFORMAT','Format for comment-integration');
define('_MD_D3FORUM_HELP_EXTERNALLINKFORMAT','leave blank for ordinary forums. If you write URI with %s started from  {XOOPS_URL}/modules/ , it will be the template linking to the sources. Native comment-integrated modules will set this field automatically.');
define('_MD_D3FORUM_BTN_MOVEFORUMTOOTHERFORUM','Move this forum into the other forum');
define('_MD_D3FORUM_BTN_COPYFORUMTOOTHERFORUM','Copy this forum into the other forum');

// makecategory and categorymanager
define('_MD_D3FORUM_CATEGORYMANAGER','Category Manager');
//define('_MD_D3FORUM_LINK_MAKECATEGORY','Create a category');
define('_MD_D3FORUM_LINK_MAKECATEGORY','Create a new category');
define('_MD_D3FORUM_LINK_MAKESUBCATEGORY','Create a subcategory');
define('_MD_D3FORUM_LINK_CATEGORYMANAGER','Modify this category');
define('_MD_D3FORUM_LINK_CATEGORY_MOD','Modify this category');
define('_MD_D3FORUM_LINK_CATEGORY_EDIT','Edit this category');
define('_MD_D3FORUM_LINK_CATEGORYACCESS','Set permissions for this category');

// batch actions
define('_MD_D3FORUM_BATCH_ACTIONS','Batch Actions');
define('_MD_D3FORUM_BA_TURNSOLVEDON','Turn all topics as solved');
define('_MD_D3FORUM_BA_MSG_CONFIRM','Notice: this action effects all topics/posts inside the category/forum');

// multi-byte spaces separated by ,
// (don't define for single space languages)
//define('_MD_D3FORUM_MULTIBYTESPACES',' ') ;

// add
define('_MD_D3FORUM_POST_VIEW','Result') ;
define('_MD_D3FORUM_ERR_CAN_NOT_POST_TOPIC_IN_ANY_FORUMS','There is not the forum that the contribution of the topic is admitted') ;
define('_MD_D3FORUM_ERR_CAN_NOT_POST_TOPIC_IN_ANY_CATEGORIES','There is not the category that the contribution of the topic is admitted') ;
define('_MD_D3FORUM_ACCESS_PERMISSION','A public range') ;
define('_MD_D3FORUM_ACCESS_PERMISSION_ALL','It is shown by the whole') ;
define('_MD_D3FORUM_ACCESS_PERMISSION_GROUP','It is shown by the group') ;
define('_MD_D3FORUM_CAN_NOT_MODIFY_THIS_POST','I cannot revise this contribution') ;
define('_MD_D3FORUM_MENU','Menu') ;
define('_MD_D3FORUM_SYSTEM_MESSAGE_ERROR_POST','I failed in a contribution.') ;
define('_MD_D3FORUM_POST_MODIFY_HISTORIES','History');
define('_MD_D3FORUM_POST_MODIFY_HISTORIES_NOT_EXIST','History is not exists.');
//define('_MD_D3FORUM_FROM','from');
//define('_MD_D3FORUM_TO','to');

/**
 * Modify Mode
 */
//define('_MD_D3FORUM_POST_MODIFY_TEXT','Sentences that translation correction is done.');
//define('_MD_D3FORUM_EDIT_TOPIC', 'Edit this topic');
//define('_MD_D3FORUM_MODIFY_TOPIC', 'Modify this topic');
//define('_MD_D3FORUM_DELETE_TOPIC', 'Delete this topic');
define('_MD_D3FORUM_POSTDELETE_MODE','Delete Mode');
define('_MD_D3FORUM_NEW_MARK','New');
define('_MD_D3FORUM_ENTER_THIS_ROOM', 'Enter');
define('_MD_D3FORUM_LEAVE_THIS_ROOM', 'Leave');
define('_MD_D3FORUM_TOPIC_LIST', 'Thread List');
define('_MD_D3FORUM_PARTCIPATE_TOPIC', 'Thread');
define('_MD_D3FORUM_MSG_CATEGORYMADE','A category is created successfully');
define('_MD_D3FORUM_MSG_CATEGORYUPDATED','The category is modified successfully');
define('_MD_D3FORUM_MSG_CATEGORYDELETED','The category is deleted successfully');
define('_MD_D3FORUM_TH_CATEGORYWEIGHT','Category weight');
define('_MD_D3FORUM_TH_CATEGORYPARENT','Parent category');
define('_MD_D3FORUM_TH_CATEGORYOPTIONS','Category options');
define('_MD_D3FORUM_ONOFF','ON/OFF');
define('_MD_D3FORUM_HOWTO_OVERRIDEOPTIONS','If you override preferences, write a line like:<br />(option name):(option value)<br />eg)<br />show_breadcrumbs:1 <br /><br />Overridable options and current values:');
define('_MD_D3FORUM_TH_FORUMWEIGHT','Forum weight');
define('_MD_D3FORUM_SUFFIX_UNDERTHISCATEGORY','(under this category)');
define('_MD_D3FORUM_SUFFIX_UNDERTHISFORUM','(under this forum)');
define('_MD_D3FORUM_SUBCATEGORIES','Subcategories');
define('_MD_D3FORUM_FIRSTPOST','First post');
define('_MD_D3FORUM_ERR_VOTEPERM','You cannot vote it');
define('_MD_D3FORUM_ERR_VOTEINVALID','Invalid vote');
define('_MD_D3FORUM_MSG_VOTEDOUBLE','You can vote once per a post');
define('_MD_D3FORUM_MSG_VOTEACCEPTED','Thanks for voting!');
define('_MD_D3FORUM_MSG_VOTEDISABLED','You cannot vote into the forum');
define('_MD_D3FORUM_VOTECOUNT','Votes');
define('_MD_D3FORUM_VOTEPOINTAVG','Average');
define('_MD_D3FORUM_VOTEPOINTDSCBEST','Useful');
define('_MD_D3FORUM_VOTEPOINTDSCWORST','Useless');
define('_MD_D3FORUM_POSTERJOINED','Joined');
define('_MD_D3FORUM_POSTERISONLINE','Online');
define('_MD_D3FORUM_CUTPASTEPOSTS','Cut and paste posts') ;
define('_MD_D3FORUM_ERR_CUTPASTENOTADMINOFDESTINATION','You are not moderator of destinated forum') ;
define('_MD_D3FORUM_ERR_PIDLOOP','parent/child loop error') ;
define('_MD_D3FORUM_CHILDREN_COUNT','children') ;
define('_MD_D3FORUM_PARENT_POSTID','Parent post_id') ;
define('_MD_D3FORUM_CUTPASTEBYPOSTID_DEST','destination post_id') ;
define('_MD_D3FORUM_CUTPASTEBYPOSTIDSBJ','Move into a post') ;
define('_MD_D3FORUM_CUTPASTEBYPOSTIDDSC','Specify ID of the post (post_id) will be parent of this post.') ;

define('COM_BACK_SELECTED_CONTENT', '選択中のコンテンツに戻る');
define('COM_ALL_TRANSLATION', '');
define('COM_AUTO', '自動');
define('COM_BTN_A_MINUS', 'A-');
define('COM_BTN_A_PLUS', 'A+');
define('COM_BTN_CANCEL', 'キャンセル');
define('COM_BTN_NEW_MESSAGE', '新規メッセージ');
define('COM_BTN_POST', '投稿');
define('COM_BTN_REPLY', '返信');
define('COM_BTN_TRANSLATE', '翻訳');
define('COM_COMMIT', '確定');
define('COM_IS_REQUIRED', 'が未入力です。');
define('COM_LANGUAGES', 'が完了しました｡');
define('COM_LNK_CLOSE', '閉じる');
define('COM_LNK_DELETE', '削除');
define('COM_LNK_EDIT', '原文編集');
define('COM_LNK_EDIT_TRANSLATION', '訳文編集');
define('COM_LNK_NEW_MESSAGE_ARRIVED', '新着メッセージがあります (クリックして更新)');
define('COM_LNK_NEW_MESSAGE_RELOADING', '新着メッセージをロード中');
define('COM_LNK_OPEN', '開く');
define('COM_MANUAL', '手動');
define('COM_MESSAGE', 'ここにメッセージを記入してください。');
define('COM_MESSAGES', '%s件のメッセージ');
define('COM_NEW_MESSAGE', '新規メッセージ');
define('COM_NOW_TRANSLATIONING', '翻訳中');
define('COM_NOW_TRANSLATION_REMAIN', '翻訳中：');
define('COM_REQUIRED', 'が未入力です。');
define('COM_SUBMITTED_BY_POST_BUTTON', '※「%s」ボタンを押すまで、投稿されません。');
define('COM_SELECT_ATTACHEMENT_CONTENT', 'コンテンツを選択');
define('COM_UNSELECT', '選択解除');
define('COM_BTN_ZOOM', 'ズーム');
define('COM_CONTENTS', '%s個のコンテンツが登録されています');
define('CON_BTN_NEW_CONTENT', '新規コンテンツ');
define('COM_BTN_CONTENT_DELETE', '削除');
define('COM_BTN_SET_MARKER', 'マーカセット');
define('COM_BTN_DEL_MARKER', 'マーカ削除');
define('COM_CONTENT_TITLE', 'コンテンツタイトル');
define('COM_SELECT_UPLOAD_FILE', 'ファイル (JPG・PNG・GIF形式の画像)');
define('COM_BTN_UPLOAD', 'アップロード');
define('COM_SET_CONTENT_RELATED', 'このコンテンツをメッセージに関連付ける');
define('COM_CONFIRM_DEL_MARKER', 'マーカが保存されていません。設定されたマーカが廃棄されますがよろしいですか？');
define('COM_CONTENT_GUIDELINE', '投稿するメッセージに関連付けたいコンテンツがあれば上のプルダウンから選択して下さい。');
define('COM_CONTENT_DEL_REMAIN', '選択したコンテンツを削除しますか?');
define('COM_TRANSLATE_SENTENCE', '翻訳結果');
define('COM_REPLY_TO_MESSAGE', 'メッセージ%sへの返信');
define('COM_REQUIRE_URL', 'Googleマップで取得できるリンクのURLを入力してください．');
define('COM_REQUIRE_SELECT_UPLOAD_FILE', 'アップロードするファイルを選択して下さい。');
define('COM_DELETE_MESSAGE_CONFIRM', 'メッセージを削除しますか？');
define('COM_REPLY_MESSAGES', ' 通の返信');
define('COM_REQUIRE_GOOGLE_MAP_URL', 'Googleマップで取得できるリンクのURLを入力してください。');
define('COM_ENTER_MESSAGE', 'メッセージを入力してください。');
define('COM_ENTER_GOOGLEMAP_URL', 'Googleマップで取得できるリンクのURLを入力してください。');
define('COM_INVALID_GOOGLEMAP_URL', 'GoogleマップのURLが未対応の形式です');
define('COM_DISCUSSION_TOP', 'ディスカッション トップ');
define('COM_DISCUSSION', 'ディスカッション');
define('COM_PARENT_MESSAGE', '返信元メッセージ ');
define('COM_HISTORY', '履歴');
define('COM_ALL_HISTORY', '全ての履歴');
define('COM_POSTED_ON', '投稿日時');
define('COM_CONTRIBUTOR_IS_ALLOWED_TO_DELETE', 'コンテンツ登録者しか削除できません。');
define('COM_CONFIRM_RELATED_CONTENT_DELETE', '%s 件のメッセージが関連付けられていますが削除しますか？');
define('COM_RELATED_CONTENT_IS_DELETED', '関連コンテンツは削除されています');
define('COM_BTN_SAVE', '保存');

define('_COM_DTFMT_YMDHI', 'Y/m/d H:i');
define('COM_BTN_EDIT_TOPIC', '原文編集');
define('COM_LNK_SHOW_TRANS_PROGRESS', '翻訳結果の表示');
define('COM_LABEL_MESSAGE', 'メッセージ');
define('COM_RELATED_CONTENTS', 'コンテンツ');
define('COM_BTN_DOWNLOAD', 'ダウンロード');
define('COM_LABEL_REGISTRATION_CONTENT', 'コンテンツ登録');
define('COM_LABEL_SELECTABLE_FILE_TYPE', 'jpg, png または gif 形式のファイルのみ選択可能です');
define('COM_UP_TO_PARENT_FOLDER', '一つ上のフォルダへ移動');
define('COM_BTN_UPLOAD_FILE', 'ファイルをアップロード');
define('COM_LABEL_SELECT', '選択');
define('COM_LABEL_FILE_NAME', 'ファイル名');
define('COM_LABEL_DESCRIPTION', '説明');
define('COM_LABEL_PERM_READ', '閲覧');
define('COM_LABEL_PERM_WRITE', '編集');
define('COM_LABEL_UPDATER', '更新者');
define('COM_LABEL_UPDATE_DATE', '最新日時');
define('COM_LINK_FILE_SHARING_TOP', 'ファイル共有トップ');

define('CONFIRM_QUIT_EDIT', '入力したメッセージは破棄されますが、よろしいですか？');
define('COM_LABEL_DISCUSSION_IN', 'ディスカッションの表示言語：');
define('COM_MSG_SAME_NAME_CONTENT_ERROR', '既に同名のコンテンツが存在します。');
define('COM_BTN_ADD_CONTENT', 'コンテンツ追加');

define('_MD_COPYRIGHT_LB', 'Copyright (C) 2010 Graduate School of Informatics, Kyoto University. All rights reserved.');
?>
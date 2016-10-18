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
define('_MD_D3FORUM_BBS_TOP', 'BBS top');
define('_MD_D3FORUM_BBS', 'BBS');
define('_MD_D3FORUM_FORUM', 'Forum');
define('_MD_D3FORUM_CATEGORY', 'Category');
define('_MD_D3FORUM_DESCRIPTION', 'Description');
define('_MD_D3FORUM_TOTALFORUMSCOUNT', 'Forums');
define('_MD_D3FORUM_LASTPOST', 'Last post');
define('_MD_D3FORUM_TOPICTITLE', 'Topic title');
define('_MD_D3FORUM_ON', 'Posted on');
define('_MD_D3FORUM_NOW_TRANSLATING', 'Now translating ...');
define('_MD_D3FORUM_DOPOST', 'Post');
define('_MD_D3FORUM_OK', 'OK');
define('_MD_D3FORUM_COMMON_CANCEL', 'Cancel');
define('_MD_D3FORUM_COMMON_CREATE', 'Create');
define('_MD_D3FORUM_COMMON_MODIFY', 'Post-edit');
define('_MD_D3FORUM_COMMON_REPLY', 'Reply');
define('_MD_D3FORUM_COMMON_EDIT', 'Pre-edit');
define('_MD_D3FORUM_COMMON_DELETE', 'Delete');
define('_MD_D3FORUM_COMMON_OK', 'OK');
define('_MD_D3FORUM_COMMON_ORIGINAL_MESSAGE', 'Original message');
define('_MD_D3FORUM_COMMON_ORIGINAL_TITLE', 'Original title');
define('_MD_D3FORUM_POSTASNEWTOPIC', 'Create a new topic');
define('_MD_D3FORUM_POST_MODIFICATION_HISTORIES', 'Modification history');
define('_MD_D3FORUM_CANCEL', 'Cancel');
define('_MD_D3FORUM_POSTED_ON', 'Posted on %s');
define('_MD_D3FORUM_MODIFIED_ON', 'Modified on %s');
define('_MD_D3FORUM_EDITED_ON', 'Edited on %s');
//20090919 add
define('_MD_D3FORUM_COMMON_TRANSLATE', 'Translate');
define('_MD_D3FORUM_COMMON_TRANSLATION', 'Translation');
define('_MD_D3FORUM_COMMON_BACK_TRANSLATION', 'Back-translation');
define('_MD_D3FORUM_COMMON_NOW_TRANSLATING', 'Now translating...');
define('_MI_D3FORUM_HOW_TO_USE_LINK', 'en.html');
define('_MD_D3FORUM_PREVIEW_NO_SOURCE', '%s is empty.');
define('_MD_D3FORUM_PREVIEW_NO_TRANSLATOIN_RESULT', 'Translation result of {1} (Language: {0}) is empty.');
define('_MD_D3FORUM_COMMON_COMMIT', 'Commit');
define('_MD_D3FORUM_COMMON_POST', 'Post');
//20090928 add
define('_MD_D3FORUM_TRANSLATION_LANGRID_ERROR', 'An error has occurred at the Language Grid.');
define('_MD_D3FORUM_MODIFY_ERROR_MESSAGE_IS_EMPTY', '%s is empty.');
//20091013 add
define('_MD_D3FORUM_COMMON_LICENSE_INFORMATION', 'License Information');
define('_MD_D3FORUM_COMMON_SERVICE_NAME', 'Service Name');
define('_MD_D3FORUM_COMMON_COPYRIGHT', 'Copyright');

// comment integration
define('_MD_D3FORUM_POSTASCOMMENTTOP', 'Post a new message');

// module top (done)
define('_MD_D3FORUM_TOTALTOPICSCOUNT', 'Topics');
define('_MD_D3FORUM_TOTALPOSTSCOUNT', 'Messages');

// postform (done)
define('_MD_D3FORUM_POSTREPLY', 'Reply');
define('_MD_D3FORUM_POSTEDIT', 'Pre-edit');
define('_MD_D3FORUM_POSTDELETE', 'Delete');
define('_MD_D3FORUM_ERR_NOMESSAGE', 'You have posted empty message.');

// makeforum and forummanager
define('_MD_D3FORUM_TH_FORUMTITLE', 'Forum title');
define('_MD_D3FORUM_TH_FORUMDESC', 'Forum description');

// makecategory and categorymanager
define('_MD_D3FORUM_TH_CATEGORYTITLE', 'Category title');
define('_MD_D3FORUM_TH_CATEGORYDESC', 'Category description');

// add
define('_MD_D3FORUM_POST_TRANSLATION_MODIFY', 'Post-edit');
define('_MD_D3FORUM_CREATE_TOPIC', 'Create a new topic');
define('_MD_D3FORUM_POST_TRANSLATE', 'Translate');
define('_MD_D3FORUM_POST_TRANSLATION_RESULT', 'Translation Result');
define('_MD_D3FORUM_POST_BACK_TRANSLATION_RESULT', 'Back Translation Result');
define('_MD_D3FORUM_PREVIEW', 'Preview');
define('_MD_D3FORUM_MODIFY_MODE', 'Post-edit mode');
define('_MD_D3FORUM_ERR_NOT_EXIST_MENU', 'There is not a category or the forum that I can access');
define('_MD_D3FORUM_RETURN_PAGE_TOP', 'Return to Top');
define('_MD_D3FORUM_RETURN_COMMUNITY_TOP', 'Discussion top');
define('_MD_D3FORUM_ORIGINAL_TEXT', 'Original Text');
define('_MD_D3FORUM_TRANSLATION_RESULT', 'Translation');
define('_MD_D3FORUM_BACK_TRANSLATION_RESULT', 'Back-translation');
define('_MD_D3FORUM_FROM', 'From');
define('_MD_D3FORUM_TO', 'To');
define('_MD_D3FORUM_POST_DELETED', 'This post was deleted.');
define('_MD_D3FORUM_CATEGORY_DELETE', 'Delete this category');
define('_MD_D3FORUM_FORUM_DELETE', 'Delete this forum');

/**
 * Modify Mode
 */
define('_MD_D3FORUM_MODIFY_COMMON_ORIGINAL_INFORMATION', 'Original information');
define('_MD_D3FORUM_MODIFY_COMMON_ORIGINAL_MESSAGE', 'Original message');
define('_MD_D3FORUM_MODIFY_COMMON_TRANSLATION', 'Translation');
define('_MD_D3FORUM_NO_TRANSLATION', 'Translation unavailable.');
define('_MD_D3FORUM_POST_ORIGINAL_TEXT', 'Original message');
define('_MD_D3FORUM_POST_MODIFY_TEXT', 'Translation result');
define('_MD_D3FORUM_EDIT_TOPIC', 'Pre-edit');
define('_MD_D3FORUM_MODIFY_TOPIC', 'Post-edit');
define('_MD_D3FORUM_DELETE_TOPIC', 'Delete');
define('_MD_D3FORUM_MESSAGE', 'Message');
define('_MD_D3FORUM_CREATE', 'Create');
define('_MD_D3FORUM_EDIT', 'Pre-edit');
define('_MD_D3FORUM_MODIFY', 'Post-edit');
define('_MD_D3FORUM_DELETE', 'Delete');
define('_MD_D3FORUM_CREATE_A_NEW_CATEGORY', 'Create a new category');
define('_MD_D3FORUM_CREATE_A_NEW_FORUM', 'Create a new forum');
define('_MD_D3FORUM_CREATE_A_NEW_TOPIC', 'Create a new topic');
define('_MD_D3FORUM_MODIFY_OK', 'OK');
define('_MD_D3FORUM_DELETE_CONFIRM_CATEGORY', 'Are you sure to delete the category?');
define('_MD_D3FORUM_DELETE_CONFIRM_FORUM', 'Are you sure to delete the forum?');
define('_MD_D3FORUM_DELETE_CONFIRM_TOPIC', 'Are you sure to delete the topic?');
define('_MD_D3FORUM_DELETE_CONFIRM_MESSAGE', 'Are you sure to delete the message?');
define('_MD_D3FORUM_CATEGORY_MODIFY_TITLE', 'Post-edit');
define('_MD_D3FORUM_REPLY', 'Reply');


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

define('COM_BACK_SELECTED_CONTENT', '선택한 콘텐츠로 돌아가기');
define('COM_ALL_TRANSLATION', '');
define('COM_AUTO', '자동');
define('COM_BTN_A_MINUS', 'A-');
define('COM_BTN_A_PLUS', 'A+');
define('COM_BTN_CANCEL', '취소');
define('COM_BTN_NEW_MESSAGE', '새 메시지');
define('COM_BTN_POST', '게시');
define('COM_BTN_REPLY', '회신');
define('COM_BTN_TRANSLATE', '번역');
define('COM_COMMIT', '커밋');
define('COM_IS_REQUIRED', '필수');
define('COM_LANGUAGES', '마침');
define('COM_LNK_CLOSE', '닫기');
define('COM_LNK_DELETE', '삭제');
define('COM_LNK_EDIT', '사전 편집');
define('COM_LNK_EDIT_TRANSLATION', '사후 편집');
define('COM_LNK_NEW_MESSAGE_ARRIVED', '새 메시지가 있습니다(다시 로드하려면 클릭).');
define('COM_LNK_NEW_MESSAGE_RELOADING', '새 메시지를 다시 로드하는 중');
define('COM_LNK_OPEN', '열기');
define('COM_MANUAL', '수동');
define('COM_MESSAGE', '여기에 메시지를 입력하십시오.');
define('COM_MESSAGES', '%s개 메시지');
define('COM_NEW_MESSAGE', '새 메시지');
define('COM_NOW_TRANSLATIONING', '번역 중');
define('COM_NOW_TRANSLATION_REMAIN', '번역 중: ');
define('COM_REQUIRED', '은(는) 필수 항목입니다.');
define('COM_SUBMITTED_BY_POST_BUTTON', '제출하려면 "%s" 버튼을 누르십시오.');
define('COM_SELECT_ATTACHEMENT_CONTENT', '목차 선택');
define('COM_UNSELECT', '지우기');
define('COM_BTN_ZOOM', '확대/축소');
define('COM_CONTENTS', '%s개 콘텐츠');
define('CON_BTN_NEW_CONTENT', '새 콘텐츠');
define('COM_BTN_CONTENT_DELETE', '삭제');
define('COM_BTN_SET_MARKER', '마커 설정');
define('COM_BTN_DEL_MARKER', '마커 삭제');
define('COM_CONTENT_TITLE', '콘텐츠 제목');
define('COM_SELECT_UPLOAD_FILE', '파일(JPG, PNG 또는 GIF 이미지 파일)');
define('COM_BTN_UPLOAD', '업로드');
define('COM_SET_CONTENT_RELATED', '이 콘텐츠를 메시지에 연결');
define('COM_CONFIRM_DEL_MARKER', '마커가 저장되지 않았습니다. 마커를 삭제하시겠습니까?');
define('COM_CONTENT_GUIDELINE', '위의 풀다운 메뉴에서 메시지에 연결할 콘텐츠를 선택하십시오.');
define('COM_CONTENT_DEL_REMAIN', '선택한 콘텐츠를 삭제하시겠습니까?');
define('COM_TRANSLATE_SENTENCE', '번역');
define('COM_REPLY_TO_MESSAGE', '메시지 #%s에 회신');
define('COM_REQUIRE_URL', 'Google Maps "링크"의 URL을 입력하십시오.');
define('COM_REQUIRE_SELECT_UPLOAD_FILE', '업로드할 파일을 선택하십시오.');
define('COM_DELETE_MESSAGE_CONFIRM', '메시지를 삭제하시겠습니까?');
define('COM_REPLY_MESSAGES', ' 메시지 회신');
define('COM_REQUIRE_GOOGLE_MAP_URL', 'Google Maps "링크"의 URL을 입력하십시오.');
define('COM_ENTER_MESSAGE', '');
define('COM_ENTER_GOOGLEMAP_URL', 'Google Maps "링크"의 URL을 입력하십시오.');
define('COM_INVALID_GOOGLEMAP_URL', 'Google Map URL is invalid.');
define('COM_DISCUSSION_TOP', 'Discussion top');
define('COM_DISCUSSION', 'Discussion');
define('COM_PARENT_MESSAGE', 'Original message');
define('COM_HISTORY', 'History');
define('COM_ALL_HISTORY', 'All history');
define('COM_POSTED_ON', 'Posted on');
define('COM_CONTRIBUTOR_IS_ALLOWED_TO_DELETE', 'Only content contributor is allowed to delete.');
define('COM_CONFIRM_RELATED_CONTENT_DELETE', '%s messages are linked to this content, Are you sure to delete it?');
define('COM_RELATED_CONTENT_IS_DELETED', 'The content linked to this message has been deleted.');
define('COM_BTN_SAVE', 'Save');
define('COM_BTN_EDIT_TOPIC', 'Pre-edit');
define('COM_LNK_SHOW_TRANS_PROGRESS', 'Show translation results');
define('COM_LABEL_MESSAGE', 'Message');
define('COM_RELATED_CONTENTS', 'Contents');
define('COM_BTN_DOWNLOAD', 'Download');
define('COM_LABEL_REGISTRATION_CONTENT', 'Register content');
define('COM_LABEL_SELECTABLE_FILE_TYPE', 'You can select a jpg, png or gif file.');
define('COM_UP_TO_PARENT_FOLDER', 'Up to parent folder');
define('COM_BTN_UPLOAD_FILE', 'Upload file');
define('COM_LABEL_SELECT', 'Select');
define('COM_LABEL_FILE_NAME', 'Name');
define('COM_LABEL_DESCRIPTION', 'Description');
define('COM_LABEL_PERM_READ', 'Read');
define('COM_LABEL_PERM_WRITE', 'Edit');
define('COM_LABEL_UPDATER', 'Updated by');
define('COM_LABEL_UPDATE_DATE', 'Last update');
define('COM_LINK_FILE_SHARING_TOP', 'File sharing top');
define('CONFIRM_QUIT_EDIT', 'Discard the entered message?');
define('COM_LABEL_DISCUSSION_IN', 'Discussion in');
define('COM_MSG_SAME_NAME_CONTENT_ERROR', 'The content of the same name already exists.');
define('COM_BTN_ADD_CONTENT', 'Add content');
define('COM_PERMISSION_DENIED', 'Permission denied');
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2010 Graduate School of Informatics, Kyoto University. All rights reserved.');

?>
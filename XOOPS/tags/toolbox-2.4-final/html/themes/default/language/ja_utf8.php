<?php

require_once dirname(__FILE__).'/user_'.basename(__FILE__);

//Used by Toolbox
define('_THEME_TOP_UNAME_LB', 'ユーザID');
define('_THEME_TOP_PASSWD_LB', 'パスワード');
define('_THEME_TOP_LOGIN_LB', 'ログイン');
define('_THEME_TOP_LOGOUT_LB', 'ログアウト');
define('_THEME_TOP_NEW_USER_LB', '新規アカウント作成');
define('_MD_USER_LANG_LOSTPASS', 'パスワードを忘れた方');

//user.php
define('_US_LOGGINGU', 'ようこそ、%sさん。');
//20100205 add
define('_US_LOGGIN_GUEST', '');
//20090919 add
define('_THEME_MENU_TEXT00_LB', 'トップ');
define('_THEME_MENU_TEXT01_LB', 'BBS');
define('_THEME_MENU_TEXT02_LB', '翻訳');
define('_THEME_MENU_TEXT03_LB', '作成');
define('_THEME_MENU_TEXT04_LB', 'プロファイル');
define('_THEME_MENU_TEXT05_LB', 'サービス設定');
define('_THEME_HOW_TO_USE', 'How to use');
//20091013 add
define('_THEME_MENU_COMMUNICATION', 'コミュニケーション');
define('_THEME_MENU_TEXT_TRANSLATION', 'テキスト翻訳');
define('_THEME_MENU_SPEECH_TRANSLATION', '音声翻訳');
//20091014 add
define('_THEME_MENU_SETTING', '設定');
//20091020 add
define('_THEME_MENU_IMPORTED_SERVICES', 'サービスのインポート');
//20091022 add
define('_THEME_MENU_SETTING_BBS', 'BBS');
define('_THEME_MENU_SETTING_TEXT_TRANSLATION', 'テキスト翻訳');
define('_THEME_MENU_TEXT_DICTIONARY', '辞書');
define('_THEME_MENU_TEXT_PARALLEL_TEXT', '用例対訳');
//20091112 add
define('_THEME_MENU_WEB_TRANSLATION', 'Web');
define('_THEME_MENU_SETTING_WEB_TRANSLATION', 'Web');
//20091130 add
define('_THEME_SIGNIN_MESSAGE', '新規にToolboxをご利用になる方は，<br />こちらのボタンをクリックしてください');
//20091215 add
define('_THEME_MENU_COMMUNITY', 'コミュニティ');
define('_THEME_MENU_USER_SEARCH', 'ユーザ一覧');
define('_THEME_MENU_TEXT_FILE_MANAGEMENT', 'ファイル共有');
//20100107 add
define('_THEME_MENU_DISUCSSION', 'ディスカッション');
define('_THEME_MENU_QA', 'Q&amp;A');
//20100120 add
define('_THEME_MENU_GLOSSARY', '用語集');
//20100127 add
define('_THEME_MENU_WEB_QA', 'Q&amp;A Web<br />インタフェース');
//20100201 add
define('_THEME_MENU_SHOWROOM_BBS', 'ショールーム<br />(BBS)');
//20100218 add
define('_THEME_MENU_SPECIAL', 'スペシャル');
define('_THEME_MENU_RECEPTION', '受付');
define('_THEME_MENU_TASK_MANAGEMENT', 'タスク管理');
define('_THEME_MENU_COLLABORATION_TRANSLATION', '協調翻訳');
//20100222 add
define('_THEME_POWERED_BY_URL', 'http://langrid.nict.go.jp/jp/index.html');
define('_THEME_POWERED_BY_TOOLBOX_URL', 'http://langrid.nict.go.jp/langrid-toolbox-wiki/');
//20100305 add
define('_THEME_MENU_TEMPLATE', '穴あき用例対訳');
//20100308 add
define('_THEME_MENU_TRANSLATION_WEB', 'Web翻訳');
//20100312 add
define('_THEME_MENU_SHOWROOM_DISUCSSION', 'ショールーム<br />(ディスカッション)');
//20100319 add
define('_THEME_MENU_AUTOCOMPLETE_SETTING', 'Auto complete');
//pagetitle
define('_THEME_PAGETITLE_USERINFO', 'プロファイル');
define('_THEME_MENU_UICUSTOMIZE', '表示言語設定');

define('_THEME_MENU_SETTING_USER', 'サービス設定<br />（個人）');
define('_THEME_MENU_SETTING_SITE', 'サービス設定<br />（共有）');
define('_THEME_MENU_SETTING_USER_NOBR', 'サービス設定（個人）');
define('_THEME_MENU_SETTING_SITE_NOBR', 'サービス設定（共有）');
define('_THEME_MENU_SETTING_SERVER', 'サービス設定（サーバ）');



//Use is not confirmed by Toolbox
//global
define('_HD_ERROR', 'error');
define('_HD_BREADCRUMBS', 'Your position in this site: ');
define('_HD_SKIP_CONTENTS', 'skip main content and read menu of site.');
define('_HD_SKIP_CONTENTS_TARGET', 'end of main content. common menu below.');
define('_HD_SKIP_PAGETOP', 'back to page top');
define('_HD_CHANGE_VIEWMODE', 'view normal design');
define('_HD_SITE_CLOSED', 'Site Closed');
define('_HD_EDIT_FOOTER', 'Edit footer');
define('_HD_CLOSE_WINDOW', 'Close this window');
define('_HD_BACK_TO_FRONTPAGE', 'Go to Frontpage');
define('_HD_INPUTHELPER','Input helper');

//comment
define('_CM_THREAD_TITLE','replies');
define('_CM_POSTCOMMENT_NEW','New Comment');
define('_CM_THREAD_CHOOSE','Thread');
define('_CM_THREAD_EXPLANATION','Curernt: tree mode');
define('_CM_FLAT_CHOOSE','chronological');
define('_CM_FLAT_EXPLANATION','Curernt: thread mode');
define('_CM_TREE_CHOOSE','tree');
define('_CM_TREE_EXPLANATION','Curernt: chronological mode');
define('_CM_OLDER_CHOOSE','older');
define('_CM_NEWER_CHOOSE','newer');
define('_CM_DELETE_TITLE','Delete Comment');
define('_CM_ICON_NORMAL','normal');
define('_CM_ICON_DISSATISFACTION','dissatisfaction');
define('_CM_ICON_SATISFACTION','satisfaction');
define('_CM_ICON_LOWER','untenable');
define('_CM_ICON_UPPER','tenable');
define('_CM_ICON_REPORT','report');
define('_CM_ICON_QUESTION','question');

//notification
define('_NOT_DELETINGNOTIFICATIONS_EXPLANATION', 'If you want to remove selected events, push the "delete" button');

//search
define('_SR_SEARCHRESULTS_BY_MODULES','Search result by each modules');
define('_SR_SEARCHRESULTS_SHOWALL','Search result of all');
define('_SR_SEARCHRESULTS_BY_USER','Search result of %s (Creaer: %s) ');
define('_SR_SEARCHRESULTS_NO_RESULT','No result.');

//user.php
define('_MD_LEGACY_MESSAGE_LOGIN_SUCCESS', 'Welcomes {0}');
define('_US_LOGOUT_CONFIRM','Are you sure?');
define('_THEME_XOOPSCONTENT_USERINFO','Personal information is not allowed to show guest users. <a href="user.php">Please login</a>.');
define('_MI_CUBE_UTILS_LANG_SSL','SSL Login');
define('_HD_USER_REGISTRATION_SUCCEEDED','User registration has been completed');

//legacy
define("_NOT_ACTIVENOTIFICATIONS",  "Active Notifications");//add
define("_NOT_ACTIVENOTIFICATIONS_EXPLANATION",  "You may disable Notifications by checking below.");//add
define("_NOT_ACTIVENOTIFICATIONS_EXPLANATION_NON",  "No Notifications available.");//add
define("_MB_LEGACY_SEND_PM",  "alt=\"Send a private message\"");
define("_MB_LEGACY_THEME_BLOCKTITLE", "Select a theme");
define("_MB_LEGACY_THEME_IMGCHANGE_CANNOT", "You cannot view thumbnails of themes until you turn JavaScript on");
define("_MB_LEGACY_RECO_CANNOT", "You cannot use function of tell a friend until you turn JavaScript on");
define("_MB_LEGACY_DHTMLTEXTAREA_SKIP_START", "Skip edit support of BB Code");
define("_MB_LEGACY_DHTMLTEXTAREA_SKIP_END", "Textarea begins");
define("_MB_LEGACY_DHTMLTEXTAREA_CANNOT", "You cannot use function of edit support of BB Code until you turn JavaScript on");
define("_MB_LEGACY_SMAILY_CANNOT", "You cannot use function of edit support of Smailies until you turn JavaScript on. But you may input smailies like these: :-D, :-), :-(, :-o, :-?, 8-), :lol:, :-x, :-P");

//pm
define('_THEME_PM_MESSAGE_SENT','Done');

//Image Manager
define( '_THEME_IMAGEMANAGER_CATEGORY', "Categories");
define( '_THEME_IMAGEMANAGER_NOPERM', "Choose other categories.");
define( '_THEME_IMAGEMANAGER_NOIMAGE', "Here are no files.");
define( '_THEME_IMAGEMANAGER_NOCAT_GUEST', "Image Manager is not available. Please, ask site administrator.");
define( '_THEME_IMAGEMANAGER_NOCAT_ADMIN', "Image Manager is not available. <a href=\"#\" onclick=\"javascript:window.opener.location='%s/modules/legacy/admin/index.php?action=ImagecategoryList';window.close();\">create at least one category.</a>");

//admin side
define( '_THEME_ADMIN_BLOCKS', "Blocks");
define( '_THEME_ADMIN_GENERAL', "General");
define( '_THEME_ADMIN_MODULES', "Modules");
define( '_THEME_ADMIN_USERS', "Users");
define( '_THEME_ADMIN_TITLE', "Administration Mode");

//pagetitle
define( '_THEME_PAGETITLE_EDITPROFILE', 'Editing your profile' );
define( '_THEME_PAGETITLE_REGISTER', 'Registering to be a user' );
define( '_THEME_PAGETITLE_LOGIN', 'Loggin in' );
define( '_THEME_PAGETITLE_LOSTPASS', 'Password reminder' );

define( '_THEME_PAGETITLE_SEARCH', 'Advanced search' );
define( '_THEME_PAGETITLE_SEARCH_RESULT', 'Search result' );
define( '_THEME_PAGETITLE_SEARCH_RESULT_SHOWALL', 'All of searching result' );
define( '_THEME_PAGETITLE_SEARCH_RESULT_SHOWALLBYUSER', 'All of searching result by user' );
define( '_THEME_PAGETITLE_NOTIFICATIONS', 'Notifications' );
define( '_THEME_PAGETITLE_NOTIFICATIONS_CONFIRM', 'Confirm deleting the notifications' );

define( '_THEME_PAGETITLE_CLICKASMILIE', "Choose Smaily");

define( '_THEME_PAGETITLE_PM_ERROR', "Private Message Error");
define( '_THEME_PAGETITLE_PM_SENT', "Private Message has been sent");
define( '_THEME_PAGETITLE_READPMSG', 'Private Message [%d]' );
define( '_THEME_PAGETITLE_DELPMSG', 'Delete Confirm: Private Message [%d] ' );

define( '_THEME_PAGETITLE_TELLAFRIEND', 'Tell a friend' );
define( '_THEME_PAGETITLE_TELLAFRIEND_ERROR', 'Error' );
define( '_THEME_PAGETITLE_TELLAFRIEND_SENT', 'Told a friend' );

define( '_THEME_PAGETITLE_ONLINE', 'Online users' );

define( '_THEME_PAGETITLE_IMAGEMANAGER', "Image Manager");
define( '_THEME_PAGETITLE_IMAGEMANAGER_UPLOAD', "Image Manager: Uploader");


define('_THEME_TOP_AUTOLOGIN_LB', '次回から入力を省略');
?>

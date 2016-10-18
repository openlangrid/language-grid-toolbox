<?php
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
define('_US_LOGGINGU','Welcome %s');// no need?
define('_MD_LEGACY_MESSAGE_LOGIN_SUCCESS','Welcome {0}');
define('_US_LOGOUT_CONFIRM','Are you sure?');
define('_MD_USER_LANG_WHOSONLINE','You may see user information by clicking username, if you have been permitted.');
define('_THEME_XOOPSCONTENT_USERINFO','Personal information is not allowed to show guest users. <a href="user.php">Please login</a>.');
define('_MI_CUBE_UTILS_LANG_SSL','SSL Login');
define('_MD_USER_LANG_TYPEPASSTWICE','type a new password twice to change it');
define('_HD_USER_REGISTRATION_SUCCEEDED','User registration has been completed');
define('_MD_USER_LANG_LOSTPASS','Lost Password');

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
define( '_THEME_PAGETITLE_USERINFO', 'Account information' );

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

?>

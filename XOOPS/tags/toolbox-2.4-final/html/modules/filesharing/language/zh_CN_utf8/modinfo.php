<?php
// Module Info

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'FSHARE_MI_LOADED' ) ) {

define( 'FSHARE_MI_LOADED' , 1 ) ;

// *** Used by Toolbox *** 
// The name of this module
define('_MD_ALBM_FSHARE_NAME', '[en]File sharing[/en][ja]ファイル共有[/ja][zh-CN]File sharing[/zh-CN][ko]File sharing[/ko]');
// A brief description of this module
define('_MD_ALBM_FSHARE_DESC', 'This module allows users to upload and share files.');

define('_MD_ALBM_CFG_DEFAULTORDER', 'Default order in Folder\'s view');
define('_MD_ALBM_CFG_FSIZE', 'Max file size');
define('_MD_ALBM_CFG_DESCFSIZE', 'The limitation of the size of uploading file (MB).<br />Files whose size exceeds the limitation by the server cannot be uploaded.');
//20100129 add
define('_MD_ALBM_CFG_ROOT_EDIT', 'Root folder edit');
define('_MD_ALBM_OPT_EDIT_ADMIN', 'Admin');
define('_MD_ALBM_OPT_EDIT_ALL', 'All');


// *** Use is not confirmed by Toolbox ***
define( "_MD_ALBM_OPT_USENAME" , "Name" ) ;
define( "_MD_ALBM_OPT_USEUNAME" , "User ID" ) ;

// Names of blocks for this module (Not all module has blocks)
define("_MD_ALBM_BNAME_RECENT","Recent Files");
define("_MD_ALBM_BNAME_HITS","Top Files");
define("_MD_ALBM_BNAME_RANDOM","Random File");
define("_MD_ALBM_BNAME_RECENT_P","Recent Files with thumbnails");
define("_MD_ALBM_BNAME_HITS_P","Top Files with thumbnails");

// Config Items
define( "_MD_ALBM_CFG_FILESPATH" , "Path to files" ) ;
define( "_MD_ALBM_CFG_DESCFILESPATH" , "Path from the directory installed XOOPS.<br />(The first character must be '/'. The last character should not be '/'.)<br />This directory's permission is 777 or 707 in unix." ) ;
define( "_MD_ALBM_CFG_THUMBSPATH" , "Path to thumbnails" ) ;
define( "_MD_ALBM_CFG_DESCTHUMBSPATH" , "Same as 'Path to files'." ) ;
define( "_MD_ALBM_CFG_POPULAR" , "Hits to be Popular" ) ;
define( "_MD_ALBM_CFG_NEWDAYS" , "Days between displaying icon of 'new'&'update'" ) ;
define( "_MD_ALBM_CFG_NEWFILES" , "Number of Files as New on Top Page" ) ;
define( "_MD_ALBM_CFG_PERPAGE" , "Displayed Files per Page" ) ;
define( "_MD_ALBM_CFG_DESCPERPAGE" , "Input selectable numbers separated with '|'<br />eg) 10|20|50|100" ) ;
define( "_MD_ALBM_CFG_ALLOWNOIMAGE" , "Allow a submit without images" ) ;
define( "_MD_ALBM_CFG_MAKETHUMB" , "Make Thumbnail Image" ) ;
define( "_MD_ALBM_CFG_DESCMAKETHUMB" , "When you change 'No' to 'Yes', You'd better 'Redo thumbnails'." ) ;
//define( "_MD_ALBM_CFG_THUMBWIDTH" , "Thumb Image Width" ) ;
//define( "_MD_ALBM_CFG_DESCTHUMBWIDTH" , "The height of thumbs will be decided from the width automatically." ) ;
define( "_MD_ALBM_CFG_THUMBSIZE" , "Size of thumbnails (pixel)" ) ;
define( "_MD_ALBM_CFG_THUMBRULE" , "Calculation rule for building thumbnails" ) ;
define( "_MD_ALBM_CFG_WIDTH" , "Max file width" ) ;
define( "_MD_ALBM_CFG_DESCWIDTH" , "This means the file's width to be resized.<br />If you use GD without truecolor, this means the limitation of width." ) ;
define( "_MD_ALBM_CFG_HEIGHT" , "Max file height" ) ;
define( "_MD_ALBM_CFG_DESCHEIGHT" , "This means the file's height to be resized.<br />If you use GD without truecolor, this means the limitation of height." ) ;
define( "_MD_ALBM_CFG_MIDDLEPIXEL" , "Max image size in single view" ) ;
define( "_MD_ALBM_CFG_DESCMIDDLEPIXEL" , "Specify (width)x(height)<br />(eg. 480x480)" ) ;
define( "_MD_ALBM_CFG_ADDPOSTS" , "The number added User's posts by posting a file." ) ;
define( "_MD_ALBM_CFG_DESCADDPOSTS" , "Normally, 0 or 1. Under 0 mean 0" ) ;
define( "_MD_ALBM_CFG_CATONSUBMENU" , "Register top categories into submenu" ) ;
define( "_MD_ALBM_CFG_NAMEORUNAME" , "Poster name displayed" ) ;
define( "_MD_ALBM_CFG_DESCNAMEORUNAME" , "Select which 'name' is displayed" ) ;
define( "_MD_ALBM_CFG_VIEWCATTYPE" , "Type of view in category" ) ;
define( "_MD_ALBM_CFG_COLSOFTABLEVIEW" , "Number of columns in table view" ) ;
define( "_MD_ALBM_CFG_ALLOWEDEXTS" , "File extensions that can be uploaded" ) ;
define( "_MD_ALBM_CFG_DESCALLOWEDEXTS" , "Input extensions with separator '|'. (eg 'jpg|jpeg|gif|png') .<br />All characters must be lowercase. Don't insert periods or spaces<br />Never add php or phtml etc." ) ;
define( "_MD_ALBM_CFG_ALLOWEDMIME" , "MIME Types can be uploaded" ) ;
define( "_MD_ALBM_CFG_DESCALLOWEDMIME" , "Input MIME Types with separator '|'. (eg 'image/gif|image/jpeg|image/png')<br />If you want to be checked by MIME Type, leave this blank" ) ;
define( "_MD_ALBM_CFG_USESITEIMG" , "Use [siteimg] in ImageManager Integration" ) ;
define( "_MD_ALBM_CFG_DESCUSESITEIMG" , "The Integrated Image Manager input [siteimg] instead of [img].<br />You have to hack module.textsanitizer.php for each module to enable tag of [siteimg]" ) ;

define( "_ALBUM_OPT_CALCFROMWIDTH" , "width:specified  height:auto" ) ;
define( "_ALBUM_OPT_CALCFROMHEIGHT" , "width:auto  width:specified" ) ;
define( "_ALBUM_OPT_CALCWHINSIDEBOX" , "put in specified size squre" ) ;

define( "_MD_ALBM_OPT_VIEWLIST" , "List View" ) ;
define( "_MD_ALBM_OPT_VIEWTABLE" , "Table View" ) ;


// Sub menu titles
define("_MD_ALBM_TEXT_SMNAME1","Submit");
define("_MD_ALBM_TEXT_SMNAME2","Popular");
define("_MD_ALBM_TEXT_SMNAME3","Top Rated");
define("_MD_ALBM_TEXT_SMNAME4","My Files");

// Names of admin menu items
define("_MD_ALBM_FSHARE_ADMENU0","Submitted Files");
define("_MD_ALBM_FSHARE_ADMENU1","File Management");
define("_MD_ALBM_FSHARE_ADMENU2","Category Management");		//mod 091228
define("_MD_ALBM_FSHARE_ADMENU_GPERM","Global Permissions");
define("_MD_ALBM_FSHARE_ADMENU3","Check Configuration & Environment");
define("_MD_ALBM_FSHARE_ADMENU4","Batch Register");
define("_MD_ALBM_FSHARE_ADMENU5","Rebuild Thumbnails");
define("_MD_ALBM_FSHARE_ADMENU_IMPORT","Import Images");
define("_MD_ALBM_FSHARE_ADMENU_EXPORT","Export Images");
define("_MD_ALBM_FSHARE_ADMENU_MYBLOCKSADMIN","Blocks & Groups Admin");
define("_MD_ALBM_FSHARE_ADMENU_MYTPLSADMIN","Templates");


// Text for notifications
define('_MI_FSHARE_GLOBAL_NOTIFY', 'Global');
define('_MI_FSHARE_GLOBAL_NOTIFYDSC', 'Global notification options with myAlbum-P');
define('_MI_FSHARE_CATEGORY_NOTIFY', 'Category');
define('_MI_FSHARE_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current file category');
define('_MI_FSHARE_FILE_NOTIFY', 'File');
define('_MI_FSHARE_FILE_NOTIFYDSC', 'Notification options that apply to the current file');

define('_MI_FSHARE_GLOBAL_NEWFILE_NOTIFY', 'New File');
define('_MI_FSHARE_GLOBAL_NEWFILE_NOTIFYCAP', 'Notify me when any new files are posted');
define('_MI_FSHARE_GLOBAL_NEWFILE_NOTIFYDSC', 'Receive notification when a new file description is posted.');
define('_MI_FSHARE_GLOBAL_NEWFILE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New file');

define('_MI_FSHARE_CATEGORY_NEWFILE_NOTIFY', 'New File');
define('_MI_FSHARE_CATEGORY_NEWFILE_NOTIFYCAP', 'Notify me when a new file is posted to the current category');
define('_MI_FSHARE_CATEGORY_NEWFILE_NOTIFYDSC', 'Receive notification when a new file description is posted to the current category');
define('_MI_FSHARE_CATEGORY_NEWFILE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New file');

}

?>
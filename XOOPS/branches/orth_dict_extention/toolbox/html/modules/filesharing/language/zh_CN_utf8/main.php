<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'FSHARE_MB_LOADED' ) ) {

define( 'FSHARE_MB_LOADED' , 1 ) ;

//%%%%%%		Module Name 'myAlbum-P'		%%%%%

// *** Used by Toolbox ***
define('_MD_ALBM_FILEMANAGEMENT', 'File sharing');
define('_MD_ALBM_HOW_TO_USE_LINK', 'filesharing_en.html');
define('_MD_ALBM_UPDATENOW', 'Update');

// only "Y/m/d" , "d M Y" , "M d Y" can be interpreted
define('_MD_ALBM_DTFMT_YMDHI', 'd M Y H:i');

define('_MD_ALBM_DIRECTCATSEL', 'Select a category');
define('_MD_ALBM_THEREARE', 'There are <b>%s</b> files');
define('_MD_ALBM_LATESTLIST', 'Latest listings');
define('_MD_ALBM_ADDFILE', 'Upload file');
define('_MD_ALBM_AM_FILENAVINFO', 'File No. %s-%s (out of %s files)');
define('_MD_ALBM_EDITTHISFILE', 'Edit');
define('_MD_ALBM_NEW', 'New');
define('_MD_ALBM_UPDATED', 'Updated');
define('_MD_ALBM_POPULAR', 'Popular');
define('_MD_ALBM_SUBMITTER', 'Submitter');
define('_MD_ALBM_MOREFILES', 'More files uploaded by %s');
define('_MD_ALBM_SORTBY', 'Sort by:');
define('_MD_ALBM_FILENAME', 'Filename');
define('_MD_ALBM_DATE', 'Date');
define('_MD_ALBM_CURSORTEDBY', 'Currently sorted by: %s');
define('_MD_ALBM_FILENAMEATOZ', 'Filename (ascending order)');
define('_MD_ALBM_FILENAMEZTOA', 'Filename (descending order)');
define('_MD_ALBM_DATEOLD', 'Date (ascending order)');
define('_MD_ALBM_DATENEW', 'Date (descending order)');
define('_MD_ALBM_FILEDESC', 'Description');
define('_MD_ALBM_FILECAT', 'Category');
define('_MD_ALBM_SELECTFILE', 'File');
define('_MD_ALBM_NOIMAGESPECIFIED', 'No file was selected. Please select a file to upload.');
define('_MD_ALBM_FILEERROR', 'The selected file is not found or the size of the file exceeded the limit.');
define('_MD_ALBM_FILEREADERROR', 'Can\'t read the uploaded file.');
define('_MD_ALBM_FILEDEL', 'Are you sure to remove this file?');
define('_MD_ALBM_AM_ADMITTING', 'The selected files are admitted ');
define('_MD_ALBM_AM_LABEL_REMOVE', 'Remove files checked');
define('_MD_ALBM_AM_JS_REMOVECONFIRM', 'Are you sure to remove?');
define('_MD_ALBM_AM_BUTTON_UPDATE', 'Modify');
define('_MD_ALBM_AM_DEADLINKMAINFILE', 'The main image don\'t exist');
define('_MD_ALBM_BTN_SELECTALL', 'Select all');
define('_MD_ALBM_BTN_SELECTNONE', 'Select none');
define('_MD_ALBM_FMT_FILENUM', '%s / page');
define('_MD_ALBM_AM_LABEL_ADMIT', 'Admit checked files');
define('_MD_ALBM_AM_BUTTON_ADMIT', 'Admit');
define('_MD_ALBM_AM_BUTTON_EXTRACT', 'Search');
define('_MD_ALBM_VALIDFILE', 'Valid');
define('_MD_ALBM_DELETINGFILE', 'Deleting ...');
define('_MD_ALBM_STORETIMESTAMP', 'Don\'t touch timestamp');
define('_MD_ALBM_DESCRIPTION', 'Description');
define('_MD_ALBM_DESCRIPTIONC', 'Description');
define('_MD_ALBM_RECEIVED', 'The file was successfully uploaded.');
define('_MD_ALBM_NOMATCH', 'No file exists in this folder.');
define('_MD_ALBM_SUBMIT', 'Submit');
define('_MD_ALBM_MUSTREGFIRST', 'Sorry, you don\'t have permission to perform this action.<br>Please register or login first!');
define('_MD_ALBM_MUSTADDCATFIRST', 'Please create a category before uploading a file.');
define('_MD_ALBM_GROUPPERM_GLOBAL', 'Global permissions');
define('_MD_ALBM_DBUPDATED', 'Database Updated Successfully!');
define('_MD_ALBM_CATDELETED', 'The category was deleted.');
define('_MD_ALBM_NAME', 'Name');
define('_MD_ALBM_NEWUPDATE', 'Last update');
define('_MD_ALBM_LASTUPDATEC', 'Last update');
define('_MD_ALBM_FILEUPLOAD', 'Upload file');
define('_MD_ALBM_MAIN', 'File sharing top');
define('_MD_ALBM_MAXSIZE', 'Maximum size');
define('_MD_ALBM_FILEEDITUPLOAD', 'Edit file');
define('_MD_ALBM_FILEDELETE', 'Delete file');
define('_MD_ALBM_NEWFILES', 'File list');
define('_MD_ALBM_INPUT_ALERT_JS', 'Please select %1');
define('_MD_ALBM_FILESIZE_UNIT', 'MB');
define('_MD_ALBM_BTN_OK', 'OK');
define('_MD_ALBM_AM_BUTTON_REMOVE', 'Delete');
define('_MD_ALBM_BTN_BACK', 'Back');
//20100129 add
define('_MD_ALBM_ADDFOLDER', 'Create folder');
define('_MD_ALBM_FOLDER_CREATE', 'Create folder');
define('_MD_ALBM_READ', 'Read');
define('_MD_ALBM_EDIT', 'Edit');
define('_MD_ALBM_FOLDER', 'Folder');
define('_MD_ALBM_FOLDER_NAME', 'Folder name');
define('_MD_ALBM_NO_FOLDER_NAME_ERROR', 'Please input folder name.');
define('_MD_ALBM_PARENT_FOLDER', 'Parent folder');
define('_MD_ALBM_EDIT_PERMISSION', 'Edit permission');
define('_MD_ALBM_READ_PERMISSION', 'Read permission');
define('_MD_ALBM_FOR_ALL_USERS', 'For all users');
define('_MD_ALBM_FOR_THE_CURRENT_USER_ONLY', 'For the current user only');
define('_MD_ALBM_NOT_FOLDER_PERMISSION', 'You have no permission to save in this folder.');
define('_MD_ALBM_NOT_PARENT_FOLDER_PERMISSION', 'No edit permission in parent folder.');
define('_MD_ALBM_OWNER', 'Owner');
define('_MD_ALBM_FOLDER_EDIT', 'Edit folder');
define('_MD_ALBM_FOLDER_DELETE', 'Delete folder');
define('_MD_ALBM_NOT_DELETE_PERMISSION', 'It is not possible to delete it because there is a folder or a file that the subordinate cannot edit.');
define('_MD_ALBM_NOT_MOVE_FOLDER', 'It is not possible to move it because there is a folder or a file that the subordinate cannot edit.');
define('_MD_ALBM_FOLDER_SAME_NAME_ERROR', 'The folder of the same name already exists.');
define('_MD_ALBM_FILE_SAME_NAME_ERROR', 'The file of the same name already exists.');
define('_MD_ALBM_BTN_CLOSE', 'Close');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright(C) 2009-2010 Language Grid Project, NICT. All rights reserved.');

// Use is Dialog
define('_MI_FILESHARINGDIALOG_DIALOG_TITLE', 'Select a file');
define('_MI_FILESHARINGDIALOG_UPLOAD_BUTTON', 'Upload a file');
define('_MI_FILESHARINGDIALOG_TABLE_COL_SELECT', '');
define('_MI_FILESHARINGDIALOG_TABLE_COL_NAME', 'File name');
define('_MI_FILESHARINGDIALOG_TABLE_COL_DESC', 'Description');
define('_MI_FILESHARINGDIALOG_TABLE_COL_READ', 'Read');
define('_MI_FILESHARINGDIALOG_TABLE_COL_EDIT', 'Edit');
define('_MI_FILESHARINGDIALOG_TABLE_COL_UPDATER', 'Created by');
define('_MI_FILESHARINGDIALOG_TABLE_COL_UPDATE', 'Last update');
define('_MI_FILESHARINGDIALOG_OK_BUTTON', 'OK');
define('_MI_FILESHARINGDIALOG_CANCEL_BUTTON', 'Cancel');
define('_MI_FILESHARINGDIALOG_SAVE_DIALOG_TITLE', 'Save a file');
define('_MI_FILESHARINGDIALOG_SAVE_FILE_NAME', 'File name');
define('_MI_FILESHARINGDIALOG_SAVE_FILE_DESC', 'Description');
define('_MI_FILESHARINGDIALOG_SAVE_PERM', 'Permission');
define('_MI_FILESHARINGDIALOG_SAVE_READ_PERM', 'Read permission');
define('_MI_FILESHARINGDIALOG_SAVE_EDIT_PERM', 'Edit permission');
define('_MI_FILESHARINGDIALOG_SAVE_PERM_OPT_PUBLIC', 'For all users');
define('_MI_FILESHARINGDIALOG_SAVE_PERM_OPT_USER', 'For the current user only');
define('_MI_FILESHARINGDIALOG_SAVE_OK_BUTTON', 'Save');
define('_MI_FILESHARINGDIALOG_ROOT_FOLDER_LABEL', 'Top');
define('_MI_FILESHARINGDIALOG_ERROR_PERMISSION_NO_EDIT', 'You have no permission to save in this folder.');

// *** Use is not confirmed by Toolbox ***
define( "_MD_ALBM_NEXT_BUTTON" , "Next" ) ;
define( "_MD_ALBM_REDOLOOPDONE" , "Done." ) ;
define( "_MD_ALBM_BTN_SELECTRVS" , "Select Reverse" ) ;
define( "_MD_ALBM_AM_ADMISSION" , "Admit Files" ) ;
define( "_MD_ALBM_AM_FILEMANAGER" , "File Manager" ) ;
define( "_MD_ALBM_AM_LABEL_MOVE" , "Change category of the checked files" ) ;
define( "_MD_ALBM_AM_BUTTON_MOVE" , "Move" ) ;
define( "_MD_ALBM_RADIO_ROTATETITLE" , "Image rotation" ) ;
define( "_MD_ALBM_RADIO_ROTATE0" , "no turn" ) ;
define( "_MD_ALBM_RADIO_ROTATE90" , "turn right" ) ;
define( "_MD_ALBM_RADIO_ROTATE180" , "turn 180 degree" ) ;
define( "_MD_ALBM_RADIO_ROTATE270" , "turn left" ) ;

// New MyAlbum 1.0.1 (and 1.2.0)
define( "_MD_ALBM_REDOTHUMBS","Redo Thumbnails (<a href='redothumbs.php'>re-start</a>)");
define( "_MD_ALBM_REDOTHUMBS2","Rebuild Thumbnails");
define( "_MD_ALBM_REDOTHUMBSINFO","Too large a number may lead to server time out.");
define( "_MD_ALBM_REDOTHUMBSNUMBER","Number of thumbs at a time");
define( "_MD_ALBM_REDOING","Redoing: ");
define( "_MD_ALBM_BACK","Return");

// New MyAlbum 1.0.0
define( "_MD_ALBM_FILEBATCHUPLOAD","Register files uploaded to the server already");
define( "_MD_ALBM_MAXPIXEL","Max pixel size");
define( "_MD_ALBM_FILEPATH","Path");
define( "_MD_ALBM_TEXT_DIRECTORY","Directory");
define( "_MD_ALBM_DESC_FILEPATH","Type the full path of the directory including files to be registered");
define( "_MD_ALBM_MES_INVALIDDIRECTORY","Invalid directory is specified.");
define( "_MD_ALBM_MES_BATCHDONE","%s file(s) have been registered.");
define( "_MD_ALBM_MES_BATCHNONE","No file was detected in the directory.");
define( "_MD_ALBM_BATCHBLANK","Leave title blank to use file names as title");
define( "_MD_ALBM_DELETEFILE","Delete?");
define( "_MD_ALBM_MOVINGFILE","Moving file");

define( "_MD_ALBM_POSTERC","Poster: ");
define( "_MD_ALBM_DATEC","Date: ");
define( "_MD_ALBM_EDITNOTALLOWED","You're not allowed to edit this comment!");
define( "_MD_ALBM_ANONNOTALLOWED","Anonymous users are not allowed to post.");
define( "_MD_ALBM_THANKSFORPOST","Thanks for your submission!");
define( "_MD_ALBM_DELNOTALLOWED","You're not allowed to delete this comment!");
define( "_MD_ALBM_GOBACK","Go Back");
define( "_MD_ALBM_AREYOUSURE","Are you sure you want to delete this comment and all comments under it?");
define( "_MD_ALBM_COMMENTSDEL","Comment(s) Deleted Successfully!");

// End New

define( "_MD_ALBM_THANKSFORINFO","Thank you for the information. We'll look into your request shortly.");
define( "_MD_ALBM_BACKTOTOP","Back to File Top");
define( "_MD_ALBM_THANKSFORHELP","Thank you for helping to maintain this directory's integrity.");
define( "_MD_ALBM_FORSECURITY","For security reasons your user name and IP address will also be temporarily recorded.");

define( "_MD_ALBM_MATCH","Match");
define( "_MD_ALBM_ALL","ALL");
define( "_MD_ALBM_ANY","ANY");

define( "_MD_ALBM_TOPRATED","Top Rated");

define( "_MD_ALBM_POPULARITYLTOM","Popularity (Least to Most Hits)");
define( "_MD_ALBM_POPULARITYMTOL","Popularity (Most to Least Hits)");
define( "_MD_ALBM_TITLEATOZ","Title (A to Z)");
define( "_MD_ALBM_TITLEZTOA","Title (Z to A)");
define( "_MD_ALBM_RATINGLTOH","Rating (Lowest Score to Highest Score)");
define( "_MD_ALBM_RATINGHTOL","Rating (Highest Score to Lowest Score)");
define( "_MD_ALBM_LIDASC","Record Number (Smaller to Bigger)");
define( "_MD_ALBM_LIDDESC","Record Number (Smaller is latter)");

define( "_MD_ALBM_NOSHOTS","No Screenshots Available");

define( "_MD_ALBM_EMAILC","Email");
define( "_MD_ALBM_CATEGORYC","Category");
define( "_MD_ALBM_TELLAFRIEND","Tell a friend");
define( "_MD_ALBM_SUBJECT4TAF","A file for you!");
define( "_MD_ALBM_HITSC","Hits");
define( "_MD_ALBM_RATINGC","Rating");
define( "_MD_ALBM_ONEVOTE","1 vote");
define( "_MD_ALBM_NUMVOTES","%s votes");
define( "_MD_ALBM_ONEPOST","1 post");
define( "_MD_ALBM_NUMPOSTS","%s posts");
define( "_MD_ALBM_COMMENTSC","Comments");
define( "_MD_ALBM_RATETHISFILE","Rate it");
define( "_MD_ALBM_MODIFY","Modify");
define( "_MD_ALBM_VSCOMMENTS","View/Send Comments");


define( "_MD_ALBM_VOTEAPPRE","Your vote is appreciated.");
define( "_MD_ALBM_THANKURATE","Thank you for taking the time to rate a file here at %s.");
define( "_MD_ALBM_VOTEONCE","Please do not vote for the same resource more than once.");
define( "_MD_ALBM_RATINGSCALE","The scale is 1 - 10, with 1 being poor and 10 being excellent.");
define( "_MD_ALBM_BEOBJECTIVE","Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
define( "_MD_ALBM_DONOTVOTE","Do not vote for your own resource.");
define( "_MD_ALBM_RATEIT","Rate It!");

define( "_MD_ALBM_ALLPENDING","All files are posted pending verification.");

define( "_MD_ALBM_RANK","Rank");
define( "_MD_ALBM_CATEGORY","Category");
define( "_MD_ALBM_SUBCATEGORY","Sub-category");
define( "_MD_ALBM_HITS","Hits");
define( "_MD_ALBM_RATING","Rating");
define( "_MD_ALBM_VOTE","Vote");
define( "_MD_ALBM_TOP10","%s Top 10"); // %s is a file category title

define( "_MD_ALBM_TITLE","Title");
define( "_MD_ALBM_POPULARITY","Popularity");
define( "_MD_ALBM_FOUNDIN","Found in:");
define( "_MD_ALBM_PREVIOUS","Previous");
define( "_MD_ALBM_NEXT","Next");

define( "_MD_ALBM_CATEGORIES","Categories");

define( "_MD_ALBM_CANCEL","Cancel");

define( "_MD_ALBM_NORATING","No rating selected.");
define( "_MD_ALBM_CANTVOTEOWN","You cannot vote on the resource you submitted.<br>All votes are logged and reviewed.");
define( "_MD_ALBM_VOTEONCE2","Vote for the selected resource only once.<br>All votes are logged and reviewed.");

//%%%%%%	Module Name 'MyAlbum' (Admin)	  %%%%%

define( "_MD_ALBM_FILESWAITING","Files Waiting for Validation");
define( "_MD_ALBM_FILEMANAGER","File Management");
define( "_MD_ALBM_CATEDIT","Add, Modify, and Delete Categories");
define( "_MD_ALBM_CHECKCONFIGS","Check Configs & Environment");
define( "_MD_ALBM_BATCHUPLOAD","Batch Register");
define( "_MD_ALBM_GENERALSET","Preferences");

define( "_MD_ALBM_DELETE","Delete");
define( "_MD_ALBM_NOSUBMITTED","No New Submitted Files.");
define( "_MD_ALBM_ADDMAIN","Add a MAIN Category");
define( "_MD_ALBM_IMGURL","Image URL (OPTIONAL Image height will be resized to 50): ");
define( "_MD_ALBM_ADD","Add");
define( "_MD_ALBM_ADDSUB","Add a SUB-Category");
define( "_MD_ALBM_IN","in");
define( "_MD_ALBM_MODCAT","Modify Category");
define( "_MD_ALBM_MODREQDELETED","Modification Request Deleted.");
define( "_MD_ALBM_IMGURLMAIN","Image URL (OPTIONAL and Only valid for main categories. Image height will be resized to 50): ");
define( "_MD_ALBM_PARENT","Parent Category:");
define( "_MD_ALBM_SAVE","Save Changes");
define( "_MD_ALBM_CATDEL_WARNING","WARNING: Are you sure you want to delete this Category and ALL its Files and Comments?");
define( "_MD_ALBM_YES","Yes");
define( "_MD_ALBM_NO","No");
define( "_MD_ALBM_NEWCATADDED","New Category Added Successfully!");
define( "_MD_ALBM_ERROREXIST","ERROR: The File you provided is already in the database!");
define( "_MD_ALBM_ERRORTITLE","ERROR: You need to enter a TITLE!");
define( "_MD_ALBM_ERRORDESC","ERROR: You need to enter a DESCRIPTION!");
define( "_MD_ALBM_WEAPPROVED","We approved your link submission to the file database.");
define( "_MD_ALBM_THANKSSUBMIT","Thank you for your submission!");
define( "_MD_ALBM_CONFUPDATED","Configuration Updated Successfully!");

}

?>
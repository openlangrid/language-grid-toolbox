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

// language file (modinfo.php)
$langmanpath = XOOPS_TRUST_PATH.'/libs/altsys/class/D3LanguageManager.class.php' ;
if( ! file_exists( $langmanpath ) ) die( 'install the latest altsys' ) ;
require_once( $langmanpath ) ;
$langman =& D3LanguageManager::getInstance() ;
$langman->read( 'modinfo.php' , $mydirname , $mytrustdirname , false ) ;

$constpref = '_MI_' . strtoupper( $mydirname ) ;

$modversion['name'] = _MI_COMMUNICATION_MODULE_NAME;
$modversion['description'] = _MI_COMMUNICATION_MODULE_DESCRIPTION;
$modversion['version'] = 1.00 ;
$modversion['credits'] = "PEAK Corp. and JIDAIKOBO";
$modversion['author'] = "Language Grid Project, Infonic" ;
$modversion['help'] = "" ;
$modversion['license'] = "GPL" ;
$modversion['official'] = 0 ;
$modversion['image'] = file_exists( $mydirpath.'/module_icon.png' ) ? 'module_icon.png' : 'module_icon.php' ;
$modversion['dirname'] = $mydirname ;

// Any tables can't be touched by modulesadmin.
$modversion['sqlfile'] = false ;
$modversion['tables'] = array() ;

//$modversion['cube_style'] = true;
// Admin things
$modversion['hasAdmin'] = 1 ;
$modversion['adminindex'] = 'admin/index.php' ;
$modversion['adminmenu'] = 'admin/admin_menu.php' ;

// Search
$modversion['hasSearch'] = 1 ;
$modversion['search']['file'] = 'search.php' ;
$modversion['search']['func'] = $mydirname.'_global_search' ;

// Menu
$modversion['hasMain'] = 1 ;
$modversion['read_any'] = true ;

// Submenu (just for mainmenu)
$modversion['sub'] = array() ;
if( is_object( @$GLOBALS['xoopsModule'] ) && $GLOBALS['xoopsModule']->getVar('dirname') == $mydirname ) {
	require_once dirname(__FILE__).'/include/common_functions.php' ;
	$modversion['sub'] = communication_get_submenu( $mydirname ) ;
}

// All Templates can't be touched by modulesadmin.
$modversion['templates'] = array();

// Blocks
$modversion['blocks'][1] = array(
	'file'			=> 'blocks.php' ,
	'name'			=> constant($constpref.'_BNAME_LIST_TOPICS') ,
	'description'	=> constant($constpref.'_BDESC_LIST_TOPICS') ,
	'show_func'		=> 'b_d3forum_list_topics_show' ,
	'edit_func'		=> 'b_d3forum_list_topics_edit' ,
	'options'		=> "$mydirname|10|1|time|1|0||" ,
	'template'		=> '' , // use "module" template instead
	'visible_any'	=> true ,
	'can_clone'		=> true ,
) ;

$modversion['blocks'][2] = array(
	'file'			=> 'blocks.php' ,
	'name'			=> constant($constpref.'_BNAME_LIST_POSTS') ,
	'description'	=> '' ,
	'show_func'		=> 'b_d3forum_list_posts_show' ,
	'edit_func'		=> 'b_d3forum_list_posts_edit' ,
	'options'		=> "$mydirname|10|time|0||" ,
	'template'		=> '' , // use "module" template instead
	'visible_any'	=> true ,
	'can_clone'		=> true ,
) ;

$modversion['blocks'][3] = array(
	'file'			=> 'blocks.php' ,
	'name'			=> constant($constpref.'_BNAME_LIST_FORUMS') ,
	'description'	=> '' ,
	'show_func'		=> 'b_d3forum_list_forums_show' ,
	'edit_func'		=> 'b_d3forum_list_forums_edit' ,
	'options'		=> "$mydirname|0|" ,
	'template'		=> '' , // use "module" template instead
	'visible_any'	=> true ,
	'can_clone'		=> true ,
) ;

// Comments
$modversion['hasComments'] = 0 ;

// Configs
$modversion['hasConfig']=1;

$constpref = '_MI_FORUM';
$modversion['config'][] = array(
    "name"=>"file_limit_size",
//    "title"=>constant($constpref."_FILE_LIMIT_SIZE"),
    "title"=>$constpref."_FILE_LIMIT_SIZE",
//"title"=>$limitSize,
    //		"description"=>$constpref."_DESCRIPTION_LIMIT_SIZE",
    "description"=>$constpref."_DESC_LIMIT_SIZE",
    'formtype'		=> 'textbox' ,
    "valuetype"=>"int",
    "default"=>"10000",
    'options'		=> array()
    ) ;


$modversion['config'][] = array(
    "name"=>"file_limit_number",
//    "title"=>constant($constpref."_FILE_LIMIT_NUMBER"),
    "title"=>$constpref."_FILE_LIMIT_NUMBER",
    'formtype'		=> 'textbox' ,
    "valuetype"=>"int",
    "default"=>"10",
    'options'		=> array()
    ) ;

//$modversion['config'][] = array(
	//'name'			=> 'autoUpdateIntevalTime' ,
	//'title'			=> 'autoUpdateIntevalTime' ,
	//'description'	=> '' ,
	//'formtype'		=> 'textbox' ,
	//'valuetype'		=> 'int' ,
	//'default'		=> 360,
	//'options'		=> array()
//);
//$modversion['config'][] = array(
	//'name'			=> 'manualUpdateIntevalTime' ,
	//'title'			=> 'manualUpdateIntevalTime' ,
	//'description'	=> '' ,
	//'formtype'		=> 'textbox' ,
	//'valuetype'		=> 'int' ,
	//'default'		=> 360,
	//'options'		=> array()
//);
$modversion['config'][] = array(
    'name'=>"manualUpdateIntevalTime",
//    'title'=>constant($constpref."_MESSAGE_TIME_MANUAL"),
    'title'=>$constpref."_MESSAGE_TIME_MANUAL",
    'description'=>$constpref."_DESCRIPTION_AN_HOUR",
    'formtype'		=> 'textbox' ,
    'valuetype'=>"int",
    'default'		=> "360",
    ) ;


$modversion['config'][] = array(
    "name"=>"autoUpdateIntevalTime",
//    "title"=>constant($constpref."_MESSAGE_TIME_AUTO"),
    "title"=>$constpref."_MESSAGE_TIME_AUTO",
    "description"=>$constpref."_DESCRIPTION_AN_HOUR",
    'formtype'		=> 'textbox' ,
    "valuetype"=>"int",
    "default"=>"360",
    'options'		=> array()
    ) ;

$modversion['config'][] = array(
	'name'			=> "topicAccessLogSaveMaxTime",
//    "title"=>constant($constpref."_MESSAGE_TIME_MAX"),
    "title"=>$constpref."_MESSAGE_TIME_MAX",
	'description'	=> $constpref."_DESCRIPTION_AN_HOUR" ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'int' ,
	'default'		=> 360,
	'options'		=> array()
);

$modversion['config'][] = array(
    'name'=>"fileSharingCategoryID",
    'title'=>$constpref."_MESSAGE_FILE_CAT_ID",
    'description'  =>'',
    'formtype'     => 'textbox' ,
    'valuetype'    =>"int",
    'default'      => "",
    ) ;


// Notification
$modversion['hasNotification'] = 0;

$modversion['onInstall'] = 'oninstall.php' ;
$modversion['onUpdate'] = 'onupdate.php' ;
$modversion['onUninstall'] = 'onuninstall.php' ;


?>
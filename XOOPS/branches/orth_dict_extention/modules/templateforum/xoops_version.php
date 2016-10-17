<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
$mydirname = basename( dirname( __FILE__ ) ) ;
$mydirpath = dirname( __FILE__ ) ;
require $mydirpath.'/mytrustdirname.php' ; // set $mytrustdirname

// language file (modinfo.php)
$langmanpath = XOOPS_TRUST_PATH.'/libs/altsys/class/D3LanguageManager.class.php' ;
if( ! file_exists( $langmanpath ) ) die( 'install the latest altsys' ) ;
require_once( $langmanpath ) ;
$langman =& D3LanguageManager::getInstance() ;
$langman->read( 'modinfo.php' , $mydirname , $mytrustdirname , false ) ;
//$langPath=$mydirpath."/language/ja_utf8/modinfo.php";
//require_once($langPath);
//
$constpref = '_MI_' . strtoupper( $mydirname ) ;

$modversion['name'] = constant($constpref.'_NAME') ;
$modversion['description'] = constant($constpref.'_DESC') ;
$modversion['version'] = 1.05;
$modversion['credits'] = "Credits";
$modversion['author'] = "Language Grid Project, NICT";
$modversion['help'] = "";
$modversion['license'] = "GPL";
$modversion['official'] = 0;
//$modversion['image'] = file_exists( $mydirpath.'/module_icon.png' ) ? 'module_icon.png' : 'module_icon.php' ;
$modversion['image'] = 'module_icon.png';
$modversion['dirname'] = $mydirname;

// Any tables can't be touched by modulesadmin.
$modversion['sqlfile'] = false;

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
//$modversion["sub"][1]["name"]="BBS設定";
//$modversion["sub"][1]["url"]=index.php;
//
$modversion['read_any'] = true ;

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
    "title"=>$constpref."_FILE_LIMIT_NUMBER",
    'formtype'		=> 'textbox' ,
    "valuetype"=>"int",
    "default"=>"10",
    'options'		=> array()
    ) ;

$modversion['config'][] = array(
    'name'=>"manualUpdateIntevalTime",
    'title'=>$constpref."_MESSAGE_TIME_MANUAL",
    'description'=>$constpref."_DESCRIPTION_AN_HOUR",
    'formtype'		=> 'textbox' ,
    'valuetype'=>"int",
    'default'		=> "360",
    ) ;


$modversion['config'][] = array(
    "name"=>"autoUpdateIntevalTime",
    "title"=>$constpref."_MESSAGE_TIME_AUTO",
    "description"=>$constpref."_DESCRIPTION_AN_HOUR",
    'formtype'		=> 'textbox' ,
    "valuetype"=>"int",
    "default"=>"360",
    'options'		=> array()
    ) ;

$modversion['config'][] = array(
	'name'			=> "topicAccessLogSaveMaxTime",
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

$modversion['config'][] = array(
    'name'=>"notifyLimit",
    'title'=>$constpref."_NOTIFY_LIMIT",
    'description'  =>'',
    'formtype'     => 'textbox' ,
    'valuetype'    =>"int",
    'default'      => "120",
    ) ;

$modversion['config'][] = array(
    'name'=>"bbsTemplate",
    'title'=>$constpref."_BBS_TEMPLATE",
    'description'  => '',
    'formtype'     => 'textarea' ,
    'valuetype'    => "string",
    'default'      => <<<JSON
[
    {
        'name':'{*}Activity report{/*}{ja}活動レポート{/ja}',
        'forums':[3],
        'template':
        [
            {
                'label':'{*}Report date{/*}{ja}活動日{/ja}',
                'type':'input',
                'options':{'type':'text','size':'72'},
                'default':''
            },
            {
                'label':'{*}Question for the YMC system{/*}{ja}YMCシステムに追加したい質問{/ja}',
                'type':'textarea',
                'options':{'column':'72','rows':'4'},
                'default':''
            },
            {
                'label':'{*}About missing{/*}{ja}YMCシステムに追加したい質問{/ja}',
                'type':'textarea',
                'options':{'column':'72','rows':'4'},
                'default':''
            },
            {
                'label':'{*}Rating of the activity{/*}{ja}本活動の評価{/ja}',
                'type':'select',
                'values':['{*}1: Best{/*}{ja}1: 非常に良い{/ja}',
                          '{*}2: Better{/*}{ja}2: 良い{/ja}',
                          '{*}3: Good{/*}{ja}3: まずまず{/ja}',
                          '{*}4: NoGood{/*}{ja}4: 良くない{/ja}',
                          '{*}5: Bad{/*}{ja}5: 悪い{/ja}'],
                'options':{},
                'default':'{*}3: Good{/*}{ja}3: まずまず{/ja}'
            }
        ]
    },
    {
        'name':'{*}Ask for Expert{/*}{ja}Expertへの質問{/ja}',
        'forums':[1,2],
        'template':
        [
            {
                'label':'{*}Thread ID{/*}{ja}質問番号{/ja}',
                'type':'input',
                'options':{'type':'text','size':'10'},
                'default':''
            },
            {
                'label':'{*}Description{/*}{ja}本文{/ja}',
                'type':'textarea',
                'options':{'column':'72','rows':'4'},
                'default':''
            }
        ]
    },
    {
        'name':'{*}Other{/*}{ja}その他{/ja}',
        'forums':[],
        'template':
        [
            {
                'label':'{*}Message{/*}{ja}メッセージ{/ja}',
                'type':'textarea',
                'options':{'column':'72','rows':'4'},
                'default':''
            }
        ]
    }
]
JSON
    ) ;


// Notification
$modversion['hasNotification'] = 0;

$modversion['onInstall'] = 'oninstall.php' ;
$modversion['onUpdate'] = 'onupdate.php' ;
$modversion['onUninstall'] = 'onuninstall.php' ;


?>
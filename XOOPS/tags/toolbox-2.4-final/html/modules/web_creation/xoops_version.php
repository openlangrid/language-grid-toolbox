<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2010  NICT Language Grid Project
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

//require_once dirname(__FILE__).'/common.php';

// Base
$modversion['name'] = _MI_WEB_CREATION_NAME;
$modversion['version'] = 0.90;
$modversion['description'] = _MI_WEB_CREATION_DESCRIPTION;
$modversion['image'] = "logo.gif";
$modversion['dirname'] = "web_creation";
$modversion['cube_style'] = true;

// Admin Setting
$modversion['hasAdmin'] = 1;
//$modversion['adminindex'] = XOOPS_URL.'/modules/'.APP_DIR_NAME.'/?admin';
$modversion['adminindex'] = './admin';
//$modversion['adminmenu'] = APP_ROOT_PATH.'/admin/menu.php';
$modversion['adminmenu'] = dirname(__FILE__).'/admin/menu.php';

// Permission
$modversion['hasMain'] = 1;
$modversion['read_any'] = true;

// Database
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['table'][0] = '{prefix}_{dirname}_display_cache';

// Template
$modversion['templates'][]['file'] = 'web_creation_main.html';
$modversion['templates'][]['file'] = 'web_creation_script.html';
$modversion['templates'][]['file'] = 'web_creation_unit_test.html';

// Configuration
$modversion['config'][] = array(
	'name' => 'web_creation_folder_id',
	'title' => '_MI_WEB_CREATION_FOLDER_ID',
	'description' => '',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' => 0
);

$modversion['config'][] = array(
	'name' => 'web_creation_folder_path',
	'title' => '_MI_WEB_CREATION_FOLDER_PATH',
	'description' => '',
	'formtype' => 'textbox',
	'valuetype' => 'text',
	'default' => XOOPS_ROOT_PATH.'/uploads/web_creation'
);

?>
<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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
$modversion['name'] = _MI_WEB_TRANSLATION_NAME;
$modversion['version'] = 0.96;
$modversion['description'] = _MI_WEB_TRANSLATION_DESCRIPTION;
$modversion['image'] = "logo.gif";
$modversion['dirname'] = "web_translation";
$modversion['cube_style'] = true;

// Admin Setting
//$modversion['hasAdmin'] = 1;
//$modversion['adminindex'] = XOOPS_URL.'/modules/'.APP_DIR_NAME.'/?admin';
//$modversion['adminindex'] = './admin';
//$modversion['adminmenu'] = APP_ROOT_PATH.'/admin/menu.php';

// Permission
$modversion['hasMain'] = 1;
$modversion['read_any'] = true;

// Database
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['table'][0] = '{prefix}_{dirname}_display_cache';

// Template
$modversion['templates'][]['file'] = 'web_translation_main.html';
$modversion['templates'][]['file'] = 'web_translation_script.html';
$modversion['templates'][]['file'] = 'web_translation_unit_test.html';

?>
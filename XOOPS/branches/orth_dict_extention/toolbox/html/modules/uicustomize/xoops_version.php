<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox.
//
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
/* $Id: xoops_version.php 3879 2010-08-02 10:15:16Z yoshimura $ */

$modversion['name'] = _MI_UICUSTOMIZE_NAME;
$modversion['version'] = 1.01;
$modversion['description'] = _MI_UICUSTOMIZE_DESCRIPTION;
$modversion['author'] = "Language Grid Project, NICT";
$modversion['credits'] = "";
$modversion['license'] = "LGPL";
$modversion['image'] = "logo.gif";
$modversion['dirname'] = 'uicustomize';

$modversion['cube_style'] = true;

// Menu
$modversion['hasMain'] = 1;
$modversion['read_any'] = true;

$modversion['hasAdmin'] = 0;

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][] = '{prefix}_uicustomize_support_ui_languages';
$modversion['tables'][] = '{prefix}_uicustomize_text_resource_files';

// Templates
$modversion['templates'][0]['file'] = 'uicustomize-frame.html';
$modversion['templates'][0]['description'] = 'UI Customize ooutside frame template';

$modversion['templates'][1]['file'] = 'uicustomize-textresource-main.html';
$modversion['templates'][1]['description'] = 'Text resource main template';

$modversion['templates'][2]['file'] = 'uicustomize-menumanage-main.html';
$modversion['templates'][2]['description'] = 'Menu context management main template';

$modversion['templates'][3]['file'] = 'uicustomize-topmanage-main.html';
$modversion['templates'][3]['description'] = 'Top page management main template';

$modversion['templates'][4]['file'] = 'uicustomize-logomanage-main.html';
$modversion['templates'][4]['description'] = 'Site logo management main template';

// no html template.
$modversion['templates'][11]['file'] = 'uicustomize_support_languages_conf_ml.html';
$modversion['templates'][11]['description'] = 'CubeUtils multlanugage config file template.';

//$modversion['onInstall'] = 'oninstall.php';
//$modversion['onUpdate'] = 'onupdate.php';
//$modversion['onUninstall'] = 'onuninstall.php';

?>
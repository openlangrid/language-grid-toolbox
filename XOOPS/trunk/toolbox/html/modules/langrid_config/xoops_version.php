<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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
/* $Id: xoops_version.php 5401 2011-02-09 05:16:59Z mtanaka $ */

$modversion['name'] = _MI_LANGRID_CONFIG_NAME;
$modversion['dirname'] = "langrid_config";
$modversion['description'] = _MI_LANGRID_CONFIG_NAME_DESC;
$modversion['version'] = 1.10;
$modversion['author'] = "Language Grid Project, NICT";
$modversion['license'] = "LGPL";
$modversion['image'] = "images/logo.gif";
$modversion['read_any'] = 1;
$modversion['hasMain'] = 1;

// Templates
$modversion['templates'][1]['file'] = 'langrid_config-main.html';
$modversion['templates'][1]['description'] = 'langrid config main template';
$modversion['templates'][2]['file'] = 'langrid_config-script.html';
$modversion['templates'][2]['description'] = 'langrid config javascript template';
$modversion['templates'][3]['file'] = 'langrid_config-personal.html';
$modversion['templates'][4]['file'] = 'langrid_config-voice.html';
$modversion['templates'][5]['file'] = 'langrid_config-imported_services.html';

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][] = 'langrid_services';
$modversion['tables'][] = 'langrid_config_voice_setting';
$modversion['tables'][] = 'langrid_config_ebmt_learning';

$modversion['onInstall'] = 'oninstall.php' ;
$modversion['onUpdate'] = 'onupdate.php' ;

?>

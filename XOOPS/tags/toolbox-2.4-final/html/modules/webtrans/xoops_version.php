<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009  NICT Language Grid Project
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

$modversion['name'] = _MI_WEBTRANSLATION_NAME;
$modversion['version'] = 0.05;
$modversion['description'] = _MI_WEBTRANSLATION_DESC;
$modversion['author'] = "";
$modversion['credits'] = "";
$modversion['license'] = "";
$modversion['image'] = "slogo.gif";
$modversion['dirname'] = "webtrans";

$modversion['cube_style'] = true;

$modversion['hasAdmin'] = 0;

$modversion['hasMain'] = 1;
$modversion['read_any'] = true;

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['table'][0] = '{prefix}_{dirname}_template';
$modversion['table'][1] = '{prefix}_{dirname}_display_cache';

$modversion['hasAdmin'] = 0;

// Templates
$modversion['templates'][1]['file'] = 'web-translation.html';
$modversion['templates'][1]['description'] = 'Main';
$modversion['templates'][2]['file'] = 'webtran_php2js.html';
$modversion['templates'][2]['description'] = 'php2js';

//$modversion['onInstall'] = 'oninstall.php' ;
//$modversion['onUpdate'] = 'onupdate.php' ;
//$modversion['onUninstall'] = 'onuninstall.php' ;

$modversion['onUpdate'] = 'onupdate.php' ;
?>
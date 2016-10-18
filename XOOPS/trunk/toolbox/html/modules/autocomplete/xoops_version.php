<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides accurate
// translation using the autocomplete feature based on parallel texts and
// translation template.
// Copyright (C) 2010  CITY OF KYOTO
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
/** $Id: xoops_version.php 3573 2010-03-30 10:40:59Z yoshimura $ */

$modversion['name'] = _MI_AUTOCOMPLETE_NAME;
$modversion['version'] = 0.02;
$modversion['description'] = _MI_AUTOCOMPLETE_NAME_DESC;
$modversion['author'] = "Language Grid Project, NICT";
$modversion['credits'] = "";
$modversion['license'] = "LGPL";
$modversion['image'] = "module_logo.gif";
$modversion['dirname'] = "autocomplete";
$modversion['cube_style'] = true;
$modversion['hasMain'] = 1;
$modversion['read_any'] = 1;

$modversion['templates'][1]['file'] = 'autocomplete_setting_main.html';
$modversion['templates'][2]['file'] = 'autocomplete_demo.html';

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][0] = '{prefix}_autocomplete_setting';


?>
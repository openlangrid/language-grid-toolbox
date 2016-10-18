<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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

$modversion['name'] = _MI_DICTIONARY_NAME;
$modversion['version'] = 0.80;
$modversion['description'] = _MI_DICTIONARY_DESC;
$modversion['author'] = "";
$modversion['credits'] = "";
$modversion['license'] = "";
$modversion['image'] = "module_icon.gif";
$modversion['dirname'] = "dictionary";

$modversion['cube_style'] = true;

$modversion['hasAdmin'] = 0;

$modversion['hasMain'] = 1;
$modversion['read_any'] = true;

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "{prefix}_user_dictionary";
$modversion['tables'][1] = "{prefix}_user_dictionary_contents";
$modversion['tables'][2] = "{prefix}_user_dictionary_permission";

$modversion['hasAdmin'] = 0;

// Templates
$modversion['templates'][1]['file'] = 'dictionary_main.html';
$modversion['templates'][1]['description'] = 'Main';
$modversion['templates'][2]['file'] = 'create_dictionary.html';
$modversion['templates'][2]['description'] = 'Component Dictionary Creation';
$modversion['templates'][3]['file'] = 'search_dictionary.html';
$modversion['templates'][3]['description'] = 'Component Dictionary Search';
$modversion['templates'][4]['file'] = 'php2js.html';
$modversion['templates'][4]['description'] = 'Adapter between PHP and JavaScript.';
$modversion['templates'][5]['file'] = 'resources_style.html';
$modversion['templates'][5]['description'] = 'CSS';
$modversion['templates'][6]['file'] = 'service_main.html';
$modversion['templates'][6]['description'] = 'Local service deployer test.';

//$modversion['onInstall'] = 'oninstall.php' ;
$modversion['onUpdate'] = 'onupdate.php' ;
$modversion['onUninstall'] = 'onuninstall.php' ;
?>
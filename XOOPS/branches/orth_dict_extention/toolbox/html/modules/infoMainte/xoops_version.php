<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This preserves contents
// entered in forms.
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
$mydirname = basename( dirname( __FILE__ ) ) ;
$mydirpath = dirname( __FILE__ ) ;

$constpref = '_MI_' . strtoupper( $mydirname ) ;

$modversion['name'] = "screenInfoMainte" ;
$modversion['description'] = "Screen information maintenance" ;
$modversion['version'] = 0.01;
$modversion['credits'] = "Credits";
$modversion['author'] = "Language Grid Project, NICT";
$modversion['help'] = "";
$modversion['license'] = "GPL";
$modversion['official'] = 0;
//$modversion['image'] = file_exists( $mydirpath.'/module_icon.png' ) ? 'module_icon.png' : 'module_icon.php' ;
$modversion['image'] = '/images/module_icon.gif';
$modversion['dirname'] = $mydirname;

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "screen_info_mainte";

// Admin things
$modversion['hasAdmin'] = 0;

// Search
$modversion['hasSearch'] = 0 ;

// Menu
$modversion['hasMain'] = 0 ;

$modversion['onInstall'] = 'oninstall.php' ;
$modversion['onUninstall'] = 'onuninstall.php' ;

?>
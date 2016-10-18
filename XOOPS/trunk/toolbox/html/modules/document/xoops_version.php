<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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

$modversion['name'] = _MI_DOCUMENT_NAME;
$modversion['version'] = 1.00;
$modversion['description'] = _MI_DOCUMENT_DESC;
$modversion['author'] = "";
$modversion['credits'] = "";
$modversion['license'] = "";
$modversion['image'] = "img/module_icon.gif";
$modversion['dirname'] = "document";

$modversion['cube_style'] = true;

$modversion['hasAdmin'] = 0;

$modversion['hasMain'] = 1;
$modversion['read_any'] = true;

$modversion['hasAdmin'] = 0;

// Templates
$modversion['templates'][1]['file'] = 'document_main.html';
$modversion['templates'][1]['description'] = 'Main';
$modversion['templates'][2]['file'] = 'document_php2js.html';
$modversion['templates'][2]['description'] = 'php2js';
?>
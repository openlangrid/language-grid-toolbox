<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
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
$modversion['name'] = _MI_WEBQA_NAME;
$modversion['version'] = 0.12;
$modversion['description'] = _MI_WEBQA_DESC;
$modversion['author'] = "";
$modversion['credits'] = "";
$modversion['license'] = "";
$modversion['image'] = "img/module.gif";
$modversion['dirname'] = "webqa";

$modversion['cube_style'] = true;
$modversion['hasMain'] = 1;
$modversion['read_any'] = true;
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';
$modversion['hasconfig'] = 1;

$modversion['onUpdate'] = 'onupdate.php';



//// Templates
//$modversion['templates'][1]['file'] = 'web-translation.html';
//$modversion['templates'][1]['description'] = 'Main';
//$modversion['templates'][2]['file'] = 'webtran_php2js.html';
//$modversion['templates'][2]['description'] = 'php2js';

//$modversion['onInstall'] = 'oninstall.php' ;
//$modversion['onUpdate'] = 'onupdate.php' ;
//$modversion['onUninstall'] = 'onuninstall.php' ;



$modversion['config'][1]['name'] = 'webqa_posting';
$modversion['config'][1]['title'] = '_MI_WEBQA_CNF_POSTING';
$modversion['config'][1]['description'] = '_MI_WEBQA_CNF_POSTING_D';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = 'POSTINGQA';

$modversion['config'][2]['name'] = 'webqa_search';
$modversion['config'][2]['title'] = '_MI_WEBQA_CNF_SEARCH';
$modversion['config'][2]['description'] = '_MI_WEBQA_CNF_SEARCH_D';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'text';
$modversion['config'][2]['default'] = 'SEARCHQA';

//$modversion['config'][3]['name'] = 'webqa_for_bbs';
//$modversion['config'][3]['title'] = '_MI_WEBQA_CNF_FOR_BBS';
//$modversion['config'][3]['description'] = '_MI_WEBQA_CNF_FOR_BBS_D';
//$modversion['config'][3]['formtype'] = 'textbox';
//$modversion['config'][3]['valuetype'] = 'text';
//$modversion['config'][3]['default'] = '1';

?>
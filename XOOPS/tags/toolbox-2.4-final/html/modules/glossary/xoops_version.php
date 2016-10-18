<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// glossaries.
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
/**
 * @author kitajima
 */
$modversion['name'] = _MI_QA_NAME;
$modversion['version'] = 1.01;
$modversion['description'] = _MI_QA_DESCRIPTION;
$modversion['author'] = "";
$modversion['credits'] = "";
$modversion['license'] = "GPL";
$modversion['image'] = "logo.gif";
$modversion['dirname'] = 'glossary';

$modversion['cube_style'] = true;

// Menu
$modversion['hasMain'] = 1;
$modversion['read_any'] = true;

$modversion['hasAdmin'] = 0;

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][] = '{prefix}_glossary_terms';
$modversion['tables'][] = '{prefix}_glossary_term_expressions';
$modversion['tables'][] = '{prefix}_glossary_definitions';
$modversion['tables'][] = '{prefix}_glossary_definition_expressions';
$modversion['tables'][] = '{prefix}_glossary_categories';
$modversion['tables'][] = '{prefix}_glossary_category_expressions';
$modversion['tables'][] = '{prefix}_glossary_category_term_relations';

// Templates
$modversion['templates'][0]['file'] = 'glossary-main.html';
$modversion['templates'][0]['description'] = 'Main';

$modversion['onInstall'] = 'oninstall.php';
$modversion['onUpdate'] = 'onupdate.php';
$modversion['onUninstall'] = 'onuninstall.php';

?>
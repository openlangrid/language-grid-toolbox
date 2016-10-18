<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
$modversion['name'] = _MI_TRANSLATION_TEMPLATE_NAME;
$modversion['version'] = 0.80;
$modversion['description'] = _MI_TRANSLATION_TEMPLATE_DESCRIPTION;
$modversion['author'] = "";
$modversion['credits'] = "";
$modversion['license'] = "GPL";
$modversion['image'] = "logo.gif";
$modversion['dirname'] = 'template';

$modversion['cube_style'] = true;

// Menu
$modversion['hasMain'] = 1;
$modversion['read_any'] = true;

$modversion['hasAdmin'] = 0;

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][] = '{prefix}_template_translation_templates';
$modversion['tables'][] = '{prefix}_template_translation_template_expressions';
$modversion['tables'][] = '{prefix}_template_bound_word_set_ids';
$modversion['tables'][] = '{prefix}_template_default_bound_word_sets';
$modversion['tables'][] = '{prefix}_template_bound_word_sets';
$modversion['tables'][] = '{prefix}_template_bound_word_set_expressions';
$modversion['tables'][] = '{prefix}_template_bound_words';
$modversion['tables'][] = '{prefix}_template_bound_word_expressions';
$modversion['tables'][] = '{prefix}_template_categories';
$modversion['tables'][] = '{prefix}_template_category_expressions';
$modversion['tables'][] = '{prefix}_template_bound_word_set_translation_template_relations';
$modversion['tables'][] = '{prefix}_template_category_translation_template_relations';

// Templates
$modversion['templates'][0]['file'] = 'translation-template-main.html';
$modversion['templates'][0]['description'] = 'Main';

$modversion['onInstall'] = 'oninstall.php';
$modversion['onUpdate'] = 'onupdate.php';
$modversion['onUninstall'] = 'onuninstall.php';
?>

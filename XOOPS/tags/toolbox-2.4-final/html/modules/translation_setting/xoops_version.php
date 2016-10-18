<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to set
// translation paths.
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

$modversion['name'] = _MI_TRANSLATION_SETTING_NAME;
$modversion['version'] = 0.05;
$modversion['description'] = _MI_TRANSLATION_SETTING_DESC;
$modversion['image'] = "slogo.gif";
$modversion['dirname'] = 'translation_setting';

$modversion['cube_style'] = true;

// Menu
$modversion['hasMain'] = 0;

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][0] = '{prefix}_translation_set';
$modversion['tables'][1] = '{prefix}_translation_path';
$modversion['tables'][2] = '{prefix}_translation_exec';
$modversion['tables'][3] = '{prefix}_translation_bind';

// Templates

// module update script.
$modversion['onUpdate'] = 'onupdate.php' ;

?>
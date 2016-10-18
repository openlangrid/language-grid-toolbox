<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
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
// 共通Define定義

// Smarty
define('APP_TEMPLATE_DIR', APP_ROOT_DIR.'/template');
define('APP_TEMPLATE_COMP_DIR', APP_TEMPLATE_DIR.'/template_c');
define('APP_SMARTY_LEFT_DELIMITER', '<{');
define('APP_SMARTY_RIGHT_DELIMITER', '}>');

// Action
define('APP_ACTION_DIR', APP_ROOT_DIR.'/action');

// View
define('APP_VIEW_DIR', APP_ROOT_DIR.'/view');

// name of theme.
define('APP_THEME_NAME', 'default');

?>
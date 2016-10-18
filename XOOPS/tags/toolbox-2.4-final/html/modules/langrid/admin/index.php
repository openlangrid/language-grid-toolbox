<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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

require_once "../../../mainfile.php";
require_once XOOPS_ROOT_PATH . "/header.php";

$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
$rs = $xoopsDB->query( "SELECT mid FROM ".$xoopsDB->prefix('modules')." WHERE dirname='$mydirname'" ) ;
list( $filesharing_mid ) = $xoopsDB->fetchRow( $rs ) ;
redirect_header(XOOPS_MODULE_URL . "/legacy/admin/index.php?action=PreferenceEdit&confmod_id=$filesharing_mid", 1, "");

require_once XOOPS_ROOT_PATH . "/footer.php";

exit();
?>

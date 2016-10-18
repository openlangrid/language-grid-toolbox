<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Playground. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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

require '../../mainfile.php';
require_once 'config.php';

$mytrustdirname = basename(dirname(__FILE__));
$mydirname = basename(dirname(__FILE__));

// login check
$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set XOOPS_TRUST_PATH in mainfile.php' ) ;

require_once dirname( __FILE__ ).'/mytrustdirname.php' ; // set $mytrustdirname
require 'helper.php';
require_all_classes($mytrustdirname);

$allow_actions = array(
	"list", "languages"
);

$delegator = get_delegator($allow_actions, "list");
$delegator -> execute();
?>
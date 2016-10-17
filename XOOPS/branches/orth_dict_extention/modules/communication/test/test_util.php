<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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
require_once '../../../mainfile.php';
require_once '../config.php';
require_once '../mytrustdirname.php' ; // set $mytrustdirname
require_once '../helper.php';

// login check
$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}


function assertEquals($a, $b, $msg = null) {
	if($a != $b) {
		if($a === null) $a = "null";
		if($b === null) $b = "null";
		throw new Exception("{$msg} Expected was {$a} but was {$b}");
	}
}

function assertExist($value) {
	return $value != null;
}

function camelize ($str) {
    return str_replace(' ','',ucwords(str_replace('_',' ',$str)));
}
function decamelize ($str) {
    return ltrim(preg_replace('/([A-Z])/e',"'_'.strtolower('$1')",$str),'_');
}

?>

<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
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
require_once('../../../mainfile.php');

$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;

if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}
$xoopsOption['template_main'] = 'service_main.html';
include(XOOPS_ROOT_PATH.'/header.php');

// for test
$db = Database::getInstance();
$tableName = $db->prefix('user_dictionary');

$bdSql = 'select dictionary_name from ' . $tableName. ' '
		. ' where type_id = 0 and delete_flag = 0';
$result = $db->query($bdSql);
$bdArray = array();
$cCount = 0;
$rCount = 0;
while($row = $db->fetchRow($result)){
	if($cCount++ == 10){
		$rCount++;
		$cCount = 0;
	}
	$bdArray[$rCount][] = $row[0];
}

$ptSql = 'select dictionary_name from ' . $tableName. ' '
		. ' where type_id = 1 and delete_flag = 0';
$result = $db->query($ptSql);
$ptArray = array();
$cCount = 0;
$rCount = 0;
while($row = $db->fetchRow($result)){
	if($cCount++ == 10){
		$rCount++;
		$cCount = 0;
	}
	$ptArray[$rCount][] = $row[0];
}

$xoopsTpl->assign(array(
	'bds' => $bdArray
	, 'pts' => $ptArray
	));

// end

include(XOOPS_ROOT_PATH.'/footer.php');
?>
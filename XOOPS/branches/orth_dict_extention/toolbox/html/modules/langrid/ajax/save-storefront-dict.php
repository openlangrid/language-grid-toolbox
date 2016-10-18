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

require_once(dirname(__FILE__).'/../../../mainfile.php');
require_once(dirname(__FILE__).'/../class/DefaultDictionariesClass.php');
global $xoopsUser;

header('Content-Type: application/json; charset=utf-8;');

$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	echo json_encode(array('status'=>'SESSIONTIMEOUT'));
	exit;
}

if (@$_POST['mode'] != 'STORE_FRONT') {
	die('Modes are different for Store front.');
}

$dictionaries = @$_POST['user_dict_ids'];

$defDicts = new DefaultDictionariesSetting();
$ret;

if (count($dictionaries) == 0) {

	// if size of request parameter '$dictionaries' is zero, then remove all dictionary records from table.  
	$ret = $defDicts -> removeStoreFrontDictionaries();
} else {
	
	// edit paramter
	$imploded = implode(',', $dictionaries);	
	$ret = $defDicts -> saveStoreFrontDictionaries(array(
		'user_dict_ids' => urldecode($imploded)
	));
}

if ($ret) {
	$contents = $defDicts -> searchByStorefront();
	echo $contents['bind_user_dict_ids'];
} else {
	echo json_encode(array('status'=>'ERROR'));
}

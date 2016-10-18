<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
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
$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	echo json_encode(array('status'=>'ERROR','message'=>'SESSIONTIMEOUT'));
}
require_once dirname(__FILE__).'/../lib/user-dictionary-controller.php';
$userDictionaryController = new UserDictionaryController();

/*
$cnt = $userDictionaryController->_preSearch($_GET);
if ($cnt > 200) {
	echo json_encode(array(
		'status'=>'ERROR',
		'message'=>sprintf(_MI_DICTIONARY_ERROR_SEARCH_RESULT_OVERSHOOT, 
		$cnt)
	));
} else {
}
*/
$response = $userDictionaryController->search($_GET);
echo json_encode($response);	

?>
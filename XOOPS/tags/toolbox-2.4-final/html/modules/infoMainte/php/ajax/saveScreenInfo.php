<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This preserves contents
// entered in forms.
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
require_once(XOOPS_MODULE_PATH.'/infoMainte/php/class/InfoDataControlClass.php');

//require_once(dirname(__FILE__).'/../../../../mainfile.php');
//require_once('../class/InfoDataControlClass.php');

header('Content-Type: application/json; charset=utf-8;');
$moduleId = $_POST['moduleId'];
$screenId = $_POST['screenId'];
$items = $_POST['items'];
if (get_magic_quotes_gpc()) {
	$items = stripcslashes($items);
}
$items = json_decode($items, true);

$InfoDataCtl =& new InfoDataControlClass();

if($InfoDataCtl->saveInfoData($moduleId,$screenId,$items)){
	echo json_encode($InfoDataCtl->getResult());
}else{
	//$res = $InfoDataCtl->getResult();
	//echo $res["message"];
	echo json_encode(array('status'=>'ERROR'));
}

?>
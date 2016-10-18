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
require_once dirname(__FILE__).'/../../../mainfile.php';
$root = XCube_Root::getSingleton();

function httpAutoLink($text){
	return ereg_replace("(https?|ftp)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)",
				 "<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>" , $text);
}

header('Content-Type: application/json; charset=utf-8;');

$serviceId = $_POST["serviceId"];

$tbl = $xoopsDB->prefix('langrid_services');

$res = null;
$sql = "";
$sql .= 'select * ';
$sql .= ' from '.$tbl.' ';
$sql .= ' where `service_id` = \''.str_replace('\\"','"',addslashes($serviceId)).'\' ';
$sql .= ' and `service_type` = \'TRANSLATION\' ';
if ($rs = $xoopsDB->query($sql)) {
	while ($row = $xoopsDB->fetchArray($rs)) {
		$res = $row;
	}
}

if($res == null){
	$sql = "";
	$sql .= 'select * ';
	$sql .= ' from '.$tbl.' ';
	$sql .= ' where `service_id` = \''.str_replace('\\"','"',addslashes($serviceId)).'\' ';
	$sql .= ' and `service_type` = \'IMPORTED_TRANSLATION\' ';
	if ($rs = $xoopsDB->query($sql)) {
		while ($row = $xoopsDB->fetchArray($rs)) {
			$res = $row;
		}
	}
}

if($res == null){$res = array();}

foreach($res as $k => $v){
	$res[$k] = htmlentities($v,ENT_QUOTES);
}

if(trim($res['license']) == ""){
	$res['license'] = "-";
}else{
	$res['license'] = httpAutoLink($res['license']);
}


$contents = '';
$contents .= '<div class="info-body">';
$contents .= '<form>';
$contents .= '<h1>'.$res['service_name'].'</h1>';
$contents .= '<div class="info-contents">';
$contents .= '<dl>';
$contents .= '<dt>'._MD_LANGRID_INFO_POP_PROVIDER.':</dt>';
$contents .= '<dd>'.$res['organization'].'&nbsp;</dd>';
$contents .= '<dt>'._MD_LANGRID_INFO_POP_COPYRIGHT.':</dt>';
$contents .= '<dd>'.$res['copyright'].'&nbsp;</dd>';
$contents .= '<dt>'._MD_LANGRID_INFO_POP_LICENSE.':</dt>';
$contents .= '<dd>'.$res['license'].'&nbsp;</dd>';
$contents .= '<dt>'._MD_LANGRID_INFO_POP_DESCRIPTION.':</dt>';
$contents .= '<dd>'.$res['description'].'&nbsp;</dd>';
$contents .= '</dl>';
$contents .= '</div>';
$contents .= '<div style="margin-top: 8px; text-align:center;">';
$contents .= '<a class="btn" style="margin-left:150px;" onclick="Element.hide($(\'baloon-'.$res['service_id'].'\'));">';
$contents .= '<img src="./images/icon/icn_close.gif" />'._MD_LANGRID_SETTING_CLOSE_BUTTON.' ';
$contents .= '</a>';
$contents .= '</div>';
$contents .= '</form>';
$contents .= '<br class="clear" />';
$contents .= '</div>';

echo json_encode(array('status'=>'OK', 'contents'=> $contents));
exit();
?>
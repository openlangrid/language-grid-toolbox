<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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

require_once(dirname(__FILE__).'/../../../../mainfile.php');
require_once(dirname(__FILE__).'/../class/TemplateManageClass.php');

header('Content-Type: application/json; charset=utf-8;');

$templateName = $_POST['name'];
$ret = "";
if(preg_match('/^[a-zA-Z0-9_-]+$/', $templateName) !== 1){
	$ret = array('result'=>false,'message'=>'illegal template Name');
}else{
	$TemplateClass =& new TemplateManageClass();
	//$userId = $xoopsUser->getVar('uid');
	//$result = $TemplateClass->getTemplateCount($userId,$templateName);
	$result = $TemplateClass->getTemplateCount($templateName);

	if($result === false){
		$ret = array('result'=>false,'message'=>'getTemplateCount failed'.$result);
	}else{
		$overwrite = false;
		if($result > 0){
			$overwrite = true;
		}
		$ret = array(
			"result" => true,
			"data" => array(
				'overwrite'=>$overwrite
			)
		);
	}
}

echo json_encode($ret);exit();
?>
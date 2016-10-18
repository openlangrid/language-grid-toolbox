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
error_reporting(0);

header('Content-Type: application/json; charset=utf-8;');

$templateName = $_POST['name'];

$ret = "";
if(preg_match('/^[a-zA-Z0-9_-]+$/', $templateName) !== 1){
	$ret = array('result'=>false);
}else{
	try{
		$TemplateClass =& new TemplateManageClass();
		//$userId = $xoopsUser->getVar('uid');

		//$result = $TemplateClass->loadTemplatePairs($userId,$templateName);
		$result = $TemplateClass->loadTemplatePairs($templateName);
		if(!is_array($result)){
			$result = array();
		}

		$pair = array();
		foreach($result as $res_pair){
			$pair[] = array(
				'PAIR_ID' => $res_pair["pair_id"],
				'SOURCE_TEXT' => $res_pair["source_text"],
				'TARGET_TEXT' => $res_pair["target_text"],
			);
		}

		$ret = array(
			"result" => true,
			"data" => array(
				'pair' => $pair,
				'name' => $templateName
			)
		);
	}catch (Exception $e) {
		$ret = array('result'=>false,'message'=>$e->getMessage());
	}
}
echo json_encode($ret);
/*
function loadtemplate(){
    $DBconf= new DATABASE_CONFIG();
    $DBconf= $DBconf->{ "default" };
	$templateName = $_POST['name'];
	if(
		preg_match('/^[a-zA-Z0-9_-]+$/', $templateName) !== 1 ||
	   	($conn=mysql_connect($DBconf["host"], $DBconf["login"], $DBconf["password"])) === FALSE)
	{
		return array('result'=>false);
	}
	mysql_select_db($DBconf["database"]);

	$result = mysql_query("SELECT * FROM TEMPLATE_TBL WHERE FILENAME='".mysql_real_escape_string($templateName)."' AND DELETE_FLAG='0' ORDER BY PAIR_ID ASC");
	if(mysql_error() !== ''){
		return array('result'=>false);
	}
	$pair = array();
	while ($row = mysql_fetch_assoc($result)) {
		$pair[] = array(
			'PAIR_ID' => $row["PAIR_ID"],
			'SOURCE_TEXT' => $row["SOURCE_TEXT"],
			'TARGET_TEXT' => $row["TARGET_TEXT"],
		);
	}
	$ret = array(
		"result" => true,
		"data" => array(
			'pair' => $pair,
			'name' => $templateName
		)
	);
	return $ret;
}
echo json_encode( loadtemplate() );
*/
?>
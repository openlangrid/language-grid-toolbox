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

//error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8;');

$pairs = $_POST['pairs'];
$templateName = $_POST['name'];
$isOverwrite = $_POST['overwrite'];

$ret = "";
if(preg_match('/^[a-zA-Z0-9_-]+$/', $templateName) !== 1){
	$ret = array('result'=>false);
}else{
	try{
		$TemplateClass =& new TemplateManageClass();
		//$userId = $xoopsUser->getVar('uid');
	
		if($isOverwrite == true){
			//$TemplateClass->deleteTemplate($userId,$templateName);
			$TemplateClass->deleteTemplate($templateName);
			/*
			$result = $TemplateClass->deleteTemplate($userId,$templateName);
			if(!$result){
				throw new Exception('update failed');
			}
			*/
		}
		
		$result = $TemplateClass->insertTemplatePairs($userId,$templateName,$pairs);
		if(!$result){
			throw new Exception('insert failed');
		}
		$ret = array(
			"result" => true,
			"data" => array(
				'name'=>$templateName
			)
		);
	}catch (Exception $e) {
		$ret = array('result'=>false,'message'=>$e->getMessage());
	}
}
echo json_encode($ret);
/*
function savetemplate(){
    $DBconf= new DATABASE_CONFIG();
    $DBconf= $DBconf->{ "default" };
	$pairs = $_POST['pairs'];
	$templateName = $_POST['name'];
	$isOverwrite = $_POST['overwrite'];

	if(
		!is_array($pairs) || 
		preg_match('/^[a-zA-Z0-9_-]+$/', $templateName) !== 1 ||
	   	($conn=mysql_connect($DBconf["host"], $DBconf["login"], $DBconf["password"])) === FALSE)
	{
		return array('result'=>false);
	}
	mysql_select_db($DBconf["database"]);

	if($isOverwrite){
		$result = mysql_query("UPDATE TEMPLATE_TBL SET DELETE_FLAG=1 WHERE FILENAME='".mysql_real_escape_string($templateName)."'");
	}
	$insertValues = array();
	$pair_id = 0;
	foreach($pairs as $item){
		$insertValues[] = "('".mysql_real_escape_string($templateName)."', ".$pair_id.", '".mysql_real_escape_string($item["source"])."', '".mysql_real_escape_string($item["target"])."', 'SYSTEM', NOW(), 'SYSTEM', NOW(), 0)";
		$pair_id++;
	}
	
	$result = mysql_query("INSERT INTO TEMPLATE_TBL (FILENAME, PAIR_ID, SOURCE_TEXT, TARGET_TEXT, UPDATE_USER, UPDATE_DATE, INSERT_USER, INSERT_DATE, DELETE_FLAG) VALUES ".join(',', $insertValues));
	if(mysql_error() !== ''){
		return array('result'=>false);
	}
	$ret = array(
		"result" => true,
		"data" => array(
			'name'=>$templateName
		)
	);
	return $ret;
}
echo json_encode( savetemplate() );
*/
?>
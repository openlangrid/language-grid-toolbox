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

function uploadTemplate($tmpFilePath){
	$tmpFileLines = file($tmpFilePath);
	foreach($tmpFileLines as $aline){
		$tmpFileContent .= $aline;
	}
	$utf8content = $tmpFileContent;

	$xml = simplexml_load_string('<xml>'.$utf8content.'</xml>','SimpleXMLElement',LIBXML_NOCDATA^LIBXML_NOERROR^LIBXML_NOWARNING);
	if($xml === false){
		return false;
	}

	$result = array();
	foreach( $xml->PAIR as $key => $value ){
		$attrs = $value->attributes();
		$result[] = array(
			'PAIR_ID' => (string)$attrs['ID'],
			'SOURCE_TEXT' => (string)$value->SOURCE_TEXT,
			'TARGET_TEXT' => (string)$value->TARGET_TEXT,
		);
	}

	return $result;
}
function uploadTemplates(){
	$response = array(
		'status' =>'OK',
		'message' => 'Successful Web Translation Upload',
		'contents' => array(),
		'path' => array(),
	);

	foreach( $_FILES['uploadFileName']['name'] as $key => $value ){
		$contents = uploadTemplate($_FILES['uploadFileName']['tmp_name'][$key]);
		if( $contents === false ){
			$response['status'] = 'ERROR';
			$response['message'] = 'The file is not a valid template file.';
			break;
		}
		$response['contents'][] = $contents;
		$response['path'][] = $_FILES['uploadFileName']['name'][$key];
	}

	echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
	echo "<title>-</title></head><body>";
	echo "<script language='JavaScript' type='text/javascript'>"."\n";
	if( isset($_POST['callback']) && $_POST['callback'] == "createEdit" ){
		echo "window.parent.WebTranslationWorkspace.prototype.createEditUploadTemplate(" . json_encode($response) . ");";
	}else{
		echo "window.parent.WebTranslationWorkspace.prototype.applyUploadTemplate(" . json_encode($response) . ");";
	}
	echo "\n"."</script>";
	echo "</body></html>";
}

uploadTemplates();

?>
<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

$document = WorkDocument::createFromParams($_POST);

// download
if(@$_POST['atLocal']) {
	$renderOption['type'] = 'stream';
	
	$xml = $document->toXML();
	header("HTTP/1.1 200 OK");
	header("Status: 200 OK");
	header("Cache-Control: private, must-revalidate"); 
	header("Accept-Ranges: ".strlen($xml)."bytes");
	header("Content-Disposition: attachment; filename=\"work_document.xml\"");
	header("Content-Length: ".strlen($xml));
	header("Content-Type: application/octet-stream");
	//header('Content-Type: application/xml; charset=utf-8;');
	print $xml;
	
} else {
	if(!$document -> validateOnInsert()) {
		$result = $document -> save();
		if ($result == FALSE) {
			include(dirname(__FILE__). '/../index.php');
			$renderOption['template'] = $service -> moduleName . '_index.html';
			$xoopsTpl -> assign('document', $document);
			$xoopsTpl -> assign('saveMissing', true);
		} else {
			redirect_header(XOOPS_URL.'/modules/'.$mytrustdirname."?file_id=".$document -> getFileId());
		}
	} else {
		include(dirname(__FILE__). '/../index.php');
		$renderOption['template'] = $service -> moduleName . '_index.html';
		$xoopsTpl -> assign('document', $document);
		$xoopsTpl -> assign('saveMissing', true);
	}
}
?>
<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //


$file = File::findById($_GET['fileId']);

$params = array_merge(array(
	"fileName" => $file -> getName(),
	"description" => $file -> getDescription(),
	"parentId" => $file -> getFolderId(),
	"readPermission" => $file -> getReadPermission(),
	"writePermission" => $file -> getWritePermission(),
), $_POST);

$document = WorkDocument::createFromParams($params);

if ($document -> save() ) {
	
	print json_encode(array("status" => true));
	
} else {
	
	print json_encode(array(
		"status" => false,
		"message" => CT_MSG_MISSING_OVERWRITE
	));
}

?>

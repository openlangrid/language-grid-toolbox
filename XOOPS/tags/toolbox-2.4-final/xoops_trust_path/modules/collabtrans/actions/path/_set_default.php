<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //
$renderOption['type'] = 'json';	

$path = TranslationPath::findById($_POST['pathId']);
$defPath = DefaultTranslationPath::craeteFromTranslationPath($path);
$result = $defPath -> insert();

if($result) {
	echo json_encode(array(
		"status" => true,
		"pathId" => $_POST['pathId']
	));	
} else {
	echo json_encode(array(
		"status" => false,
		"pathId" => $_POST['pathId']
	));
}
?>
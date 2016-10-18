<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

if(validate()) {
	add();
}

redirect_header($_SERVER['HTTP_REFERER']);

function validate() {
	$keys = array('keyword', 'dictionary', 'dictionaryName');
	$result = true;
	foreach($keys as $k) {
		$result &= !is_null($_POST[$k]);
	}
	$result &= count($_POST['dictionary']) > 1;
	return $result;
}

function add() {
	
	$client = new DictionaryClient();
	$exps = array();
	foreach($_POST['dictionary'] as $key => $value) {
		$exp = new ToolboxVO_Resource_Expression();
		$exp -> language = $key;
		$exp -> expression = $value;
		$exps[] = $exp;
	}
	$client -> addRecord($_POST['dictionaryName'], $exps);
}
?>
<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //
if(validate()) {
	$glossary = findRecord();
	if($glossary) {
		$r = $glossary->addDefinition(createTerm(), createDefinition());
	} else {
		add();		
	}
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

function findRecord() {
	return GlossaryItem::find(array(
		"name" => $_POST['dictionaryName'],
		"keyword" => $_POST['term'][$_POST['sourceLang']],
		"sourceLang" => $_POST['sourceLang'],
		"targetLang" => $_POST['targetLang'],
		"method" => 'complete'
	));
}

function add() {
	$client = new GlossaryClient();
	return $client -> addRecord(
		$_POST['dictionaryName'], 
		createTerm(), 
		array(createDefinition()), 
		$categoryIds = null
	);
}

function createTerm() {
	$terms = array();
	foreach($_POST['term'] as $key => $value) {
		$term = new ToolboxVO_Resource_Expression();
		$term -> language = $key;
		$term -> expression = $value;
		$terms[] = $term;
	}
	return $terms;
}

function createDefinition() {
	$exps = array();
	foreach($_POST['dictionary'] as $key => $value) {
		$exp = new ToolboxVO_Resource_Expression();
		$exp -> language = $key;
		$exp -> expression = $value;
		$exps[] = $exp;
	}
	
	$difinition = new ToolboxVO_Glossary_Definition();
	$difinition->expression = $exps;
	return $difinition;
}
?>

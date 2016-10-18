<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

if(!@$_GET['sourceLang']) {
	die();
}

$xoopsTpl -> assign('resources', 
	Glossary::findAllByLang($_GET['sourceLang'], $_GET['targetLang'])
);
?>
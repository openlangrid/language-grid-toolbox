<?php
require_once dirname(__FILE__).'/../../class/glossary_list.php';

// resource name
$resourceName = @$_GET['resourceName'] ? $_GET['resourceName'] : @$_POST['resourceName'];

$xoopsTpl -> assign(array(
	'resourceName' => $resourceName,
	'glossaryDictionaryList' => GlossaryList::findGlossaryNames(),
	'glossaryCheckedDictionaryList' => GlossaryList::findSelectedDefaultGlossaryDictionaries($resourceName)
));

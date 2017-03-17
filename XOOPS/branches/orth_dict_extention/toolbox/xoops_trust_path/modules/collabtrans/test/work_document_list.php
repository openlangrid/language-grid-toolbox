<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/work_document_list.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/common_util.php';

echo '<p>WorkDocumentList Test start</p>';

try{

$list = WorkDocumentList::getDocumentsForPage();
echo join($list -> getPageNoList() , ", ");	


echo $list -> getLastPageNo();

	
echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';
} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}
?>

<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/file.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/common_util.php';


echo '<p>File Test start</p>';
try{
$client = new FileSharingClient();
$response = $client -> getAllFiles(); 
foreach($response['contents'] as $f) {
	if($f -> categoryId ==  WorkDocument::FILE_SHARING_CATEGORY_ID)
		$client -> deleteFile($f -> id);
}

$file = File::findById(2);
assertEquals(2, $file -> getId());



echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';
} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}

?>

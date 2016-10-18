<?php
//header('Content-Type: text/html; charset=utf-8;');
error_reporting(E_ALL);
require('../../mainfile.php');
require_once(dirname(__FILE__).'/../class/client/BBSClient.class.php');
var_dump($_FILES);

$client = new BBSClient();
$topicId = 786;
$expja = new ToolboxVO_BBS_MessageExpression();
$expja->language = 'ja';
$expja->body = 'メッセージ本文';
$expArray[] = $expja;
$expen = new ToolboxVO_BBS_MessageExpression();
$expen->language = 'en';
$expen->body = 'Message body.';
$expArray[] = $expen;
$attachments = array();
foreach ($_FILES as $file) {
	$attachment = new ToolboxVO_BBS_Attachment();
	$attachment->location = $file['tmp_name'];
	$attachment->name = $file['name'];
	$attachment->size = $file['size'];
	$attachment->type = $file['type'];
	$attachments[] = $attachment;
}
try {
	$distBBS =& $client->postMessage($topicId, $expArray, $attachments);
} catch (Exeption $e) {
}
//$distBBS =& $client->postMessage($topicId, $expArray);

var_dump($distBBS);
?>
<form action="" method="post" enctype="multipart/form-data">
<input type="file" name="file" />
<input type="submit" />
</form>
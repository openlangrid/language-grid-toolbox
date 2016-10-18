<?php

$renderOption['type'] = 'noheader';

// question ID
$contentId = @$_GET['contentId'];
if ($contentId < 1) {
	exit('contentId is required.');
}

$content = ShopAnswerContent::findById($contentId);
if (!$content) {
	exit('faild to get content by Id :' + $contentId);
}

$xoopsTpl->assign(array(
    'xoops_url' =>  XOOPS_URL,
    'mod_url' =>  XOOPS_MODULE_URL.'/'.$GLOBALS['mydirname'],
    'content' => $content,
));


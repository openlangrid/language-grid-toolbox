<?php
$content = ShopAnswerContent::findById(@$_GET['shopAnswerContentId']);
$xoopsTpl->assign('shopAnswerId', @$_GET['shopAnswerId']);
$xoopsTpl->assign('content', $content);
$xoopsTpl->assign('shopAnswerContentId', @$_GET['shopAnswerContentId']);
$xoopsTpl->assign('contentType', @$_GET['contentType']);
$xoopsTpl->assign('contentTitle', unescape_magic_quote(@$_GET['contentTitle']));
$xoopsTpl->assign('contentUpdateAreaId', @$_GET['contentUpdateAreaId']);
?>
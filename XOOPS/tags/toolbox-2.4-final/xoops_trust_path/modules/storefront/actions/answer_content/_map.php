<?php
/*
 * template : answer_content__map.html
 */
require_once dirname(__FILE__).'/../../class/shop_answer_content.php';

$content = ShopAnswerContent::findById($_GET['shopAnswerContentId']);
if ($content && $content -> getContentType() == 'google_map') {
	$xoopsTpl->assign('content', $content);
}
?>
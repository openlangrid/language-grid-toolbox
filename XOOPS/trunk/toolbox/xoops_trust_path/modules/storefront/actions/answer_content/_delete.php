<?php

$shopAnswerContentId = @$_GET['shopAnswerContentId'] ? $_GET['shopAnswerContentId'] : @$_POST['shopAnswerContentId'];
$contentType = @$_GET['contentType'] ? $_GET['contentType'] : @$_POST['contentType'];

$shopAnswerContent = ShopAnswerContent::findById($shopAnswerContentId);

//$shopAnswerContent = ShopAnswerContent::createFromParams(
//											array('shop_answer_content_id' => $shopAnswerContentId,
//											      'content_type' => $contentType));
$result = $shopAnswerContent -> delete();

?>
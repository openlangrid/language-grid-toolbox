<?php
require_once dirname(__FILE__).'/../../class/shop_answer_content.php';

// making response stream by echo command;
$content = ShopAnswerContent::findById($_GET['shopAnswerContentId']);

if ($content && $content -> getContentType() == 'image') {
	header('Content-type: '. $content -> getMimeType());
	header('Cache-control: max-age=31536000');
	header('Expires: '.gmdate("D, d M Y H:i:s", time() + 31536000).'GMT');
	header('Content-disposition: filename='.$content -> getFilename());
	header('Content-Length: '.strlen($content -> getImageData()));
	header('Last-Modified: '.gmdate("D, d M Y H:i:s", $content -> getCreated()).'GMT');
	echo $content -> getImageData();
}
exit;

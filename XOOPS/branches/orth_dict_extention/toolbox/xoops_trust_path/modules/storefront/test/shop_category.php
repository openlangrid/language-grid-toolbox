<?php
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/manager/language-manager.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/shop_category.php';

$manager = new BilingualManager();

echo "****************************** [shop_category.php test start] ******************************";

echo '<p>-- createFromParams() ----------------------------------</p>' . PHP_EOL;

$category = ShopCategory::createFromParams(array(
	'id' => 38,
	'mainExpression' => 'createFromParams test',
	'subExpression' => 'not for native language'
));

if ($category->getId() != 38) {
	throw new Exception(__LINE__);
}
if ($category->getMainExpression() != 'createFromParams test') {
	throw new Exception(__LINE__);
}
if ($category->getSubExpression() != 'not for native language') {
	throw new Exception(__LINE__);
}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">getExpression() OK</p>' . PHP_EOL;

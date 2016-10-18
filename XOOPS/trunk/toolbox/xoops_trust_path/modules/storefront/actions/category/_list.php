<?php
require_once dirname(__FILE__).'/../../class/shop_category_list.php';

$renderOption['type'] = 'noheader';

$options = array();

// page
$page = @$_GET['page'];
if (!$page) {
	$page = 1;
}
$options['page'] = $page;

$resourceName = urldecode(@$_GET['resourceName']);
$keyWord = @$_GET['keyWord'];
$manager = new BilingualManager();
$pageInfo = null;

// find categories
if ($keyWord) {
	$pageInfo = ShopCategoryList::findByResourceNameAndKeyWord($resourceName, $keyWord, $manager, $options);
} else {
	$pageInfo = ShopCategoryList::findByResourceName($resourceName, $manager, $options);
}

$xoopsTpl -> assign(array(
	'pager' => $pageInfo,
	'keyWord' => htmlspecialchars($keyWord),
	'resourceName' => urlencode(htmlspecialchars($resourceName))
));

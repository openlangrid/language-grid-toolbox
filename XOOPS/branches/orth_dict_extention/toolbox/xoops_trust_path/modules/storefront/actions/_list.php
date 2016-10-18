<?php
// fetch page state.
$opts = $_GET;

// fetch list model
$receptionList = ReceptionList::findAll();
$receptions = $receptionList->getReceptions($opts);

$perPages = array(
	'5' => 5,
	'10' => 10,
	'20' => 20,
	'50' => 50,
	'0' => STF_LABEL_ALL
);

// sort order
$sortOrders = array(
	'ASC' => '▲',
	'DSC' => '▼',
);

$sortKey = $receptionList->getSortKey();
$order = $receptionList->getSortOrder();

$titles = array(
	'NAME' => array('label' => STF_LABEL_NAME, 'sortOrder' => $order, 'arrow' => '' ),
	'LANGUAGE' => array('label' => STF_LABEL_LANGUAGE, 'sortOrder' => $order, 'arrow' => '' ),
	'CREATOR' => array('label' => STF_LABEL_CREATOR, 'sortOrder' => $order, 'arrow' => '' ),
	'LAST_UPDATE' => array('label' => STF_LABEL_LAST_UPDATE, 'sortOrder' => $order, 'arrow' => '' ),
	'ENTRIES' => array('label' => STF_LABEL_ENTRIES, 'sortOrder' => $order, 'arrow' => '' ),
);

$titles[$sortKey]['arrow'] = $sortOrders[$order];
$titles[$sortKey]['sortOrder'] = $receptionList->getNextSortOrder();

$customizeUrl = "{$xoopsTpl->get_template_vars('mod_url')}/config/?action=list&resourceName=";
$startUrl = "{$xoopsTpl->get_template_vars('mod_url')}/category/?action=list&resourceName=";
$baseUrl = "{$xoopsTpl->get_template_vars('mod_url')}/?action=_list";
$pagerUrl = $baseUrl;
$sorterUrl = $baseUrl;

$hash = $_GET;

$hash['page'] = $receptionList->getCurrentPage();
$hash['perPage'] = $receptionList->getPerPage();
$hash['sortKey'] = $sortKey;
$hash['sortOrder'] = $order;

$queryString = CommonUtil::toQueryString($hash, array('action', 'page', 'perPage'));
if (strlen($queryString) > 0) {
	$pagerUrl .= "&{$queryString}";
}
$queryString = CommonUtil::toQueryString($hash, array('action', 'page', 'sortKey', 'sortOrder'));
if (strlen($queryString) > 0) {
	$sorterUrl .= "&{$queryString}";
}
$sorterUrl .= "&page=1";

$entryNumOfFound = sprintf(STF_LABEL_NUM_OF_FOUND,
			$receptionList->getMinNumber(), $receptionList->getMaxNumber(), $receptionList->countAll());

$xoopsTpl->assign(array(
	'entryNumOfFound' => $entryNumOfFound,
	'pagerUrl' => $pagerUrl,
	'sorterUrl' => $sorterUrl,
	'receptions' => $receptions,
	'pager' => $receptionList,
	'perPages' => $perPages,
	'customizeUrl' => $customizeUrl,
	'startUrl' => $startUrl,
	'titles' => $titles,
));

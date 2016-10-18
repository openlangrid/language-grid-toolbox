<?php
require_once dirname(__FILE__).'/../folder/_show.php';

$xoopsTpl->assign(array(
	'shopAnswerId' => $_GET['shopAnswerId'],
	'contentUpdateAreaId' => $_GET['contentUpdateAreaId'],
));

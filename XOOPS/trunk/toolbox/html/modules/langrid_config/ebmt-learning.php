<?php
/* $Id: ebmt-learning.php 4766 2010-11-17 09:05:35Z yoshimura $ */

require '../../mainfile.php';
require_once XOOPS_ROOT_PATH.'/modules/langrid_config/common.php';
require_once XOOPS_ROOT_PATH.'/modules/toolbox/toolbox.php';

$mydirname = basename( dirname( __FILE__ ) ) ;
$mydirpath = dirname( __FILE__ ) ;
$mytrustdirname = basename(dirname(__FILE__));

error_reporting(E_ALL);
ini_set('display_errors', 'On');

debugLog('EBMT-Learning Process start.');
try {
	require XOOPS_ROOT_PATH.'/service_grid/manager/EBMTLearningManager.class.php';
	$EBMTLearningManager = new EBMTLearningManager();
	$EBMTLearningManager->learning();
} catch (Exception $e) {
	debugLog(print_r($e, true));
}
debugLog('EBMT-Learning Process finish.');
?>
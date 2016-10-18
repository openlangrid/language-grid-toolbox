<?php

require('../../mainfile.php');
include(XOOPS_ROOT_PATH.'/header.php');

// Redirect to top page if user don't sign in.
$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

if (isset($_GET['page'])) {

	// Ajax
	$page = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , $_GET['page'] );
	$file = dirname(__FILE__).'/ajax/imported-services/'.$page.'.php';
	if(file_exists($file)) {
		include $file;
		die();
	}
}

$mydirname = basename(dirname(__FILE__));

$xoopsOption['template_main'] = 'langrid_imported_services.html';

$javaScripts = array(
	'imported-services-config.php',
	'templates.js',
	'panel.js',
	'imported-services-language-selectors-panel.js',
	'imported-services-language-paths-panel.js',
	'light-popup-panel.js',
	'imported-services-add-service-popup-panel.js',
	'imported-services-edit-service-popup-panel.js',
	'table-panel.js',
	'imported-services-table-panel.js',
	'imported-services-panel.js',
	'imported-services-main.js'
);

// Header
$xoops_module_header = $xoopsTpl->get_template_vars( "xoops_module_header" );

// JavaScript
foreach ($javaScripts as $javaScript) {
	$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/imported-services/'.$javaScript.'?'.time().'"></script>';
}

// CSS
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/imported-services.css?'.time().'" />';
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/user_style.css?'.time().'" />';

$module_img_url = XOOPS_URL.'/modules/'.$mydirname.'/images/';

$root = XCube_Root::getSingleton();
//$userIsAdmin = $root->mContext->mXoopsUser->isAdmin();
$userIsAdmin = true;

$xoopsTpl->assign(
	array(
		'xoops_module_header' => $xoops_module_header,
		'howToUse' => XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_LANGRID_IMPORTED_SERVICES_HOW_TO_USE_LINK,
		'module_img_url' => $module_img_url,
		'xoops_url' => XOOPS_URL,
		'userIsAdmin' => $userIsAdmin
	)
);
require_once(XOOPS_ROOT_PATH."/footer.php");
?>
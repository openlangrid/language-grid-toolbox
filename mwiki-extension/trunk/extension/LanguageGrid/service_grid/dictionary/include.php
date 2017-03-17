<?php

global $wgRequest, $wgOut, $wgServer, $wgScriptPath, $wgTitle, $wgArticle, $wgLanguageCode;

$wikiRootPath = $wgServer . $wgScriptPath;
$extRootPath = $wikiRootPath.'/extensions/LanguageGrid';
$myJsPath = $extRootPath.'/service_grid/dictionary/js';
$myCssPath = $extRootPath.'/service_grid/dictionary/css';

$mainJs = array(
	$extrootpath.'/common/js_lib/prototype-1.6.0.3.js',
	$extrootpath.'/common/js_lib/json2.js',
	$myjspath.'/language-util.js',
	$myjspath.'/ajax-wrapper.js',
	$myjspath.'/singleton-dialog.js',
	$myjspath.'/area-expand-collapse-controller.js',
	$myjspath.'/dictionary-main.js',
	$myjspath.'/dictionary-main-input-inspector.js',
	$myjspath.'/dictionary-editstate.js',
	$myjspath.'/include/include-dictionary-manager.js',
	$myjspath.'/include/include-dictionary-pane.js',
	$myjspath.'/include/include-dictionary-workspace.js',
	$myjspath.'/include/include-dictionary-main.js',
	$myjspath.'/load/load-dictionary-manager.js',
	$myjspath.'/load/load-dictionary-pane.js',
	$myjspath.'/load/load-dictionary-workspace.js',
	$myjspath.'/load/load-dictionary-main.js',
	$myjspath.'/upload/upload-dictionary-pane.js',
	$myjspath.'/upload/upload-dictionary-workspace.js',
	$myjspath.'/upload/upload-dictionary-main.js',
	$myjspath.'/translation/language-selector-pair.js',
	$myjspath.'/translation/license-area.js',
	$myjspath.'/translation/translator.js',
	$myjspath.'/translation/translation-timer.js',
	$myjspath.'/translation/translation-workspace.js',
	$myjspath.'/translation/translation-workspace-controller.js',
	$myjspath.'/translation/translation-main.js'
);

$mainCss = array(
	$myCssPath.'/style.css',
	$myCssPath.'/resources_style.css',
	$myCssPath.'/translation_style.css'
);
?>

<?php
// write  mainfile.php
$mainfile_add = false;
$main_path = dirname(dirname(dirname(__FILE__))).'/mainfile.php';
if (is_writable($main_path) && is_readable($main_path)){
	$text = array();
	$_text = file($main_path);
	foreach ($_text as $line){
		if( strstr( $line , "    define('XOOPS_COOKIE_PATH'" ) ) {
			$text[] = "    define('XOOPS_COOKIE_PATH','".addslashes(@$_POST['cookie_path'])."');\n" ;
			continue ;
		}
		if (strpos($line, 'define("XCL_MEMORY_LIMIT",')!==false ||
			strpos($line, 'define("LEGACY_INSTALLERCHECKER_ACTIVE"')!==false){
			continue;
		}
		$text[] = rtrim($line)."\n";
		if (strpos($line, 'define("XOOPS_MAINFILE_INCLUDED",1)')!==false){
			if (isset($_POST['memory_limit']) && preg_match('{^\d+M?$}i', $_POST['memory_limit'])){
				$text[] = sprintf('    define("XCL_MEMORY_LIMIT", "%s"); // extra param'."\n", 
								  $_POST['memory_limit']);
			}
			if (isset($_POST['ins_kill']) && $_POST['ins_kill']=="Yes"){
				$text[] = '    define("LEGACY_INSTALLERCHECKER_ACTIVE", false); // extra param'."\n";
			}
		}
	}
	if ($fp = fopen($main_path, "w")){
		if (fputs($fp, implode('', $text))!==false){
			$mainfile_add = true;
		}
	}
}

$wizard->assign('mainfile_add', $mainfile_add ? _OKIMG._INSTALL_CL2_1 : _NGIMG._INSTALL_CL2_2); 
$wizard->setTemplatePath(dirname(__FILE__));
$wizard->render('templates/install_serversettings_save.html');

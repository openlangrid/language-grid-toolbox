<?php
/// Server-setting values
$ss_check = array();
$ss_error = false; // mainfile.phpにてフォローできるもの PHP_INI_ALL
$htaccess = ''; // htaccess推奨 PHP_INI_PERDIR

require_once dirname(dirname(dirname(__FILE__)))."/mainfile.php";

// error initial
$error = false;

// memory_limit
$memory_limit = !defined('XCL_MEMORY_LIMIT') ? ini_get('memory_limit') : XCL_MEMORY_LIMIT;
$ml_ok = intval($memory_limit)>=16 ;

$ss_check[] = array(
	'title' => _INSTALL_CL1_1,
	'msg'   => $ml_ok ? _OKIMG._INSTALL_CL1_2 : _NGIMG._INSTALL_CL1_3 ,
	'em'    => $ml_ok ? '' : _INSTALL_CL1_4 ,
	'form'  => sprintf('<input type="text" size="4" value="%s" name="memory_limit">', 
					   $memory_limit),
	);

// install_dir_checker_canceler
$ins_kill = defined('LEGACY_INSTALLERCHECKER_ACTIVE');
$ss_check[] = array(
	'title' => _INSTALL_CL1_5,
	'msg'   => _INSTALL_CL1_6,
	'em'    => '' ,
	'form'  => sprintf('<input type="text" size="3" value="%s" name="ins_kill" onclick="if(this.readOnly && confirm(\'%s\')){this.readOnly=false;}" onblur="this.readOnly=true;" readonly>', 
					   $ins_kill ? 'Yes': 'No', _INSTALL_CL1_7),
	);
$wizard->assign('check', $ss_check);

/// .htaccess
if (ini_get('magic_quotes_gpc')){
	$htaccess .= "php_flag magic_quotes_gpc Off\n";
}
// mbstring
if (extension_loaded('mbstring')){
	if (ini_get('mbstring.encoding_translation')){
		$htaccess .= "php_flag mbstring.encoding_translation Off\n";
	}
	if (strtolower(ini_get('mbstring.http_input'))!='pass'){
		$htaccess .= "php_value mbstring.http_input pass\n";
	}
	if (strtolower(ini_get('mbstring.http_output'))!='pass'){
		$htaccess .= "php_value mbstring.http_output pass\n";
	}
	if (intval(ini_get('mbstring.func_overload'))>0){
		$htaccess .= "php_value mbstring.func_overload 0\n";
	}
}
$wizard->assign('htaccess',$htaccess);

if(! $error) {
	$wizard->assign('message',_INSTALL_CL1_OK);
}else{
	$wizard->assign('message',_INSTALL_CL1_NG);
	$wizard->setReload(true);
}

$wizard->setTemplatePath(dirname(__FILE__));
$wizard->render('templates/install_serversettings.html');

<?php

	include_once '../mainfile.php';
	include_once dirname(__FILE__).'/class/settingmanager_hd.php';
	$sm = new setting_manager_hd();
	$sm->readConstant();
	$hints_666 = '';
	$hints_777 = '';

	// writable directories under xoops_trust_path
	$writeok = array( $sm->trust_path.'/cache' , $sm->trust_path.'/templates_c' , $sm->trust_path.'/uploads' , $sm->trust_path.'/session' , $sm->trust_path.'/log' , $sm->trust_path.'/tmp' ) ;

	// get each module's directories should be writable
	$dh = opendir( $sm->trust_path . '/modules' ) ;
	while( $trustdirname = readdir( $dh ) ) {
		if( file_exists( $sm->trust_path . '/modules/' . $trustdirname . '/include/install_extramodcheck.inc.php' ) ) {
			require $sm->trust_path . '/modules/' . $trustdirname . '/include/install_extramodcheck.inc.php' ;
			$func_name = 'get_writeoks_from_' . $trustdirname ;
			if( function_exists( $func_name ) ) {
				$func_ret = $func_name( $sm->root_path , $trustdirname ) ;
				if( is_array( $func_ret ) ) {
					$writeok = array_merge( $writeok , $func_ret ) ;
				} else {
					$writeok = array_merge( $writeok , array( $func_ret ) ) ;
				}
			}
		}
	}
	closedir( $dh ) ;

	$error = false;
	foreach ($writeok as $wok) {
		if (!is_dir($wok)) {
			if ( file_exists($wok) ) {
				@chmod($wok, 0666);
				if (! is_writeable($wok)) {
					$wizard->addArray('checks',_NGIMG.sprintf(_INSTALL_L83, $wok));
					$error = true;
					$hints_666 .= $hints_666 ? ' '.$wok : 'chmod 0666 '.$wok ;
				}else{
					$wizard->addArray('checks',_OKIMG.sprintf(_INSTALL_L84, $wok));
				}
			}
		} else {
			@chmod($wok, 0777);
			if (! is_writeable($wok)) {
				$wizard->addArray('checks',_NGIMG.sprintf(_INSTALL_L85, $wok));
				$error = true;
				$hints_777 .= $hints_777 ? ' '.$wok : 'chmod 0777 '.$wok ;
			}else{
				$wizard->addArray('checks',_OKIMG.sprintf(_INSTALL_L86, $wok));
			}
		}
	}

	if(! $error) {
		$wizard->assign('message',_INSTALL_L87);
	}else{
		$message = sprintf('hint:<br><textarea onfocus="this.select();" style="width:100%%;height:60px;" readonly>%s</textarea><br>%s',
						   ($hints_777 ? $hints_777."\n" : '').($hints_666 ? $hints_666."\n" : '') , _INSTALL_L46);
		$wizard->assign('message', $message);
		$wizard->setReload(true);
	}
	$wizard->render('install_modcheck.tpl.php');
?>

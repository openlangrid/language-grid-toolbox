<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

$modversion['name'] = _MD_TASK_MODULE_NAME;
$modversion['image'] = 'module_icon.png';
$modversion['description'] = _MD_TASK_MODULE_DESCRIPTION;
$modversion['version'] = 0.90;
$modversion['license'] = "GPL" ;
$modversion['official'] = 0 ;
$modversion['dirname'] = $mydirname ;

$modversion['onInstall'] = 'oninstall.php' ;
$modversion['onUpdate'] = 'onupdate.php' ;
$modversion['onUninstall'] = 'onuninstall.php' ;

$modversion['hasAdmin'] = 0;
$modversion['hasMain'] = 1;
$modversion['read_any'] = true ;

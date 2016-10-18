<?php
// the name of this module
$modversion['name'] = STF_MODULE_NAME;
$modversion['image'] = 'module_icon.png';

// the description of this module
$modversion['description'] = STF_MODULE_DESCRIPTION;

// this module's version
$modversion['version'] = 0.90;

// license
$modversion['license'] = "GPL";

// 0:this module is not official
// 1:official
$modversion['official'] = 0;

$modversion['dirname'] = $mydirname;

// ----------------------------------------------
// Admin things
// ----------------------------------------------
// 0:this module does not lets user configure something
// 1:does let
$modversion['hasAdmin'] = 1;

// ----------------------------------------------
// Menu
// ----------------------------------------------
// 0:this module will not be displayed in main menu
// 1:will be displayed
$modversion['hasMain'] = 1;

// ----------------------------------------------
// Configuration
// ----------------------------------------------
// no congifurations

// ----------------------------------------------
// Others
// ----------------------------------------------
$modversion['onInstall'] = 'oninstall.php' ;
$modversion['onUpdate'] = 'onupdate.php' ;
$modversion['onUninstall'] = 'onuninstall.php' ;

$modversion['read_any'] = true ;

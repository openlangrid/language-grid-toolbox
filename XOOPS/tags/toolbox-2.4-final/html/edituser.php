<?php
/**
 *
 * @package Legacy
 * @version $Id: edituser.php,v 1.3 2008/09/25 15:10:27 kilica Exp $
 * @copyright Copyright 2005-2007 XOOPS Cube Project  <http://xoopscube.sourceforge.net/> 
 * @license http://xoopscube.sourceforge.net/license/GPL_V2.txt GNU GENERAL PUBLIC LICENSE Version 2
 *
 */
/*------------------------------------------------------------------------*
 |  This file was entirely rewritten by the XOOPS Cube Legacy project for |
 |   keeping compatibility with XOOPS 2.0.x <http://www.xoops.org>        |
 *------------------------------------------------------------------------*/

require_once "mainfile.php";

if(empty($_POST)) require_once 'modules/user/subProfile/getUserSubProfile.php';
else require_once 'modules/user/subProfile/setUserSubProfile.php';

XCube_DelegateUtils::call("Legacypage.Edituser.Access");
?>
<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //
require_once(dirname(__FILE__).'/../../../mainfile.php');

echo '<pre>';
echo 'start.';

$_root_ =& XCube_Root::getSingleton();
echo 'created root object.';

if ($_root_->mContext->mXoopsUser === null) {
	echo 'No User.';
} else {
	print_r($_root_->mContext->mXoopsUser);
}

require_once(XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php');
$lgclient = new LangridAccessClient();
$dist = $lgclient->translate('en', 'ja', 'How are you', 'BBS');
print_r($dist);

echo 'end';
echo '</pre>';
//error_reporting(E_ALL);
//
////echo file_exists('../../../mainfile.php');
//
//require('../../../mainfile.php');
//require('../../../header.php');
//
//echo 'start';
//$root =& XCube_Root::getSingleton();
//echo 'start2';
//var_dump($root);
//echo 'start3';
////if ($root->mContext->mXoopsUser) {
////	// non;
////} else {
////	require_once(XOOPS_ROOT_PATH.'/modules/user/class/users.php');
////	$userhandler = new UserUsersHandler($root->mController->mDB);
////	$user =& $userhandler->get('1');
////	$root->mContext->mXoopsUser = $user;
////}
//echo 'start4';
//
//require_once(XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php');
////require_once(XOOPS_ROOT_PATH.'/api/class/manager/Toolbox_LangridAccess_TranslationManager.class.php');
////$manager = new Toolbox_LangridAccess_TranslationManager();
////echo 'a new Manager()<br>';
////$dist = $manager->translate('en', 'ja', 'How are you', 'BBS');
//
//$lgclient = new LangridAccessClient();
//print_r($lgclient->getBindingSet('BBS'));
//$dist = $lgclient->translate('en', 'ja', 'How are you', 'BBS');
//print_r($dist);
//


?>

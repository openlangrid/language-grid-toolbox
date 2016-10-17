<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
class Forum_AutoLoginHack extends XCube_ActionFilter
{
    var $mCookiePath;
    var $mRememberMe = 0;
    var $mLifeTime;

    function preBlockFilter()
    {
    	$url = $_SERVER['REQUEST_URI'];
    	if (strpos($url, 'modules/forum/notifynewpost.php') !== false) {
	        $root =& XCube_Root::getSingleton();
	        $root->mDelegateManager->add('Legacy_Controller.SetupUser', array(&$this, 'setupUser'), XCUBE_DELEGATE_PRIORITY_FINAL-1);
    	}
    }

    function setupUser(&$principal, &$controller, &$context) {
        $root =& XCube_Root::getSingleton();
        $xoopsUser = $this->getUserObject();
		if ($xoopsUser == null) {
			die('管理者ユーザでの偽装ログインに失敗しました。');
		}
        $context->mXoopsUser =& $xoopsUser;
        // Regist to session
        $root->mSession->regenerate();
        $_SESSION['xoopsUserId'] = $xoopsUser->getVar('uid');
        $_SESSION['xoopsUserGroups'] = $xoopsUser->getGroups();

        $context->mXoopsUser->setGroups($_SESSION['xoopsUserGroups']);

        $roles = array();
        $roles[] = "Site.RegisteredUser";
        if ($context->mXoopsUser->isAdmin(-1)) {
            $roles[] = "Site.Administrator";
        }
        if (in_array(XOOPS_GROUP_ADMIN, $_SESSION['xoopsUserGroups'])) {
            $roles[] = "Site.Owner";
        }

        $identity =& new Legacy_Identity($context->mXoopsUser);
        $principal = new Legacy_GenericPrincipal($identity, $roles);
        XCube_DelegateUtils::call('Site.CheckLogin.Success', new XCube_Ref($xoopsUser));
    }

    function getUserObject() {
        $user_handler =& xoops_gethandler('user');
        return $user_handler->get(1);
    }
}
?>
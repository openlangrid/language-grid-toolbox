<?php
class LangridConfig_AutoLoginHack extends XCube_ActionFilter
{
    var $mCookiePath;
    var $mRememberMe = 0;
    var $mLifeTime;

    function preBlockFilter()
    {
    	$url = $_SERVER['REQUEST_URI'];
    	if (strpos($url, 'modules/langrid_config/ebmt-learning.php') !== false) {
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
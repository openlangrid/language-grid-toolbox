<?php
/**
 *
 * @package HdLegacy
 * @version $Id: SiteClose.class.php,v 1.4 2007/09/22 06:50:16 gusagi Exp $
 * @copyright Copyright 2005-2008 Hodajuku  <https://sourceforge.net/projects/hodajuku/>
 * @license http://www.gnu.org/licenses/gpl.txt GNU GENERAL PUBLIC LICENSE Version 2
 *
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

if ( ! class_exists('Legacy_SiteClose') ) {
    include_once( XOOPS_ROOT_PATH . '/modules/legacy/preload/Primary/SiteClose.class.php' );
}

/***
 * The action filter for the site close procedure.
 */
class HdLegacy_SiteClose extends Legacy_SiteClose
{
	function preBlockFilter()
	{
		if ($this->mRoot->mContext->getXoopsConfig('closesite') == 1) {
			$this->mController->mSetupUser->add("HdLegacy_SiteClose::callbackSetupUser", XCUBE_DELEGATE_PRIORITY_FINAL-1);
			$this->mRoot->mDelegateManager->add("Site.CheckLogin.Success", array(&$this, "callbackCheckLoginSuccess"));
		}
	}

	/**
	 * Checks whether the site is closed now, and whether all of must modules
	 * have been installed. This function is called through delegates.
	 * @var XoopsUser &$xoopsUser
	 * @see preBlockFilter()
	 */
	function callbackSetupUser(&$principal, &$controller, &$context)
	{
		$accessAllowFlag = false;
		$xoopsConfig = $controller->mRoot->mContext->getXoopsConfig();

		if (!empty($_POST['xoops_login'])) {
			$controller->checkLogin();
			return;
		} else if (@$_GET['op']=='logout') { // GIJ
			$controller->logout();
			return;
		} elseif (is_object($context->mXoopsUser)) {
			foreach ($context->mXoopsUser->getGroups() as $group) {
				if (in_array($group, $xoopsConfig['closesite_okgrp']) || XOOPS_GROUP_ADMIN == $group) {
					$accessAllowFlag = true;
					break;
				}
			}
		}

		if (!$accessAllowFlag) {
			require_once XOOPS_ROOT_PATH . '/class/template.php';
			$xoopsTpl = new XoopsTpl();
			$xoopsTpl->assign(array('xoops_sitename' => htmlspecialchars($xoopsConfig['sitename']),
									   'xoops_isuser' => is_object( $context->mXoopsUser ),//GIJ
									   'xoops_themecss' => xoops_getcss(),
									   'xoops_imageurl' => XOOPS_THEME_URL . '/' . $xoopsConfig['theme_set'] . '/',
									   'lang_login' => _LOGIN,
									   'lang_username' => _USERNAME,
									   'lang_password' => _PASSWORD,
									   'lang_siteclosemsg' => $xoopsConfig['closesite_text']
									   ));

			$xoopsTpl->compile_check = true;

			// @todo filebase template with absolute file path
			$site_close_tpl = sprintf('%s/themes/%s/templates/hdlegacy_site_closed.html',
									  XOOPS_ROOT_PATH, $xoopsConfig['theme_set']);
			if (!file_exists($site_close_tpl)){
				$site_close_tpl = XOOPS_ROOT_PATH . '/modules/hdLegacy/templates/hdlegacy_site_closed.html';
				$xoopsTpl->display($site_close_tpl);
			} else {
				$xoopsTpl->assign('xoops_contents', $xoopsTpl->fetch($site_close_tpl));
				$theme_file = sprintf("%s/themes/%s/theme.html", XOOPS_ROOT_PATH, $xoopsConfig['theme_set']);
				$xoopsTpl->assign('xoops_rootpath', XOOPS_ROOT_PATH );
				$xoopsTpl->assign('xoops_theme', $xoopsConfig['theme_set']);
				$xoopsTpl->display($theme_file);
			}
			exit();
		}
	}
}

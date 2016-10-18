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
class AbstractAction extends Action {

	function getParameter($key, $row = false) {
		$value = null;
		if (isset($_GET[$key])) {
			$value = $_GET[$key];
		} else if(isset($_POST[$key])) {
			$value = $_POST[$key];
		} else {
			return null;
		}
		if ($row) {
			return htmlentities($value);
		}
		if (get_magic_quotes_gpc()) {
			if (is_array($value)) {
				$stripped = array();
				foreach($value as $v) {
					$stripped[] = stripslashes($v);
				}
				return $stripped;
			} else {
				return stripslashes($value);
			}
		} else {
			return $value;
		}
	}

	function initMlLang(&$context) {
		// for HttpParameter
		$lang = $context->get('ml_lang', null);
		if (! $lang) {
			$lang = $this->getMlLanguage();
			$timeout = time() + (30 * 24 * 60 * 60); // 30days
			setcookie('webqa_ml_lang', $lang, $timeout, APP_COOKIE_PATH);
		}

		// use_lang and view_lang makes confusing at edit
		if (isset($_GET['use_lang'])) {
			$_GET['view_lang'] = $_GET['use_lang'];
		} else if (isset($_GET['view_lang'])) {
			$_GET['use_lang'] = $_GET['view_lang'];
		}

		$ulang = $this->getUseLanguage();
		$_SESSION['use_lang'] = $ulang;
		$vlang = $this->getViewLanguage();
		$_SESSION['view_lang'] = $vlang;

		$context->set('ml_lang', $lang);
		$context->set('use_lang', $ulang);
		$context->set('view_lang', $vlang);

		// set for language resource.
		$langName = '';

		switch ( $lang ) {
			case 'ja':
				$langName = 'ja_utf8';
				break;
			case 'en':
				$langName = 'english';
				break;
			case 'ko':
				$langName = 'korean';
				break;
			case 'zh-CN':
				$langName = 'zh_CN_utf8';
				break;
			default: // en
				$langName = 'english';
				break;
		}
		require_once(sprintf(APP_ROOT_DIR.'/language/%s/common.php', $langName));
		require_once(sprintf(XOOPS_ROOT_PATH . '/themes/' . APP_THEME_NAME . '/language/%s.php', $langName));
	}

	private function getMlLanguage() {
		$language = 'en';

		if (isset($_GET['ml_lang']) && $this->isValidMlLanguage($_GET['ml_lang'])) {
			$language = $_GET['ml_lang'];
		} else if (isset($_COOKIE['webqa_ml_lang']) && $this->isValidMlLanguage($_COOKIE['webqa_ml_lang'])) {
			$language = $_COOKIE['webqa_ml_lang'];
		}

		return $language;
	}

	private function isValidMlLanguage($language) {
		return in_array($language, array('ja', 'en', 'ko', 'zh-CN'));
	}

	private function getUseLanguage() {
		$language = $this->getMlLanguage();

		if (isset($_GET['use_lang']) && $this->isValidUseLanguage($_GET['use_lang'])) {
			$language = $_GET['use_lang'];
		} else if (isset($_SESSION['use_lang']) && $this->isValidUseLanguage($_SESSION['use_lang'])) {
			$language = $_SESSION['use_lang'];
		}

		return $language;
	}

	private function isValidUseLanguage($language) {
		return ($language != null);
	}
	
	protected function isFilterZeroAnswer() {
		return !self::isAuthorized();
	}

	protected function getLoginUname() {
		$uname = null;
		if (XCube_Root::getSingleton()->mContext->mXoopsUser) {
			$uname = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uname');
		}
		return $uname;
	}

	protected function getViewLanguage() {
		$language = $this->getUseLanguage();

		if (isset($_GET['view_lang']) && $this->isValidUseLanguage($_GET['view_lang'])) {
			$language = $_GET['view_lang'];
		} else if (isset($_SESSION['view_lang']) && $this->isValidUseLanguage($_SESSION['view_lang'])) {
			$language = $_SESSION['view_lang'];
		}

		return $language;
	}
    
    static public function isAuthorized() {
    	$uid = XCube_Root::getSingleton()->mContext->mXoopsUser && 
    			XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
		if($uid == null) return false;

		// Prohibit a guest user from accessing as a registered user
		$isGuestGroup = in_array(XOOPS_GROUP_ANONYMOUS, 
			XCube_Root::getSingleton()->mContext->mXoopsUser->getGroups());
		return !$isGuestGroup;
    }
}
?>

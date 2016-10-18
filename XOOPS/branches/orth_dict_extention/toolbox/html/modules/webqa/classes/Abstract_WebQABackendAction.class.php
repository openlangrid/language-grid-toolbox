<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
/* $Id: Abstract_WebQABackendAction.class.php 6254 2012-01-23 05:33:54Z infonic $ */
/**
 * <#if lang="ja">
 * WebQAバックエンド処理コントローラの基底クラス
 * </#if>
 */
abstract class Abstract_WebQABackendAction {

	protected $_response = null;



	/**
	 * コンストラクタ
	 */
    public function Abstract_WebQABackendAction() {
		// none;
    }

	/**
	 * エントリポイント
	 */
    public function execute() {
    	//$this->spoofingLoginUser();
    	try {
			$this->_response = $this->dispatch();
    	} catch (Exception $e) {
    		mylog(print_r($e, true));
    	}

//		// ログアウトする
		$this->logout();
//		$root =& XCube_Root::getSingleton();
//		$root->mController->logout();
    }

	/**
	 * レスポンスデータを生成する
	 * 　JSON形式でレスポンスデータを生成します。
	 */
    public function makeResponse() {
    	if ($this->_response == null) {
    		return "";
    	} else {
    		return json_encode($this->_response);
    	}
    }

	/**
	 * HTTPレスポンスを出力する
	 */
    public function outputResponse() {
    	header('Content-Type: application/json; charset=utf-8;');
    	echo $this->makeResponse();
    	die();
    }

	/**
	 * モジュール設定を返す
	 */
	public function getModuleConfig() {
		$root =& XCube_Root::getSingleton();
		$config = $root->mContext->mModule->mModuleConfig;
		return $config;
	}

	public function spoofingLoginUser() {
		require_once(XOOPS_ROOT_PATH.'/modules/user/class/users.php');
		$root =& XCube_Root::getSingleton();
		$userhandler = new UserUsersHandler($root->mController->mDB);
		$user =& $userhandler->get('1');
		$root->mContext->mXoopsUser =& $user;
	}

	function getParameter($key, $row = false) {
		$value = isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
		if ($row && $value) {
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

	function checkParameter($key, $row = false) {
		if (isset($_REQUEST[$key])) {
			return $this->getParameter($key, $row);
		}
		return null;
	}

	function logout() {
		$root =& XCube_Root::getSingleton();
		//$xoopsConfig = $root->mContext->mXoopsConfig;

		$root->mLanguageManager->loadModuleMessageCatalog('user');

		// Reset session
		$_SESSION = array();
		$root->mSession->destroy(true);

		// clear entry from online users table
		if (is_object($root->mContext->mXoopsUser)) {
			$onlineHandler =& xoops_gethandler('online');
			$onlineHandler->destroy($root->mContext->mXoopsUser->get('uid'));
		}
    }

	/**
	 * 派生クラスで処理の実体を実装してください。
	 */
    protected abstract function dispatch();

}

function log_info($val) {
//	error_log(print_r($val, 1), 3, dirname(__FILE__).'/../log/'.basename(__FILE__).'-'.mtime().'log');
	error_log(date("Y-m-d H:i:s"), 3, dirname(__FILE__).'/../log/'.basename(__FILE__).'.log');
	error_log(print_r($val, 1), 3, dirname(__FILE__).'/../log/'.basename(__FILE__).'.log');
}
?>
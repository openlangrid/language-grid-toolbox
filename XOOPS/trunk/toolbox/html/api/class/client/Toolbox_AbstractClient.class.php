<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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

class Toolbox_AbstractClient {

    function __construct() {
    	if (!$this->_loadLanguageFile()) {
    		die('Language define file failed to load.');
    	}
		if (!$this->_checkLogin()) {
			if (defined(_TBOX_API_NO_LOGIN)) {
				die(_TBOX_API_NO_LOGIN);
			} else {
				die('Please use Toolbox API after the user logs into Toolbox.');
			}
		}
    }

    function _loadLanguageFile() {
    	if (file_exists(XOOPS_ROOT_PATH . '/modules/legacy/kernel/Legacy_LanguageManager.class.php')) {
    		require_once XOOPS_ROOT_PATH . '/modules/legacy/kernel/Legacy_LanguageManager.class.php';
    		$languageManager = new Legacy_LanguageManager();
    		$languageManager->loadPageTypeMessageCatalog('api');
    		return true;
		}
    	return false;
    }

    function _checkLogin() {
    	$root =& XCube_Root::getSingleton();
		if ($root->mContext->mXoopsUser == null) {
			return false;
		} else {
			return true;
		}
    }

	/**
	 * 
	 * @param String $status
	 * @param String $message
	 * @param mixed $contens
	 * @return array
	 */
	protected function buildResponse($status, $message, $contents) {
		return array(
			'status' => $status,
			'message' => $message,
			'contents' => $contents
		);
	}
	
	/**
	 * 
	 * @param mixed $value
	 * @param String $message optional
	 * @throws Exception
	 * @return void
	 */
	protected function assertNotEmpty($value, $message = '') {
		if ($value == null || $value == '') {
			throw new Exception($message);
		}
	}
	
	/**
	 * 
	 * @param int $expected
	 * @param int $actual
	 * @param String $message optional
	 * @throws Exception
	 * @return void
	 */
	protected function assertGreaterThan($expected, $actual, $message = '') {
		if (!($expected < $actual)) {
			throw new Exception($message);
		}
	}
	
	/**
	 * 
	 * @param String $type
	 * @param mixed $value
	 * @param String $message optional
	 * @throws Exception
	 * @return void
	 */
	protected function assertType($type, $value, $message = '') {
		switch ($type) {
		case 'array':
			if (!is_array($value)) {
				throw new Exception($message);
			}
			break;
		default:
			if (!is_a($value, $type)) {
				throw new Exception($message);
			}
			break;
		}
	}
	
	/**
	 * 
	 * @param String $type
	 * @param mixed $value
	 * @param String $message optional
	 * @throws Exception
	 * @return void
	 */
	protected function assertContainsOnly($type, $value, $message = '') {
		foreach ($value as $v) {
			$this->assertType($type, $v, $message);
		}
	}
}
?>
<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2010  NICT Language Grid Project
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

require_once APP_ROOT_PATH.'/class/toolbox/ToolboxUtil.class.php';

abstract class AbstractAction {
	
	protected $errorMessages = array();
	
	public function __construct() {
		
	}
	
	/**
	 * 
	 */
	public function isError() {
		return count($this->errorMessages) > 0;
	}
	
	public function getParameter($key) {
		
		$value = '';

		if (isset($_POST[$key])) {
			$value = $_POST[$key];
		} else if (isset($_GET[$key])) {
			$value = $_GET[$key];
		}
		
		$value = $this->getRow($value);
		
		return $value;
	}
	
	private function getRow($value) {
		if (!get_magic_quotes_gpc()) {
			return $value;
		}
		
		if (is_array($value)) {
			for ($key = array_keys($value), $i = 0, $length = count($key); $i < $length; $i++) {
				$value[$key[$i]] = $this->getRow($value[$key[$i]]);
			}
			return $value;
		}
		
		return stripslashes($value);
	}
	
	/**
	 * @return String[] Error messages
	 */
	public function getErrorMessages() {
		return $this->errorMessages;
	}
	
	public function execute() {
		if (!ToolboxUtil::isLoginUser()) {
			redirect_header(XOOPS_URL.'/');
		}
	}
	
	private function isLoginUser() {
		return ToolboxUtil::isLoginUser();
	}
	
	public function executeView(&$render) {
		
	}
}
?>
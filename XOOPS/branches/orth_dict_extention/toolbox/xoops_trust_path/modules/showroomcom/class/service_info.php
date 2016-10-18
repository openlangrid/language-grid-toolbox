<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

class ServiceInfo {
	private $loginUser;
	public $moduleName;
	public $submoduleName;
	public $action;

	public function __construct($moduleName, $submoduleName, $action) {
		$this -> loginUser = XCube_Root::getSingleton()->mContext->mXoopsUser;
		$this -> moduleName = $moduleName;
		$this -> submoduleName = $submoduleName;
		$this -> action = $action;
	}

	public function getUserId() {
		return $this -> loginUser -> get('uid');
	}

	public function getUserName() {
		return $this -> loginUser -> get('uname');
	}

	public function getName() {
		return $this->loginUser->get('name');
	}

	public function getModulePath() {
		return XOOPS_URL."/modules/".$this -> moduleName;
	}
	
	public function getModuleName() {
		return $this -> moduleName;
	}
}
?>
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

class WebCreation {

	/**
	 * Constructor
	 * @param unknown_type $admin
	 */
	public function __construct($admin = false) {
		$this->admin = $admin;
		$root = XCube_Root::getSingleton();
		$this->action = $root->mContext->mRequest->getRequest('action');

		if ($this->action == '' || !preg_match('/^\w+$/', $this->action)) {
			$this->action = 'index';
		}
	}

	/**
	 *
	 * @param unknown_type $controller
	 */
	public function execute($controller) {

		$filePath = $this->getActionFilePath();

		if (!file_exists($filePath)) {
			die('Invalid action error.');
		}

		require_once $filePath;

		$className = $this->getActionClassName();

		$action = new $className($controller);
		$action->execute();

		if ($action->isError()) {
			die('Action error.');
		} else {
			$action->executeView($controller->mRoot->mContext->mModule->getRenderTarget());
		}
	}

	/**
	 *
	 */
	private function getActionClassName() {
		return ucfirst($this->action).'Action';
	}

	/**
	 *
	 */
	private function getActionFilePath() {
		$className = $this->getActionClassName();

		$filePath = APP_ROOT_PATH.'/class/action';

		if ($this->admin) {
			$filePath .= '/admin';
		}

		$filePath .= '/'.$className.'.class.php';

		return $filePath;
	}
}
?>
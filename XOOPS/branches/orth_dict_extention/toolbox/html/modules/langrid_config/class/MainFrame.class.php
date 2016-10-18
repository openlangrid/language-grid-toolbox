<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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
/* $Id: MainFrame.class.php 4826 2010-11-26 07:32:54Z kitajima $ */

class MainFrame {

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

		$this->page = $root->mContext->mRequest->getRequest('page');
		if ($this->page == '' || !preg_match('/^\w+$/', $this->page)) {
			$this->page = '';
		}
		if ($this->page != '' && $this->page[strlen($this->page)-1] !== '/') {
			$this->page .= '/';
		}

	}

	/**
	 *
	 * @param unknown_type $controller
	 */
	public function execute($controller) {
		try {
			$filePath = $this->getActionFilePath();

			if (!file_exists($filePath)) {
				die('Invalid action error.'.$filePath);
			}
			header('X-Toolbox_InvokeAction: '.$filePath);

			require_once($filePath);

			$className = $this->getActionClassName();

			header("x-InvokeClass: ".$className);

			$action = new $className($controller);
			$action->execute();

			if ($action->isError()) {
				die('Action error.');
			} else {
				$action->executeView($controller->mRoot->mContext->mModule->getRenderTarget());
			}
		} catch (Exception $e) {
			print_r($e);
		}
	}

	/**
	 *
	 */
	private function getActionClassName() {
//		return ucfirst($this->action).'Action';

		$page = ucwords(str_replace('_', ' ', $this->page));
		$page = str_replace(' ', '', $page);

		return ucfirst(str_replace('/', '_', $page)) . ucfirst($this->action).'Action';
	}

	/**
	 *
	 */
	private function getActionFilePath() {
		$className = $this->getActionClassName();
		$filePath = MY_MODULE_PATH.'/class/action';

		if ($this->admin) {
			$filePath .= '/admin';
		}

//		$filePath .= '/'.$className.'.class.php';
		$filePath .= '/' . $this->page . $className.'.class.php';

		return $filePath;
	}
}
?>
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
/* $Id: AjaxAction.class.php 3750 2010-07-22 05:11:43Z yoshimura $ */

require_once MY_MODULE_PATH.'/class/action/AbstractAction.class.php';

class AjaxAction extends AbstractAction {

	protected $result;

	protected function buildResult($status, $message, $contents) {
		$this->result = array(
			'status' => $status,
			'message' => $message,
			'contents' => $contents
		);
	}

	protected function buildSuccessResult($contents) {
		$this->buildResult('OK', 'Success', $contents);
	}

	protected function buildErrorResult($message) {
		$this->buildResult('Error', $message, null);
	}

	public function executeView() {
//		header('Content-Type: application/json; charset=utf-8;');
		echo json_encode($this->result);
		die();
	}
}
?>
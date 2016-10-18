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

abstract class Toolbox_AbstractManager {

	protected $root = null;
	protected $db = null;
	protected $uid = null;

	public function __construct() {
		$this->root =& XCube_Root::getSingleton();
		$this->db = $this->root->mController->mDB;
		if ($this->root->mContext->mXoopsUser) {
			$this->uid = $this->root->mContext->mXoopsUser->get('uid');
		}
	}

	protected function getResponsePayload($contents=array(), $status='OK', $message='NoError') {
		$response = array(
			'status'=>$status,
			'message'=>$message,
			'contents'=>$contents);
		return $response;
	}

	protected function getErrorResponsePayload($message, $status='Error') {
		$response = array(
			'status'=>$status,
			'message'=>$message,
			'contents'=>'');
		return $response;
	}

}
?>
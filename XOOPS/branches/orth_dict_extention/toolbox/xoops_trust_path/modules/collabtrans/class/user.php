<?php
//  ------------------------------------------------------------------------ //
// This is a Language Grid Toolbox module. This module extends
// the BBS module for real-time discussions.
// Copyright (C) 2010 Graduate School of Informatics, Kyoto University. 
// All Rights Reserved.
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
class User {
	private $id;
	private $name;
	private $user_avatar;
	private $icon;

	public function __construct($id) {
		$this->id = $id;

		$userHandler =& xoops_gethandler('user');
		$user =& $userHandler->get($this->id);

		if ($user) {
			$this->name = $user->getVar('name');
			if (!$this->name) {
				$this->name = $user->getVar('uname');
			}
			$this->user_avatar = $user->getVar('user_avatar');
		}
	}
	public function getId() {
		return $this->id;
	}
	public function getName() {
		return $this->name;
	}
	public function getUserAvatar() {
		return $this->user_avatar;
	}
	
	public function getIcon() {
		if ($this->user_avatar == 'blank.gif') {
			return XOOPS_URL.'/modules/user/images/no-image.jpg';
		} else {
			return XOOPS_UPLOAD_URL.'/'.$this->user_avatar;
		}
	}
}
?>
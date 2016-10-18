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
// $Id: ProfileClient.class.php 6209 2011-11-30 02:34:21Z mtanaka $

require_once(dirname(__FILE__).'/../../IProfileClient.interface.php');
require_once(dirname(__FILE__).'/Toolbox_AbstractClient.class.php');
require_once(dirname(__FILE__).'/../manager/Toolbox_Profile_UserManager.class.php');

class ProfileClient extends Toolbox_AbstractClient implements IProfileClient {

	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 * @return array
	 */
	public function getAllUserIDs() {
		$manager = new Toolbox_Profile_UserManager();
		return $manager->getAllList();
	}

	/**
	 *
	 * @return $id
	 */
	public function getCurrentUserID() {
		$manager = new Toolbox_Profile_UserManager();
		return $manager->getCurrentUser();
	}

	/**
	 *
	 * @param $userId
	 * @return array
	 */
	public function getProfile($userId) {
		$manager = new Toolbox_Profile_UserManager();
		return $manager->getUser($userId);
	}

	/**
	 *
	 * @param $profile
	 * @param $userId
	 * @return void
	 */
	public function setProfile($profile, $userId = null) {
		$manager = new Toolbox_Profile_UserManager();
		return $manager->updateUser($userId, $profile);
	}
}
?>

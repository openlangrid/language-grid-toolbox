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
// $Id: IProfileClient.interface.php 1619 2009-10-13 04:45:38Z yoshimura $

/**
 * ProfileClient
 */
interface IProfileClient {
	/**
	 *
	 * @return array
	 */
	public function getAllUserIDs();
	/**
	 *
	 * @return $id
	 */
	public function getCurrentUserID();
	/**
	 *
	 * @param $userId
	 * @return array
	 */
	public function getProfile($userId);
	/**
	 *
	 * @param $profile
	 * @param $userId
	 * @return void
	 */
	public function setProfile($profile, $userId = null);
}

class ToolboxVO_Profile_UserProfile {
	var $id;
	var $name;
	var $email;
	var $discloseEmail;
	var $avatarImage;
	var $timezone ;
}
?>

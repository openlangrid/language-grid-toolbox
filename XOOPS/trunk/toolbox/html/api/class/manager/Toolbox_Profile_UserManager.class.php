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

require_once(dirname(__FILE__).'/Toolbox_AbstractManager.class.php');
require_once(dirname(__FILE__).'/../handler/Profile_UsersHandler.class.php');

class Toolbox_Profile_UserManager extends Toolbox_AbstractManager {

	protected $handler = null;

	public function __construct() {
		parent::__construct();
		$this->handler = new Profile_UsersHandler($this->db);
	}

	public function getAllList() {
		$objects =& $this->handler->getObjects();

		$result = array();
		foreach ($objects as $object) {
//			$result[] = $this->UserObject2ResponseVO($object);
			$result[] = $object->get('uname');
		}
		return $this->getResponsePayload($result);
	}

	public function getCurrentUser() {
		$root =& XCube_Root::getSingleton();
		$uid = $root->mContext->mXoopsUser->get('uid');

		$object =& $this->handler->get($uid);
		if ($object) {
			return $this->getResponsePayload($object->get('uname'));
		} else {
			return $this->getErrorResponsePayload('No user');
		}
	}

	public function getUser($uid) {
//		$object =& $this->handler->get($uid);
		$object = $this->getUserByUname($uid);
		if ($object) {
			return $this->getResponsePayload($this->UserObject2ResponseVO($object));
		} else {
			return $this->getErrorResponsePayload('No user');
		}
	}

	public function updateUser($uid, $profile) {
//		$object =& $this->handler->get($uid);
		if($this->uid != 1){
			return $this->getErrorResponsePayload('Administrator only');
		}
		if(get_class($profile) != "ToolboxVO_Profile_UserProfile"){
			return $this->getErrorResponsePayload('profile object is invalid.');
		}
		if($uid == null){
			$object =& $this->handler->get($profile->id);
		}else{
			$object = $this->getUserByUname($uid);
		}
		if($object) {
			$object->set('name', $profile->name);
			$object->set('email', $profile->email);
			$object->set('user_viewemail', $profile->discloseEmail);
			$object->set('user_avatar', $profile->avatarImage);
			$object->set('timezone_offset', $profile->timezone);
			if(!$this->handler->insert($object, true)){
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			return $this->getResponsePayload(true);
		} else {
			return $this->getErrorResponsePayload('No user');
		}
	}

	protected function getUserByUname($uname) {
		$mc = new CriteriaCompo();
		$mc->add(new Criteria('uname', $uname));
		$obj =& $this->handler->getObjects($mc);
		if ($obj != null && count($obj) > 0) {
			return $obj[0];
		}
		return null;
	}

	protected function UserObject2ResponseVO($object) {
		$profile = new ToolboxVO_Profile_UserProfile();
		$profile->id = $object->get('uid');
		$profile->name = $object->get('uname');
		if ($object->get('name')) {
			$profile->name = $object->get('name');
		}
		$profile->email = $object->get('email');
		$profile->discloseEmail = $object->get('user_viewemail');
		$profile->timezone = $object->get('timezone_offset');
		return $profile;
	}
}
?>
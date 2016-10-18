<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// Q&As.
// Copyright (C) 2010  CITY OF KYOTO
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
require_once dirname(__FILE__).'/../factory/client-factory.php';

/**
 * @author kitajima
 */
class QaPermissionManager {

	private $root;
	private $resourceClient;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
		$this->resourceClient = $factory->createResourceClient();
		$this->root = XCube_Root::getSingleton();
	}
	
	/**
	 * 
	 * @param String $name
	 * @param String $readPermission
	 * @param String $editPermission
	 * @return void
	 */
	public function setPermission($name, $readPermission, $editPermission) {
		$this->resourceClient->setPermission(
			$name
			, $this->createToolboxVO($readPermission)
			, $this->createToolboxVO($editPermission)
		);
	}
	
	/**
	 * 
	 * @param String $name
	 * @return QaEnumPermission
	 */
	public function getMyPermission($name) {
		$result = $this->resourceClient->getLanguageResource($name);
		$resource = $result['contents'];
		return $this->getMyPermissionFromResource($resource);
	}
	
	public function getMyPermissionFromResource($resource) {
		$permission = QaEnumPermission::NOTHING;

		if ($this->isSu($resource)) {
			$permission = QaEnumPermission::SU;
		} else if ($this->isEditable($resource)) {
			$permission = QaEnumPermission::EDIT;
		} else if ($this->isReadable($resource)) {
			$permission = QaEnumPermission::READ;
		}
		return $permission;
	}
	
	/**
	 * 
	 * @param ToolboxVO_Resource_LanguageResource $resource
	 * @return bool
	 */
	private function isSu($resource) {
		return (
			($resource->creator == $this->root->mContext->mXoopsUser->get('uname'))
			|| ($this->root->mContext->mXoopsUser->get('uid') == 1)
		);
	}
	
	/**
	 * 
	 * @param ToolboxVO_Resource_LanguageResource $resource
	 * @return bool
	 */
	private function isEditable($resource) {
		return ($this->isPublic($resource->editPermission->type));
	}
	
	/**
	 * 
	 * @param ToolboxVO_Resource_LanguageResource $resource
	 * @return bool
	 */
	private function isReadable($resource) {
		return ($this->isPublic($resource->readPermission->type));
	}
	
	/**
	 * 
	 * @param String $type
	 * @return bool
	 */
	private function isPublic($type) {
		return (strtoupper($type) == 'PUBLIC');
	}
	
	/**
	 * 
	 * @param String $type
	 * @return bool
	 */
	private function isUser($type) {
		return (strtoupper($type) == 'USER');
	}
	
	/**
	 * 
	 * @param String $type
	 * @return ToolboxVO_Resource_Permission
	 */
	public function createToolboxVO($type) {
		$permission = new ToolboxVO_Resource_Permission();
		switch (strtoupper($type)) {
		case 'USER':
			$permission->type = 'USER';
			$permission->userId = $this->root->mContext->mXoopsUser->get('uid');
			break;
		default:
			$permission->type = 'PUBLIC';
			break;
		}
		return $permission;
	}
}

/**
 * 
 * @author kitajima
 *
 */
class QaEnumPermission {
	const NOTHING = 0;
	const READ = 1;
	const EDIT = 2;
	const SU = 3;
}
?>
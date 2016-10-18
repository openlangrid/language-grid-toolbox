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
require_once(dirname(__FILE__).'/../../IResourceClient.interface.php');
require_once(dirname(__FILE__).'/Toolbox_AbstractClient.class.php');

require_once(dirname(__FILE__).'/../manager/Toolbox_Resource_GetManager.class.php');
require_once(dirname(__FILE__).'/../manager/Toolbox_Resource_CreateEditManager.class.php');
/**
 * ResourceClient
 */
class ResourceClient extends Toolbox_AbstractClient implements IResourceClient {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 * @param $type
	 * @param $offset
	 * @param $limi
	 * @return arrayt
	 */
	public function getAllLanguageResources($type = null, $offset = null, $limit = null) {
		$manager = new Toolbox_Resource_GetManager();
		return $manager->getAllResources($type, $offset, $limit);
	}

	/**
	 *
	 * @param $name
	 * @return array
	 */
	public function getLanguageResource($name) {
		$manager = new Toolbox_Resource_GetManager();
		return $manager->getResource($name);
	}

	/**
	 *
	 * @param $word
	 * @param $matchingMethod
	 * @param $type
	 * @param $languages
	 * @param $offset
	 * @param $limit
	 * @return array
	 */
	public function searchLanguageResource($word, $matchingMethod, $type, $languages, $offset = null, $limit = null) {
		$manager = new Toolbox_Resource_GetManager();
		return $manager->search($word, $matchingMethod, $type, $languages, $offset, $limit);
	}

	/**
	 *
	 * @param $name
	 * @param $type
	 * @param $languages
	 * @param $readPermission
	 * @param $editPermission
	 * @return array
	 */
	public function createLanguageResource($name, $type, $languages, $readPermission, $editPermission) {
		$manager = new Toolbox_Resource_CreateEditManager();
		return $manager->createResource($type, $name, $languages, $readPermission, $editPermission);
	}

	/**
	 *
	 * @param $name
	 * @return boolean
	 */
	public function deleteLanguageResource($name) {
		$manager = new Toolbox_Resource_CreateEditManager();
		return $manager->deleteResource($name);
	}

	/**
	 *
	 * @param $name
	 * @param $language
	 * @return boolean
	 */
	public function addLanguage($name, $language) {
		$manager = new Toolbox_Resource_CreateEditManager();
		return $manager->addLanguage($name, $language);
	}

	/**
	 *
	 * @param $name
	 * @param $language
	 * @return boolean
	 */
	public function deleteLanguage($name, $language) {
		$manager = new Toolbox_Resource_CreateEditManager();
		return $manager->removeLanguage($name, $language);
	}

	/**
	 *
	 * @param $name
	 * @param $readPermission
	 * @param $editPermission
	 * @return boolean
	 */
	public function setPermission($name, $readPermission, $editPermission) {
		$manager = new Toolbox_Resource_CreateEditManager();
		return $manager->setPermission($name, $readPermission, $editPermission);
	}

	/**
	 *
	 * @param $name
	 * @param $serviceId
	 * @param $serviceName
	 * @return boolean
	 */
	public function deploy($name, $serviceId = null, $serviceName = null) {
		$manager = new Toolbox_Resource_CreateEditManager();
		return $manager->deployService($name);
	}

	/**
	 *
	 * @param $name
	 * @return void
	 */
	public function undeploy($name) {
		$manager = new Toolbox_Resource_CreateEditManager();
		return $manager->undeployService($name);
	}
}
?>

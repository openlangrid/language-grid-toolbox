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
require_once(dirname(__FILE__).'/../../IDictionaryClient.interface.php');
require_once(dirname(__FILE__).'/Toolbox_AbstractClient.class.php');
require_once(dirname(__FILE__).'/../manager/Toolbox_Resource_DictionaryReadManager.class.php');
require_once(dirname(__FILE__).'/../manager/Toolbox_Resource_DictionaryCreateEditManager.class.php');
require_once(dirname(__FILE__).'/ResourceClient.class.php');
/**
 * DictionaryClient
 */
class DictionaryClient extends Toolbox_AbstractClient implements IDictionaryClient {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 * @param $name
	 * @param $offset
	 * @param $limit
	 * @return array
	 */
	public function getAllRecords($name, $offset = null, $limit = null) {
		$manager =& new Toolbox_Resource_DictionaryReadManager();
		return $manager->getAllRecords($name, $offset, $limit);
	}

	/**
	 *
	 * @param $name
	 * @param $records
	 * @return boolean
	 */
	public function setRecords($name, $records) {
		$manager =& new Toolbox_Resource_DictionaryCreateEditManager();
		return $manager->setRecords($name, $records);
	}

	/**
	 *
	 * @param $name
	 * @param $expressions
	 * @return array
	 */
	public function addRecord($name, $expressions) {
		$manager =& new Toolbox_Resource_DictionaryCreateEditManager();
		return $manager->addRecord($name, $expressions);
	}

	/**
	 *
	 * @param $name
	 * @param $recordId
	 * @return boolean
	 */
	public function deleteRecord($name, $recordId) {
		$manager =& new Toolbox_Resource_DictionaryCreateEditManager();
		return $manager->deleteRecord($name, $recordId);
	}

	/**
	 *
	 * @param $name
	 * @param $recordId
	 * @param expressions
	 * @return boolean
	 */
	public function updateRecord($name, $recordId, $expressions, $priority = null) {
		$manager =& new Toolbox_Resource_DictionaryCreateEditManager();
		return $manager->updateRecord($name, $recordId, $expressions);
	}

	/**
	 *
	 * @param $name
	 * @param $recordId
	 * @param $priority
	 * @return boolean
	 */
	public function setRecordPriority($name, $recordId, $priority) {

	}

	/**
	 *
	 * @param $name
	 * @param $recordId
	 * @param $readPermission
	 * @param $editPermission
	 * @return boolean
	 */
	public function setRecordPermission($name, $recordId, $readPermission, $editPermission) {

	}

	/**
	 *
	 * @param $name
	 * @param $word
	 * @param $language
	 * @param $matchingMethod
	 * @param $offset
	 * @param $limit
	 * @return array
	 */
	public function searchRecord($name, $word, $language, $matchingMethod, $offset = null, $limit = null) {
		$manager =& new Toolbox_Resource_DictionaryReadManager();
		return $manager->searchRecords($name, $language, $word, $matchingMethod, $offset, $limit);
	}

	/**
	 *
	 * @param $name
	 * @param $serviceId
	 * @param $serviceName
	 * @return boolean
	 */
	public function deploy($name, $serviceId = null, $serviceName = null) {
		$resourceClient =& new ResourceClient();
		return $resourceClient->deploy($name, $serviceId, $serviceName);
	}

	/**
	 *
	 * @param $name
	 * @return void
	 */
	public function undeploy($name) {
		$resourceClient =& new ResourceClient();
		return $resourceClient->undeploy($name);
	}
}
?>

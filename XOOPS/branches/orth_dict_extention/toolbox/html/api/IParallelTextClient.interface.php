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
require_once(dirname(__FILE__).'/IResourceVO.interface.php');

/**
 * ParallelTextClient
 */
interface IParallelTextClient {

	/**
	 *
	 * @param $name
	 * @param $offset
	 * @param $limit
	 * @return array
	 */
	public function getAllRecords($name, $offset = null, $limit = null);

	/**
	 *
	 * @param $name
	 * @param $records
	 * @return boolean
	 */
	public function setRecords($name, $records);

	/**
	 *
	 * @param $name
	 * @param $expressions
	 * @return array
	 */
	public function addRecord($name, $expressions);

	/**
	 *
	 * @param $name
	 * @param $recordId
	 * @return boolean
	 */
	public function deleteRecord($name, $recordId);

	/**
	 *
	 * @param $name
	 * @param $recordId
	 * @param expressions
	 * @return boolean
	 */
	public function updateRecord($name, $recordId, $expressions, $priority);

	/**
	 *
	 * @param $name
	 * @param $recordId
	 * @param $priority
	 * @return boolean
	 */
	public function setRecordPriority($name, $recordId, $priority);

	/**
	 *
	 * @param $name
	 * @param $recordId
	 * @param $readPermission
	 * @param $editPermission
	 * @return boolean
	 */
	public function setRecordPermission($name, $recordId, $readPermission, $editPermission);

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
	public function searchRecord($name, $word, $language, $matchingMethod, $offset = null, $limit = null);
}
?>

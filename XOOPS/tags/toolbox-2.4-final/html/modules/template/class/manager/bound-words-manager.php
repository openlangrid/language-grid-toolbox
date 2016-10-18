<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
class BoundWordsManager {
	
	private $client;
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct() {
		$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
		$this->client = $factory->createModuleClient();
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @return unknown_type
	 */
	public function getAllBoundWordsByResourceName($name) {
		$setResult = $this->client->getAllBoundWordSets($name);
		
		$return = array();
		foreach ($setResult['contents'] as $set) {
			$id = $set->id;
			$wordsResult = $this->client->getAllBoundWords($name, $id);
			foreach ($wordsResult['contents'] as $word) {
				$return[] = $word;
			}
		}
		
		return $return;
	}
}
?>
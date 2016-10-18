<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// glossaries.
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
class QaResourceManager {

	private $resourceClient;
	
	public function __construct() {
		$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
		$this->resourceClient = $factory->createResourceClient();
	}
	
	/**
	 * 
	 * @param String $name
	 * @return bool
	 */
	public function isResourceExist($name) {
		$result = $this->resourceClient->getLanguageResource($name);
		return ($result['contents'] != null);
	}
	
	/**
	 * 
	 * @param String $name
	 * @param String[] $languages
	 * @return void
	 */
	public function setLanguages($name, $languages) {
		$result = $this->resourceClient->getLanguageResource($name);
		$resource = $result['contents'];
		
		foreach ($resource->languages as $language) { 
			if (!in_array($language, $languages)) {
				$this->resourceClient->deleteLanguage($name, $language);
			}
		}

		foreach ($languages as $language) { 
			if (!in_array($language, $resource->languages)) {
				$this->resourceClient->addLanguage($name, $language);
			}
		}
	}
}
?>
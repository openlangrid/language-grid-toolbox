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
/**
 * 
 * @author kinoshita
 *
 */
require_once(XOOPS_ROOT_PATH.'/api/class/client/ResourceClient.class.php');


class Reception {
	
	/**
	 * @var ToolboxVO_Resource_LanguageResource
	 */
	protected $model;
	
	/**
	 * returns a QAClient object.
	 * @return ResourceClient
	 */
	protected static function getResourceClient() {
		return new ResourceClient();
	}
	
	/**
	 * Constructor
	 * @param ToolboxVO_Resource_LanguageResource $record
	 * @return Reception
	 */
	public function __construct($record) {
		$this->model = $record;
	}
	
	/**
	 * returns a last update;
	 * @return int
	 */
	public function getLastUpdate() {
		return $this->model->lastUpdate;
	}

	/**
	 * returns a user friendly formatted date value of last update.
	 * @return string
	 */
	public function getLastUpdateString() {
		return CommonUtil::formatTimestamp($this->getLastUpdate(), STF_DATE_FORMAT);
	}
		
	/**
	 * returns a Name of QA. 
	 * @return string
	 */
	public function getName() {
		return $this->model->name;
	}
	
	/**
	 * returns creator.
	 * @return string
	 */
	public function getCreator() {
		return $this->model->creator;
	}
	
	/**
	 * returns array of resource languages
	 * @return array
	 */
	public function getLanguages() {
		return $this->model->languages;
	}
	
	/**
	 * translate languages in string.
	 * @return string
	 */
	public function getLanguageCollectionString() {
		return join(array_map("Reception::translateLanguageSymbol", $this->model->languages), ', ');
	}
	
	/**
	 * returns count of child entries.
	 * @return unknown_type
	 */
	public function getEntryCount() {
		return $this->model->entryCount;
	}
	
	private static $langHash;
	
	private static function translateLanguageSymbol($str) {
		if (!@$langHash) {
			$langHash = CommonUtil::getLanguageNameMap();
		}
		$longName = @$langHash[$str];
		return $longName ? $longName : $str;
	}
}

?>
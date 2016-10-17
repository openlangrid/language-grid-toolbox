<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
 * @author kitajima
 *
 */

require_once(dirname(__FILE__).'/bbs-edit-history-dao.php');

class BBSEditHistoryManager {
	private $dao;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->dao = new BBSEditHistoryDAO();
	}

	/**
	 * @return Object DAO
	 */
	protected function getDao(){
		return $this->dao;
	}

	/**
	 *
	 * @param $bbsId
	 * @param $bbsItemTypeCode
	 * @param $languageCode
	 * @return Array
	 */
	public function getModificationHistory($bbsId, $bbsItemTypeCode, $languageCode) {
		return $this->dao->getModificationHistory($bbsId, $bbsItemTypeCode, $languageCode);
	}

	/**
	 *
	 * @param $bbsItemTypeCode
	 * @param $bbsId
	 * @param $languageCode
	 * @param $processTypeCode
	 * @param $bbsText
	 * @param $userId
	 * @return Boolean
	 */
	public function registerModificationHistory($bbsId, $bbsItemTypeCode, $languageCode,
								$processTypeCode, $bbsText, $userId) {
		$return = $this->dao->registerModificationHistory($bbsId, $bbsItemTypeCode, $languageCode,
								$processTypeCode, $bbsText, $userId);
		return $return;
	}
}
?>
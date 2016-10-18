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
/**
 *
 * @author kitajima
 *
 */
class BBSEditHistoryModel {
	private $bbsId;
	private $bbsItemTypeCode;
	private $languageCode;
	private $historyCount;
	private $processTypeCode;
	private $bbsText;
	private $userId;
	private $userName;
	private $createDate;
	private $deleteFlag;

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * getter/setter
	 */
	public function getBBSId() {
		return $this->bbsId;
	}
	public function setBBSId($bbsId) {
		$this->bbsId = $bbsId;
	}
	public function getBBSItemTypeCode() {
		return $this->bbsItemTypeCode;
	}
	public function setBBSItemTypeCode($bbsItemTypeCode) {
		$this->bbsItemTypeCode = $bbsItemTypeCode;
	}
	public function getLanguageCode() {
		return $this->languageCode;
	}
	public function setLanguageCode($languageCode) {
		$this->languageCode = $languageCode;
	}
	public function getHistoryCount() {
		return $this->historyCount;
	}
	public function setHistoryCount($historyCount) {
		$this->historyCount = $historyCount;
	}
	public function getProcessTypeCode() {
		return $this->processTypeCode;
	}
	public function setProcessTypeCode($processTypeCode) {
		$this->processTypeCode = $processTypeCode;
	}
	public function getBBSText() {
		return $this->bbsText;
	}
	public function setBBSText($bbsText) {
		$this->bbsText = $bbsText;
	}
	public function getUserId() {
		return $this->userId;
	}
	public function setUserId($userId) {
		$this->userId = $userId;
	}
	public function getUserName() {
		return $this->userName;
	}
	public function setUserName($userName) {
		$this->userName = $userName;
	}
	public function getCreateDate() {
		return formatTimestamp($this->createDate, 'm');
	}
	public function setCreateDate($createDate) {
		$this->createDate = $createDate;
	}
	public function getDeleteFlag() {
		return $this->deleteFlag;
	}
	public function setDeleteFlag($deleteFlag) {
		$this->deleteFlag = $deleteFlag;
	}
}
?>
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
require_once dirname(__FILE__).'/language-entity.php';

/**
 *
 * @author kitajima
 *
 */
class UserEntity {
	private $id;
	private $groupId;
	private $name;
	private $fullName;
	private $language;
	private $timeZoneOffset;
	private $createTime;
	private $updateTime;

	/**
	 * Constructor
	 * @param int $id System ID
	 * @param String $name Login ID
	 * @param String $fullName Full Name
	 */
	public function __construct($id = 0, $name = '', $fullName = '') {
		$this->id = $id;
		$this->name = $name;
		$this->fullName = $fullName;
	}

	/**
	 * @return int User ID
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return void
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 *
	 * @return int
	 */
	public function getGroupId() {
		return $this->groupId;
	}

	/**
	 *
	 * @param int $groupId
	 * @return void
	 */
	public function setGroupId($groupId) {
		$this->groupId = $groupId;
	}

	/**
	 *
	 * @return String
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 *
	 * @param String $name
	 * @return String
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 *
	 * @return String
	 */
	public function getFullName() {
		return $this->fullName;
	}

	/**
	 *
	 * @param String $fullName
	 * @return void
	 */
	public function setFullName($fullName) {
		$this->fullName = $fullName;
	}

	/**
	 * @return String the Icon
	 */
	public function getIcon() {
		return $this->icon;
	}
	
	/**
	 * @param String Icon
	 * @return void
	 */
	public function setIcon($icon) {
		if ($icon == 'blank.gif') {
			$icon = XOOPS_URL.'/modules/user/images/no-image.jpg';
		} else {
			$icon = XOOPS_UPLOAD_URL.'/'.$icon;
		}
		$this->icon = $icon;
	}

	/**
	 *
	 * @return String
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 *
	 * @param String $language
	 * @return void
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}

	/**
	 *
	 * @return int
	 */
	public function getTimeZoneOffset() {
		return $this->timeZoneOffset;
	}

	/**
	 *
	 * @param int $timeZoneOffset
	 * @return void
	 */
	public function setTimeZoneOffset($timeZoneOffset) {
		$this->timeZoneOffset = $timeZoneOffset;
	}

	/**
	 *
	 * @return int
	 */
	public function getCreateTime() {
		return $this->createTime;
	}

	/**
	 *
	 * @param int $createTime
	 * @return void
	 */
	public function setCreateTime($createTime) {
		$this->createTime = $createTime;
	}

	/**
	 * @return int
	 */
	public function getUpdateTime() {
		return $this->updateTime;
	}

	/**
	 *
	 * @param int $updateTime
	 * @return void
	 */
	public function setUpdateTime($updateTime) {
		$this->updateTime = $updateTime;
	}

	/**
	 * @return Array
	 */
	public function toArray() {
		return array(
			'id' => $this->id
			, 'name' => $this->name
			, 'fullName' => $this->fullName
			, 'icon' => $this->icon
		);
	}
}
?>
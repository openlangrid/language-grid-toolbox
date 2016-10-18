<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
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
abstract class AbstractModel {

	protected $languageManager;
	protected $lastPostTime;

	protected function __construct() {
		$this->languageManager = new LanguageManager();
		$this->root = XCube_Root::getSingleton();
		$this->db = Database::getInstance();
		$this->moduleName = basename(realpath(dirname(__FILE__).'/../../'));
	}

	public function hasNewPost() {
		if (!$this->getLastPostTime()) {
			return false;
		}
		return (time() - $this->getLastPostTime()) < 24 * 60 * 60;
	}
	/**
	 * getter/setter
	 */
	public function getOriginalLanguage() {
		return $this->language;
	}
	public function setOriginalLanguage($language) {
		$this->language = $language;
	}
	public function getCreateTime() {
		return $this->createTime;
	}
	public function setCreateTime($createTime) {
		$this->createTime = $createTime;
	}
	public function getLastPostTime() {
		return $this->lastPostTime;
	}
	public function setLastPostTime($lastPostTime) {
		$this->lastPostTime = $lastPostTime;
	}
}
?>
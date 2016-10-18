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
class CompositeEntity {

	private $parent;
	private $child;

	private $header;
	private $body;
	private $createTime;
	private $updateTime;

	public function getParent() {
		return $this->parent;
	}
	public function setParent($parent) {
		$this->parent = $parent;
	}

	public function getChild() {
		return $this->child;
	}
	public function setChild($child) {
		$this->child = $child;
	}

	public function getHeader() {
		return $this->header;
	}
	public function setHeader($header) {
		$this->header = $header;
	}
	public function getBody() {
		return $this->body;
	}
	public function setBody($body) {
		$this->body = $body;
	}
	public function getCreateTime() {
		return $this->createTime;
	}
	public function setCreateTime($createTime) {
		$this->createTime = $createTime;
	}
	public function getUpdateTime() {
		return $this->updateTime;
	}
	public function setUpdateTime($updateTime) {
		$this->updateTime = $updateTime;
	}
}
?>
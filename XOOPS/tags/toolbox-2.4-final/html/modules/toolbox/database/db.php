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
class DB extends PDO {

	private $tablePrefix = '';

	/**
	 * Constructor
	 * @param $dsn
	 * @param $username
	 * @param $password
	 */
	public function __construct($dsn, $username, $password) {
		parent::__construct($dsn, $username, $password);
	}

	/**
	 *
	 * @return String Table prefix
	 */
	public function getTablePrefix() {
		return $this->tablePrefix;
	}

	/**
	 *
	 * @param String $tablePrefix
	 * @return void
	 */
	public function setTablePrefix($tablePrefix) {
		$this->tablePrefix = $tablePrefix;
	}

	/**
	 *
	 * @param String $string
	 * @return String
	 */
	public function prefix($string) {
		return $this->tablePrefix.$string;
	}
}
?>
<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

require_once APP_ROOT_PATH.'/class/handler/WebCreationDisplayCacheObject.class.php';

class DisplayCacheService {

	private $handler;

	public function __construct() {
		$this->db = Database::getInstance();
		$this->handler = new WebCreationDisplayCacheHandler($this->db);
	}

	public function getContents($key) {
		$o = $this->handler->get($key);

		if (!$o) {
			throw new Exception();
		}

		return $o->get('contents');
	}

	public function deleteOldContents() {
		$c = new CriteriaCompo();
		$c->add(new Criteria('creation_time', time() - 120, '<'));

		$this->handler->deleteAll($c, true);
	}

	/**
	 *
	 * @param String $contents
	 * @return String key
	 */
	public function registerContents($contents) {
		$key = $this->generateKey($contents, 0);

		$o = $this->handler->create(true);
		$o->set('display_key', $key);
		$o->set('contents', $contents);
		$o->set('creation_time', time());

		if (!$this->handler->insert($o, true)) {
			throw new Exception(mysql_error());
		}

		return $key;
	}

	private function isKeyExists($key) {
		$c = new CriteriaCompo();
		$c->add(new Criteria('display_key', $key));

		return $this->handler->getCount($c) > 0;
	}

	private function generateKey($contents, $count) {
		if ($count > 10) {
			throw new Exception();
		}

		$key = md5(rand().$contents.rand().time());

		if ($this->isKeyExists($key)) {
			return $this->generateKey($key, ++$count);
		}

		return $key;
	}
}
?>
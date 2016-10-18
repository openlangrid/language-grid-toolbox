<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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

class DisplayCacheManagerClass {

	private $db = null;
	private $TBL = null;

	function __construct() {
		global $xoopsDB;
		$this->db = $xoopsDB;
		$mydirname = basename(realpath(dirname(__FILE__)."/../../"));
		$this->TBL = $xoopsDB->prefix($mydirname.'_display_cache');
	}

	function getCacheContents($displayKey) {
		$_sql = '';
		$_sql .= 'SELECT `contents` FROM '.$this->TBL.' WHERE `display_key` = \'%s\'';

		$sql = sprintf($_sql, $this->mysqlescape($displayKey));

		$res = false;

		if ($rs = $this->db->query($sql)) {
			if ($row = $this->db->fetchArray($rs)) {
				$res = $row['contents'];
			}
		}

		$this->clearCache();

		return $res;
	}

	function setCacheContents($htmlText, $userId) {
		$_sql = '';
		$_sql .= 'INSERT INTO '.$this->TBL.' (`display_key`, `contents`, `user_id`, `create_time`)';
		$_sql .= ' VALUES (\'%s\', \'%s\', %d, %d)';

		$key = $this->createKey($htmlText, $userId);
		$sql = sprintf($_sql,
			$this->mysqlescape($key),
			$this->mysqlescape($htmlText),
			$this->mysqlescape($userId),
			$this->mysqlescape(time()));

		if($this->db->queryf($sql)) {
			return $key;
		} else {
			return false;
		}
	}

	function clearCache() {
		$_sql = '';
		$_sql = 'DELETE FROM '.$this->TBL.' WHERE `create_time` < %d';

		// TODO:すぐに消してもいいとおもうのだが、、、残しておいても使い道ないし、、、
		$yesterday = time() - (60 * 60 * 24);
		$sql = sprintf($_sql, $yesterday);

		return $this->db->queryf($sql);
	}

	private function createKey($htmlText, $userId) {
		// ハッシュの種は重複しなければなんでもいいので、以下のようにユーザIDと日付を加えておく。
		$base = '';
		$base .= 'DISPLAYUSERID='.$userId;
		$base .= 'DISPLAYTIME='.time();
		$base .= 'DISPLAY='.$htmlText;

		$md5 = md5($base);
		return $md5;
	}

	private function mysqlescape($str) {
		if ( get_magic_quotes_gpc() ) {
			$str = stripslashes( $str );
		}
		return mysql_real_escape_string($str);
	}

}
?>
<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

require_once dirname(__FILE__).'/util/user.php';

class Com_ActiveUser {
	private $record;
	private $user;
	
	private function __construct($record) {
		$this -> record = $record;
		$this -> user = new User($record['uid']);
	}
	
	public function getUser() {
		return $this -> user;
	}
	
	public function getId() {
		return $this -> getUser() -> getId();
	}
	
	public function getName() {
		return $this -> getUser() -> getName();
	}
	
	public function getIcon() {
		return $this -> getUser() -> getIcon();
	}
	
	static protected function actualTableName($baseName) {
		$xoopsDB =& Database::getInstance();
		return $xoopsDB->prefix."_".$GLOBALS['mydirname']."_".$baseName;
	}
	
	static public function updateLastAccessByLoginUser($topicId) {
		$userAccessTable = self::actualTableName('user_access');
		$xoopsDB =& Database::getInstance();
		$sql = "
			DELETE FROM
				{$userAccessTable}
			WHERE
				uid = '%s'
		";
		$sql = sprintf($sql, getLoginUserUID());
		$result = $xoopsDB->queryF($sql);
		$sql  = "
			INSERT INTO {$userAccessTable}
				(uid, access_time, function_distinction, topic_id)
			VALUES
				(%s, SYSDATE(), '1', %s)
		";

		$sql = sprintf($sql, getLoginUserUID(), $xoopsDB -> quoteString($topicId));
		$result = $xoopsDB->queryF($sql);
	}
	
	static public function findAllByTopicId($topicId) {
		
		$xoopsDB =& Database::getInstance();
		$userAccessTable = self::actualTableName('user_access');
		$usersTable = $xoopsDB->prefix('users');
		
		// select active user
		$sql = "
			SELECT T2.uid AS uid,T2.uname AS uname,T2.user_avatar AS user_avatar,T1.access_time AS access_time
			FROM
				{$userAccessTable} T1
				INNER JOIN {$usersTable} T2
					ON T1.uid = T2.uid
			WHERE
				date_add(access_time, interval 1 minute ) > SYSDATE()
				AND
				function_distinction = 1 AND topic_id = %s
			ORDER BY access_time DESC
		";
		$sql = sprintf($sql, $xoopsDB -> quoteString($topicId));
		
		$results = array();

		$response = $xoopsDB->query($sql);
		if ($response) {
			while($row = $xoopsDB->fetchArray($response)){
			    $au = new Com_ActiveUser($row);
			    array_push($results, $au);
			}
		}
		return $results;
	}
}
?>
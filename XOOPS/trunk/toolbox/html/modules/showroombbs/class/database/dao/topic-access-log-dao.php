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

Toolbox::import('toolbox.exception.SQLException');
Toolbox::import('toolbox.database.dao.GenericDAO');
Toolbox::import('toolbox.entity.UserEntity');

global $xoopsModuleConfig;
if(isset($xoopsModuleConfig['topicAccessLogSaveMaxTime'])){
	define('MAX_LIMIT_SECONDS', $xoopsModuleConfig['topicAccessLogSaveMaxTime']);
}else{
	define('MAX_LIMIT_SECONDS', 0);
}
/**
 *
 * @author kitajima
 *
 */
class TopicAccessLogDAO extends GenericDAO {

	private $topicId;

	/**
	 * Constructor
	 * @param $db DB connection
	 * @param $topicId Topic ID
	 * @param $userId User ID
	 */
	public function __construct($db, $topicId = 0, $userId = 0) {
		parent::__construct($db);
		$this->setTable(USE_TABLE_PREFIX.'_topic_access_log');
		$this->setTopicId($topicId);
		$this->setUserId($userId);
	}

	/**
	 * Facade
	 * @throws SqlException
	 * @return void
	 */
	public function doLogging() {
		$this->refresh();
		$this->deleteLog();
		$this->createLog();
	}

	/**
	 * @throws SqlException
	 * @return UserEntity[] user
	 */
	public function getOnlineUsers() {
		$sql  = ' SELECT `user_id`, `name`, `full_name`, `user_avatar`,`topic_id`, `last_access_time` ';
		$sql .= ' FROM %s AS T1 ';
		$sql .= ' 	LEFT JOIN (SELECT `user_avatar`, `uid` AS `user_id`, `name` AS `full_name`, `uname` AS `name` FROM %s)';
		$sql .= '		AS T2 USING (`user_id`) ';
		$sql .= ' WHERE	(`topic_id` = %d) AND (`last_access_time` > %d)';
		$sql = sprintf($sql, $this->table, $this->db->prefix('users'), $this->topicId, time() - MAX_LIMIT_SECONDS);
		$sth = $this->db->query($sql);
		if (!$sth) {
			throw new SQLException($sql);
		}
		$users = array();
		while ($row = $sth->fetch()) {
			$user = new UserEntity();
			$user->setId($row['user_id']);
			$user->setName($row['name']);
			$user->setFullName($row['full_name']);
			$user->setIcon($row['user_avatar']);
			$users[] = $user;
		}
		return $users;
	}

	/**
	 * @throws SqlException
	 * @return void
	 */
	private function createLog() {
		$sql  = ' INSERT INTO `%s` (topic_id, user_id, last_access_time) ';
		$sql .= ' VALUES (%d, %d, %d)';
		$sql = sprintf($sql, $this->table, $this->topicId, $this->userId, time());
		$result = $this->db->query($sql);
		if (!$result) {
			throw new SqlException($sql);
		}
	}

	/**
	 * @throws SqlException
	 * @return void
	 */
	private function deleteLog() {
		$sql  = ' DELETE FROM `%s` WHERE (`user_id` = %d && `topic_id` = %d) ';
		$sql = sprintf($sql, $this->table, $this->userId, $this->topicId);
		$result = $this->db->query($sql);
		if (!$result) {
			throw new SqlException($sql);
		}
	}

	/**
	 * @throws SqlException
	 * @return void
	 */
	private function refresh() {
		$sql  = ' DELETE FROM `%s` WHERE (`last_access_time` < %d) ';
		$sql = sprintf($sql, $this->table, time() - MAX_LIMIT_SECONDS);
		$result = $this->db->query($sql);
		if (!$result) {
			throw new SqlException($sql);
		}
	}

	/**
	 * @return int Topic ID
	 */
	public function getTopicId() {
		return $this->topicId;
	}

	/**
	 * @param int $topicId
	 * @return void
	 */
	public function setTopicId($topicId) {
		$this->topicId = $topicId;
	}

	/**
	 *
	 * @return int User ID
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 *
	 * @param int $userId
	 * @return void
	 */
	public function setUserId($userId) {
		$this->userId = $userId;
	}
}
?>
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
require_once dirname(__FILE__).'/generic_dao.php';
require_once dirname(__FILE__).'/../../entity/user_entity.php';
require_once dirname(__FILE__).'/../../exception/sql_exception.php';

/**
 *
 * @author kitajima
 *
 */
class TopicAccessLogDAO extends GenericDao {

	private $topicId;
	private static $table;

	const MAX_LIMIT_MINUTES = 5;

	/**
	 * Constructor
	 * @param $db DB connection
	 * @param $topicId Topic ID
	 * @param $userId User ID
	 */
	public function __construct($db, $topicId, $userId) {
		parent::__construct($db);
		$this->setTable('topic_access_log');
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
		$sql  = ' SELECT `id`, `user_id`, `topic_id`, `last_access_time` ';
		$sql .= ' FROM %s AS T1 ';
		$sql .= ' 	LEFT JOIN (SELECT uid AS user_id, uname AS user_name FROM %s)';
		$sql .= '		AS T2 USING (user_id) ';
		$sql .= ' WHERE	(`topic_id` = %d) AND (`last_access_time` > %d)';
		$sql = sprintf($sql, $this->table, $this->db->prefix('users'), $this->topicid, time() - MAX_LIMIT_MINUTES * 60);
		if (!$result) {
			throw new SqlException($sql);
		}
		$users = array();
		while ($row = $this->db->fetchArray($result)) {
			$users[] = new UserEntity($row['user_id'], $row['user_name']);
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
		$sql = sprintf($sql, self::table, $this->userId, $this->topicId, time());
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
		$sql = sprintf($sql, self::table, $this->userId, $this->topicId);
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
		$sql = sprintf($sql, self::table, time() - MAX_LIMIT_MINUTES * 60);
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
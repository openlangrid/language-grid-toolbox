<?php

require_once dirname(__FILE__).'/../user-dao.php';
require_once dirname(__FILE__).'/../../../entity/user-entity.php';

class XOOPSUserDAO extends UserDAO {

	/**
	 *
	 * @param unknown_type $db
	 * @return unknown_type
	 */
	public function __construct($db) {
		parent::__construct($db);
		$this->setTable('users');
	}

	/**
	 * (non-PHPdoc)
	 * @see html/modules/toolbox/database/dao/UserDAO#getAllUsers()
	 */
	public function getAllUsers() {
		$sql  = ' SELECT `uid` `user_id`, `uname` `user_name`, `timezone_offset` ';
		$sql .= ' FROM %s ';
		$sql = sprintf($sql, $this->table);

		$rows = $this->db->query($sql);

		$users = array();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				$users[] = $this->buildUser($row);
			}
		}

		return $users;
	}

	/**
	 *
	 * @param unknown_type $row
	 * @return unknown_type
	 */
	private function buildUser($row) {
		$user = new UserEntity();
		$user->setId($row['user_id']);
		$user->setName($row['user_name']);
		return $user;
	}
}
?>
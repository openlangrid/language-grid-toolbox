<?php
require_once dirname(__FILE__).'/generic-dao.php';

abstract class UserDAO extends GenericDAO {
	/**
	 *
	 * @return UserEntity[] users
	 */
	public abstract function getAllUsers();
}
?>
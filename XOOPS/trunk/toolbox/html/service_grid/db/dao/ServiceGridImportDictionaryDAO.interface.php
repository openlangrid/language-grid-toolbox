<?php
interface ServiceGridImportDictionaryDAO{
	public function queryById($id);
	public function queryByUserDictionaryId($userDictionaryId);
	public function insert($userDictionaryId, $bindType, $bindValue);
	public function delete($id);
}
?>
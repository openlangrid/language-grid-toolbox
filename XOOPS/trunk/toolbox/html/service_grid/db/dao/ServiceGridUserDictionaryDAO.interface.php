<?php

interface ServiceGridUserDictionaryDAO {
	public function insert($object);
	
	public function update($object);
	
	public function delete($object);
	
	public function getUserDictionaryIdByName($dictName);
}
?>
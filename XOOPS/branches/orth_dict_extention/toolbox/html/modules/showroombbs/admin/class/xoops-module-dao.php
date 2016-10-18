<?php
class XoopsModuleDAO {
	
	private $db;
	
	public function __construct() {
		$this->db =& Database::getInstance();
	}

	public function isModuleExists($moduleName) {
		$modules = $this->db->prefix('modules');
		$sql = 'SELECT COUNT(mid) CNT FROM `'.$modules.'` WHERE dirname=\'%s\'';
		$sql = sprintf($sql, $moduleName);
		
		$result = $this->db->queryF($sql);
		if (!$result) {
			throw new Exception('ERROR:'.$sql);
		}
		
		$row = $this->db->fetchArray($result);
		if (!$row) {
			throw new Exception('ERROR');
		}

		return ($row['CNT'] > 0);
	}
	
	public function deleteTableData($table) {
		$table = $this->db->prefix($table);

		$sql = 'TRUNCATE TABLE '.$table;
		
		if (!$this->db->queryF($sql)) {
			throw new Exception('ERROR:'.$sql);
		}
	}
	
	public function cloneTableData($sourceTable, $targetTable, $columns) {
		$from = $this->db->prefix($sourceTable);
		$to = $this->db->prefix($targetTable);
		
		$this->deleteTableData($targetTable);
		
		$sql = 'INSERT into '.$to.'('.implode(', ', $columns).') SELECT '.implode(', ', $columns).' FROM '.$from;
		if (!$this->db->queryF($sql)) {
			throw new Exception('ERROR:'.$sql);
		}
	}
}
?>
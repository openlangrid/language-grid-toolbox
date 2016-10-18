<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once dirname(__FILE__).'/pager.php';
require_once dirname(__FILE__).'/file.php';

class FileList extends Pager {
	
	private $sum;
	
	public function getSum() {
		if(!$sum) {
			$sum = count(self::findAll(array(), 0, -1) -> getFiles());
		}
		return $sum;
	}
	
	public function getFiles() {
		return $this -> getEntities();
	}
	
	static public function getFilesForPage($page = 1) {
		return self::findAll(array(), ($page - 1) * self::DEFALT_LIMIT, self::DEFALT_LIMIT);
	}
	
	static public function findAll($options = array(), $offset = 0, $limit = self::DEFALT_LIMIT) {
		if($limit > 0)
			$options['limit'] = $limit;
		if($offset > 0)	
			$options['offset'] = $offset;
		return new FileList(File::findAll($options),$offset,  $limit);
	}
}
?>

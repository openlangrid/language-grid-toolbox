<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //


require_once dirname(__FILE__).'/work_document.php';
require_once dirname(__FILE__).'/pager.php';

class WorkDocumentList extends Pager {
	
	public function getSum() {
		return WorkDocument::countAll();
	}
	
	public function getDocuments() {
		return $this -> getEntities();
	}
	
	static public function getDocumentsForPage($page = 1) {
		return self::findAll(array(
			"where" => array()
		), ($page - 1) * self::DEFALT_LIMIT, self::DEFALT_LIMIT);
	}
	
	static public function findAll($options, $offset = 0, $limit = self::DEFALT_LIMIT) {
		if($offset > 0)	$options['offset'] = $offset;
		$options['limit'] = $limit;
		return new WorkDocumentList(WorkDocument::findAll($options), $offset, $limit);
	}
}
?>

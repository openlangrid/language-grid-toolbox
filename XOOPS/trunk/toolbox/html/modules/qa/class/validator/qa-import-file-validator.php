<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// Q&As.
// Copyright (C) 2010  CITY OF KYOTO
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

require_once dirname(__FILE__).'/../manager/qa-resource-manager.php';

/**
 * @author kitajima
 */
class QaImportFileValidator {
	
	private $phpExcel;
	private $message;
	
	/**
	 * Constructor
	 * @param unknown_type $phpExcel
	 */
	public function __construct($phpExcel) {
		$this->phpExcel = $phpExcel;
	}

	/**
	 * 
	 * @return bool
	 */
	public function valid($name, $languages, $file) {
		$this->message = '';

		$resourceManager = new QaResourceManager();
		if ($resourceManager->isResourceExist($name)) {
			throw new Exception(_MI_QA_ERROR_RESOURCE_NAME_ALREADY_IN_USE);
		}
		
		if ($this->isFileValid($file)) {
			$this->message = _MI_QA_ERROR_INVALID_FILE_FORMAT;
			return false;
		}
		
		if ($this->isFirstRowValid()) {
			$this->message = _MI_QA_ERROR_INVALID_FILE_FORMAT;
			return false;
		}
		
		if ($this->isLanguagesValid()) {
			$this->message = _MI_QA_ERROR_INVALID_FILE_FORMAT;
			return false;
		}
		return true;
	}
	
	/**
	 * 
	 * @param FILE $file
	 * @return bool
	 */
	private function isFileValid($file) {
		if ($file['error'] != 0 || $file['size'] == 0) {
			return false;
		}
		return true;
	}
	
	/**
	 * 
	 * @return bool
	 */
	private function isFirstRowValid() {
		if ($this->phpExcel->getActiveSheet()->getCell('A1')->getValue() != 'id') {
			return false;
		}
		if ($this->phpExcel->getActiveSheet()->getCell('B1')->getValue() != 'type') {
			return false;
		}
		if ($this->phpExcel->getActiveSheet()->getCell('C1')->getValue() != 'answers') {
			return false;
		}
		if ($this->phpExcel->getActiveSheet()->getCell('D1')->getValue() != 'categories') {
			return false;
		}
		return true;
	}

	private function isLanguagesValid() {
		return (count(getLanguages($this->phpExcel)) >= 2);
	}

	/**
	 * 
	 * @return String
	 */
	public function getMessage() {
		return $this->message;
	}
}
?>
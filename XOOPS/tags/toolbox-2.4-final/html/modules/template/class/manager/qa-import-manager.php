<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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

require_once dirname(__FILE__).'/../util/qa-excel-util.php';
require_once dirname(__FILE__).'/../validator/qa-import-file-validator.php';

require_once dirname(__FILE__).'/../lib/php-excel-1.7.1/Classes/PHPExcel.php';
require_once dirname(__FILE__).'/../lib/php-excel-1.7.1/Classes/PHPExcel/IOFactory.php';

/**
 * 
 * @author kitajima
 *
 */
class QaImportManager {
	
	private $file;
	private $phpExcel;

	/**
	 * Constructor
	 * @param String $name
	 * @param FILE $file
	 */
	public function __construct($name, $file) {
		$this->name = $name;
		$this->file = $file;
	}

	/**
	 * @throws Exception
	 * @return unknown_type
	 */
	public function import() {
		$this->phpExcel = PHPExcel_IOFactory::load($this->file['tmp_name']);
		$validator = new QaImportFileValidator();
		if (!$validator->valid()) {
			throw new Exception($validator->getMessage());
		}
	}
	
	private function addCategory() {
		
	}
	
	private function addRecord() {
		
	}
	
	/**
	 * 
	 * @return array
	 */
	private function buildCells() {
		$cells = array();
		$languages = getLanguages($objPHPExcel);
		for ($i = 2; ;$i++) {
			$id = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
			if ($id == '') {
				break;
			}
			if (isset($cells[$id])) {
				throw new Exception();
			}
			$cells[$id] = array(
				'id' => $id,
				'type' => $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue(),
				'answers' => preg_split('/, ?/', $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue()),
				'categories' => preg_split('/, ?/', $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue())
			);
			foreach ($languages as $key => $language) {
				$cells[$id][$language] = $objPHPExcel->getActiveSheet()->getCell($key.$i)->getValue();
			}
		}
		return $cells;
	}
	
	/**
	 * 
	 * @return array<String, String>
	 */
	private function getLanguages() {
		$languages = array();
		require XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';
		$columns = QaExcelUtil::getColumnExpressions();
		for ($i = 4; ; $i++) {
			$language = $objPHPExcel->getActiveSheet()->getCell(chr($columns[$i]).'1')->getValue();
			if ($language == '') {
				break;
			}
			if (array_key_exists($language, $LANGRID_LANGUAGE_ARRAY)) {
				$languages[chr($columns[$i])] = $language;
			}
		}
		return $languages;
	}
}
?>
<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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

class Toolbox_TranslationTemplateSorter {
	
	private $field;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
	}
	
	/**
	 * 
	 * @param unknown_type $arr
	 * @param unknown_type $sortOrder
	 * @param unknown_type $orderBy
	 * @return unknown_type
	 */
	public function sort(&$arr, $sortOrder, $orderBy) {
		switch (strtoupper($orderBy)) {
		case 'ID':
			$this->sortByField(&$arr, 'id', $sortOrder);
			break;
		case 'CREATIONDATE':
			$this->sortByField(&$arr, 'creationDate', $sortOrder);
			break;
		case 'UPDATEDATE':
			$this->sortByField(&$arr, 'updateDate', $sortOrder);
			break;
		default:
			$this->sortByExpression(&$arr, $orderBy, $sortOrder);
			break;
		}
	}
	
	/**
	 * 
	 * @param unknown_type $arr
	 * @param unknown_type $orderBy
	 * @param unknown_type $sortOrder
	 * @return unknown_type
	 */
	public function sortByExpression(&$arr, $orderBy, $sortOrder) {
		$this->language = $orderBy;
		usort(&$arr, array($this, 'sortByExpression'.$this->getSortOrder($sortOrder)));
	}
	
	/**
	 * 
	 * @param unknown_type $a
	 * @param unknown_type $b
	 * @return unknown_type
	 */
	public function sortByExpressionAsc($a, $b) {
		$a = $this->getExpressionByLanguage($a->expressions, $this->language);
		$b = $this->getExpressionByLanguage($b->expressions, $this->language);
		
		if ($a && $b) {
			if ($a == $b) {
				return 0;
			}
			
			return ($a > $b) ? 1 : -1;
		} else if ($a) {
			return -1;
		} else if ($b) {
			return 1;
		} else {
			return 0;
		}
	}
	
	/**
	 * 
	 * @param unknown_type $expressions
	 * @param String $language
	 * @return String
	 */
	private function getExpressionByLanguage($expressions, $language) {
		foreach ($expressions as $exp) {
			if ($exp->language == $language) {
				return $exp->expression;
			}
		}
		
		return null;
	}
	
	/**
	 * 
	 * @param unknown_type $b
	 * @param unknown_type $a
	 * @return unknown_type
	 */
	public function sortByExpressionDesc($b, $a) {
		return $this->sortByExpressionAsc($b, $a);
	}
	
	/**
	 * 
	 * @param unknown_type $arr
	 * @param unknown_type $field
	 * @param unknown_type $sortOrder
	 * @return unknown_type
	 */
	public function sortByField(&$arr, $field, $sortOrder) {
		$this->field = $field;
		usort($arr, array($this, 'sortByField'.$this->getSortOrder($sortOrder)));
	}

	/**
	 * 
	 * @param unknown_type $a
	 * @param unknown_type $b
	 * @return unknown_type
	 */
	public function sortByFieldAsc($a, $b) {
		$a = $a->{$this->field};
		$b = $b->{$this->field};
		
		if ($a == $b) {
			return 0;
		}
		
		return ($a > $b) ? 1 : -1;
	}
	
	/**
	 * 
	 * @param unknown_type $a
	 * @param unknown_type $b
	 * @return unknown_type
	 */
	public function sortByFieldDesc($a, $b) {
		return $this->sortByFieldAsc($b, $a);
	}
	
	/**
	 * 
	 * @param unknown_type $sortOrder
	 * @return unknown_type
	 */
	private function getSortOrder($sortOrder) {
		return (strtoupper($sortOrder) == 'DESC') ? 'Desc' : 'Asc';
	}
}
?>
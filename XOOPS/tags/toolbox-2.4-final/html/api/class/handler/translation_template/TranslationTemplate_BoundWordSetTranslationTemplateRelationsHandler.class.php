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

require_once(dirname(__FILE__).'/../Toolbox_CompositeKeyGenericHandler.class.php');

class TranslationTemplate_BoundWordSetTranslationTemplateRelationsObject extends XoopsSimpleObject {

	public function TranslationTemplate_BoundWordSetTranslationTemplateRelationsObject() {
		$this->initVar('bound_word_set_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('translation_template_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('index', XOBJ_DTYPE_INT, 0, false);
	}
}

class TranslationTemplate_BoundWordSetTranslationTemplateRelationsHandler extends Toolbox_CompositeKeyGenericHandler {

	public $mTable = "template_bound_word_set_translation_template_relations";
	public $mPrimary = "bound_word_set_id";
	public $mClass = "TranslationTemplate_BoundWordSetTranslationTemplateRelationsObject";
	public $mPrimaryAry = array('bound_word_set_id', 'translation_template_id', 'index');

	/**
	 * @param $criteria CriteriaElement
	 * @param $obj XoopsSimpleObject
	 */	
	function _makeCriteriaElement4sql($criteria, &$obj)
	{
		if (is_a($criteria, "CriteriaElement")) {
			if ($criteria->hasChildElements()) {
				$queryString = "";
				$maxCount = $criteria->getCountChildElements();
				
	            $queryString = '('. $this->_makeCriteria4sql($criteria->getChildElement(0));
	            for ($i = 1; $i < $maxCount; $i++) {
					$queryString .= " " . $criteria->getCondition($i) . " " . $this->_makeCriteria4sql($criteria->getChildElement($i));
	            }
	            $queryString .= ')';
	            
	            return $queryString;
			}
			else {
				//
				// Render
				//
				$name = $criteria->getName();
				$value = $criteria->getValue();
				if ($name != null && isset($obj->mVars[$name])) {
					if ($value === null) {
						$criteria->operator = $criteria->getOperator() == '=' ? "IS" : "IS NOT";
						$value = "NULL";
					}
					else {
						switch ($obj->mVars[$name]['data_type']) {
							case XOBJ_DTYPE_BOOL:
								$value = $value ? "1" : "0";
								break;
							
							case XOBJ_DTYPE_INT:
								$value = intval($value);
								break;
						
							case XOBJ_DTYPE_FLOAT:
								$value = floatval($value);
								break;
						
							case XOBJ_DTYPE_STRING:
							case XOBJ_DTYPE_TEXT:
								$value = $this->db->quoteString($value);
								break;
								
							default:
								$value = $this->db->quoteString($value);
						}
					}
				} else {
				    $value = $this->db->quoteString($value);
				}

				if ($name != null) {
					return '`'.$name . "` " . $criteria->getOperator() . " " . $value;
				}
				else {
					return null;
				}
			}
		}
	}
}
?>
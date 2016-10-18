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

require_once(dirname(__FILE__).'/Toolbox_CompositeKeyGenericHandler.class.php');

class Glossary_CategoryTermRelationsObject extends XoopsSimpleObject {

	function Glossary_CategoryTermRelationsObject() {
		$this->initVar('glossary_category_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('glossary_term_id', XOBJ_DTYPE_INT, 0, false);
	}
}

class Glossary_CategoryTermRelationsHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = "glossary_category_term_relations";
	var $mPrimary = "glossary_term_id";
	var $mClass = "Glossary_CategoryTermRelationsObject";
	var $mPrimaryAry = array('glossary_category_id', 'glossary_term_id');
	
	public function deleteByCategoryId($categoryId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add('glossary_category_id', $categoryId);
		if (!$this->deleteAll($criteriaCompo, true)) {
			return false;
		}
		return true;
		
	}
	
	public function deleteByTermId($termId) {
		return $this->deleteByQuestionId($termId);
	}
	
	public function deleteByQuestionId($questionId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('glossary_term_id', $questionId));
		if (!$this->deleteAll($criteriaCompo, true)) {
			return false;
		}
		return true;
	}
}
?>
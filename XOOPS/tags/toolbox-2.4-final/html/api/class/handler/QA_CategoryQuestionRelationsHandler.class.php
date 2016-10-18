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

class QA_CategoryQuestionRelationsObject extends XoopsSimpleObject {

	function QA_CategoryQuestionRelationsObject() {
		$this->initVar('qa_category_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('qa_question_id', XOBJ_DTYPE_INT, 0, false);
	}
}

class QA_CategoryQuestionRelationsHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = "qa_category_question_relations";
	var $mPrimary = "qa_question_id";
	var $mClass = "QA_CategoryQuestionRelationsObject";
	var $mPrimaryAry = array('qa_category_id', 'qa_question_id');
	
	public function deleteByCategoryId($categoryId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add('qa_category_id', $categoryId);
		if (!$this->deleteAll($criteriaCompo, true)) {
			return false;
		}
		return true;
		
	}
	public function deleteByQuestionId($questionId) {
		$criteriaCompo = new CriteriaCompo();
		$criteriaCompo->add(new Criteria('qa_question_id', $questionId));
		if (!$this->deleteAll($criteriaCompo, true)) {
			return false;
		}
		return true;
	}
}
?>
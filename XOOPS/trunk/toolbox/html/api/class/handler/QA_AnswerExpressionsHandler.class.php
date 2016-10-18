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

class QA_AnswerExpressionsObject extends XoopsSimpleObject {

	function QA_AnswerExpressionsObject() {
		$this->initVar('qa_answer_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('language_code', XOBJ_DTYPE_STRING, '', true, 30);
		$this->initVar('expression', XOBJ_DTYPE_STRING, '', false);
	}
}

class QA_AnswerExpressionsHandler extends Toolbox_CompositeKeyGenericHandler {
	
	var $mTable = "qa_answer_expressions";
	var $mPrimary = "qa_answer_id";
	var $mClass = "QA_AnswerExpressionsObject";
	var $mPrimaryAry = array('qa_answer_id', 'language_code');
}
?>
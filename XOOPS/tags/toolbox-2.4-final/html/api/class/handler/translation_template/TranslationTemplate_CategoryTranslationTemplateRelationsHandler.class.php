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

class TranslationTemplate_CategoryTranslationTemplateRelationsObject extends XoopsSimpleObject {

	function TranslationTemplate_CategoryTranslationTemplateRelationsObject() {
		$this->initVar('category_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('translation_template_id', XOBJ_DTYPE_INT, 0, false);
	}
}

class TranslationTemplate_CategoryTranslationTemplateRelationsHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = "template_category_translation_template_relations";
	var $mPrimary = "category_id";
	var $mClass = "TranslationTemplate_CategoryTranslationTemplateRelationsObject";
	var $mPrimaryAry = array('category_id', 'translation_template_id');
	
	public function deleteByCategoryId($categoryId) {
		$criteria = new CriteriaCompo();
		$criteria->add('category_id', $categoryId);
		
		if (!$this->deleteAll($criteria, true)) {
			return false;
		}
		
		return true;
		
	}
	public function deleteByTranslationTemplateId($translationTemplateId) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('translation_template_id', $translationTemplateId));
		
		if (!$this->deleteAll($criteria, true)) {
			return false;
		}
		
		return true;
	}
}
?>
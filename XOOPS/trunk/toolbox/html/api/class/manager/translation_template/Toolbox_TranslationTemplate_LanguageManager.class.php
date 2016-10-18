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


require_once dirname(__FILE__).'/Toolbox_TranslationTemplate_AbstractManager.class.php';

class Toolbox_TranslationTemplate_LanguageManager extends Toolbox_TranslationTemplateAbstractManager {
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function deleteLanguage($name, $languageCode) {
		$resourceId = $this->getResourceIdByName($name);
		
		$this->deleteTranslationTemplateExpressions($resourceId, $languageCode);
		$this->deleteBoundWordSetExpressions($resourceId, $languageCode);
		$this->deleteBoundWordExpressions($resourceId, $languageCode);
		$this->deleteCategoryExpressions($resourceId, $languageCode);
	}
	
	/**
	 * 
	 * @param int $resourceId
	 * @param String $languageCode
	 * @return unknown_type
	 */
	private function deleteTranslationTemplateExpressions($resourceId, $languageCode) {
		$this->deleteExpressions('Toolbox_TranslationTemplate_RecordReadManager'
				,'getRecordsByResourceId', 'translation_template_id'
				,'m_translationTemplateExpressionsHandler', $resourceId, $languageCode);
	}
	
	/**
	 * 
	 * @param unknown_type $resourceId
	 * @param unknown_type $languageCode
	 * @return unknown_type
	 */
	private function deleteBoundWordSetExpressions($resourceId, $languageCode) {
		$this->deleteExpressions('Toolbox_TranslationTemplate_BoundWordSetReadManager'
				,'getBoundWordSetsByResourceId', 'bound_word_set_id'
				,'m_boundWordSetExpressionsHandler', $resourceId, $languageCode);
	}

	
	/**
	 * 
	 * @param unknown_type $resourceId
	 * @param unknown_type $languageCode
	 * @return unknown_type
	 */
	private function deleteBoundWordExpressions($resourceId, $languageCode) {
		$this->deleteExpressions('Toolbox_TranslationTemplate_BoundWordReadManager'
				,'getBoundWordsByResourceId', 'bound_word_id'
				, 'm_boundWordExpressionsHandler', $resourceId, $languageCode);
	}
	
	/**
	 * 
	 * @param unknown_type $resourceId
	 * @param unknown_type $languageCode
	 * @return unknown_type
	 */
	private function deleteCategoryExpressions($resourceId, $languageCode) {
		$this->deleteExpressions('Toolbox_TranslationTemplate_CategoryReadManager'
				,'getCategoriesByResourceId', 'category_id'
				, 'm_categoryExpressionsHandler', $resourceId, $languageCode);
	}	
	
	/**
	 * 
	 * @param unknown_type $readManager
	 * @param unknown_type $method
	 * @param unknown_type $idName
	 * @param unknown_type $handler
	 * @param unknown_type $resourceId
	 * @param unknown_type $languageCode
	 * @return unknown_type
	 */
	private function deleteExpressions($readManager, $method, $idName, $handler, $resourceId, $languageCode) {
		
		$file = dirname(__FILE__).'/'.$readManager.'.class.php';
		if (is_file($file)) require_once $file;
		
		$manager = new $readManager();
		$objs = $manager->{$method}($resourceId);
		
		$ids = array();
		foreach ($objs as $obj) {
			$ids[] = $obj->get('id');
		}
		
		$c = new CriteriaCompo();
//		$c->add(new Criteria($idName, implode(',', $ids), 'IN'));
		foreach ($ids as $id) {
			$c->add(new Criteria($idName, $id), 'OR');
		}
		$c->add(new Criteria('language_code', $languageCode));
		
		$objs = $this->{$handler}->getObjects($c);
		
		foreach ($objs as $obj) {
			$obj->set('expression', '');
			$this->{$handler}->insert($obj, true);
		}
	}
}
?>
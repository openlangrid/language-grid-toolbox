<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
define('_LEGACY_PREVENT_EXEC_COMMON_', 1);

require_once(dirname(__FILE__).'/../../../../mainfile.php');
require_once(dirname(__FILE__).'/../exception/UnsupportedLanguagePairException.php');

require_once(dirname(__FILE__).'/../model/Template.php');
require_once(dirname(__FILE__).'/../model/Choice.php');
require_once(dirname(__FILE__).'/../model/ChoiceParameter.php');
require_once(dirname(__FILE__).'/../model/ValueParameter.php');
require_once(dirname(__FILE__).'/../model/Category.php');

$root =& XCube_Root::getSingleton();
$root->mController->executeCommonSubset();

class TemplateParallelTextDAO{
	private $db;
	private $typeId = 4;
	private $resourceId;
	
	// table names
	private $templates;
	private $templateTranslationTemplates;
	private $templateTranslationTemplateExpressions;
	private $templateBoundWords;
	private $templateBoundWordExpressions;
//	private $templateBoundWordSets;
	private $templateBoundWordSetExpressions;
//	private $templateBoundWordSetIds;
	private $templateBoundWordSetTranslationTemplateRelations;
	private $templateDefaultBoundWordSets;
	private $templateCategories;
	private $templateCategoryExpressions;
	private $templateCategoryTranslationTemplateRelations;

	public function __construct($resourceName){
		$this->db = Database::getInstance();
		mysql_set_charset('utf8');
		$this->templates = $this->db->prefix('user_dictionary');
		$this->templateBoundWords = $this->db->prefix('template_bound_words');
		$this->templateBoundWordExpressions = $this->db->prefix('template_bound_word_expressions');
//		$this->templateBoundWordSets = $this->db->prefix('template_bound_word_sets');
		$this->templateBoundWordSetExpressions = $this->db->prefix('template_bound_word_set_expressions');
//		$this->templateBoundWordSetIds = $this->db->prefix('template_bound_word_set_ids');
		$this->templateBoundWordSetTranslationTemplateRelations = $this->db->prefix('template_bound_word_set_translation_template_relations');
		$this->templateCategories = $this->db->prefix('template_categories');
		$this->templateCategoryExpressions = $this->db->prefix('template_category_expressions');
		$this->templateCategoryTranslationTemplateRelations = $this->db->prefix('template_category_translation_template_relations');
		$this->templateDefaultBoundWordSets = $this->db->prefix('template_default_bound_word_sets');
		$this->templateTranslationTemplates = $this->db->prefix('template_translation_templates');
		$this->templateTranslationTemplateExpressions = $this->db->prefix('template_translation_template_expressions');
		
		$resourceName = mysql_real_escape_string($resourceName);
		$this->resourceId = $this->getDicId($resourceName);
		if(!isset($this->resourceId)){
			throw new Exception("Template Resource '" . $resourceName . "' is not found.");
		}
	}

	public function getDBInstance(){
		return $this->db;
	}
	
	public function searchTemplates($language, $text, $matchingMethod, $categoryIds){
		$mQuery = $this->makeMatchingMethodQuery("ttte.expression", $text, $matchingMethod);
		$cQuery = $this->makeCategoriesQuery("tcttr.category_id", $categoryIds);
		$sql = 
			"SELECT ttt.id AS template_id, ttte.expression AS template, tbwsttr.index AS parameter_id," .
				"set_value.expression AS value_type, tdbws.type AS default_value_type, tbw.id AS choice_id," .
				"word_value.expression AS choice_value, category_value.category_id," .
				"category_value.expression AS category_name, ttt.creation_time AS created_at," .
				"ttt.update_time AS updated_at" .
			" FROM ((((((((%s" .
				" `" . $this->templateTranslationTemplates . "` AS ttt" .
				" INNER JOIN `" . $this->templateTranslationTemplateExpressions . "` AS ttte ON ttt.id = ttte.translation_template_id)" .
				" %s" .
				" LEFT JOIN `" . $this->templateCategoryTranslationTemplateRelations . "` AS tcttr_category ON tcttr_category.translation_template_id = ttt.id)" .
				" LEFT JOIN (SELECT * FROM `" . $this->templateCategoryExpressions . "` AS tce WHERE tce.language_code = '%s') AS category_value ON category_value.category_id = tcttr_category.category_id)" .
				" LEFT JOIN `" . $this->templateBoundWordSetTranslationTemplateRelations . "` AS tbwsttr ON ttte.translation_template_id = tbwsttr.translation_template_id)" .
				" LEFT JOIN (SELECT * FROM `" . $this->templateBoundWordSetExpressions . "` AS tbwse WHERE tbwse.language_code = '%s') AS set_value  ON tbwsttr.bound_word_set_id = set_value.bound_word_set_id)" .
				" LEFT JOIN `" . $this->templateDefaultBoundWordSets . "` AS tdbws  ON tdbws.id = tbwsttr.bound_word_set_id)" .
				" LEFT JOIN `" . $this->templateBoundWords . "` AS tbw ON tbw.bound_word_set_id = tbwsttr.bound_word_set_id)" .
				" LEFT JOIN (SELECT * FROM `" . $this->templateBoundWordExpressions . "` AS tbwe WHERE tbwe.language_code = '%s') AS word_value ON tbw.id = word_value.bound_word_id)" .
			" WHERE ttt.resource_id = %s" .
				" AND ttte.language_code = '%s'%s" .
			" ORDER BY ttt.id;";
		
		$open = "(";
		if(empty($cQuery)) {
			$open = "";
		}
		
		$selectSql = sprintf($sql, $open, $cQuery, $language, $language, $language, $this->resourceId, $language, $mQuery);
		$results = $this->db->query($selectSql);
		return $this->makeTemplateModel($results);
	}
	
	public function getTemplatesByTemplateId($language, $templateIds){
		$sql =
			"SELECT ttt.id AS template_id, ttte.expression AS template, tbwsttr.index AS parameter_id," .
				"set_value.expression AS value_type, tdbws.type AS default_value_type, tbw.id AS choice_id," .
				"word_value.expression AS choice_value, category_value.category_id," .
				"category_value.expression AS category_name, ttt.creation_time AS created_at," .
				"ttt.update_time AS updated_at" .
			" FROM ((((((((" .
				" (select * FROM `" . $this->templateTranslationTemplates . "` WHERE id IN(%s)) AS ttt" .
				" INNER JOIN `" . $this->templateTranslationTemplateExpressions . "` AS ttte ON ttt.id = ttte.translation_template_id)" .
				" LEFT JOIN `" . $this->templateCategoryTranslationTemplateRelations . "` AS tcttr_category ON tcttr_category.translation_template_id = ttt.id)" .
				" LEFT JOIN (SELECT * FROM `" . $this->templateCategoryExpressions . "` AS tce WHERE tce.language_code = '%s') AS category_value ON category_value.category_id = tcttr_category.category_id)" .
				" LEFT JOIN `" . $this->templateBoundWordSetTranslationTemplateRelations . "` AS tbwsttr ON ttte.translation_template_id = tbwsttr.translation_template_id)" .
				" LEFT JOIN (SELECT * FROM `" . $this->templateBoundWordSetExpressions . "` AS tbwse WHERE tbwse.language_code = '%s') AS set_value  ON tbwsttr.bound_word_set_id = set_value.bound_word_set_id)" .
				" LEFT JOIN `" . $this->templateDefaultBoundWordSets . "` AS tdbws  ON tdbws.id = tbwsttr.bound_word_set_id)" .
				" LEFT JOIN `" . $this->templateBoundWords . "` AS tbw ON tbw.bound_word_set_id = tbwsttr.bound_word_set_id)" .
				" LEFT JOIN (SELECT * FROM `" . $this->templateBoundWordExpressions . "` AS tbwe WHERE tbwe.language_code = '%s') AS word_value ON tbw.id = word_value.bound_word_id)" .
			" WHERE ttt.resource_id = %s" .
				" AND ttte.language_code = '%s'" .
			" ORDER BY ttt.id;";
		$ins = "";
		foreach($templateIds as $id) {
			$ins = $ins . "'" . $id . "'" . ",";			
		}
		$ins = preg_replace("/,$/", "", $ins);
		$selectSql = sprintf($sql, $ins, $language, $language, $language, $this->resourceId, $language);
		$results = $this->db->query($selectSql);
		return $this->makeTemplateModel($results);
	}
	
	public function generateSentence($language, $templateId, $boundChoiceParameters, $boundValueParameters){
		$sql =
			"SELECT ttt.id AS template_id, ttte.expression AS template, tbwsttr.index AS parameter_id," .
				"set_value.expression AS value_type, tdbws.type AS default_value_type, tbw.id AS choice_id," .
				"word_value.expression AS choice_value," .
				"ttt.creation_time AS created_at, ttt.update_time AS updated_at" .
			" FROM ((((((" .
				" `" . $this->templateTranslationTemplates . "` AS ttt" .
				" INNER JOIN `" . $this->templateTranslationTemplateExpressions . "` AS ttte ON ttt.id = ttte.translation_template_id)" .
				" LEFT JOIN `" . $this->templateBoundWordSetTranslationTemplateRelations . "` AS tbwsttr ON ttte.translation_template_id = tbwsttr.translation_template_id)" .
				" LEFT JOIN (SELECT * FROM `" . $this->templateBoundWordSetExpressions . "` AS tbwse WHERE tbwse.language_code = '%s') AS set_value  ON tbwsttr.bound_word_set_id = set_value.bound_word_set_id)" .
				" LEFT JOIN `" . $this->templateDefaultBoundWordSets . "` AS tdbws  ON tdbws.id = tbwsttr.bound_word_set_id)" .
				" LEFT JOIN `" . $this->templateBoundWords . "` AS tbw ON tbw.bound_word_set_id = tbwsttr.bound_word_set_id)" .
				" LEFT JOIN (SELECT * FROM `" . $this->templateBoundWordExpressions . "` AS tbwe WHERE tbwe.language_code = '%s') AS word_value ON tbw.id = word_value.bound_word_id)" .
			" WHERE ttt.resource_id = %s" .
				" AND ttt.id = %s" . 
				" AND ttte.language_code = '%s'";
		$selectSql = sprintf($sql, $language, $language, $this->resourceId, $templateId, $language);
		$results = $this->db->query($selectSql);
		$choices;
		$sentence = "";
		while($result = $this->db->fetchRow($results)){
			if(empty($sentence)){
				$sentence = $result[1];
			}
			if( ! is_null($result[2])){
				$parameterId = $result[2];
				// choices
				if( ! is_null($result[3]) && ! is_null($result[5])){
					if( ! isset($choices[$parameterId])){
						$choices = array($parameterId => array());
					}
					$choices[$parameterId][$result[5]] = $result[6];
				}
			}
		}
		foreach($boundChoiceParameters as $bcp){
			$sentence = preg_replace("/\[" . $bcp['parameterId'] . "\]/", $choices[$bcp['parameterId']][$bcp['choiceId']], $sentence);
		}
		foreach($boundValueParameters as $bvp){
			$sentence = preg_replace("/\[" . $bvp['parameterId'] . "\]/", $bvp['value'], $sentence);
		}
		return $sentence;
	}
	
	public function listTemplateCategories($language){
		$sql  = "SELECT tce.category_id, tce.expression FROM" .
		 	" `" . $this->templateCategoryExpressions . "` AS tce" .
		 	" INNER JOIN `" . $this->templateCategories . "` AS tc" .
		 	" ON tce.category_id = tc.id" .
		 	" WHERE tc.resource_id = %s AND tce.language_code = '%s'" .
			" ORDER BY tc.id";
		$selectSql = sprintf($sql, $this->resourceId, $language);
		$results = $this->db->query($selectSql);
		$categories = array();
		while($result = $this->db->fetchRow($results)){
			$categories[] = new Category($result[0], $result[1]);
		}
		return $categories;
	}
	
	public function isDeploy(){
		$sql = "SELECT deploy_flag FROM `" . $this->templates . "` WHERE user_dictionary_id = %s";
		$selectSql = sprintf($sql, $this->resourceId);
		$results = $this->db->query($selectSql);
		$result = $this->db->fetchRow($results);
		if( ! is_null($result)){
			return $result[0];
		}
		return false;
	}

	private function getDicId($resourceName){
		$sql = '
			SELECT	`user_dictionary_id`
			FROM	' . $this->templates .'
			WHERE	`dictionary_name` = \'%s\'
			AND		`delete_flag` = \'0\'
			AND		`type_id` = %d';
		$sql = sprintf($sql, $resourceName, $this->typeId);
		$result = $this->db->query($sql);
		$row = $this->db->fetchRow($result);
		return $row[0];
	}

	private function makeCategoriesQuery($column, $categoryIds){
		if($categoryIds == null || count($categoryIds) == 0) {
			return "";
		}
		$ins = "";
		foreach($categoryIds as $id) {
			$ins = $ins . "'" . $id . "'" . ",";			
		}
		$ins = preg_replace("/,$/", "", $ins) . ")";
		return " INNER JOIN (SELECT * FROM `" . $this->templateCategoryTranslationTemplateRelations . "` WHERE category_id IN(" .
			$ins .
			") AS tcttr ON tcttr.translation_template_id = ttt.id)";
	}
	
	private function makeMatchingMethodQuery($column, $text, $matchingMethod){
		$query = " AND " . $column;
		if($matchingMethod === "COMPLETE") {
			$query = $query . " LIKE '" . $text . "'";
		}else if($matchingMethod === "PARTIAL") {
			$query = $query . " LIKE '%" . $text . "%'";
		}else if($matchingMethod === "PREFIX") {
			$query = $query . " LIKE '" . $text . "%'";
		}else if($matchingMethod === "SUFFIX") {
			$query = $query . " LIKE '%" . $text . "'";
		}else if($matchingMethod === "REGEX") {
			$query = $query . " REGEXP '" . $text . "'";
		}else{
			$query = "";
		}
		return $query;
	}
	
	private function makeTemplateModel($results){
		$templates = array();
		while($result = $this->db->fetchRow($results)){
			if( ! isset($templates[$result[0]])){
				$templates[$result[0]] = new Template();
			}
			$template = $templates[$result[0]];

			if(empty($template->templateId)){
				$template->templateId = $result[0];
			}
			if(empty($template->template)){
				$template->template = preg_replace("/[\[\]]/", "", preg_replace("/\[[0-9]+\]/", '<param id="$0">', $result[1]));
			}

			// categories
			$isAdd = true;
			if( ! is_null($result[7])){
				if(isset($template->categories)){
					foreach($template->categories as $category){
						if($category->categoryId == $result[7]){
							$isAdd = false;
							break;
						}
					}
				}else{
					$template->categories = array();
				}
				if($isAdd){
					$template->categories[] = new Category($result[7], $result[8]);
				}
			}
			
			if( ! is_null($result[2])){
				$parameterId = $result[2];
				
				if( ! is_null($result[4])){
					// values	
					if( ! isset($template->valueParameters)){
						$template->valueParameters = array();
					}
					
					$isAddValue = true;
					foreach($template->valueParameters as $valueParameter){
						if($valueParameter->parameterId == $parameterId){
							$isAddValue = false;
							break;
						}
					}
					if($isAddValue){
						$valueType = $result[4];
						if($valueType === "number"){
							$valueType = "float";
						}else if($valueType === "date"){
							$valueType = "day_of_month";
						}
						$template->valueParameters[] = new ValueParameter($parameterId, $valueType);
					}
				}else if( ! is_null($result[3]) && ! is_null($result[5])){
					// choices
					if( ! isset($template->choiceParameters)){
						$template->choiceParameters = array();
						$template->choiceParameters[] = new ChoiceParameter($parameterId);
					}
					$isAdd = true;
					foreach($template->choiceParameters as $choiceParameter){
						if($choiceParameter->parameterId == $parameterId){
							$isAddChoice = true;
							foreach($choiceParameter->choices as $choice){
								if($choice->choiceId == $result[5]){
									$isAddChoice = false;
									break;
								}
							}
							if($isAddChoice){
								$choiceParameter->choices[] = new Choice($result[5], $result[6]);
							}
							$isAdd = false;
							break;
						}
					}
					if($isAdd){
						$template->choiceParameters[] = new ChoiceParameter($parameterId);
						$template->choiceParameters->choices[] = new Choice($result[5], $result[6]);
					}
					$isAdd = true;
				}
			}
		}
		return $templates;
	}
}
?>

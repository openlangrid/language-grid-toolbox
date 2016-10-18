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

interface ITranslationTemplateClient {
	
	/**
	 * 
	 * @param String $name
	 * @param String $sortOrder optional
	 * @param String $orderBy optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return ToolboxVO_TranslationTemplate_TranslationTemplateRecord[]
	 */
	public function getAllRecords($name, $sortOrder = null, $orderBy = null, $offset = null, $limit = null);
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @return ToolboxVO_TranslationTemplate_TranslationTemplateRecord
	 */
	public function getRecord($name, $id);
	
	/**
	 * 
	 * @param String $name
	 * @param ToolboxVO_Resource_Expression[] $expressions
	 * @param int[] $wordSetIds
	 * @param int[] $categoryIds optional
	 * @return ToolboxVO_TranslationTemplate_TranslationTemplateRecord
	 */
	public function addRecord($name, $expressions, $wordSetIds, $categoryIds = null);
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @return void
	 */
	public function deleteRecord($name, $id);
	
	/**
	 * 
	 * @param String $name
	 * @return void
	 */
	public function deleteAllRecords($name);
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @param ToolboxVO_Resource_Expression[] $expressions
	 * @param int[] $wordSetIds
	 * @param int[] $categoryIds optional
	 * @return void
	 */
	public function updateRecord($name, $id, $expressions, $wordSetIds, $categoryIds = null);
	
	/**
	 * 
	 * @param String $name
	 * @param String $word
	 * @param String $language
	 * @param String $matchingMethod
	 * @param int[] $categoryIds optional
	 * @param String $sortOrder optional
	 * @param String $orderBy optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return ToolboxVO_TranslationTemplate_TranslationTemplateRecord[]
	 */
	public function searchRecord($name, $word, $language, $matchingMethod, $categoryIds = null, $sortOrder = null, $orderBy = null, $offset = null, $limit = null);
	
	/**
	 * 
	 * @param String $name
	 * @param String $word
	 * @param String $language
	 * @param String $matchingMethod
	 * @param int[] $categoryIds optional
	 * @return int
	 */
	public function countRecords($name, $word, $language, $matchingMethod, $categoryIds = null);
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @param ToolboxVO_TranslationTemplate_BoundWord[] $boundWords
	 * @return ToolboxVO_TranslationTemplate_TranslationTemplateRecord
	 */
	public function fillTranslationTemplate($name, $id, $boundWords);
	
	/**
	 * 
	 * @param String $name
	 * @param String $sortOrder optional
	 * @param String $orderBy optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return ToolboxVO_TranslationTemplate_BoundWordSet[]
	 */
	public function getAllBoundWordSets($name, $sortOrder = null, $orderBy = null, $offset = null, $limit = null);
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @return ToolboxVO_TranslationTemplate_BoundWordSet
	 */
	public function getBoundWordSet($name, $id);
	
	/**
	 * 
	 * @param String $name
	 * @param ToolboxVO_Resource_Expression[] $setName
	 * @return ToolboxVO_TranslationTemplate_BoundWordSet
	 */
	public function addBoundWordSet($name, $setName);
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @return void
	 */
	public function deleteBoundWordSet($name, $id);
	
	/**
	 * 
	 * @param String $name
	 * @return void
	 */
	public function deleteAllBoundWordSets($name);
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @param ToolboxVO_Resource_Expression[] $setName
	 * @return void
	 */
	public function updateBoundWordSet($name, $id, $setName);
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @param String $sortOrder optional
	 * @param String $orderBy optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return ToolboxVO_TranslationTemplate_BoundWord[]
	 */
	public function getAllBoundWords($name, $id, $sortOrder = null, $orderBy = null, $offset = null, $limit = null);
	
	/**
	 * 
	 * @param String $name
	 * @param int $boundWordSetId
	 * @param int $boundWordId
	 * @return ToolboxVO_TranslationTemplate_BoundWord
	 */
	public function getBoundWord($name, $boundWordSetId, $boundWordId);
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @param ToolboxVO_Resource_Expression[] $expressions
	 * @return ToolboxVO_TranslationTemplate_BoundWord
	 */
	public function addBoundWord($name, $id, $expressions);
	
	/**
	 * 
	 * @param String $name
	 * @param int $boundWordSetId
	 * @param int $boundWordId
	 * @return void
	 */
	public function deleteBoundWord($name, $boundWordSetId, $boundWordId);
	
	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @return void
	 */
	public function deleteAllBoundWords($name, $id);
	
	/**
	 * 
	 * @param String $name
	 * @param int $boundWordSetId
	 * @param int $boundWordId
	 * @return void
	 */
	public function updateBoundWord($name, $boundWordSetId, $boundWordId, $expressions);
	
	/**
	 * 
	 * @param String $name
	 * @param String $sortOrder optional
	 * @param String $orderBy optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return ToolboxVO_TranslationTemplate_TranslationTemplateCategory[]
	 */
	public function getAllCategories($name, $sortOrder = null, $orderBy = null, $offset = null, $limit = null);
	
	/**
	 * 
	 * @param String $name
	 * @param int $categoryId
	 * @return ToolboxVO_TranslationTemplate_TranslationTemplateCategory
	 */
	public function getCategory($name, $categoryId);
	
	/**
	 * 
	 * @param String $name
	 * @param ToolboxVO_Resource_Expression[] $categoryName
	 * @return ToolboxVO_TranslationTemplate_TranslationTemplateCategory
	 */
	public function addCategory($name, $categoryName);
	
	/**
	 * 
	 * @param String $name
	 * @param int $categoryId
	 * @return void
	 */
	public function deleteCategory($name, $categoryId);
	
	/**
	 * 
	 * @param String $name
	 * @return void
	 */
	public function deleteAllCategories($name);
	
	/**
	 * 
	 * @param String $name
	 * @param int $categoryId
	 * @param ToolboxVO_Resource_Expression[] $categoryName
	 * @return void
	 */
	public function updateCategory($name, $categoryId, $categoryName);
	
	/**
	 * 
	 * @param String $name
	 * @param int $categoryId
	 * @return ToolboxVO_TranslationTemplate_TranslationTemplateRecord[]
	 */
	public function getRecordsByCategory($name, $categoryId);
	
	/**
	 * 
	 * @param String $name
	 * @param String $serviceId
	 * @param String $serviceName
	 * @return ToolboxVO_LangridAccess_LanguageService
	 */
	public function deploy($name, $serviceId, $serviceName);
	
	/**
	 * 
	 * @param String $name
	 * @return void
	 */
	public function undeploy($name);
	
	/**
	 * 
	 * @param String $type
	 * @return void
	 */
	//public function addDefaultBoundWordSet($type);
}
?>
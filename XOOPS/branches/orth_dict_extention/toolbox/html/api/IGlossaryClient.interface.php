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

interface IGlossaryClient {

	/** 
	 * 
	 * @param String $name
	 * @param String $sortOrder optional
	 * @param String $orderBy optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return ToolboxVO_QA_QARecord[]
	 */
	public function getAllRecords($name, $sortOrder = null, $orderBy = null
			, $offset = null, $limit = null);
	
	/**
	 * 
	 * @param String $name
	 * @param int $categoryId
	 * @return ToolboxVO_Glossary_GlossaryRecord[]
	 */
	public function getRecordsByCategory($name, $categoryId);

	/**
	 * 
	 * @param String $name
	 * @param int $id
	 * @return ToolboxVO_Glossary_GlossaryRecord
	 */
	public function getRecord($name, $id);

	/**
	 * 
	 * @param String $name
	 * @param ToolboxVO_Resource_Expression[] $term
	 * @param ToolboxVO_Glossary_Definition[][] $definition
	 * @param int[] $categoryIds optional
	 * @return ToolboxVO_Glossary_GlossaryRecord
	 */
	public function addRecord($name, $term, $definition, $categoryIds = null);
	
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
	 * @param int $recordId
	 * @param ToolboxVO_Resource_Expression[] $term
	 * @param ToolboxVO_Glossary_Definition[][] $definition
	 * @param int[] $categoryIds optional
	 * @return ToolboxVO_Glossary_GlossaryRecord
	 */
	public function updateRecord($name, $recordId, $term, $definition, $categoryIds = null);
	
	/**
	 * 
	 * @param String $name
	 * @param ToolboxVO_Resource_Expression[] $categoryName
	 * @return ToolboxVO_Glossary_GlossaryCategory
	 */
	public function addCategory($name, $categoryName, $language);
	
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
	 * @param int $caegoryId
	 * @param String $categoryName
	 * @return void
	 */
	public function updateCategory($name, $caegoryId, $categoryName);
	
	/**
	 * 
	 * @param String $name
	 * @return ToolboxVO_Glossary_GlossaryCategory[]
	 */
	public function getAllCategories($name);
	
	/**
	 * 
	 * @param String $name
	 * @param int $categoryId
	 * @return ToolboxVO_Glossary_GlossaryCategory
	 */
	public function getCategory($name, $categoryId);

	/**
	 * @param String $name
	 * @param String $word
	 * @param String $language
	 * @param String $matchingMethod
	 * @param int $categoryIds optional
	 * @param String $scope optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return ToolboxVO_QA_QARecord[]
	 */
	public function searchRecord($name, $word, $language, $matchingMethod
			, $categoryIds = null, $scope = null, $sortOrder = null
			, $orderBy = null, $offset = null, $limit = null);
			
	/**
	 * @param String $name
	 * @param String $word
	 * @param String $language
	 * @param String $matchingMethod
	 * @param int $categoryIds optional
	 * @param String $scope optional
	 * @param int $offset optional
	 * @param int $limit optional
	 * @return int
	 */
	public function countRecords($name, $word, $language, $matchingMethod
			, $categoryIds = null, $scope = null, $sortOrder = null
			, $orderBy = null);

	/**
	 * 
	 * @param String $name
	 * @param int $serviceId
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
}
?>
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

require_once(dirname(__FILE__).'/../../ITranslationTemplateClient.interface.php');
require_once(dirname(__FILE__).'/Toolbox_AbstractClient.class.php');
require_once(dirname(__FILE__).'/ResourceClient.class.php');

require_once dirname(__FILE__).'/../manager/translation_template/Toolbox_TranslationTemplate_RecordCreateEditManager.class.php';
require_once dirname(__FILE__).'/../manager/translation_template/Toolbox_TranslationTemplate_RecordReadManager.class.php';
require_once dirname(__FILE__).'/../manager/translation_template/Toolbox_TranslationTemplate_RecordSearchManager.class.php';
require_once dirname(__FILE__).'/../manager/translation_template/Toolbox_TranslationTemplate_BoundWordSetCreateEditManager.class.php';
require_once dirname(__FILE__).'/../manager/translation_template/Toolbox_TranslationTemplate_BoundWordSetReadManager.class.php';
require_once dirname(__FILE__).'/../manager/translation_template/Toolbox_TranslationTemplate_BoundWordCreateEditManager.class.php';
require_once dirname(__FILE__).'/../manager/translation_template/Toolbox_TranslationTemplate_BoundWordReadManager.class.php';
require_once dirname(__FILE__).'/../manager/translation_template/Toolbox_TranslationTemplate_CategoryCreateEditManager.class.php';
require_once dirname(__FILE__).'/../manager/translation_template/Toolbox_TranslationTemplate_CategoryReadManager.class.php';

class TranslationTemplateClient extends Toolbox_AbstractClient implements ITranslationTemplateClient {

	protected $resourceClient;
	protected $m_selectedLanguage;

	public function __construct() {
		parent::__construct();
		$this->_init();
	}
    
	protected function _init() {
		if (isset($_COOKIE["selectedLanguage"])) {
			$this->m_selectedLanguage = $_COOKIE["selectedLanguage"];
		} else {
			$this->m_selectedLanguage = 'en';
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#getAllRecords($name, $sortOrder, $orderBy, $offset, $limit)
	 */
	public function getAllRecords($name, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = array();
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_RecordReadManager();
			$contents = $manager->getAllRecords($name, $sortOrder, $orderBy, $offset, $limit);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#getRecord($name, $id)
	 */
	public function getRecord($name, $id) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($id, '$id must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_RecordReadManager();
			$contents = $manager->getRecord($name, $id);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#addRecord($name, $expressions, $wordSetIds, $categoryIds)
	 */
	public function addRecord($name, $expressions, $wordSetIds, $categoryIds = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertExpressions($expressions);
			$this->assertWordSetIds($wordSetIds);
			
			$manager = new Toolbox_TranslationTemplate_RecordCreateEditManager();
			$contents = $manager->addRecord($name, $expressions, $wordSetIds, $categoryIds);
			
			$this->updateResourceTimestamp($name);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#deleteRecord($name, $id)
	 */
	public function deleteRecord($name, $id) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($id, '$id must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_RecordCreateEditManager();
			$manager->deleteRecord($name, $id);
			
			$this->updateResourceTimestamp($name);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#deleteAllRecords($name)
	 */
	public function deleteAllRecords($name) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_RecordCreateEditManager();
			$manager->deleteAllRecords($name);
			
			$this->updateResourceTimestamp($name);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#updateRecord($name, $id, $expressions, $wordSetIds, $categoryIds)
	 */
	public function updateRecord($name, $id, $expressions, $wordSetIds, $categoryIds = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($id, '$id must not be an empty string.');
			$this->assertExpressions($expressions);
			$this->assertWordSetIds($wordSetIds);
			
			$manager = new Toolbox_TranslationTemplate_RecordCreateEditManager();
			$contents = $manager->updateRecord($name, $id, $expressions, $wordSetIds, $categoryIds);
			
			$this->updateResourceTimestamp($name);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#searchRecord($name, $word, $language, $matchingMethod, $categoryIds, $sortOrder, $orderBy, $offset, $limit)
	 */
	public function searchRecord($name, $word, $language, $matchingMethod, $categoryIds = null, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($word, '$word must not be an empty string.');
			$this->assertNotEmpty($language, '$language must not be an empty string.');
			$this->assertMatchingMethod($matchingMethod);
			
			$manager = new Toolbox_TranslationTemplate_RecordSearchManager();
			$contents = $manager->searchRecord($name, $word, $language, $matchingMethod, $categoryIds, $sortOrder, $orderBy, $offset, $limit);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#searchRecord($name, $word, $language, $matchingMethod, $categoryIds, $sortOrder, $orderBy, $offset, $limit)
	 */
	public function searchRecordAndor($name, $word, $language, $matchingAndor, $searchType = "like", $categoryIds = null, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
//			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertType('array', $name, '$name must not be non array type.');
			$this->assertNotEmpty($word, '$word must not be an empty string.');
			$this->assertNotEmpty($language, '$language must not be an empty string.');
			$this->assertMatchingAndor($matchingAndor);
			
			$manager = new Toolbox_TranslationTemplate_RecordSearchManager();
			$contents = $manager->searchRecordAndor($name, $word, $language, $matchingAndor, $searchType, $categoryIds, $sortOrder, $orderBy, $offset, $limit);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#countRecords($name, $word, $language, $matchingMethod, $categoryIds)
	 */
	public function countRecords($name, $word, $language, $matchingMethod, $categoryIds = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($word, '$word must not be an empty string.');
			$this->assertNotEmpty($language, '$language must not be an empty string.');
			$this->assertMatchingMethod($matchingMethod);
			
			$manager = new Toolbox_TranslationTemplate_RecordSearchManager();
			$contents = $manager->countRecords($name, $word, $language, $matchingMethod, $categoryIds = null);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#fillTranslationTemplate($name, $id, $boundWords)
	 */
	public function fillTranslationTemplate($name, $id, $boundWords) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($id, '$id must not be an empty string.');
			$this->assertBoundWords($boundWords);
			
			$manager = new Toolbox_TranslationTemplate_RecordReadManager();
			$contents = $manager->fillTranslationTemplate($name, $id, $boundWords);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#getAllBoundWordSets($name, $sortOrder, $orderBy, $offset, $limit)
	 */
	public function getAllBoundWordSets($name, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_BoundWordSetReadManager();
			$contents = $manager->getAllBoundWordSets($name, $sortOrder, $orderBy, $offset, $limit);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#getBoundWordSet($name, $id)
	 */
	public function getBoundWordSet($name, $id) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($id, '$id must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_BoundWordSetReadManager();
			$contents = $manager->getBoundWordSet($name, $id);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#addBoundWordSet($name, $setName)
	 */
	public function addBoundWordSet($name, $setName) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertExpressions($setName, '$setName');
			
			$manager = new Toolbox_TranslationTemplate_BoundWordSetCreateEditManager();
			$contents = $manager->addBoundWordSet($name, $setName);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#deleteBoundWordSet($name, $id)
	 */
	public function deleteBoundWordSet($name, $id) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($name, '$id must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_BoundWordSetCreateEditManager();
			$contents = $manager->deleteBoundWordSet($name, $id);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#deleteAllBoundWordSets($name)
	 */
	public function deleteAllBoundWordSets($name) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_BoundWordSetCreateEditManager();
			$contents = $manager->deleteAllBoundWordSets($name);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#updateBoundWordSet($name, $id, $setName)
	 */
	public function updateBoundWordSet($name, $id, $setName) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($name, '$id must not be an empty string.');
			$this->assertExpressions($setName, '$setName');
			
			$manager = new Toolbox_TranslationTemplate_BoundWordSetCreateEditManager();
			$contents = $manager->updateBoundWordSet($name, $id, $setName);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#getAllBoundWords($name, $id, $sortOrder, $orderBy, $offset, $limit)
	 */
	public function getAllBoundWords($name, $id, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($name, '$id must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_BoundWordReadManager();
			$contents = $manager->getAllBoundWords($name, $id, $sortOrder, $orderBy, $offset, $limit);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#getBoundWord($name, $boundWordSetId, $boundWordId)
	 */
	public function getBoundWord($name, $boundWordSetId, $boundWordId) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($boundWordSetId, '$boundWordSetId must not be an empty string.');
			$this->assertNotEmpty($boundWordId, '$boundWordId must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_BoundWordReadManager();
			$contents = $manager->getBoundWord($name, $boundWordSetId, $boundWordId);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#addBoundWord($name, $id, $expressions)
	 */
	public function addBoundWord($name, $id, $expressions) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($id, '$id must not be an empty string.');
			$this->assertExpressions($expressions);
			
			$manager = new Toolbox_TranslationTemplate_BoundWordCreateEditManager();
			$contents = $manager->addBoundWord($name, $id, $expressions);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#deleteBoundWord($name, $boundWordSetId, $boundWordId)
	 */
	public function deleteBoundWord($name, $boundWordSetId, $boundWordId) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($boundWordSetId, '$boundWordSetId must not be an empty string.');
			$this->assertNotEmpty($boundWordId, '$boundWordId must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_BoundWordCreateEditManager();
			$contents = $manager->deleteBoundWord($name, $boundWordSetId, $boundWordId);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#deleteAllBoundWords($name, $id)
	 */
	public function deleteAllBoundWords($name, $id) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($id, '$id must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_BoundWordCreateEditManager();
			$contents = $manager->deleteAllBoundWords($name, $id);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#updateBoundWord($name, $boundWordSetId, $boundWordId)
	 */
	public function updateBoundWord($name, $boundWordSetId, $boundWordId, $expressions) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($boundWordSetId, '$boundWordSetId must not be an empty string.');
			$this->assertNotEmpty($boundWordId, '$boundWordId must not be an empty string.');
			$this->assertExpressions($expressions);
			
			$manager = new Toolbox_TranslationTemplate_BoundWordCreateEditManager();
			$contents = $manager->updateBoundWord($name, $boundWordSetId, $boundWordId, $expressions);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#getAllCategories($name, $sortOrder, $orderBy, $offset, $limit)
	 */
	public function getAllCategories($name, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_CategoryReadManager();
			$contents = $manager->getAllCategories($name, $sortOrder, $orderBy, $offset, $limit);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#getCategory($name, $categoryId)
	 */
	public function getCategory($name, $categoryId) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($categoryId, '$categoryId must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_CategoryReadManager();
			$contents = $manager->getCategory($name, $categoryId);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#addCategory($name, $categoryName)
	 */
	public function addCategory($name, $categoryName) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertExpressions($categoryName, '$categoryName');
			
			$manager = new Toolbox_TranslationTemplate_CategoryCreateEditManager();
			$contents = $manager->addCategory($name, $categoryName);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#deleteCategory($name, $categoryId)
	 */
	public function deleteCategory($name, $categoryId) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($categoryId, '$categoryId must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_CategoryCreateEditManager();
			$manager->deleteCategory($name, $categoryId);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#deleteAllCategories($name)
	 */
	public function deleteAllCategories($name) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_CategoryCreateEditManager();
			$manager->deleteAllCategories($name);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#updateCategory($name, $categoryId, $categoryName)
	 */
	public function updateCategory($name, $categoryId, $categoryName) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($categoryId, '$categoryId must not be an empty string.');
			$this->assertNotEmpty($categoryName, '$categoryName must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_CategoryCreateEditManager();
			$manager->updateCategory($name, $categoryId, $categoryName);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#getRecordsByCategory($name, $categoryId)
	 */
	public function getRecordsByCategory($name, $categoryId) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($name, '$name must not be an empty string.');
			$this->assertNotEmpty($categoryId, '$categoryId must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_RecordReadManager();
			$manager->getRecordsByCategory($name, $categoryId);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#deploy($name, $serviceId, $serviceName)
	 */
	public function deploy($name, $serviceId, $serviceName) {
		$resourceClient = new ResourceClient();
		$result = $resourceClient->deploy($name, $serviceId, $serviceName);
		$this->updateResourceTimestamp($name);
		return $result;
	}

	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#undeploy($name)
	 */
	public function undeploy($name) {
		$resourceClient = new ResourceClient();
		$result = $resourceClient->undeploy($name);
		$this->updateResourceTimestamp($name);
		return $result;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/ITranslationTemplateClient#addDefaultBoundWordSet($type)
	 */
	public function addDefaultBoundWordSet($type) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		
		try {
			$this->assertNotEmpty($type, '$id must not be an empty string.');
			
			$manager = new Toolbox_TranslationTemplate_BoundWordSetCreateEditManager();
			$contents = $manager->addDefaultBoundWordSet($type);
		} catch (Toolbox_SQLException $e) {
			$status = 'ERROR';
			$message = basename($e->getFile()).' at line '.$e->getLine();
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		
		return $this->buildResponse($status, $message, $contents);
	}
	
	public function createNgramFromExpression($limit = 500) {
		
		require_once XOOPS_ROOT_PATH.'/api/class/client/TranslationTemplateClient.class.php';
		require_once XOOPS_ROOT_PATH.'/api/class/manager/translation_template/ngram_converter.php';
		
		$root =& XCube_Root::getSingleton();
		$db = $root->mController->mDB;
		$handler = new TranslationTemplate_TranslationTemplateExpressionsHandler($db);

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria("ngram", null));
		$expressions = $handler -> getObjects($criteria, $limit);
		
		$count = count($expressions); 
		$i = 0;

		$p = new NgramConverter();		
		foreach($expressions as $exp) {

			$value = mysql_real_escape_string($exp->get('expression'));
			$ngramvalue = $value;
			$language = $exp->get('language_code');

			if($this -> isNgramLanguage($language)) {
				$ngramvalue = mysql_real_escape_string($p->to_fulltext( $exp->get('expression'), 2 ));				
			}
	
			$sql =<<<SQL
UPDATE
	`{$db->prefix}_template_translation_template_expressions`
SET
	ngram = '{$ngramvalue}'
WHERE
	expression = '{$value}'
SQL;

			$result = $db->queryF($sql);
			if($result <  1) {
				echo $sql;
				echo "<br>";
			}
			$i += $result;
		}
		
		return array($count, $i);

	}
	
	private function isNgramLanguage($language) {
		return $language == "ja" || $language == "zh-CN" || $language == "zh-TW" || $language == "zh";
	}
	
	/**
	 * 
	 * @param String $name
	 * @return void
	 */
	private function updateResourceTimestamp($name) {
		$manager = new Toolbox_Resource_CreateEditManager();
		$manager->updateTime($name);
	}
	
	/**
	 * 
	 * @param String $matchingMethod
	 * @throws Exception
	 * @return unknown_type
	 */
	private function assertMatchingMethod($matchingMethod) {
		$matchingMethods = array(
			'complete', 'prefix', 'suffix', 'partial'
		);
		
		if (!in_array($matchingMethod, $matchingMethods)) {
			throw new Exception('$matchingMethod must be a (complete/prefix/suffix/partial).');
		}
	}
	
	/**
	 * 
	 * @param String $matchingMethod
	 * @throws Exception
	 * @return unknown_type
	 */
	private function assertMatchingAndor($matchingAndor) {
		$matchingMethods = array(
			'AND', 'OR', 'fulltext'
		);
		
		if (!in_array($matchingAndor, $matchingMethods)) {
			throw new Exception('$matchingAndor must be a (AND/OR/fulltext).');
		}
	}
	
	/**
	 * 
	 * @param mixed $expressions
	 * @throws Exception
	 * @return void
	 */
	private function assertExpressions($expressions, $arg = '$expressions') {
		$this->assertType('array', $expressions, $arg.' must be an array.');
		$this->assertGreaterThan(0, count($expressions), $arg.' count must be greater than 0.');
		$this->assertContainsOnly('ToolboxVO_Resource_Expression', $expressions, $arg.' must contain only a ToolboxVO_Resource_Expression.');
	}
	
	/**
	 * 
	 * @param mixed $expressions
	 * @throws Exception
	 * @return void
	 */
	private function assertWordSetIds($wordSetIds) {
		$this->assertType('array', $wordSetIds, '$wordSetIds must be an array.');
//		$this->assertGreaterThan(0, count($wordSetIds), '$$wordSetIds count must be greater than 0.');
//		$this->assertContainsOnly('ToolboxVO_Resource_Expression', $expressions, '$expressions\' must contain only a ToolboxVO_Resource_Expression.');
	}
	
	/**
	 * 
	 * @param mixed $boundWords
	 * @throws Exception
	 * @return void
	 */
	private function assertBoundWords($boundWords) {
		$this->assertType('array', $boundWords, '$boundWords must be an array.');
		$this->assertContainsOnly('ToolboxVO_TranslationTemplate_BoundWord', $boundWords, '$boundWords\' must contain only a ToolboxVO_TranslationTemplate_BoundWord.');
	}
}
?>

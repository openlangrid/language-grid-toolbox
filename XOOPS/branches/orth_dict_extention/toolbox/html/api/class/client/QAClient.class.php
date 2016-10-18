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
require_once(dirname(__FILE__).'/../../IQAClient.interface.php');
require_once(dirname(__FILE__).'/Toolbox_AbstractClient.class.php');
require_once(dirname(__FILE__).'/ResourceClient.class.php');

require_once dirname(__FILE__).'/../manager/Toolbox_QA_RecordCreateEditManager.class.php';
require_once dirname(__FILE__).'/../manager/Toolbox_QA_RecordReadManager.class.php';
require_once dirname(__FILE__).'/../manager/Toolbox_QA_CategoryCreateEditManager.class.php';
require_once dirname(__FILE__).'/../manager/Toolbox_QA_CategoryReadManager.class.php';

class QAClient extends Toolbox_AbstractClient implements IQAClient {

	protected $resourceClient;
	protected $m_selectedLanguage;

	public function __construct() {
		$this->init();
	}
	
	private function init() {
		if (isset($_COOKIE["selectedLanguage"])) {
			$this->m_selectedLanguage = $_COOKIE["selectedLanguage"];
		} else {
			$this->m_selectedLanguage = 'en';
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#getAllRecords($name, $sortOrder, $orderBy, $offset, $limit)
	 */
	public function getAllRecords($name, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = array();
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$manager = new Toolbox_QA_RecordReadManager();
			$contents = $manager->getAllRecords($name, $sortOrder, $orderBy, $offset, $limit);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#getRecordsByCategory($name, $categoryId)
	 */
	public function getRecordsByCategory($name, $categoryId) {
		$status = 'OK';
		$message = 'Success';
		$contents = array();
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertNotEmpty($categoryId, 'Category ID is invalid');
			$manager = new Toolbox_QA_RecordReadManager();
			$contents = $manager->getRecordsByCategoryId($categoryId);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#getRecord($name, $id)
	 */
	public function getRecord($name, $id) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertNotEmpty($id, 'ID is invalid');
			$manager = new Toolbox_QA_RecordReadManager();
			$contents = $manager->getRecord($id);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#addRecord($name, $question, $answers, $categoryIds)
	 */
	public function addRecord($name, $question, $answers, $categoryIds = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertExpressionComposite($question, 'Question is invalid');
			$this->assertAnswerComposite($answers, 'Answers is invalid');
			$manager = new Toolbox_QA_RecordCreateEditManager();
			$contents = $manager->addRecord($name, $question, $answers, $categoryIds);
			$this->updateResource($name);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#deleteRecord($name, $id)
	 */
	public function deleteRecord($name, $id) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertNotEmpty($id, 'ID is invalid');
			$manager = new Toolbox_QA_RecordCreateEditManager();
			$manager->deleteRecord($id);
			$this->updateResource($name);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#deleteAllRecord($name)
	 */
	public function deleteAllRecords($name) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertNotEmpty($id, 'ID is invalid');
			$manager = new Toolbox_QA_RecordCreateEditManager();
			$manager->deleteAllRecords($name);
			$this->updateResource($name);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#updateRecord($name, $recordId, $question, $answers, $categoryIds)
	 */
	public function updateRecord($name, $recordId, $question, $answers, $categoryIds = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertNotEmpty($recordId, 'Record ID is invalid');
			$this->assertExpressionComposite($question, 'Question is invalid');
			$this->assertAnswerComposite($answers, 'Answers is invalid');
			
			$manager = new Toolbox_QA_RecordCreateEditManager();
			$contents = $manager->updateRecord($recordId, $question, $answers, $categoryIds);
			
			$this->updateResource($name);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#addCategory($name, $categoryName)
	 */
	public function addCategory($name, $categoryName ,$language) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertNotEmpty($categoryName, 'Category name is invalid');
			$this->assertExpressionComposite($categoryName, 'Category name is invalid');
			$this->assertNotEmpty($language, 'Language is invalid');
			$manager = new Toolbox_QA_CategoryCreateEditManager();
			$contents = $manager->addCategory($name, $language, $categoryName);
			$this->updateResource($name);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#deleteCategory($name, $categoryId)
	 */
	public function deleteCategory($name, $categoryId) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertNotEmpty($categoryId, 'Category ID is invalid');
			$manager = new Toolbox_QA_CategoryCreateEditManager();
			$manager->deleteCategory($categoryId);
			$this->updateResource($name);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}

	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#updateCategory($name, $caegoryId, $categoryName)
	 */
	public function updateCategory($name, $categoryId, $categoryName) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertNotEmpty($categoryId, 'Category ID is invalid');
			$this->assertNotEmpty($categoryName, 'Category name is invalid');
			$this->assertExpressionComposite($categoryName, 'Category name is invalid');
			$manager = new Toolbox_QA_CategoryCreateEditManager();
			$manager->updateCategory($categoryId, $categoryName);
			$this->updateResource($name);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#getAllCategories($name)
	 */
	public function getAllCategories($name) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$manager = new Toolbox_QA_CategoryReadManager();
			$contents = $manager->getAllCategories($name);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#getCategory($name, $categoryId)
	 */
	public function getCategory($name, $categoryId) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertNotEmpty($categoryId, 'Category ID is invalid');
			$manager = new Toolbox_QA_CategoryReadManager();
			$contents = $manager->getCategory($categoryId);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#searchRecord($name, $word, $language, $matchingMethod, $categoryIds, $scope, $offset, $limit)
	 */
	public function searchRecord($name, $word, $language, $matchingMethod
			, $categoryIds = null, $scope = null, $sortOrder = null
			, $orderBy = null, $offset = null, $limit = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertNotEmpty($word, 'Word is invalid');
			$this->assertNotEmpty($language, 'Language is invalid');
			$this->assertNotEmpty($matchingMethod, 'Matching method is invalid');
			$this->assertMatchingMethod($matchingMethod, 'Matching method is invalid');
			$manager = new Toolbox_QA_RecordReadManager();
			$contents = $manager->searchRecord($name, $word, $language
					, $matchingMethod, $categoryIds, $scope, $sortOrder
					, $orderBy, $offset, $limit);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#countRecords($name, $word, $language, $matchingMethod, $categoryIds, $scope, $sortOrder, $orderBy)
	 */
	public function countRecords($name, $word, $language, $matchingMethod
			, $categoryIds = null, $scope = null, $sortOrder = null
			, $orderBy = null) {
		$status = 'OK';
		$message = 'Success';
		$contents = null;
		try {
			$this->assertNotEmpty($name, 'Name is invalid');
			$this->assertNotEmpty($word, 'Word is invalid');
			$this->assertNotEmpty($language, 'Language is invalid');
			$this->assertNotEmpty($matchingMethod, 'Matching method is invalid');
			$this->assertMatchingMethod($matchingMethod, 'Matching method is invalid');
			$manager = new Toolbox_QA_RecordReadManager();
			$contents = $manager->countRecords($name, $word, $language
					, $matchingMethod, $categoryIds, $scope, $sortOrder
					, $orderBy);
		} catch (Exception $e) {
			$status = 'ERROR';
			$message = $e->getMessage();
		}
		return $this->getResponse($status, $message, $contents);
	}

	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#deploy($name, $serviceId, $serviceName)
	 */
	public function deploy($name, $serviceId, $serviceName) {
		$resourceClient = new ResourceClient();
		$this->updateResource($name);
		return $resourceClient->deploy($name, $serviceId, $serviceName);
	}

	/**
	 * (non-PHPdoc)
	 * @see html/api/IQAClient#undeploy($name)
	 */
	public function undeploy($name) {
		$resourceClient = new ResourceClient();
		$this->updateResource($name);
		return $resourceClient->undeploy($name);
	}

	/**
	 * 
	 * @param String $status
	 * @param String $message
	 * @param mixed $contens
	 * @return array
	 */
	protected function getResponse($status, $message, $contents) {
		return array(
			'status' => $status,
			'message' => $message,
			'contents' => $contents
		);
	}
	
	/**
	 * 
	 * @param mixed $value
	 * @param String $message optional
	 * @throws Exception
	 * @return void
	 */
	protected function assertNotEmpty($value, $message) {
		if ($value == null || $value == '') {
			throw new Exception($message);
		}
	}
	
	/**
	 * 
	 * @param String $matchingMethod
	 * @param String $message
	 * @throws Exception
	 * @return void
	 */
	protected function assertMatchingMethod($matchingMethod, $message) {
		$supportedMatchingMethods = array(
			'complete', 'prefix', 'partial', 'suffix'//, 'regex'
		);
		if (!in_array($matchingMethod, $supportedMatchingMethods)) {
			throw new Exception($message);
		}
	}
	
	/**
	 * 
	 * @param mixed $value
	 * @param String $message optional
	 * @throws Exception
	 * @return void
	 */
	protected function assertExpressionComposite($value, $message) {
		if (is_array($value)) {
			foreach ($value as $v) {
				$this->assertExpressionComposite($v, $message);
			}
		} else if (!is_a($value, 'ToolboxVO_Resource_Expression')) {
			throw new Exception($message);
		}
	}
	
	/**
	 * 
	 * @param mixed $value
	 * @param String $message optional
	 * @throws Exception
	 * @return void
	 */
	protected function assertAnswerComposite($value, $message) {
		if (is_array($value)) {
			foreach ($value as $v) {
				$this->assertAnswerComposite($v, $message);
			}
		} else if (!is_a($value, 'ToolboxVO_QA_Answer')) {
			throw new Exception($message);
		}
	}

	/**
	 * 
	 * @param String $name
	 * @return void
	 */
	protected function updateResource($name) {
		$manager = new Toolbox_Resource_CreateEditManager();
		$manager->updateTime($name);
	}
}
?>

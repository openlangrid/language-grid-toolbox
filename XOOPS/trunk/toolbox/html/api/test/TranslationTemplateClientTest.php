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
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8;');


require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/ResultPrinter.php';

require_once dirname(__FILE__).'/../../mainfile.php';
require_once dirname(__FILE__).'/../class/client/TranslationTemplateClient.class.php';
require_once dirname(__FILE__).'/../class/client/ResourceClient.class.php';

class TranslationTemplateClientTest extends PHPUnit_Framework_TestCase {
	
	private $recordCount = 0;
	private $boundWordSetCount = 0;
	private $boundWordCount = 0;
	private $categoryCount = 0;
	
	private $resourceName;
	private $languages = array('ja', 'en', 'ko', 'zh');
	
	/**
	 * <#if lang="ja">
	 * テスト用のリソースを作成する
	 * </#if>
	 */
	public function begin() {
		$this->resourceClient = new ResourceClient();
		$this->client = new TranslationTemplateClient();
		$this->resourceName = 'TranslationTemplateClientTest';
		
//		$result = $this->createLanguageResource();
//		$this->assertEquals($result['status'], 'OK');
	}
	
	/**
	 * <#if lang="ja">
	 * テスト用のリソースを削除する
	 * </#if>
	 */
	public function end() {
//		$this->deleteLanguageResource();
	}
	
	/**
	 * <#if lang="ja">
	 * テストを実行する
	 * </#if>
	 * @return unknown_type
	 */
	public function startTest($scope = '') {
		$this->initCount();
		
		$reflection = new ReflectionClass('TranslationTemplateClientTest');
		
		foreach ($reflection->getMethods() as $m) {
			if (!$this->isTestMethod($m->name, $scope)) {
				continue;
			}
			
			$this->testCount++;
			
			try {
				$this->{$m->name}();
			} catch (Exception $e) {
				var_dump($e);
				$this->errorCount++;
			}
		}
	}
	
	private function initCount() {
		$this->errorCount = 0;
		$this->testCount = 0;
	}
	
	private function isTestMethod($method, $scope = '') {
		return preg_match('/^test'.ucfirst($scope).'.+$/i', $method);
	}
	
	public function printResult() {
		echo '<table style="border-collapse: collapse; border-spacing: 0; border: 1px solid #000; width: 300px; height: 40px; color: #000; text-align: center;">';
		echo '<tr>';
		if ($this->testCount != $this->errorCount) {
			echo '<td style="background: #0f0; width:'.(($this->testCount-$this->errorCount)/$this->testCount*300).';"></td>';
		}
		if ($this->errorCount > 0) {
			echo '<td style="background:#f00;"></td>';
		}
		echo '<td>'.($this->testCount-$this->errorCount).'/'.$this->testCount.'</td>';
		echo '</tr>';
		echo '</table>';
	}
	
	/**
	 * <#if lang="ja">
	 * テスト用のリソースを作成する
	 * </#if>
	 */
	public function createLanguageResource() {
//		$this->resourceName = 'TEST'.time();
		$permission = new ToolboxVO_Resource_Permission();
		$permission->userId = 1;
		$permission->type = 'public';
		
		$result = $this->resourceClient->createLanguageResource($this->resourceName, 'TRANSLATION_TEMPLATE', $this->languages, $permission, $permission);
//		$this->assertEquals($result['status'], 'OK');
		
		return $result;
	}
	
	/**
	 * 
	 * <#if lang="ja">
	 * テスト用のリソースを削除する
	 * </#if>
	 * 
	 * @return unknown_type
	 */
	public function deleteLanguageResource() {
		$result = $this->resourceClient->deleteLanguageResource($this->resourceName);
//		$this->assertEquals($result['status'], 'OK');
	}
	
	/**
	 * <#if lang="ja">
	 * ToolboxVO_Resource_Expressionを返す
	 * </#if>
	 */
	private function createExpressions() {
		$return = array();
		
		foreach ($this->languages as $language) {
			$e = new ToolboxVO_Resource_Expression();
			$e->language = $language;
			$e->expression = 'Expression in '.$language;
			$return[] = $e;
		}
		
		return $return;
	}
	
	/**
	 * <#if lang="ja">
	 * Recordを作成する
	 * </#if>
	 */
	private function createRecord() {
		$expressions = $this->createExpressions();
		$result = $this->client->addRecord($this->resourceName, $expressions, array());
		$this->recordCount++;
		
		$this->assertEquals($result['status'], 'OK');
		
		return $result;
	}
	
	/**
	 * <#if lang="ja">
	 * Recordを削除する
	 * </#if>
	 */
	private function deleteRecord($id) {
		$result = $this->client->deleteRecord($this->resourceName, $id);
		$this->recordCount--;
		
		$this->assertEquals($result['status'], 'OK');
		
		return $result;
	}
	
	/**
	 * <#if lang="ja">
	 * BoundWordSetを作成する
	 * </#if>
	 */
	private function createBoundWordSet() {
		$expressions = $this->createExpressions();
		$result = $this->client->addBoundWordSet($this->resourceName, $expressions);
		$this->boundWordSetCount++;
		$this->assertEquals($result['status'], 'OK');
		
		return $result;
	}
	
	/**
	 * <#if lang="ja">
	 * BoundWordSetを削除する
	 * </#if>
	 */
	private function deleteBoundWordSet($id) {
		$result = $this->client->deleteBoundWordSet($this->resourceName, $id);
		$this->boundWordSetCount--;
		
		$this->assertEquals($result['status'], 'OK');
		
		return $result;
	}
	
	/**
	 * <#if lang="ja">
	 * BoundWordを作成する
	 * </#if>
	 */
	private function createBoundWord($setId) {
		$expressions = $this->createExpressions();
		$result = $this->client->addBoundWord($this->resourceName, $setId, $expressions);
		$this->boundWordCount++;
		
		$this->assertEquals($result['status'], 'OK');
		return $result;
	}
	
	/**
	 * <#if lang="ja">
	 * BoundWordSetを削除する
	 * </#if>
	 */
	private function deleteBoundWord($setId, $id) {
		$result = $this->client->deleteBoundWord($this->resourceName, $setId, $id);
		$this->boundWordCount--;
		
		$this->assertEquals($result['status'], 'OK');
		return $result;
	}
	
	/**
	 * <#if lang="ja">
	 * Categoryを作成する
	 * </#if>
	 */
	private function createCategory() {
		$expressions = $this->createExpressions();
		$result = $this->client->addCategory($this->resourceName, $expressions);
		$this->categoryCount++;
		$this->assertEquals($result['status'], 'OK');
		return $result;
	}
	
	/**
	 * <#if lang="ja">
	 * Categoryを削除する
	 * </#if>
	 */
	private function deleteCategory($categoryId) {
		$result = $this->client->deleteCategory($this->resourceName, $categoryId);
		$this->categoryCount--;
		
		$this->assertEquals($result['status'], 'OK');
		return $result;
	}
	
	/***************************
	 * 
	 * Test getAllRecords
	 * 
	 **************************/
//	public function testGetAllRecords1() {
//		$result = $this->client->getAllRecords($this->resourceName);
//		$this->assertEquals($result['status'], 'OK');
//		$this->assertEquals(count($result['contents']), $this->recordCount);
//		$this->assertTrue(is_array($result['contents']));
//	}

	public function testGetAllRecords2() {
		for ($i = 0; $i < 5; $i++) {
			$this->createRecord();
		}
		
		$result = $this->client->getAllRecords($this->resourceName, null, null, 0, 3);
		
		$this->assertEquals($result['status'], 'OK');
		$this->assertTrue(is_array($result['contents']));
		$this->assertEquals(count($result['contents']), 3);
	}

	public function testGetAllRecords3() {
		for ($i = 0; $i < 5; $i++) {
			$this->createRecord();
		}
		
		$result = $this->client->getAllRecords($this->resourceName, null, null, 3, 1);
		
		$this->assertEquals($result['status'], 'OK');
		$this->assertTrue(is_array($result['contents']));
		$this->assertEquals(count($result['contents']), 1);
	}

	public function testGetAllRecords4() {
		for ($i = 0; $i < 5; $i++) {
			$this->createRecord();
		}
		
		$result = $this->client->getAllRecords($this->resourceName, null, null, 0, 5);
		$this->assertEquals($result['status'], 'OK');
		$this->assertTrue(is_array($result['contents']));
		$this->assertEquals(count($result['contents']), 5);
	}

	public function testGetAllRecords5() {
		for ($i = 0; $i < 5; $i++) {
			$this->createRecord();
		}
		
		$result = $this->client->getAllRecords($this->resourceName);
		$this->assertEquals($result['status'], 'OK');
		$this->assertTrue(is_array($result['contents']));
		
		foreach ($result['contents'] as $c) {
			$this->assertType('ToolboxVO_TranslationTemplate_TranslationTemplateRecord', $c);
		}
	}
	
	/***************************
	 * 
	 * Test getRecord
	 * 
	 **************************/
	public function testGetRecord1() {
		$result1 = $this->createRecord();
		$result2 = $this->client->getRecord($this->resourceName, $result1['contents']->id);
		
		$this->assertEquals($result2['status'], 'OK');
		$this->assertEquals($result1['contents']->id, $result2['contents']->id);
	}
	
	/***************************
	 * 
	 * Test addRecord
	 * 
	 **************************/
	public function testAddRecord1() {
		$result = $this->createRecord();
		$this->assertEquals($result['status'], 'OK');
	}
	
	/***************************
	 * 
	 * Test deleteRecord
	 * 
	 **************************/
	public function testDeleteRecord1() {
		$result1 = $this->createRecord();
		$result2 = $this->deleteRecord($result1['contents']->id);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test deleteAllRecords
	 * 
	 **************************/
	public function testDeleteAllRecords1() {
		$result = $this->client->deleteAllRecords($this->resourceName);
		$this->recordCount = 0;
		
		$this->assertEquals($result['status'], 'OK');
	}

	/***************************
	 * 
	 * Test updateRecord
	 * 
	 **************************/
	public function testUpdateRecord1() {
		$result1 = $this->createRecord();
		$result2 = $this->client->updateRecord($this->resourceName, $result1['contents']->id, $result1['contents']->expressions, array());
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test searchRecord
	 * 
	 **************************/
	public function testSearchRecord1() {
		$result = $this->client->searchRecord($this->resourceName, 'in', 'en', 'partial');
		
		$this->assertEquals($result['status'], 'OK');
	}
	
	public function testSearchRecord2() {
		for ($i = 0; $i < 5; $i++) {
			$this->createRecord();
		}
		
		$result = $this->client->searchRecord($this->resourceName, 'in', 'en', 'partial');
		
		$this->assertEquals(count($result['contents']), $this->recordCount);
		$this->assertEquals($result['status'], 'OK');
	}

	/***************************
	 * 
	 * Test countRecords
	 * 
	 **************************/
	public function testCountRecords1() {
		$result = $this->client->countRecords($this->resourceName, 'in', 'en', 'partial');
		
		$this->assertEquals($result['status'], 'OK');
	}

	/***************************
	 * 
	 * Test fillTranslationTemplate
	 * 
	 **************************/
	public function testFillTranslationTemplate1() {
		$result1 = $this->createRecord();
		$result2 = $this->client->fillTranslationTemplate($this->resourceName, $result1['contents']->id, array());
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test getAllBoundWordSets
	 * 
	 **************************/
	public function testGetAllBoundWordSets1() {
		$result = $this->client->getAllBoundWordSets($this->resourceName);
		
		$this->assertEquals($result['status'], 'OK');
	}

	/***************************
	 * 
	 * Test getBoundWordSet
	 * 
	 **************************/
	public function testGetBoundWordSet1() {
		$result1 = $this->createBoundWordSet();
		$result2 = $this->client->getBoundWordSet($this->resourceName, $result1['contents']->id);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test addBoundWordSet
	 * 
	 **************************/
	public function testAddBoundWordSet1() {
		$result = $this->createBoundWordSet();
		
		$this->assertEquals($result['status'], 'OK');
	}

	/***************************
	 * 
	 * Test deleteBoundWordSet
	 * 
	 **************************/
	public function testDeleteBoundWordSet1() {
		$result1 = $this->createBoundWordSet();
		$result2 = $this->deleteBoundWordSet($result1['contents']->id);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test deleteAllBoundWordSets
	 * 
	 **************************/
	public function testDeleteAllBoundWordSet1() {
		$result = $this->client->deleteAllBoundWordSets($this->resourceName);
		
		$this->assertEquals($result['status'], 'OK');
	}

	/***************************
	 * 
	 * Test updateBoundWordSet
	 * 
	 **************************/
	public function testUpdateBoundWordSet1() {
		$result1 = $this->createBoundWordSet();
		$result2 = $this->client->updateBoundWordSet($this->resourceName, $result1['contents']->id, $result1['contents']->name);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test getAllBoundWords
	 * 
	 **************************/
	public function testGetAllBoundWords1() {
		$result1 = $this->createBoundWordSet();
		
		for ($i = 0; $i < 5; $i++) {
			$this->createBoundWord($result1['contents']->id);
		}
		
		$result2 = $this->client->getAllBoundWords($this->resourceName, $result1['contents']->id);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test getBoundWord
	 * 
	 **************************/
	public function testGetBoundWord1() {
		$result1 = $this->createBoundWordSet();
		$result2 = $this->createBoundWord($result1['contents']->id);
		$result3 = $this->client->getBoundWord($this->resourceName, $result1['contents']->id, $result2['contents']->id);
		
		$this->assertEquals($result3['status'], 'OK');
	}

	/***************************
	 * 
	 * Test addBoundWord
	 * 
	 **************************/
	public function testAddBoundWord1() {
		$result1 = $this->createBoundWordSet();
		$result2 = $this->createBoundWord($result1['contents']->id);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test deleteBoundWord
	 * 
	 **************************/
	public function testDeleteBoundWord1() {
		$result1 = $this->createBoundWordSet();
		$result2 = $this->createBoundWord($result1['contents']->id);
		$result3 = $this->deleteBoundWord($result1['contents']->id, $result2['contents']->id);
		
		$this->assertEquals($result3['status'], 'OK');
	}

	/***************************
	 * 
	 * Test deleteAllBoundWords
	 * 
	 **************************/
	public function testDeleteAllBoundWords1() {
		$result1 = $this->createBoundWordSet();
		$result2 = $this->createBoundWord($result1['contents']->id);
		$result3 = $this->client->deleteAllBoundWords($this->resourceName, $result1['contents']->id);
		
		$this->assertEquals($result3['status'], 'OK');
	}

	/***************************
	 * 
	 * Test updateBoundWord
	 * 
	 **************************/
	public function testUpdateBoundWord1() {
		$result1 = $this->createBoundWordSet();
		$result2 = $this->createBoundWord($result1['contents']->id);
		$result3 = $this->client->updateBoundWord($this->resourceName, $result1['contents']->id, $result1['contents']->id, $result2['contents']->expressions);
		
		$this->assertEquals($result3['status'], 'OK');
	}

	/***************************
	 * 
	 * Test getAllCategories
	 * 
	 **************************/
	public function testGetAllCategories1() {
		for ($i = 0; $i < 5; $i++) {
			$this->createCategory();
		}
		
		$result2 = $this->client->getAllCategories($this->resourceName);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test getCategory
	 * 
	 **************************/
	public function testGetCategory1() {
		$result1 = $this->createCategory();
		$result2 = $this->client->getCategory($this->resourceName, $result1['contents']->id);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test addCategory
	 * 
	 **************************/
	public function testAddCategory1() {
		$result1 = $this->createCategory();
		
		$this->assertEquals($result1['status'], 'OK');
	}

	/***************************
	 * 
	 * Test deleteCategory
	 * 
	 **************************/
	public function testDeleteCategory1() {
		$result1 = $this->createCategory();
		$result2 = $this->deleteCategory($result1['contents']->id);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test deleteAllCategories
	 * 
	 **************************/
	public function testDeleteAllCategories1() {
		for ($i = 0; $i < 5; $i++) {
			$this->createCategory();
		}
			
		$result2 = $this->client->deleteAllCategories($this->resourceName);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test updateCategory
	 * 
	 **************************/
	public function testUpdateCategory1() {
		$result1 = $this->createCategory();
		$result2 = $this->client->updateCategory($this->resourceName, $result1['contents']->id, $result1['contents']->name);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test getRecordsByCategory
	 * 
	 **************************/
	public function testGetRecordsByCategory1() {
		$result1 = $this->createCategory();
		$result2 = $this->client->getRecordsByCategory($this->resourceName, $result1['contents']->id);
		
		$this->assertEquals($result2['status'], 'OK');
	}

	/***************************
	 * 
	 * Test deploy
	 * 
	 **************************/
//	public function testDeploy1() {
//		$result = $this->client->deploy($this->resourceName, null, null);
//		$this->assertEquals($result['status'], 'OK');
//	}

	/***************************
	 * 
	 * Test undeploy
	 * 
	 **************************/
//	public function testUndeploy1() {
//		$result = $this->client->undeploy($this->resourceName);
//		$this->assertEquals($result['status'], 'OK');
//	}
}

class PHPUnit_TextUI_HtmlResultPrinter extends PHPUnit_TextUI_ResultPrinter {
	
}

if (strtolower(basename($_SERVER['PHP_SELF'])) == strtolower(basename(__FILE__))) {
	$test = new TranslationTemplateClientTest();
	$test->begin();
	$test->createLanguageResource();
	
	$test->startTest('searchRecord');
	$test->printResult();
	
	// Cannot use under code because of DB connection closed after once test case executed.
	// I don't know why so..
//	echo '<pre>';
//	$suite = new PHPUnit_Framework_TestSuite('TranslationTemplateClientTest');
//	$result = $suite->run();
//	$printer = new PHPUnit_TextUI_HtmlResultPrinter();
//	$printer->printResult($result);
	
	$test->deleteLanguageResource();
}
?>
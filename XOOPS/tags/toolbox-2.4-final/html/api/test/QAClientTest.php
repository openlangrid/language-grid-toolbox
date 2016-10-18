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
require_once dirname(__FILE__).'/../class/client/QAClient.class.php';
require_once dirname(__FILE__).'/../class/client/ResourceClient.class.php';

class QAClientTest extends PHPUnit_Framework_TestCase {
	
	private $resourceClient;
	private $qaClient;
	
	private $categoryIds;
	
	private $resourceName = 'QA API Test';
	private $type = 'QA';
	private $languages = array('en', 'ja', 'zh');
	
	public function setup() {
		$this->resourceClient = new ResourceClient();
		$this->qaClient = new QAClient();
	}
	
	public function tearDown() {
		$this->resourceClient->deleteLanguageResource($this->resourceName);
	}
	
	private function createResource() {

		$permission = new ToolboxVO_Resource_Permission();
		$permission->type = 'public';
		
		$this->resourceClient->createLanguageResource(
			$this->resourceName, $this->type, $this->languages,
			$permission, $permission);
	}
	
	private function testGetAllCategories() {
		$this->resourceClient->getAllCategories();
	}
	
	private function testAddCategory() {
		
	}
	
}

if (strtolower(basename($_SERVER['PHP_SELF'])) == strtolower(basename(__FILE__))) {
	echo '<pre>';
	$suite = new PHPUnit_Framework_TestSuite('QAClientTest');
	$result = $suite->run();
	$printer = new PHPUnit_TextUI_ResultPrinter();
	$printer->printResult($result);
}
?>
<?php

require_once 'PHPUnit/Framework.php';

class ToolboxTest extends PHPUnit_Framework_TestCase {
	public function testTest() {
		$fix = array();
		$this->assertEquals(0, count($fix));
		$this->assertEquals(1, count($fix));
		$this->assertEquals(0, count($fix));
	}
}

?>
<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once 'PHPUnit/Framework.php';

class Test extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	function test() {
		$var = 'sssss';
		self::assertEquals('aaaaa', preg_replace('/s/', 'a', $var));
	}
}

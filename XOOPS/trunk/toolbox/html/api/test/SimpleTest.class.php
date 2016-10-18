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
class SimpleTest {
	public function run() {
	}
	public function out($string) {
		echo '<h1 style="color:green;">'.$string.'</h1>';
	}
	public function err($string) {
		echo '<h1 style="color:red;">'.$string.'</h1>';
	}
	public function dump($value, $string) {
		$this->out($string);
		var_dump($value);
	}
	public function statusCheck($result) {
		$this->assertEquals($result['status'], 'OK');
	}
	public function printR($value) {
		echo '<pre>';
		print_r($value);
		echo '</pre>';
	}
	public function assertEquals($value1, $value2, $message = 'error') {
		if ($value1 !== $value2) {
			$this->err($message);
		}
	}
	public static function main() {
		
	}
}
?>
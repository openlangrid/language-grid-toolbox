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

require_once('SOAP/Value.php');

class Translation{
	public $headWord = '';
	public $targetWords = array();

	public function __construct($headWord = '', $targetWords = array()) {
		$this->headWord = $headWord;
//		$this->targetWords = $targetWords;

		$tws = array();
		foreach ($targetWords as $targetWord) {
			$s = new SOAP_Value('targetWords', 'string', $targetWord);
			$tws[] = $s;
		}
		$this->targetWords = $tws;
	}
}
?>

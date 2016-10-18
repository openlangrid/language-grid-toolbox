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
class ParameterValidator{
	private $matchingMethods = array(
		"complete", "partial", "prefix", "suffix", "regexp"
	);

	public function getMatchingMethods(){
		return $this->matchingMethods;
	}

	public function validateNull($validatable){
		return isset($validatable) && $validatable != null && $validatable != '';
	}

	public function validateLanguageCode($validatable){
		return true;
	}

	public function validateMatchingMethod($validatable){
		foreach($this->matchingMethods as $method){
			if(strcasecmp($method, $validatable) == 0){
				return true;
			}
		}
		return false;
	}
}
?>

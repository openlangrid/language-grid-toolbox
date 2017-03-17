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
require_once(dirname(__FILE__) . '/../defines.php');
require_once(dirname(__FILE__) . '/../database/DictionaryDAO.php');
require_once(dirname(__FILE__) . '/../validator/ParameterValidator.php');
require_once(dirname(__FILE__) . '/../exception/InvalidParameterException.php');

class DictionaryService {
	private $dao;
	protected $__dispatch_map;

	public function __construct($dictionaryName, $typeId){
		$pv = new ParameterValidator();
		if(!$pv->validateNull($dictionaryName)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('dictionaryName', $dictionaryName, php_uname("n"));
			throw $ipe;
		}
		$this->dao = new DictionaryDAO($this->doClean($dictionaryName), $typeId);
	}

	protected function doSearch($headLang, $targetLang, $headWord, $matchingMethod) {
		$sources = $this->dao->searchSourceWords(
				$headLang, $headWord, $matchingMethod, 0, SEARCH_RESULT_LIMIT);
		$targets = $this->dao->searchSameRowTargetWords(
				$sources, $targetLang, 0, SEARCH_RESULT_LIMIT);
		$results = array();
		$i = 0;
		foreach($sources as $row => $contents){
			$results[$i] = array();
       		$results[$i]['source'] = $contents;
       		$results[$i++]['target'] = $targets[(int)$row];
		}
		return $results;
	}

	protected function getDao(){
		return $this->dao;
	}

	public function doClean($dValue){
		if(get_magic_quotes_gpc()) {
			$dValue = stripslashes($dValue);
		}
		return str_replace("&amp;", "&", htmlspecialchars($dValue, ENT_QUOTES, "UTF-8"));
	}
}
?>

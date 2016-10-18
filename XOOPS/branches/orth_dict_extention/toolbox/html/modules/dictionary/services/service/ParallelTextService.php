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
require_once(dirname(__FILE__) . '/DictionaryService.php');
require_once(dirname(__FILE__) . '/../model/ParallelText.php');

class ParallelTextService extends DictionaryService {
	public function __construct($dictionaryName, $typeId){
		parent::__construct($dictionaryName, $typeId);
		$this->__dispatch_map = array();
		// search
		$this->__dispatch_map['search'] = array(
			'in' => array(
				'sourceLang' => 'string'
				, 'targetLang' => 'string'
				, 'source' => 'string'
				, 'matchingMethod' => 'string'
				)
			, 'out' => array(
				'searchReturn' => '{urn:ParallelText}paralleltextArray')
			);
		// for exception defines
		$this->__typedef['stringArray'] = array(
			array('item' => 'string')
			);
		$this->__typedef['ParallelText'] = array(
			'source' => 'string'
			, 'target' => 'string'
			);
		$this->__typedef['paralleltextArray'] = array(
			array('item' => '{urn:ParallelText}ParallelText')
			);
	}

	public function search($sourceLang, $targetLang, $source, $matchingMethod){
		$pv = new ParameterValidator();

		if(!$pv->validateNull($sourceLang) || !$pv->validateLanguageCode($sourceLang)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('sourceLang', $sourceLang, php_uname("n"));
			throw $ipe;
		}
		if(!$pv->validateNull($targetLang) || !$pv->validateLanguageCode($targetLang)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('targetLang', $targetLang, php_uname("n"));
			throw $ipe;
		}
		if(!$pv->validateNull($source) || !$pv->validateLanguageCode($targetLang)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('source', $source, php_uname("n"));
			throw $ipe;
		}
		if(!$pv->validateNull($matchingMethod) || !$pv->validateMatchingMethod($matchingMethod)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('matchingMethod', $matchingMethod, php_uname("n"));
			throw $ipe;
		}

		$sourceLang = $this->doClean($sourceLang);
		$targetLang = $this->doClean($targetLang);
		$source = $this->doClean($source);
		$matchingMethod = $this->doClean($matchingMethod);

		$result = $this->doSearch($sourceLang, $targetLang, $source, $matchingMethod);
		$parallels = array();
		foreach($result as $value ){
			$parallels[] = new ParallelText($value['source'], $value['target'][0]);
		}
		return $parallels;
	}
}
?>

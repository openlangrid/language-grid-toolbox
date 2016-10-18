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
require_once(dirname(__FILE__) . '/../database/TemplateParallelTextDAO.php');
require_once(dirname(__FILE__) . '/../validator/ParameterValidator.php');
require_once(dirname(__FILE__) . '/../exception/InvalidParameterException.php');
require_once(dirname(__FILE__) . '/../model/Template.php');

class TemplateParallelTextService {
	private $dao;
	protected $__dispatch_map;
	
	public function __construct($resourceName){
		$pv = new ParameterValidator();
		if(!$pv->validateNull($resourceName)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('resourceName', $resourceName, php_uname("n"));
			throw $ipe;
		}
		
		$this->dao = new TemplateParallelTextDAO($this->doClean($resourceName));

		$this->__dispatch_map = array();
		// search
		$this->__dispatch_map['search'] = array(
			'in' => array(
				'language' => 'string',
				'text' => 'string',
				'matchingMethod' => 'string',
				'categoryIds' => array(
					'category' => array(
						'categoryId' => 'string',
						'categoryName' => 'string'
				))),
			'out' => array(
				'searchReturn' => '{urn:TemplateParallelTextService}templateArray')
			);
		// for exception defines
		$this->__typedef['stringArray'] = array(
			array('item' => 'string')
		);
		// Define ValueParameterr
		$this->__typedef['ValueParameter'] = array(
			'parameterId' => 'string',
			'type' => 'string',
			'min' => 'string',
			'max' => 'string'
		);
		$this->__typedef['valueArray'] = array(
			array('item' => '{urn:TemplateParallelTextService}ValueParameter')
		);
		// Define choices
		$this->__typedef['Choice'] = array(
			'choiceId' => 'string',
			'value' => 'string'
		);
		$this->__typedef['choiceArray'] = array(
			array('item' => '{urn:TemplateParallelTextService}Choice')
		);
		$this->__typedef['ChoiceParameter'] = array(
			'parameterId' => 'string',
			'choices' => '{urn:TemplateParallelTextService}choiceArray'
		);
		// Define category
		$this->__typedef['Category'] = array(
			'categoryId' => 'string',
			'categoryName' => 'string'
		);
		$this->__typedef['categoryArray'] = array(
			array('item' => '{urn:TemplateParallelTextService}Category')
		);
		
		$this->__typedef['Template'] = array(
			'templateId' => 'string',
			'template' => 'string',
			'choiceParameters' => '{urn:TemplateParallelTextService}choiceArray',
			'valueParameters' => '{urn:TemplateParallelTextService}valueArray',
			'categories' => '{urn:TemplateParallelTextService}categoryArray'
		);
		$this->__typedef['templateArray'] = array(
			array('item' => '{urn:TemplateParallelTextService}Template')
		);
	}

	public function searchTemplates($language, $text, $matchingMethod, $categoryIds){
		$pv = new ParameterValidator();
		if( ! $pv->validateNull($language) || ! $pv->validateLanguageCode($language)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('language', $language, php_uname("n"));
			throw $ipe;
		}
		if( ! $pv->validateNull($text)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('text', $text, php_uname("n"));
			throw $ipe;
		}
		if( ! $pv->validateNull($matchingMethod) || ! $pv->validateMatchingMethod($matchingMethod)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('matching method', $matchingMethod, php_uname("n"));
			throw $ipe;
		}
		
		$language = $this->doClean($language);
		$text = $this->doClean($text);
		$matchingMethod = $this->doClean($matchingMethod);
		
		if($categoryIds != null){
			foreach($categoryIds as $key => $value){
				$categoryIds[$key] = $this->doClean($value);
			}
		}
		
		return $this->dao->searchTemplates($language, $text, $matchingMethod, $categoryIds);
	}
	
	
	public function getTemplatesByTemplateId($language, $templateIds){
		$pv = new ParameterValidator();
		if( ! $pv->validateNull($language) || ! $pv->validateLanguageCode($language)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('language', $language, php_uname("n"));
			throw $ipe;
		}
		if( ! $pv->validateNull($templateIds)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('template ids', $templateIds, php_uname("n"));
			throw $ipe;
		}
		
		$language = $this->doClean($language);
		
		return $this->dao->getTemplatesByTemplateId($language, $templateIds);
	}
	
	public function generateSentence($language, $templateId, $boundChoiceParameters, $boundValueParameters){
		$pv = new ParameterValidator();
		if( ! $pv->validateNull($language) || ! $pv->validateLanguageCode($language)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('language', $language, php_uname("n"));
			throw $ipe;
		}
		if( ! $pv->validateNull($templateId)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('template id', $templateId, php_uname("n"));
			throw $ipe;
		}
		
		$language = $this->doClean($language);
		$templateId = $this->doClean($templateId);
		
		return $this->dao->generateSentence($language, $templateId, $boundChoiceParameters, $boundValueParameters);
	}
	
	public function listTemplateCategories($language){
		$pv = new ParameterValidator();
		if( ! $pv->validateNull($language) || ! $pv->validateLanguageCode($language)){
			$ipe = new InvalidParameterException();
			$ipe->setSoapMessage('language', $language, php_uname("n"));
			throw $ipe;
		}

		$language = $this->doClean($language);
		
		return $this->dao->listTemplateCategories($language);
	}
	
	private function doClean($dValue){
		if(get_magic_quotes_gpc()) {
			$dValue = stripslashes($dValue);
		}
		return str_replace("&amp;", "&", htmlspecialchars($dValue, ENT_QUOTES, "UTF-8"));
	}
}
?>

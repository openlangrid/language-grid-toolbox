<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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

/* $Id: translation-template-export-manager.php 5000 2011-01-07 01:25:43Z uehara $ */


require_once dirname(__FILE__).'/../../class/factory/client-factory.php';


class TranslationTemplateExportManager {

	private $mResourceName;

	private $mResourceClient = null;
	private $mModuleClient = null;

	private $mResourceResult = null;
	private $mRecordResult = null;
	private $mCategoryResult = null;
	private $mBoundWordSetResult = null;

	private $mParamBoundList = array();

	private $mOut = array();


	public function __construct($resourceName) {
		$this->mResourceName = $resourceName;
		$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
		$this->mResourceClient = $factory->createResourceClient();
		$this->mModuleClient = $factory->createModuleClient();
	}

	public function export() {
		$this->loadDatas();

		$i = 1;
		foreach ($this->mRecordResult as $record) {
			$this->mOut[$i] = array(
				'id' => $record->id,
				'type' => 'parallel_text',
				'cat' => implode(',', $record->categoryIds)
			);
			foreach ($record->expressions as $expression) {
				$text = $expression->expression;
				foreach ($record->wordSetIds as $paramId => $wordSetId) {
					$text = preg_replace('/\['.$paramId.'\]/iu', $this->makeParam($paramId, $wordSetId), $text);
				}
				$this->mOut[$i][$expression->language] = $text;
			}
			$i++;
		}

		foreach ($this->mCategoryResult as $category) {
			$this->mOut[$i] = array(
				'id' => $category->id,
				'type' => 'category',
				'cat' => ''
			);
			foreach ($category->name as $expression) {
				$this->mOut[$i][$expression->language] = $expression->expression;
			}
			$i++;
		}

		foreach ($this->mBoundWordSetResult as $boundWordSet) {
			if ($boundWordSet->type != 'enum') {
				continue;
			}
			$this->mOut[$i] = array(
				'id' => $boundWordSet->id,
				'type' => 'parameter',
				'cat' => ''
			);
			foreach ($boundWordSet->name as $expression) {
				$this->mOut[$i][$expression->language] = $expression->expression;
			}
			$i++;

			foreach ($boundWordSet->words as $word) {
				$this->mOut[$i] = array(
					'id' => $word->id,
					'type' => 'word',
					'cat' => $boundWordSet->id
				);
				foreach ($word->expressions as $expression) {
					$this->mOut[$i][$expression->language] = $expression->expression;
				}
				$i++;
			}
		}

		return $this->output();
	}


	private function loadDatas() {
		$a = $this->mResourceClient->getLanguageResource($this->mResourceName);
		if ($this->_validApiResult($a)) {
			$this->mResourceResult = $a['contents'];
		}
		$a = $this->mModuleClient->getAllRecords($this->mResourceName);
		if ($this->_validApiResult($a)) {
			$this->mRecordResult = $a['contents'];
		}
		$a = $this->mModuleClient->getAllCategories($this->mResourceName);
		if ($this->_validApiResult($a)) {
			$this->mCategoryResult = $a['contents'];
		}
		$a = $this->mModuleClient->getAllBoundWordSets($this->mResourceName);
		if ($this->_validApiResult($a)) {
			$this->mBoundWordSetResult = $a['contents'];
		}
		$this->initMakeParameterBoundTemplate();
	}

	private function initMakeParameterBoundTemplate() {
		foreach ($this->mBoundWordSetResult as $boundWordSet) {
			switch ($boundWordSet->type) {
				case 'text':
					$this->mParamBoundList[$boundWordSet->id] = '<param id="%d" type="text" />';
					break;
				case 'month':
					$this->mParamBoundList[$boundWordSet->id] = '<param id="%d" type="month" />';
					break;
				case 'date':
					$this->mParamBoundList[$boundWordSet->id] = '<param id="%d" type="day_of_month" />';
					break;
				case 'hour':
					$this->mParamBoundList[$boundWordSet->id] = '<param id="%d" type="hour" />';
					break;
				case 'minute':
					$this->mParamBoundList[$boundWordSet->id] = '<param id="%d" type="minute" />';
					break;
				case 'number':
					$this->mParamBoundList[$boundWordSet->id] = '<param id="%d" type="float" />';
					break;
				case 'enum':
				default:
					$this->mParamBoundList[$boundWordSet->id] = '<param id="%d" domains="%d" />';
					break;
			}
		}
	}

	private function makeParam($paramId, $wordSetId) {
		$template = '';
		if (isset($this->mParamBoundList[$wordSetId])) {
			$template = $this->mParamBoundList[$wordSetId];
		} else {
			$template = '<!error! id="%d" wordSetId="%d"/>';
		}
		return sprintf($template, $paramId, $wordSetId);
	}

	private function output() {
		$tsv = array();

		$head = array(
			'id', 'type', 'cat/par'
		);
		foreach ($this->mResourceResult->languages as $language) {
			$head[] = $language;
		}
		$tsv[] = implode("\t", $head);

		foreach ($this->mOut as $rowNumber => $row) {
			$brow = array();
			$brow[] = $row['id'];
			$brow[] = $row['type'];
			$brow[] = $row['cat'];

			foreach ($this->mResourceResult->languages as $language) {
				$v = isset($row[$language]) ? $row[$language] : '';
				$v = preg_replace('/\r\n|\r|\n/iu', '\\n', $v);
				$v = preg_replace('/\t/iu', '\\t', $v);
				$brow[] = $v;
			}
			$tsv[] = implode("\t", $brow);
		}

		return implode(PHP_EOL, $tsv).PHP_EOL;
	}

	private function _validApiResult($result, $exception = 'Exception') {
		if ($result == null) {
			throw new $exception();
		}
		if ($result['status'] != 'OK') {
			throw new $exception($result['message']);
		}
		return true;
	}
}
?>
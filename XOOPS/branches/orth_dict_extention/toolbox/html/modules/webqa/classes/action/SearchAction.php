<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
require_once(dirname(__FILE__).'/../Abstract_WebQABackendAction.class.php');
require_once(dirname(__FILE__).'/PostLanguageAction.php');
require_once(dirname(__FILE__).'/PostAuthorAction.php');

class SearchAction extends Abstract_WebQABackendAction {

	public function SearchAction() {

	}

	public function dispatch() {
		$context = $this->getContext();

		$offset = 0;
		$limit = 10;

		if ($this->checkParameter('num')) {
			$limit = $this->getParameter('num');
		}

		if ($this->checkParameter('page')) {
			$page = $this->getParameter('page');
			$offset = ($limit * ($page - 1));
		}

		$sortOrder = null;
		$orderBy = null;
		if ($this->checkParameter('order')) {
			switch ($this->getParameter('order')) {
				case 'date':
					$sortOrder = 'creationDate';
					$orderBy = 'desc';
					break;
				default:
					break;
			}
		}
		
		$filterZeroAnswer = false;
		if ($this->checkParameter('filterZeroAnswer')) {
			$filterZeroAnswer = !!$this->getParameter('filterZeroAnswer');
		}

		$context['records'] = $this->filterResult($context['records'], $offset, $limit, $orderBy, $sortOrder, $filterZeroAnswer);
		$context['recordCount'] = count($context['records']);

		return $context;
	}

	private function getContext() {
		require_once(XOOPS_ROOT_PATH.'/api/class/client/QAClient.class.php');
		$qaclient = new QAClient();
		
		$config = $this->getModuleConfig();
		$names = preg_split('/, ?/', $config['webqa_search']);
		$word = $this->getParameter('word');
		$language = $this->getParameter('use_lang');
		$viewLanguage = $this->getParameter('view_lang');
		$matchingMethod = 'partial';
		
		$type = $this->getParameter('type');
		$cat = $this->getParameter('cat');
		if ($type == 'all' || $cat == null) {
			$categoryIds = null;
		} else {
			$categoryIds = $cat;
		}
		$scope = 'qa';

		$context = array();
		$context['records'] = array();
		$context['recordCount'] = 0;
		foreach ($names as $name) {
			if ($word == '') {
				$recordsResult = $qaclient->getAllRecords($name);
				if ($categoryIds != null) {
					$this->applyCategoryFilter($recordsResult, $categoryIds);
				}
			} else {
				$recordsResult = $qaclient->searchRecord($name, $word, $language, $matchingMethod, $categoryIds, $scope);
			}

			if (strtoupper($recordsResult['status']) == 'OK') {
				foreach ($recordsResult['contents'] as $record) {
					$author = PostAuthorAction::getAuthor($record->id, 0);
					$context['records'][] = array(
						'resource' => $name,
						'id' => $record->id,
						'question' => $this->_getExpressionByUseLang($record->question, $viewLanguage),
						'answersnum' => count($record->answers),
						'postdate' => date("Y/m/d H:i:s", $record->creationDate),
						'updateDate' => $record->updateDate,
						'language' => PostLanguageAction::getLanguage($record->id, 0),
						'categoryIds' => $record->categoryIds,
						'author' => $author ? $author->name : null
					);
					++$context['recordCount'];
				}
			}
		}
		
		return $context;
	}
	
	private function applyCategoryFilter(&$records, $categoryIds) {
		$result = array();
		foreach ($records['contents'] as $r) {
			foreach ($categoryIds as $cid) {
				if (in_array($cid, $r->categoryIds)) {
					$result[] = $r;
					break;
				}
			}
		}
		$records['contents'] = $result;
	}
	
	public function filterResult($records, $offset, $limit, $orderBy, $sortOrder, $filterZeroAnswer) {
		$result = array();
		
		if ($orderBy) {
			usort($records, array($this, 'sortUpdateDesc'));
		}

		foreach ($records as $i => $record) {
			if (count($result) < $limit && $i >= $offset) {
				if(!$filterZeroAnswer || $this -> hasAnswer($record)) {
					$result[] = $record;				
				}
			}
		}

		return $result;
	}
	
	private function hasAnswer($record) {
		return $record['answersnum'] > 0;
	}
	
	private function sortUpdateDesc($a, $b) {
		if ($a['updateDate'] == $b['updateDate']) {
			return 0;
		}
		return ($a['updateDate'] > $b['updateDate']) ? -1 : 1;
	}
	
	private function _getExpressionByUseLang($exps, $lang) {
		foreach ($exps as $exp) {
			if ($exp->language == $lang) {
				return $exp->expression;
			}
		}
	}
}
?>

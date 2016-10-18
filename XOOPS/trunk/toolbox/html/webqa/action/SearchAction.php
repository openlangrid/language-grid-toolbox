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
class SearchAction extends AbstractAction {

	private $mForm = null;

	function dispatch(&$context) {
		// TODO: バックエンドをHTTPで呼び出して、データを取得する処理
		
		if ($this->getParameter('back') === null) {
			$useLang = $context->get('use_lang');
			$viewLang = $context->get('view_lang');
			
			$form = array(
				'use_lang' => $useLang,
				'view_lang' => $viewLang,
				'word' => $this->getParameter('word'),
				'order' => $this->getParameter('order'),
				'num' => $this->getParameter('num'),
				'page' => $this->getPage(),
				'type' => $this->getParameter('type'),
				'cat' => $this->getParameter('cat'),
				'filterZeroAnswer' => $this -> isFilterZeroAnswer()
			);
		} else if (isset($_SESSION['search_form'])) {
			$form = $_SESSION['search_form'];
		} else {
			$context->set('list', array());
			return 'default';
		}
		
		$this->search($context, $form);

		return 'default';
	}
	
	protected function search($context, $option) {
		$this->mForm = $option;
		$context->set('form', $option);
		$_SESSION['search_form'] = $option;

		$qaManager = new QAManager();
		$searchResult = $qaManager->search($option);

		$context->set('list', $searchResult['records']);

		// page
		$recordCount = $searchResult['recordCount'];

		$showPager = false;
		if ($option['num'] < $recordCount) {
			$showPager = true;
		}

		$context->set('showPager', $showPager);
		$context->set('navigation', $this->getPager($recordCount));
		$context->set('totalPages', ceil($recordCount / $option['num']));
		$context->set('resultMessage', $this->getResultMessage($recordCount));
		$context->set('view_lang', $option['view_lang']);
	}

	private function getResultMessage($recordCount) {
		require_once dirname(__FILE__).'/../classes/StringUtils.php';

		if ($recordCount > 0) {
			return StringUtils::evaluate(WQA_LB_SEARCHFORM_RESULT, $recordCount, $this->getBegin(), $this->getEnd($recordCount));
		} else {
			return WQA_LB_SEARCHFORM_NO_RESULT;
		}
	}

	private function getBegin() {
		$num = $this->mForm['num'];
		$page = $this->mForm['page'];

		return ($page - 1) * $num + 1;
	}

	private function getEnd($recordCount) {
		$num = $this->mForm['num'];
		$page = $this->mForm['page'];

		$end = $num * $page;

		return ($end > $recordCount) ? $recordCount : $end;
	}

	protected function getPage() {
		return $this->getParameter('page') ? $this->getParameter('page') : 1;
	}

	protected function getPager($recordCount) {
		require_once("Pager/Pager.php");

		$exForm = $this->mForm;
		$action = $this->getParameter('action');
		$exForm['action'] = ($action) ? $action : 'default';

		$options = array(
			'totalItems' => $recordCount,
			'perPage' => $this->mForm['num'],
			'delta' => 10,
			'currentPage' => $this->mForm['page'],
			'urlVar' => 'page',
			'prevImg' => '&lt;&lt; Previous',
			'nextImg' => 'Next &gt;&gt;',
			'clearIfVoid' => true,
			'importQuery' => false,
			'extraVars' => $exForm
		);

		$pager = Pager::factory($options);
		$navi = $pager -> getLinks();

		return $navi;
	}
}
?>

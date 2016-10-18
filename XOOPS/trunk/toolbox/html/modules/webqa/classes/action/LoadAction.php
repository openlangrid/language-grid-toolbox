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

class LoadAction extends Abstract_WebQABackendAction {

	public function LoadAction() {

	}

	public function dispatch() {
		$context = array();

		require_once(XOOPS_ROOT_PATH.'/api/class/client/QAClient.class.php');
		$qaclient = new QAClient();

		$id = $this->getParameter('id');
		$language = $this->getParameter('use_lang');

		$result =& $qaclient->getRecord('foo', $id);

		if ($result['status'] == 'OK') {
			$qa = $result['contents'];
			$authorInfo =& PostAuthorAction::getAuthor($qa->id);
			$context['id'] = $qa->id;
			$context['question'] = $this->_getExpressionByUseLang($qa->question, $language);
			$context['postdate'] = date("Y/m/d H:i:s", $qa->creationDate);
			$context['answersnum'] = count($qa->answers);
			$context['language'] = PostLanguageAction::getLanguage($qa->id);
			$context['categoryIds'] = $qa->categoryIds;
			$context['author'] = $authorInfo ? $authorInfo->name : null;
			foreach ($qa->answers as $answer) {
				$authorInfo =& PostAuthorAction::getAuthor($qa->id, $answer->id);
				$context['answers'][] = array(
					'text' => $this->_getExpressionByUseLang($answer->expression, $language),
					'date' => ($answer->creationDate) ? date("Y/m/d H:i:s", $answer->creationDate) : '-',
					'language' => PostLanguageAction::getLanguage($qa->id, $answer->id),
					'author' => $authorInfo ? $authorInfo->name : null
				);
			}
		}

		return $context;
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

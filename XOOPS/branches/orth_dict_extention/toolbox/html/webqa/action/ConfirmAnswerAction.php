<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
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

require_once dirname(__FILE__).'/ConfirmAction.php';

class ConfirmAnswerAction extends ConfirmAction {

	function dispatch(&$context) {
		// Paramterを取得
		$id = $this->getParameter('id');
		$resourceName = $this->getParameter('name');
		$useLang = $context->get('use_lang');

		$qaManager = new QAManager();
		$qa = $qaManager->load($id, $useLang);
		$context->set('qa', $qa);
		$context->set('name', $resourceName);

		$form = array(
			'id' => $id,
			'answer' => $this->getParameter('answer'),
			'use_lang' => $context->get('use_lang'),
			'author_uname' => $this->getLoginUname()
		);

		$qaManager = new QAManager();
		
		$context->set('form', $form);

		$errors = $this->validate($form);
		
		if (!empty($errors)) {
			$context->set('error_message_list', $errors);
			return 'qa';
		}

		$_SESSION['input_form'] = $form;
		return 'confirmAnswer';
	}

	protected function validate($form) {
		$errors = array();
		if (empty($form['answer'])) {
			$errors[] = StringUtils::evaluate(WQA_LB_CONFIRM_REQUIRE_ERROR, WQA_LB_QA_A_BODY);
		}
		if (mb_strwidth($form['answer'], 'UTF-8') > self::$bodyMaxLength) {
			$errors[] = StringUtils::evaluate(WQA_LB_CONFIRM_MAX_LENGTH_ERROR, WQA_LB_QA_A_BODY, self::$bodyMaxLength);
		}

		return $errors;
	}
}
?>

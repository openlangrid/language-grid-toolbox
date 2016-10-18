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
require_once dirname(__FILE__).'/../classes/StringUtils.php';

class ConfirmAction extends AbstractAction {

	public static $titleMaxLength = 60;
	public static $bodyMaxLength = 1000;

	function dispatch(&$context) {

		$form = array(
			// 'title' => $this->getParameter('title'),
			'question' => $this->getParameter('question'),
			'category' => $this->getParameter('category'),
			'use_lang' => $context->get('use_lang'),
			'author_uname' => $this->getLoginUname()
		);

		$qaManager =& new QAManager();
		$categoryName = "";
		if (isset($form['category']) && is_array($form['category'])) {
			foreach($form['category'] as $cid){
				if($categoryName != ""){$categoryName .= " , ";}
				$category = $qaManager->getCategory($cid);
				$categoryName .= $category['name'][$context->get('use_lang')];
			}
		}
		$form['category_name'] = $categoryName;

		$context->set('form', $form);

		$errors = $this->validate($form);

		if ($errors != null && is_array($errors) && count($errors) > 0) {
			$context->set('error_message_list', $errors);
			return 'question';
		}

		$_SESSION['input_form'] = $form;

		return 'confirm';
	}

	protected function validate($form) {
		$errors = array();
		//if (empty($form['title'])) {
		//	$errors[] = StringUtils::evaluate(WQA_LB_CONFIRM_REQUIRE_ERROR, WQA_LB_CONFIRM_TITLE);
		//}
		//if (mb_strwidth($form['title'], 'UTF-8') > self::$titleMaxLength) {
		//	$errors[] = StringUtils::evaluate(WQA_LB_CONFIRM_MAX_LENGTH_ERROR, WQA_LB_CONFIRM_TITLE, self::$titleMaxLength);
		//}
		if (empty($form['question'])) {
			$errors[] = StringUtils::evaluate(WQA_LB_CONFIRM_REQUIRE_ERROR, WQA_LB_CONFIRM_BODY);
		}
		if (mb_strwidth($form['question'], 'UTF-8') > self::$bodyMaxLength) {
			$errors[] = StringUtils::evaluate(WQA_LB_CONFIRM_MAX_LENGTH_ERROR, WQA_LB_CONFIRM_BODY, self::$bodyMaxLength);
		}

		return $errors;
	}
}
?>

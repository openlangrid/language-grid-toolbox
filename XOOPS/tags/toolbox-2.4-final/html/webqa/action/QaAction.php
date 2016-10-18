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
class QaAction extends AbstractAction {

	function dispatch(&$context) {
		// TODO: バックエンドをHTTPで呼び出して、データを取得する処理

		// Paramterを取得
		$id = $this->getParameter('id');
		$resourceName = $this->getParameter('name');
		$useLang = $context->get('view_lang');

		$form = array(
			'id' => $id,
			'use_lang' => $useLang,
			'answer' => ''
		);
		
		if (isset($_SESSION['input_form'])) {
			$form = array_merge($form, $_SESSION['input_form']);
		}
		
		$context->set('form', $form);
		$_SESSION['input_form'] = null;

		$qaManager =& new QAManager();
		$qa = $qaManager->load($id, $useLang);
		$context->set('qa', $qa);
		$context->set('name', $resourceName);

		return 'qa';
	}
}
?>

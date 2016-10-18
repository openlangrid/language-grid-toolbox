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

class GetCategoryAction extends Abstract_WebQABackendAction {

	public function SearchAction() {

	}

	protected function getName() {
		return null;
	}

	public function dispatch() {
		$context = array();

		require_once(XOOPS_ROOT_PATH.'/api/class/client/QAClient.class.php');
		$qaclient = new QAClient();

		$config = $this->getModuleConfig();
		$name = $config['webqa_posting'];

		$id = null;
		if ($this->checkParameter('category_id')) {
			$id = $this->getParameter('category_id');
		}

		if ($id) {
			$result =& $qaclient-> getCategory($name, $id);

			if ($result['status'] == 'OK') {
				$record = $result['contents'];
				$name = array();
				foreach ($record->name as $exp) {
					$name[$exp->language] = $exp->expression;
				}
				$context = array(
					'id' => $record->id,
					'name' => $name,
					'language' => $record->language
				);
			}
		} else {
			$result =& $qaclient-> getAllCategories($name);

			if ($result['status'] == 'OK') {
				foreach ($result['contents'] as $record) {
					$name = array();
					foreach ($record->name as $exp) {
						$name[$exp->language] = $exp->expression;
					}
					$context[] = array(
						'id' => $record->id,
						'name' => $name,
						'language' => $record->language
					);
				}
			}
		}

		return $context;
	}
}
?>

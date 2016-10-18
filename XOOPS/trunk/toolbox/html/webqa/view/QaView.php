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
require_once('ViewUtil.php');

class QaView extends AbstractView {

	function dispatch(&$context) {
		$smarty = $this->getSmarty();
		$smarty->assign('useLang', $context->get('use_lang'));
		if ($context->get('view_lang') != '') {
			$smarty->assign('viewLang', $context->get('view_lang'));
		} else {
			$smarty->assign('viewLang', $context->get('use_lang'));
		}

		$lang = $smarty->get_template_vars('viewLang');
		$categories = ViewUtil::getCategories($lang);
		$smarty->assign('categories', $categories);

		$qa = $context->get('qa', array());

		$cnames = array();
		foreach($qa['categoryIds'] as $cid) {
				$cname = $categories[$cid];
				$cnames[] = $cname;
			}
		$qa['categories'] = implode(', ', $cnames);

		$smarty->assign('qa', $qa);
		$smarty->assign('form', $context->get('form'));
		$smarty->assign('error_message_list', $context->get('error_message_list'));
		$smarty->assign('name', $context->get('name'));
		$smarty->assign('id', $context->get('id'));

		$qaManager = new QAManager();
		$useLangs = $qaManager->getQaLanguages($context->get('name'));
		$smarty->assign('useLangs', $useLangs);
		$smarty->assign('resource', $context->get('resource'));
		$smarty->assign('tabLeftOn', true);
		$this->display($smarty, $context);
	}
}
?>

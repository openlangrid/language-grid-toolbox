<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
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
class AbstractView extends View {

	function display(&$smarty, &$context, $template="") {
		$smarty->assign('ml_lang', $context->get('ml_lang'));
		$smarty->assign('use_lang', $context->get('use_lang'));
		$smarty->assign('view_lang', $context->get('view_lang'));
		$smarty->assign('action', $context->get('action'));
		$smarty->assign('context', $context->getAll());
		$smarty->assign('xoops_url', XOOPS_URL);
		parent::display(&$smarty, &$context, $template);
	}
}
?>

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
require_once(dirname(__FILE__).'/AbstractAction.php');

class View {
	function __construct() {

	}

	function dispatch(&$context) {
		die("下位クラスでオーバーライドしてください。");
	}

    /**
     * クラスが利用するテンプレート名の取得
     */
    function getTemplateName() {
        $tmp = get_class($this);
        $tmp = preg_replace("/view$/i", "", $tmp);
        return $tmp.".html";
    }

    /**
     * getSmarty
     */
    function getSmarty()
    {
        // Smartyのオブジェクトを作成
        $smarty = new Smarty();
        $smarty->template_dir = APP_TEMPLATE_DIR;
        $smarty->compile_dir  = APP_TEMPLATE_COMP_DIR;
        $smarty->left_delimiter = APP_SMARTY_LEFT_DELIMITER;
        $smarty->right_delimiter = APP_SMARTY_RIGHT_DELIMITER;
		register_themedb_resource($smarty);

        return $smarty;
    }

    /**
     * Smartyのdisplayメソッドのラッパー関数
     */
    function display(&$smarty, &$context, $template="")
    {
        if ($template == "") {
            $template = $this->getTemplateName();
        }

		$smarty->assign('context', $context->getAll());
		$smarty->assign('ml_lang', $context->get('ml_lang'));
		if (AbstractAction::isAuthorized()) {
			$smarty->assign('authorized', true);
			$smarty->assign(array('xoops_charset' => 'utf-8',
								  'xoops_contents' => ($smarty->fetch('common/tabs.template.html')
													   . $smarty->fetch($template)),
								  'xoops_crblocks' => array(array('content' => $smarty->fetch('common/jumplang.template.html'))),
								  'xoops_theme' => APP_THEME_NAME,
								  'xoops_dirname' => 'webqa',
								  'xoops_rootpath' => XOOPS_ROOT_PATH,
								  'xoops_version' => XOOPS_VERSION,
								  'xoops_imageurl' => XOOPS_THEME_URL . "/" . APP_THEME_NAME . "/" ,
								  'xoops_themecss' => xoops_getcss(APP_THEME_NAME),
								  'xoops_sitename' => WQA_APPLICATION_NAME,
								  'xoops_module_header' => ('<link rel="stylesheet" type="text/css" href="./css/import.css" />'
															. $smarty->fetch('common/qapost.template.html')),
								  // 'xoops_pagetitle' => $textFilter->toShow($context->getAttribute('legacy_pagetitle')),
								  'xoops_url' => XOOPS_URL));
			
			$smarty->display('db:theme.html');
		} else { 
			$smarty->assign('authorized', false);
			$smarty->assign('template_name', $template);
			$smarty->display('common/header.template.html');
		}
    }
 
}
?>

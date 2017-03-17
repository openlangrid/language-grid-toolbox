<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
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
require_once(dirname(__FILE__).'/common/php_lib/Smarty-2.6.26/libs/Smarty.class.php');
/**
 * <#if locale="en">
 * Smarty template engine class
 * <#elseif locale="ja">
 * Smartyテンプレートエンジンクラス
 * </#if>
 */
class LanguageGridSmarty extends Smarty{

    function LanguageGridSmarty($module) {
		$dir = dirname(__FILE__);
		$this->template_dir = $dir.'/'.$module.'/templates/';
		$this->compile_dir = $dir.'/templates_c/';

		$this->left_delimiter =  '<{';
		$this->right_delimiter =  '}>';
    }
}

/**
 * <#if locale="en">
 * For page dictionary
 * <#elseif locale="ja">
 * ページ辞書用
 * </#if>
 */
//class LanguageGridSmartyByDictionary extends LanguageGridSmarty {
//	function LanguageGridSmartyByDictionary() {
//		parent::LanguageGridSmarty('service_grid/dictionary');
//	}
//}

/**
 * <#if locale="en">
 * For translation setting
 * <#elseif locale="ja">
 * 翻訳設定用
 * </#if>
 */
class LanguageGridSmartyBySetting extends LanguageGridSmarty {
	function LanguageGridSmartyBySetting() {
//		parent::LanguageGridSmarty('setting');
		parent::LanguageGridSmarty('langrid');
	}
}
?>
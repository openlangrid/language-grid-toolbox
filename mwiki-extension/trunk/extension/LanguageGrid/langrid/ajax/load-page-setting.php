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
require_once(dirname(__FILE__).'/../class/PathSettingWrapperClass.php');
require_once(dirname(__FILE__).'/../class/DefaultDictionariesClass.php');
require_once(dirname(__FILE__).'/../class/TranslationOptionsClass.php');

/**
 * <#if locale="en">
 * Class for loading translation settings
 * <#elseif locale="ja">
 * 翻訳設定値ロードクラス
 * </#if>
 */
class LoadPageSetting extends LanguageGridAjaxRunner {
	function dispatch($action, $params) {
		$contents = array();
		$titleDbKey = $params;

		$idUtil =& new LanguageGridArticleIdUtil();
		$setId = $idUtil->getSetIdByPageTitle($titleDbKey);

		$defDicts =& new DefaultDictionariesSetting();
		$contents['DefaultDicts'] = $defDicts->searchByArticleId($setId);

		$translationOptions =& new TranslationOptions();
		$contents['TranslationOptions'] = $translationOptions->searchByArticleId($setId);
		
		$pathSetting =& new PathSettingWapperClass();
		$setting = $pathSetting->searchByArticleId($setId);
		$contents['setting'] = $setting;

		
		return $contents;
	}
}
?>
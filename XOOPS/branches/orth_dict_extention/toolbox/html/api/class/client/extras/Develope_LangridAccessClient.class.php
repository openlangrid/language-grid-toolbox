<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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

require_once(dirname(__FILE__).'/../LangridAccessClient.class.php');
require_once(dirname(__FILE__).'/../../../../service_grid/client/common/util/SourceTextJoinStrategyType.class.php');
require_once(dirname(__FILE__).'/../../manager/extras/Toolbox_Develope_LangridAccess_TranslationManager.class.php');
//
require_once(XOOPS_ROOT_PATH.'/api/class/manager/Toolbox_ServiceGridClientAdapter.class.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');

class Develope_LangridAccessClient extends LangridAccessClient {

	/**
	 * <#if lang="ja">
	 * 複数行翻訳
	 * </#if>
	 */
	public function multisentenceTranslate($sourceLang, $targetLang, $sourceArray, $bindingSetName, $SourceTextJoinStrategy = SourceTextJoinStrategyType::Customized, $options = array()) {
//		$manager = new Toolbox_Develope_LangridAccess_TranslationManager();
		$manager = new Toolbox_ServiceGridClientAdapter();
		return $manager->multisentenceTranslate($sourceLang, $targetLang, $sourceArray, $bindingSetName, $SourceTextJoinStrategy, $options);
	}
	/**
	 * for Developer only
	 * <#if locale="en">
	 * <#elseif locale="ja">
	 * </#if>
	 * @param unknown_type $sourceLang
	 * @param unknown_type $targetLang
	 * @param unknown_type $source
	 * @param unknown_type $translationBindingSetName
	 */
	public function metaTranslate($sourceLang, $targetLang, $source, $translationBindingSetName, $options = array()) {
		$manager = new Toolbox_ServiceGridClientAdapter();
		return $manager->metaTranslate($sourceLang, $targetLang, $source, $translationBindingSetName, $options);
	}
	
	/**
	 * <#if lang="ja">
	 * 複数行折返翻訳
	 * </#if>
	 */
	public function multisentenceBackTranslate($sourceLang, $intermediateLang, $sourceArray, $bindingSetName, $SourceTextJoinStrategy = SourceTextJoinStrategyType::Customized, $options = array()) {
//		$manager = new Toolbox_Develope_LangridAccess_TranslationManager();
		$manager = new Toolbox_ServiceGridClientAdapter();

		$result = $manager->multisentenceBackTranslate($sourceLang, $intermediateLang, $sourceArray, $bindingSetName, $SourceTextJoinStrategy, $options);
//		debugLog('## ServiceGrid ##'.print_r($result, true));
		return $result;
	}
}
?>

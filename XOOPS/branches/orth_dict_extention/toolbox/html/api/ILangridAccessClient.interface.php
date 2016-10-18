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

interface ILangridAccessClient {

	/**
	 *
	 * @param $sourceLang
	 * @param $targetLang
	 * @param $source
	 * @param $translationBindingSetName
	 * @return array
	 */
	public function translate($sourceLang, $targetLang, $source, $translationBindingSetName);
	/**
	 *
	 * @param $sourceLang
	 * @param $intermediateLang
	 * @param $source
	 * @param $translationBindingSetName
	 * @return array
	 */
	public function backTranslate($sourceLang, $intermediatetLang, $source, $translationBindingSetName);

	/**
	 *
	 * @param $bindingType
	 * @return array
	 */
	public function getAllBindingSets($bindingType = null);
	/**
	 *
	 * @param $bindingSetName
	 * @return array
	 */
	public function getBindingSet($bindingSetName);
	/**
	 *
	 * @param $bindingSetName
	 * @param $type
	 * @param $bShared
	 * @return array
	 */
	public function createBindingSet($bindingSetName, $type, $bShared);
	/**
	 *
	 * @param $bindingSetName
	 * @return void
	 */
	public function deleteBindingSet($bindingSetName);

	/**
	 *
	 * @param $translationBindingSetName
	 * @return array
	 */
	public function getAllMultihopTranslationBindings($translationBindingSetName);
	/**
	 *
	 * @param $translationBindingSetName
	 * @param $multihopTranslationBindingId
	 * @return array
	 */
	public function getMultihopTranslationBinding($translationBindingSetName, $multihopTranslationBindingId);

	/**
	 *
	 * @param $translationBindingSetName
	 * @param $multihopTranslationBindingId
	 * @param $translationBindings
	 * @return void
	 */
	public function setMultihopTranslationBinding($translationBindingSetName, $multihopTranslationBindingId, $translationBindings);
	/**
	 *
	 * @param $translationBindingSetName
	 * @param $path
	 * @param $translationBindings
	 * @return array
	 */
	public function addMultihopTranslationBinding($translationBindingSetName, $path, $translationBindings);
	/**
	 *
	 * @param $translationBindingSetName
	 * @param $multihopTranslationBindingId
	 * @return void
	 */
	public function deleteMultihopTranslationBinding($translationBindingSetName, $multihopTranslationBindingId);
	/**
	 *
	 * @param $translationBindingSetName
	 * @return array
	 */
	public function getSupportedTranslationLanguagePairs($translationBindingSetName);
	/**
	 * <#if locale="en">
	 * </#if>
	 * <#if locale="ja">
	 * /modules/langrid/class/get-supported-language-pair-class.php::getLanguageNamePair()互換
	 * </#if>
	 */
	public function getSupportedTranslationLanguagePairsWithName($translationBindingSetName);

	/**
	 *
	 * @param $type
	 * @return array
	 */
	public function getAllLanguageServices($type = null);
}

class ToolboxVO_LangridAccess_BindingSet {
	var $name;
	var $bindingType;
	var $setType;
}

class ToolboxVO_LangridAccess_LanguagePath {
	var $languages;
}

class ToolboxVO_LangridAccess_TranslationBinding {
	var $sourceLang;
	var $targetLang;
	var $translationServiceId;
	var $morphologicalAnalysisServiceId;
	var $globalDictionaryServiceIds;
	var $localDictionaryServiceIds;
	var $temporalDictionaryNames;
	var $globalParallelTextServiceIds;		/* Unused */
	var $localParallelTextServiceIds;		/* Unused */
	var $temporalParallelTextyNames;		/* Unused */
}

class ToolboxVO_LangridAccess_MultihopTranslationBinding {
	var $id;
	var $path;
	var $translationBindings;
}

class ToolboxVO_LangridAccess_InvocationInfo {
	var $serviceName;
	var $copyright;
	var $license;
	var $errorMessage;
}

class ToolboxVO_LangridAccess_TranslationResult {
	var $result;
	var $multihopTranslationBinding;
	var $translationInvocationInfo;
}

class ToolboxVO_LangridAccess_BackTranslationResult {
	var $intermediateResult;
	var $targetResult;
	var $multihopTranslationBinding;
	var $translationInvocationInfo;
}

class ToolboxVO_LangridAccess_LangridUser {
	var $name;
	var $responsiblePerson;
	var $email;
	var $homepageUrl;
	var $address;
	var $registrationDate;
}

class ToolboxVO_LangridAccess_LanguageService {
	var $serviceId;
	var $type;
	var $serviceName;
	var $description;
	var $provider;
	var $license;
	var $endpintUrl;
	var $registrationDate;
	var $lastUpdate;
	var $supportedLanguages;
}
?>

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

/**
 * TextTranslationClient
 */
interface ITextTranslationClient {

	/**
	 * simple translate
	 *
	 * @param $sourceLang translation source language.
	 * @param $targetLagg translation target language.
	 * @param $source translation source text.
	 * @return array
	 */
	public function translate($sourceLang, $targetLang, $source, $translationBindingSetId);

	/**
	 * back translate
	 *
	 * @param $sourceLang translation source language.
	 * @param $targetLagg translation target language.
	 * @param $source translation source text.
	 * @return array
	 */
	public function backTranslate($sourceLang, $targetLang, $source, $translationBindingSetId);
}

/**
 * translate result object
 */
class ToolboxVO_TextTranslation_TranslationResult {
	var $result;
	var $multihopTranslationBinding;
	var $translationInvocationInfo;
}
/**
 * back translate result object
 */
class ToolboxVO_TextTranslation_BackTranslationResult {
	var $intermediateResult;
	var $targetResult;
}

class ToolboxVO_TextTranslation_MultihopTranslationBinding {
	var $id;
	var $path;
	var $translationBindings;
}

class ToolboxVO_TextTranslation_InvocationInfo {
	var $serviceName;
	var $copyright;
	var $license;
	var $errorMessage;
}

class ToolboxVO_TextTranslation_TranslationBinding {
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

?>

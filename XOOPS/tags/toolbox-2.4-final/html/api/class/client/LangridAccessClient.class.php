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

require_once(dirname(__FILE__).'/../../ILangridAccessClient.interface.php');
require_once(dirname(__FILE__).'/Toolbox_AbstractClient.class.php');
require_once(dirname(__FILE__).'/../manager/Toolbox_LangridAccess_SettingManager.class.php');
require_once(dirname(__FILE__).'/../manager/Toolbox_LangridAccess_TranslationManager.class.php');
// for Toolbox
require_once(XOOPS_ROOT_PATH.'/api/class/manager/Toolbox_ServiceGridClientAdapter.class.php');

class LangridAccessClient extends Toolbox_AbstractClient implements ILangridAccessClient {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
	}
	/**
	 *
	 * @param $sourceLang
	 * @param $targetLang
	 * @param $source
	 * @param $translationBindingSetName
	 * @param $options array('type'=>'normal') normal or lite or rich or dual
	 * @return array
	 */
	public function translate($sourceLang, $targetLang, $source, $translationBindingSetName, $options = array()) {
		$manager = new Toolbox_ServiceGridClientAdapter();
		return $manager->translate($sourceLang, $targetLang, $source, $translationBindingSetName, $options);
	}
	/**
	 *
	 * @param $sourceLang
	 * @param $intermediateLang
	 * @param $source
	 * @param $translationBindingSetName
	 * @param $options array('type'=>'normal') normal or lite or rich or dual
	 * @return array
	 */
	public function backTranslate($sourceLang, $intermediatetLang, $source, $translationBindingSetName, $options = array()) {
		$manager = new Toolbox_ServiceGridClientAdapter();
		return $manager->backTranslate($sourceLang, $intermediatetLang, $source, $translationBindingSetName, $options);
	}
	/**
	 *
	 * @param $bindingType
	 * @return array
	 */
	public function getAllBindingSets($bindingType = null) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->getAllBindingSets($bindingType);
	}
	/**
	 *
	 * @param $bindingSetName
	 * @return array
	 */
	public function getBindingSet($bindingSetName) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->getBindingSet($bindingSetName);
	}
	/**
	 *
	 * @param $bindingSetName
	 * @param $type
	 * @param $bShared
	 * @return array
	 */
	public function createBindingSet($bindingSetName, $type, $bShared) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->createBindingSet($bindingSetName, $type, $bShared);
	}
	/**
	 *
	 * @param $bindingSetName
	 * @return void
	 */
	public function deleteBindingSet($bindingSetName) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->deleteBindingSet($bindingSetName);
	}

	/**
	 *
	 * @param $translationBindingSetName
	 * @return array
	 */
	public function getAllMultihopTranslationBindings($translationBindingSetName) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->getTranslationPaths($translationBindingSetName);
	}
	/**
	 *
	 * @param $translationBindingSetName
	 * @param $multihopTranslationBindingId
	 * @return array
	 */
	public function getMultihopTranslationBinding($translationBindingSetName, $multihopTranslationBindingId) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->getTranslationPath($translationBindingSetName, $multihopTranslationBindingId);
	}

	/**
	 *
	 * @param $translationBindingSetName
	 * @param $multihopTranslationBindingId
	 * @param $translationBindings
	 * @return void
	 */
	public function setMultihopTranslationBinding($translationBindingSetName, $multihopTranslationBindingId, $translationBindings) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->updateTranslationPath($translationBindingSetName, $multihopTranslationBindingId, $translationBindings);
	}
	/**
	 *
	 * @param $translationBindingSetName
	 * @param $path
	 * @param $translationBindings
	 * @return array
	 */
	public function addMultihopTranslationBinding($translationBindingSetName, $path, $translationBindings) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->addTranslationPath($translationBindingSetName, $path, $translationBindings);
	}
	/**
	 *
	 * @param $translationBindingSetName
	 * @param $multihopTranslationBindingId
	 * @return void
	 */
	public function deleteMultihopTranslationBinding($translationBindingSetName, $multihopTranslationBindingId) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->removeTranslationPath($translationBindingSetName, $multihopTranslationBindingId);
	}
	/**
	 *
	 * @param $translationBindingSetName
	 * @return array
	 */
	public function getSupportedTranslationLanguagePairs($translationBindingSetName) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->getSupportedTranslationPathLanguagePairs($translationBindingSetName, false);
	}
	/**
	 * <#if locale="en">
	 * </#if>
	 * <#if locale="ja">
	 * /modules/langrid/class/get-supported-language-pair-class.php::getLanguageNamePair()互換
	 * </#if>
	 */
	public function getSupportedTranslationLanguagePairsWithName($translationBindingSetName) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->getSupportedTranslationPathLanguagePairs($translationBindingSetName, true);
	}


	/**
	 *
	 * @param $type
	 * @return array
	 */
	public function getAllLanguageServices($type = null) {
		$manager =& new Toolbox_LangridAccess_SettingManager();
		return $manager->getAllLanguageServices($type);
	}
}
?>

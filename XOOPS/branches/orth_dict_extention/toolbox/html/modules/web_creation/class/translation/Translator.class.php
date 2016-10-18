<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2010  NICT Language Grid Project
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

require_once XOOPS_ROOT_PATH.'/api/class/client/extras/Develope_LangridAccessClient.class.php';
require_once APP_ROOT_PATH.'/class/template/TemplateApplyer.class.php';
require_once APP_ROOT_PATH.'/class/toolbox/TranslatorSettingAdapter.class.php';
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');

class Translator {

	protected $sourceLanguage;
	protected $targetLanguage;
	protected $licenses = array();

	/**
	 * Constructor
	 * @param String $sourceLanguage
	 * @param String $targetLanguage
	 */
	public function __construct($sourceLanguage, $targetLanguage) {
		$this->sourceLanguage = $sourceLanguage;
		$this->targetLanguage = $targetLanguage;
	}

	/**
	 *
	 * @param String $sourceLanguage
	 * @param String $targetLanguage
	 * @param array $templates
	 */
	public static function factory($sourceLanguage, $targetLanguage, $templates = null) {

		if (!is_array($templates) || empty($templates)) {
			return new SimpleTranslator($sourceLanguage, $targetLanguage);
		}

		return new TemplateTranslator($sourceLanguage, $targetLanguage, $templates);
	}

	/**
	 *
	 * @param unknown_type $result
	 */
	public function translate($result) {
		return $result;
	}

	/**
	 *
	 * @param unknown_type $sources
	 */
	protected function doTranslate($sources) {
		$client = new Develope_LangridAccessClient();

		$result = $client->multisentenceTranslate(
				$this->sourceLanguage, $this->targetLanguage, $sources
				, TranslatorSettingAdapter::BINDING_NAME, Toolbox_Develope_SourceTextJoinStrategyType::Customized
		);

		if ($result['contents'] == null || $result['status'] != 'OK') {
			throw new Exception($result['message']);
		}
		$contents = $result['contents'];
		if (is_array($contents)) {
			$contents = $contents[0];
		}
		if (!is_a($contents, 'ToolboxVO_LangridAccess_TranslationResult')) {
			throw new Exception('Toolbox api returnd object is not a ToolboxVO_LangridAccess_TranslationResult.');
		}

		$this->licenses = array();
		foreach ($contents->translationInvocationInfo as $l) {
			$this->licenses[$l->serviceName] = $l;
		}

		return $contents->result;
	}

	/**
	 * @return
	 */
	public function getLicenses() {
		return $this->licenses;
	}
}

class SimpleTranslator extends Translator {

	public function translate($result) {

		$sources = array();
		$sourceKeys = array();

		foreach ($result as $key => $line) {
			if ($line['status'] == 'fixed') {
				continue;
			}

			if ($line['status'] == 'tag') {
				$result[$key]['target'] = $line['source'];
				continue;
			}

			$sourceKeys[] = $key;
			$sources[] = $line['source'];
		}

		$targets = $this->doTranslate($sources);

		foreach ($sourceKeys as $i => $key) {
			$result[$key]['target'] = $targets[$i];
		}

		return $result;
	}
}

class TemplateTranslator extends Translator {

	private $templates;

	public function __construct($sourceLanguage, $targetLanguage, $templates) {
		parent::__construct($sourceLanguage, $targetLanguage);
		$this->templates = $templates;
	}

	private function applyTemplate(&$result) {
		$applyer = new TemplateApplyer();

		foreach ($result as $key => $value) {
			$result[$key]['template'] = false;
		}

		foreach ($this->templates as $t) {
			$applyer->apply($result, $t);
		}
	}

	public function translate($result) {
		$this->applyTemplate($result);

		$sources = array();
		$sourceKeys = array();

		foreach ($result as $key => $line) {
			if ($line['template'] || $line['status'] == 'fixed') {
				continue;
			}

			if ($line['status'] == 'tag') {
				$result[$key]['target'] = $line['source'];
				continue;
			}

			$sourceKeys[] = $key;
			$sources[] = $line['source'];
		}

		$targets = $this->doTranslate($sources);

		foreach ($sourceKeys as $i => $key) {
			$result[$key]['target'] = $targets[$i];
		}

		return $result;
	}
}
?>
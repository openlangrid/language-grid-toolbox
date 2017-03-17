<?php

require_once dirname(__FILE__).'/LgUtil.class.php';

class LgSettingManager {

	private $code;

	private static $jsBase = array(
		'main' => array(
			'${EXT_ROOT_PATH}/common/js_lib/prototype-1.6.0.3.js',
			'${EXT_ROOT_PATH}/common/js_lib/json2.js',
			'${JS_PATH}/language-util.js',
			'${JS_PATH}/ajax-wrapper.js',
			'${JS_PATH}/singleton-dialog.js',
			'${JS_PATH}/area-expand-collapse-controller.js',
			'${JS_PATH}/dictionary-main.js',
			'${JS_PATH}/dictionary-main-input-inspector.js',
			'${JS_PATH}/dictionary-editstate.js',
			'${JS_PATH}/dictionary-upload-panel.js',
			'${JS_PATH}/import/import-dictionary-manager.js',
			'${JS_PATH}/import/import-dictionary-pane.js',
			'${JS_PATH}/import/import-dictionary-workspace.js',
			'${JS_PATH}/import/import-dictionary-main.js',
			'${JS_PATH}/translation/language-selector-pair.js',
			'${JS_PATH}/translation/license-area.js',
			'${JS_PATH}/translation/translator.js',
			'${JS_PATH}/translation/translation-timer.js',
			'${JS_PATH}/translation/translation-workspace.js',
			'${JS_PATH}/translation/translation-workspace-controller.js',
			'${JS_PATH}/translation/translation-main.js'
		),
		'languageSelect' => array(
			
		)
	);

	private static $cssBase = array(
		'main' => array(
			'${CSS_PATH}/style.css',
			'${CSS_PATH}/resources_style.css',
			'${CSS_PATH}/translation_style.css'
		),
		'languageSelect' => array(

		)
	);

	private $js;
	private $css;
	private $env;

	public function __construct($code) {
		$this->code = $code;

		$this->setEnv();
		$this->parseJs();
		$this->parseCss();
	}

	public function getJS() {
		return $this->js[$this->code];
	}

	public function getCSS() {
		return $this->css[$this->code];
	}

	private function setEnv() {
		$this->env = array(
			'${EXT_ROOT_PATH}' => LgUtil::getExtRootPath(),	
			'${JS_PATH}' => LgUtil::getExtDictionaryRootPath().'/js',
			'${CSS_PATH}' => LgUtil::getExtDictionaryRootPath().'/css'
		);	
	}

	private function parseJs() {
		$this->js = array();

		foreach (self::$jsBase as $code => $paths) {
			$this->js[$code] = array();
			foreach ($paths as $path) {
				$this->js[$code][] = $this->parsePath($path);
			}
		}
	}

	private function parseCss() {
		$this->css = array();

		foreach (self::$cssBase as $code => $paths) {
			$this->css[$code] = array();
			foreach ($paths as $path) {
				$this->css[$code][] = $this->parsePath($path);
			}
		}
	}

	private function parsePath($path) {
		foreach ($this->env as $env => $value) {
			$path = str_replace($env, $value, $path);
		}

		return $path;
	}
}
?>

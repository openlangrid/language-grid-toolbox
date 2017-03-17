<?php

require_once XOOPS_ROOT_PATH.'/modules/langrid_config/class/manager/VoiceSettingManager.class.php';
require_once XOOPS_ROOT_PATH.'/service_grid/client/LanguageGrid.interface.php';

class TextToSpeech implements LanguageGrid {

	protected $_client;
	protected $_language;

	public function __construct($lang) {
		$this->_language = $lang;

		$data = VoiceSettingManager::getUserSetting();
		$serviceId = $data[$lang];

		$this->_client = new LangridSoapClient($serviceId);
	}

	public function invoke() {
		// ???
	}

	/**
	 * 音声合成サービスを呼び出す
	 * @param <type> $sourceLang
	 * @param <type> $targetLang
	 * @param <type> $text
	 * @return <type>
	 */
	public function speak($text) {
		$res = $this->_client->invokeService('speak', array(
			$this->_language, $text, 'woman', 'audio/x-wav'
		));

		if (strcasecmp($res['status'], 'OK') != 0) {
			return null;
		}

		if (!isset($res['contents']->audio)) {
			return null;
		}

		return $res['contents']->audio;
	}
}
?>
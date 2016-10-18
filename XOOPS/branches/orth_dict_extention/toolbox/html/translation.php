<?php
require_once XOOPS_ROOT_PATH.'/modules/langrid/php/building-blocks/combination/translation-with-bilingual-dictionary-with-lm.php';
require_once XOOPS_TRUST_PATH.'/libs/altsys/class/D3LanguageManager.class.php';
require_once XOOPS_ROOT_PATH.'/modules/langrid/php/langrid-client.php';

class Translation {

	private $targetLanguages = array();
	private $sourceText = '';
	private $translator;
	private $result = array(
						'status' => 'OK',
						'message' => _MD_D3FORUM_SYSTEM_MESSAGE_TRANSLATION_IS_SUCCESS,
						'contents' => array()
						);
	private $isError = false;
	private $isWarning = false;
	private $languageManager;

	function __construct() {
		$this->root = XCube_Root::getSingleton();
		$this->languageManager = D3LanguageManager::getInstance();
		$this->sourceLanguageTag = $this->languageManager->getSourceLanguageTag();
		$this->targetLanguages = $this->languageManager->getTargetLanguages();
	}

	/**
	 * @param $sourceText
	 * @return Array
	 * result['contents'][language tag]['translation']['contents']
	 * result['contents'][language tag]['backTranslation']['contents']
	 */
	public function translate ($sourceText = '', $config = array()) {
		$userDict = array(array());

		$config = array_merge(
			array(
					'appName'=> 'Forum',
					'loginUserId'=> $this->root->mContext->mXoopsUser->get('uid'),
				),
			$config
		);
		
		foreach ($this->targetLanguages as $targetLanguageTag => $targetLanguageName) {
			$config['note2'] = 'translation';
			$langridClient = new LangridClient(
				array(
					'sourceLang' => $this->sourceLanguageTag,
					'targetLang' => $targetLanguageTag
					),
				$config
			);
			$translation = $langridClient->translate($sourceText);
			$this->result['contents'][$targetLanguageTag]['translation'] = $translation['contents']['targetText'];

			$config['note2'] = 'backTranslation';
			$langridClient = new LangridClient(
				array(
					'sourceLang' => $targetLanguageTag,
					'targetLang' => $this->sourceLanguageTag
					),
				$config
			);
			$backTranslation = $langridClient->translate($translation['contents']['targetText']['contents']);
			$this->result['contents'][$targetLanguageTag]['backTranslation'] = $backTranslation['contents']['targetText'];
		}
		return $this->result;
	}

	public function isError() {
		return $this->isError;
	}
	public function isWarning() {
		return $this->isWarning;
	}
	public function getResult() {
		return $this->result;
	}
	public function setTargetLanguage($targetLanguage) {
		$this->targetLanguages = array($targetLanguage => '');
	}
	public function setTargetLanguages($targetLanguages) {
		$this->targetLanguages = $targetLanguages;
	}
	public function getTargetLanguages() {
		return $this->targetLanguages;
	}
	public function getSourceText() {
		return $this->sourceText;
	}
}
?>
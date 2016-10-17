<?php

class TranslatorAdapter {

	private $client;
	private $title;

	private $sources;
	private $blanks;
	private $lines;
	private $result;

	public function __construct($title) {
		$this->client = new Wikimedia_LangridAccessClient();
		$this->title = $title;
	}

	public function translate($sourceLang, $intermediatetLang, $source) {
		// return $this->testTranslate($sourceLang, $intermediatetLang, $source);

		$this->preFilter($source);

		$result = $this->client->multisentenceBackTranslate($sourceLang, $intermediatetLang, $this->sources, $this->title);
	
		$this->postFilter($result);
		
		return $this->result;
	}

	private function preFilter($source) {
		$this->sources = array();
		$this->blanks = array();
		$this->result = array(
			'Translation' => array(),
			'BackTranslation' => array(),
			'LicenseInformation' => array()		
		);

		$sourceArray = explode("\n", $source);
		$this->lines = count($sourceArray);

		foreach ($sourceArray as $i => $line) {
			if ($this->isNull($line)) {
				$this->blanks[] = $i;
				continue;
			}

			$this->sources[] = $line;
		}
	}

	private function postFilter($result) {
		$translation = array();
		$backTranslation = array();

		for ($i = 0; $i < $this->lines; $i++) {
			if (in_array($i, $this->blanks)) {
				$translation[] = '';
				$backTranslation[] = '';
				continue;
			}

			$translation[] = array_shift($result['contents']->intermediate);
			$backTranslation[] = array_shift($result['contents']->target);
		}

		$this->result['Translation'] = implode("\n", $translation);
		$this->result['BackTranslation'] = implode("\n", $backTranslation);
		$this->result['LicenseInformation'] = $result['LicenseInformation'];
	}

	private function isNull($text) {
		$text = preg_replace("#\t|\n|\s|\r| |　#u", '', $text);
		return ($text == '');
	}

	public function testTranslate($sourceLang, $intermediatetLang, $source) {
		$return = array(
			'Translation' => array(),
			'BackTranslation' => array(),
			'LicenseInformation' => array()		
		);

		$result = $this->client->translate($sourceLang, $intermediatetLang, $source, $this->title);
		$backResult = $this->client->translate($intermediatetLang, $sourceLang, $result['contents'], $this->title);

		$return['Translation'] = $result['contents'];
		$return['BackTranslation'] = $backResult['contents'];
		$return['LicenseInformation'] = array_merge($result['LicenseInformation'], $backResult['LicenseInformation']);

		return $return;
	}
}
?>

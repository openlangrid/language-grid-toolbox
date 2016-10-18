<?php

class LgDictionaryIndexController {

	private $request;
	private $path;

	private static $mapping = array(
		'languageselected' => 'maintenance.php',
		'selectlanguage' => 'selectlanguage.php',
		'download' => 'download.php',
		'upload' => 'upload.php'
	);

	public function __construct($request, $path) {
		$this->request = $request;
		$this->path = $path;
	}

	public function getPath() {
		$idUtil =& new LanguageGridArticleIdUtil();
		$dictId = $idUtil->getDictionaryIdByPageTitle($idUtil->getTitleDbKey());

		foreach (self::$mapping as $key => $value) {
			if ($this->request->getCheck($key)) {
				return $this->path.'/'.$value;
			}
		}

		$dbHandler = new UserDictionaryDbHandler();
		$dictObj = $dbHandler->getUserDictionary($dictId);

		if ($dictObj == null) {
			return $this->path.'/selectlanguage.php';
		} else {
			return $this->path.'/main.php';
		}
	}
}
?>

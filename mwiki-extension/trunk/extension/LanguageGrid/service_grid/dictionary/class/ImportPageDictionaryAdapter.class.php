<?php

require_once MYEXTPATH.'/service_grid/db/adapter/DaoAdapter.class.php';
require_once MYEXTPATH.'/service_grid/db/handler/UserDictionaryDbHandler.class.php';

class ImportPageDictionaryAdapter_PageNotExistsException extends Exception {}
class ImportPageDictionaryAdapter_PageDuplicationException extends Exception {}

class ImportPageDictionaryAdapter {

	const IMPORT_PAGE_DICT_BIND = '4';

	private $mDao = null;
	private $titleDbkey = null;

	public function __construct($titleDbKey = null) {
		$this->mDao = DaoAdapter::getAdapter()->getImportDictionaryDao();

		if ($titleDbKey) {
			$this->titleDbkey = $titleDbKey;
		}
	}

	public function load() {
		$userDictionaryId = $this->_getUserDictionaryId();

		$list = $this->mDao->queryByUserDictionaryId($userDictionaryId, self::IMPORT_PAGE_DICT_BIND);

		$contents = array();

		if ($list) {
			foreach ($list as $a) {
				$contents[] = $a->getBindValue();
			}
		}

		return $contents;
	}

	public function loadDictionary($fromTitle, $toTitle) {
		if (!$this->validateImportPageExists($fromTitle)) {
			throw new ImportPageDictionaryAdapter_PageNotExistsException('Not found.');
		}
		$ft = Title::makeTitle(0, $fromTitle);
		$ftKey = LanguageGridArticleIdUtil::getTitleDbKey($ft);
		$util = new LanguageGridArticleIdUtil();
		$id = $util->getDictionaryIdByPageTitle($ftKey);
		$handler = new UserDictionaryDbHandler();
		$data = $handler->doRead($id);
		$tt = Title::makeTitle(0, $toTitle);
		$ttKey = LanguageGridArticleIdUtil::getTitleDbKey($tt);
		$id = $util->getDictionaryIdByPageTitle($ttKey);
		$handler->doImport($id, $data);
		return true;
	}

	public function add($value) {
		if (!$this->validateImportPageExists($value)) {
			throw new ImportPageDictionaryAdapter_PageNotExistsException('Not found.');
		}

		$userDictionaryId = $this->_getUserDictionaryId();

		if (!$this->validateImportPageDuplication($userDictionaryId, $value)) {
			throw new ImportPageDictionaryAdapter_PageDuplicationException('Duplication');
		}

		$ret = $this->mDao->insert($userDictionaryId, self::IMPORT_PAGE_DICT_BIND, $value);

		if ($ret) {
			return $ret;
		}

		return false;
	}

	public function remove($value) {
		$userDictionaryId = $this->_getUserDictionaryId();

		$list = $this->mDao->queryByUserDictionaryId($userDictionaryId);

		if (!$list) {
			return;
		}

		foreach ($list as $a) {
			if ($a->getBindValue() == $value) {
				$this->mDao->delete($a->getId());
			}
		}
	}

	private function _getUserDictionaryId() {
		$idUtil = new LanguageGridArticleIdUtil();

		if ($this->titleDbkey == null) {
			$this->titleDbkey = $idUtil->getTitleDbKey();
		}

		$userDictionaryId = $idUtil->getDictionaryIdByPageTitle($this->titleDbkey);

		return $userDictionaryId;
	}

	private function validateImportPageExists($value) {
		$idUtil = new LanguageGridArticleIdUtil();
		$ret = $idUtil->getDictionaryIdByPageTitle($value);
		
		if ($ret == null || $ret == 0) {
			return false;
		}

		return true;
	}

	private function validateImportPageDuplication($userDictionaryId, $value) {
		$list = $this->mDao->searchByParams($userDictionaryId, self::IMPORT_PAGE_DICT_BIND, $value);
		return empty($list);
	}
}
?>

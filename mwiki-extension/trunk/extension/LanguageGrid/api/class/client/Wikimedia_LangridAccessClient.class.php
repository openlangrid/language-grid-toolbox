<?php
require_once(MYEXTPATH.'/service_grid/config/ServiceGridConfig.class.php');
require_once(MYEXTPATH.'/service_grid/ServiceGridClient.class.php');
require_once(MYEXTPATH.'/service_grid/dictionary/class/ImportPageDictionaryAdapter.class.php');
require_once(MYEXTPATH.'/service_grid/db/handler/TranslationOptionDbHandler.class.php');

/**
 * <#if locale="en">
 * Language Grid access API client
 * <#elseif locale="ja">
 * 言語グリッドアクセス用APIクライアント
 * </#if>
 */
class Wikimedia_LangridAccessClient  {
	public function getSupportedTranslationLanguagePairs($title) {
		$idUtil = new LanguageGridArticleIdUtil();
		$titleDbKey = $idUtil->getTitleDbKey($title);
		$setId = $idUtil->getSetIdByPageTitle($titleDbKey);
		$serviceGridClient = new ServiceGridClient();
		$pairs = $serviceGridClient->getSupportedTranslationPathLanguagePairs($setId);
		return $pairs;
	}
	public function translate($sourceLang, $targetLang, $source, $title) {
		$keys = $this->getIds($title);
		$type = $this->getTranslationOptions($keys['setId']);
		$serviceGridClient = new ServiceGridClient(array('type'=>$type));
		return $serviceGridClient->translate($sourceLang, $targetLang, $source, $keys['setId'], $keys['dbKey'], $keys['dictIds']);
	}

	public function multisentenceTranslate($sourceLang, $targetLang, $sourceArray, $title) {
		$keys = $this->getIds($title);
		$type = $this->getTranslationOptions($keys['setId']);		
		$serviceGridClient = new ServiceGridClient(array('type'=>$type));
		return $serviceGridClient->multisentenceTranslate($sourceLang, $targetLang, $sourceArray, $keys['setId'], $keys['dbKey'], $keys['dictIds'], SourceTextJoinStrategyType::Customized);
	}

	public function backTranslate($sourceLang, $intermediatetLang, $source, $title) {
		$keys = $this->getIds($title);
		$type = $this->getTranslationOptions($keys['setId']);		
		$serviceGridClient = new ServiceGridClient(array('type'=>$type));
		return $serviceGridClient->backTranslate($sourceLang, $intermediatetLang, $source, $keys['setId'], $keys['dbKey'], $keys['dictIds']);
	}

	public function multisentencebackTranslate($sourceLang, $intermediatetLang, $sourceArray, $title) {
		$keys = $this->getIds($title);
		$type = $this->getTranslationOptions($keys['setId']);		
		$serviceGridClient = new ServiceGridClient(array('type'=>$type));
		return $serviceGridClient->multisentenceBackTranslate($sourceLang, $intermediatetLang, $sourceArray, $keys['setId'], $keys['dbKey'], $keys['dictIds'], SourceTextJoinStrategyType::Customized);
	}

	private function getIds($title) {
		$idUtil = new LanguageGridArticleIdUtil();
		$titleDbKey = $idUtil->getTitleDbKey($title);
		$setId = $idUtil->getSetIdByPageTitle($titleDbKey);
		$dictId = $idUtil->getDictionaryIdByPageTitle($titleDbKey);
		$dictIds = array();
		$dictIds = $this->getPageDictImport($dictIds, $titleDbKey);
		$dictIds[] = $dictId;

		return array('setId'=>$setId, 'dbKey'=>$titleDbKey, 'dictIds'=>$dictIds, 'type'=>$type);
	}
	private function validTitle($title) {
		$idUtil = new LanguageGridArticleIdUtil();
		return $idUtil->getSetIdByPageTitle($title);
	}
	private function getPageDictImport(&$dictIds, $titleDbKey) {
		$idUtil = new LanguageGridArticleIdUtil();
		$importDic = new ImportPageDictionaryAdapter($titleDbKey);
		$resultsDictNames = $importDic->load();
		foreach ($resultsDictNames as $dictName) {
			$dictIds[] = $idUtil->getDictionaryIdByPageTitle($dictName);
		}
		return $dictIds;
	}
	private function getTranslationOptions($setId) {
		$optionHandler = new TranslationOptionDbHandler();
		$options = $optionHandler->load($setId);

		if (count($options) > 0) {
			$lite = $options[0]->getLiteFlag();
			$rich = $options[0]->getRichFlag();

			if ($lite === '1' && $rich === '1') {
				$type = 'dual';
			} else if ($lite === '1') {
				$type = 'lite';
			} else if ($rich === '1') {
				$type = 'rich';
			} else {
				$type = 'normal';
			}
		} else {
			$type = 'normal';
		}
		
		return $type;
	}
}
?>

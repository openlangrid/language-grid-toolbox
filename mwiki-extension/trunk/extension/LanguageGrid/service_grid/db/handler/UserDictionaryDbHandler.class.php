<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
require_once(dirname(__FILE__).'/../adapter/DaoAdapter.class.php');
/**
 * <#if locale="en">
 * Temporal dictionary DB handler class
 * <#elseif locale="ja">
 * テンポラル辞書DBハンドラクラス
 * </#if>
 */
class UserDictionaryDbHandler {

	protected $db = null;
	protected $m_DictionaryDao = null;
	protected $m_ContentsDao = null;

    function __construct() {
		// get adapter
		$adapter = DaoAdapter::getAdapter();
    	$this->m_DictionaryDao = $adapter->getUserDictionaryDao();
    	$this->m_ContentsDao = $adapter->getUserDictionaryContentsDao();
    }

    public function getUserDictionaryDao() {
    	return $this->m_DictionaryDao;
    }
    public function getUserDictionaryContentsDao() {
    	return $this->m_ContentsDao;
    }

	/**
	 * <#if locale="en">
	 * Return page dictionary
	 * <#elseif locale="ja">
	 * ページ辞書を返す
	 * </#if>
	 */
    public function getUserDictionary($articleId) {
    	return $this->m_DictionaryDao->get($articleId);
    }

	/**
	 * <#if locale="en">
	 * Specify languages and return dictionary contents
	 * <#elseif locale="ja">
	 * 言語を指定して辞書コンテンツを返す
	 * </#if>
	 */
    public function getSupportedLanguages($articleId) {
		return $this->m_ContentsDao->getLanguages($articleId);
    }

	/**
	 * <#if locale="en">
	 * Specify languages and return dictionary contents
	 * <#elseif locale="ja">
	 * 言語を指定して辞書コンテンツを返す
	 * </#if>
	 */
    public function getContents($articleId, $languageCode) {
		$objects =& $this->m_ContentsDao->getContentsByLanguage($articleId, $languageCode);

		$contents = array();
		foreach ($objects as $obj) {
			if ($obj->get('row') > 0) {
				$contents[$obj->get('row')] = $obj->get('contents');
			}
		}
		return $contents;
    }

 
	/**
	 * <#if locale="en">
	 * Read dictionary contents
	 * <#elseif locale="ja">
	 * 辞書コンテンツの読み出し
	 * </#if>
	 */
	function doRead($articleId) {
		$dictionaries = $this->m_ContentsDao->getContents($articleId);
		return $dictionaries;
	}

	/**
	 * <#if locale="en">
	 * Extract data for dictionary download
	 * <#elseif locale="ja">
	 * 辞書ダウンロード用データの抽出
	 * </#if>
	 */
	function doDownload($articleId) {
		$dictionaries = $this->m_ContentsDao->getContents($articleId, true);
		return $dictionaries;
	}

	function doUpload($articleId, $tmpFilePath, $mimeType) {
		if (!$this->isValidFileFormat($mimeType)) {
			return wfMsg('lg:The_file_format_is_invalid.');
		}

		$tmpFileLines = file($tmpFilePath);
		$code = mb_detect_encoding($tmpFileLines[0]);

		if (ord($tmpFileLines[0]{0}) == 255 && ord($tmpFileLines[0]{1}) == 254) {
			$code = "UTF-16LE";
		} else if (ord($tmpFileLines[0]{0}) == 254 && ord($tmpFileLines[0]{1}) == 255) {
			$code = "UTF-16BE";
		} else if (!$code) {
			$error = "Invalid Encoding.";
		}
		foreach($tmpFileLines as $aline) {
			$tmpFileContent .= $aline;
		}

		$utf8content = mb_convert_encoding($tmpFileContent, 'UTF-8', $code);
		if (ord($utf8content{0}) == 0xef && ord($utf8content{1}) == 0xbb && ord($utf8content{2}) == 0xbf) {
			$utf8content = substr($utf8content, 3);
		}

		$lines = split("\r?\n", $utf8content);
		$validColNums;
		$validColLang;
		foreach($lines as $aline){
			if (mb_strlen($aline) == 0) {
				continue;
			}
			$rowArray = explode("\t", $aline);

			if(!$validColNums){
				$validColNums = array();
				for($i=0; $i<count($rowArray); $i++){
					if(mb_strlen($rowArray[$i])>0){
						$validColNums[] = $i;
						$validColLang[] = $rowArray[$i];
					}
				}
			} else {
				$tableRow = array();
				foreach($validColNums as $colNum){
					$tableRow[$validColLang[$colNum]] = $rowArray[$colNum] ? $rowArray[$colNum] : "";
				}
				$dictTable[] = $tableRow;
			}
		}

		/*
		 * <#if locale="en">
		 * Merge dictionary languages
		 * <#elseif locale="ja">
		 * 辞書の言語をマージ
		 * </#if>
		 */
		$currentLangs = $this->getSupportedLanguages($articleId);
		$languageArray = array_merge($currentLangs, $validColLang);
		$this->updateLanguage($articleId, $languageArray);

		$entryDataArray = array();

		/*
		 * <#if locale="en">
		 * Add new record
		 * <#elseif locale="ja">
		 * 新規のレコードを追加
		 * </#if>
		 */
		$rowNumber = $this->m_ContentsDao->getMaxRowNumber($articleId);
		foreach ($dictTable as $record) {
			$rowNumber++;
			foreach ($languageArray as $lang) {
				$rowObj = $this->m_ContentsDao->create(true);
				$rowObj->set('row', $rowNumber);
				$rowObj->set('language', $lang);
				$rowObj->set('contents', $record[$lang]?$record[$lang]:'');
				$entryDataArray[] = $rowObj;
			}
		}
		foreach ($entryDataArray as $obj) {
			$obj->set('user_dictionary_id', $articleId);
			$obj->set('delete_flag', '0');
			$this->m_ContentsDao->insert($obj, true);
		}
		$this->dictionaryInfoUpdate($articleId);

		return '';
	}

	/**
	 * data format is such like this matrix... array(array('en' => 'Hello', 'ja' => 'こんにちは'), ...);
	 */
	function doImport($articleId, $data) {
		if (empty($data)) return;
		
		$dataLangs = array();
		foreach (array_keys($data[0]) as $lang) {
			if ($lang != 'row') $dataLangs[] = $lang;
		}
		
		// 言語のマージ
		$languages = $this->getSupportedLanguages($articleId);
		$languages = array_merge($languages, $dataLangs);
		$this->updateLanguage($articleId, $languages);
		
		// 列のマージ
		$rowNumber = $this->m_ContentsDao->getMaxRowNumber($articleId);
		foreach ($data as $i => $record) {
			++$rowNumber;
			foreach ($languages as $language) {
				$rowObj = $this->m_ContentsDao->create(true);
				$rowObj->set('row', $rowNumber);
				$rowObj->set('language', $language);
				$rowObj->set('contents', (isset($record[$language])) ? $record[$language] : '');
				$rowObj->set('user_dictionary_id', $articleId);
				$rowObj->set('delete_flag', '0');
				$this->m_ContentsDao->insert($rowObj, true);
			}
		}
		
		$this->dictionaryInfoUpdate($articleId);

		return '';
	}


	/**
	 * <#if locale="en">
	 * Search dictionary
	 * <#elseif locale="ja">
	 * 辞書の検索
	 * </#if>
	 */
	function doSearch($data) {
		$id = $data['dictionaryId'];
		$lang = $data['search_lang'];
		$word = $data['word'];
		$dictionaries = $this->m_ContentsDao->searchContents($id, $lang, $word);
		return $dictionaries;
	}

    /**
     * <#if locale="en">
     * Create page dictionary
     * <#elseif locale="ja">
     * ページ辞書を作成
     * </#if>
     */
    public function create($titleDbKey, $languageArray) {
    	$dictObj = $this->m_DictionaryDao->create(true);
    	$dictObj->set('dictionary_name', $titleDbKey);
    	$dictObj->set('delete_flag', '0');
    	$this->m_DictionaryDao->insert($dictObj);
    	$dictId = $dictObj->get('user_dictionary_id');
    	foreach ($languageArray as $lang) {
	    	$langRowObj = $this->m_ContentsDao->create(true);
	    	$langRowObj->set('user_dictionary_id', $dictId);
	    	$langRowObj->set('row', '0');
	    	$langRowObj->set('language', $lang);
	    	$langRowObj->set('contents', $lang);
	    	$langRowObj->set('delete_flag', '0');
	    	$this->m_ContentsDao->insert($langRowObj, true);
    	}
		$this->dictionaryInfoUpdate($dictId);
    	return true;
    }

	/**
	 * <#if locale="en">
	 * Add/delete languages of page dictionary
	 * <#elseif locale="ja">
	 * ページ辞書の言語を増減
	 * </#if>
	 */
    public function updateLanguage($articleId, $languageArray) {
    	$currentLanguages = $this->m_ContentsDao->getLanguages($articleId);

    	foreach ($currentLanguages as $lang) {
    		if (in_array($lang, $languageArray) == false) {
		/*
		 * <#if locale="en">
		 * Delete languages
		 * <#elseif locale="ja">
		 * 言語削除
		 * </#if>
		 */
				$this->m_ContentsDao->deleteByLanguage($articleId, $lang);
    		}
    	}

    	$looper = $this->getContents($articleId, $currentLanguages[0]);
    	foreach($languageArray as $lang) {
    		if (in_array($lang, $currentLanguages) == false) {
		/*
		 * <#if locale="en">
		 * Add languages
		 * <#elseif locale="ja">
		 * 言語追加
		 * </#if>
		 */
		    	$langRowObj = $this->m_ContentsDao->create(true);
		    	$langRowObj->set('user_dictionary_id', $articleId);
		    	$langRowObj->set('row', '0');
		    	$langRowObj->set('language', $lang);
		    	$langRowObj->set('contents', $lang);
		    	$langRowObj->set('delete_flag', '0');
		    	$this->m_ContentsDao->insert($langRowObj, true);
		    	foreach ($looper as $row => $text) {
			    	$langRowObj = $this->m_ContentsDao->create(true);
			    	$langRowObj->set('user_dictionary_id', $articleId);
			    	$langRowObj->set('row', $row);
			    	$langRowObj->set('language', $lang);
			    	$langRowObj->set('contents', '');
			    	$langRowObj->set('delete_flag', '0');
			    	$this->m_ContentsDao->insert($langRowObj, true);
		    	}
    		}
    	}
		$this->dictionaryInfoUpdate($articleId);
    }
	/**
	 * <#if locale="en">
	 * Update dictionary contents
	 * <#elseif locale="ja">
	 * 辞書コンテンツを更新
	 * </#if>
	 */
    public function update($dictionaryId, $data) {
    	$responseData = array();
		$allLangs = $this->getSupportedLanguages($dictionaryId);
		$entryDataArray = array();
		/*
		 * <#if locale="en">
		 * Add new record and count records
		 * <#elseif locale="ja">
		 * 新規のレコードを追加（＋新規レコード数を覚えておく）
		 * </#if>
		 */
		$newCount = 0;
		foreach ($data as $record) {
			if ($record['isNew']) {
				$newCount++;
				$res = array('row' => $newCount);
				foreach ($allLangs as $lang) {
					$rowObj = $this->m_ContentsDao->create(true);
					$rowObj->set('row', $newCount);
					$rowObj->set('language', $lang);
					$rowObj->set('contents', $record[$lang]);
					$entryDataArray[] = $rowObj;
					$res[$lang] = $record[$lang];
				}
				$responseData[$newCount - 1] = $res ;
			}
		}
		/*
		 * <#if locale="en">
		 * Overwrite if POST data exists in current data
		 * <#elseif locale="ja">
		 * 既存データにPOSTデータが存在する場合に、上書き
		 * </#if>
		 */
		foreach ($allLangs as $lang) {
			$postLangKey = null;
			$newRow = $newCount;
			$currentContents = $this->getContents($dictionaryId, $lang);
			foreach ($currentContents as $row => $text) {
				$isDelete = false;
				foreach ($data as $record) {
					if ($record['row'] == $row) {
						if ($record['isDelete']) {
							$isDelete = true;
							break;
						}
						$text = $record[$lang];
						break;
					}
				}
				if ($isDelete == false) {
					$newRow++;
					$rowObj = $this->m_ContentsDao->create(true);
					$rowObj->set('row', $newRow);
					$rowObj->set('language', $lang);
					$rowObj->set('contents', $text);
					$entryDataArray[] = $rowObj;

					if (is_array($responseData[$newRow - 1]) == false) {
						$responseData[$newRow - 1] = array('row' => $newRow);
					}
					$responseData[$newRow - 1][$lang] = $text;
				}
			}
		}
		$this->m_ContentsDao->deleteContents($dictionaryId);
		foreach ($entryDataArray as $obj) {
			$obj->set('user_dictionary_id', $dictionaryId);
			$obj->set('delete_flag', '0');
			$this->m_ContentsDao->insert($obj, true);
		}
		$this->dictionaryInfoUpdate($dictionaryId);
		return $responseData;
    }
	/**
	 * <#if locale="en">
	 * Update basic dictionary information
	 * <#elseif locale="ja">
	 * 辞書基本情報の更新
	 * </#if>
	 */
    private function dictionaryInfoUpdate($articleId) {
		global $wgUser;
    	$dictObj = $this->m_DictionaryDao->get($articleId);
    	$dictObj->set('update_date', time());
    	$dictObj->set('last_update_user', $wgUser->getName());
    	$this->m_DictionaryDao->update($dictObj);
    }
    /**
     * <#if locale="en">
     * Judge whether the file format is valid for upload
     * <#elseif locale="ja">
     * アップロードを許すファイルタイプ判定
     * </#if>
     */
	private function isValidFileFormat($mimeType) {
		$whiteList = array(
			'text/plain',
			'application/octet-stream'
			);
			if (in_array($mimeType, $whiteList)) {
				return true;
			}
			return false;
	}
}
?>

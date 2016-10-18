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
require_once(dirname(__FILE__).'/../AbstractDao.class.php');
require_once(dirname(__FILE__).'/../../../service_grid/client/common/util/SoapValueCreation.class.php');

/**
 * <#if locale="en">
 * User dictionary (page dictionary) database handler class
 * <#elseif locale="ja">
 * ユーザ辞書（ページ辞書）データベースハンドラクラス
 * </#if>
 */
class UserDictionaryContentsObject extends AbstractDaoObject {

	function UserDictionaryContentsObject() {
		$this->mVar['user_dictionary_id'] = '';
		$this->mVar['language'] = '';
		$this->mVar['row'] = '';
		$this->mVar['contents'] = '';
		$this->mVar['delete_flag'] = '';
	}
}

class UserDictionaryContentsDaoImpl extends AbstractDaoComposite implements ServiceGridUserDictionaryContentsDao {

	var $mTable = "user_dictionary_contents";
	var $mPrimaryAry = array('user_dictionary_id', 'language', 'row');
	var $mClass = "UserDictionaryContentsObject";
	public function getContents($userDictionaryId, $sourceLang = null, $targetLang = null, $sourceText = null, $hasHeader = false) {
		$userDictionaryContentsTable = $this->db->tableName('user_dictionary_contents');
		$id = intval($userDictionaryId);
		$sql  = '';
		$sql .= ' SELECT language,row,contents FROM '.$userDictionaryContentsTable.' ';
		$sql .= ' WHERE user_dictionary_id = %d AND delete_flag = 0 ';
		if ($hasHeader == false) {
			$sql .= ' AND row > 0';
		}
		$sql .= ' ORDER BY row, language';

		$sql = sprintf($sql, $id);
		$result = $this->db->query($sql);
		$dictionaries = array();
		while ($row = $this->db->fetchRow($result)) {
			$index = intval($row['row']) - 1;
			$dictionaries[$index][$row['language']] = $row['contents'];
			$dictionaries[$index]['row'] = $row['row'];
		}
		return $dictionaries;
	}
	public function getLanguages($userDictionaryId) {
		$params = array(
			'user_dictionary_id' => $userDictionaryId,
			'row' => '0',
			'delete_flag' => '0'
		);

		$objects =& $this->search($params);

		$languages = array();
		if (count($objects)) {
			foreach (array_keys($objects) as $key) {
				$languages[] = $objects[$key]->get('language');
			}
		}
		return $languages;
	}
	public function getUserDictionaryContents($bindingSetName, $sourceLang, $targetLang, $sourceText = "") {
		$contents =& $this->getContents($bindingSetName);
		$userDictArray = array();
		foreach ($contents as $record) {
			$srcWord = $record[$sourceLang];
			$tgtWord = $record[$targetLang];
			if (!empty($srcWord) && !empty($tgtWord)&& mb_stripos($sourceText, $srcWord) !== false) {
				$userDictArray[] = $this->makeSOAP_Value($srcWord, $tgtWord);
			}
		}
		return $userDictArray;
	}
	private function makeSOAP_Value($headWord, $targetWord) {
		$headWord = $this->_htmlEscape($headWord);
		$targetWord = $this->_htmlEscape($targetWord);
		$results = SoapValueCreation::createTemporalDictionaries(array(array(
				$headWord,
				$targetWord,
			)));
		return $results[0];
	}
	private function _htmlEscape($str) {
		return htmlspecialchars($str, ENT_COMPAT);
	}
	private function _getDictionaryIdByRequestPage() {
		global $wgTitle;
		$idUtil =& new LanguageGridArticleIdUtil();
		return $idUtil->getDictionaryIdByPageTitle(LanguageGridArticleIdUtil::getTitleDbKey());
	}
	function getContentsByLanguage($userDictionaryId, $language) {
		$params = array(
			'user_dictionary_id' => $userDictionaryId,
			'language' => $language,
			'delete_flag' => '0'
		);
		$objects =& $this->search($params, 'row');
		return $objects;
	}
	function deleteByLanguage($userDictionaryId, $language) {
		$_sql = 'DELETE FROM '.$this->mTable.' WHERE user_dictionary_id = \'%s\' and language = \'%s\'';
		$sql = sprintf($_sql, $userDictionaryId, $language);
		return $this->db->query($sql);
	}
	function deleteContents($userDictionaryId) {
		$_sql = 'DELETE FROM '.$this->mTable.' WHERE user_dictionary_id = \'%s\' and row > 0 ';
		$sql = sprintf($_sql, $userDictionaryId, $language);
		return $this->db->query($sql);
	}
	function getMaxRowNumber($articleId) {
		$sql = '';
		$sql .=  'SELECT max(row) AS N FROM '.$this->mTable.' ';
		$sql .= ' WHERE user_dictionary_id = %d ';
		$sql = sprintf($sql, $articleId);
		$result = $this->db->query($sql);
		if ($row = $this->db->fetchRow($result)) {
			return $row['N'];
		} else {
			return 0;
		}
	}
	function insert($obj) {
		if ($obj->isNew() == false) {
			return $this->update($obj);
		} else {
			$data = (array)$obj->getVars();
			return parent::insert($data);
		}
	}
	function update($object) {
		$data = (array)$object->getVars();
		return parent::update($data);
	}

	public function searchContents($userDictionaryId, $language, $word) {
		$userDictionaryTable = $this->db->tableName('user_dictionary');
		$userDictionaryContentsTable = $this->db->tableName('user_dictionary_contents');
		$id = intval($userDictionaryId);
		$sql  = '';
		$sql .= ' SELECT language,row,contents FROM '.$userDictionaryContentsTable.' ';
		$sql .= ' WHERE user_dictionary_id = %d AND delete_flag = 0 AND row > 0 ';
		$sql .= ' AND language = \'%s\' AND contents LIKE \'%s\'';
		$sql .= ' ORDER BY row, language';

		$sql = sprintf($sql, $id, $language, '%'.$word.'%');
		$dist = $this->db->query($sql);
		$ids = array();
		while ($row = $this->db->fetchRow($dist)) {
			$ids[] = $row['row'];
		}

		if (count($ids) == 0) {
			return array();
		}

		$sql  = '';
		$sql .= ' SELECT language,row,contents FROM '.$userDictionaryContentsTable.' ';
		$sql .= ' WHERE user_dictionary_id = %d AND delete_flag = 0 AND row > 0 ';
		$sql .= ' AND row IN (%s) ';
		$sql .= ' ORDER BY row, language';

		$sql = sprintf($sql, $id, implode(',', $ids));
		$result = $this->db->query($sql);

		$dictionaries = array();
		while ($row = $this->db->fetchRow($result)) {
			$index = intval($row['row']) - 1;
			$dictionaries[$index][$row['language']] = $row['contents'];
			$dictionaries[$index]['row'] = $row['row'];
		}

		return $dictionaries;
	}

	function delete($object) {
		$data = (array)$object->getVars();
		return parent::delete($data);
	}
	
}
?>

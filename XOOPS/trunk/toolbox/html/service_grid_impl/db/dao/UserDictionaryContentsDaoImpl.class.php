<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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
//require_once(dirname(__FILE__).'/../AbstractDao.class.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dao/ServiceGridUserDictionaryContentsDAO.interface.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/client/common/util/SoapValueCreation.class.php');
require_once(XOOPS_ROOT_PATH.'/api/class/handler/Toolbox_CompositeKeyGenericHandler.class.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');
/**
 * <#if locale="en">
 * User dictionary (page dictionary) database handler class
 * <#elseif locale="ja">
 * ユーザ辞書（ページ辞書）データベースハンドラクラス
 * </#if>
 */
class UserDictionaryContentsXoopsObject extends XoopsSimpleObject {

	function UserDictionaryContentsXoopsObject() {
		$this->initVar('user_dictionary_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('language', XOBJ_DTYPE_STRING, '', true, 30);
		$this->initVar('row', XOBJ_DTYPE_INT, 0, true);
		$this->initVar('contents', XOBJ_DTYPE_STRING, true);
		$this->initVar('delete_flag', XOBJ_DTYPE_STRING, '0', true, 1);
	}
}

class UserDictionaryContentsDaoImpl extends Toolbox_CompositeKeyGenericHandler implements ServiceGridUserDictionaryContentsDAO {

	var $mTable = "user_dictionary_contents";
	var $mPrimaryAry = array('user_dictionary_id', 'language', 'row');
	var $mClass = "UserDictionaryContentsXoopsObject";
	/**
	 * <#if locale="en">
	 * Get dictionary contents
	 * <#elseif locale="ja">
	 * 辞書コンテンツを取得
	 * </#if>
	 */
	public function getContents($userDictionaryId, $sourceLang = null, $targetLang = null, $sourceText = null) {
		$userDictionaryContentsTable = $this->db->prefix('user_dictionary_contents');
		$id = intval($userDictionaryId);
		$sql  = '';
		$sql .= ' SELECT language,row,contents FROM '.$userDictionaryContentsTable.' ';
		$sql .= ' WHERE user_dictionary_id = %d AND delete_flag = 0 ';
//		if ($hasHeader == false) {
//			$sql .= ' AND row > 0';
//		}
		$sql .= ' ORDER BY row, language';

		$sql = sprintf($sql, $id);
		$result = $this->db->query($sql);
		$dictionaries = array();
		while ($row = $this->db->fetchArray($result)) {
//			debugLog(print_r($row, true));
			$index = intval($row['row']) - 1;
			$dictionaries[$index][$row['language']] = $row['contents'];
			$dictionaries[$index]['row'] = $row['row'];
		}
		return $dictionaries;
	}
	public function getLanguages($userDictionaryId) {
		$table = $this->db->prefix('user_dictionary_contents');

		$sql = '';
		$sql .= ' SELECT `language` FROM '.$table.' AS T ';
		$sql .= ' WHERE `user_dictionary_id` = \'%s\' ';
		$sql .= '  AND `row` = 0 ';
		$sql .= '  AND `delete_flag` = 0 ';
		$sql .= ' ORDER BY `language` ';

		$languages = array();
		$result = $this->db->query(sprintf($sql, $userDictionaryId));
		while ($row = $this->db->fetchArray($result)) {
			$languages[] = $row['language'];
		}
		return $languages;
	}
	/**
	 * Return temporal dictionary data
	 */
	public function getUserDictionaryContents($bindingSetName, $sourceLang, $targetLang, $sourceText = "") {
		$userDictArray = array();
        if (! $bindingSetName || ! count($bindingSetName)) {
            // return empty array if there is no binding dictionaries.
            return $userDictArray;
        }

        debugLog("getUserDictionaryContents begin");
        // dictionary ids
        if (! is_array($bindingSetName)) {
            $bindingSetName = array($bindingSetName);
        }
        $bindings = implode(', ', $bindingSetName);

        // query texts
        $ngram = 3;
        if (strlen($sourceText)) {
            $mbchars = $this->_mbStringToArray($sourceText, $ngram);
            $lastIn = "LEFT(`contents`, ${ngram}) IN (" . implode(", ", $mbchars) . ")";
            if ($ngram > 1) {
                $m = " AND (( CHAR_LENGTH(`contents`) >= ${ngram} AND ${lastIn} )";
                for ($i = $ngram - 1; $i > 0; $i --) {
                    $mbchars = $this->_mbStringToArray($sourceText, $i);
                    $m .= " OR (CHAR_LENGTH(`contents`) = ${i} AND `contents` IN (" . implode(",", $mbchars) . ")) ";
                }
                $m .= ") ";
            } else {
                $m = " AND ${lastIn} ";
            }
        } else {
            // $m = 'AND `contents` REGEXP \'^$\'';
            $m = "AND `contents` = ''";
        }

		$table = $this->db->prefix('user_dictionary_contents');
        $sql = '';
        $sql .= "SELECT ";
        $sql .= "    src.contents AS `${sourceLang}` ";
        $sql .= "    , tgt.contents AS `${targetLang}` ";
        $sql .= "FROM ";
        $sql .= "    `${table}` tgt ";
        $sql .= "JOIN ";
        $sql .= "    (SELECT ";
        $sql .= "        `user_dictionary_id` ";
        $sql .= "        , `row` ";
        $sql .= "        , `language` ";
        $sql .= "        , `contents` ";
        $sql .= "     FROM ";
        $sql .= "        ${table} ";
        $sql .= "     WHERE ";
        $sql .= "        `user_dictionary_id` in (${bindings}) ";
        $sql .= "        AND `delete_flag` = 0 ";
        $sql .= "        AND `row` > 0 ";
        $sql .= "        AND `language` = '${sourceLang}' ";
        $sql .= "        ${m} ";
        $sql .= "     ORDER BY ";
        $sql .= "        FIELD(`user_dictionary_id`, ${bindings}) DESC ";
        $sql .= "        , `row` ASC ";
        $sql .= "    ) src ";
        $sql .= "ON src.`user_dictionary_id` = tgt.`user_dictionary_id` ";
        $sql .= "   AND src.`row` = tgt.`row` ";
        $sql .= "WHERE ";
        $sql .= "   tgt.`language` = '${targetLang}' ";
        $sql .= "   AND tgt.`delete_flag` = 0 ";

        debugLog($sql);
		$result = $this->db->query($sql);
		while ($row = $this->db->fetchArray($result)) {
			if(!empty($row[$targetLang])){
				$userDictArray[$row[$sourceLang]] =
					$this->makeSOAP_Value($row[$sourceLang], $row[$targetLang]);
			} else{
				debugLog("ignore empty target. (" . $sourceLang . ":" . $targetLang . ") = (" . $row[$sourceLang] . ":--empty--)");
			}
		}
        debugLog("found " . count($userDictArray) . " words in Dictionary.");
		return array_values($userDictArray);
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

    private function _mbStringToArray ($string, $grams = 1) { 
        $a = array();
        $strlen = mb_strlen($string); 
        while ($strlen) { 
            $a[] = $this->db->quoteString(mb_substr($string, 0, $grams, "UTF-8")); 
            $string = mb_substr($string, 1, $strlen, "UTF-8"); 
            $strlen = mb_strlen($string); 
        }
        sort($a);
        return array_unique($a);
    } 

	/**
	 *
	 * Get requested page ID
	 *
	 */
	private function _getDictionaryIdByRequestPage() {
		global $wgTitle;
		$idUtil = new LanguageGridArticleIdUtil();
		return $idUtil->getDictionaryIdByPageTitle(LanguageGridArticleIdUtil::getTitleDbKey());
	}

		/**
	 * <#if locale="en">
	 * Specify languages and get contents
	 * <#elseif locale="ja">
	 * 言語を指定してコンテンツを取得
	 * </#if>
	 */
	function getContentsByLanguage($userDictionaryId, $language) {
		$params = array(
			'user_dictionary_id' => $userDictionaryId,
			'language' => $language,
			'delete_flag' => '0'
		);

		$objects =& $this->search($params, 'row');

		return $objects;
	}

	/**
	 * <#if locale="en">
	 * Specify languages and delete all contents
	 * <#elseif locale="ja">
	 * 言語を指定して一括削除
	 * </#if>
	 */
	function deleteByLanguage($userDictionaryId, $language) {
		$_sql = 'DELETE FROM '.$this->mTable.' WHERE user_dictionary_id = \'%s\' and language = \'%s\'';
		$sql = sprintf($_sql, $userDictionaryId, $language);
		return $this->db->query($sql);
	}

	/**
	 * <#if locale="en">
	 * Delete all the dictionary contents
	 * <#elseif locale="ja">
	 * 辞書コンテンツを全削除
	 * </#if>
	 */
	function deleteContents($userDictionaryId) {
		$_sql = 'DELETE FROM '.$this->mTable.' WHERE user_dictionary_id = \'%s\' and row > 0 ';
		$sql = sprintf($_sql, $userDictionaryId, $language);
		return $this->db->query($sql);
	}

	/**
	 * <#if locale="en">
	 * Count of dictionary contents record
	 * <#elseif locale="ja">
	 * 辞書コンテンツのレコード件数
	 * </#if>
	 */
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

	function delete($object) {
		$data = (array)$object->getVars();

		return parent::delete($data);
	}

}
?>

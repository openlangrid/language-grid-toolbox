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
interface ServiceGridUserDictionaryContentsDAO {
	public function getLanguages($userDictionaryId);
	public function getContents($userDictionaryId, $source = null);
	public function getUserDictionaryContents($bindingSetName, $sourceLang, $targetLang, $sourceText = "");
	/**
	 * <#if locale="en">
	 * Specify languages and get contents
	 * <#elseif locale="ja">
	 * 言語を指定してコンテンツを取得
	 * </#if>
	 */
	public function getContentsByLanguage($userDictionaryId, $language);
	/**
	 * <#if locale="en">
	 * Specify languages and delete all contents
	 * <#elseif locale="ja">
	 * 言語を指定して一括削除
	 * </#if>
	 */
	public function deleteByLanguage($userDictionaryId, $language);
	/**
	 * <#if locale="en">
	 * Delete all the dictionary contents
	 * <#elseif locale="ja">
	 * 辞書コンテンツを全削除
	 * </#if>
	 */
	public function deleteContents($userDictionaryId);
	/**
	 * <#if locale="en">
	 * Count of dictionary contents record
	 * <#elseif locale="ja">
	 * 辞書コンテンツのレコード件数
	 * </#if>
	 */
	public function getMaxRowNumber($articleId);
	
	public function insert($obj);
	
	public function update($object);

	public function searchContents($userDictionaryId, $language, $word);

	function delete($object);
}
?>

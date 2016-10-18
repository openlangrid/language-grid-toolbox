<?php
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

	function delete($object);
}
?>
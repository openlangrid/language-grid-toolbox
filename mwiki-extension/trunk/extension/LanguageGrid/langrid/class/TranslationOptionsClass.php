<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
require_once(MYEXTPATH.'/service_grid/db/handler/TranslationOptionDbHandler.class.php');

/**
 * <#if locale="en">
 * Data manager class for translation options
 * <#elseif locale="ja">
 * 翻訳オプション関連のデータマネージャクラス
 * </#if>
 */
class TranslationOptions {
	private $svcSetting = null;

	function __construct() {
		$this->svcSetting =& new TranslationOptionDbHandler();
	}

	/**
	 * <#if locale="en">
	 * Search by article ID
	 * <#elseif locale="ja">
	 * 記事IDで検索
	 * </#if>
	 */
	function searchByArticleId($id) {
		$ret = $this->svcSetting->load($id);
		if(count($ret) > 0){
			$result['lite'] = ($ret[0]->getLiteFlag() == '1') ? true : false;
			$result['rich'] = ($ret[0]->getRichFlag() == '1') ? true : false;
			return $result;
		}else{
			return array('lite' => false, 'rich'=>false);
		}
	}

	/**
	 * <#if locale="en">
	 * Save translation options
	 * <#elseif locale="ja">
	 * 翻訳オプションを保存
	 * </#if>
	 */
	function saveTranslationOption($articleId, $data) {
		if ($articleId != null) {
			$this->doSaveTranslationOption(0,$articleId,$data);
		}
		return true;
	}

	private function doSaveTranslationOption($uid,$set_id,$data){
		$options = $this->svcSetting->load($set_id);
		if (count($options) == 0) {
			$this->svcSetting->add($uid, $set_id, $data['lite'], $data['rich']);
		} else {
			$option = $options[0];
			$option->setLiteFlag($data['lite']);
			$option->setRichFlag($data['rich']);
			$this->svcSetting->update($option);
		}

		return true;
	}
}
?>

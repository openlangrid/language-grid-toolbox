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

class TranslationOptionDbHandler {
	protected $db = null;
	protected $m_TranslationOptionDao = null;
	public function __construct() {
		// Set Adapter
		$adapter = DaoAdapter::getAdapter();
		$this->m_TranslationOptionDao = $adapter->getTranslationOptionDao();
	}
	public function getTranslationOptionDao() {
		return $this->m_TranslationOptionDao;
	}

	/** @OK
	 * <#if locale="en">
	 * Register translation option
	 * <#elseif locale="ja">
	 * 翻訳オプションを登録
	 * </#if>
	 *
	 * @return TranslationOptionObject
	 */
	function add($userId, $setId, $lite, $rich) {
		$translationOption =& $this->m_TranslationOptionDao->create(true);
		$translationOption->set('user_id', $userId);
		$translationOption->set('set_id', $setId);
		$translationOption->set('lite_flag', $lite);
		$translationOption->set('rich_flag', $rich);

		if ($this->m_TranslationOptionDao->insert($translationOption)) {
			return $translationOption;
		} else {
			die('SQL Error.'.__FILE__.'('.__LINE__.')');
		}
	}

	/**
	 * <#if locale="en">
	 * Save translation option in DB
	 *
	 * @param $translationOptionObject
	 * <#elseif locale="ja">
	 * 翻訳オプションをDBに保存
	 *
	 * @param $translationOptionObject
	 * </#if>
	 * @return bool
	 */
	function update($translationOptionObject) {
		if ($translationOptionObject != null) {
			$translationOptionObject->setUpdateTime(time());
			$this->m_TranslationOptionDao->update($translationOptionObject, true);
		}
		return true;
	}

	/**
	 * <#if locale="en">
	 * Load translation option
	 * <#elseif locale="ja">
	 * 翻訳オプションを取得
	 * </#if>
	 *
	 * @return TranslationOptionObject
	 */
	function load($setId) {
		return $this->m_TranslationOptionDao->queryBySetId($setId);
	}
	
}
?>

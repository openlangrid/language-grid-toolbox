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
require_once(MYEXTPATH.'/service_grid/db/handler/TranslationPathDbHandler.class.php');

/**
 * <#if locale="en">
 * Data manager class for default dictionary settings
 * <#elseif locale="ja">
 * デフォルト辞書関連のデータマネージャクラス
 * </#if>
 */
class DefaultDictionariesSetting {
	private $svcSetting = null;

	function __construct() {
		$this->svcSetting =& new TranslationPathDbHandler();
	}

	/**
	 * <#if locale="en">
	 * Search by article ID
	 * <#elseif locale="ja">
	 * 記事IDで検索
	 * </#if>
	 */
	function searchByArticleId($id) {
		$ret = $this->svcSetting->loadDefaultDictionary('0',$id);
		if($ret != null){
            $binds = $this->svcSetting->loadDefaultDictionaryBinds($ret->get('setting_id'));
			$ids = array(1=>array(),2=>array(),3=>array());
			if(is_array($binds)){
				foreach($binds as $bind){
					if ($bind->getBindType() == '1') {
						$ids[1][] = $bind->getBindValue();
					}
				}
			}
			$result['bind_global_dict_ids'] = implode(",",$ids[1]);
			$result['bind_local_dict_ids'] = implode(",",$ids[2]);
			$result['bind_user_dict_ids'] = implode(",",$ids[3]);
			return $result;
		}else{
			return array('bind_global_dict_ids'=>'','bind_local_dict_ids'=>'','bind_user_dict_ids'=>'');
		}
	}

	/**
	 * <#if locale="en">
	 * Save default dictionaries
	 * <#elseif locale="ja">
	 * デフォルト辞書を保存
	 * </#if>
	 */
	function savePageDictionaries($articleId, $data) {
		if ($articleId != null) {
			$this->saveDafaultDictionary(0,$articleId,$data);
		}
		return true;
	}

	private function saveDafaultDictionary($uid,$set_id,$data){
		$Setting = $this->svcSetting->loadDefaultDictionary($uid,$set_id);
		if($Setting == null){
			$Setting = $this->svcSetting->addDefaultDictionarySetting($uid,$set_id);
		}else{
			$this->svcSetting->removeDefaultDictionaryBind($Setting->get('setting_id'));
		}
        if(!empty($data['global_dict_ids'])) {
            $ids = explode(",",$data['global_dict_ids']);
            foreach($ids as $dict_id){
                $bind = $this->svcSetting->addDefaultDictionaryBind($Setting->get('setting_id'),'1',$dict_id);
            }
        }
		$ret = $this->updatePathSetting($uid,$set_id);
		return true;
	}

	private function updatePathSetting($uid,$set_id) {
		$this->svcSetting->updateDefaultDict4TranslationPath($uid,$set_id);
	}
}
?>

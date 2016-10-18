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
/**
 * <#if locale="en">
 * Class for setting hook for recognizing LanguageGrid extension
 * <#elseif locale="ja">
 * LanguageGridエクステンションをMediaWikiに認識させるHookを設定するクラス
 * </#if>
 */
class LanguageGridTabInstaller {
	function __construct( ) { }

	/**
	 * <#if locale="en">
	 * Hook SkinTemplateTabs and generate unique page tab
	 * @param $skin skin object
	 * @param $content_actions tab information
	 * <#elseif locale="ja">
	 * SkinTemplateTabsをホックして、独自のページタブを生成する
	 * @param $skin スキンオブジェクト
	 * @param $content_actions タブの情報
	 * </#if>
	 */
	function insertTabs($skin, &$content_actions) {
		global $wgRequest, $wgTitle;
		$idUtil =& new LanguageGridArticleIdUtil();
		if (!$idUtil->existsPage()) {
			return false;
		}
		/*
		 * <#if locale="en">
		 * Load Wiki message resource
		 * <#elseif locale="ja">
		 * WikiのメッセージリソースをLoad
		 * </#if>
 		 */
		wfLoadExtensionMessages( 'LanguageGrid' );

		if ($wgRequest->getCheck('pagedict') || $wgRequest->getCheck('setting')) {
			foreach ($content_actions as &$tab) {
				$tab['class'] = '';
				/*
				 * <#if locale="en">
				 * Cancel the original tab CSS since the unique tab is Active
				 * <#elseif locale="ja">
				 * 独自のタブがActiveにする為、本来のタブのCSSを解除
				 * </#if>
		 		 */
			}
            unset($content_actions['edit']);
		}
        
        $subjpage = $wgTitle->getSubjectPage();
		if ($wgRequest->getCheck('pagedict')) {
			$content_actions['LanguageGridPageDictionary'] = $this->makeTabPageDictionary($subjpage, "selected");
		} else {
			$content_actions['LanguageGridPageDictionary'] = $this->makeTabPageDictionary($subjpage);
		}

		if ($wgRequest->getCheck('setting')) {
			$content_actions['LanguageGridTranslationSetting'] = $this->makeTabTranslationSetting($subjpage, "selected");
		} else {
			$content_actions['LanguageGridTranslationSetting'] = $this->makeTabTranslationSetting($subjpage);
		}

//		$content_actions['LanguageGridTest'] = $this->makeTabTest($wgTitle);
//		$content_actions['LanguageGridDaoTest'] = $this->makeTabDaoTest($wgTitle);

		/*
		 * <#if locale="en">
		 * Return true and continue handling
		 * <#elseif locale="ja">
		 * trueを返して処理を継続
		 * </#if>
 		 */
		return true;
	}

	/**
	 * <#if locale="en">
	 * Hook CustomEditor and generate page that responds to unique Action
	 * <#elseif locale="ja">
	 * CustomEditorをホックして、独自Actionに応じたページを生成する
	 * </#if>
	 */
	function hookAction($article, $user) {
		global $wgRequest, $wgOut;

		if ($wgRequest->getCheck('pagedict')) {
			require_once(dirname(__FILE__).'/api/class/client/Wikimedia_LangridAccessClient.class.php');
			require_once(dirname(__FILE__).'/service_grid/dictionary/index.php');
			return false;
		}
		if ($wgRequest->getCheck('setting')) {
			/*
			 * <#if locale="en">
			 * Service refresh
			 * <#elseif locale="ja">
			 * サービスリフレッシュ
			 * </#if>
 			 */
//			require_once(dirname(__FILE__).'/langrid/include/refreshLangridService.php');

			$this->loadLanguageResources('langrid');
			require_once(dirname(__FILE__).'/langrid/index.php');
			return false;
		}
//		if ($wgRequest->getCheck('test')) {
//			require_once(dirname(__FILE__).'/test.php');
//			return false;
//		}
//		if ($wgRequest->getCheck('daotest')) {
//			require_once(dirname(__FILE__).'/service_grid_impl/index.php');
//			return false;
//		}
		/*
		 * <#if locale="en">
		 * action=edit
		 * <#elseif locale="ja">
		 * 通常のaction=editへ
		 * </#if>
 		 */
		return true;
	}

	/**
	 * <#if locale="en">
	 * Create information for page dictionary tab
	 * <#elseif locale="ja">
	 * ページ辞書タブの情報作成
	 * </#if>
	 */
	private function makeTabPageDictionary($wgTitle, $css = false) {
		global $wgRequest;
		return array(
			'class' => $css,
			'text' => wfMsgHTML('tab-label-dictionary'),
			'href' => $wgTitle->getLinkUrl('action=edit&pagedict')
		);
	}

	/**
	 * <#if locale="en">
	 * Create information for translation setting tab
	 * <#elseif locale="ja">
	 * 翻訳設定タブの情報作成
	 * </#if>
	 */
	private function makeTabTranslationSetting($wgTitle, $css = false) {
		return array(
			'class' => $css,
			'text' => wfMsgHTML('tab-label-setting'),
			'href' => $wgTitle->getLinkUrl('action=edit&setting')
		);
	}

//	private function makeTabTest($wgTitle) {
//		return array(
//			'class' => '',
//			'text' => 'test',
//			'href' => $wgTitle->getFullURL('action=edit&test')
//		);
//	}
//	private function makeTabDaoTest($wgTitle) {
//		return array(
//			'class' => '',
//			'text' => 'daotest',
//			'href' => $wgTitle->getFullURL('action=edit&daotest')
//		);
//	}

	/**
	 * <#if locale="en">
	 * Load defined language resource for Toolbox(Xoops)
	 * <#elseif locale="ja">
	 * Toolbox(Xoops)用に定義された言語リソースをロード
	 * </#if>
	 */
	function loadLanguageResources($module) {
		global $wgLanguageCode;
		switch ( $wgLanguageCode ) {
			case 'ja':
				require_once(dirname(__FILE__)."/$module/language/ja_utf8/modinfo.php");
				require_once(dirname(__FILE__)."/$module/language/ja_utf8/main.php");
				break;
			default:
				require_once(dirname(__FILE__)."/$module/language/english/modinfo.php");
				require_once(dirname(__FILE__)."/$module/language/english/main.php");
				break;
		}
	}
}

class LanguageGridArticleIdUtil {
	function __construct() {

	}

	/**
	 * <#if locale="en">
	 * This method returns if $dbKey is stored in PAGE table or not.
	 * Note: Used to determine if the tabs in Language Grid extension should be shown.
	 * When Article ID is undefined, you can't save a dictionary nor translation settings.
	 * <#elseif locale="ja">
	 * 記事本文、ノートに関係なく、$dbKeyがPAGEテーブルにエントリーされているか否か
	 * MEMO:言語グリッドエクステンションのタブを表示するかどうかの判定基準となる
	 * ArticleIDが未定の場合は、辞書も翻訳設定も保存しようがないので。
	 * </#if>
	 */
	function existsPage() {
		global $wgTitle;
		if ($wgTitle->exists()) {
			return true;
		}

		$t = $wgTitle->getSubjectPage();
		if ($t->exists()) {
			return true;
		}

		return false;
	}

	/**
	 * <#if locale="en">
	 * Get translation setting set ID from article title
	 * <#elseif locale="ja">
	 * 記事タイトルより、翻訳設定セットIDを取得する
	 * </#if>
	 */
	function getSetIdByPageTitle($dbKey) {
		$db =& wfGetDB(DB_MASTER);
		$_sql = 'SELECT set_id FROM %s WHERE set_name = \'%s\'';
		$sql = sprintf($_sql, $db->tableName('translation_set'), $dbKey);
		$dist =& $db->query($sql);
		if ($row = $dist->fetchRow()) {
			return $row['set_id'];
		}
		return 0;
	}

	/**
	 * <#if locale="en">
	 * Get translation setting ID from article title
	 * if the ID does not exists, register the set ID.
	 * <#elseif locale="ja">
	 * 記事タイトルより、翻訳設定セットIDを取得する
	 * 存在しない場合は、セットIDを新規登録する
	 * </#if>
	 */
	function checkSetIdByPageTitle($dbKey) {
		$setId = $this->getSetIdByPageTitle($dbKey);
		if ($setId == 0) {
			$db =& wfGetDB(DB_MASTER);
			$_sql = 'INSERT INTO %s (set_name, create_time) VALUES (\'%s\', \'%s\')';
			$sql = sprintf($_sql, $db->tableName('translation_set'), $dbKey, time());
			$dist = $db->query($sql);
			$setId = $db->insertId();
		}

		return $setId;
	}

	/**
	 * <#if locale="en">
	 * Get a page dictionary ID from article title
	 * <#elseif locale="ja">
	 * 記事タイトルより、ページ辞書IDを取得する
	 * </#if>
	 */
	function getDictionaryIdByPageTitle($dbKey) {
		$db =& wfGetDB(DB_MASTER);
		$_sql = 'SELECT user_dictionary_id FROM %s WHERE dictionary_name = \'%s\'';
		$sql = sprintf($_sql, $db->tableName('user_dictionary'), $dbKey);
		$dist =& $db->query($sql);
		if ($row = $dist->fetchRow()) {
			return $row['user_dictionary_id'];
		}
		return 0;
	}

	/**
	 *<#if locale="ja">
	 * 言語グリッドExtension内で利用する記事のキー情報を返す。
	 *  名前空間がTalkを含むものは、その元になっている名前空間に変換しておく。
	 * （１画面上にタブで表示されている本来は別記事管理を同一記事として扱うため。）
	 * </#if>
	 * @see /includes/Defines.php
	 */
	static function getTitleDbKey($title = null) {
		global $wgTitle;

		if ($title == null || is_a($title, "Title") === false) {
			$title = $wgTitle;
		}

		return $title->getSubjectPage()->getPrefixedDBkey();
	}
	
}
require_once(dirname(__FILE__).'/service_grid/db/adapter/DaoAdapter.class.php');
require_once(dirname(__FILE__).'/service_grid/db/dao/ServiceGridTranslationPathDAO.interface.php');
require_once(dirname(__FILE__).'/service_grid/db/dao/ServiceGridTranslationExecDAO.interface.php');
require_once(dirname(__FILE__).'/service_grid/db/dao/ServiceGridTranslationBindDAO.interface.php');
require_once(dirname(__FILE__).'/service_grid_impl/db/dao/TranslationPathDaoImpl.class.php');
require_once(dirname(__FILE__).'/service_grid_impl/db/dao/TranslationExecDaoImpl.class.php');
require_once(dirname(__FILE__).'/service_grid_impl/db/dao/TranslationBindDaoImpl.class.php');
class ServiceGridHooks {
	/**
	 * <#if locale="en">
	 * <#elseif locale="ja">
	 * 設定ファイル読み込み処理
	 * </#if>
	 */
	private function loadSettingFile($title, $file) {
		$json = file_get_contents($file);
		$objects = json_decode($json, true);
		error_log(print_r($object, true));
		// 翻訳パスの生成
		if ($this->addTranslationPathSetting($title, $objects)) {			
			$dttm = date('Y.m.d.H:i:s', time());
			$newFile = $file.'.'.$dttm;
			if (!rename($file, $newFile)) {
				error_log('rename failed.'.$file);
			}
		}
		return true;
	}
	/**
	 * <#if locale="en">
	 * <#elseif locale="ja">
	 * 設定ファイルチェック処理
	 * </#if>
	 */
	public function checkSettingFile() {
		$title = LanguageGridArticleIdUtil::getTitleDbKey();
		error_log('File Exists Check!!'.$title);
		$settingFileName = MYEXTPATH.'/setting_file/'.$title.'.json';
		if (file_exists($settingFileName)) {
			$this->loadSettingFile($title, $settingFileName);
		}
		return true;
	}
	/**
	 * <#if locale="en">
	 * <#elseif locale="ja">
	 * 記事表示時に翻訳パス生成
	 * </#if>
	 */
	protected function addTranslationPathSetting($title, $pathObjs) {
		$idUtil =& new LanguageGridArticleIdUtil();
		$setId = $idUtil->checkSetIdByPageTitle($title);
		$adapter = DaoAdapter::getAdapter();
		$translationPathDao = $adapter->getTranslationPathDao();
		$translationExecDao = $adapter->getTranslationExecDao();
		$translationBindDao = $adapter->getTranslationBindDao();
		// Clear!!!
		if (!$translationPathDao->deleteBySetId($setId)) {
			error_log('Translation Path Not Found.');
		}
		$objects = $pathObjs['path'];
		$pathId = 0;
//		error_log('%%%%%%%%%%%%%%%%%%%%%');
//		error_log(print_r($objects, true));
		foreach ($objects as $object) {
			$translationObj = new TranslationPathObject();
			$translationObj->set('path_name', '');
			$translationObj->set('user_id', 0);
			$translationObj->set('set_id', $setId);
			$translationObj->set('source_lang', $object['source_lang']);
			$translationObj->set('target_lang', $object['target_lang']);
			$translationObj->set('revs_path_id', $object['revs_path_id']);
			$translationObj->set('create_user_id', '0');
			$translationObj->set('update_user_id', '0');
			$translationObj->set('create_time', time());
			$translationObj->set('update_time', time());
//			error_log('######################');
//			error_log(print_r($translationObj, true));
			$pathId = $translationPathDao->insert($translationObj, true);
			$execId = 0; $execOrder = 0;
			foreach ($object['exec'] as $exec) {
				$translationExecObj = new TranslationExecObject();
				$translationExecObj->set('path_id', $pathId);
				$translationExecObj->set('exec_id', ++$execId);
				$translationExecObj->set('exec_order', ++$execOrder);
				$translationExecObj->set('source_lang', $exec['source_lang']);
				$translationExecObj->set('target_lang', $exec['target_lang']);
				$translationExecObj->set('service_type', $exec['service_type']);
				$translationExecObj->set('service_id', $exec['service_id']);
				$translationExecObj->set('dictionary_flag', $exec['dictionary_flag']);
//				$translationExecObj->set('revs_path_id', $exec[]);
				$translationExecObj->set('create_user_id', '0');
				$translationExecObj->set('update_user_id', '0');
				$translationExecObj->set('create_time', time());
				$translationExecObj->set('update_time', time());
				$translationExecDao->insert($translationExecObj, true);
				$bindId = 0;
//				error_log('**********************');
//				error_log(print_r($translationExecObj, true));
				foreach ($exec['bind'] as $bind) {
					$translationBindOjb = new TranslationBindObject();
					$translationBindOjb->set('path_id', $pathId);
					$translationBindOjb->set('exec_id', $execId);
					$translationBindOjb->set('bind_id', ++$bindId);
					$translationBindOjb->set('bind_type', $bind['bind_type']);
					$translationBindOjb->set('bind_value', $bind['bind_value']);
					$translationBindOjb->set('create_user_id', '0');
					$translationBindOjb->set('update_user_id', '0');
					$translationBindOjb->set('create_time', time());
					$translationBindOjb->set('update_time', time());
					$translationBindDao->insert($translationBindOjb, true);
//					error_log(print_r($translationBindOjb, true));
				}
			}
		}
		return true;
	}
	
	public function makeDirectory($path) {
		if (mkdir($path, 0766)) {
		} else {
			error_log("The directory was not made.".$title);
		}
		return;
	}
}
?>

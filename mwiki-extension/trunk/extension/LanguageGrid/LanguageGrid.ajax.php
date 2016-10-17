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
 * Ajax request controller class
 * <#elseif locale="ja">
 * Ajaxリクエストコントローラクラス
 * </#if>
 */
 require_once(dirname(__FILE__).'/LanguageGrid.php');
class LanguageGridAjaxController {

	/**
	 * <#if locale="en">
	 * Implement command patterns of Ajax request
	 * @param $action command
	 * @param $params data
	 * <#elseif locale="ja">
	 * Ajaxリクエストのコマンドパターンを実現する
	 * @param $action コマンド
	 * @param $params データ
	 * </#if>
	 */
	static function invoke($action, $params = array()) {
		$dist = null;
		switch ($action) {
			case 'Setting:Load':
				require_once(dirname(__FILE__).'/langrid/ajax/load-page-setting.php');
				$class = new LoadPageSetting();
				$dist = $class->dispatch($action, $params);
				break;
			case 'Setting:Save':
				require_once(dirname(__FILE__).'/langrid/ajax/save-page-setting.php');
				$class = new SavePageDict();
				$dist = $class->dispatch($action, $params);
				break;
			case 'Setting:DictSave':
				require_once(dirname(__FILE__).'/langrid/ajax/save-page-dict.php');
				$class = new SavePageDict();
				$dist = $class->dispatch($action, $params);
				break;
			case 'Setting:OptionSave':
				require_once(dirname(__FILE__).'/langrid/ajax/save-translation-option.php');
				$class = new SaveTranslationOption();
				$dist = $class->dispatch($action, $params);
				break;
			case 'ImportDictionary:Add':
			case 'ImportDictionary:Load':
			case 'ImportDictionary:Delete':
				require_once(dirname(__FILE__).'/service_grid/dictionary/ajax/ImportDictionary.class.php');
				$class = new ImportDictionary();
				$dist = $class->dispatch($action, $params);
				break;
			case 'Translation:Translate':
				require_once(dirname(__FILE__).'/api/class/client/Wikimedia_LangridAccessClient.class.php');
				require_once(dirname(__FILE__).'/service_grid/dictionary/ajax/Translation.class.php');
				$class = new Translation();
				$dist = $class->dispatch($action, $params);
				break;
			case 'Dictionary:Load':
				require_once(dirname(__FILE__).'/service_grid/dictionary/ajax/LoadDictionary.class.php');
				$class = new LoadDictionary();
				$dist = $class->dispatch($action, $params);
				break;
			case 'Dictionary:Save':
				require_once(dirname(__FILE__).'/service_grid/dictionary/ajax/SaveDictionary.class.php');
				$class = new SaveDictionary();
				$dist = $class->dispatch($action, $params);
				break;
			case 'Dictionary:Upload':
				require_once(dirname(__FILE__).'/service_grid/dictionary/ajax/SaveDictionary.class.php');
				$class = new SaveDictionary();
				$dist = $class->dispatch($action, $params);
				break;
			default:
				break;
		}

		$json = array('status' => 'Error');
		if ($dist) {
			$json = json_encode(array('status' => 'OK', 'contents' => $dist));
		}

		$response =& new AjaxResponse($json);
		$response->setContentType('application/json; charset=utf-8;');
		return $response;
	}
}

/**
 * <#if locale="en">
 * Basis of class for responding Ajax request
 * <#elseif locale="ja">
 * Ajaxリクエストに応答するクラスの基底
 * </#if>
 */
class LanguageGridAjaxRunner {

	function __construct() { }

	function dispatch($action, $params = array()) {
		return "no data";
	}

	protected function getParameters($params) {
		$data = array();

		$tokens = explode('&', urldecode($params));
		foreach ($tokens as $item) {
			$keyValue = explode('=', $item);
			$key = $keyValue[0];
			$value = $keyValue[1];

			$data[$key] = $value;
		}

		return $data;
	}
}
?>

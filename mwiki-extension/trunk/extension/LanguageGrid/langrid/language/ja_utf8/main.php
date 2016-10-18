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

//Used by Wiki
define('_MD_LANGRID_SEARCH_BOX_TITLE', '検索');
define('_MD_LANGRID_SETTING_FILTER_FROM', '翻訳元言語');
define('_MD_LANGRID_SETTING_FILTER_TO', '翻訳先言語');
define('_MD_LANGRID_SETTING_FILTER_BTN', '検索');
define('_MD_LANGRID_SETTING_DISPLAY_ALL_PATH', 'すべての翻訳パスを表示');

define('_MD_LANGRID_BBS_STG_ADD_PATH_BTN', '翻訳パスの追加');
define('_MD_LANGRID_ADD_PATH_DESC', '　*Customized dictionary(ies)');

define('_MD_LANGRID_MSG_PATH_NOT_FOUND', '新しい翻訳パス設定を作成してください。');

//Used by Toolbox
/* for PageTab Label */
define('_MD_LANGRID_TAB2_NAME', 'BBS');
define('_MD_LANGRID_TAB3_NAME', 'テキスト翻訳');
//20091112 add
define('_MD_LANGRID_TAB5_NAME', 'Web作成');

/* for BBS Page */
define('_MD_LANGRID_BBS_STG_SUBMIT_BTN', '保存');
define('_MD_LANGRID_BBS_STG_CONBINATION_BTN', '翻訳先言語の追加');
define('_MD_LANGRID_BBS_STG_DELETE_BTN', '削除');
define('_MD_LANGRID_BBS_STG_DICTIONARY_BTN', '辞書');
define('_MD_LANGRID_STG_ERR_MSG3', '翻訳パスに対する翻訳サービスの設定が不正です。');
define('_MD_LANGRID_STG_MSG3', '翻訳パス "%SRC %FLW %TGT" を削除しますか？');
define('_MD_LANGRID_DICT_POP_TITLE', '辞書');
define('_MD_LANGRID_DICT_POP_T1', 'グローバル辞書');
define('_MD_LANGRID_DICT_POP_T2', 'テンポラル辞書');
define('_MD_LANGRID_DICT_POP_OK', 'OK');
define('_MD_LANGRID_DICT_POP_CANCEL', 'キャンセル');
define('_MD_LANGRID_STG_ERR_MSG1', '翻訳サービスを選択してください。');
define('_MD_LANGRID_STG_MSG2', 'BBS用の翻訳パスの設定に成功しました。');
define('_MD_LANGRID_NOWLOADING', 'ロード中…');
//20090922 add
define('_MD_LANGRID_STG_MSG4', '翻訳パスの編集は管理者のみ可能です。');
define('_MD_LANGRID_SETTING_DEFAULT_DICTIONARY', 'デフォルト辞書');
define('_MD_LANGRID_SETTING_CUSTOM_DICTIONARY', 'カスタマイズ辞書');
define('_MD_LANGRID_SETTING_ADVANCED', '高度な設定');
define('_MD_LANGRID_INFO_POP_PROVIDER', '提供者');
define('_MD_LANGRID_INFO_POP_COPYRIGHT', '著作権情報');
define('_MD_LANGRID_INFO_POP_LICENSE', 'ライセンス情報');
define('_MD_LANGRID_SETTING_VIEW_DEFAULT_DICT', 'デフォルトの辞書を表示');
define('_MD_LANGRID_SETTING_CLOSE_BUTTON', '閉じる');
define('_MD_LANGRID_DICT_POP_LOAD_DEFAULT', 'デフォルト設定をロード');
define('_MD_LANGRID_SETTING_MATCHES', '件見つかりました');
define('_MD_LANGRID_SETTING_EDIT_DEFAULT_DICT', 'デフォルトの辞書を設定');
define('_MD_LANGRID_SETTING_EDIT_TRANSLATION_OPTIONS', '翻訳オプションを設定');
define('_MD_LANGRID_SETTING_RETURN_TOP', 'トップに戻る');
define('_MD_LANGRID_SETTING_DICT_SELECT', '%S種類の辞書が選択されています');
define('_MD_LANGRID_SETTING_DICT_NO_SELECT', '辞書が選択されていません');
define('_MD_LANGRID_SETTING_NO_TRANSLATION_OPTIONS', '翻訳オプションが設定されていません');
define('_MD_LANGRID_SETTING_LITE', '引用符付きフレーズの翻訳回避');
define('_MD_LANGRID_SETTING_RICH', '原語表示');
define('_MD_LANGRID_SETTING_ADD_BUTTON', '追加');
define('_MD_LANGRID_STG_ADDED_MESSAGE', '翻訳パス "%SRC %FLW %TGT" が追加されました。');
define('_MD_LANGRID_STG_SAVED_MESSAGE', '翻訳パス "%SRC %FLW %TGT" が変更されました。');
define('_MD_LANGRID_CONFIRM_CANCEL', '変更を破棄 しますか？');
//20090928 add
define('_MD_LANGRID_INFO_POP_DESCRIPTION', '説明');
//20091006 add
define('_MD_LANGRID_DICT_POP_MSG_NO_DICT', 'グローバル辞書が選択されていません。');
define('_MD_LANGRID_DICT_POP_MSG_NO_COM_DICT', 'テンポラル辞書が選択されていません。');
//20091019 add
define('_MD_LANGRID_DICT_POP_MORPHOLOGICAL_ANALYZER', '形態素解析');
//20091022 add
define('_MI_LANGRID_TEXT_HOW_TO_USE_LINK', 'settings_Text_ja.html');
define('_MI_LANGRID_BBS_HOW_TO_USE_LINK', 'settings_BBS_ja.html');
define('_MI_LANGRID_IMPORTED_SERVICES_HOW_TO_USE_LINK', 'import_ja.html');
define('_MI_LANGRID_WEB_HOW_TO_USE_LINK', 'settings_Web_ja.html');
define('_MD_LANGRID_DICT_POP_T3', 'ローカル辞書');
define('_MD_LANGRID_DICT_POP_MSG_NO_IMP_DICT', 'ローカル辞書が選択されていません。');
//20091027 add
define('_MD_LANGRID_DICT_MSG_NO_DICTIONARY_LOCAL', 'ローカル辞書がありません。');
define('_MD_LANGRID_DICT_MSG_NO_DICTIONARY_TEMPORAL', 'テンポラル辞書がありません。');
//20091029 add
define('_MD_LANGRID_DICT_MSG_OVER_SELECT_DICTIONARY', 'グローバル辞書・ローカル辞書は合計で5件まで選択できます。');

/* Imported Services */
define('_MD_LANGRID_IMPORTED_SERVICES', 'サービスのインポート');
define('_MD_LANGRID_IMPORTED_SERVICES_ADD_SERVICE', 'サービスの追加');
define('_MD_LANGRID_IMPORTED_SERVICES_EDIT_SERVICE', '編集');
define('_MD_LANGRID_IMPORTED_SERVICES_REMOVE_SERVICE', '削除');
define('_MD_LANGRID_IMPORTED_SERVICES_SERVICE_NAME_IS_IN_USE', 'サービス名「%s」は、すでに使われています。');
define('_MD_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL_IS_IN_USE', 'エンドポイントURL「%s」はすでにインポートされています。');
define('_MI_LANGRID_IMPORTED_SERVICES_ADD', '追加');
define('_MI_LANGRID_IMPORTED_SERVICES_ADD_SERVICE', 'サービスの追加');
define('_MI_LANGRID_IMPORTED_SERVICES_ARE_YOU_REALLY_SURE_YOU_WANT_TO_REMOVE_THE_SERVICE', '本当にそのサービスを削除してもよろしいですか？');
define('_MI_LANGRID_IMPORTED_SERVICES_AT_LEAST_ONE_LANGUAGE_PATH_IS_REQUIRED', '少なくとも一つの言語パスを選択してください。');
define('_MI_LANGRID_IMPORTED_SERVICES_AT_LEAST_TWO_LANGUAGES_ARE_REQUIRED', '少なくとも二つの言語を選択してください。');
define('_MI_LANGRID_IMPORTED_SERVICES_CANCEL', 'キャンセル');
define('_MI_LANGRID_IMPORTED_SERVICES_COPYRIGHT', '著作権');
define('_MI_LANGRID_IMPORTED_SERVICES_DICTIONARY', '辞書');
define('_MI_LANGRID_IMPORTED_SERVICES_BIDIRECTIONAL', '<->');
define('_MI_LANGRID_IMPORTED_SERVICES_EDIT_SERVICE', 'サービスの編集');
define('_MI_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL', 'エンドポイントURL');
define('_MI_LANGRID_IMPORTED_SERVICES_IMPORT', 'インポート');
define('_MI_LANGRID_IMPORTED_SERVICES_LANGUAGE', '言語');
define('_MI_LANGRID_IMPORTED_SERVICES_LANGUAGES', '言語');
define('_MI_LANGRID_IMPORTED_SERVICES_LICENSE', 'ライセンス');
define('_MI_LANGRID_IMPORTED_SERVICES_NO_IMPORTED_SERVICES', 'インポートされたサービスはありません。');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_IMPORTING', 'インポート中…');
define('_MI_LANGRID_IMPORTED_SERVICES_NO_SERVICE_IS_SELECTED', 'サービスが選択されていません。');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_LOADING', 'ロード中…');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_REMOVING', '削除中…');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_SAVING', '保存中…');
define('_MI_LANGRID_IMPORTED_SERVICES_NULL_LANGUAGE_NAME', '---');
define('_MI_LANGRID_IMPORTED_SERVICES_NULL_TABLE_VALUE', '<div class="align-center">-</div>');
define('_MI_LANGRID_IMPORTED_SERVICES_OK', 'OK');
define('_MI_LANGRID_IMPORTED_SERVICES_PROVIDER', '提供者');
define('_MI_LANGRID_IMPORTED_SERVICES_REGISTRATION_DATE', '登録日');
define('_MI_LANGRID_IMPORTED_SERVICES_REMOVE_LANGUAGE', '言語の削除');
define('_MI_LANGRID_IMPORTED_SERVICES_MONODIRECTIONAL', '->');
define('_MI_LANGRID_IMPORTED_SERVICES_SAVE', '保存');
define('_MI_LANGRID_IMPORTED_SERVICES_SERVICE_NAME', 'サービス名');
define('_MI_LANGRID_IMPORTED_SERVICES_SERVICE_TYPE', 'サービスタイプ');
define('_MI_LANGRID_IMPORTED_SERVICES_STRING_IS_BLANK', '{0}が空です。');
define('_MI_LANGRID_IMPORTED_SERVICES_TRANSLATOR', '翻訳');
define('_MI_LANGRID_IMPORTED_SERVICES_ON_AJAX_SERVER_ERROR', 'サーバーとの通信中にエラーが発生しました。お手数ですが、ページを更新してみてください。');
//20091030 add
define('_MI_LANGRID_IMPORTED_SERVICES_REQUIRED_FIELD', '*必須項目');
//20091127 add
define('_MI_LANGRID_IMPORTED_SERVICES_HAS_DUPLICATED_PATHS', '言語パスが重複しています。');
define('_MI_LANGRID_IMPORTED_SERVICES_HAS_SAME_PATHS', '翻訳元と翻訳先が同じ言語パスがあります。');
define('_MI_LANGRID_IMPORTED_SERVICES_THE_INPUT_URL_IS_INVALID', '入力されたURLは正しくありません。');


//Use is not confirmed by Toolbox
/* for PageTab Label */
define('_MD_LANGRID_TAB1_NAME', 'For User Setting');
define('_MD_LANGRID_TAB4_NAME', 'For BBS');

/* for User Setting Page */
define('_MD_LANGRID_SETTING_TRANSLATION', 'Translator');
define('_MD_LANGRID_SETTING_DICTIONARY', 'Dictionary');
define('_MD_LANGRID_SETTING_USER_DICTIONARY', 'Community Dictionary');

/* for BBS Page */
define('_MD_LANGRID_STG_MSG1', 'Translation paths for text translation were successfully saved.');
define('_MD_LANGRID_STG_ERR_MSG2', 'NoSelectedLanguages.');
define('_MD_LANGRID_STG_ERR_MSG4', 'その翻訳パスはすでに存在します。');

/* for Language Grid Service messages. use by /langrid/php/building-blocks/service.php */
if (!defined('_MD_LANGRID_ERROR_ACCESS_LIMIT_EXCEEDED_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_ACCESS_LIMIT_EXCEEDED_EXCEPTION', 'You have reached the limit access for service (ID:"%s", Name:"%s").');
}
if (!defined('_MD_LANGRID_ERROR_NO_ACCESS_PERMISSION_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_NO_ACCESS_PERMISSION_EXCEPTION', 'You do not have the appropriate permissions to execute the service (ID:"%s", Name:"%s").');
}
if (!defined('_MD_LANGRID_ERROR_NO_VALID_ENDPOINTS_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_NO_VALID_ENDPOINTS_EXCEPTION', 'No valid endpoints of the atomic service (ID:"%s", Name:"%s") to invoke. Please contact the service provider.');
}
if (!defined('_MD_LANGRID_ERROR_INVALID_PARAMETER_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_INVALID_PARAMETER_EXCEPTION', 'Service (ID:"%s", Name:"%s"): invalid parameter value for ("%s"). Please check the input data.');
}
if (!defined('_MD_LANGRID_ERROR_SERVICE_NOT_ACTIVE_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_SERVICE_NOT_ACTIVE_EXCEPTION', 'Service "%s" has been suspended in the Service Manager. Please check the service status.');
}
if (!defined('_MD_LANGRID_ERROR_SERVICE_NOT_FOUND_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_SERVICE_NOT_FOUND_EXCEPTION', 'Service "%s" has not been found. Please check the Service ID.');
}
if (!defined('_MD_LANGRID_ERROR_SERVICE_ALREADY_EXISTS_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_SERVICE_ALREADY_EXISTS_EXCEPTION', 'Service "%s" already exists. Please choose another Service ID.');
}
if (!defined('_MD_LANGRID_ERROR_NO_VALID_ENDPOINTS_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_NO_VALID_ENDPOINTS_EXCEPTION', 'Service "%s" at node (ID:"%s") does not have any valid endpoint. Please contact the service provider.');
}
if (!defined('_MD_LANGRID_ERROR_SERVICE_CONFIGURATION_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_SERVICE_CONFIGURATION_EXCEPTION', 'Service configuration error at node (ID:"%s").');
}
if (!defined('_MD_LANGRID_ERROR_UNKNOWN_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_UNKNOWN_EXCEPTION', 'Language grid server (node ID:%s) returned an unknown error. Please contact the language grid operator.');
}
if (!defined('_MD_LANGRID_ERROR_UNSUPPORTED_LANGUAGE_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_UNSUPPORTED_LANGUAGE_EXCEPTION', 'The language ("%s") that has been requested is not supported by the Service(ID:"%s", Name:"%s"). Please check the Service Manager.');
}
if (!defined('_MD_LANGRID_ERROR_UNSUPPORTED_LANGUAGE_PAIR_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_UNSUPPORTED_LANGUAGE_PAIR_EXCEPTION', 'The language pair ("%s:%s") that has been requested is not supported by the Service (ID:"%s", Name:"%s"). Please check the Service Manager.');
}
if (!defined('_MD_LANGRID_ERROR_UNKNOW_ERROR')) {
	define('_MD_LANGRID_ERROR_UNKNOW_ERROR', 'An unknown error ("%s") occurred in Language grid.');
}
if (!defined('_MD_LANGRID_ERROR_PROCESS_FAILED_EXCEPTION')) {
	define('_MD_LANGRID_ERROR_PROCESS_FAILED_EXCEPTION', 'Process Failed Exception occurred in Language grid. [%s]');
}

?>
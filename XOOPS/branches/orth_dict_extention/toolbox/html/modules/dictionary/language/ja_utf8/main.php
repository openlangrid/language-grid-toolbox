<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
// Copyright (C) 2009-2013  Department of Social Informatics, Kyoto University
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

//Used by Toolbox
define('_MI_DICTIONARY_NAME2', '言語資源名');
define('_MI_DICTIONARY_DICTIONARY_SEARCH', '検索');
define('_MI_DICTIONARY_LANGUAGES', '言語');
define('_MI_DICTIONARY_CREATE_RESOURCE', '作成');
define('_MI_DICTIONARY_IMPORT_RESOURCE', '言語資源のインポート');
define('_MI_DICTIONARY_SELECT_LANGUAGES_TO_DELETE', '削除する言語を選択してください。');
//20090919 add
define('_MI_DICTIONARY_HOW_TO_USE_LINK', 'dictionary_ja.html');
//20091022 add
define('_MI_DICTIONARY_PARALLEL_TEXT_HOW_TO_USE_LINK', 'paralleltext_ja.html');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University. All rights reserved.');

/**
 * Common
 */
define('_MI_DICTIONARY_COMMON_TYPE', '種類');
define('_MI_DICTIONARY_COMMON_LANGUAGES', '言語');
define('_MI_DICTIONARY_ERROR_FILE_REQUIRED', 'ファイルを指定してください。');
define('_MI_DICTIONARY_ERROR_FILE_FORMAT_INVALID', 'ファイル形式が不正です。');
//20090919 add
define('_MI_DICTIONARY_SERVICE', 'サービス');
define('_MI_DICTIONARY_DEPLOY', '配備');
define('_MI_DICTIONARY_UNDEPLOY', '配備解除');
define('_MI_DICTIONARY_WSDL', 'WSDL');

/**
 * Label
 */
define('_MI_DICTIONARY_COMMUNITY_RESOURCES', '作成・編集');
define('_MI_DICTIONARY_COMMUNITY_DICTIONARY', '辞書');
define('_MI_DICTIONARY_COMMUNITY_PARALLEL_TEXT', '用例対訳');
define('_MI_DICTIONARY_USER_DICTIONARY', '辞書');
define('_MI_DICTIONARY_USER_PARALLEL_TEXT', '用例対訳');
define('_MI_DICTIONARY_EDIT_PERMISSION', '編集を許可');
define('_MI_DICTIONARY_READ_PERMISSION', '閲覧を許可');
define('_MI_DICTIONARY_FOR_ALL_USERS', '全ユーザ');
define('_MI_DICTIONARY_FOR_THE_CURRENT_USER_ONLY', '現在のユーザのみ');
define('_MI_DICTIONARY_USER', '作成者');
define('_MI_DICTIONARY_READ', '閲覧');
define('_MI_DICTIONARY_EDIT', '編集');
define('_MI_DICTIONARY_LAST_UPDATE', '更新日');
define('_MI_DICTIONARY_COUNTS', 'レコード数');
define('_MI_DICTIONARY_SAVE_RESOURCE', '辞書を保存');
define('_MI_DICTIONARY_SAVE_RESOURCE_P', '用例対訳を保存');
//20090919 add
define('_MI_DICTIONARY_COMMON_RESOURCE_NAME', '言語資源名');
define('_MI_DICTIONARY_COMMON_RESOURCE_NAME_RULE', '言語資源名には、1文字以上の半角英文字と、半角数字・半角スペース・ハイフン "-"・ピリオド "." からなる4文字以上の文字列が指定できます。');
//20090926 add
define('_MI_DICTIONARY_COMMON_ADD_LANGUAGE', '言語の追加');
define('_MI_DICTIONARY_SELECT_LANGUAGES_TO_ADD', '追加する言語を選択してください。');
define('_MI_DICTIONARY_COMMON_DELETE_LANGUAGE', '言語の削除');

/**
 * Button
 */
define('_MI_DICTIONARY_CREATE', '作成');
define('_MI_DICTIONARY_IMPORT', 'インポート');
define('_MI_DICTIONARY_EXPORT', 'エクスポート');
define('_MI_DICTIONARY_LOAD', '見る');
define('_MI_DICTIONARY_REMOVE', '削除');
define('_MI_DICTIONARY_SAVE', '保存');
define('_MI_DICTIONARY_CLOSE', '閉じる');
define('_MI_DICTIONARY_ADD_RECORD', 'レコードの追加');
define('_MI_DICTIONARY_DELETE_RECORD', 'レコードの削除');
define('_MI_DICTIONARY_ADD_LANGUAGE', '言語の追加');
define('_MI_DICTIONARY_DELETE_LANGUAGE', '言語の削除');
define('_MI_DICTIONARY_ADD_WORD', '単語の追加');

/**
 * Status
 */
// Create
define('_MI_DICTIONARY_STATUS_CREATING_DICTIONARY', '辞書を作成中…');
define('_MI_DICTIONARY_STATUS_CREATING_DICTIONARY_P', '用例対訳を作成中…');
define('_MI_DICTIONARY_STATUS_DICTIONARY_CREATED', '辞書が作成されました。');
define('_MI_DICTIONARY_STATUS_PARALLEL_TEXT_CREATED', '用例対訳が作成されました。');

// Save
define('_MI_DICTIONARY_STATUS_DICTIONARY_SAVED', '辞書が保存されました。');
define('_MI_DICTIONARY_STATUS_PARALLEL_TEXT_SAVED', '用例対訳が保存されました。');

// Remove
define('_MI_DICTIONARY_NOW_REMOVING', '削除しています…');

// Read
define('_MI_DICTIONARY_STATUS_NOW_LOADING', 'ロード中…');

// Add Record
define('_MI_DICTIONARY_STATUS_RECORD_ADDED', '辞書にレコードを追加しました。');
define('_MI_DICTIONARY_STATUS_RECORD_ADDED_P', '用例対訳にレコードを追加しました。');

// Delete Record
define('_MI_DICTIONARY_STATUS_RECORD_DELETED', '選択したレコードを削除しました。');

// Add Language(s)
define('_MI_DICTIONARY_STATUS_LANGUAGE_ADDED', '<span style=color:red;>%s</span>を辞書に追加しました。');
define('_MI_DICTIONARY_STATUS_LANGUAGE_ADDED_P', '<span style=color:red;>%s</span>を用例対訳に追加しました。');

// Delete Language(s)
define('_MI_DICTIONARY_STATUS_LANGUAGE_DELETED', '<span style=color:red;>%s</span>を辞書から削除しました。');
define('_MI_DICTIONARY_STATUS_LANGUAGE_DELETED_P', '<span style=color:red;>%s</span>を用例対訳から削除しました。');

// PHP
// ERROR
define('_MI_DICTIONARY_ERROR_DICTIONARY_ALREADY_EXISTS', 'その名前はすでに使われています。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_ALREADY_EXISTS', 'その名前はすでに使われています。');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_LOAD', 'この辞書をロードする権限がありません。');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_LOAD_P', 'この用例対訳をロードする権限がありません。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NAME_EMPTY', '言語資源名を入力してください。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_NAME_EMPTY', '言語資源名を入力してください。');
define('_MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES', '辞書には2つ以上の言語が必要です。');
define('_MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES_P', '用例対訳には2つ以上の言語が必要です。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NAME_INVALID', '辞書名が無効です。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_NAME_INVALID', '用例対訳名が無効です。');
//20090919 add
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_DEPLOY', 'この辞書を配備する権限がありません。');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_UNDEPLOY', 'この辞書を配備解除する権限がありません。');

/**
 * JavaScript Error
 */
// Load
define('_MI_DICTIONARY_ERROR_UPLOAD_INVALID_LANGUAGE_TAG', '言語コード "%s"は無効です。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NOT_FOUND', '辞書または用例対訳を作成するか、インポートしてください。');

//Confirm
define('_MI_DICTIONARY_CONFIRM_DELETE_LANGUAGE_ROWS', '選択した言語を削除しますか？');
define('_MI_DICTIONARY_CONFIRM_DELETE_SELECTED_COLUMNS', '選択したレコードを削除しますか？');
//20090919 add
define('_MI_DICTIONARY_CONFIRM_DEPLOY_DICT', '辞書を配備しますか？');
define('_MI_DICTIONARY_CONFIRM_UNDEPLOY_DICT', '辞書を配備解除しますか？');
define('_MI_DICTIONARY_CONFIRM_DEPLOY_PARALLEL', '用例対訳を配備しますか？');
define('_MI_DICTIONARY_CONFIRM_UNDEPLOY_PARALLEL', '用例対訳を配備解除しますか？');
//20090925 add
define('_MI_DICTIONARY_ERROR_DICTIONARY_IS_NOT_DEPLOYED', '選択された辞書は配備されていません。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_NOT_DEPLOYED', '選択された用例対訳は配備されていません。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_IS_ALREADY_DEPLOYED', '選択された辞書はすでに配備されています。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_ALREADY_DEPLOYED', '選択された用例対訳はすでに配備されています。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_DEPLOY_IS_NOT_PERMITTED', '選択された辞書を配備する権限がありません。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_NOT_PERMITTED', '選択された用例対訳を配備する権限がありません。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_UNDEPLOY_IS_NOT_PERMITTED', '選択された辞書を配備解除する権限がありません。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_UNDEPLOY_IS_NOT_PERMITTED', '選択された用例対訳を配備解除する権限がありません。');
//20091001 add
define('_MI_DICTIONARY_ERROR_RECORD_NO_SELECTED', '辞書が選択されていません。');
define('_MI_DICTIONARY_ERROR_RECORD_NO_SELECTED_P', '用例対訳が選択されていません。');
//20091021 add
define('_MI_DICTIONARY_ERROR_KEYWORD_IS_EMPTY', 'キーワードが空です。');
define('_MI_DICTIONARY_ERROR_SELECT_A_LANGUAGE', '検索する対訳の言語を選択してください。');
define('_MI_DICTIONARY_ERROR_TOO_MUCH_SELECTED_LANGUAGES', '3言語以上選択する事はできません。');
define('_MI_DICTIONARY_ERROR_SEARCH_RESULT_OVERSHOOT', '検索結果が多すぎるため、表示できません（%s件）。検索条件を変更してください。');
//20110111 add
define('_MI_DICTIONARY_ERROR_DICTIONARY_CONFLICT', '編集中に他のユーザによって更新されています。上書きしますか？');

/**
 * Warning
 */
define('_MI_DICTIONARY_WARNING_NO_MORE_LANGUAGES', '追加できる言語がありません。');
define('_MI_DICTIONARY_WARNING_DISCARD_CHANGES', '辞書が保存されていません。変更を破棄しますか？');
define('_MI_DICTIONARY_WARNING_PARALLEL_TEXT_DISCARD_CHANGES', '用例対訳が保存されていません。変更を破棄しますか？');
define('_MI_DICTIONARY_WARNING_REMOVE_DICTIONARY', 'この辞書を削除しますか？');
define('_MI_DICTIONARY_WARNING_REMOVE_DICTIONARY_P', 'この用例対訳を削除しますか？');
define('_MI_DICTIONARY_WARNING_CANNOT_DELETE_LANGUAGES', '辞書には2つ以上の言語が必要です。');
define('_MI_DICTIONARY_WARNING_CANNOT_DELETE_LANGUAGES_P', '用例対訳には2つ以上の言語が必要です。');
//20090919 add
define('_MI_DICTIONARY_SEARCH_DICTIONARY_NOT_FOUND', '指定したキーワードを含む辞書のレコードが見つかりませんでした。');
define('_MI_DICTIONARY_SEARCH_PARALLEL_TEXT_NOT_FOUND', '指定したキーワードを含む用例対訳のレコードが見つかりませんでした。');
define('_MI_DICTIONARY_DICTIONARY_REMOVE_ERROR_NO_PERMISSION', '選択された辞書を削除する権限がありません。');
define('_MI_DICTIONARY_PARALLEL_TEXT_REMOVE_ERROR_NO_PERMISSION', '選択された用例対訳を削除する権限がありません。');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_DOWNLOAD_RESOURCE', '選択された言語資源をエクスポートする権限がありません．');

/**
 * Search
 */
define('_MI_DICTIONARY_SEARCH_KEYWORD', 'キーワード');
define('_MI_DICTIONARY_SEARCH_TO', 'To');
define('_MI_DICTIONARY_SEARCH_SELECT_TYPE', '種類');
define('_MI_DICTIONARY_SEARCH_SELECT_ALL', '全てを選択');
define('_MI_DICTIONARY_SEARCH_MATCHING_METHOD', '検索条件');
define('_MI_DICTIONARY_SEARCH_PARTIAL', '部分一致');
define('_MI_DICTIONARY_SEARCH_COMPLETE', '全文一致');
define('_MI_DICTIONARY_SEARCH_PREFIX', '前方一致');
define('_MI_DICTIONARY_SEARCH_SUFFIX', '後方一致');
define('_MI_DICTIONARY_SEARCH_FROM', 'From');


//Use is not confirmed by Toolbox
//define('_MI_DICTIONARY_NAME', 'User Dictionary');/
define('_MI_DICTIONARY_NAME', 'Language Resources');
define('_MI_DICTIONARY_DESC', 'This is a module to create community dictionaries and parallel texts. Community dictionaries can be combined with machine translators. Community dictionaries and parallel texts can be searched by keywords.');

define('_MI_DICTIONARY_DICTIONARY_NAME', 'Dictionary name');
define('_MI_DICTIONARY_PARALLEL_TEXT_NAME', 'Parallel text name');

define('_MI_DICTIONARY_SUPPORTED_LANGUAGES', 'Supported Languages');


/**
 * Common
 */
define('_MI_DICTIONARY_COMMON_FILE', 'File');

/**
 * Label
 */
//define('_MI_DICTIONARY_COMMUNITY_DICTIONARY', 'Community dictionary');
//define('_MI_DICTIONARY_COMMUNITY_PARALLEL_TEXT', 'Community parallel text');
//define('_MI_DICTIONARY_USER_DICTIONARY', 'Community Dictionary');
//define('_MI_DICTIONARY_USER_PARALLEL_TEXT', 'Community Parallel Text');

//define('_MI_DICTIONARY_USER', 'Creater');

define('_MI_DICTIONARY_SAVE_RESOURCE_TO_SERVER', 'Save dictionary to server');
define('_MI_DICTIONARY_SAVE_RESOURCE_TO_SERVER_P', 'Save parallel text to server');

/**
 * Button
 */
define('_MI_DICTIONARY_NOT_CREATED', 'Not Created yet.');
define('_MI_DICTIONARY_YOU_CAN_CREATE', 'You can create your original dictionary.');


// Common
define('_MI_DICTIONARY_LABEL_DICTIONARY_NAME_RULE', 'Dictionary name can contain only(one-byte)English characters, numerals, undersore "_", hyphen "-", space " " and period ".".');
define('_MI_DICTIONARY_LABEL_DICTIONARY_NAME_RULE_P', 'Parallel text name can contain only(one-byte)English characters, numerals, undersore "_", hyphen "-", space " " and period ".".');


/**
 * Status
 */
// Create
//define('_MI_DICTIONARY_STATUS_DICTIONARY_CREATED', 'Your dictionary was created.');
//define('_MI_DICTIONARY_STATUS_PARALLEL_TEXT_CREATED', 'Your parallel text was created.');

// Load
define('_MI_DICTIONARY_STATUS_LOADED', 'Dictionary was loaded.');
define('_MI_DICTIONARY_STATUS_NOT_LOADED', 'Dictionary was not loaded.');
define('_MI_DICTIONARY_STATUS_LOADED_P', 'Parallel text was loaded.');
define('_MI_DICTIONARY_STATUS_NOT_LOADED_P', 'Parallel text was not loaded.');

// Upload
define('_MI_DICTIONARY_STATUS_UPLOADED', 'Dictionary was uploaded.');
define('_MI_DICTIONARY_STATUS_NOT_UPLOADED', 'Dictionary was not uploaded.');
define('_MI_DICTIONARY_STATUS_NOW_UPLOADING', 'Now uploading ...');


// Add Record
//define('_MI_DICTIONARY_STATUS_RECORD_ADDED', 'Record is added to the dictionary.');
//define('_MI_DICTIONARY_STATUS_RECORD_ADDED_P', 'Record is added to the parallel text.');

// Delete Record
//define('_MI_DICTIONARY_STATUS_RECORD_DELETED',  'Record has been deleted.');

// Add Language(s)
define('_MI_DICTIONARY_STATUS_LANGUAGES_ADDED',  '%s were added to the dictionary.');
define('_MI_DICTIONARY_STATUS_LANGUAGES_ADDED_P',  '%s were added to the parallel text.');

// Delete Language(s)
define('_MI_DICTIONARY_STATUS_LANGUAGES_DELETED',  '%s were deleted from the dictionary.');
define('_MI_DICTIONARY_STATUS_LANGUAGES_DELETED_P',  '%s were deleted from the parallel text.');


// PHP
// ERROR
define('_MI_DICTIONARY_ERROR_DICTIONARY', 'Dictionary ID is No post parameter.');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_EDIT', 'You have no permission to edit the dictionary.');
//define('_MI_DICTIONARY_ERROR_DICTIONARY_ALREADY_EXISTS', 'The dictionary already exists on the server. Please change the dictionary name and save it.');
//define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_ALREADY_EXISTS', 'The parallel text already exists on the server. Please change the parallel text name and save it.');
//define('_MI_DICTIONARY_ERROR_DICTIONARY_NAME_EMPTY', 'Dictionary name is empty');
//define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_NAME_EMPTY', 'Parallel text name is empty');
//define('_MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES', 'At least two languages are required in your dictionary.');
//define('_MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES_P', 'At least two languages are required in your parallel text.');


/**
 * JavaScript Error
 */

// Load
define('_MI_DICTIONARY_ERROR_DICTIONARY_FILE_ERROR', 'Dictionary File Error: ');
define('_MI_DICTIONARY_ERROR_DICTIONARY_WAS_NOT_UPLOADED', 'Dictionary was not uploaded.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_IS_EMPTY', 'File Format Error: File is empty.');
define('_MI_DICTIONARY_ERROR_INVALID_LANGUAGE_TAG', 'File Format Error: %s is Invalid Language Tag.');
define('_MI_DICTIONARY_LANGUAGE_TAG_INVALID', 'The language tag "%s" is invalid.');
define('_MI_DICTIONARY_ERROR_MULTIPLE_ROWS', 'File Format Error: Contains Multiple %s Rows.');
define('_MI_DICTIONARY_ERROR_WORD_TOO_LONG', 'Part of your input was too long and cut into 80 characters.');
define('_MI_DICTIONARY_ERROR_UNKNOWN_ERROR_OCCURED', 'Unknown Error occured.');
define('_MI_DICTIONARY_ERROR_SERVER_ERROR', 'Server Error.');
//define('_MI_DICTIONARY_ERROR_WAS_TOO_LONG_EDIT', 'Dictionary record is too long. Please input each record within 80 characters.');
define('_MI_DICTIONARY_ERROR_WAS_TOO_LONG_EDIT', 'The word is too long. Length of each item should be within 80 characters.');
define('_MI_DICTIONARY_ERROR_INVALID_DICTIONARY_NAME', 'Invalid Dictionary file name');
define('_MI_DICTIONARY_ERROR_NOT_SAVED', "The current dictionary was not saved.");
define('_MI_DICTIONARY_ERROR_NOT_SAVED_P', "The current parallel text was not saved.");
//define('_MI_DICTIONARY_ERROR_AT_LEAST_TWO_LANGUAGES', 'Your dictionary must cover at least two languages.');
define('_MI_DICTIONARY_ERROR_AT_LEAST_TWO_LANGUAGES', 'At least two languages are required in the dictionary. ');
//define('_MI_DICTIONARY_ERROR_DICTIONARY_NOT_FOUND', 'Any dictionaries were not found on the server.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NOT_FOUND', 'Please create or import a dictionary / parallel text first.');
define('_MI_DICTIONARY_ERROR_CANNOT_BE_DOWNLOADED', "Error: Empty dictionary cannot be downloaded.");

//Server Error
define('_MI_DICTIONARY_ERROR_FAILED_TO_LOAD_SUPPORTED_LANGUAGE', 'Failed to load supported langauges.');
define('_MI_DICTIONARY_ERROR_FAILED_TO_REMOVE', 'Failed to remove.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_FAILURED', 'Failed to save your dictionary on the server.');



/**
 * Warning
 */
//define('_MI_DICTIONARY_WARNING_DISCARD_CHANGES', 'Your dictionary has not been saved after the last change. Discard changes?');
//define('_MI_DICTIONARY_WARNING_PARALLEL_TEXT_DISCARD_CHANGES', 'Your parallel text has not been saved after the last change. Discard changes?');
//define('_MI_DICTIONARY_WARNING_REMOVE_DICTIONARY', 'Removed dictionary will be deleted from the server and \n cannot be restored.\n\n Are you really sure you want to remove the dicitionary?');
//define('_MI_DICTIONARY_WARNING_REMOVE_DICTIONARY_P', 'Removed parallel text will be deleted from the server and \n cannot be restored.\n\n Are you really sure you want to remove the parallel text?');

//define('_MI_DICTIONARY_WARNING_CANNOT_DELETE_LANGUAGES', 'You cannot delete languages \n because the dictionary must cover at least two languages.');
//define('_MI_DICTIONARY_WARNING_CANNOT_DELETE_LANGUAGES_P', 'You cannot delete languages \n because the parallel text must cover at least two languages.');



define('_MI_DICTIONARY_RETURN_TO_TOP', 'TOPへ戻る');
define('_MI_DICTIONARY_OK', 'OK');
define('_MI_DICTIONARY_CANCEL', 'キャンセル');
define('_MI_DICTIONARY_LANGUAGE', '言語');
define('_MI_DICTIONARY_ALL_LANGUAGES', '全ての言語');
define('_MI_DICTIONARY_OTHER', 'その他');
define('_MI_DICTIONARY_CONFIRM', '選択');

define('_MI_DICTIONARY_COMMUNITY_PARAPHRASE_DICTIONARY', '言い換え辞書');
define('_MI_DICTIONARY_COMMUNITY_NORMALIZE_DICTIONARY', '表記統合辞書');
define('_MI_DICTIONARY_STATUS_NOW_SAVING', '保存中...');


?>
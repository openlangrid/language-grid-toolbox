<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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

//Used by Toolbox
define('_MI_WEBTRANS_TOOL_NAME', 'Web作成');
define('_MI_WEBTRANS_HOW_TO_USE_LINK', 'webtrans_ja.html');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009-2010 Language Grid Project, NICT. All rights reserved.');

// label
define('_MI_WEBTRANS_LABEL_TRANSLATE', '翻訳');
define('_MI_WEBTRANS_LABEL_WEBPAGEURL', 'WebページURL');
define('_MI_WEBTRANS_LABEL_APPLY_TEMPLATE', 'テンプレートの適用');
define('_MI_WEBTRANS_LABEL_ORIGINAL_WEBPAGE', '翻訳元のWebページ');
define('_MI_WEBTRANS_LABEL_TRANSLATED_WEBPAGE', '翻訳後のWebページ');
define('_MI_WEBTRANS_LABEL_BACK_TRANSLATED_WEBPAGE', '折り返し翻訳後のWebページ');
define('_MI_WEBTRANS_LABEL_LICENSE_INFORMATION', 'ライセンス情報');
define('_MI_WEBTRANS_LABEL_CREATEANDEDITTEMPLATE', 'テンプレートの作成・編集');
define('_MI_WEBTRANS_LABEL_CREATE_PAIR', 'ペアの作成');
define('_MI_WEBTRANS_LABEL_LIST_ALL_PAIR', 'ペア一覧');

// button
define('_MI_WEBTRANS_TOOL_BUTTON_IMPORT_WEBPAGE', '取得');
define('_MI_WEBTRANS_TOOL_BUTTON_DISPLAY_WEBPAGE', '表示');
define('_MI_WEBTRANS_TOOL_BUTTON_LOAD_TEMPLATE', 'テンプレートのロード');
define('_MI_WEBTRANS_TOOL_BUTTON_UPLOAD_TEMPLATE', 'テンプレートのアップロード');
define('_MI_WEBTRANS_TOOL_BUTTON_TRANSLATE', '翻訳');
define('_MI_WEBTRANS_TOOL_BUTTON_CANCEL', '取消');
define('_MI_WEBTRANS_TOOL_BUTTON_UPLOAD', 'アップロード');
define('_MI_WEBTRANS_TOOL_BUTTON_DOWNLOAD', 'ダウンロード');
define('_MI_WEBTRANS_TOOL_BUTTON_UNDO', 'Undo');
define('_MI_WEBTRANS_TOOL_BUTTON_DISPLAY', '表示');
define('_MI_WEBTRANS_TOOL_BUTTON_ADD_TEMPLATE', 'テンプレートにペアを追加');
define('_MI_WEBTRANS_TOOL_BUTTON_SAVE_TEMPLATE', 'テンプレートの保存');
define('_MI_WEBTRANS_TOOL_BUTTON_DOWNLOAD_TEMPLATE', 'テンプレートのダウンロード');

// information
define('_MI_WEBTRANS_TOOL_TRANSLATION_DOING', '翻訳中 ...');

// messages
define('_MI_WEBTRANS_MSG_URL_INVALID', 'URLが無効です。');
define('_MI_WEBTRANS_TOOL_BACKTRANSLATION_MESSAGE', '折り返し翻訳語のWebページはこちらに表示されます。 この領域は編集できません。');
define('_MI_WEBTRANS_POPUP_UPLOAD_HTML', 'HTMLファイルをアップロードします。');
define('_MI_WEBTRANS_POPUP_DOWNLOAD_HTML', 'HTMLファイルをダウンロードします。');
define('_MI_WEBTRANS_POPUP_LOAD_TEMPLATE', 'テンプレートをサーバーから読み込みます。');
define('_MI_WEBTRANS_POPUP_UPLOAD_TEMPLATE', 'テンプレートファイルをアップロードします。');
define('_MI_WEBTRANS_POPUP_SAVE_TEMPLATE', 'テンプレートをサーバーに保存します。');
define('_MI_WEBTRANS_POPUP_SAVE_MESSAGE', 'テンプレート名には半角英数字、アンダースコア"_"、ハイフン"-"のみ使用できます。');
define('_MI_WEBTRANS_POPUP_DOWNLOAD_TEMPLATE', 'テンプレートファイルをダウンロードします。');
define('_MI_WEBTRANS_POPUP_FILE_NAME', 'ファイル名');
define('_MI_WEBTRANS_POPUP_TEMPLATE_NAME', 'テンプレート名');
define('_MI_WEBTRANS_POPUP_LOAD_ANOTHER_TEMPLATE', '他のテンプレートを読み込む');
define('_MI_WEBTRANS_POPUP_UPLOAD_ANOTHER_TEMPLATE', '他のテンプレートをアップロード');

// javascript message
define('_MI_WEBTRANS_JS_TRANSLATE_ERROR', '言語グリッドでエラーが発生しました。');
define('_MI_WEBTRANS_JS_SERVER_ERROR', 'サーバーからの応答が不正です。');
define('_MI_WEBTRANS_JS_NO_MORE_SMALL_AREA', 'テキストエリアのサイズをこれ以上小さくすることはできません。');
define('_MI_WEBTRANS_JS_ORIGINAL_INIT_MESSAGE', '翻訳元のWebページはこちらに表示されます。 この領域は編集できます。');
define('_MI_WEBTRANS_JS_TRANSLATED_INIT_MESSAGE', '翻訳後のWebページはこちらに表示されます。 この領域は編集できます。');
define('_MI_WEBTRANS_JS_TEMPLATE_INIT_MESSAGE', 'ここにHTMLソースを貼り付けて下さい。');
define('_MI_WEBTRANS_SERVICE_NAME', 'サービス名');
define('_MI_WEBTRANS_COPYRIGHT', '著作権情報');
define('_MI_WEBTRANS_TOOL_BUTTON_DELETE_PAIR', 'ペアの削除');
define('_MI_WEBTRANS_JS_FIELD_ADD_ERROR', 'これ以上の追加はできません。');
define('_MI_WEBTRANS_JS_FILE_NAME_ERROR', 'ファイル名が不正です。');
define('_MI_WEBTRANS_JS_TEMPLATE_NAME_ERROR', 'テンプレート名が不正です。');
define('_MI_WEBTRANS_JS_NO_TEMPLATE_MSG', '{0} が見つかりません。');
define('_MI_WEBTRANS_JS_TEMPLATE_OVER_MAX', '{0}件のテンプレートが既に読み込まれています。');
define('_MI_WEBTRANS_JS_NO_PAIR_MSG', 'ペアがありません。');
define('_MI_WEBTRANS_JS_CONFIRM_OVERWRITE', '"{0}"は既に存在します。. \n上書きしてもよろしいですか?');
define('_MI_WEBTRANS_JS_SAVE_COMPLATE', 'セーブが完了しました。');
define('_MI_WEBTRANS_JS_TEMPLATE_EMPTY', 'テンプレートが空です!!');
define('_MI_WEBTRANS_POPUP_TITLE_LOAD_TEMPLATE', 'テンプレートのロード');
define('_MI_WEBTRANS_POPUP_TITLE_UPLOAD_TEMPLATE', 'テンプレートのアップロード');
define('_MI_WEBTRANS_POPUP_TITLE_UPLOAD_HTML', 'HTMLファイルのアップロード');
define('_MI_WEBTRANS_POPUP_TITLE_DOWNLOAD_HTML', 'HTMLファイルのダウンロード');
define('_MI_WEBTRANS_POPUP_TITLE_SAVE_TEMPLATE', 'テンプレートのセーブ');
define('_MI_WEBTRANS_POPUP_TITLE_DOWNLOAD_TEMPLATE', 'テンプレートのダウンロード');
define('_MI_WEBTRANS_JS_ABORT_TRANSLATION', '翻訳を中止しています...');
define('_MI_WEBTRANS_JS_TRANSLATION_INITIALIZEING', '[...初期化中...]');
define('_MI_WEBTRANS_JS_APPLYING_TEMPLATE', '[...テンプレートの適用中...]');
define('_MI_WEBTRANS_JS_ANALYSIS_HTML', '[...HTMLの解析中...]');
define('_MI_WEBTRANS_JS_LICENSE_AREA_MSG', '翻訳に使用される資源のライセンス情報はこちらに表示されます。');
//20100302 add
define('_MI_WEBTRANS_JS_SESSIONTIMEOUT', 'ログインセッションがタイムアウトしました。');
define('_MI_WEBTRANS_JS_DISPLAY_SAVEERROR', '表示コンテンツの一時保存に失敗しました。');

?>
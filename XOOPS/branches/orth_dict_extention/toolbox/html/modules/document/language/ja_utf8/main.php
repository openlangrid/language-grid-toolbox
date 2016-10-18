<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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

//Used by Toolbox
define('_MI_DOCUMENT_TOOL_NAME', 'テキスト翻訳');
//20090919 add
define('_MI_DOCUMENT_HOW_TO_USE_LINK', 'document_ja.html');

 // button
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATE', '翻訳');
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATION_TIME', '翻訳時間');
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATE_CANCEL', 'キャンセル');
//20090928 add
define('_MI_DOCUMENT_TOOL_BUTTON_CLEAR', '消去');

 // information
define('_MI_DOCUMENT_TOOL_TRANSLATION_DOING', '翻訳中…');
define('_MI_DOCUMENT_TOOL_BACKTRANSLATION_DOING', '折り返し翻訳中…');
define('_MI_DOCUMENT_TOOL_TRANSLATION_PARSE_TEXT', '解析中…');
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATE_CANCEL_DOING', '翻訳を中断しています…');

 // label
define('_MI_DOCUMENT_TOOL_TRANSLATION_FROM', 'From');
define('_MI_DOCUMENT_TOOL_TRANSLATION_TO', 'To');
define('_MI_DOCUMENT_TOOL_BACKTRANSLATION_NAME', '折り返し翻訳');
//20091013 add
define('_MI_DOCUMENT_LICENSE_INFORMATION', 'ライセンス情報');
define('_MI_DOCUMENT_SERVICE_NAME', 'サービス名');
define('_MI_DOCUMENT_COPYRIGHT', '著作権情報');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University. All rights reserved.');

// messages
define('_MI_DOCUMENT_TOOL_ERROR_TIMEOUT_MESSAGE', '言語グリッドにアクセスが集中しているため、タイムアウトしました。');

// javascript message
define('_MI_DOCUMENT_JS_NO_MORE_SMALL_AREA', 'テキストエリアのサイズをこれ以上小さくすることはできません。');
define('_MI_DOCUMENT_JS_NO_MORE_SMALL_FONT', '文字のサイズをこれ以上小さくすることはできません。');
//20091030 add
define('_MI_DOCUMENT_NO_TRANSLATION', '翻訳できません。');
define('_MI_DOCUMENT_JS_TRANSLATE_ERROR', 'サーバでエラーが発生しました。');
define('_MI_DOCUMENT_JS_SERVER_ERROR', 'サーバーからの応答が不正です。');

define('_MI_DOCUMENT_JS_VOICE_CREATING', '音声読み上げデータを生成中 ...');
define('_MI_DOCUMENT_JS_UNABLE_QT_PLAYER', 'QuickTime Playerが利用できません。音声読み上げ機能を利用するには、QuickTime Playerをインストールしてください。');
define('_MI_DOCUMENT_JS_UNABLE_LANGUAGE', 'この言語は音声読み上げに対応していません。');
define('_MI_DOCUMENT_JS_VOICE_CREATION_FAILED', '音声ファイルの生成に失敗しました。');
//20101005 add
define('_MI_DOCUMENT_JS_TAG_WARNING', '<skip_translation> タグは、一行内で閉じてください。');
//20101005 end
//20101006 add
define('_MI_DOCUMENT_LITE', '引用符付きフレーズ<br>の翻訳回避');
define('_MI_DOCUMENT_RICH', '原語表示');

//Use is not confirmed by Toolbox
 // messages
 define('_MI_DOCUMENT_TOOL_BACKTRANSLATION_MESSAGE', 'Back translation results of highlighted sentences are shown in this area.');
 define('_MI_DOCUMENT_TOOL_ERROR_UNKNOWN_MESSAGE', 'An unknown error related to the Language Grid ToolBox has occurred.');

?>
<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
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
define('WQA_APPLICATION_NAME', 'Q&A Webインタフェース');
define('WQA_LB_SEARCHFORM_SEARCH_BUTTON', '検索');
define('WQA_LB_SEARCHFORM_DATEORDER', '日付順');
define('WQA_LB_SEARCHFORM_VIEWNUM', '表示件数');
define('WQA_LB_SEARCHFORM_VIEWNUM2', '件');
define('WQA_LB_SEARCHFORM_NEWQA', '質問を投稿');
define('WQA_LB_SEARCHRESULT_DATETIME', '投稿日時');
define('WQA_LB_SEARCHRESULT_ANSNUM', '回答(%s)件');
define('WQA_LB_QA_POST_AUTHOR', '投稿者');
define('WQA_LB_QA_DATETIME', '投稿日時');
define('WQA_LB_INPUT_QUESTION_POST', '質問投稿');
define('WQA_LB_INPUT_CATEGORY', 'カテゴリ');
define('WQA_LB_INPUT_FORM_TITLE', 'タイトル');
define('WQA_LB_INPUT_FORM_TITLE_D', '全角30文字以内で入力して下さい。');
define('WQA_LB_INPUT_FORM_BODY', '質問内容');
define('WQA_LB_INPUT_FORM_BODY_D', '全角500文字以内で入力して下さい。');
define('WQA_LB_INPUT_FORM_SUBMIT', '投稿');
define('WQA_LB_INPUT_FORM_CANCEL', '戻る');
define('WQA_LB_INPUT_REQUIRE', '*必須項目');
define('WQA_LB_CONFIRM_QUESTION_POST', '質問内容の確認');
define('WQA_LB_CONFIRM_CATEGORY', '投稿カテゴリ');
define('WQA_LB_CONFIRM_TITLE', 'タイトル');
define('WQA_LB_CONFIRM_BODY', '質問内容');
define('WQA_LB_CONFIRM_CANCEL', '戻る');
define('WQA_LB_CONFIRM_SUBMIT', '投稿');
define('WQA_LB_CONFIRM_REQUIRE', '*必須項目');
define('WQA_LB_CONFIRM_REQUIRE_ERROR', '{0}を入力してください。');
define('WQA_LB_CONFIRM_MAX_LENGTH_ERROR', '{0}は{1}文字までです。');
define('WQA_LB_SEARCHFORM_WEB_QA_TOP_PAGE', 'Q&A検索');
define('WQA_LB_DONE_NOW_POSTING', '投稿中です …');
define('WQA_LB_DONE_PLEASE_WAIT', 'しばらくお待ちください。');
define('WQA_LB_DONE_TOP', 'トップページへ戻る');
define('WQA_LB_QA_TOP', 'トップページへ戻る');
define('WQA_LB_QA_BACK', '戻る');
define('WQA_COMMON_TAB_NAME', 'Q&A Webインタフェース');
define('WQA_COMMON_HOW_TO_USE', 'How to use');
define('WQA_COMMON_HOW_TO_USE_URL', './how-to-use/webqa_ja.html');
define('WQA_LB_SEARCHFORM_NO_RESULT', '検索条件に一致するQ &amp; Aは見つかりませんでした。');
define('WQA_LB_SEARCHFORM_RESULT', '{0}件中 {1} - {2}件表示');
define('WQA_LB_SEARCHFORM_CLEAR_SEARCH_RESULT', '検索条件と結果のクリア');
define('WQA_LB_SEARCHFORM_LANGUAGE_LABEL', 'Q&amp;A検索対象の言語');
define('WQA_LB_SEARCHFORM_NOTICE_FOR_GUEST', '(ログインしていない場合、回答の付いた質問のみ表示されます。回答はログインユーザだけが投稿できます。)');
define('WQA_LB_QA_LANGUAGE_LABEL', 'Q&amp;A表示言語');
define('WQA_LB_INPUT_LANGUAGE_LABEL', '質問投稿の言語');
define('WQA_LB_QA_Q_AND_A', '質問と回答');
define('WQA_LB_QA_Q', '質問');
define('WQA_LB_QA_A', '回答');
define('WQA_LB_QA_NO_ANSWER', 'この質問への回答がありません。');
define('WQA_LB_SEARCHFORM_NO_DATA', '選択した言語のデータがありません。');
define('WQA_LB_QA_NO_DATA', '選択した言語のデータがありません。');
define('WQA_LB_INPUT_CATEGORY_NO_DATA', '選択した言語のデータがありません。');
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009  Department of Social Informatics, Kyoto University');
//20100225 add
define('WQA_LB_QA_NO_CATEGORIES', '投稿先のQ&A集にはカテゴリが定義されていません。');
define('WQA_LB_QA_NO_SEARCH_QA', '検索対象のQ&A集が設定されていません。');
define('WQA_LB_QA_NO_POSTING_QA', '質問の投稿先となるQ&A集が設定されていません。');
define('WQA_LB_QA_NO_POST_FORUM', '質問投稿先のフォーラムが設定されていないため、フォーラムへの投稿は行われません。');
//20100922 add
define('WQA_LB_SEARCHFORM_VIEWNUM_ALL', '全て');
define('WQA_COMMON_TAB_NAME_LIST', '質問の一覧');
define('WQA_COMMON_TAB_NAME_POST', '質問の投稿');
define('WQA_LB_QA_POST_LANGUAGE', '投稿言語');
define('WQA_LB_QA_ALL_CATEGORIES', '全てのカテゴリ');
define('WQA_LB_QA_SELECTED_CATEGORIES', 'その他');
define('WQA_LB_QA_CATEGORY_SELECT', '選択');
define('WQA_LB_SEARCHFORM_KEYWORD', 'キーワード');
define('WQA_LB_QA_POST_A', '回答の投稿');
define('WQA_LB_QA_A_BODY', '回答');
define('WQA_LB_CONFIRM_ANSWER_POST', '回答の確認');
define('WQA_LB_INPUT_ANSWER_LANGUAGE_LABEL', '回答投稿の言語');
?>
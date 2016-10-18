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
define('WQA_APPLICATION_NAME', '질문과 답변 웹 인터페이스');
define('WQA_LB_SEARCHFORM_SEARCH_BUTTON', '검색');
define('WQA_LB_SEARCHFORM_DATEORDER', '날짜별로 결과 정렬');
define('WQA_LB_SEARCHFORM_VIEWNUM', '결과 수');
define('WQA_LB_SEARCHFORM_VIEWNUM2', '');
define('WQA_LB_SEARCHFORM_NEWQA', '새 질문 게시');
define('WQA_LB_SEARCHRESULT_DATETIME', '날짜');
define('WQA_LB_SEARCHRESULT_ANSNUM', '답변 수: %s');
define('WQA_LB_QA_POST_AUTHOR', 'Posted by ');
define('WQA_LB_QA_DATETIME', '날짜');
define('WQA_LB_INPUT_QUESTION_POST', '새 질문 게시');
define('WQA_LB_INPUT_CATEGORY', '범주');
define('WQA_LB_INPUT_FORM_TITLE', '제목');
define('WQA_LB_INPUT_FORM_TITLE_D', '최대 30자까지 입력할 수 있습니다.');
define('WQA_LB_INPUT_FORM_BODY', '질문');
define('WQA_LB_INPUT_FORM_BODY_D', '최대 500자까지 입력할 수 있습니다.');
define('WQA_LB_INPUT_FORM_SUBMIT', '제출');
define('WQA_LB_INPUT_FORM_CANCEL', '뒤로');
define('WQA_LB_INPUT_REQUIRE', '*필수 항목');
define('WQA_LB_CONFIRM_QUESTION_POST', '질문 확인');
define('WQA_LB_CONFIRM_CATEGORY', '범주');
define('WQA_LB_CONFIRM_TITLE', '제목');
define('WQA_LB_CONFIRM_BODY', '질문');
define('WQA_LB_CONFIRM_CANCEL', '뒤로');
define('WQA_LB_CONFIRM_SUBMIT', '제출');
define('WQA_LB_CONFIRM_REQUIRE', '*필수 항목');
define('WQA_LB_CONFIRM_REQUIRE_ERROR', '{0}은(는) 필수 항목입니다.');
define('WQA_LB_CONFIRM_MAX_LENGTH_ERROR', '{0}은(는) {1}자 이하여야 합니다.');
define('WQA_LB_SEARCHFORM_WEB_QA_TOP_PAGE', '질문과 답변 검색');
define('WQA_LB_DONE_NOW_POSTING', '게시 중 ...');
define('WQA_LB_DONE_PLEASE_WAIT', '잠시 기다려 주십시오.');
define('WQA_LB_DONE_TOP', '맨 위로 이동');
define('WQA_LB_QA_TOP', '맨 위로 이동');
define('WQA_LB_QA_BACK', '뒤로');
define('WQA_COMMON_TAB_NAME', '질문과 답변 웹 인터페이스');
define('WQA_COMMON_HOW_TO_USE', '사용 방법');
define('WQA_COMMON_HOW_TO_USE_URL', './how-to-use/webqa_ko.html');
define('WQA_LB_SEARCHFORM_NO_RESULT', '일치하는 질문과 답변이 없습니다.');
define('WQA_LB_SEARCHFORM_RESULT', '#{1} - #{2}/#{0}');
define('WQA_LB_SEARCHFORM_CLEAR_SEARCH_RESULT', '검색 조건과 결과를 지우십시오.');
define('WQA_LB_SEARCHFORM_LANGUAGE_LABEL', 'Search Q&amp;A in');
define('WQA_LB_QA_LANGUAGE_LABEL', 'Display Q&amp;A in');
define('WQA_LB_INPUT_LANGUAGE_LABEL', 'Post a question in');
define('WQA_LB_QA_Q_AND_A', 'Q&amp;A');
define('WQA_LB_QA_Q', 'Question');
define('WQA_LB_QA_A', 'Answer');
define('WQA_LB_QA_NO_ANSWER', 'There is no answer to this question.');
define('WQA_LB_SEARCHFORM_NO_DATA', 'No data in the selected language.');
define('WQA_LB_QA_NO_DATA', 'No data in the selected language.');
define('WQA_LB_INPUT_CATEGORY_NO_DATA', 'No data in the selected language.');
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009  Department of Social Informatics, Kyoto University');
//20100225 add
define('WQA_LB_QA_NO_CATEGORIES', 'No category defined in the target Q&As.');
define('WQA_LB_QA_NO_SEARCH_QA', 'No Q&As are specified for search.');
define('WQA_LB_QA_NO_POSTING_QA', 'No Q&As are specified for posting.');
define('WQA_LB_QA_NO_POST_FORUM', 'The topic for your question will not be created because no forum is specified for posting questions.');
//20100922 add
define('WQA_LB_SEARCHFORM_VIEWNUM_ALL', 'All');
define('WQA_COMMON_TAB_NAME_LIST', 'View questions');
define('WQA_COMMON_TAB_NAME_POST', 'Post a question');
define('WQA_LB_QA_POST_LANGUAGE', 'Original language');
define('WQA_LB_QA_ALL_CATEGORIES', 'All categories');
define('WQA_LB_QA_SELECTED_CATEGORIES', 'Other');
define('WQA_LB_QA_CATEGORY_SELECT', 'Select');
define('WQA_LB_SEARCHFORM_KEYWORD', 'Keyword');
define('WQA_LB_QA_POST_A', 'Post an answer');
define('WQA_LB_QA_A_BODY', 'Answer');
define('WQA_LB_CONFIRM_ANSWER_POST', 'Confirm your answer');
define('WQA_LB_INPUT_ANSWER_LANGUAGE_LABEL', 'Post an answer in');
?>

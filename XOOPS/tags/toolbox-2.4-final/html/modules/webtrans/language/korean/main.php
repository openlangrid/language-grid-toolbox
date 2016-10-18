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
define('_MI_WEBTRANS_TOOL_NAME', '웹 생성');
define('_MI_WEBTRANS_HOW_TO_USE_LINK', 'webtrans_ko.html');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009-2010 Language Grid Project, NICT. All rights reserved.');

// label
define('_MI_WEBTRANS_LABEL_TRANSLATE', '번역');
define('_MI_WEBTRANS_LABEL_WEBPAGEURL', '웹 페이지 URL');
define('_MI_WEBTRANS_LABEL_APPLY_TEMPLATE', '템플릿 적용');
define('_MI_WEBTRANS_LABEL_ORIGINAL_WEBPAGE', '원래 웹 페이지');
define('_MI_WEBTRANS_LABEL_TRANSLATED_WEBPAGE', '번역된 웹 페이지');
define('_MI_WEBTRANS_LABEL_BACK_TRANSLATED_WEBPAGE', '역번역된 웹 페이지');
define('_MI_WEBTRANS_LABEL_LICENSE_INFORMATION', '라이센스 정보');
define('_MI_WEBTRANS_LABEL_CREATEANDEDITTEMPLATE', '템플릿 생성 &amp; 편집');
define('_MI_WEBTRANS_LABEL_CREATE_PAIR', '쌍 생성');
define('_MI_WEBTRANS_LABEL_LIST_ALL_PAIR', '모든 쌍 나열');

// button
define('_MI_WEBTRANS_TOOL_BUTTON_IMPORT_WEBPAGE', '웹 페이지 가져오기');
define('_MI_WEBTRANS_TOOL_BUTTON_DISPLAY_WEBPAGE', '표시');
define('_MI_WEBTRANS_TOOL_BUTTON_LOAD_TEMPLATE', '템플릿 로드');
define('_MI_WEBTRANS_TOOL_BUTTON_UPLOAD_TEMPLATE', '템플릿 업로드');
define('_MI_WEBTRANS_TOOL_BUTTON_TRANSLATE', '번역');
define('_MI_WEBTRANS_TOOL_BUTTON_CANCEL', '취소');
define('_MI_WEBTRANS_TOOL_BUTTON_UPLOAD', '업로드');
define('_MI_WEBTRANS_TOOL_BUTTON_DOWNLOAD', '다운로드');
define('_MI_WEBTRANS_TOOL_BUTTON_UNDO', '실행 취소');
define('_MI_WEBTRANS_TOOL_BUTTON_DISPLAY', '표시');
define('_MI_WEBTRANS_TOOL_BUTTON_ADD_TEMPLATE', '템플릿에 쌍 추가');
define('_MI_WEBTRANS_TOOL_BUTTON_SAVE_TEMPLATE', '템플릿 저장');
define('_MI_WEBTRANS_TOOL_BUTTON_DOWNLOAD_TEMPLATE', '템플릿 다운로드');

// information
define('_MI_WEBTRANS_TOOL_TRANSLATION_DOING', '번역하는 중 ...');

// messages
define('_MI_WEBTRANS_MSG_URL_INVALID', '잘못된 URL');
define('_MI_WEBTRANS_TOOL_BACKTRANSLATION_MESSAGE', '역번역된 웹 페이지 HTML 소스가 여기에 표시됩니다. 이 영역은 편집할 수 없습니다.');
define('_MI_WEBTRANS_POPUP_UPLOAD_HTML', 'HTML 파일을 컴퓨터에서 업로드합니다.');
define('_MI_WEBTRANS_POPUP_DOWNLOAD_HTML', 'HTML 파일을 컴퓨터로 다운로드합니다.');
define('_MI_WEBTRANS_POPUP_LOAD_TEMPLATE', '템플릿 파일을 서버에서 로드합니다.');
define('_MI_WEBTRANS_POPUP_UPLOAD_TEMPLATE', '템플릿 파일을 서버에서 업로드합니다.');
define('_MI_WEBTRANS_POPUP_SAVE_TEMPLATE', '템플릿 파일을 서버에 저장합니다.');
define('_MI_WEBTRANS_POPUP_SAVE_MESSAGE', '템플릿 이름에는 영문자(1바이트), 숫자, 밑줄 "_" 및 하이픈 "-"만 포함할 수 있습니다.');
define('_MI_WEBTRANS_POPUP_DOWNLOAD_TEMPLATE', '템플릿 파일을 컴퓨터로 다운로드합니다.');
define('_MI_WEBTRANS_POPUP_FILE_NAME', '파일 이름');
define('_MI_WEBTRANS_POPUP_TEMPLATE_NAME', '템플릿 이름');
define('_MI_WEBTRANS_POPUP_LOAD_ANOTHER_TEMPLATE', '다른 템플릿 로드');
define('_MI_WEBTRANS_POPUP_UPLOAD_ANOTHER_TEMPLATE', '다른 템플릿 업로드');

// javascript message
define('_MI_WEBTRANS_JS_TRANSLATE_ERROR', 'Language Grid에서 오류가 발생했습니다.');
define('_MI_WEBTRANS_JS_SERVER_ERROR', '서버에서 예상치 못한 응답을 받았습니다.');
define('_MI_WEBTRANS_JS_NO_MORE_SMALL_AREA', '텍스트 영역의 크기를 더 이상 줄일 수 없습니다.');
define('_MI_WEBTRANS_JS_ORIGINAL_INIT_MESSAGE', '여기에 원래 웹 페이지의 HTML 소스가 표시되어 있습니다. 이 영역을 편집할 수 있습니다.');
define('_MI_WEBTRANS_JS_TRANSLATED_INIT_MESSAGE', '여기에 번역된 웹 페이지의 HTML 소스가 표시되어 있습니다. 이 영역을 편집할 수 있습니다.');
define('_MI_WEBTRANS_JS_TEMPLATE_INIT_MESSAGE', '여기에 HTML 소스를 붙여넣습니다.');
define('_MI_WEBTRANS_SERVICE_NAME', '서비스 이름');
define('_MI_WEBTRANS_COPYRIGHT', '저작권');
define('_MI_WEBTRANS_TOOL_BUTTON_DELETE_PAIR', '쌍 삭제');
define('_MI_WEBTRANS_JS_FIELD_ADD_ERROR', '필드를 더 이상 추가할 수 없습니다.');
define('_MI_WEBTRANS_JS_FILE_NAME_ERROR', '잘못된 파일 이름입니다.');
define('_MI_WEBTRANS_JS_TEMPLATE_NAME_ERROR', '잘못된 템플릿 이름');
define('_MI_WEBTRANS_JS_NO_TEMPLATE_MSG', '{0}이(가) 없습니다.');
define('_MI_WEBTRANS_JS_TEMPLATE_OVER_MAX', '{0} 템플릿이 이미 로드되었습니다.');
define('_MI_WEBTRANS_JS_NO_PAIR_MSG', '쌍이 없습니다.');
define('_MI_WEBTRANS_JS_CONFIRM_OVERWRITE', '"{0}"이(가) 있습니다. \n덮어쓰시겠습니까?');
define('_MI_WEBTRANS_JS_SAVE_COMPLATE', '템플릿을 저장합니다.');
define('_MI_WEBTRANS_JS_TEMPLATE_EMPTY', '템플릿이 비어 있습니다.');
define('_MI_WEBTRANS_POPUP_TITLE_LOAD_TEMPLATE', '템플릿 로드');
define('_MI_WEBTRANS_POPUP_TITLE_UPLOAD_TEMPLATE', '템플릿 업로드');
define('_MI_WEBTRANS_POPUP_TITLE_UPLOAD_HTML', 'HTML 파일 업로드');
define('_MI_WEBTRANS_POPUP_TITLE_DOWNLOAD_HTML', 'HTML 파일 다운로드');
define('_MI_WEBTRANS_POPUP_TITLE_SAVE_TEMPLATE', '템플릿 저장');
define('_MI_WEBTRANS_POPUP_TITLE_DOWNLOAD_TEMPLATE', '템플릿 다운로드');
define('_MI_WEBTRANS_JS_ABORT_TRANSLATION', '번역을 중단하는 중...');
define('_MI_WEBTRANS_JS_TRANSLATION_INITIALIZEING', '[...초기화하는 중...]');
define('_MI_WEBTRANS_JS_APPLYING_TEMPLATE', '[...템플릿 적용...]');
define('_MI_WEBTRANS_JS_ANALYSIS_HTML', '[...HTML 소스 분석...]');
define('_MI_WEBTRANS_JS_LICENSE_AREA_MSG', '언어 리소스를 사용할 때 라이센스 정보가 여기에 표시됩니다.');
//20100302 add
define('_MI_WEBTRANS_JS_SESSIONTIMEOUT', 'session time out.');
define('_MI_WEBTRANS_JS_DISPLAY_SAVEERROR', 'failed to save the content to be displayed.');


?>
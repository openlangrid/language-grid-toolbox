<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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
/* for PageTab Label */
define('_MD_LANGRID_TAB2_NAME', 'BBS');
define('_MD_LANGRID_TAB3_NAME', '텍스트 번역');
//20091112 add
define('_MD_LANGRID_TAB5_NAME', '웹 번역');
//20100107 add
define('_MD_LANGRID_TAB6_NAME', 'Disucssion');
//20100120 add
define('_MD_LANGRID_TAB7_NAME', 'Collaborative translation');
//20100205 add
if (!defined('_MD_COPYRIGHT_LB')) {
define('_MD_COPYRIGHT_LB', '');
}

/* for BBS Page */
define('_MD_LANGRID_BBS_STG_ADD_PATH_BTN', '번역 경로 추가');
define('_MD_LANGRID_BBS_STG_SUBMIT_BTN', '저장');
define('_MD_LANGRID_BBS_STG_CONBINATION_BTN', '추가 번역 허용');
define('_MD_LANGRID_BBS_STG_DELETE_BTN', '삭제');
define('_MD_LANGRID_BBS_STG_DICTIONARY_BTN', '사전');
define('_MD_LANGRID_STG_ERR_MSG3', '이 번역 경로에 대한 번역기 할당이 잘못되었습니다.');
define('_MD_LANGRID_STG_MSG3', '번역 경로 "%SRC %FLW %TGT"을(를) 삭제하시겠습니까?');
define('_MD_LANGRID_DICT_POP_TITLE', '사전');
define('_MD_LANGRID_DICT_POP_T1', '글로벌 사전');
define('_MD_LANGRID_DICT_POP_T2', '임시 사전');
define('_MD_LANGRID_DICT_POP_OK', '확인');
define('_MD_LANGRID_DICT_POP_CANCEL', '취소');
define('_MD_LANGRID_STG_ERR_MSG1', '기계 번역기를 선택하십시오.');
define('_MD_LANGRID_STG_MSG2', 'BBS의 번역 경로가 저장되었습니다.');
define('_MD_LANGRID_NOWLOADING', '로드 중 ...');
//20090922 add
define('_MD_LANGRID_STG_MSG4', '관리자만 번역 경로를 편집할 수 있습니다.');
define('_MD_LANGRID_SETTING_DEFAULT_DICTIONARY', '기본');
define('_MD_LANGRID_SETTING_CUSTOM_DICTIONARY', '사용자 지정');
define('_MD_LANGRID_SETTING_ADVANCED', '고급 옵션');
define('_MD_LANGRID_INFO_POP_PROVIDER', '제공자');
define('_MD_LANGRID_INFO_POP_COPYRIGHT', '저작권');
define('_MD_LANGRID_INFO_POP_LICENSE', '라이센스');
define('_MD_LANGRID_SETTING_VIEW_DEFAULT_DICT', '기본 사전 보기');
define('_MD_LANGRID_SETTING_CLOSE_BUTTON', '닫기');
define('_MD_LANGRID_DICT_POP_LOAD_DEFAULT', '기본값 로드');
define('_MD_LANGRID_SETTING_FILTER_FROM', '필터 시작');
define('_MD_LANGRID_SETTING_FILTER_TO', '끝');
define('_MD_LANGRID_SETTING_MATCHES', '일치');
define('_MD_LANGRID_SETTING_EDIT_DEFAULT_DICT', '기본 사전 편집');
define('_MD_LANGRID_SETTING_RETURN_TOP', '맨 위로 이동');
define('_MD_LANGRID_SETTING_DICT_SELECT', '%S개 사전이 선택됨');
define('_MD_LANGRID_SETTING_DICT_NO_SELECT', '선택된 사전 없음');
define('_MD_LANGRID_SETTING_FILTER_BTN', '필터링');
define('_MD_LANGRID_SETTING_DISPLAY_ALL_PATH', '모든 번역 경로 표시');
define('_MD_LANGRID_SETTING_ADD_BUTTON', '추가');
define('_MD_LANGRID_STG_ADDED_MESSAGE', '번역 경로 "%SRC %FLW %TGT"이(가) 추가되었습니다.');
define('_MD_LANGRID_STG_SAVED_MESSAGE', '번역 경로 "%SRC %FLW %TGT"이(가) 변경되었습니다.');
define('_MD_LANGRID_CONFIRM_CANCEL', '변경 내용을 삭제하시겠습니까?');
//20090928 add
define('_MD_LANGRID_INFO_POP_DESCRIPTION', '서비스 설명');
//20091006 add
define('_MD_LANGRID_DICT_POP_MSG_NO_DICT', '글로벌 사전을 선택하지 않았습니다.');
define('_MD_LANGRID_DICT_POP_MSG_NO_COM_DICT', '임시 사전을 선택하지 않았습니다.');
//20091019 add
define('_MD_LANGRID_DICT_POP_MORPHOLOGICAL_ANALYZER', '형태학적 분석기');
//20091022 add
define('_MI_LANGRID_TEXT_HOW_TO_USE_LINK', 'settings_Text_ko.html');
define('_MI_LANGRID_BBS_HOW_TO_USE_LINK', 'settings_BBS_ko.html');
define('_MI_LANGRID_IMPORTED_SERVICES_HOW_TO_USE_LINK', 'import_ko.html');
define('_MI_LANGRID_WEB_HOW_TO_USE_LINK', 'settings_Web_ko.html');
define('_MD_LANGRID_DICT_POP_T3', '로컬 사전');
define('_MD_LANGRID_DICT_POP_MSG_NO_IMP_DICT', '로컬 사전을 선택하지 않았습니다.');
//20091027 add
define('_MD_LANGRID_DICT_MSG_NO_DICTIONARY_LOCAL', '로컬 사전 없음');
define('_MD_LANGRID_DICT_MSG_NO_DICTIONARY_TEMPORAL', '임시 사전 없음');
//20091029 add
define('_MD_LANGRID_DICT_MSG_OVER_SELECT_DICTIONARY', '최대 5개까지 글로벌/로컬 사전을 선택할 수 있습니다.');
//20100120 add
define('_MI_LANGRID_TRANS_HOW_TO_USE_LINK', 'settings_Collabtrans_ko.html');
//20100129 add
define('_MI_LANGRID_COM_HOW_TO_USE_LINK', 'settings_Discussion_ko.html');

/* Imported Services */
define('_MD_LANGRID_IMPORTED_SERVICES', '가져온 서비스');
define('_MD_LANGRID_IMPORTED_SERVICES_ADD_SERVICE', '+ 서비스 추가');
define('_MD_LANGRID_IMPORTED_SERVICES_EDIT_SERVICE', '편집');
define('_MD_LANGRID_IMPORTED_SERVICES_REMOVE_SERVICE', '제거');
define('_MD_LANGRID_IMPORTED_SERVICES_SERVICE_NAME_IS_IN_USE', '서비스 이름 "%s"은(는) 이미 사용 중입니다.');
define('_MD_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL_IS_IN_USE', '끝점 URL "%s"은(는) 이미 사용 중입니다.');
define('_MI_LANGRID_IMPORTED_SERVICES_ADD', '추가');
define('_MI_LANGRID_IMPORTED_SERVICES_ADD_SERVICE', '서비스 추가');
define('_MI_LANGRID_IMPORTED_SERVICES_ARE_YOU_REALLY_SURE_YOU_WANT_TO_REMOVE_THE_SERVICE', '서비스를 제거하시겠습니까?');
define('_MI_LANGRID_IMPORTED_SERVICES_AT_LEAST_ONE_LANGUAGE_PATH_IS_REQUIRED', '언어 경로가 하나 이상 필요합니다.');
define('_MI_LANGRID_IMPORTED_SERVICES_AT_LEAST_TWO_LANGUAGES_ARE_REQUIRED', '언어가 2개 이상 필요합니다.');
define('_MI_LANGRID_IMPORTED_SERVICES_CANCEL', '취소');
define('_MI_LANGRID_IMPORTED_SERVICES_COPYRIGHT', '저작권');
define('_MI_LANGRID_IMPORTED_SERVICES_DICTIONARY', '사전');
define('_MI_LANGRID_IMPORTED_SERVICES_BIDIRECTIONAL', '<->');
define('_MI_LANGRID_IMPORTED_SERVICES_EDIT_SERVICE', '서비스 편집');
define('_MI_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL', '끝점');
define('_MI_LANGRID_IMPORTED_SERVICES_IMPORT', '가져오기');
define('_MI_LANGRID_IMPORTED_SERVICES_LANGUAGE', '언어');
define('_MI_LANGRID_IMPORTED_SERVICES_LANGUAGES', '언어');
define('_MI_LANGRID_IMPORTED_SERVICES_LICENSE', '라이센스');
define('_MI_LANGRID_IMPORTED_SERVICES_NO_IMPORTED_SERVICES', '가져온 서비스가 없습니다.');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_IMPORTING', '가져오는 중 ...');
define('_MI_LANGRID_IMPORTED_SERVICES_NO_SERVICE_IS_SELECTED', '서비스를 선택하지 않았습니다.');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_LOADING', '로드 중 ...');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_REMOVING', '제거 중 ...');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_SAVING', '저장 중 ...');
define('_MI_LANGRID_IMPORTED_SERVICES_NULL_LANGUAGE_NAME', '---');
define('_MI_LANGRID_IMPORTED_SERVICES_NULL_TABLE_VALUE', '<div class="align-center">-</div>');
define('_MI_LANGRID_IMPORTED_SERVICES_OK', '확인');
define('_MI_LANGRID_IMPORTED_SERVICES_PROVIDER', '제공자');
define('_MI_LANGRID_IMPORTED_SERVICES_REGISTRATION_DATE', '등록 날짜');
define('_MI_LANGRID_IMPORTED_SERVICES_REMOVE_LANGUAGE', '언어 제거');
define('_MI_LANGRID_IMPORTED_SERVICES_MONODIRECTIONAL', '->');
define('_MI_LANGRID_IMPORTED_SERVICES_SAVE', '저장');
define('_MI_LANGRID_IMPORTED_SERVICES_SERVICE_NAME', '서비스 이름');
define('_MI_LANGRID_IMPORTED_SERVICES_SERVICE_TYPE', '서비스 유형');
define('_MI_LANGRID_IMPORTED_SERVICES_STRING_IS_BLANK', '{0}이(가) 비어 있습니다.');
define('_MI_LANGRID_IMPORTED_SERVICES_TRANSLATOR', '번역기');
define('_MI_LANGRID_IMPORTED_SERVICES_ON_AJAX_SERVER_ERROR', '서버와 통신하는 중 오류가 발생했습니다. 페이지를 다시 로드하십시오.');
//20091030 add
define('_MI_LANGRID_IMPORTED_SERVICES_REQUIRED_FIELD', '*필수 필드');
//20091127 add
define('_MI_LANGRID_IMPORTED_SERVICES_HAS_DUPLICATED_PATHS', '중복된 언어 경로가 있습니다.');
define('_MI_LANGRID_IMPORTED_SERVICES_HAS_SAME_PATHS', '언어 경로는 서로 다른 언어를 포함해야 합니다.');
define('_MI_LANGRID_IMPORTED_SERVICES_THE_INPUT_URL_IS_INVALID', '잘못된 URL을 입력했습니다.');


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
define('_MD_LANGRID_STG_ERR_MSG4', 'The translation path already exists.');

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
	define('_MD_LANGRID_ERROR_PROCESS_FAILED_EXCEPTION', 'Process Failed Exception occurred in Language grid.[%s]');
}


?>
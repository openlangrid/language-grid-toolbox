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
define('_MI_DICTIONARY_NAME2', '리소스 이름');
define('_MI_DICTIONARY_DICTIONARY_SEARCH', '검색');
define('_MI_DICTIONARY_LANGUAGES', '언어');
define('_MI_DICTIONARY_CREATE_RESOURCE', '언어 리소스 생성');
define('_MI_DICTIONARY_IMPORT_RESOURCE', '언어 리소스 가져오기');
define('_MI_DICTIONARY_SELECT_LANGUAGES_TO_DELETE', '삭제할 언어를 선택하십시오.');
//20090919 add
define('_MI_DICTIONARY_HOW_TO_USE_LINK', 'dictionary_ko.html');
//20091022 add
define('_MI_DICTIONARY_PARALLEL_TEXT_HOW_TO_USE_LINK', 'paralleltext_ko.html');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University. All rights reserved.');

/**
 * Common
 */
define('_MI_DICTIONARY_COMMON_TYPE', '유형');
define('_MI_DICTIONARY_COMMON_LANGUAGES', '언어');
define('_MI_DICTIONARY_ERROR_FILE_REQUIRED', '파일이 필요합니다.');
define('_MI_DICTIONARY_ERROR_FILE_FORMAT_INVALID', '파일 형식이 잘못되었습니다.');
//20090919 add
define('_MI_DICTIONARY_SERVICE', '서비스');
define('_MI_DICTIONARY_DEPLOY', '배포');
define('_MI_DICTIONARY_UNDEPLOY', '배포 취소');
define('_MI_DICTIONARY_WSDL', 'WSDL');

/**
 * Label
 */
define('_MI_DICTIONARY_COMMUNITY_RESOURCES', '생성/편집');
define('_MI_DICTIONARY_COMMUNITY_DICTIONARY', '사전');
define('_MI_DICTIONARY_COMMUNITY_PARALLEL_TEXT', '가로 텍스트');
define('_MI_DICTIONARY_USER_DICTIONARY', '사전');
define('_MI_DICTIONARY_USER_PARALLEL_TEXT', '가로 텍스트');
define('_MI_DICTIONARY_EDIT_PERMISSION', '권한 편집');
define('_MI_DICTIONARY_READ_PERMISSION', '권한 읽기');
define('_MI_DICTIONARY_FOR_ALL_USERS', '모든 사용자용');
define('_MI_DICTIONARY_FOR_THE_CURRENT_USER_ONLY', '현재 사용자 전용');
define('_MI_DICTIONARY_USER', '생성자');
define('_MI_DICTIONARY_READ', '읽기');
define('_MI_DICTIONARY_EDIT', '편집');
define('_MI_DICTIONARY_LAST_UPDATE', '마지막 업데이트');
define('_MI_DICTIONARY_COUNTS', '항목 수');
define('_MI_DICTIONARY_SAVE_RESOURCE', '사전 저장');
define('_MI_DICTIONARY_SAVE_RESOURCE_P', '가로 텍스트 저장');
//20090919 add
define('_MI_DICTIONARY_COMMON_RESOURCE_NAME', '리소스 이름');
define('_MI_DICTIONARY_COMMON_RESOURCE_NAME_RULE', '리소스 이름은 영문자(1개 이상), 숫자, 공백 " ", 하이픈 "-" 및 마침표 "."를 포함하여 4자 이상으로 지정해야 합니다.');
//20090926 add
define('_MI_DICTIONARY_COMMON_ADD_LANGUAGE', '언어 추가');
define('_MI_DICTIONARY_SELECT_LANGUAGES_TO_ADD', '추가할 언어를 선택합니다.');
define('_MI_DICTIONARY_COMMON_DELETE_LANGUAGE', '언어 삭제');

/**
 * Button
 */
define('_MI_DICTIONARY_CREATE', '생성');
define('_MI_DICTIONARY_IMPORT', '가져오기');
define('_MI_DICTIONARY_EXPORT', '내보내기');
define('_MI_DICTIONARY_LOAD', '로드');
define('_MI_DICTIONARY_REMOVE', '제거');
define('_MI_DICTIONARY_SAVE', '저장');
define('_MI_DICTIONARY_CLOSE', '닫기');
define('_MI_DICTIONARY_ADD_RECORD', '레코드 추가');
define('_MI_DICTIONARY_DELETE_RECORD', '레코드 삭제');
define('_MI_DICTIONARY_ADD_LANGUAGE', '언어 추가');
define('_MI_DICTIONARY_DELETE_LANGUAGE', '언어 삭제');
define('_MI_DICTIONARY_ADD_WORD', 'Add word');

/**
 * Status
 */
// Create
define('_MI_DICTIONARY_STATUS_CREATING_DICTIONARY', '사전을 생성하는 중 ...');
define('_MI_DICTIONARY_STATUS_CREATING_DICTIONARY_P', '가로 텍스트를 생성하는 중 ...');
define('_MI_DICTIONARY_STATUS_DICTIONARY_CREATED', '사전이 생성되었습니다.');
define('_MI_DICTIONARY_STATUS_PARALLEL_TEXT_CREATED', '가로 텍스트가 생성되었습니다.');

// Save
define('_MI_DICTIONARY_STATUS_DICTIONARY_SAVED', '현재 사전이 저장되었습니다.');
define('_MI_DICTIONARY_STATUS_PARALLEL_TEXT_SAVED', '현재 가로 텍스트가 저장되었습니다.');

// Remove
define('_MI_DICTIONARY_NOW_REMOVING', '지금 제거하는 중 ...');

// Read
define('_MI_DICTIONARY_STATUS_NOW_LOADING', '지금 로드하는 중 ...');

// Add Record
define('_MI_DICTIONARY_STATUS_RECORD_ADDED', '새 레코드가 현재 사전에 추가되었습니다.');
define('_MI_DICTIONARY_STATUS_RECORD_ADDED_P', '새 레코드가 현재 가로 텍스트에 추가되었습니다.');

// Delete Record
define('_MI_DICTIONARY_STATUS_RECORD_DELETED', '선택한 레코드가 삭제되었습니다.');

// Add Language(s)
define('_MI_DICTIONARY_STATUS_LANGUAGE_ADDED', '<span style=color:red;>%s</span>이(가) 사전에 추가되었습니다.');
define('_MI_DICTIONARY_STATUS_LANGUAGE_ADDED_P', '<span style=color:red;>%s</span>이(가) 가로 텍스트에 추가되었습니다.');

// Delete Language(s)
define('_MI_DICTIONARY_STATUS_LANGUAGE_DELETED', '<span style=color:red;>%s</span>이(가) 사전에서 삭제되었습니다.');
define('_MI_DICTIONARY_STATUS_LANGUAGE_DELETED_P', '<span style=color:red;>%s</span>이(가) 가로 텍스트에서 삭제되었습니다.');

// PHP
// ERROR
define('_MI_DICTIONARY_ERROR_DICTIONARY_ALREADY_EXISTS', '이미 사용되고 있는 이름입니다.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_ALREADY_EXISTS', '이미 사용되고 있는 이름입니다.');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_LOAD', '사전을 로드할 권한이 없습니다.');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_LOAD_P', '가로 텍스트를 로드할 권한이 없습니다.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NAME_EMPTY', '리소스 이름은 필수 항목입니다.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_NAME_EMPTY', '리소스 이름은 필수 항목입니다.');
define('_MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES', '사전에 2개 이상의 언어가 필요합니다.');
define('_MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES_P', '가로 텍스트에 2개 이상의 언어가 필요합니다.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NAME_INVALID', '사전 이름이 잘못되었습니다.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_NAME_INVALID', '가로 텍스트 이름이 잘못되었습니다.');
//20090919 add
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_DEPLOY', '사전을 배포할 권한이 없습니다.');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_UNDEPLOY', '사전을 배포 취소할 권한이 없습니다.');

/**
 * JavaScript Error
 */
// Load
define('_MI_DICTIONARY_ERROR_UPLOAD_INVALID_LANGUAGE_TAG', '언어 코드 "%s"이(가) 잘못되었습니다.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NOT_FOUND', '먼저 사전/가로 텍스트를 먼저 생성하거나 가져오십시오.');

//Confirm
define('_MI_DICTIONARY_CONFIRM_DELETE_LANGUAGE_ROWS', '선택한 언어를 삭제하시겠습니까?');
define('_MI_DICTIONARY_CONFIRM_DELETE_SELECTED_COLUMNS', '선택한 레코드를 삭제하시겠습니까?');
//20090919 add
define('_MI_DICTIONARY_CONFIRM_DEPLOY_DICT', '사전을 배포하시겠습니까?');
define('_MI_DICTIONARY_CONFIRM_UNDEPLOY_DICT', '사전을 배포 취소하시겠습니까?');
define('_MI_DICTIONARY_CONFIRM_DEPLOY_PARALLEL', '가로 텍스트를 배포하시겠습니까?');
define('_MI_DICTIONARY_CONFIRM_UNDEPLOY_PARALLEL', '가로 텍스트를 배포 취소하시겠습니까?');
//20090925 add
define('_MI_DICTIONARY_ERROR_DICTIONARY_IS_NOT_DEPLOYED', '선택한 사전이 배포되지 않았습니다.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_NOT_DEPLOYED', '선택한 가로 텍스트가 배포되지 않았습니다.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_IS_ALREADY_DEPLOYED', '선택한 사전은 이미 배포되었습니다.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_ALREADY_DEPLOYED', '선택한 가로 텍스트는 이미 배포되었습니다.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_DEPLOY_IS_NOT_PERMITTED', '선택한 사전을 배포할 권한이 없습니다.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_NOT_PERMITTED', '선택한 가로 텍스트를 배포할 권한이 없습니다.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_UNDEPLOY_IS_NOT_PERMITTED', '선택한 사전을 배포 취소할 권한이 없습니다.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_UNDEPLOY_IS_NOT_PERMITTED', '선택한 가로 텍스트를 배포 취소할 권한이 없습니다.');
//20091001 add
define('_MI_DICTIONARY_ERROR_RECORD_NO_SELECTED', '사전을 선택하지 않았습니다.');
define('_MI_DICTIONARY_ERROR_RECORD_NO_SELECTED_P', '가로 텍스트를 선택하지 않았습니다.');
//20091021 add
define('_MI_DICTIONARY_ERROR_KEYWORD_IS_EMPTY', '키워드가 비어 있습니다.');
define('_MI_DICTIONARY_ERROR_SELECT_A_LANGUAGE', '대상 언어를 하나 이상 선택하십시오.');
define('_MI_DICTIONARY_ERROR_TOO_MUCH_SELECTED_LANGUAGES', 'Please select at most two target language.');
define('_MI_DICTIONARY_ERROR_SEARCH_RESULT_OVERSHOOT', 'There is the search HIT number %s, and there is too much it. Please narrow down a condition.');
//20110111 add
define('_MI_DICTIONARY_ERROR_DICTIONARY_CONFLICT', 'This dictionary has been updated by other users while you are editing. Are you sure to overwrite?');

/**
 * Warning
 */
define('_MI_DICTIONARY_WARNING_NO_MORE_LANGUAGES', '추가할 언어가 더 이상 없습니다.');
define('_MI_DICTIONARY_WARNING_DISCARD_CHANGES', '마지막으로 변경한 후 사전이 저장되지 않았습니다. 변경 내용을 취소하시겠습니까?');
define('_MI_DICTIONARY_WARNING_PARALLEL_TEXT_DISCARD_CHANGES', '마지막으로 변경한 후 가로 텍스트가 저장되지 않았습니다. 변경 내용을 취소하시겠습니까?');
define('_MI_DICTIONARY_WARNING_REMOVE_DICTIONARY', '사전을 제거하시겠습니까?');
define('_MI_DICTIONARY_WARNING_REMOVE_DICTIONARY_P', '가로 텍스트를 제거하시겠습니까?');
define('_MI_DICTIONARY_WARNING_CANNOT_DELETE_LANGUAGES', '사전에 2개 이상의 언어가 필요합니다.');
define('_MI_DICTIONARY_WARNING_CANNOT_DELETE_LANGUAGES_P', '가로 텍스트에 2개 이상의 언어가 필요합니다.');
//20090919 add
define('_MI_DICTIONARY_SEARCH_DICTIONARY_NOT_FOUND', '키워드를 포함하는 사전 항목이 없습니다.');
define('_MI_DICTIONARY_SEARCH_PARALLEL_TEXT_NOT_FOUND', '키워드를 포함하는 가로 텍스트가 없습니다.');
define('_MI_DICTIONARY_DICTIONARY_REMOVE_ERROR_NO_PERMISSION', '사전을 삭제할 권한이 없습니다.');
define('_MI_DICTIONARY_PARALLEL_TEXT_REMOVE_ERROR_NO_PERMISSION', '가로 텍스트를 삭제할 권한이 없습니다.');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_DOWNLOAD_RESOURCE', '리소스를 다운로드할 권한이 없습니다.');

/**
 * Search
 */
define('_MI_DICTIONARY_SEARCH_KEYWORD', '키워드');
define('_MI_DICTIONARY_SEARCH_TO', '끝');
define('_MI_DICTIONARY_SEARCH_SELECT_TYPE', '유형');
define('_MI_DICTIONARY_SEARCH_SELECT_ALL', '모두 선택');
define('_MI_DICTIONARY_SEARCH_MATCHING_METHOD', '일치 방법');
define('_MI_DICTIONARY_SEARCH_PARTIAL', '부분');
define('_MI_DICTIONARY_SEARCH_COMPLETE', '완료');
define('_MI_DICTIONARY_SEARCH_PREFIX', '접두사');
define('_MI_DICTIONARY_SEARCH_SUFFIX', '접미사');
define('_MI_DICTIONARY_SEARCH_FROM', '시작');


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



define('_MI_DICTIONARY_RETURN_TO_TOP', 'Return to top');
define('_MI_DICTIONARY_OK', 'OK');
define('_MI_DICTIONARY_CANCEL', 'Cancel');
define('_MI_DICTIONARY_LANGUAGE', 'Language');
define('_MI_DICTIONARY_ALL_LANGUAGES', 'All languages');
define('_MI_DICTIONARY_OTHER', 'Other');
define('_MI_DICTIONARY_CONFIRM', 'Select');

define('_MI_DICTIONARY_COMMUNITY_PARAPHRASE_DICTIONARY', 'Paraphrase Dictionary');
define('_MI_DICTIONARY_COMMUNITY_NORMALIZE_DICTIONARY', 'Normalize Dictionary');
define('_MI_DICTIONARY_STATUS_NOW_SAVING', 'Now saving ...');


?>

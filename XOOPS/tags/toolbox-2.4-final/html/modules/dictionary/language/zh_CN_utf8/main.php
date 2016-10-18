<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
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
define('_MI_DICTIONARY_NAME2', '语言资源名称');
define('_MI_DICTIONARY_DICTIONARY_SEARCH', '搜索');
define('_MI_DICTIONARY_LANGUAGES', '语言');
define('_MI_DICTIONARY_CREATE_RESOURCE', '新建语言资源');
define('_MI_DICTIONARY_IMPORT_RESOURCE', '导入语言资源');
define('_MI_DICTIONARY_SELECT_LANGUAGES_TO_DELETE', '请选择待删除的语言。');
//20090919 add
define('_MI_DICTIONARY_HOW_TO_USE_LINK', 'dictionary_zh.html');
//20091022 add
define('_MI_DICTIONARY_PARALLEL_TEXT_HOW_TO_USE_LINK', 'paralleltext_zh.html');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University. All rights reserved.');

/**
 * Common
 */
define('_MI_DICTIONARY_COMMON_TYPE', '种类');
define('_MI_DICTIONARY_COMMON_LANGUAGES', '语言');
define('_MI_DICTIONARY_ERROR_FILE_REQUIRED', '请指定文件。');
define('_MI_DICTIONARY_ERROR_FILE_FORMAT_INVALID', '文件格式无效。');
//20090919 add
define('_MI_DICTIONARY_SERVICE', '服务');
define('_MI_DICTIONARY_DEPLOY', '配置');
define('_MI_DICTIONARY_UNDEPLOY', '取消配置');
define('_MI_DICTIONARY_WSDL', 'WSDL');

/**
 * Label
 */
define('_MI_DICTIONARY_COMMUNITY_RESOURCES', '新建/编辑');
define('_MI_DICTIONARY_COMMUNITY_DICTIONARY', '词典');
define('_MI_DICTIONARY_COMMUNITY_PARALLEL_TEXT', '双语对照文本');
define('_MI_DICTIONARY_USER_DICTIONARY', '词典');
define('_MI_DICTIONARY_USER_PARALLEL_TEXT', '双语对照文本');
define('_MI_DICTIONARY_EDIT_PERMISSION', '编辑权限');
define('_MI_DICTIONARY_READ_PERMISSION', '阅读权限');
define('_MI_DICTIONARY_FOR_ALL_USERS', '适用于所有用户');
define('_MI_DICTIONARY_FOR_THE_CURRENT_USER_ONLY', '仅适用于当前用户');
define('_MI_DICTIONARY_USER', '创建者');
define('_MI_DICTIONARY_READ', '阅读');
define('_MI_DICTIONARY_EDIT', '编辑');
define('_MI_DICTIONARY_LAST_UPDATE', '最新更新时间');
define('_MI_DICTIONARY_COUNTS', '条目');
define('_MI_DICTIONARY_SAVE_RESOURCE', '保存词典');
define('_MI_DICTIONARY_SAVE_RESOURCE_P', '保存双语对照文本');
//20090919 add
define('_MI_DICTIONARY_COMMON_RESOURCE_NAME', '语言资源名称');
define('_MI_DICTIONARY_COMMON_RESOURCE_NAME_RULE', '语言资源名称应至少包含4个字符，可使用英文字母（至少1个）、数字、空格、连字符“-”和“.”。');
//20090926 add
define('_MI_DICTIONARY_COMMON_ADD_LANGUAGE', '添加语言');
define('_MI_DICTIONARY_SELECT_LANGUAGES_TO_ADD', '请选择待添加的语言。');
define('_MI_DICTIONARY_COMMON_DELETE_LANGUAGE', '删除语言');

/**
 * Button
 */
define('_MI_DICTIONARY_CREATE', '新建');
define('_MI_DICTIONARY_IMPORT', '导入');
define('_MI_DICTIONARY_EXPORT', '导出');
define('_MI_DICTIONARY_LOAD', '载入');
define('_MI_DICTIONARY_REMOVE', '移除');
define('_MI_DICTIONARY_SAVE', '保存');
define('_MI_DICTIONARY_CLOSE', '关闭');
define('_MI_DICTIONARY_ADD_RECORD', '添加记录');
define('_MI_DICTIONARY_DELETE_RECORD', '删除记录');
define('_MI_DICTIONARY_ADD_LANGUAGE', '添加语言');
define('_MI_DICTIONARY_DELETE_LANGUAGE', '删除语言');

/**
 * Status
 */
// Create
define('_MI_DICTIONARY_STATUS_CREATING_DICTIONARY', '正在新建词典……');
define('_MI_DICTIONARY_STATUS_CREATING_DICTIONARY_P', '正在新建双语对照文本……');
define('_MI_DICTIONARY_STATUS_DICTIONARY_CREATED', '新建词典完成。');
define('_MI_DICTIONARY_STATUS_PARALLEL_TEXT_CREATED', '新建双语对照文本完成。');

// Save
define('_MI_DICTIONARY_STATUS_DICTIONARY_SAVED', '当前词典已保存。');
define('_MI_DICTIONARY_STATUS_PARALLEL_TEXT_SAVED', '当前双语对照文本已保存。');

// Remove
define('_MI_DICTIONARY_NOW_REMOVING', '正在移除……');

// Read
define('_MI_DICTIONARY_STATUS_NOW_LOADING', '正在载入……');

// Add Record
define('_MI_DICTIONARY_STATUS_RECORD_ADDED', '新记录已添加至当前词典。');
define('_MI_DICTIONARY_STATUS_RECORD_ADDED_P', '新记录已添加至当前双语对照文本。');

// Delete Record
define('_MI_DICTIONARY_STATUS_RECORD_DELETED', '所选记录已删除。');

// Add Language(s)
define('_MI_DICTIONARY_STATUS_LANGUAGE_ADDED', '<span style=color:red;>%s</span>已添加至词典。');
define('_MI_DICTIONARY_STATUS_LANGUAGE_ADDED_P', '<span style=color:red;>%s</span>已添加至双语对照文本。');

// Delete Language(s)
define('_MI_DICTIONARY_STATUS_LANGUAGE_DELETED', '<span style=color:red;>%s</span> 已从词典中删除。');
define('_MI_DICTIONARY_STATUS_LANGUAGE_DELETED_P', '<span style=color:red;>%s</span> 已从双语对照文本中删除。');

// PHP
// ERROR
define('_MI_DICTIONARY_ERROR_DICTIONARY_ALREADY_EXISTS', '名称已存在。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_ALREADY_EXISTS', '名称已存在。');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_LOAD', '您无权载入词典。');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_LOAD_P', '您无权载入双语对照文本。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NAME_EMPTY', '语言资源名称为必填项。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_NAME_EMPTY', '语言资源名称为必填项。');
define('_MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES', '词典中至少应包含2种语言。');
define('_MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES_P', '双语对照文本中至少应包含2种语言。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NAME_INVALID', '词典名称无效。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_NAME_INVALID', '双语对照文本名称无效。');
//20090919 add
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_DEPLOY', '您无权配置词典。');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_UNDEPLOY', '您无权取消配置词典。');

/**
 * JavaScript Error
 */
// Load
define('_MI_DICTIONARY_ERROR_UPLOAD_INVALID_LANGUAGE_TAG', '语言代码“%s”无效。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NOT_FOUND', '请首先新建或导入词典/双语对照文本。');

//Confirm
define('_MI_DICTIONARY_CONFIRM_DELETE_LANGUAGE_ROWS', '您确定删除所选语言吗？');
define('_MI_DICTIONARY_CONFIRM_DELETE_SELECTED_COLUMNS', '您确定删除所选记录吗？');
//20090919 add
define('_MI_DICTIONARY_CONFIRM_DEPLOY_DICT', '您确定配置词典吗？');
define('_MI_DICTIONARY_CONFIRM_UNDEPLOY_DICT', '您确定取消配置词典吗？');
define('_MI_DICTIONARY_CONFIRM_DEPLOY_PARALLEL', '您确定配置双语对照文本吗？');
define('_MI_DICTIONARY_CONFIRM_UNDEPLOY_PARALLEL', '您确定取消配置双语对照文本吗？');
//20090925 add
define('_MI_DICTIONARY_ERROR_DICTIONARY_IS_NOT_DEPLOYED', '尚未配置所选词典。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_NOT_DEPLOYED', '尚未配置所选双语对照文本。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_IS_ALREADY_DEPLOYED', '已配置所选词典。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_ALREADY_DEPLOYED', '已配置所选双语对照文本。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_DEPLOY_IS_NOT_PERMITTED', '您无权配置所选词典。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_NOT_PERMITTED', '您无权配置所选双语对照文本。');
define('_MI_DICTIONARY_ERROR_DICTIONARY_UNDEPLOY_IS_NOT_PERMITTED', '您无权取消配置所选词典。');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_UNDEPLOY_IS_NOT_PERMITTED', '您无权取消配置所选双语对照文本。');
//20091001 add
define('_MI_DICTIONARY_ERROR_RECORD_NO_SELECTED', '尚未选择任何词典。');
define('_MI_DICTIONARY_ERROR_RECORD_NO_SELECTED_P', '尚未选择任何双语对照文本。');
//20091021 add
define('_MI_DICTIONARY_ERROR_KEYWORD_IS_EMPTY', '关键词为空。');
define('_MI_DICTIONARY_ERROR_SELECT_A_LANGUAGE', '请至少选择一种目标语言。');
define('_MI_DICTIONARY_ERROR_SEARCH_RESULT_OVERSHOOT', 'There is the search HIT number %s, and there is too much it. Please narrow down a condition.');
//20110111 add
define('_MI_DICTIONARY_ERROR_DICTIONARY_CONFLICT', 'This dictionary has been updated by other users while you are editing. Are you sure to overwrite?');

/**
 * Warning
 */
define('_MI_DICTIONARY_WARNING_NO_MORE_LANGUAGES', '无法再添加语言。');
define('_MI_DICTIONARY_WARNING_DISCARD_CHANGES', '上次修改后未保存词典。放弃修改吗？');
define('_MI_DICTIONARY_WARNING_PARALLEL_TEXT_DISCARD_CHANGES', '上次修改后未保存双语对照文本。放弃修改吗？');
define('_MI_DICTIONARY_WARNING_REMOVE_DICTIONARY', '您确定删除词典吗？');
define('_MI_DICTIONARY_WARNING_REMOVE_DICTIONARY_P', '您确定删除双语对照文本吗？');
define('_MI_DICTIONARY_WARNING_CANNOT_DELETE_LANGUAGES', '词典中至少应包含2种语言。');
define('_MI_DICTIONARY_WARNING_CANNOT_DELETE_LANGUAGES_P', '双语对照文本中至少应包含2种语言。');
//20090919 add
define('_MI_DICTIONARY_SEARCH_DICTIONARY_NOT_FOUND', '未找到包含关键词的词典条目。');
define('_MI_DICTIONARY_SEARCH_PARALLEL_TEXT_NOT_FOUND', '未找到包含关键词的双语对照文本。');
define('_MI_DICTIONARY_DICTIONARY_REMOVE_ERROR_NO_PERMISSION', '您无权删除词典。');
define('_MI_DICTIONARY_PARALLEL_TEXT_REMOVE_ERROR_NO_PERMISSION', '您无权删除双语对照文本。');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_DOWNLOAD_RESOURCE', '您无权下载语言资源。');

/**
 * Search
 */
define('_MI_DICTIONARY_SEARCH_KEYWORD', '关键词');
define('_MI_DICTIONARY_SEARCH_TO', '至');
define('_MI_DICTIONARY_SEARCH_SELECT_TYPE', '种类');
define('_MI_DICTIONARY_SEARCH_SELECT_ALL', '选择全部');
define('_MI_DICTIONARY_SEARCH_MATCHING_METHOD', '匹配方式');
define('_MI_DICTIONARY_SEARCH_PARTIAL', '部分一致');
define('_MI_DICTIONARY_SEARCH_COMPLETE', '全部一致');
define('_MI_DICTIONARY_SEARCH_PREFIX', '前缀一致');
define('_MI_DICTIONARY_SEARCH_SUFFIX', '后缀一致');
define('_MI_DICTIONARY_SEARCH_FROM', '自');


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
define('_MI_DICTIONARY_STATUS_NOW_SAVING', 'Now saving ...');


?>

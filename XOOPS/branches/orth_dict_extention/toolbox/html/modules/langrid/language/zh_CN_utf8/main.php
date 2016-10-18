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
define('_MD_LANGRID_TAB3_NAME', '文本翻译');
//20091112 add
define('_MD_LANGRID_TAB5_NAME', '在线翻译');
//20100107 add
define('_MD_LANGRID_TAB6_NAME', 'Disucssion');
//20100120 add
define('_MD_LANGRID_TAB7_NAME', 'Collaborative translation');
//20100205 add
if (!defined('_MD_COPYRIGHT_LB')) {
define('_MD_COPYRIGHT_LB', '');
}

/* for BBS Page */
define('_MD_LANGRID_BBS_STG_ADD_PATH_BTN', '添加翻译路径');
define('_MD_LANGRID_BBS_STG_SUBMIT_BTN', '保存');
define('_MD_LANGRID_BBS_STG_CONBINATION_BTN', '更多翻译');
define('_MD_LANGRID_BBS_STG_DELETE_BTN', '删除');
define('_MD_LANGRID_BBS_STG_DICTIONARY_BTN', '词典');
define('_MD_LANGRID_STG_ERR_MSG3', '该翻译路径赋值无效。');
define('_MD_LANGRID_STG_MSG3', '您确定删除翻译路径“%SRC %FLW %TGT”吗?');
define('_MD_LANGRID_DICT_POP_TITLE', '词典');
define('_MD_LANGRID_DICT_POP_T1', '在线词典');
define('_MD_LANGRID_DICT_POP_T2', '临时词典');
define('_MD_LANGRID_DICT_POP_OK', '确定');
define('_MD_LANGRID_DICT_POP_CANCEL', '取消');
define('_MD_LANGRID_STG_ERR_MSG1', '请选择翻译器。');
define('_MD_LANGRID_STG_MSG2', 'BBS翻译路径已成功保存。');
define('_MD_LANGRID_NOWLOADING', '正在载入……');
//20090922 add
define('_MD_LANGRID_STG_MSG4', '仅管理员可编辑翻译路径。');
define('_MD_LANGRID_SETTING_DEFAULT_DICTIONARY', '默认词典');
define('_MD_LANGRID_SETTING_CUSTOM_DICTIONARY', '定制词典');
define('_MD_LANGRID_SETTING_ADVANCED', '高级选项');
define('_MD_LANGRID_INFO_POP_PROVIDER', '提供者');
define('_MD_LANGRID_INFO_POP_COPYRIGHT', '版权信息');
define('_MD_LANGRID_INFO_POP_LICENSE', '许可信息');
define('_MD_LANGRID_SETTING_VIEW_DEFAULT_DICT', '查看默认词典');
define('_MD_LANGRID_SETTING_CLOSE_BUTTON', '关闭');
define('_MD_LANGRID_DICT_POP_LOAD_DEFAULT', '加载默认设定');
define('_MD_LANGRID_SETTING_FILTER_FROM', '筛选自');
define('_MD_LANGRID_SETTING_FILTER_TO', '至');
define('_MD_LANGRID_SETTING_MATCHES', '匹配');
define('_MD_LANGRID_SETTING_EDIT_DEFAULT_DICT', '编辑默认词典');
define('_MD_LANGRID_SETTING_RETURN_TOP', '返回顶部');
define('_MD_LANGRID_SETTING_DICT_SELECT', '已选择%S部词典');
define('_MD_LANGRID_SETTING_DICT_NO_SELECT', '未选择任何词典');
define('_MD_LANGRID_SETTING_FILTER_BTN', '筛选');
define('_MD_LANGRID_SETTING_DISPLAY_ALL_PATH', '显示所有翻译路径');
define('_MD_LANGRID_SETTING_ADD_BUTTON', '添加');
define('_MD_LANGRID_STG_ADDED_MESSAGE', '翻译路径“%SRC %FLW %TGT”已添加。');
define('_MD_LANGRID_STG_SAVED_MESSAGE', '翻译路径“%SRC %FLW %TGT”已修改。');
define('_MD_LANGRID_CONFIRM_CANCEL', '放弃修改吗？');
//20090928 add
define('_MD_LANGRID_INFO_POP_DESCRIPTION', '服务说明');
//20091006 add
define('_MD_LANGRID_DICT_POP_MSG_NO_DICT', '未选择任何在线词典。');
define('_MD_LANGRID_DICT_POP_MSG_NO_COM_DICT', '未选择任何临时词典。');
//20091019 add
define('_MD_LANGRID_DICT_POP_MORPHOLOGICAL_ANALYZER', '构词分析程序');
//20091022 add
define('_MI_LANGRID_TEXT_HOW_TO_USE_LINK', 'settings_Text_zh.html');
define('_MI_LANGRID_BBS_HOW_TO_USE_LINK', 'settings_BBS_zh.html');
define('_MI_LANGRID_IMPORTED_SERVICES_HOW_TO_USE_LINK', 'import_zh.html');
define('_MI_LANGRID_WEB_HOW_TO_USE_LINK', 'settings_Web_zh.html');
define('_MD_LANGRID_DICT_POP_T3', '本地词典');
define('_MD_LANGRID_DICT_POP_MSG_NO_IMP_DICT', '未选择任何本地词典。');
//20091027 add
define('_MD_LANGRID_DICT_MSG_NO_DICTIONARY_LOCAL', '无本地词典');
define('_MD_LANGRID_DICT_MSG_NO_DICTIONARY_TEMPORAL', '无临时词典');
//20091029 add
define('_MD_LANGRID_DICT_MSG_OVER_SELECT_DICTIONARY', '最多可选择5部在线词典/本地词典。');
//20100120 add
define('_MI_LANGRID_TRANS_HOW_TO_USE_LINK', 'settings_Collabtrans_zh.html');
//20100129 add
define('_MI_LANGRID_COM_HOW_TO_USE_LINK', 'settings_Discussion_zh.html');

/* Imported Services */
define('_MD_LANGRID_IMPORTED_SERVICES', '已导入的服务');
define('_MD_LANGRID_IMPORTED_SERVICES_ADD_SERVICE', '+添加服务');
define('_MD_LANGRID_IMPORTED_SERVICES_EDIT_SERVICE', '编辑');
define('_MD_LANGRID_IMPORTED_SERVICES_REMOVE_SERVICE', '移除');
define('_MD_LANGRID_IMPORTED_SERVICES_SERVICE_NAME_IS_IN_USE', '服务名称“%s”已存在。');
define('_MD_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL_IS_IN_USE', 'Endpoint URL“%s”已存在。');
define('_MI_LANGRID_IMPORTED_SERVICES_ADD', '添加');
define('_MI_LANGRID_IMPORTED_SERVICES_ADD_SERVICE', '添加服务');
define('_MI_LANGRID_IMPORTED_SERVICES_ARE_YOU_REALLY_SURE_YOU_WANT_TO_REMOVE_THE_SERVICE', '您确定移除服务吗？');
define('_MI_LANGRID_IMPORTED_SERVICES_AT_LEAST_ONE_LANGUAGE_PATH_IS_REQUIRED', '至少需要一个语言路径。');
define('_MI_LANGRID_IMPORTED_SERVICES_AT_LEAST_TWO_LANGUAGES_ARE_REQUIRED', '至少需要两种语言。');
define('_MI_LANGRID_IMPORTED_SERVICES_CANCEL', '取消');
define('_MI_LANGRID_IMPORTED_SERVICES_COPYRIGHT', '版权');
define('_MI_LANGRID_IMPORTED_SERVICES_DICTIONARY', '词典');
define('_MI_LANGRID_IMPORTED_SERVICES_BIDIRECTIONAL', '<->');
define('_MI_LANGRID_IMPORTED_SERVICES_EDIT_SERVICE', '编辑服务');
define('_MI_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL', 'Endpoint');
define('_MI_LANGRID_IMPORTED_SERVICES_IMPORT', '导入');
define('_MI_LANGRID_IMPORTED_SERVICES_LANGUAGE', '语言');
define('_MI_LANGRID_IMPORTED_SERVICES_LANGUAGES', '语言');
define('_MI_LANGRID_IMPORTED_SERVICES_LICENSE', '许可');
define('_MI_LANGRID_IMPORTED_SERVICES_NO_IMPORTED_SERVICES', '未导入任何服务。');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_IMPORTING', '正在导入……');
define('_MI_LANGRID_IMPORTED_SERVICES_NO_SERVICE_IS_SELECTED', '未选择任何服务。');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_LOADING', '正在载入……');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_REMOVING', '正在移除……');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_SAVING', '正在保存……');
define('_MI_LANGRID_IMPORTED_SERVICES_NULL_LANGUAGE_NAME', '---');
define('_MI_LANGRID_IMPORTED_SERVICES_NULL_TABLE_VALUE', '<div class="align-center">-</div>');
define('_MI_LANGRID_IMPORTED_SERVICES_OK', '确定');
define('_MI_LANGRID_IMPORTED_SERVICES_PROVIDER', '提供者');
define('_MI_LANGRID_IMPORTED_SERVICES_REGISTRATION_DATE', '注册日期');
define('_MI_LANGRID_IMPORTED_SERVICES_REMOVE_LANGUAGE', '移除语言');
define('_MI_LANGRID_IMPORTED_SERVICES_MONODIRECTIONAL', '->');
define('_MI_LANGRID_IMPORTED_SERVICES_SAVE', '保存');
define('_MI_LANGRID_IMPORTED_SERVICES_SERVICE_NAME', '服务名称');
define('_MI_LANGRID_IMPORTED_SERVICES_SERVICE_TYPE', '服务类型');
define('_MI_LANGRID_IMPORTED_SERVICES_STRING_IS_BLANK', '{0}为空。');
define('_MI_LANGRID_IMPORTED_SERVICES_TRANSLATOR', '翻译');
define('_MI_LANGRID_IMPORTED_SERVICES_ON_AJAX_SERVER_ERROR', '与服务器链接时发生错误。请重新载入页面。');
//20091030 add
define('_MI_LANGRID_IMPORTED_SERVICES_REQUIRED_FIELD', '*必填项目');
//20091127 add
define('_MI_LANGRID_IMPORTED_SERVICES_HAS_DUPLICATED_PATHS', '存在双重语言路径。');
define('_MI_LANGRID_IMPORTED_SERVICES_HAS_SAME_PATHS', '语言路径应包含不同的语言。');
define('_MI_LANGRID_IMPORTED_SERVICES_THE_INPUT_URL_IS_INVALID', '输入的URL无效。');


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
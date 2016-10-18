<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
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

//Used by Wiki
define('_MD_LANGRID_SEARCH_BOX_TITLE', 'Search');
define('_MD_LANGRID_SETTING_FILTER_FROM', 'from');
define('_MD_LANGRID_SETTING_FILTER_TO', 'to');
define('_MD_LANGRID_SETTING_FILTER_BTN', 'Search');
define('_MD_LANGRID_SETTING_DISPLAY_ALL_PATH', 'Display all translation path');

define('_MD_LANGRID_BBS_STG_ADD_PATH_BTN', 'Add translation path');
define('_MD_LANGRID_ADD_PATH_DESC', '　*Customized dictionary(ies)');

define('_MD_LANGRID_MSG_PATH_NOT_FOUND', 'Please create a new translation path settings.');
//Used by Toolbox
/* for PageTab Label */
define('_MD_LANGRID_TAB2_NAME', 'BBS');
define('_MD_LANGRID_TAB3_NAME', 'Text translation');
//20091112 add
define('_MD_LANGRID_TAB5_NAME', 'Web creation');

/* for BBS Page */
define('_MD_LANGRID_BBS_STG_SUBMIT_BTN', 'Save');
define('_MD_LANGRID_BBS_STG_CONBINATION_BTN', 'Enable further translation');
define('_MD_LANGRID_BBS_STG_DELETE_BTN', 'Delete');
define('_MD_LANGRID_BBS_STG_DICTIONARY_BTN', 'Dictionary');
define('_MD_LANGRID_STG_ERR_MSG3', 'The assignment of translator(s) for this translation path is invalid.');
define('_MD_LANGRID_STG_MSG3', 'Are you sure to delete translation path "%SRC %FLW %TGT"?');
define('_MD_LANGRID_DICT_POP_TITLE', 'Dictionary');
define('_MD_LANGRID_DICT_POP_T1', 'Global dictionary');
define('_MD_LANGRID_DICT_POP_T2', 'Temporal dictionary');
define('_MD_LANGRID_DICT_POP_OK', 'OK');
define('_MD_LANGRID_DICT_POP_CANCEL', 'Cancel');
define('_MD_LANGRID_STG_ERR_MSG1', 'Please select a machine translator.');
define('_MD_LANGRID_STG_MSG2', 'Translation paths for BBS were successfully saved.');
define('_MD_LANGRID_NOWLOADING', 'Now loading ...');
//20090922 add
define('_MD_LANGRID_STG_MSG4', 'Only administrators can edit translation paths.');
define('_MD_LANGRID_SETTING_DEFAULT_DICTIONARY', 'Default');
define('_MD_LANGRID_SETTING_CUSTOM_DICTIONARY', 'Customized');
define('_MD_LANGRID_SETTING_ADVANCED', 'Advanced options');
define('_MD_LANGRID_INFO_POP_PROVIDER', 'Provider');
define('_MD_LANGRID_INFO_POP_COPYRIGHT', 'Copyright');
define('_MD_LANGRID_INFO_POP_LICENSE', 'License');
define('_MD_LANGRID_SETTING_VIEW_DEFAULT_DICT', 'View default dictionary');
define('_MD_LANGRID_SETTING_CLOSE_BUTTON', 'Close');
define('_MD_LANGRID_DICT_POP_LOAD_DEFAULT', 'Load default');
define('_MD_LANGRID_SETTING_MATCHES', 'matches');
define('_MD_LANGRID_SETTING_EDIT_DEFAULT_DICT', 'Edit default dictionary');
define('_MD_LANGRID_SETTING_EDIT_TRANSLATION_OPTIONS', 'Edit translation options');
define('_MD_LANGRID_SETTING_RETURN_TOP', 'Return to top');
define('_MD_LANGRID_SETTING_DICT_SELECT', '%S dictionary(ies) selected');
define('_MD_LANGRID_SETTING_DICT_NO_SELECT', 'No dictionary selected');
define('_MD_LANGRID_SETTING_NO_TRANSLATION_OPTIONS', 'No transaltion options enabled');
define('_MD_LANGRID_SETTING_LITE', 'Escape translation of quoted phrases');
define('_MD_LANGRID_SETTING_RICH', 'Display original words');
define('_MD_LANGRID_SETTING_ADD_BUTTON', 'Add');
define('_MD_LANGRID_STG_ADDED_MESSAGE', 'Translation path "%SRC %FLW %TGT" was added.');
define('_MD_LANGRID_STG_SAVED_MESSAGE', 'Translation path "%SRC %FLW %TGT" was changed.');
define('_MD_LANGRID_CONFIRM_CANCEL', 'Discard the change?');
//20090928 add
define('_MD_LANGRID_INFO_POP_DESCRIPTION', 'Service Description');
//20091006 add
define('_MD_LANGRID_DICT_POP_MSG_NO_DICT', 'No global dictionary is selected.');
define('_MD_LANGRID_DICT_POP_MSG_NO_COM_DICT', 'No temporal dictionary is selected.');
//20091019 add
define('_MD_LANGRID_DICT_POP_MORPHOLOGICAL_ANALYZER', 'Morphological Analyzer');
//20091022 add
define('_MI_LANGRID_TEXT_HOW_TO_USE_LINK', 'settings_Text_en.html');
define('_MI_LANGRID_BBS_HOW_TO_USE_LINK', 'settings_BBS_en.html');
define('_MI_LANGRID_IMPORTED_SERVICES_HOW_TO_USE_LINK', 'import_en.html');
define('_MI_LANGRID_WEB_HOW_TO_USE_LINK', 'settings_Web_en.html');
define('_MD_LANGRID_DICT_POP_T3', 'Local dictionary');
define('_MD_LANGRID_DICT_POP_MSG_NO_IMP_DICT', 'No local dictionary is selected.');
//20091027 add
define('_MD_LANGRID_DICT_MSG_NO_DICTIONARY_LOCAL', 'No local dictionary');
define('_MD_LANGRID_DICT_MSG_NO_DICTIONARY_TEMPORAL', 'No temporal dictionary');
//20091029 add
define('_MD_LANGRID_DICT_MSG_OVER_SELECT_DICTIONARY', 'You can select up to 5 global/local dictionaries in total.');

/* Imported Services */
define('_MD_LANGRID_IMPORTED_SERVICES', 'Imported services');
define('_MD_LANGRID_IMPORTED_SERVICES_ADD_SERVICE', '+ Add service');
define('_MD_LANGRID_IMPORTED_SERVICES_EDIT_SERVICE', 'Edit');
define('_MD_LANGRID_IMPORTED_SERVICES_REMOVE_SERVICE', 'Remove');
define('_MD_LANGRID_IMPORTED_SERVICES_SERVICE_NAME_IS_IN_USE', 'Service name "%s" is already in use.');
define('_MD_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL_IS_IN_USE', 'Endpoint URL "%s" is already in use.');
define('_MI_LANGRID_IMPORTED_SERVICES_ADD', 'Add');
define('_MI_LANGRID_IMPORTED_SERVICES_ADD_SERVICE', 'Add service');
define('_MI_LANGRID_IMPORTED_SERVICES_ARE_YOU_REALLY_SURE_YOU_WANT_TO_REMOVE_THE_SERVICE', 'Are you really sure you want to remove the service?');
define('_MI_LANGRID_IMPORTED_SERVICES_AT_LEAST_ONE_LANGUAGE_PATH_IS_REQUIRED', 'At least one language path is required.');
define('_MI_LANGRID_IMPORTED_SERVICES_AT_LEAST_TWO_LANGUAGES_ARE_REQUIRED', 'At least two languages are required.');
define('_MI_LANGRID_IMPORTED_SERVICES_CANCEL', 'Cancel');
define('_MI_LANGRID_IMPORTED_SERVICES_COPYRIGHT', 'Copyright');
define('_MI_LANGRID_IMPORTED_SERVICES_DICTIONARY', 'Dictionary');
define('_MI_LANGRID_IMPORTED_SERVICES_BIDIRECTIONAL', '<->');
define('_MI_LANGRID_IMPORTED_SERVICES_EDIT_SERVICE', 'Edit service');
define('_MI_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL', 'Endpoint URL');
define('_MI_LANGRID_IMPORTED_SERVICES_IMPORT', 'Import');
define('_MI_LANGRID_IMPORTED_SERVICES_LANGUAGE', 'Language');
define('_MI_LANGRID_IMPORTED_SERVICES_LANGUAGES', 'Languages');
define('_MI_LANGRID_IMPORTED_SERVICES_LICENSE', 'License');
define('_MI_LANGRID_IMPORTED_SERVICES_NO_IMPORTED_SERVICES', 'There is no imported service.');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_IMPORTING', 'Now importing ...');
define('_MI_LANGRID_IMPORTED_SERVICES_NO_SERVICE_IS_SELECTED', 'No service is selected.');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_LOADING', 'Now loading ...');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_REMOVING', 'Now removing ...');
define('_MI_LANGRID_IMPORTED_SERVICES_NOW_SAVING', 'Now saving ...');
define('_MI_LANGRID_IMPORTED_SERVICES_NULL_LANGUAGE_NAME', '---');
define('_MI_LANGRID_IMPORTED_SERVICES_NULL_TABLE_VALUE', '<div class="align-center">-</div>');
define('_MI_LANGRID_IMPORTED_SERVICES_OK', 'OK');
define('_MI_LANGRID_IMPORTED_SERVICES_PROVIDER', 'Provider');
define('_MI_LANGRID_IMPORTED_SERVICES_REGISTRATION_DATE', 'Registration date');
define('_MI_LANGRID_IMPORTED_SERVICES_REMOVE_LANGUAGE', 'Remove language');
define('_MI_LANGRID_IMPORTED_SERVICES_MONODIRECTIONAL', '->');
define('_MI_LANGRID_IMPORTED_SERVICES_SAVE', 'Save');
define('_MI_LANGRID_IMPORTED_SERVICES_SERVICE_NAME', 'Service name');
define('_MI_LANGRID_IMPORTED_SERVICES_SERVICE_TYPE', 'Service type');
define('_MI_LANGRID_IMPORTED_SERVICES_STRING_IS_BLANK', '{0} is blank.');
define('_MI_LANGRID_IMPORTED_SERVICES_TRANSLATOR', 'Translator');
define('_MI_LANGRID_IMPORTED_SERVICES_ON_AJAX_SERVER_ERROR', 'An error has occurred during communication with the server. Please reload the page.');
//20091030 add
define('_MI_LANGRID_IMPORTED_SERVICES_REQUIRED_FIELD', '*Required field');
//20091127 add
define('_MI_LANGRID_IMPORTED_SERVICES_HAS_DUPLICATED_PATHS', 'There are duplicated language paths.');
define('_MI_LANGRID_IMPORTED_SERVICES_HAS_SAME_PATHS', 'Language path should contain different languages.');
define('_MI_LANGRID_IMPORTED_SERVICES_THE_INPUT_URL_IS_INVALID', 'The URL you entered is invalid.');


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
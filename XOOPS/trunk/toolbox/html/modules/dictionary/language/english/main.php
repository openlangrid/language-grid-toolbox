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
define('_MI_DICTIONARY_NAME2', 'Resource name');
define('_MI_DICTIONARY_DICTIONARY_SEARCH', 'Search');
define('_MI_DICTIONARY_LANGUAGES', 'Languages');
define('_MI_DICTIONARY_CREATE_RESOURCE', 'Create a language resource');
define('_MI_DICTIONARY_IMPORT_RESOURCE', 'Import a language resource');
define('_MI_DICTIONARY_SELECT_LANGUAGES_TO_DELETE', 'Select language(s) to delete.');
//20090919 add
define('_MI_DICTIONARY_HOW_TO_USE_LINK', 'dictionary_en.html');
//20091022 add
define('_MI_DICTIONARY_PARALLEL_TEXT_HOW_TO_USE_LINK', 'paralleltext_en.html');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University. All rights reserved.');

/**
 * Common
 */
define('_MI_DICTIONARY_COMMON_TYPE', 'Type');
define('_MI_DICTIONARY_COMMON_LANGUAGES', 'Languages');
define('_MI_DICTIONARY_ERROR_FILE_REQUIRED', 'File is required.');
define('_MI_DICTIONARY_ERROR_FILE_FORMAT_INVALID', 'The file format is invalid.');
//20090919 add
define('_MI_DICTIONARY_SERVICE', 'Service');
define('_MI_DICTIONARY_DEPLOY', 'Deploy');
define('_MI_DICTIONARY_UNDEPLOY', 'Undeploy');
define('_MI_DICTIONARY_WSDL', 'WSDL');

/**
 * Label
 */
define('_MI_DICTIONARY_COMMUNITY_RESOURCES', 'Create/Edit');
define('_MI_DICTIONARY_COMMUNITY_DICTIONARY', 'Dictionary');
define('_MI_DICTIONARY_COMMUNITY_PARALLEL_TEXT', 'Parallel text');
define('_MI_DICTIONARY_USER_DICTIONARY', 'Dictionary');
define('_MI_DICTIONARY_USER_PARALLEL_TEXT', 'Parallel text');
define('_MI_DICTIONARY_EDIT_PERMISSION', 'Edit permission');
define('_MI_DICTIONARY_READ_PERMISSION', 'Read permission');
define('_MI_DICTIONARY_FOR_ALL_USERS', 'For all users');
define('_MI_DICTIONARY_FOR_THE_CURRENT_USER_ONLY', 'For the current user only');
define('_MI_DICTIONARY_USER', 'Creator');
define('_MI_DICTIONARY_READ', 'Read');
define('_MI_DICTIONARY_EDIT', 'Edit');
define('_MI_DICTIONARY_LAST_UPDATE', 'Last update');
define('_MI_DICTIONARY_COUNTS', 'Entries');
define('_MI_DICTIONARY_SAVE_RESOURCE', 'Save dictionary');
define('_MI_DICTIONARY_SAVE_RESOURCE_P', 'Save parallel text');
//20090919 add
define('_MI_DICTIONARY_COMMON_RESOURCE_NAME', 'Resource name');
define('_MI_DICTIONARY_COMMON_RESOURCE_NAME_RULE', 'Resource name should consist of 4 or more characters including English letters (at least one), numerals, space character " ", hyphen "-" and period "."');
//20090926 add
define('_MI_DICTIONARY_COMMON_ADD_LANGUAGE', 'Add language');
define('_MI_DICTIONARY_SELECT_LANGUAGES_TO_ADD', 'Select language(s) to add.');
define('_MI_DICTIONARY_COMMON_DELETE_LANGUAGE', 'Delete language');

/**
 * Button
 */
define('_MI_DICTIONARY_CREATE', 'Add');
define('_MI_DICTIONARY_IMPORT', 'Import');
define('_MI_DICTIONARY_EXPORT', 'Export');
define('_MI_DICTIONARY_LOAD', 'View');
define('_MI_DICTIONARY_REMOVE', 'Remove');
define('_MI_DICTIONARY_SAVE', 'Save');
define('_MI_DICTIONARY_CLOSE', 'Close');
define('_MI_DICTIONARY_ADD_RECORD', 'Add record');
define('_MI_DICTIONARY_DELETE_RECORD', 'Delete record');
define('_MI_DICTIONARY_ADD_LANGUAGE', 'Add language');
define('_MI_DICTIONARY_DELETE_LANGUAGE', 'Delete language');
define('_MI_DICTIONARY_ADD_WORD', 'Add word');

/**
 * Status
 */
// Create
define('_MI_DICTIONARY_STATUS_CREATING_DICTIONARY', 'Creating dictionary ...');
define('_MI_DICTIONARY_STATUS_CREATING_DICTIONARY_P', 'Creating parallel text ...');
define('_MI_DICTIONARY_STATUS_DICTIONARY_CREATED', 'The dictionary was created.');
define('_MI_DICTIONARY_STATUS_PARALLEL_TEXT_CREATED', 'The parallel text was created.');

// Save
define('_MI_DICTIONARY_STATUS_DICTIONARY_SAVED', 'The current dictionary was saved.');
define('_MI_DICTIONARY_STATUS_PARALLEL_TEXT_SAVED', 'The current parallel text was saved.');

// Remove
define('_MI_DICTIONARY_NOW_REMOVING', 'Now Removing ...');

// Read
define('_MI_DICTIONARY_STATUS_NOW_LOADING', 'Now loading ...');

// Add Record
define('_MI_DICTIONARY_STATUS_RECORD_ADDED', 'A new record is added to the current dictionary.');
define('_MI_DICTIONARY_STATUS_RECORD_ADDED_P', 'A new record is added to the current parallel text.');

// Delete Record
define('_MI_DICTIONARY_STATUS_RECORD_DELETED', 'The selected record was deleted.');

// Add Language(s)
define('_MI_DICTIONARY_STATUS_LANGUAGE_ADDED', '<span style=color:red;>%s</span> was added to the dictionary.');
define('_MI_DICTIONARY_STATUS_LANGUAGE_ADDED_P', '<span style=color:red;>%s</span> was added to the parallel text.');

// Delete Language(s)
define('_MI_DICTIONARY_STATUS_LANGUAGE_DELETED', '<span style=color:red;>%s</span> was deleted from the dictionary.');
define('_MI_DICTIONARY_STATUS_LANGUAGE_DELETED_P', '<span style=color:red;>%s</span> was deleted from the parallel text.');

// PHP
// ERROR
define('_MI_DICTIONARY_ERROR_DICTIONARY_ALREADY_EXISTS', 'The name is already in use.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_ALREADY_EXISTS', 'The name is already in use.');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_LOAD', 'You have no permission to load the dictionary.');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_LOAD_P', 'You have no permission to load the parallel text.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NAME_EMPTY', 'Resource name is required.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_NAME_EMPTY', 'Resource name is required.');
define('_MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES', 'At least two languages are required in the dictionary.');
define('_MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES_P', 'At least two languages are required in the parallel text.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NAME_INVALID', 'Dictionary name is invalid');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_NAME_INVALID', 'Parallel text name is invalid');
//20090919 add
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_DEPLOY', 'You have no permission to deploy the dictionary.');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_UNDEPLOY', 'You have no permission to undeploy the dictionary.');

/**
 * JavaScript Error
 */
// Load
define('_MI_DICTIONARY_ERROR_UPLOAD_INVALID_LANGUAGE_TAG', 'The language code "%s" is invalid.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_NOT_FOUND', 'Please create or import a dictionary / parallel text first.');

//Confirm
define('_MI_DICTIONARY_CONFIRM_DELETE_LANGUAGE_ROWS', 'Are you sure to delete the selected language?');
define('_MI_DICTIONARY_CONFIRM_DELETE_SELECTED_COLUMNS', 'Are you sure to delete the selected record?');
//20090919 add
define('_MI_DICTIONARY_CONFIRM_DEPLOY_DICT', 'Are you really sure you want to deploy the dicitionary?');
define('_MI_DICTIONARY_CONFIRM_UNDEPLOY_DICT', 'Are you really sure you want to undeploy the dicitionary?');
define('_MI_DICTIONARY_CONFIRM_DEPLOY_PARALLEL', 'Are you really sure you want to deploy the parallel text?');
define('_MI_DICTIONARY_CONFIRM_UNDEPLOY_PARALLEL', 'Are you really sure you want to undeploy the parallel text?');
//20090925 add
define('_MI_DICTIONARY_ERROR_DICTIONARY_IS_NOT_DEPLOYED', 'The selected dictionary is not deployed.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_NOT_DEPLOYED', 'The selected parallel text is not deployed.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_IS_ALREADY_DEPLOYED', 'The selected dictionary is already deployed.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_ALREADY_DEPLOYED', 'The selected parallel text is already deployed.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_DEPLOY_IS_NOT_PERMITTED', 'You have no permission to deploy the selected dictionary.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_IS_NOT_PERMITTED', 'You have no permission to deploy the selected parallel text.');
define('_MI_DICTIONARY_ERROR_DICTIONARY_UNDEPLOY_IS_NOT_PERMITTED', 'You have no permission to undeploy the selected dictionary.');
define('_MI_DICTIONARY_ERROR_PARALLEL_TEXT_UNDEPLOY_IS_NOT_PERMITTED', 'You have no permission to undeploy the selected parallel text.');
//20091001 add
define('_MI_DICTIONARY_ERROR_RECORD_NO_SELECTED', 'No dictionary is selected.');
define('_MI_DICTIONARY_ERROR_RECORD_NO_SELECTED_P', 'No parallel text is selected.');
//20091021 add
define('_MI_DICTIONARY_ERROR_KEYWORD_IS_EMPTY', 'The keyword is empty.');
define('_MI_DICTIONARY_ERROR_SELECT_A_LANGUAGE', 'Please select at least one target language.');
define('_MI_DICTIONARY_ERROR_TOO_MUCH_SELECTED_LANGUAGES', 'Please select at most two target language.');
define('_MI_DICTIONARY_ERROR_SEARCH_RESULT_OVERSHOOT', 'Too many results matched (%s matched). Change search conditions to reduce the number of results.');
//20110111 add
define('_MI_DICTIONARY_ERROR_DICTIONARY_CONFLICT', 'This dictionary has been updated by other users while you are editing. Are you sure to overwrite?');

/**
 * Warning
 */
define('_MI_DICTIONARY_WARNING_NO_MORE_LANGUAGES', 'There are no more languages to be added.');
define('_MI_DICTIONARY_WARNING_DISCARD_CHANGES', 'The dictionary was not saved after the last change. Discard changes?');
define('_MI_DICTIONARY_WARNING_PARALLEL_TEXT_DISCARD_CHANGES', 'The parallel text was not saved after the last change. Discard changes?');
define('_MI_DICTIONARY_WARNING_REMOVE_DICTIONARY', 'Are you really sure you want to remove the dicitionary?');
define('_MI_DICTIONARY_WARNING_REMOVE_DICTIONARY_P', 'Are you really sure you want to remove the parallel text?');
define('_MI_DICTIONARY_WARNING_CANNOT_DELETE_LANGUAGES', 'At least two languages are required in the dictionary. ');
define('_MI_DICTIONARY_WARNING_CANNOT_DELETE_LANGUAGES_P', 'At least two languages are required in the parallel text. ');
//20090919 add
define('_MI_DICTIONARY_SEARCH_DICTIONARY_NOT_FOUND', 'No dictionary entries containing the keyword were found.');
define('_MI_DICTIONARY_SEARCH_PARALLEL_TEXT_NOT_FOUND', 'No parallel texts containing the keyword were found.');
define('_MI_DICTIONARY_DICTIONARY_REMOVE_ERROR_NO_PERMISSION', 'You have no permission to delete the dictionary.');
define('_MI_DICTIONARY_PARALLEL_TEXT_REMOVE_ERROR_NO_PERMISSION', 'You have no permission to delete the parallel text.');
define('_MI_DICTIONARY_ERROR_NO_PERMISSION_TO_DOWNLOAD_RESOURCE', 'You have no permission to download the resource.');

/**
 * Search
 */
define('_MI_DICTIONARY_SEARCH_KEYWORD', 'Keyword');
define('_MI_DICTIONARY_SEARCH_TO', 'To');
define('_MI_DICTIONARY_SEARCH_SELECT_TYPE', 'Type');
define('_MI_DICTIONARY_SEARCH_SELECT_ALL', 'Select all');
define('_MI_DICTIONARY_SEARCH_MATCHING_METHOD', 'Matching method');
define('_MI_DICTIONARY_SEARCH_PARTIAL', 'partial');
define('_MI_DICTIONARY_SEARCH_COMPLETE', 'complete');
define('_MI_DICTIONARY_SEARCH_PREFIX', 'prefix');
define('_MI_DICTIONARY_SEARCH_SUFFIX', 'suffix');
define('_MI_DICTIONARY_SEARCH_FROM', 'From');


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
define('_MI_DICTIONARY_COMMUNITY_NORMALIZE_DICTIONARY', 'Orthographic Dictionary');
define('_MI_DICTIONARY_STATUS_NOW_SAVING', 'Now saving ...');
?>

<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //
// popup for translation source selection
define('COM_UP_TO_PARENT_FOLDER', 'Up to parent folder');
define('COM_BTN_UPLOAD_FILE', 'Upload file');
define('COM_LABEL_SELECT', 'Select');
define('COM_LABEL_FILE_NAME', 'Name');
define('COM_LABEL_DESCRIPTION', 'Description');
define('COM_LABEL_PERM_READ', 'Read');
define('COM_LABEL_PERM_WRITE', 'Edit');
define('COM_LABEL_UPDATER', 'Updated by');
define('COM_LABEL_UPDATE_DATE', 'Last update');
define('COM_LINK_FILE_SHARING_TOP', 'File sharing top');
define('CT_LABEL_DOWNLOAD', 'Download');
define('_MD_TR_DTFMT_YMDHI', 'd M Y H:i');
define('_COM_DTFMT_YMDHI', 'd M Y H:i');
define('CT_LABEL_SEARCH_RESULT', '%s result(s) matched');
// Titles
define('_MD_TASK_LIST_TITLE', 'Task list');
define('_MD_TASK_NEW_TITLE', 'New task');
define('_MD_TASK_SELECT_FILE_TITLE', 'Select document');
define('_MD_TASK_EDIT_TITLE', 'Edit task');
define('_MD_TASK_HISTORY_TITLE', 'Histories of the task');
define('_MD_TASK_FORUM_DIALOG_TITLE', 'Select a forum for "%s"');
// Labels
define('_MD_TASK_NAME', 'Task name');
define('_MD_TASK_FILE_NAME', 'File name');
define('_MD_TASK_DOCUMENT_NAME', 'Document name');
define('_MD_TASK_LANG', 'Language');
define('_MD_TASK_SOURCE_LANG', 'From');
define('_MD_TASK_TARGET_LANG', 'To');
define('_MD_TASK_STATUS', 'Progress');
define('_MD_TASK_LIMIT', 'Deadline');
define('_MD_TASK_CLOSING_DATE', 'Date');
define('_MD_TASK_CLOSING_TIME', 'Time');
define('_MD_TASK_WORKER', 'Person in charge');
define('_MD_TASK_FILE', 'Document');
define('_MD_TASK_DOCUMENT', 'Document');
define('_MD_TASK_NOT_SELECTED', 'Not selected');
define('_MD_TASK_SMOOTHING', 'Smoothing');
define('_MD_TASK_SMOOTHING_LIMIT_DATE', 'Deadline date of smoothing');
define('_MD_TASK_SMOOTHING_LIMIT_TIME', 'Deadline time of smoothing');
define('_MD_TASK_SMOOTHING_WORKER', 'Person in charge of smoothing');
define('_MD_TASK_UPDATE_SUMMARY', 'Comment');
define('_MD_TASK_CHECK', 'Proofreading');
define('_MD_TASK_CHECK_LIMIT_DATETIME', 'Deadline of proofreading');
define('_MD_TASK_CHECK_LIMIT_DATE', 'Deadline date of proofreading');
define('_MD_TASK_CHECK_LIMIT_TIME', 'Deadline time of proofreading');
define('_MD_TASK_CHECK_WORKER', 'Person in charge of proofreading');
define('_MD_TASK_CREATOR', 'Creator');
define('_MD_TASK_UPDATE_DATE', 'Last update');
define('_MD_TASK_SEARCH', 'Search');
define('_MD_TASK_SELECT', 'Select');
define('_MD_TASK_CREATE', 'Create');
define('_MD_TASK_CANCEL', 'Cancel');
define('_MD_TASK_SAVE', 'Save');
define('_MD_TASK_REVERT', 'Revert');
define('_MD_TASK_LOADING', 'Now loading...');
define('_MD_TASK_DETAIL_SEARCH', 'Advanced');
define('_MD_TASK_DATE_FORMAT', 'd M Y');
define('_MD_TASK_TIME_FORMAT', 'H:i');
define('_MD_TR_DTFMT_YMDHI', 'd M Y H:i');
define('_MD_TASK_TO_TOP', 'Return to top');
define('_MD_TASK_CLICK_CALENDAR', 'Set date');
define('_MD_TASK_SELECT_FILE', 'Select document');
define('_MD_TASK_UPDATE_FILE', 'Change');
define('_MD_TASK_SELECT_LANG', 'Select language');
define('_MD_TASK_PROGRESS', '---別シンボルを利用---');
define('_MD_TASK_ACHIEVEMENT_0', '0%');
define('_MD_TASK_ACHIEVEMENT_100', '100%');
define('_MD_TASK_ALL', 'All');
define('_MD_TASK_FORUM_FOR_THIS', 'Associated forum');
define('_MD_TASK_CHANGE_FORUM', 'Change forum');
define('_MD_TASK_FILE_UNAVAILABLE', 'File not found.');
// related to communication
define('_MD_TASK_CATEGORY', 'Category');
define('_MD_TASK_FORUM_NAME', 'Forum title');
define('_MD_TASK_POSTS_COUNT', 'Messages');
define('_MD_TASK_LATEST_POST_DATE', 'Last update');
define('_MD_TASK_LATEST_POSTED_USER', 'Updated by');
define('_MD_TASK_ASSOCIATE', 'Associate and open');
define('_MD_TASK_CLEAR_SEARCH_CONDITION', 'Clear search conditions');
define('_MD_TASK_PART_SEARCH', 'Partial');
define('_MD_TASK_PREFIX_SEARCH', 'Prefix');
define('_MD_TASK_SUFFIX_SEARCH', 'Suffix');
define('_MD_TASK_EXACT_SEARCH', 'Complete');
define('_MD_TASK_TO_TOPIC', 'Open the forum for this task');
define('_MD_TASK_TO_TRANS', 'Open this document with Collaboration Translation');
define('_MD_TASK_MENU', 'Menu');
// pager
define('TR_LINK_PREV', '&lt;Prev');
define('TR_LINK_NEXT', 'Next&gt;');
// validation
define('_MD_TASK_MSG_DELETE', 'Are you sure you want to delete this task?');
define('_MD_TASK_EMPTY_PREDICATE', 'is required.');
define('_MD_TASK_INVALID_PREDICATE', 'is invalid.');
define('_MD_TASK_INVALID_DATETIME_RELATION', 'The deadline of smoothing must be earlier than the deadline of proofreading.');
define('_MD_TASK_INVALID_SMOOSING_DATETIME_PASTTIME', 'The deadline of smoothing must be after current time.');
define('_MD_TASK_INVALID_CHECK_DATETIME_PASTTIME', 'The deadline of proofreading must be after current time.');
define('_MD_TASK_MSG_SELECT_FILE', 'Select a document.');
define('_MD_TASK_MSG_SELECT_HISTORY', 'Select a document to be reverted.');
define('_MD_TASK_MSG_SELECT_FORUM', 'Select a forum to associate with this task.');
define('_MD_TASK_MSG_EMPTY_KEYWORD', 'Enter keyword');
define('_MD_TASK_SEARCH_TOTAL_ZERO', 'No tasks matched.');
define('_MD_TASK_EDIT', 'Edit');
define('_MD_TASK_HISTORY', 'History');
define('_MD_TASK_DELETE', 'Delete');
define('_MD_TASK_HOW_TO_USE_LINK', 'en.html');
define('_MD_TASK_BTN_BACK', 'Back');

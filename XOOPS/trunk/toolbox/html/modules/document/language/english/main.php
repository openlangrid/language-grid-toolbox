<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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
define('_MI_DOCUMENT_TOOL_NAME', 'Text translation');
//20090919 add
define('_MI_DOCUMENT_HOW_TO_USE_LINK', 'document_en.html');

 // button
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATE', 'Translate');
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATION_TIME', 'Translation time');
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATE_CANCEL', 'Cancel');
//20090928 add
define('_MI_DOCUMENT_TOOL_BUTTON_CLEAR', 'Clear');

 // information
define('_MI_DOCUMENT_TOOL_TRANSLATION_DOING', 'Now translating ...');
define('_MI_DOCUMENT_TOOL_BACKTRANSLATION_DOING', 'Now back translating ...');
define('_MI_DOCUMENT_TOOL_TRANSLATION_PARSE_TEXT', 'Now parsing text ...');
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATE_CANCEL_DOING', 'Now suspending translation ...');

 // label
define('_MI_DOCUMENT_TOOL_TRANSLATION_FROM', 'From');
define('_MI_DOCUMENT_TOOL_TRANSLATION_TO', 'To');
define('_MI_DOCUMENT_TOOL_BACKTRANSLATION_NAME', 'Back-translation');
//20091013 add
define('_MI_DOCUMENT_LICENSE_INFORMATION', 'License Information');
define('_MI_DOCUMENT_SERVICE_NAME', 'Service Name');
define('_MI_DOCUMENT_COPYRIGHT', 'Copyright');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University. All rights reserved.');

// messages
define('_MI_DOCUMENT_TOOL_ERROR_TIMEOUT_MESSAGE', 'Timeout due to access concentration on the Language Grid.');

// javascript message
define('_MI_DOCUMENT_JS_NO_MORE_SMALL_AREA', 'The size of text area cannot be reduced anymore.');
define('_MI_DOCUMENT_JS_NO_MORE_SMALL_FONT', 'The font size cannot be reduced anymore.');
//20091030 add
define('_MI_DOCUMENT_NO_TRANSLATION', 'Translation unavailable.');
define('_MI_DOCUMENT_JS_TRANSLATE_ERROR', 'An error has occurred at the server.');
define('_MI_DOCUMENT_JS_SERVER_ERROR', 'Unexpected response is received from the server.');
//20101005 add
define('_MI_DOCUMENT_JS_TAG_WARNING', '<skip_translation> tag should be closed in single line.');
//20101005 end
define('_MI_DOCUMENT_JS_VOICE_CREATING', 'Generating text to speech data ...');
define('_MI_DOCUMENT_JS_UNABLE_QT_PLAYER', 'QuickTime Player is unavailable. Install QuickTime Player to use text to speech.');
define('_MI_DOCUMENT_JS_UNABLE_LANGUAGE', 'Text to speech is not available for this language.');
define('_MI_DOCUMENT_JS_VOICE_CREATION_FAILED', 'Failed to generate text to speech data.');
//20101006 add
define('_MI_DOCUMENT_LITE', 'Escape Translation of<br>Quoted Phrases');
define('_MI_DOCUMENT_RICH', 'Display Original Words');

//Use is not confirmed by Toolbox
 // messages
 define('_MI_DOCUMENT_TOOL_BACKTRANSLATION_MESSAGE', 'Back translation results of highlighted sentences are shown in this area.');
 define('_MI_DOCUMENT_TOOL_ERROR_UNKNOWN_MESSAGE', 'An unknown error related to the Language Grid ToolBox has occurred.');

?>
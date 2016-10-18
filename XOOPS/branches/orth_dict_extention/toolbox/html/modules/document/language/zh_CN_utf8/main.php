<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
define('_MI_DOCUMENT_TOOL_NAME', '文本翻译');
//20090919 add
define('_MI_DOCUMENT_HOW_TO_USE_LINK', 'document_zh.html');

 // button
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATE', '翻译');
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATION_TIME', '翻译时间');
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATE_CANCEL', '取消');
//20090928 add
define('_MI_DOCUMENT_TOOL_BUTTON_CLEAR', '清除');

 // information
define('_MI_DOCUMENT_TOOL_TRANSLATION_DOING', '正在翻译……');
define('_MI_DOCUMENT_TOOL_BACKTRANSLATION_DOING', '正在反向翻译……');
define('_MI_DOCUMENT_TOOL_TRANSLATION_PARSE_TEXT', '正在解析文本……');
define('_MI_DOCUMENT_TOOL_BUTTON_TRANSLATE_CANCEL_DOING', '翻译暂停……');

 // label
define('_MI_DOCUMENT_TOOL_TRANSLATION_FROM', '自');
define('_MI_DOCUMENT_TOOL_TRANSLATION_TO', '至');
define('_MI_DOCUMENT_TOOL_BACKTRANSLATION_NAME', '反向翻译');
//20091013 add
define('_MI_DOCUMENT_LICENSE_INFORMATION', '许可信息');
define('_MI_DOCUMENT_SERVICE_NAME', '服务名称');
define('_MI_DOCUMENT_COPYRIGHT', '版权信息');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University. All rights reserved.');

// messages
define('_MI_DOCUMENT_TOOL_ERROR_TIMEOUT_MESSAGE', '因Language Grid访问量过于集中而导致超时。');

// javascript message
define('_MI_DOCUMENT_JS_NO_MORE_SMALL_AREA', '文本区域无法再缩小。');
define('_MI_DOCUMENT_JS_NO_MORE_SMALL_FONT', '字号无法再缩小。');
//20091030 add
define('_MI_DOCUMENT_NO_TRANSLATION', '翻译不可用。');
define('_MI_DOCUMENT_JS_TRANSLATE_ERROR', 'Language Grid 发生错误。');
define('_MI_DOCUMENT_JS_SERVER_ERROR', '收到服务器意外响应。');
//20101005 add
define('_MI_DOCUMENT_JS_TAG_WARNING', '<skip_translation> tag should be closed in single line.');
//20101005 end


//Use is not confirmed by Toolbox
 // messages
 define('_MI_DOCUMENT_TOOL_BACKTRANSLATION_MESSAGE', 'Back translation results of highlighted sentences are shown in this area.');
 define('_MI_DOCUMENT_TOOL_ERROR_UNKNOWN_MESSAGE', 'An unknown error related to the Language Grid ToolBox has occurred.');

?>
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
define('_MI_WEBTRANS_TOOL_NAME', '新建网页');
define('_MI_WEBTRANS_HOW_TO_USE_LINK', 'webtrans_zh.html');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright (C) 2009-2010 Language Grid Project, NICT. All rights reserved.');

// label
define('_MI_WEBTRANS_LABEL_TRANSLATE', '翻译');
define('_MI_WEBTRANS_LABEL_WEBPAGEURL', '网页URL');
define('_MI_WEBTRANS_LABEL_APPLY_TEMPLATE', '应用模板');
define('_MI_WEBTRANS_LABEL_ORIGINAL_WEBPAGE', '原始网页');
define('_MI_WEBTRANS_LABEL_TRANSLATED_WEBPAGE', '翻译后网页');
define('_MI_WEBTRANS_LABEL_BACK_TRANSLATED_WEBPAGE', '反向翻译后网页');
define('_MI_WEBTRANS_LABEL_LICENSE_INFORMATION', '许可信息');
define('_MI_WEBTRANS_LABEL_CREATEANDEDITTEMPLATE', '新建 &amp;编辑模板');
define('_MI_WEBTRANS_LABEL_CREATE_PAIR', '新建匹配项目');
define('_MI_WEBTRANS_LABEL_LIST_ALL_PAIR', '匹配项目列表');

// button
define('_MI_WEBTRANS_TOOL_BUTTON_IMPORT_WEBPAGE', '导入网页');
define('_MI_WEBTRANS_TOOL_BUTTON_DISPLAY_WEBPAGE', '显示');
define('_MI_WEBTRANS_TOOL_BUTTON_LOAD_TEMPLATE', '载入模板');
define('_MI_WEBTRANS_TOOL_BUTTON_UPLOAD_TEMPLATE', '上传模板');
define('_MI_WEBTRANS_TOOL_BUTTON_TRANSLATE', '翻译');
define('_MI_WEBTRANS_TOOL_BUTTON_CANCEL', '取消');
define('_MI_WEBTRANS_TOOL_BUTTON_UPLOAD', '上传');
define('_MI_WEBTRANS_TOOL_BUTTON_DOWNLOAD', '下载');
define('_MI_WEBTRANS_TOOL_BUTTON_UNDO', '撤销');
define('_MI_WEBTRANS_TOOL_BUTTON_DISPLAY', '显示');
define('_MI_WEBTRANS_TOOL_BUTTON_ADD_TEMPLATE', '添加至模板');
define('_MI_WEBTRANS_TOOL_BUTTON_SAVE_TEMPLATE', '保存模板');
define('_MI_WEBTRANS_TOOL_BUTTON_DOWNLOAD_TEMPLATE', '下载模板');

// information
define('_MI_WEBTRANS_TOOL_TRANSLATION_DOING', '正在翻译……');

// messages
define('_MI_WEBTRANS_MSG_URL_INVALID', '无效URL');
define('_MI_WEBTRANS_TOOL_BACKTRANSLATION_MESSAGE', '此处显示反向翻译后网页 HTML 源代码。无法编辑该区域。');
define('_MI_WEBTRANS_POPUP_UPLOAD_HTML', '从您的电脑上传HTML文件。');
define('_MI_WEBTRANS_POPUP_DOWNLOAD_HTML', '将HTML文件下载到您的电脑。');
define('_MI_WEBTRANS_POPUP_LOAD_TEMPLATE', '从服务器载入模板文件。');
define('_MI_WEBTRANS_POPUP_UPLOAD_TEMPLATE', '从您的电脑上传模板文件。');
define('_MI_WEBTRANS_POPUP_SAVE_TEMPLATE', '将模板文件保存到服务器。');
define('_MI_WEBTRANS_POPUP_SAVE_MESSAGE', '模板名称仅可包含（半角）英文字母、数字、下划线“_”和连字符“-”。');
define('_MI_WEBTRANS_POPUP_DOWNLOAD_TEMPLATE', '将模板文件下载到您的电脑。');
define('_MI_WEBTRANS_POPUP_FILE_NAME', '文件名称');
define('_MI_WEBTRANS_POPUP_TEMPLATE_NAME', '模板名称');
define('_MI_WEBTRANS_POPUP_LOAD_ANOTHER_TEMPLATE', '载入其他模板');
define('_MI_WEBTRANS_POPUP_UPLOAD_ANOTHER_TEMPLATE', '上传其他模板');

// javascript message
define('_MI_WEBTRANS_JS_TRANSLATE_ERROR', 'Language Grid 发生错误。');
define('_MI_WEBTRANS_JS_SERVER_ERROR', '收到服务器意外响应。');
define('_MI_WEBTRANS_JS_NO_MORE_SMALL_AREA', '文本区域无法再缩小。');
define('_MI_WEBTRANS_JS_ORIGINAL_INIT_MESSAGE', '此处显示原始网页 HTML 源代码。可编辑该区域。');
define('_MI_WEBTRANS_JS_TRANSLATED_INIT_MESSAGE', '此处显示翻译后网页 HTML 源代码。可编辑该区域。');
define('_MI_WEBTRANS_JS_TEMPLATE_INIT_MESSAGE', '在此处粘贴 HTML 源代码。');
define('_MI_WEBTRANS_SERVICE_NAME', '服务名称');
define('_MI_WEBTRANS_COPYRIGHT', '版权信息');
define('_MI_WEBTRANS_TOOL_BUTTON_DELETE_PAIR', '删除匹配项目');
define('_MI_WEBTRANS_JS_FIELD_ADD_ERROR', '无法再添加域。');
define('_MI_WEBTRANS_JS_FILE_NAME_ERROR', '文件名称无效。');
define('_MI_WEBTRANS_JS_TEMPLATE_NAME_ERROR', '模板名称无效。');
define('_MI_WEBTRANS_JS_NO_TEMPLATE_MSG', '{0}未找到。');
define('_MI_WEBTRANS_JS_TEMPLATE_OVER_MAX', '{0}模板已载入。');
define('_MI_WEBTRANS_JS_NO_PAIR_MSG', '匹配项目不存在。');
define('_MI_WEBTRANS_JS_CONFIRM_OVERWRITE', '“{0}”已存在。\n是否覆盖？');
define('_MI_WEBTRANS_JS_SAVE_COMPLATE', '保存完毕。');
define('_MI_WEBTRANS_JS_TEMPLATE_EMPTY', '模板为空！');
define('_MI_WEBTRANS_POPUP_TITLE_LOAD_TEMPLATE', '载入模板');
define('_MI_WEBTRANS_POPUP_TITLE_UPLOAD_TEMPLATE', '上传模板');
define('_MI_WEBTRANS_POPUP_TITLE_UPLOAD_HTML', '上传 HTML 文件');
define('_MI_WEBTRANS_POPUP_TITLE_DOWNLOAD_HTML', '下载 HTML 文件');
define('_MI_WEBTRANS_POPUP_TITLE_SAVE_TEMPLATE', '保存模板');
define('_MI_WEBTRANS_POPUP_TITLE_DOWNLOAD_TEMPLATE', '下载模板');
define('_MI_WEBTRANS_JS_ABORT_TRANSLATION', '翻译终止……');
define('_MI_WEBTRANS_JS_TRANSLATION_INITIALIZEING', '[……正在初始化……]');
define('_MI_WEBTRANS_JS_APPLYING_TEMPLATE', '[……正在应用模板……]');
define('_MI_WEBTRANS_JS_ANALYSIS_HTML', '[……正在解析 HTML 源代码……]');
define('_MI_WEBTRANS_JS_LICENSE_AREA_MSG', '使用语言资源时，在此处显示许可信息。');
//20100302 add
define('_MI_WEBTRANS_JS_SESSIONTIMEOUT', 'session time out.');
define('_MI_WEBTRANS_JS_DISPLAY_SAVEERROR', 'failed to save the content to be displayed.');

?>
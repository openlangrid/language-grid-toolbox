<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox.
//
// Copyright (C) 2010  NICT Language Grid Project
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
/* $Id: global.php 3727 2010-07-13 05:30:48Z yoshimura $ */
error_reporting(0);
require_once('../../../mainfile.php');
?>
var Global = {
	Label : {
		TextResource : {
			MID : '<?php echo _MI_UIC_TR_MID ?>',
			MODULE_NAME : '<?php echo _MI_UIC_TR_MODULE_NAME ?>',
			FILE_NAME : '<?php echo _MI_UIC_TR_FILE_NAME ?>',
			PERSON : '<?php echo _MI_UIC_TR_PERSON ?>',
			LAST_UPDATE : '<?php echo _MI_UIC_TR_LAST_UPDATE ?>',
			FILE_SELECT : '<?php echo _MI_UIC_TR_CONTEXT_FILE_SELECT ?>',
			FILE_DELETE : '<?php echo _MI_UIC_TR_CONTEXT_FILE_DELETE ?>',
			FILE_DOWNLOAD : '<?php echo _MI_UIC_TR_CONTEXT_FILE_DOWNLOAD ?>',
			CREATE_TEMPLATE : '<?php echo _MI_UIC_TR_CONTEXT_CREATE_TEMPLATE ?>'
		},
		EditLang : {
			POPUP_TITLE : '<?php echo _MI_UIC_TR_EDITLANG_TITLE ?>',
			POPUP_DESCRIPTION : '<?php echo _MI_UIC_TR_EDITLANG_DESC ?>',
			OK_BUTTON : '<?php echo _MI_UIC_COMMON_OK ?>',
			CANCEL_BUTTON : '<?php echo _MI_UIC_COMMON_CANCEL ?>',
			NOW_EDITING : '<?php echo _MI_UIC_COMMON_NOW_EDITTING ?>'
		}
	},
	Text : {
		A : '<?php ?>',
		NOW_LOADING : '<?php echo _MI_UIC_NOW_LOADING ?>',
		NOW_MODIFY : '<?php echo _MI_UIC_NOW_MODIFY ?>',
		PAGE_RESULTS : '<?php echo _MI_UIC_PAGE_RESULTS ?>',
		ITEMS : '<?php echo _MI_UIC_ITEMS ?>',
		ALL : '<?php echo _MI_UIC_ALL ?>',
		PREVIEW : '<?php echo _MI_UIC_PREVIEW ?>',
		NEXT : '<?php echo _MI_UIC_NEXT ?>',
		DEFAULT : '<?php echo _MI_UIC_DEFAULT ?>',
		NODATA : '<?php echo _MI_UIC_NODATA ?>'
	},
	Image : {
		BLANK : '-',
		CHECK : '<img src="./image/icon/icon_check.png" />',
		LOADING : '<img style="vertical-align: middle;" src="./image/etc/loading.gif" />'
	},
	Url : {
		TEXT_RESOURCE_LANGUAGE_LOAD: './?action=text-resource-load-language',
		TEXT_RESOURCE_MODULES_LOAD: './?action=text-resource-load-modules',
		TEXT_RESOURCE_DOWNLOAD: '../filesharing/?page=file_dl&lid=#{lid}',
		TEXT_RESOURCE_TEMPLATE_URL: './?action=text-resource-template-download&mid=#{mid}',
		TEXT_RESOURCE_MODIFY: './?action=text-resource-modify-resource',
		TEXT_RESOURCR_LANGUAGE_SAVE: './?action=text-resource-save-language',
		CREATE_RESOURCE : './?page=create-resource',
		EDIT_RESOURCE : './?page=edit-resource',
		DELETE_RESOURCE : './?page=delete-resource',
		EXPORT_RESOURCE : './?page=export-resource',
		IMPORT_RESOURCE : './?page=import-resource',
		LOAD_CATEGORIES : './?page=load-categories',
		LOAD_RESOURCES : './?page=load-resources',
		READ_RESOURCE : './?page=read-resource',
		SAVE_MASTER_CATEGORY : './?page=save-master-category',
		DELETE_MASTER_CATEGORY : './?page=delete-master-category',
		SAVE_RECORD : './?page=save-record',
		DELETE_RECORD : './?page=delete-record',
		SEARCH : './?page=search',
		FILE_DIALOG : '<?php echo XOOPS_URL . '/modules/collabtrans/file/?action=_list&parentId=#{parentId}' ?>'
	},
	Language : {
		<?php
		require XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';
		$language = '';
		foreach ($LANGRID_LANGUAGE_ARRAY as $key => $value) {
			$language .= '\''.$key.'\' : \''.$value.'\',';
		}
		echo substr($language, 0, -1);
		?>
	},
	WideLanguages : ['nb', 'be', 'zh-CN', 'zh-TW', 'en-GB', 'en-US', 'ga', 'nn', 'pt-BR', 'pt-PT', 'rm', 'gd'],
	ClassName : {
		CLICKABLE_TEXT : 'common-clickable',
		DISABLE_TEXT : 'common-disable',
		OPENABLE : 'common-openable',
		CLOSABLE : 'common-closable'
	},
	Templates : {
		DIV : '<div id="#{id}" class="#{className}">#{contents}</div>',
		SPAN : '<span id="#{id}" class="#{className}">#{contents}</span>',
		INPUT : '<label><input id="#{id}" type="#{type}" class="#{className}"'
			+ ' value="#{value}" name="#{name}" #{attribute} /> #{contents}</label>',
		BUTTON : '<button id="#{id}" class="#{className}">#{name}</button>',
		LIST_BOX : {
			HEADER : '<select id="#{id}" class="#{className}">',
			BODY : '<option value="#{value}" #{attribute}>#{name}</option>',
			FOOTER : '</select>'
		}
	},
	location : null,
	displayLanguage : '<?php echo preg_replace('/[^a-zA-Z-]+/u', '', (isset($_COOKIE['ml_lang']) ? $_COOKIE['ml_lang'] : 'en')); ?>',

	version: '1.0.0'
};
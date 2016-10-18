//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// glossaries.
// Copyright (C) 2010  CITY OF KYOTO
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
/**
 * @author kitajima
 */
var QaImportQaPopupPanel = Class.create();
Object.extend(QaImportQaPopupPanel.prototype, LightPopupPanel.prototype);
Object.extend(QaImportQaPopupPanel.prototype, {

	errorMessage : null,
	WIDTH : '480',
	
	initialize : function() {
		LightPopupPanel.prototype.initialize.apply(this, arguments);
		document.observe('import:failure', this.importFailured.bindAsEventListener(this));
	},

	initEventListeners : function() {
		LightPopupPanel.prototype.initEventListeners.apply(this, arguments);
		this.addEvent('editPermissionChanged', this.Config.Id.EDIT_PERMISSION, 'change', this.Event.editPermissionChanged.bindAsEventListener(this));
		this.addEvent('okClicked', this.Config.Id.OK, 'click', this.Event.okClicked.bindAsEventListener(this));
		this.addEvent('cancelClicked', this.Config.Id.CANCEL, 'click', this.Event.cancelClicked.bindAsEventListener(this));
		this.addEvent('formSubmitted', this.Config.Id.FORM, 'submit', this.Event.formSubmitted.bindAsEventListener(this));
		this.addEvent('importSuccess', document, 'import:success', this.Event.importSuccess.bindAsEventListener(this));
	},

	createEditPermissionSelectorHtml : function() {
		return new Template(this.Templates.permissionSelector.all + this.Templates.permissionSelector.user).evaluate({
			all : Global.Text.FOR_ALL_USERS,
			user : Global.Text.FOR_THE_CURRENT_USER_ONLY
		});
	},

	createReadPermissionSelectorHtml : function() {
		var template = this.Templates.permissionSelector.all;
		if (!(!$(this.Config.Id.EDIT_PERMISSION) || $F(this.Config.Id.EDIT_PERMISSION) == 'all')) {
			template += this.Templates.permissionSelector.user;
		}
		return new Template(template).evaluate({
			all : Global.Text.FOR_ALL_USERS,
			user : Global.Text.FOR_THE_CURRENT_USER_ONLY
		});
	},
	
	getBody : function() {
		return 	new Template(this.Templates.base).evaluate({
			formId : this.Config.Id.FORM,
			formAction : Global.Url.IMPORT_RESOURCE,
			title : Global.Text.IMPORT_LANGUAGE_RESOURCE,
			file : Global.Text.FILE,
			inputFileId : this.Config.Id.INPUT_FILE,
			type : Global.Text.TYPE,
			qa : Global.Text.QA,
			resourceName : Global.Text.RESOURCE_NAME,
			resourceNameInputId : this.Config.Id.RESOURCE_NAME_INPUT,
			resourceNamePattern : Global.Text.RESOURCE_NAME_PATTERN,
			editPermission : Global.Text.EDIT_PERMISSION,
			editPermissionSelectorId : this.Config.Id.EDIT_PERMISSION,
			editPermissionSelector : this.createEditPermissionSelectorHtml(),
			readPermission : Global.Text.READ_PERMISSION,
			readPermissionSelectorId : this.Config.Id.READ_PERMISSION,
			readPermissionSelector : this.createReadPermissionSelectorHtml(),
			statusAreaId : this.Config.Id.STATUS_AREA,
			okId : this.Config.Id.OK,
			ok : Global.Text.OK,
			cancelId : this.Config.Id.CANCEL,
			cancel : Global.Text.CANCEL
		});
	},
	
	getCheckedLanguages : function() {
		var languages = [];
		$$('.' + this.Config.ClassName.LANGUAGE_CHECK_BOX).each(function(element){
			if (element.checked) {
				languages.push(element.value);
			}
		}.bind(this));
		return languages;
	},
	
	getParameters : function() {
		var parameters = {
			name : $F(this.Config.Id.RESOURCE_NAME_INPUT),
			readPermission : $F(this.Config.Id.READ_PERMISSION),
			editPermission : $F(this.Config.Id.EDIT_PERMISSION)
		};
		this.getCheckedLanguages().each(function(language, i){
			parameters['languages[' + (i) + ']'] = language;
		}.bind(this));
		return parameters;
	},
	
	submit : function() {
		this.setOkCancelButtonDisabled();
		this.setStatus(Global.Image.LOADING + ' ' + Global.Text.NOW_IMPORTING);
		$(this.Config.Id.FORM).submit();
	},

	valid : function() {
		var parameters = this.getParameters();
		var validator = new QaResourceValidator();
		this.errorMessage = null;
		if (!$F(this.Config.Id.INPUT_FILE)) {
			this.errorMessage = Global.Text.ERROR_SELECT_A_FILE;
			return false;
		}
		if (parameters.name == '') {
			this.errorMessage = Global.Text.ERROR_INPUT_RESOURCE_NAME;
			return false;
		}
		if (!validator.isNameValid(parameters.name)) {
			this.errorMessage = Global.Text.INVALID_RESOURCE_NAME;
			return false;
		}
		return true;
	},
	
	onShowPane : function() {
		
	},
	
	onHidePane : function() {
		
	},
	
	setOkCancelButtonAbled : function() {
		$(this.Config.Id.OK).removeClassName(this.Config.ClassName.BUTTON_DISABLED);
		$(this.Config.Id.CANCEL).removeClassName(this.Config.ClassName.BUTTON_DISABLED);
	},
	
	setOkCancelButtonDisabled : function() {
		$(this.Config.Id.OK).addClassName(this.Config.ClassName.BUTTON_DISABLED);
		$(this.Config.Id.CANCEL).addClassName(this.Config.ClassName.BUTTON_DISABLED);
	},
	setStatus : function(message) {
		$(this.Config.Id.STATUS_AREA).update(message);
	},
	
	importFailured : function(event) {
		this.setOkCancelButtonAbled();
		this.setStatus('<span style="color: red;">' + event.memo.message + '</span>');
	}
});
QaImportQaPopupPanel.prototype.Config = {
	Id : {
		FORM : 'qa-popup-import-form',
		INPUT_FILE : 'qa-popup-import-input-file',
		RESOURCE_NAME_INPUT : 'qa-popup-import-resource-name-input',
		EDIT_PERMISSION : 'qa-popup-import-edit-permission',
		READ_PERMISSION : 'qa-popup-import-read-permission',
		STATUS_AREA : 'qa-popup-common-status-area',
		OK : 'qa-popup-import-submit',
		CANCEL : 'qa-popup-import-cancel'
	},
	ClassName : {
		BUTTON_DISABLED : 'qa-common-popup-gray-button-disabled',
		LANGUAGE_CHECK_BOX : 'qa-popup-import-langauge-checkbox'
	}
};
QaImportQaPopupPanel.prototype.Event = {
	editPermissionChanged : function(event) {
		$(this.Config.Id.READ_PERMISSION).update(this.createReadPermissionSelectorHtml());
	},

	importSuccess : function(event) {
		this.hide();
	},

	formSubmitted : function(event) {
		(this.Event.okClicked.bind(this, event))();
	},
	okClicked : function(event) {
		if ($(this.Config.Id.OK).hasClassName(this.Config.ClassName.BUTTON_DISABLED)) {
			return;
		}
		if (!this.valid()) {
			this.setStatus('<span style="color: red;">' + this.errorMessage + '</span>');
			return;
		}
		this.submit();
	},
	cancelClicked : function(event) {
		if ($(this.Config.Id.CANCEL).hasClassName(this.Config.ClassName.BUTTON_DISABLED)) {
			return;
		}
		this.hide();
	}
};
QaImportQaPopupPanel.prototype.Templates = {
	base : '<div class="qa-common-popup-wrapper">'
		 + '<div class="qa-common-popup-title">#{title}</div>'
//		 + '<form id="#{formId}" enctype="multipart/form-data" action="#{formAction}" method="post">'
		 + '<form target="import-target" id="#{formId}" enctype="multipart/form-data" action="#{formAction}" method="post">'
		 + '<table class="qa-common-popup-table">'
		 + '<tr><td class="title-row"><div class="row-title">#{file}</div></td><td><input name="file" size="44" class="qa-common-popup-input" type="file" id="#{inputFileId}" /></td></tr>'
		 + '<tr><td class="title-row"><div class="row-title">#{type}</div></td><td>#{qa}</td></tr>'
		 + '<tr><td><div class="row-title">#{resourceName}</div></td><td><input name="name" class="qa-common-popup-input" id="#{resourceNameInputId}" type="text" value="" /><p class="qa-common-popup-resource-name-pattern">#{resourceNamePattern}</p></td></tr>'
		 + '<tr>'
		 + '<td><div class="row-title">#{editPermission}</div></td>'
		 + '<td><select name="edit" class="qa-common-popup-select" id="#{editPermissionSelectorId}">#{editPermissionSelector}</select></td>'
		 + '</tr>'
		 + '<tr>'
		 + '<td><div class="row-title">#{readPermission}</div></td>'
		 + '<td><select name="read" class="qa-common-popup-select" id="#{readPermissionSelectorId}">#{readPermissionSelector}</select></td>'
		 + '</tr>'
		 + '</table>'
		 + '</form>'
		 + '<iframe id="import-target" name="import-target" style="display: none;"></iframe>'
		 + '<div id="#{statusAreaId}"></div>'
		 + '<div>'
		 + '<button id="#{okId}" class="qa-common-popup-gray-button">#{ok}</button>'
		 + '<button id="#{cancelId}" class="qa-common-popup-gray-button">#{cancel}</button>'
		 + '</div>'
		 + '</div>',
	languageSelector : '<span><label>'
		+ '<input class="#{className}" value="#{language}" type="checkbox">'
		+ ' #{languageName}</label></span>',
	permissionSelector : {
		all : '<option value="all">#{all}</option>',
		user : '<option value="user">#{user}</option>'
	}
};
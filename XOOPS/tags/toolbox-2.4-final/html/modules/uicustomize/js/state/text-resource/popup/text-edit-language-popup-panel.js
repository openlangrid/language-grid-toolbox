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
/* $Id: text-edit-language-popup-panel.js 3662 2010-06-16 02:22:17Z yoshimura $ */

var TextEditLanguagePopupPanel = Class.create();
Object.extend(TextEditLanguagePopupPanel.prototype, LightPopupPanel.prototype);
Object.extend(TextEditLanguagePopupPanel.prototype, {

	errorMessage : null,
	WIDTH : '500',

	initialize : function() {
		LightPopupPanel.prototype.initialize.apply(this, arguments);
	},

	initEventListeners : function() {
		LightPopupPanel.prototype.initEventListeners.apply(this, arguments);
		this.addEvent('okClicked', this.Config.Id.OK, 'click', this.Event.okClicked.bindAsEventListener(this));
		this.addEvent('cancelClicked', this.Config.Id.CANCEL, 'click', this.Event.cancelClicked.bindAsEventListener(this));
	},

	createLanguageSelectorHtml : function() {
		var html = [];
		var column2 = '';
		for (var key in Global.Language) {
			if (Global.WideLanguages.indexOf(key) != -1) {
				column2 = 'qa-common-column2';
			} else {
				column2 = '';
			}
			html.push(new Template(this.Templates.languageSelector).evaluate({
				column2 : column2,
				className : this.Config.ClassName.LANGUAGE_CHECK_BOX,
				language : key,
				languageName : Global.Language[key],
				checked : Global.supportedLanguages.include(key) ? 'checked' : ''
			}));
		}
		return html.join('');
	},

	getBody : function() {
		return 	new Template(this.Templates.base).evaluate({
			title : Global.Label.EditLang.POPUP_TITLE,
			description : Global.Label.EditLang.POPUP_DESCRIPTION,
			languageSelector : this.createLanguageSelectorHtml(),
			resourceName : Global.Text.RESOURCE_NAME,
			resourceNameInputId : this.Config.Id.RESOURCE_NAME_INPUT,
			resourceNamePattern : Global.Text.RESOURCE_NAME_PATTERN,
			statusAreaId : this.Config.Id.STATUS_AREA,
			okId : this.Config.Id.OK,
			ok : Global.Label.EditLang.OK_BUTTON,
			cancelId : this.Config.Id.CANCEL,
			cancel : Global.Label.EditLang.CANCEL_BUTTON
		});
	},

	create : function() {
		this.setOkCancelButtonDisabled();
		this.setStatus(Global.Image.LOADING + ' ' + Global.Label.EditLang.NOW_EDITING);
		var parameters = this.getParameters();
		new Ajax.Request(Global.Url.TEXT_RESOURCR_LANGUAGE_SAVE, {
			postBody : Object.toQueryString(parameters),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				if (response.status == 'ERROR') {
					this.setOkCancelButtonAbled();
					this.setStatus('<span style="color: red;">' + response.message + '</span>');
					return;
				}
//				Global.location = parameters.name;
				this.hide();
//				document.fire('state:edit');
//				document.write('document.location.reload(true);');
				document.location.reload(true);
			}.bind(this),
			onException : function() {
			},
			onFailure : function() {
			},
			onComplete : function() {
			}.bind(this)
		});
	},

	valid : function() {
		return true;
	},

	onShowPanel : function() {

	},

	onHidePanel : function() {

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
		var parameters = {};
		this.getCheckedLanguages().each(function(language, i){
			parameters['languages[' + (i) + ']'] = language;
		}.bind(this));
		return parameters;
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
	}
});
TextEditLanguagePopupPanel.prototype.Config = {
	Id : {
		RESOURCE_NAME_INPUT : 'qa-resource-name-input',
		EDIT_PERMISSION : 'qa-resource-edit-permission',
		READ_PERMISSION : 'qa-resource-read-permission',
		STATUS_AREA : 'qa-popup-common-status-area',
		OK : 'qa-resource-submit',
		CANCEL : 'qa-resource-cancel'
	},
	ClassName : {
		BUTTON_DISABLED : 'qa-common-popup-gray-button-disabled',
		LANGUAGE_CHECK_BOX : 'qa-resource-langauge-checkbox'
	}
};
TextEditLanguagePopupPanel.prototype.Event = {
	okClicked : function(event) {
		if ($(this.Config.Id.OK).hasClassName(this.Config.ClassName.BUTTON_DISABLED)) {
			return;
		}
		if (!this.valid()) {
			this.setStatus('<span style="color: red;">' + this.errorMessage + '</span>');
			return;
		}
		this.create();
	},
	cancelClicked : function(event) {
		if ($(this.Config.Id.CANCEL).hasClassName(this.Config.ClassName.BUTTON_DISABLED)) {
			return;
		}
		this.hide();
	}
};
TextEditLanguagePopupPanel.prototype.Templates = {
	base : '<div class="qa-common-popup-wrapper">'
		 + '<div class="qa-common-popup-title">#{title}</div>'
		 + '<table class="qa-common-popup-table">'
		 + '<tr><td colspan="2" class="title-row"><div class="row-title">#{description}</div></td></tr>'
		 + '<tr><td colspan="2"><div class="qa-common-popup-language-selection-wrapper">#{languageSelector}</div></td></tr>'
		 + '</table>'
		 + '<div id="#{statusAreaId}"></div>'
		 + '<div>'
		 + '<button id="#{okId}" class="qa-common-popup-gray-button">#{ok}</button>'
		 + '<button id="#{cancelId}" class="qa-common-popup-gray-button">#{cancel}</button>'
		 + '</div>'
		 + '</div>',
	languageSelector : '<span class="#{column2}"><label>'
		+ '<input class="#{className}" value="#{language}" type="checkbox" #{checked}>'
		+ ' #{languageName}</label></span>',
	permissionSelector : {
		all : '<option value="all">#{all}</option>',
		user : '<option value="user">#{user}</option>'
	}
};
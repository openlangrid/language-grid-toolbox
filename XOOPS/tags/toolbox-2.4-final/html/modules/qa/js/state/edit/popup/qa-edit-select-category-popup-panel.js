//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// Q&As.
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
var QaEditSelectCategoryPopupPanel = Class.create();
Object.extend(QaEditSelectCategoryPopupPanel.prototype, LightPopupPanel.prototype);
Object.extend(QaEditSelectCategoryPopupPanel.prototype, {
	
	WIDTH : '400',

	stateManager : null,
	checkedIds : null,
	resource : null,
	record : null,
	recordPanel : null,
	language : null,

	initialize : function() {
		LightPopupPanel.prototype.initialize.apply(this, arguments);
	},

	initEventListeners : function() {
		LightPopupPanel.prototype.initEventListeners.apply(this, arguments);
		this.addEvent('languageChanged', this.getLanguagesId(), 'change', this.Event.sourceLanguageChanged.bindAsEventListener(this));
		this.addEvent('masterClicked', this.getMasterId(), 'click', this.Event.categoryMasterClicked.bindAsEventListener(this));
		this.addEvent('closeButtonClicked', this.getCloseId(), 'click', this.Event.closeButtonClicked.bindAsEventListener(this));
		this.addEvent('saveButtonClickEvent', this.getSaveButtonId(), 'click', this.Event.saveButtonClicked.bindAsEventListener(this));
		this.addEvent('cancelButtonClickEvent', this.getCancelButtonId(), 'click', this.Event.cancelButtonClicked.bindAsEventListener(this));
	},

	createCategoryCheckBoxHtml : function() {
		var html = [];
		var checked = '';
		this.resource.categoryIds.each(function(categoryId){
			if (this.checkedIds.indexOf(categoryId) != -1) {
				checked = 'checked="checked"';
			} else {
				checked = '';
			}

			html.push(new Template(this.Templates.categoryCheckBox).evaluate({
				checkClassName : this.Config.ClassName.CHECK,
				attribute : checked,
				value : categoryId,
				contents : Global.Categories.getName(categoryId, this.language)
			}));
		}.bind(this));
		return html.join('');
	},
	
	createLanguageSelectorHtml : function() {
		var html = [];
		var selected = '';
		LanguageUtils.sort(this.resource.languages);
		this.resource.languages.each(function(language){
			if (this.language == language) {
				selected = 'selected="selected"';
			} else {
				selected = '';
			}
			html.push('<option '+selected+' value="' + language + '">' + Global.Language[language] + '</option>');
		}.bind(this));
		return html.join('');
	},

	getBody : function() {
		return new Template(this.Templates.base).evaluate({
			closeId : this.getCloseId(),
			statusId : this.Config.Id.STATUS,
			languagesId : this.Config.Id.LANGUAGES,
			languages : this.createLanguageSelectorHtml(),
			questionId : this.Config.Id.QUESTION,
			question : this.record.expressions[this.language],
			editMasterCategory : Global.Text.EDIT_THE_CATEGORIES,
			editMasterCategoryId : this.getMasterId(),
			categoriesArea : this.getCategoriesAreaId(),
			categories : this.createCategoryCheckBoxHtml(),
			saveId : this.getSaveButtonId(),
			save : Global.Text.SAVE,
			cancelId : this.getCancelButtonId(),
			cancel : Global.Text.CANCEL
		});
	},
	
	isSaveCancelButtonDisabled : function() {
		return $(this.getSaveButtonId()).hasClassName(this.Config.ClassName.SAVE_DISABLED)
			|| $(this.getCancelButtonId()).hasClassName(this.Config.ClassName.CANCEL_DISABLED);
	},

	setSaveCancelButtonAbled : function() {
		$(this.getSaveButtonId()).removeClassName(this.Config.ClassName.SAVE_DISABLED)
		$(this.getCancelButtonId()).removeClassName(this.Config.ClassName.CANCEL_DISABLED);
		$(this.getCloseId()).removeClassName(this.Config.ClassName.CLOSE_DISABLED);
	},
	
	setSaveCancelButtonDisabled : function() {
		$(this.getSaveButtonId()).addClassName(this.Config.ClassName.SAVE_DISABLED)
		$(this.getCancelButtonId()).addClassName(this.Config.ClassName.CANCEL_DISABLED);
		$(this.getCloseId()).addClassName(this.Config.ClassName.CLOSE_DISABLED);
	},

	submit : function() {
		var changeFlag = false;
		var newCategoryIds = this.getCheckedCategoryIds();
		if (newCategoryIds.length != this.stateManager.record.categoryIds.length) {
			changeFlag = true;
		}
		this.stateManager.record.categoryIds.each(function(categoryId){
			newCategoryIds.without(categoryId);
		}.bind(this));
		if (newCategoryIds.length != 0) {
			changeFlag = true;
		}
		if (changeFlag) {
			this.stateManager.setChangedParameter('Category', null, 'Change');
		}
		this.stateManager.currentRecord.categoryIds = this.getCheckedCategoryIds();
		this.hide();
//		this.setSaveCancelButtonDisabled();
//		this.setStatus(Global.Image.LOADING + ' ');
//		this.setStatus(Global.Image.LOADING + ' ' + Global.Text.NOW_SAVING);
//		new Ajax.Request(Global.Url.SAVE_RECORD_CATEGORY, {
//			postBody : Object.toQueryString(this.getParameters()),
//			onSuccess : function(transport) {
//				var response = transport.responseText.evalJSON();
//				this.record.categoryIds = this.getCheckedCategoryIds();
//				this.recordPanel.draw();
//				this.hide();
//			}.bind(this)
//		});
	},
	getParameters : function() {
		return {
			recordId : this.record.questionId,
			'categoryIds[]' : this.getCheckedCategoryIds()
		};
	},
	
	setStatus : function(message) {
		$(this.Config.Id.STATUS).update(message);
	},

	getMasterId : function() {
		return this.Config.Id.MASTER;
	},
	
	getCloseId : function() {
		return this.Config.Id.CLOSE;
	},

	getLanguagesId : function() {
		return this.Config.Id.LANGUAGES;
	},
	
	getCategoriesAreaId : function() {
		return this.Config.Id.CATEGORIES_AREA;
	},
	
	getSaveButtonId : function() {
		return this.Config.Id.OK;
	},
	
	getCancelButtonId : function() {
		return this.Config.Id.CANCEL;
	},

	draw : function() {
		$(this.getCategoriesAreaId()).update(this.createCategoryCheckBoxHtml());
		$(this.Config.Id.QUESTION).update(this.record.expressions[this.language]);
	},

	onShowPanel : function() {
		
	},
	
	onHidePanel : function() {
	},
	
	getCheckedCategoryIds : function() {
		var categoryIds = [];
		$$('.' + this.Config.ClassName.CHECK).each(function(element){
			if (element.checked) {
				categoryIds.push(parseInt(element.value));
			}
		}.bind(this));
		return categoryIds;
	}
});

QaEditSelectCategoryPopupPanel.prototype.Config = {
	Id : {
		QUESTION : 'qa-select-category-question',
		STATUS : 'qa-select-category-status',
		LANGUAGES : 'qa-select-category-language',
		MASTER : 'qa-select-category-master',
		CLOSE : 'qa-select-category-close',
		OK : 'qa-select-category-ok',
		CANCEL : 'qa-select-category-cancel',
		CATEGORIES_AREA : 'qa-select-categories-area'
	},
	ClassName : {
		CHECK : 'qa-select-category-check',
		CLOSE_DISABLED : 'qa-common-close-disabled',
		SAVE_DISABLED : 'qa-common-save-disabled',
		CANCEL_DISABLED : 'qa-common-cancel-disabled'
	}
};
QaEditSelectCategoryPopupPanel.prototype.Event = {
	sourceLanguageChanged : function(event) {
		this.checkedIds = this.getCheckedCategoryIds();
		this.language = $(this.getLanguagesId()).value;
		this.draw();
	},

	categoryMasterClicked : function(event) {
		var popup = new QaEditMasterCategoryPopupPanel();
		popup.resource = this.resource;
		popup.firstSourceLanguage = this.recordPanel.parent.sourceLanguage;
		popup.sourceLanguage = this.recordPanel.parent.sourceLanguage;
		popup.targetLanguage = this.recordPanel.parent.targetLanguage;
		popup.onHidePanel = function(){
			this.recordPanel.parent.draw;
			
			var popup = new QaEditSelectCategoryPopupPanel();
			popup.resource = this.resource;
			popup.language = this.language;
			popup.record = this.record;
			popup.checkedIds = this.checkedIds;
			popup.recordPanel = this.recordPanel;
			popup.stateManager = this.stateManager;
			popup.onHidePanel = this.onHidePanel;
			popup.show();
		}.bind(this);
		popup.show();
	},

	saveButtonClicked : function(event) {
		if (this.isSaveCancelButtonDisabled()) {
			return;
		}
		this.submit();
	},

	closeButtonClicked : function(event) {
		if (this.isSaveCancelButtonDisabled()) {
			return;
		}
		this.hide();
	},

	cancelButtonClicked : function(event) {
		if (this.isSaveCancelButtonDisabled()) {
			return;
		}
		this.hide();
	}
};

QaEditSelectCategoryPopupPanel.prototype.Templates = {
	base : '<table id="qa-select-category-table">'
		+ '<tr><td><div class="float-right"><button id="#{closeId}" class="qa-common-close">×</button></div></td></tr>'
		+ '<tr><td><div class="float-right" style="margin-top: 4px;">in <select id="#{languagesId}">#{languages}</select></div></td></tr>'
		+ '<tr><td><b>' + Global.Text.Q + ': <span id="#{questionId}">#{question}</span></b></td></tr>'
		+ '<tr><td><div class="float-right"><span id="#{editMasterCategoryId}" class="qa-common-clickable">&gt; #{editMasterCategory}</span></div></td></tr>'
		+ '<tr><td><div id="#{categoriesArea}" class="qa-common-selection">#{categories}</div></td></tr>'
		+ '<tr><td>'
		+ '<div class="float-right">'
		+ '<span id="#{statusId}"></span>'
		+ '<button class="qa-common-save-button" id="#{saveId}"><span>#{save}</span></button>'
		+ '<button class="qa-common-cancel-button" id="#{cancelId}"><span>#{cancel}</span></button>'
		+ '</div>'
		+ '</td></tr>'
		+ '</table>',
	categoryCheckBox : '<label><input class="#{checkClassName}" #{attribute} type="checkbox" value="#{value}" /> #{contents}</label><br />'
};
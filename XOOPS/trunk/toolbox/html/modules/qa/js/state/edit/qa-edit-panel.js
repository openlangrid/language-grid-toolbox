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
var QaEditPanel = Class.create();
Object.extend(QaEditPanel.prototype, Panel.prototype);
Object.extend(QaEditPanel.prototype, {
	
	id : 'qa-edit-panel',
	recordsPanel : null,
	
	resource : null,
	observed : false,
	
	readOnly : false,

	initialize : function() {
		Panel.prototype.initialize.apply(this, arguments);
		this.recordsPanel = new QaEditRecordsPanel();
	},

	initEventListeners : function() {
		if (!this.observerd) {
			document.observe('source:changed', this.drawCategories.bind(this));
			this.observerd = true;
		}
		this.addEvent('editLanguagesClicked', this.Config.Id.EDIT_LANGUAGES, 'click', this.Event.editLanguagesClicked.bindAsEventListener(this));
		this.addEvent('editCategoriesClicked', this.Config.Id.EDIT_CATEGORIES, 'click', this.Event.editCategoriesClicked.bindAsEventListener(this));
		this.addEvent('addQuestionClicked', this.Config.Id.ADD_QUESTION, 'click', this.Event.addQuestionClicked.bindAsEventListener(this));
		this.addEvent('openAllAnswersClicked', this.Config.Id.OPEN_ALL_ANSWERS, 'click', this.Event.openAllAnswersClicked.bindAsEventListener(this));
		this.addEvent('closeAllAnswersClicked', this.Config.Id.CLOSE_ALL_ANSWERS, 'click', this.Event.closeAllAnswersClicked.bindAsEventListener(this));
	},

	load : function() {
		this.update(Global.Image.LOADING + ' ' + Global.Text.NOW_LOADING);
		new Ajax.Request(Global.Url.READ_RESOURCE, {
			postBody : Object.toQueryString({
				name : Global.location
			}),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				this.resource = response.contents.resource;
				for (var id in response.contents.categories) {
					Global.Categories.set(id, response.contents.categories[id]);
				}
				if (this.resource.languages.indexOf(Global.displayLanguage) != -1) {
					this.sourceLanguage = Global.displayLanguage;
				} else {
					this.sourceLanguage = this.resource.languages[0];
				}
				this.readOnly = (this.resource.meta.permission <= 1);
				this.recordsPanel.set(this.resource, this.resource.languages, this.sourceLanguage);
				this.draw();
			}.bind(this),
			onException : function() {
			},
			onFailure : function() {
				alert(Global.Text.ERROR_AJAX_FAILURE);
			}
		});
	},
	
	createHtml : function() {
		var attribute = (this.readOnly) ? 'style="display:none;"' : '';
		var languages = [];
		LanguageUtils.sort(this.resource.languages);
		this.resource.languages.each(function(language){
			languages.push(Global.Language[language]);
		}.bind(this));

		var categories = [];
		this.resource.categoryIds.each(function(categoryId){
			categories.push(Global.Categories.getName(categoryId, this.sourceLanguage));
		}.bind(this));

		return new Template(this.Templates.base).evaluate({
			clickableClassName : Global.ClassName.CLICKABLE_TEXT,
			languagesLabel : Global.Text.LANGUAGES,
			languagesId : this.LANGUAGES_ID,
			languages : languages.join(', '),
			editLanguagesAttribute : attribute,
			editLanguagesId : this.Config.Id.EDIT_LANGUAGES,
			editLanguages : Global.Text.ADD_DELETE_LANGUAGES,
			categoriesId : this.CATEGORIES_ID,
			categoriesLabel : Global.Text.CATEGORIES,
			categories : categories.join(', '),
			attribute : attribute,
			editCategoriesAttribute : attribute,
			editCategoriesId : this.Config.Id.EDIT_CATEGORIES,
			editCategories : Global.Text.EDIT_THE_CATEGORIES,
			recordsId : this.recordsPanel.id,
			addQuestionId : this.Config.Id.ADD_QUESTION,
			addQuestion : Global.Text.ADD_QUESTION,
			allAnswerLabel : Global.Text.ALL_ANSWERS,
			openAnswersId : this.Config.Id.OPEN_ALL_ANSWERS,
			openAnswers : Global.Text.OPEN,
			closeAnswersId : this.Config.Id.CLOSE_ALL_ANSWERS,
			closeAnswers : Global.Text.CLOSE,
			returnToTop : Global.Text.RETURN_TO_TOP
		});
	},
	
	drawCategories : function() {
		var categories = [];
		this.resource.categoryIds.each(function(categoryId){
			var category = Global.Categories.get(categoryId)[this.recordsPanel.sourceLanguage] || '';
			if (!category) {
				var original = Global.Categories.get(categoryId)[Global.Categories.get(categoryId).language] || Global.Text.BLANK;
				category = Global.Text.BLANK + '(' + original + ')';
			}
			categories.push(category);
		}.bind(this));
		$(this.CATEGORIES_ID).update(categories.join(', '));
	},

	draw : function() {
		this.stopEventObserving();
		this.update(this.createHtml());
		this.initEventListeners();
		this.startEventObserving();
		this.recordsPanel.draw();
	},
	
	clear : function() {
		this.recordsPanel.clear();
	},
	
	LANGUAGES_ID : 'qa-edit-languages-1988',
	CATEGORIES_ID : 'qa-edit-categories-0316'
});

QaEditPanel.prototype.Config = {
	Id : {
		EDIT_LANGUAGES : 'qa-edit-edit-languages',
		EDIT_CATEGORIES : 'qa-edit-edit-categories',
		ADD_QUESTION : 'qa-edit-add-question',
		OPEN_ALL_ANSWERS : 'qa-edit-open-all-answers',
		CLOSE_ALL_ANSWERS : 'qa-edit-close-all-answers'
	},
	ClassName : {
		
	}
};

QaEditPanel.prototype.State = {
	READY : 0,
	EDIT : 1,
	UNSAVED : 2
};

QaEditPanel.prototype.Templates = {
	base : '#{languagesLabel}: <span id="#{languagesId}">#{languages}</span> <span #{editLanguagesAttribute} >[<span class="#{clickableClassName}" id="#{editLanguagesId}">#{editLanguages}</span>]</span><br />'
		+ '#{categoriesLabel}: <span id="#{categoriesId}">#{categories}</span> <span #{editCategoriesAttribute} >[<span class="#{clickableClassName}" id="#{editCategoriesId}">#{editCategories}</span>]</span>'
		+ '<div class="clearfix">'
		+ '<div class="float-right">'
		+ '<button #{attribute} id="#{addQuestionId}"><span>#{addQuestion}</span></button><br />'
		+ '#{allAnswerLabel} <span class="#{clickableClassName}" id="#{openAnswersId}">#{openAnswers}</span> | <span class="#{clickableClassName}" id="#{closeAnswersId}">#{closeAnswers}</span>'
		+ '</div>'
		+ '</div>'
		+ '<div id="#{recordsId}"></div>'
		+ '<a href="#">↑#{returnToTop}</a>'
};

QaEditPanel.prototype.Event = {
	editLanguagesClicked : function(event) {
		if (!!Global.recordPanel) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		var popup = new QaEditQaPopupPanel();
		popup.resource = this.resource;
		popup.onSavePanel = function(languages) {
//			var oldLanguages = popup.resource.languages.clone();
			popup.resource.languages.clear();
			var dispLanguages = [];
			LanguageUtils.sort(languages);
			languages.each(function(language){
				popup.resource.languages.push(language);
				dispLanguages.push(Global.Language[language]);
			}.bind(popup));
			$(this.LANGUAGES_ID).update(dispLanguages.join(', '));
			if (popup.resource.languages.indexOf(this.recordsPanel.sourceLanguage) == -1) {
				this.recordsPanel.sourceLanguage = popup.resource.languages[0];
			}
			if (popup.resource.languages.indexOf(this.recordsPanel.targetLanguage) == -1) {
				this.recordsPanel.targetLanguage = popup.resource.languages[0];
			}
			if (this.recordsPanel.sourceLanguage == this.recordsPanel.targetLanguage) {
				this.recordsPanel.targetLanguage = popup.resource.languages[0];
			}
			if (this.recordsPanel.sourceLanguage == this.recordsPanel.targetLanguage) {
				this.recordsPanel.targetLanguage = popup.resource.languages[1];
			}
			this.recordsPanel.recordPanels.each(function(panel){
				panel.sourceLanguage = this.recordsPanel.sourceLanguage;
				panel.targetLanguage = this.recordsPanel.targetLanguage;
			}.bind(this));
			this.recordsPanel.draw();
		}.bind(this);
		popup.show();
	},

	editCategoriesClicked : function(event) {
		var popup = new QaEditMasterCategoryPopupPanel();
		popup.resource = this.resource;
		popup.firstSourceLanguage = this.recordsPanel.sourceLanguage;
		popup.sourceLanguage = this.recordsPanel.sourceLanguage;
		popup.targetLanguage = this.recordsPanel.targetLanguage;
		popup.onHidePanel = function() {
			this.drawCategories();
			this.recordsPanel.draw();
		}.bind(this);
		popup.show();
	},
	addQuestionClicked : function(event) {
		this.recordsPanel.addRecord();
	},
	openAllAnswersClicked : function(event) {
		this.recordsPanel.openAllAnswers();
	},
	closeAllAnswersClicked : function(event) {
		this.recordsPanel.closeAllAnswers();
	}
};
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
var QaEditInsertParameterPopupPanel = Class.create();
Object.extend(QaEditInsertParameterPopupPanel.prototype, LightPopupPanel.prototype);
Object.extend(QaEditInsertParameterPopupPanel.prototype, {
	
	WIDTH : '600',

	stateManager : null,
	checkedIds : null,
	resource : null,
	record : null,
	recordPanel : null,
	language : null,
	
	checkedId : null,
	
	parameterIds : null,
	
	drawCompleteFlag : false,
	
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
		
		this.addEvent('addButtonClickEvent', this.getAddButtonId(), 'click', this.Event.addButtonClicked.bindAsEventListener(this));
		this.addEvent('deleteButtonClickEvent', this.getDeleteButtonId(), 'click', this.Event.deleteButtonClicked.bindAsEventListener(this));
		this.addEvent('editButtonClickEvent', this.getEditButtonId(), 'click', this.Event.editButtonClicked.bindAsEventListener(this));
	},

	createCategoryCheckBoxHtml : function() {
		var html = [];
		html.push('<table width="100%" id="qa-master-category">');
		html.push('<tr><th width="100" style="white-space: nowrap;" colspan="2">' + Global.Text.INDEX + '</th><th>' + Global.Text.TYPE_OF_VALUE + '</th></tr>');
		this.parameterIds.each(function(parameterId, i){
			
			var attribute = '';
			
			if (this.checkedId == i) {
				attribute = 'checked="checked"';
			}
			
			html.push(new Template(this.Templates.categoryCheckBox).evaluate({
				typeSelector : this.createTypeSelectorHtml(parameterId),
				index : i,
				attribute : attribute
			}));
		}.bind(this));
		html.push('</table>');
		
		return html.join('');
	},
	
	createTypeSelectorHtml : function(selectedId) {
		var html = [];
		html.push('<select class="type-select">');
		this.resource.wordSetIds.each(function(id){
			if (id == selectedId) {
				html.push('<option selected="selected" value="' + id + '">');
			} else {
				html.push('<option value="' + id + '">');
			}
			html.push(Global.WordSets.getName(id, this.language));
			html.push('</option>');
		}.bind(this));
		html.push('</select>');
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
		var body = new Template(this.Templates.base).evaluate({
			titleLabel : Global.Text.INSERT_A_NEW_PARAMETER,
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
			save : Global.Text.INSERT,
			cancelId : this.getCancelButtonId(),
			cancel : Global.Text.CANCEL,
			addParameterButtonId : this.getAddButtonId(),
			removeParameterButtonId : this.getDeleteButtonId(),
			editParameterButtonId : this.getEditButtonId()
		});
		this.drawCompleteFlag = true;
		return body;
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
	
	getSelectedIndex : function() {
		
	},

	/**
	 * submit
	 */
	submit : function() {
		if (this.getCheckedId()) {
			var language = (this.leftFlag) ? this.recordPanel.parent.sourceLanguage : this.recordPanel.parent.targetLanguage;
			this.record.expressions[language] = (this.record.expressions[language] || '') + '[' + this.getCheckedId() + ']';
		}
		
		this.stateManager.setChangedParameter('Parameter', null, 'Change');
		this.stateManager.currentRecord.parameterIds = this.getSelectedParameterIds();
		this.hide();
	},
	
	/**
	 * 
	 */
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
	
	getAddButtonId : function() {
		return this.Config.Id.ADD;
	},
	
	getDeleteButtonId : function() {
		return this.Config.Id.DELETE;
	},
	
	getEditButtonId : function() {
		return this.Config.Id.EDIT;
	},

	draw : function() {
		this.checkedId = this.getCheckedId();
		$(this.getCategoriesAreaId()).update(this.createCategoryCheckBoxHtml());
		// $(this.Config.Id.QUESTION).update(Global.Text.INSERT_A_PARAMETER);
		this.drawCompleteFlag = true;
	},
	
	getSelectedParameterIds : function() {
		if (!this.drawCompleteFlag) {
			return this.parameterIds;
		}
		
		var ids = [];
		$$('.type-select').each(function(select){
			ids.push(select.value);
		});
		return ids;
	},
	
	getCheckedId : function() {
		var id = null;
		$$('.template-insert-index-radio').each(function(radio){
			if (radio.checked) {
				id = radio.value;
				throw $break;
			}
		});
		
		return id;
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
	},
	
	addNewRow : function() {
		this.parameterIds = this.getSelectedParameterIds();
		this.parameterIds.push(Global.WordSets.getTextType().id);
	},
	
	getRows : function() {
		return this.parameterIds.length;
	},
	
	deleteRow : function() {
		this.parameterIds = this.getSelectedParameterIds();
		this.parameterIds.pop();
	}
});

QaEditInsertParameterPopupPanel.prototype.Config = {
	Id : {
		QUESTION : 'qa-select-category-question',
		STATUS : 'qa-select-category-status',
		LANGUAGES : 'qa-select-category-language',
		MASTER : 'qa-select-category-master',
		CLOSE : 'qa-select-category-close',
		OK : 'qa-select-category-ok',
		CANCEL : 'qa-select-category-cancel',
		ADD : 'qa-select-category-add',
		DELETE : 'qa-select-category-delete',
		EDIT : 'qa-select-category-edit',
		CATEGORIES_AREA : 'qa-select-categories-area'
	},
	ClassName : {
		CHECK : 'qa-select-category-check',
		CLOSE_DISABLED : 'qa-common-close-disabled',
		SAVE_DISABLED : 'qa-common-save-disabled',
		CANCEL_DISABLED : 'qa-common-cancel-disabled'
	}
};
QaEditInsertParameterPopupPanel.prototype.Event = {
	sourceLanguageChanged : function(event) {
		this.checkedIds = this.getCheckedCategoryIds();
		this.parameterIds = this.getSelectedParameterIds();
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
			
			var popup = new QaEditInsertParameterPopupPanel();
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

	/**
	 * on click save button
	 */
	saveButtonClicked : function(event) {
		if (this.isSaveCancelButtonDisabled()) {
			return;
		}
		this.submit();
	},

	/**
	 * 
	 */
	closeButtonClicked : function(event) {
		if (this.isSaveCancelButtonDisabled()) {
			return;
		}
		this.hide();
	},

	/**
	 * 
	 */
	cancelButtonClicked : function(event) {
		if (this.isSaveCancelButtonDisabled()) {
			return;
		}
		this.hide();
	},
	
	addButtonClicked : function(event) {
		this.addNewRow();
		this.draw();
	},
	
	deleteButtonClicked : function(event) {
		if (this.getRows() == 0) {
			return;
		}
		this.deleteRow();
		this.draw();
	},
	
	editButtonClicked : function(event) {
		var popup = new QaEditMasterSetPopupPanel();
		
		popup.resource = this.resource;
		popup.sourceLanguage = this.recordPanel.parent.sourceLanguage;
		popup.targetLanguage = this.recordPanel.parent.targetLanguage;
		popup.onHidePanel = function(){
			this.recordPanel.parent.draw();
			this.show();
		}.bind(this);
		
		popup.show();
	}
};

QaEditInsertParameterPopupPanel.prototype.Templates = {
	base : '<table id="qa-select-category-table">'
		+ '<tr><td><div class="float-right"><button id="#{closeId}" class="qa-common-close">×</button></div></td></tr>'
		+ '<tr><td><div style="margin: 4px 0 15px 0;" class="float-left"><b class="qa-common-popup-new-title">#{titleLabel}</b></div><div class="float-right" style="margin-top: 4px;">in <select id="#{languagesId}">#{languages}</select></div></td></tr>'
//		+ '<tr><td><b><span id="#{questionId}">#{question}</span></b></td></tr>'
		+ '<tr><td><div class="float-right">'
		+ '<button class="qa-common-blue-button" id="#{addParameterButtonId}"><span>' + Global.Text.ADD_PARAMETER + '</span></button>'
		+ '<button class="qa-common-blue-button" id="#{removeParameterButtonId}"><span>' + Global.Text.REMOVE_PARAMETER + '</span></button>'
		+ '<button class="qa-common-blue-button" id="#{editParameterButtonId}"><span>' + Global.Text.EDIT_PARAMETER + '</span></button>'
		+ '</div></td></tr>'
		+ '<tr><td><div id="#{categoriesArea}" class="qa-common-selection">#{categories}</div></td></tr>'
		+ '<tr><td>'
		+ '<div class="float-right">'
		+ '<span id="#{statusId}"></span>'
		+ '<button class="qa-common-save-button" id="#{saveId}"><span>#{save}</span></button>'
		+ '<button class="qa-common-cancel-button" id="#{cancelId}"><span>#{cancel}</span></button>'
		+ '</div>'
		+ '</td></tr>'
		+ '</table>',
	categoryCheckBox : '<tr><td width="15"><input class="template-insert-index-radio" value="#{index}" type="radio" #{attribute} name="index" /></td><td width="70">#{index}</td><td>#{typeSelector}</td></tr>'
};
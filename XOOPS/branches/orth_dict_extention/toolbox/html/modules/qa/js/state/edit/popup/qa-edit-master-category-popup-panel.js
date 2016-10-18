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
var QaEditMasterCategoryPopupPanel = Class.create();
Object.extend(QaEditMasterCategoryPopupPanel.prototype, LightPopupPanel.prototype);
Object.extend(QaEditMasterCategoryPopupPanel.prototype, {
	
	WIDTH : '700',
	resource : null,

	newFlag : false,
	newLanguage : null,
	changeFlag : false,
	editing : null,
	editingCategoryId : null,
	cache : null,

	firstSourceLanguage : null,
	sourceLanguage : null,
	targetLanguage : null,

	initialize : function() {
		LightPopupPanel.prototype.initialize.apply(this, arguments);
	},

	initEventListeners : function() {	
		LightPopupPanel.prototype.initEventListeners.apply(this, arguments);
		this.addEvent('closeClicked', this.CLOSE_ID, 'click', this.closeClicked.bindAsEventListener(this));
		this.addEvent('xClicked', this.X_ID, 'click', this.closeClicked.bindAsEventListener(this));

		this.addEvent('sourceLanguageChanged', this.SOURCE_ID, 'change', this.sourceLanguageChanged.bindAsEventListener(this));
		this.addEvent('targetLanguageChanged', this.TARGET_ID, 'change', this.targetLanguageChanged.bindAsEventListener(this));

		$$('.' + this.EDITABLE_CELL_CLASS).each(function(element, i){
			this.addEvent('contentsClicked' + i, element, 'click', this.contentsClicked.bindAsEventListener(this));
		}.bind(this));

		this.addEvent('addCategoryClicked', this.ADD_CATEGORY_ID, 'click', this.addCategoryClicked.bindAsEventListener(this));

		if (this.resource) {
			(this.resource.categoryIds || []).each(function(categoryId, i){
				this.addEvent('deleteClicked' + i, this.getDeleteId(categoryId), 'click', this.deleteClicked.bindAsEventListener(this));
				this.addEvent('saveClicked' + i, this.getSaveId(categoryId), 'click', this.saveClicked.bindAsEventListener(this));
				this.addEvent('cancelClicked' + i, this.getCancelId(categoryId), 'click', this.cancelClicked.bindAsEventListener(this));
			}.bind(this));
		}
	},

	createLanguageSelector : function(selectedLanguage) {
		var html = [];
		var attribute = '';
		LanguageUtils.sort(this.resource.languages);
		this.resource.languages.each(function(language) {
			if (selectedLanguage == language) {
				attribute = 'selected="selected"';
			} else {
				attribute = '';
			}
			html.push(new Template('<option #{attribute} value="#{value}">#{contents}</option>').evaluate({
				attribute : attribute,
				value : language,
				contents : Global.Language[language]
			}));
		}.bind(this));
		return html.join('');
	},

	createCategoryRow : function() {
		var html = [];
		this.resource.categoryIds.each(function(categoryId){
			var source = Global.Categories.get(categoryId)[this.sourceLanguage];
			var target = Global.Categories.get(categoryId)[this.targetLanguage];
			if (this.editingCategoryId == categoryId && this.changeFlag) {
				source = this.cache[this.sourceLanguage];
				target = this.cache[this.targetLanguage];
			}
			if (!source) {
				source = Global.Text.BLANK;
			}
			if (!target) {
				target = Global.Text.BLANK;
			}
			html.push(new Template(this.Templates.categoryRow).evaluate({
				rowId : this.getRowId(categoryId),
				unsavedId : this.getUnsavedId(categoryId),
				sourceClass : this.SOURCE_CLASS,
				source : source,
				targetClass : this.TARGET_CLASS,
				target : target,
				deleteLabel : Global.Text.DELETE,
				deleteId : this.getDeleteId(categoryId),
				saveId : this.getSaveId(categoryId),
				save : Global.Text.SAVE,
				cancelId : this.getCancelId(categoryId),
				cancel : Global.Text.CANCEL,
				saveCancelRowId : this.getSaveCancelRowId(categoryId)
			}));
		}.bind(this));
		return html.join('');
	},

	getBody : function() {
		return new Template(this.Templates.base).evaluate({
			qa : this.resource.name,
			editMasterCategory : Global.Text.EDIT_THE_CATEGORIES,
			xId : this.X_ID,
			addCategoryId : this.ADD_CATEGORY_ID,
			addCategory : Global.Text.ADD_CATEGORY,
			sourceLanguageId : this.SOURCE_ID,
			sourceClass : this.SOURCE_CLASS,
			sourceLanguage : this.createLanguageSelector(this.sourceLanguage),
			targetLanguageId : this.TARGET_ID,
			targetClass : this.TARGET_CLASS,
			targetLanguage : this.createLanguageSelector(this.targetLanguage),
			categoryRowId : this.TBODY_ID,
			categoryRow : this.createCategoryRow(),
			close : Global.Text.CLOSE,
			closeId : this.CLOSE_ID
		});
	},

	draw : function() {
		this.stopEventObserving();
		$(this.SOURCE_ID).update(this.createLanguageSelector(this.sourceLanguage));
		$(this.TARGET_ID).update(this.createLanguageSelector(this.targetLanguage));
		$(this.TBODY_ID).update(this.createCategoryRow());
		if (this.changeFlag) {
			this.showSaveCancelRow(this.editingCategoryId);
			$(this.getUnsavedId(this.editingCategoryId)).show();
		}
		this.initEventListeners();
		this.startEventObserving();
		
//		if (this.resource.categoryIds.length == 0) {
//			this.addCategory();
//		}
	},

	onShowPanel : function() {
		this.stopEventObserving();
		new Ajax.Request(Global.Url.LOAD_CATEGORIES, {
			postBody : Object.toQueryString({
				name : this.resource.name
			}),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				for (var id in response.contents.categories) {
					var category = Global.Categories.get(id);
					if (!category) {
						continue;
					}
					category.count = response.contents.categories[id].count;
					Global.Categories.set(id, category);
				}
			}.bind(this)
		});
		this.initEventListeners();
		this.startEventObserving();
//		
//		if (this.resource.categoryIds.length == 0) {
//			this.addCategory();
//		}
	},
	
	onHidePane : function() {
		this.callBack();
	},

	callBack : function() {
		
	},

	contentsChangedProcess : function(){
		var newValue = $F(this.TEXTAREA_ID);
		var oldValue = $(this.TEXTAREA_ID).retrieve('oldValue');
		if (newValue == oldValue) {
			return;
		}
		if (!this.changeFlag) {
			this.cache = {};
			this.resource.languages.each(function(language){
				this.cache[language] = Global.Categories.get(this.editingCategoryId)[language];
			}.bind(this));
			this.showSaveCancelRow(this.editingCategoryId);
			$(this.getUnsavedId(this.editingCategoryId)).show();
			this.changeFlag = true;
		}
		if (this.editing.hasClassName(this.SOURCE_CLASS)) {
			this.cache[this.sourceLanguage] = newValue;
		} else {
			this.cache[this.targetLanguage] = newValue;
		}
	},
	
	addCategory : function() {
		this.resource.categoryIds.unshift(0);
		this.newFlag = true;
		this.changeFlag = true;
		this.editingCategoryId = 0;
		this.cache = {};
		this.resource.languages.each(function(language){
			this.cache[language] = Global.Categories.get(this.editingCategoryId)[language];
		}.bind(this));
		this.changeFlag = true;
		this.draw();
	}
});
Object.extend(QaEditMasterCategoryPopupPanel.prototype, {
	
	SOURCE_ID : 'popup-source',
	TARGET_ID : 'popup-target',
	
	SAVE_DISABLED_CLASS : 'qa-common-save-disabled',
	CANCEL_DISABLED_CLASS : 'qa-common-cancel-disabled',
	CLOSE_DISABLED_CLASS : 'qa-common-close-disabled',
	
	SOURCE_CLASS : 'popup-source-text',
	TARGET_CLASS : 'popup-target-text',
	
	DELETE : 'popup-delete-category-',
	
	TEXTAREA_ID : 'popup-textarea',
	TBODY_ID : 'popup-tbody',
	
	ADD_CATEGORY_ID : 'popup-add-category-id',

	ROW : 'popup-row-',
	SAVE_CANCEL_ROW : 'popup-save-cancel-row-',
	
	DELETE_PREFIX : 'popup-delete-',
	SAVE_PREFIX : 'popup-save-',
	CANCEL_PREFIX : 'popup-cancel-',
	
	UNSAVED_PREFIX : 'popup-unsaved-koshiitai-',
	SAVE_ID : 'popup-save',
	CANCEL_ID : 'popup-cancel',

	CLOSE_ID : 'popup-close',
	X_ID : 'popup-x',
	
	NOW_EDITING_CLASS : 'popup-now-editing',
	EDITABLE_CELL_CLASS : 'popup-editable-cell',
	
	getRowId : function(id) {
		return this.ROW + id;
	},
	
	getSaveCancelRowId : function(id) {
		return this.SAVE_CANCEL_ROW + id;
	},

	getDeleteId : function(id) {
		return this.DELETE_PREFIX + id;
	},
	
	getUnsavedId : function(id) {
		return this.UNSAVED_PREFIX + id;
	},
	
	getSaveId : function(id) {
		return this.SAVE_PREFIX + id;
	},
	
	getCancelId : function(id) {
		return this.CANCEL_PREFIX + id;
	},
	
	isSaveCancelDisabled : function(id) {
		return $(this.CLOSE_ID).hasClassName(this.CLOSE_DISABLED_CLASS);
	},
	
	setSaveCancelAbled : function(id) {
		$(this.CLOSE_ID).removeClassName(this.CLOSE_DISABLED_CLASS);
		$(this.getCancelId(id)).removeClassName(this.CANCEL_DISABLED_CLASS);
		$(this.getSaveId(id)).removeClassName(this.SAVE_DISABLED_CLASS);
		
	},
	
	setSaveCancelDisabled : function(id) {
		$(this.CLOSE_ID).addClassName(this.CLOSE_DISABLED_CLASS);
		$(this.getCancelId(id)).addClassName(this.CANCEL_DISABLED_CLASS);
		$(this.getSaveId(id)).addClassName(this.SAVE_DISABLED_CLASS);
	},
	
	showSaveCancelRow : function(id) {
		$(this.getSaveCancelRowId(id)).show();
	},
	
	hideSaveCancelRow : function(id) {
		$(this.getSaveCancelRowId(id)).hide();
	},
	
	commitChanges : function() {
		var category = {};
		if (this.newFlag) {
			category.language = this.newLanguage;
		} else {
			category.language = Global.Categories.get(this.editingCategoryId).language;
		}
		this.resource.languages.each(function(language){
			category[language] = this.cache[language];
		}.bind(this));
		Global.Categories.set(this.editingCategoryId, category);
		if (this.newFlag) {
			this.resource.categoryIds.unshift(this.editingCategoryId);
		}
		this.discardChanges();
		this.draw();
	},

	discardChanges : function() {
		var deleteId = (this.newFlag) ? 0 : this.editingCategoryId;
		if (deleteId > 0) {
			this.hideSaveCancelRow(deleteId);
			$(this.getUnsavedId(deleteId)).hide();
		}
		this.cache = null;
		this.editing = null;
		this.editingCategoryId = null;
		this.changeFlag = false;
		this.newFlag = false;
	},

	contentsClicked : function(event) {
		var element = Event.element(event);
		var categoryId;
		element.ancestors().each(function(e){
			if (e.tagName == 'TR') {
				categoryId = e.id.replace(this.ROW, '');
				throw $break;
			}
		}.bind(this));
		if (this.changeFlag && (this.editingCategoryId != categoryId)) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
				this.resource.categoryIds.shift();
				$(this.ROW + this.editingCategoryId).next().remove();
				$(this.ROW + this.editingCategoryId).next().remove();
				$(this.ROW + this.editingCategoryId).remove();
			} else {
				$$('#' + this.ROW + this.editingCategoryId + ' td')[1].update(Global.Categories.get(this.editingCategoryId)[this.sourceLanguage]);
				$$('#' + this.ROW + this.editingCategoryId + ' td')[2].update(Global.Categories.get(this.editingCategoryId)[this.targetLanguage]);
			}
			this.discardChanges();
		}
		if (element.tagName == 'TEXTAREA' || element.hasClassName(this.NOW_EDITING_CLASS)) {
			return;
		}
		this.editing = element;
		this.editingCategoryId = categoryId;
		var value = element.innerHTML;
		if (value == Global.Text.BLANK) {
			value = '';
		}
		var dimensions = element.getDimensions();
		element.update('<textarea style="width: '+(dimensions.width-22)+'px; height: '+(dimensions.height-4)+'px;" id="' + this.TEXTAREA_ID + '">' + value + '</textarea>');
		$(this.TEXTAREA_ID).focus();
		$(this.TEXTAREA_ID).store({
			oldValue : value
		});
		$(this.TEXTAREA_ID).observe('keypress', this.keypressEvent.bindAsEventListener(this));
		$(this.TEXTAREA_ID).observe('keydown', this.keypressEvent.bindAsEventListener(this));
		$(this.TEXTAREA_ID).observe('keyup', this.contentsChanged.bindAsEventListener(this));
		$(this.TEXTAREA_ID).observe('blur', this.contentsBlured.bindAsEventListener(this));
		element.addClassName(this.NOW_EDITING_CLASS);
		element.removeClassName(this.EDITABLE_CELL_CLASS);
	},
	keypressEvent : function(event) {
		if (event.keyCode == Event.KEY_RETURN) {
			Event.stop(event);
			return false;
		}
	},
	contentsChanged : function(event) {
		if (event.keyCode == Event.KEY_RETURN) {
			Event.stop(event);
			return false;
		}
		this.contentsChangedProcess();
	},
	contentsBlured : function(event) {
		this.contentsChangedProcess();
		var value = $F(this.TEXTAREA_ID).escapeHTML();
		if (value == '') {
			value = Global.Text.BLANK;
		}
		this.editing.update(value);
		this.editing.removeClassName(this.NOW_EDITING_CLASS);
		this.editing.addClassName(this.EDITABLE_CELL_CLASS);
		this.contentsCache = null;
		this.editing = null;
	},
	sourceLanguageChanged : function(event) {
		this.sourceLanguage = $(this.SOURCE_ID).value;
		if (this.targetLanguage == this.sourceLanguage) {
			this.targetLanguage = this.resource.languages[0];
		}
		if (this.targetLanguage == this.sourceLanguage) {
			this.targetLanguage = this.resource.languages[1];
		}
		this.draw();
	},
	targetLanguageChanged : function(event) {
		this.targetLanguage = $(this.TARGET_ID).value;
		if (this.targetLanguage == this.sourceLanguage) {
			this.sourceLanguage = this.resource.languages[0];
		}
		if (this.targetLanguage == this.sourceLanguage) {
			this.sourceLanguage = this.resource.languages[1];
		}
		this.draw();
	},
	addCategoryClicked : function(event) {
		if (this.changeFlag) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
				this.resource.categoryIds.shift();
			}
			this.discardChanges();
			this.draw();
		}
		this.addCategory();
	},
	saveClicked : function(event) {
		if (this.isSaveCancelDisabled(this.editingCategoryId)) {
			return;
		}
		var language = this.firstSourceLanguage;
		var count = 0;
		var temp = '';
		for (var key in this.cache) {
			if (!!this.cache[key]) {
				temp = key;
				count++;
			}
		}
		if (count == 1) {
			language = temp;
		}
		var parameters = {
			newFlag : (this.newFlag) ? 1 : 0,
			name : this.resource.name,
			categoryId : this.editingCategoryId,
			language : language
		};
		this.resource.languages.each(function(language){
			parameters['expressions['+language+']'] = this.cache[language] || '';
		}.bind(this));

		this.setSaveCancelDisabled(this.editingCategoryId);
		new Ajax.Request(Global.Url.SAVE_MASTER_CATEGORY, {
			postBody : Object.toQueryString(parameters),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				this.setSaveCancelAbled(this.editingCategoryId);
				if (this.newFlag) {
					this.resource.categoryIds.shift();
					this.editingCategoryId = response.contents.id;
					this.newLanguage = language;
				}
				this.commitChanges();
			}.bind(this),
			onException : function() {
			},
			onFailure : function() {
			}
		});
	},
	cancelClicked : function(event) {
		if (this.isSaveCancelDisabled(this.editingCategoryId)) {
			return;
		}
		if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
			return;
		}
		if (this.newFlag) {
			this.resource.categoryIds.shift();
		}
		this.discardChanges();
		this.draw();
	},
	deleteClicked : function(event) {
		var categoryId = Event.element(event).id.replace(this.DELETE_PREFIX, '');
		if (this.changeFlag && (this.editingCategoryId != categoryId)) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
				this.resource.categoryIds.shift();
				$(this.ROW + this.editingCategoryId).next().remove();
				$(this.ROW + this.editingCategoryId).next().remove();
				$(this.ROW + this.editingCategoryId).remove();
			} else {
				$$('#' + this.ROW + this.editingCategoryId + ' td')[1].update(Global.Categories.get(this.editingCategoryId)[this.sourceLanguage]);
				$$('#' + this.ROW + this.editingCategoryId + ' td')[2].update(Global.Categories.get(this.editingCategoryId)[this.targetLanguage]);
			}
			this.discardChanges();
		}
		if (this.newFlag) {
			if (!confirm(Global.Text.CONFIRM_DELETE)) {
				return;
			}
			this.resource.categoryIds.shift();
			this.discardChanges();
			this.draw();
			return;
		}
		var count = Global.Categories.get(categoryId).count;
		var message = (count > 0)
						? Global.Text.CONFIRM_DELETE_CATEGORY.replace('#{0}', count)
						: Global.Text.CONFIRM_DELETE;
		if (!confirm(message)) {
			return;
		}
		var parameter = Object.toQueryString({
			categoryId : categoryId,
			name : this.resource.name
		});
		new Ajax.Request(Global.Url.DELETE_MASTER_CATEGORY, {
			postBody : parameter,
			onSuccess : function() {
				
			}
		});
		this.resource.categoryIds.each(function(cId, i){
			if (cId == categoryId) {
				this.resource.categoryIds.splice(i, 1);
			}
		}.bind(this));
//		this.resource.categoryIds = this.resource.categoryIds.without(categoryId);
		document.fire('category:deleted');
		this.discardChanges();
		this.draw();
	},
	closeClicked : function(event) {
		if (this.isSaveCancelDisabled()) {
			return;
		}
		if (this.changeFlag ) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
				this.resource.categoryIds.shift();
			}
			this.discardChanges();
			this.draw();
		}
		this.hide();
	}
});
QaEditMasterCategoryPopupPanel.prototype.Templates = {
	base : '<table class="qa-mastar-category-wrapper" id="qa-select-category-table">'
		+ '<tr><td><div class="float-right"><button id="#{xId}" class="qa-common-close">×</button></div></td></tr>'
		+ '<tr><td><b class="qa-common-popup-new-title">#{qa} #{editMasterCategory}</b><div class="float-right"><button id="#{addCategoryId}" class="qa-common-add-button"><span>#{addCategory}</span></button></div></td></tr>'
		+ '<tr><td><div class="qa-master-selection qa-common-selection">'
		+ '<table id="qa-master-category">'
		+ '<tr><th></th><th><b>in</b> <select id="#{sourceLanguageId}">#{sourceLanguage}</select></th><th colspan="2"><b>in</b> <select id="#{targetLanguageId}">#{targetLanguage}</select></th></tr>'
		+ '<tbody id="#{categoryRowId}">'
		+ '#{categoryRow}'
		+ '</tbody>'
		+ '</table>'
		+ '</div></td></tr>'
		+ '<tr><td align="center">'
		+ '<button class="qa-common-cancel-button" id="#{closeId}"><span>#{close}</span></button>'
		+ '</td></tr>'
		+ '</table>',
	categoryRow : '<tr id="#{rowId}"><td class="bg-gray"><span id="#{unsavedId}" style="display: none;">*</span></td><td class="popup-editable-cell #{sourceClass}">#{source}</td><td class="popup-editable-cell #{targetClass}">#{target}</td><td><div class="float-right"><button id="#{deleteId}" class="qa-common-delete-button"><span>#{deleteLabel}</span></button></div></td></tr>'
		+ '<tr style="display: none;" id="#{saveCancelRowId}"><td class="bg-gray"> </td><td colspan="3"><div class="float-right"><button id="#{saveId}" class="qa-common-save-button"><span>#{save}</span></button><button id="#{cancelId}" class="qa-common-cancel-button"><span>#{cancel}</span></button></div></td></tr>'
		+ '<tr class="border-line"><td colspan="4"></td></tr>'
};
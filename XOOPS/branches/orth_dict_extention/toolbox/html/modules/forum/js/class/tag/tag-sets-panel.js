//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
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
var TagSetsPanel = Class.create();
Object.extend(TagSetsPanel.prototype, PanelPlus.prototype);
Object.extend(TagSetsPanel.prototype, {

	WIDTH : '765',
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
		PanelPlus.prototype.initialize.apply(this, arguments);
		this.sourceLanguage = TagConfigConst.Resource.sourceLang;
		this.targetLanguage = TagConfigConst.Resource.targetLang;
	},

	initEventListeners : function() {
		this.stopEventObserving();
		this.events = new Hash();

		this.addEvent('sourceLanguageChanged', this.SOURCE_ID, 'change', this.sourceLanguageChanged.bindAsEventListener(this));
		this.addEvent('targetLanguageChanged', this.TARGET_ID, 'change', this.targetLanguageChanged.bindAsEventListener(this));

		$$('.' + this.EDITABLE_CELL_CLASS).each(function(element, i){
			this.addEvent('contentsClicked' + i, element, 'click', this.contentsClicked.bindAsEventListener(this));
		}.bind(this));

		this.addEvent('addTagSetClicked', this.ADD_TAG_SET_ID, 'click', this.addTagSetClicked.bindAsEventListener(this));

		if (TagConfigConst.Resource.tagSets) {
			(TagConfigConst.Resource.tagSets || []).each(function(setObj, i){
				this.addEvent('deleteClicked' + i, this.getDeleteId(setObj.id), 'click', this.deleteClicked.bindAsEventListener(this));
				this.addEvent('editClicked' + i, this.getEditId(setObj.id), 'click', this.editClicked.bindAsEventListener(this));
				this.addEvent('saveClicked' + i, this.getSaveId(setObj.id), 'click', this.saveClicked.bindAsEventListener(this));
				this.addEvent('cancelClicked' + i, this.getCancelId(setObj.id), 'click', this.cancelClicked.bindAsEventListener(this));
			}.bind(this));
		}
	},

	createLanguageSelector : function(selectedLanguage) {
		var html = [];
		var attribute = '';
		$H(TagConfigConst.Languages).each(function(language) {
			if (selectedLanguage == language[0]) {
				attribute = 'selected="selected"';
			} else {
				attribute = '';
			}
			html.push(new Template('<option #{attribute} value="#{value}">#{contents}</option>').evaluate({
				attribute : attribute,
				value : language[0],
				contents : language[1]
			}));
		}.bind(this));
		return html.join('');
	},

	createCategoryRow : function() {
		var html = [];
//		this.resource.each(function(setObj, index) {
		TagConfigConst.Resource.tagSets.each(function(setObj, index) {
			var source = setObj.name[this.sourceLanguage];
			var target = setObj.name[this.targetLanguage];
			if (this.changeFlag && setObj.id == this.editingCategoryId) {
				source = this.cache[this.sourceLanguage];
				target = this.cache[this.targetLanguage];
			}

			source = (!source) ? TagConfigConst.Text.BLANK : source;
			target = (!target) ? TagConfigConst.Text.BLANK : target;

			html.push(new Template(this.Templates.tagSetRow).evaluate({
				source : source,
				target : target,
				sourceClass : this.SOURCE_CLASS,
				targetClass : this.TARGET_CLASS,
				rowId : this.getRowId(setObj.id),
				unsavedId : this.getUnsavedId(setObj.id),
				editId : this.getEditId(setObj.id),
				deleteId : this.getDeleteId(setObj.id),
				editLabel : TagConfigConst.Text.EDIT,
				deleteLabel : TagConfigConst.Text.DELETE,
				saveCancelRowId : this.getSaveCancelRowId(setObj.id),
				saveId : this.getSaveId(setObj.id),
				cancelId : this.getCancelId(setObj.id),
				save : TagConfigConst.Text.SAVE,
				cancel : TagConfigConst.Text.CANCEL
			}));
		}.bind(this));
		return html.join('');
	},

	getEditId : function(wordSetId) {
		return 'ms-edit-id-' + wordSetId;
	},

	getBody : function() {
		return new Template(this.Templates.base).evaluate({
			addCategoryId : this.ADD_TAG_SET_ID,
			addCategory : TagConfigConst.Text.ADD_TAG_SET,
			sourceLanguageId : this.SOURCE_ID,
			sourceLanguage : this.createLanguageSelector(this.sourceLanguage),
			targetLanguageId : this.TARGET_ID,
			targetLanguage : this.createLanguageSelector(this.targetLanguage),
			categoryRowId : this.TBODY_ID
		});
	},

	draw : function() {
		$(this.TBODY_ID).update(this.createCategoryRow());

		if (this.newFlag) {
			$$('.delete-edit')[0].hide();
		}
		
		if (this.changeFlag) {
			this.showSaveCancelRow(this.editingCategoryId);
			$(this.getUnsavedId(this.editingCategoryId)).show();
		}

		this.initEventListeners();
		this.startEventObserving();
	},

	onShowPanel : function() {
		this.draw();
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

			$H(TagConfigConst.Languages).each(function(language) {
				this.cache[language[0]] = (this.editingResource.name[language[0]] == 'undefined' ? '' : this.editingResource.name[language[0]]);
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

	addTagSet : function() {
//		this.resource.wordSetIds.unshift(0);
//		TagConfigConst.Resource.tagSets.unshift(0);
		this.newFlag = true;
		this.changeFlag = true;
		this.editingCategoryId = 0;
		this.cache = {};

		var obj = {id:0, name:{}};

		$H(TagConfigConst.Languages).each(function(language) {
			this.cache[language[0]] = '';
			obj.name[language[0]] = '';
		}.bind(this));
//		this.resource.languages.each(function(language){
//			this.cache[language] = Global.WordSets.get(this.editingCategoryId)[language];
//		}.bind(this));

		TagConfigConst.Resource.tagSets.unshift(obj);

		this.changeFlag = true;
		this.draw();
	},

	isAllEmpty : function() {
		var emptyFlag = true;
		$H(TagConfigConst.Languages).each(function(language) {
			if (!!this.cache[language[0]]) {
				emptyFlag = false;
				throw $break;
			}
		}.bind(this));

		return emptyFlag;
	},

	getResource : function(tagSetId) {
		var res = null;
		TagConfigConst.Resource.tagSets.each(function(setObj) {
			if (setObj.id == tagSetId) {
				res = setObj;
			}
		}.bind(this));
		return res;
	}
});
Object.extend(TagSetsPanel.prototype, {

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

	ADD_TAG_SET_ID : 'popup-add-tagset-id',

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
//		return $(this.CLOSE_ID).hasClassName(this.CLOSE_DISABLED_CLASS);
		return false;
	},

	setSaveCancelAbled : function(id) {
//		$(this.CLOSE_ID).removeClassName(this.CLOSE_DISABLED_CLASS);
		$(this.getCancelId(id)).removeClassName(this.CANCEL_DISABLED_CLASS);
		$(this.getSaveId(id)).removeClassName(this.SAVE_DISABLED_CLASS);

	},

	setSaveCancelDisabled : function(id) {
//		$(this.CLOSE_ID).addClassName(this.CLOSE_DISABLED_CLASS);
		$(this.getCancelId(id)).addClassName(this.CANCEL_DISABLED_CLASS);
		$(this.getSaveId(id)).addClassName(this.SAVE_DISABLED_CLASS);
	},

	showSaveCancelRow : function(id) {
		$(this.getSaveCancelRowId(id)).show();
	},

	hideSaveCancelRow : function(id) {
		$(this.getSaveCancelRowId(id)).hide();
	},

	commitChanges : function(savedTagSetObj) {
		TagConfigConst.Resource.tagSets.each(function(setObj, index) {
			if (setObj.id == this.editingCategoryId) {
				TagConfigConst.Resource.tagSets[index] = savedTagSetObj;
			}
		}.bind(this));
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
		var wordSetId;
		element.ancestors().each(function(e){
			if (e.tagName == 'TR') {
				wordSetId = e.id.replace(this.ROW, '');
				throw $break;
			}
		}.bind(this));
		if (this.changeFlag && (this.editingCategoryId != wordSetId)) {
			if (!confirm(TagConfigConst.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
				//this.resource.wordSetIds.shift();
				TagConfigConst.Resource.tagSets.shift();
				$(this.ROW + this.editingCategoryId).next().remove();
				$(this.ROW + this.editingCategoryId).next().remove();
				$(this.ROW + this.editingCategoryId).remove();
			} else {
//				$$('#' + this.ROW + this.editingCategoryId + ' td')[1].update(Global.WordSets.get(this.editingCategoryId)[this.sourceLanguage]);
//				$$('#' + this.ROW + this.editingCategoryId + ' td')[2].update(Global.WordSets.get(this.editingCategoryId)[this.targetLanguage]);
				$$('#' + this.ROW + this.editingCategoryId + ' td')[1].update(this.editingResource.name[this.sourceLanguage]);
				$$('#' + this.ROW + this.editingCategoryId + ' td')[2].update(this.editingResource.name[this.targetLanguage]);
			}
			this.discardChanges();
		}
		if (element.tagName == 'TEXTAREA' || element.hasClassName(this.NOW_EDITING_CLASS)) {
			return;
		}
		this.editing = element;
		this.editingCategoryId = wordSetId;
		this.editingResource = this.getResource(wordSetId);
		var value = element.innerHTML;
		if (value == TagConfigConst.Text.BLANK) {
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
			value = TagConfigConst.Text.BLANK;
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
			$H(TagConfigConst.Languages).each(function(language) {
				if (language[0] != this.sourceLanguage) {
					this.targetLanguage = language[0];
					$(this.TARGET_ID).value = language[0];
					throw $break;
				}
			}.bind(this));
		}
		this.draw();
	},
	targetLanguageChanged : function(event) {
		this.targetLanguage = $(this.TARGET_ID).value;
		if (this.targetLanguage == this.sourceLanguage) {
			$H(TagConfigConst.Languages).each(function(language) {
				if (language[0] != this.targetLanguage) {
					this.sourceLanguage = language[0];
					$(this.SOURCE_ID).value = language[0];
					throw $break;
				}
			}.bind(this));
		}
		this.draw();
	},
	addTagSetClicked : function(event) {
		if (this.changeFlag) {
			if (!confirm(TagConfigConst.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
//				this.resource.wordSetIds.shift();
				TagConfigConst.Resource.tagSets.shift();
			}
			this.discardChanges();
			this.draw();
		}
		this.addTagSet();
	},
	saveClicked : function(event) {
		if (this.isSaveCancelDisabled(this.editingCategoryId)) {
			return;
		}
		if (this.isAllEmpty()) {
			alert(TagConfigConst.Text.ALL_EMPTY_NOT_REGISTER);
			return;
		}
		var parameters = {
			action : 'saveTagSet',
			newFlag : (this.newFlag) ? 1 : 0,
			tagSetId : this.editingCategoryId
		};
		$H(TagConfigConst.Languages).each(function(language) {
			parameters['expressions['+language[0]+']'] = this.cache[language[0]] || '';
		}.bind(this));

		this.setSaveCancelDisabled(this.editingCategoryId);
		new Ajax.Request(this.Urls.SAVE_TAG_SETS, {
			postBody : Object.toQueryString(parameters),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				this.setSaveCancelAbled(this.editingCategoryId);
				this.commitChanges(response.contents);
			}.bind(this),
			onException : function() {			},
			onFailure : function() {
			}
		});
	},

	cancelClicked : function(event) {
		if (this.isSaveCancelDisabled(this.editingCategoryId)) {
			return;
		}
		if (!confirm(TagConfigConst.Text.CONFIRM_DISCARD_CHANGES)) {
			return;
		}
		if (this.newFlag) {
			//this.resource.wordSetIds.shift();
			TagConfigConst.Resource.tagSets.shift();
		}
		this.discardChanges();
		this.draw();
	},

	editClicked : function(event) {
		var wordSetId = this.getWordSetIdByEditId(Event.element(event).id);
		var popup = new TagPanel();
		popup.wordSetId = wordSetId;
//		popup.resource = this.resource;
		popup.resource = this.getResource(wordSetId);
		popup.sourceLanguage = this.sourceLanguage;
		popup.targetLanguage = this.targetLanguage;
		popup.onHidePanel = function() {
//			this.show();
			$('preferences').show();
		}.bind(this);
		popup.show();
		$('preferences').hide();
	},

	getWordSetIdByEditId : function(editId) {
		return editId.replace('ms-edit-id-', '');
	},

	deleteClicked : function(event) {
		var wordSetId = Event.element(event).id.replace(this.DELETE_PREFIX, '');
		if (this.changeFlag && (this.editingCategoryId != wordSetId)) {
			if (!confirm(TagConfigConst.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
//				this.resource.wordSetIds.shift();
				TagConfigConst.Resource.tagSets.shift();
				$(this.ROW + this.editingCategoryId).next().remove();
				$(this.ROW + this.editingCategoryId).next().remove();
				$(this.ROW + this.editingCategoryId).remove();
			} else {
//				$$('#' + this.ROW + this.editingCategoryId + ' td')[1].update(Global.WordSets.get(this.editingCategoryId)[this.sourceLanguage]);
//				$$('#' + this.ROW + this.editingCategoryId + ' td')[2].update(Global.WordSets.get(this.editingCategoryId)[this.targetLanguage]);
				$$('#' + this.ROW + this.editingCategoryId + ' td')[1].update(this.editingResource.name[this.sourceLanguage]);
				$$('#' + this.ROW + this.editingCategoryId + ' td')[2].update(this.editingResource.name[this.targetLanguage]);
			}
			this.discardChanges();
		}
		if (this.newFlag) {
			if (!confirm(TagConfigConst.Text.CONFIRM_DELETE)) {
				return;
			}
//			this.resource.wordSetIds.shift();
			TagConfigConst.Resource.tagSets.shift();
			this.discardChanges();
			this.draw();
			return;
		}
//		var count = Global.WordSets.get(wordSetId).count;
//		var message = (count > 0)
//						? Global.Text.CONFIRM_DELETE_BOUND_WORD_SET.replace('#{0}', count)
//						: Global.Text.CONFIRM_DELETE;
		if (!confirm(TagConfigConst.Text.CONFIRM_DELETE)) {
			return;
		}
		var parameter = Object.toQueryString({
			action : 'deleteTagSet',
			tagSetId : wordSetId
//			name : this.resource.name
		});
		new Ajax.Request(this.Urls.DELETE_TAG_SETS, {
			postBody : parameter,
			onSuccess : function() {

			}
		});
//		this.resource.wordSetIds.each(function(cId, i){
//			if (cId == wordSetId) {
//				this.resource.wordSetIds.splice(i, 1);
//			}
//		}.bind(this));

		var arrIds = 0;
		TagConfigConst.Resource.tagSets.each(function(setObj, index) {
			if (setObj.id == wordSetId) {
				arrIds = index;
			}
		}.bind(this));
		TagConfigConst.Resource.tagSets.splice(arrIds, 1);

		document.fire('wordSet:deleted');
		this.discardChanges();
		this.draw();
	},
	closeClicked : function(event) {
		if (this.isSaveCancelDisabled()) {
			return;
		}
		if (this.changeFlag ) {
			if (!confirm(TagConfigConst.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
//				this.resource.wordSetIds.shift();
				TagConfigConst.Resource.tagSets.shift();
			}
			this.discardChanges();
			this.draw();
		}
		this.hide();
	}
});
TagSetsPanel.prototype.Urls = {
	LOAD_TAG_SETS : './?page=ajax_tag_config',
	SAVE_TAG_SETS : './?page=ajax_tag_config',
	DELETE_TAG_SETS : './?page=ajax_tag_config'
};
TagSetsPanel.prototype.Templates = {
	base : '<table class="qa-mastar-category-wrapper" id="qa-select-category-table">'
		+ '<tr><td><div class="float-right"><button id="#{addCategoryId}" class="qa-common-add-button"><span>#{addCategory}</span></button></div></td></tr>'
		+ '<tr><td><div class="qa-master-selection qa-common-selection">'
		+ '<table id="qa-master-category">'
		+ '<tr><th></th><th><b>in</b> <select id="#{sourceLanguageId}">#{sourceLanguage}</select></th><th colspan="2"><b>in</b> <select id="#{targetLanguageId}">#{targetLanguage}</select></th></tr>'
		+ '<tbody id="#{categoryRowId}">'
		+ '#{categoryRow}'
		+ '</tbody>'
		+ '</table>'
		+ '</div></td></tr>'
		+ '</table>',
	tagSetRow : '<tr id="#{rowId}"><td class="bg-gray"><span id="#{unsavedId}" style="display: none;">*</span></td><td class="popup-editable-cell #{sourceClass}">#{source}</td><td class="popup-editable-cell #{targetClass}">#{target}</td><td><div style="width: 150px;" class="float-right"><span class=" delete-edit"><button #{attribute} id="#{editId}" class="qa-common-delete-button"><span>#{editLabel}</span></button><button id="#{deleteId}" class="qa-common-delete-button"><span>#{deleteLabel}</span></button></span></div></td></tr>'
		+ '<tr style="display: none;" id="#{saveCancelRowId}"><td class="bg-gray"> </td><td colspan="3"><div class="float-right"><button id="#{saveId}" class="qa-common-save-button"><span>#{save}</span></button><button id="#{cancelId}" class="qa-common-cancel-button"><span>#{cancel}</span></button></div></td></tr>'
		+ '<tr class="border-line"><td colspan="4"></td></tr>'
};
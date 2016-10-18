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
var TagPanel = Class.create();
Object.extend(TagPanel.prototype, LightPopupPanelPlus.prototype);
Object.extend(TagPanel.prototype, {

	WIDTH : '765',
	resource : null,

	newFlag : false,
	newLanguage : null,
	changeFlag : false,
	editing : null,
	editingWordId : null,
	cache : null,

	wordIds : null,

	hasNew : false,

	firstSourceLanguage : null,
	sourceLanguage : null,
	targetLanguage : null,

	initialize : function() {
		LightPopupPanelPlus.prototype.initialize.apply(this, arguments);
	},

	initEventListeners : function() {
		this.stopEventObserving();
		this.events = new Hash();

		LightPopupPanelPlus.prototype.initEventListeners.apply(this, arguments);

		this.addEvent('closeClicked', this.CLOSE_ID, 'click', this.closeClicked.bindAsEventListener(this));
		this.addEvent('xClicked', this.X_ID, 'click', this.closeClicked.bindAsEventListener(this));

		this.addEvent('sourceLanguageChanged', this.SOURCE_ID, 'change', this.sourceLanguageChanged.bindAsEventListener(this));
		this.addEvent('targetLanguageChanged', this.TARGET_ID, 'change', this.targetLanguageChanged.bindAsEventListener(this));

		$$('.' + this.EDITABLE_CELL_CLASS).each(function(element, i){
			this.addEvent('contentsClicked' + i, element, 'click', this.contentsClicked.bindAsEventListener(this));
		}.bind(this));

		this.addEvent('addTagClicked', this.ADD_TAG_ID, 'click', this.addTagClicked.bindAsEventListener(this));

		if (this.resource) {
			this.getWords().each(function(word, i) {
				this.addEvent('deleteClicked' + i, this.getDeleteId(word.id), 'click', this.deleteClicked.bindAsEventListener(this));
				this.addEvent('saveClicked' + i, this.getSaveId(word.id), 'click', this.saveClicked.bindAsEventListener(this));
				this.addEvent('cancelClicked' + i, this.getCancelId(word.id), 'click', this.cancelClicked.bindAsEventListener(this));
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

	getWords : function() {
		return this.resource.words;
	},

	getWord : function(tagId) {
		var res = null;
		this.getWords().each(function(tag) {
			if (tag.id == tagId) {
				res = tag;
			}
		}.bind(this));
		return res;
	},

	createCategoryRow : function() {
		var html = [];
		this.getWords().each(function(word, index) {
			var source = word.expressions[this.sourceLanguage];
			var target = word.expressions[this.targetLanguage];

			if (this.changeFlag && word.id == this.editingWordId) {
				source = this.cache[this.sourceLanguage];
				target = this.cache[this.targetLanguage];
			}

			source = (!source) ? TagConfigConst.Text.BLANK : source;
			target = (!target) ? TagConfigConst.Text.BLANK : target;

			html.push(new Template(this.Templates.tagRow).evaluate({
				source : source,
				target : target,
				sourceClass : this.SOURCE_CLASS,
				targetClass : this.TARGET_CLASS,
				rowId : this.getRowId(word.id),
				unsavedId : this.getUnsavedId(word.id),
				editId : this.getEditId(word.id),
				deleteId : this.getDeleteId(word.id),
				editLabel : TagConfigConst.Text.EDIT,
				deleteLabel : TagConfigConst.Text.DELETE,
				saveCancelRowId : this.getSaveCancelRowId(word.id),
				saveId : this.getSaveId(word.id),
				cancelId : this.getCancelId(word.id),
				save : TagConfigConst.Text.SAVE,
				cancel : TagConfigConst.Text.CANCEL
			}));
		}.bind(this));
		return html.join('');
	},

	getEditId : function(wordId) {
		return 'ms-edit-id-' + wordId;
	},

	getBody : function() {
		return new Template(this.Templates.base).evaluate({
//			qa : this.resource.name,
			editMasterCategory : TagConfigConst.Text.EDIT_TAGS,
			xId : this.X_ID,
			addTagId : this.ADD_TAG_ID,
			addTag : TagConfigConst.Text.ADD_TAG,
			sourceLanguageId : this.SOURCE_ID,
			sourceClass : this.SOURCE_CLASS,
			sourceLanguage : this.createLanguageSelector(this.sourceLanguage),
			targetLanguageId : this.TARGET_ID,
			targetClass : this.TARGET_CLASS,
			targetLanguage : this.createLanguageSelector(this.targetLanguage),
			categoryRowId : this.TBODY_ID,
			categoryRow : this.createCategoryRow(),
			close : TagConfigConst.Text.CLOSE,
			closeId : this.CLOSE_ID
		});
	},

	draw : function() {
		$(this.TBODY_ID).update(this.createCategoryRow());
		if (this.changeFlag) {
			this.showSaveCancelRow(this.editingWordId);
			$(this.getUnsavedId(this.editingWordId)).show();
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
				this.cache[language[0]] = (this.editingResource.expressions[language[0]] == 'undefined' ? '' : this.editingResource.expressions[language[0]]);
			}.bind(this));
//			this.resource.languages.each(function(language){
//				this.cache[language] = Global.Words.get(this.editingWordId)[language];
//			}.bind(this));
			this.showSaveCancelRow(this.editingWordId);
			$(this.getUnsavedId(this.editingWordId)).show();
			this.changeFlag = true;
		}
		if (this.editing.hasClassName(this.SOURCE_CLASS)) {
			this.cache[this.sourceLanguage] = newValue;
		} else {
			this.cache[this.targetLanguage] = newValue;
		}
	},

	addTag : function() {
//		this.wordIds.unshift(0);
//		this.hasNew = true;
		this.newFlag = true;
		this.changeFlag = true;
		this.editingWordId = 0;

		this.cache = {};

		var obj = {id:0, expressions:{}};

		$H(TagConfigConst.Languages).each(function(language) {
			this.cache[language[0]] = '';
			obj.expressions[language[0]] = '';
		}.bind(this));
//		this.resource.languages.each(function(language){
//			this.cache[language] = Global.Words.get(this.editingWordId)[language];
//		}.bind(this));

		this.resource.words.unshift(obj);

		this.changeFlag = true;
		this.draw();
	},

	isAllEmpty : function() {
		var emptyFlag = true;

//		this.resource.languages.each(function(language){
//			if (!!this.cache[language]) {
//				emptyFlag = false;
//				throw $break;
//			}
//		}.bind(this));
		$H(TagConfigConst.Languages).each(function(language) {
			if (!!this.cache[language[0]]) {
				emptyFlag = false;
				throw $break;
			}
		}.bind(this));

		return emptyFlag;
	}
});
Object.extend(TagPanel.prototype, {

	SOURCE_ID : 'popup-tag-source',
	TARGET_ID : 'popup-tag-target',

	SAVE_DISABLED_CLASS : 'qa-common-save-disabled',
	CANCEL_DISABLED_CLASS : 'qa-common-cancel-disabled',
	CLOSE_DISABLED_CLASS : 'qa-common-close-disabled',

	SOURCE_CLASS : 'popup-tag-source-text',
	TARGET_CLASS : 'popup-tag-target-text',

	DELETE : 'popup-tag-delete-category-',

	TEXTAREA_ID : 'popup-tag-textarea',
	TBODY_ID : 'popup-tag-tbody',

	ADD_TAG_ID : 'popup-tag-add-tag-id',

	ROW : 'popup-tag-row-',
	SAVE_CANCEL_ROW : 'popup-tag-save-cancel-row-',

	DELETE_PREFIX : 'popup-tag-delete-',
	SAVE_PREFIX : 'popup-tag-save-',
	CANCEL_PREFIX : 'popup-tag-cancel-',

	UNSAVED_PREFIX : 'popup-tag-unsaved-koshiitai-',
	SAVE_ID : 'popup-tag-save',
	CANCEL_ID : 'popup-tag-cancel',

	CLOSE_ID : 'popup-tag-close',
	X_ID : 'popup-tag-x',

	NOW_EDITING_CLASS : 'popup-tag-now-editing',
	EDITABLE_CELL_CLASS : 'popup-tag-editable-cell',

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

	commitChanges : function(savedTag) {
		if (this.newFlag) {
			this.resource.words[0] = savedTag;

		} else {
			this.resource.words.each(function(word, index) {
				if (word.id == this.editingWordId) {
					this.resource.words[index] = savedTag;
				}
			}.bind(this));
		}

		this.discardChanges();
		this.draw();
	},

	discardChanges : function() {
		var deleteId = (this.newFlag) ? 0 : this.editingWordId;
		if (deleteId > 0) {
			this.hideSaveCancelRow(deleteId);
			$(this.getUnsavedId(deleteId)).hide();
		}
		this.cache = null;
		this.editing = null;
		this.editingWordId = null;
		this.changeFlag = false;
		this.newFlag = false;
	},

	contentsClicked : function(event) {
		var element = Event.element(event);
		var wordId;
		element.ancestors().each(function(e){
			if (e.tagName == 'TR') {
				wordId = e.id.replace(this.ROW, '');
				throw $break;
			}
		}.bind(this));
		if (this.changeFlag && (this.editingWordId != wordId)) {
			if (!confirm(TagConfigConst.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
				this.wordIds.shift();
				$(this.ROW + this.editingWordId).next().remove();
				$(this.ROW + this.editingWordId).next().remove();
				$(this.ROW + this.editingWordId).remove();
			} else {
//				$$('#' + this.ROW + this.editingWordId + ' td')[1].update(Global.Words.get(this.editingWordId)[this.sourceLanguage]);
//				$$('#' + this.ROW + this.editingWordId + ' td')[2].update(Global.Words.get(this.editingWordId)[this.targetLanguage]);
				$$('#' + this.ROW + this.editingWordId + ' td')[1].update(this.editingResource.expressions[this.sourceLanguage]);
				$$('#' + this.ROW + this.editingWordId + ' td')[2].update(this,editingResource.expressions[this.targetLanguage]);
			}
			this.discardChanges();
		}
		if (element.tagName == 'TEXTAREA' || element.hasClassName(this.NOW_EDITING_CLASS)) {
			return;
		}
		this.editing = element;
		this.editingWordId = wordId;
		this.editingResource = this.getWord(wordId);
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
	addTagClicked : function(event) {
		if (this.changeFlag) {
			if (!confirm(TagConfigConst.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
//				this.wordIds.shift();
				this.resource.words.shift();
				this.hasNew = false;
			}
			this.discardChanges();
			this.draw();
		}
		this.addTag();
	},
	saveClicked : function(event) {
		if (this.isSaveCancelDisabled(this.editingWordId)) {
			return;
		}

		if (this.isAllEmpty()) {
			alert(TagConfigConst.Text.ALL_EMPTY_NOT_REGISTER);
			return;
		}

		var parameters = {
			action : 'saveTag',
			newFlag : (this.newFlag) ? 1 : 0,
			tagId : this.editingWordId,
			tagSetId : this.resource.id
		};
		$H(TagConfigConst.Languages).each(function(language) {
			parameters['expressions['+language[0]+']'] = this.cache[language[0]] || '';
		}.bind(this));

		this.setSaveCancelDisabled(this.editingWordId);
//		new Ajax.Request(Global.Url.SAVE_WORD, {
		new Ajax.Request(this.Urls.SAVE_TAG, {
			postBody : Object.toQueryString(parameters),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				this.setSaveCancelAbled(this.editingWordId);
//				if (this.newFlag) {
//					this.wordIds.shift();
//					this.hasNew = false;
//					this.editingWordId = response.contents.id;
//				}
				this.commitChanges(response.contents);
			}.bind(this),
			onException : function() {			},
			onFailure : function() {
			}
		});
	},

	cancelClicked : function(event) {
		if (this.isSaveCancelDisabled(this.editingWordId)) {
			return;
		}
		if (!confirm(TagConfigConst.Text.CONFIRM_DISCARD_CHANGES)) {
			return;
		}
		if (this.newFlag) {
//			this.wordIds.shift();
			this.resource.words.shift();
			this.hasNew = false;
		}
		this.discardChanges();
		this.draw();
	},

	editClicked : function(event) {
		var wordId = this.getWordSetIdByEditId(Event.element(event).id);
		var popup = new QaEditMasterWordPopupPanel();
		popup.wordId = wordId;
		popup.resource = this.resource;
		popup.sourceLanguage = this.sourceLanguage;
		popup.targetLanguage = this.targetLanguage;
		popup.onHidePanel = function() {
			this.show();
		}.bind(this);
		popup.show();
	},

	getWordSetIdByEditId : function(editId) {
		return editId.replace('ms-edit-id-', '');
	},

	deleteClicked : function(event) {
		var wordId = Event.element(event).id.replace(this.DELETE_PREFIX, '');
		if (this.changeFlag && (this.editingWordId != wordId)) {
			if (!confirm(TagConfigConst.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
				this.wordIds.shift();
				this.hasNew = false;
				$(this.ROW + this.editingWordId).next().remove();
				$(this.ROW + this.editingWordId).next().remove();
				$(this.ROW + this.editingWordId).remove();
			} else {
//				$$('#' + this.ROW + this.editingWordId + ' td')[1].update(Global.Words.get(this.editingWordId)[this.sourceLanguage]);
//				$$('#' + this.ROW + this.editingWordId + ' td')[2].update(Global.Words.get(this.editingWordId)[this.targetLanguage]);
				$$('#' + this.ROW + this.editingWordId + ' td')[1].update(this.editingResource.expressions[this.sourceLanguage]);
				$$('#' + this.ROW + this.editingWordId + ' td')[2].update(this,editingResource.expressions[this.targetLanguage]);
			}
			this.discardChanges();
		}
		if (this.newFlag) {
			if (!confirm(TagConfigConst.Text.CONFIRM_DELETE)) {
				return;
			}
//			this.wordIds.shift();
			this.resource.words.shift();
			this.hasNew = false;
			this.discardChanges();
			this.draw();
			return;
		}
//		var count = Global.Words.get(wordId).count;
//		var message = (count > 0)
//						? Global.Text.CONFIRM_DELETE_BOUND_WORD_SET.replace('#{0}', count)
//						: Global.Text.CONFIRM_DELETE;
		if (!confirm(TagConfigConst.Text.CONFIRM_DELETE)) {
			return;
		}
		var parameter = Object.toQueryString({
			action : 'deleteTag',
			tagSetId : this.resource.id,
			tagId : wordId
		});
		new Ajax.Request(this.Urls.DELETE_TAG, {
			postBody : parameter,
			onSuccess : function() {

			}
		});

//		this.resource.wordIds.each(function(cId, i){
//			if (cId == wordId) {
//				this.resource.wordIds.splice(i, 1);
//			}
//		}.bind(this));

//		this.wordIds.each(function(cId, i){
//			if (cId == wordId) {
//				this.wordIds.splice(i, 1);
//			}
//		}.bind(this));
//
//		delete Global.WordSets.get(this.wordSetId)[wordId];

		var arrIds = 0;
		this.resource.words.each(function(word, index) {
			if (word.id == wordId) {
				arrIds = index;
			}
		}.bind(this));
		this.resource.words.splice(arrIds, 1);

		document.fire('wordSet:deleted');
		this.discardChanges();
		this.draw();
	},
//
//	getWords : function() {
//		var words = Global.WordSets.get(this.wordSetId).words;
//
//		return Object.isArray(words) ? {} : words;
//	},

	closeClicked : function(event) {
		if (this.isSaveCancelDisabled()) {
			return;
		}
		if (this.changeFlag ) {
			if (!confirm(TagConfigConst.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			if (this.newFlag) {
				this.wordIds.shift();
			}
			this.discardChanges();
			this.draw();
		}
		this.hide();
	}
});
TagPanel.prototype.Urls = {
	SAVE_TAG : './?page=ajax_tag_config',
	DELETE_TAG : './?page=ajax_tag_config'
};
TagPanel.prototype.Templates = {
	base : '<table class="qa-mastar-category-wrapper" id="qa-select-category-table">'
		+ '<tr><td><div class="float-right"><button id="#{xId}" class="qa-common-close">×</button></div></td></tr>'
		+ '<tr><td><b class="qa-common-popup-new-title">' + /*#{qa}*/ '#{editMasterCategory}</b><div class="float-right"><button id="#{addTagId}" class="qa-common-add-button"><span>#{addTag}</span></button></div></td></tr>'
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
	tagRow : '<tr id="#{rowId}"><td class="bg-gray"><span id="#{unsavedId}" style="display: none;">*</span></td><td class="popup-tag-editable-cell #{sourceClass}">#{source}</td><td class="popup-tag-editable-cell #{targetClass}">#{target}</td><td><div class="float-right"><button id="#{deleteId}" class="qa-common-delete-button"><span>#{deleteLabel}</span></button></div></td></tr>'
		+ '<tr style="display: none;" id="#{saveCancelRowId}"><td class="bg-gray"> </td><td colspan="3"><div class="float-right"><button id="#{saveId}" class="qa-common-save-button"><span>#{save}</span></button><button id="#{cancelId}" class="qa-common-cancel-button"><span>#{cancel}</span></button></div></td></tr>'
		+ '<tr class="border-line"><td colspan="4"></td></tr>'
};
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
var QaEditRecordPanel = Class.create();
Object.extend(QaEditRecordPanel.prototype, Panel.prototype);
Object.extend(QaEditRecordPanel.prototype, {

	id : null,
	index : null,

	record : null,
	unsavedRecord : null,

	stateManager : null,
	languages : null,

	parent : null,
	resource : null,
	readOnlyFlag : false,

	sourceLanguage : null,
	targetLanguage : null,

	state : null,
	editingId : null,
	contentsCache : null,

	newFlag : false,
	answersOpenFlag : false,

	colspan : 6,

	initialize : function() {
		Panel.prototype.initialize.apply(this, arguments);
		this.state = this.State.READY;
		this.stateManager = new QaEditRecordStateManager();
	},

	set : function(id, record, sourceLanguage, targetLanguage, languages) {
		this.id = id;
		this.record = record;
		this.sourceLanguage = sourceLanguage;
		this.targetLanguage = targetLanguage;
		this.languages = languages;
		this.stateManager.set(record, languages);
	},

	initEventListeners : function() {
		this.addEvent('toggleAnswersEvent', this.getToggleAnswersButtonId(), 'click', this.Event.toggleAnswersButtonClicked.bindAsEventListener(this));
		if (this.readOnlyFlag) {
			return;
		}
		this.addEvent('questionSourceClickEvent', (this.Config.Id.QUESTION_PREFIX + this.Config.Id.SOURCE_PREFIX + this.id), 'click', this.Event.contentsClicked.bindAsEventListener(this));
		this.addEvent('questionTargetClickEvent', (this.Config.Id.QUESTION_PREFIX + this.Config.Id.TARGET_PREFIX + this.id), 'click', this.Event.contentsClicked.bindAsEventListener(this));

		this.stateManager.currentRecord.answers.each(function(answer, i){
			this.addEvent('answerSourceClickEvent' + i, (this.Config.Id.ANSWER_PREFIX + this.Config.Id.SOURCE_PREFIX + i + '-' + this.id), 'click', this.Event.contentsClicked.bindAsEventListener(this));
			this.addEvent('answerTargetClickEvent' + i, (this.Config.Id.ANSWER_PREFIX + this.Config.Id.TARGET_PREFIX + i + '-' + this.id), 'click', this.Event.contentsClicked.bindAsEventListener(this));
			this.addEvent('deleteAnswerClicked' + i, this.getDeleteAnswerId(i), 'click', this.Event.deleteAnswerClicked.bindAsEventListener(this));
		}.bind(this));
		this.addEvent('categoriesClickEvent', (this.getCategoriesId()), 'click', this.Event.categoriesClicked.bindAsEventListener(this));
		this.addEvent('deleteButtonClickEvent', this.getDeleteButtonId(), 'click', this.Event.deleteQuestionButtonClicked.bindAsEventListener(this));
		this.addEvent('saveButtonClickEvent', this.getSaveButtonId(), 'click', this.Event.saveButtonClicked.bindAsEventListener(this));
		this.addEvent('cancelButtonClickEvent', this.getCancelButtonId(), 'click', this.Event.cancelButtonClicked.bindAsEventListener(this));
		this.addEvent('unsavedClicked', this.getUnsavedId(), 'click', this.Event.unsavedClicked.bindAsEventListener(this));
		this.addEvent('addAnswerClicked', this.getAddAnswerId(), 'click', this.Event.addAnswerClicked.bindAsEventListener(this));
	},

	save : function() {
		$(this.getSaveLoadingId()).show();
		this.stateManager.commitRecord();
		this.saveCancelButtonDisabled();
		
		var parameters = this.stateManager.serialized();
		Object.extend(parameters, {
			recordId : this.record.questionId,
			name : this.resource.name,
			newFlag : (this.newFlag) ? 1 : 0
		});
		
		new Ajax.Request(Global.Url.SAVE_RECORD, {
			asynchronous : false,
			postBody : Object.toQueryString(parameters),
			onSuccess : function(transport) {
				Global.recordPanel = null;
				
				var response = transport.responseText.evalJSON();
				
				this.hideSaveCancel();
				
				if (this.newFlag) {
					this.newFlag = false;
					this.record.questionId = response.contents.id;
				}
				
				this.record.answers.each(function(answer){
					if (answer.answerId == null) {
						var _a = response.contents.answers.pop();
						answer.answerId = _a.answerId;
						answer.creationDate = _a.creationDate;
					} else {
						throw $break;
					}
				}.bind(this));
				
				this.stateManager.restore();
				
				$(this.getUnsavedId()).hide();
				$(this.getSaveLoadingId()).hide();
			}.bind(this),
			onException : function() {
				;
			}
		});
	},
	
	isSaveCancelButtonDisabled : function() {
		return $(this.getSaveButtonId()).hasClassName(this.Config.ClassName.SAVE_DISABLED)
			|| $(this.getCancelButtonId()).hasClassName(this.Config.ClassName.CANCEL_DISABLED);
	},

	saveCancelButtonAbled : function() {
		$(this.getSaveButtonId()).removeClassName(this.Config.ClassName.SAVE_DISABLED);
		$(this.getCancelButtonId()).removeClassName(this.Config.ClassName.CANCEL_DISABLED);
	},
	
	saveCancelButtonDisabled : function() {
		$(this.getSaveButtonId()).addClassName(this.Config.ClassName.SAVE_DISABLED);
		$(this.getCancelButtonId()).addClassName(this.Config.ClassName.CANCEL_DISABLED);
	},
	
	discard : function() {
		$(this.getUnsavedId()).hide();
	},

	getRowSpan : function() {
		// 3 = question rows + save,cancel row
		if (this.isAnswersOpen()) {
			return 3 + this.stateManager.currentRecord.answers.length;
		} else {
			return 3;
		}
	},

	createHtml : function() {
		var html = [];
		html.push(this.createQuestionRowHtml());
		html.push(this.createControllerRowHtml());
		html.push(this.createAnswerRowsHtml());
		html.push(this.createSaveCancelRowHtml());
		html.push(this.Templates.footerLineRow);
		return html.join('');
	},

	createQuestionRowHtml : function() {
		var className = this.Config.ClassName.EDITABLE_CELL;
		var attribute = '';
		if (this.readOnlyFlag) {
			attribute = ' style="display: none;" ';
			className = '';
		}
		return new Template(this.Templates.questionRow).evaluate({
			attribute : attribute,
			editableCellClass : className,
			id : this.Config.Id.QUESTION_ROW_PREFIX + this.id,
			unsavedId : this.getUnsavedId(),
			rowSpan : this.getRowSpan(),
			sourceId : this.Config.Id.QUESTION_PREFIX + this.Config.Id.SOURCE_PREFIX + this.id,
			inSourceLanguage : this.createContentsHtml(this.sourceLanguage, this.stateManager.currentRecord.expressions[this.sourceLanguage]),
			targetId : this.Config.Id.QUESTION_PREFIX + this.Config.Id.TARGET_PREFIX + this.id,
			inTargetLanguage : this.createContentsHtml(this.targetLanguage, this.stateManager.currentRecord.expressions[this.targetLanguage]),
			categoriesId : this.getCategoriesId(),
			categories : this.createCategoryHtml(),
			deleteClassName : this.Config.ClassName.DELETE_BUTTON,
			deleteButtonId : this.getDeleteButtonId(),
			deleteLabel : Global.Text.DELETE
		});
	},

	createContentsHtml : function(language, aContents) {
		var contents = '';
		if (!aContents) {
			contents = Global.Text.BLANK;
		} else {
			contents = aContents;
		}
		return contents;
	},
	
	createControllerRowHtml : function() {
		var html = [];
		html.push(new Template(this.Templates.controllerRow).evaluate({
			answers : Global.Text.ANSWERS_WITH_RESUTLS.replace('#{0}', this.stateManager.currentRecord.answers.length),
			id : this.getToggleAnswersButtonId(),
			toggleAnswerClassName : this.Config.ClassName.TOGGLE_ANSWER_BUTTON + ' ' + Global.ClassName.CLICKABLE_TEXT,
			addAnswerId : this.getAddAnswerId(),
			addAnswerClassName : this.Config.ClassName.ADD_ANSWER,
			addAnswer : Global.Text.ADD_ANSWER
		}));
		return html.join('');
	},
	
	createAnswerRowsHtml : function() {
		var attribute = '';
		var html = [];
		var className = this.Config.ClassName.EDITABLE_CELL;
		if (this.readOnlyFlag) {
			attribute = ' style="display: none;" ';
			className = '';
		}
		this.stateManager.currentRecord.answers.each(function(answer, i){
			html.push(new Template(this.Templates.answerRow).evaluate({
				attribute : attribute,
				editableCellClass : className,
				id : this.getAnswerId(i),
				sourceId : this.Config.Id.ANSWER_PREFIX + this.Config.Id.SOURCE_PREFIX  + i + '-' + this.id,
				inSourceLanguage : this.createContentsHtml(this.sourceLanguage, answer[this.sourceLanguage]),
				targetId : this.Config.Id.ANSWER_PREFIX + this.Config.Id.TARGET_PREFIX  + i + '-' + this.id,
				inTargetLanguage : this.createContentsHtml(this.targetLanguage, answer[this.targetLanguage]),
				deleteController : Global.Text.DELETE,
				deleteClassName : this.Config.ClassName.DELETE_BUTTON,
				deleteId : this.getDeleteAnswerId(i),
				deleteLabel : Global.Text.DELETE
			}));
		}.bind(this));
		return html.join('');
	},

	createSaveCancelRowHtml : function() {
		return new Template(this.Templates.saveCancelRow).evaluate({
			rowId : this.getSaveCancelRowId(),
			id : this.getSaveCancelCellId(),
			saveLoading : this.getSaveLoadingId(),
			loading : Global.Image.LOADING,
			saveId : this.getSaveButtonId(),
			save : Global.Text.SAVE,
			cancelId : this.getCancelButtonId(),
			cancel : Global.Text.CANCEL
		});
	},

	createCategoryHtml : function() {
		var html = [];

		this.stateManager.currentRecord.categoryIds.each(function(categoryId){
			if (this.resource.categoryIds.indexOf(categoryId) != -1) {
				html.push(Global.Categories.getName(categoryId, this.sourceLanguage));
			}
		}.bind(this));

		return new Template(Global.Templates.DIV).evaluate({
			id : this.getCategoriesId(),
			contents : (html.length == 0) ? Global.Text.BLANK : html.join(', ')
		});
	},

	isAnswersOpen : function() {
		return this.answersOpenFlag;
	},
	
	discardChange : function() {
		if (this.newFlag) {
			this.parent.deleteNewRecord();
		} else {
			this.stateManager.restore();
			this.draw();
		}
	},

	deleteAnswers : function() {
		for (var i = 0; i < 100; i++) {
			var answerRow = $(this.getAnswerId(i));
			if (!answerRow) {
				break;
			}
			answerRow.remove();
		}
	},

	showAnswers : function() {
		this.stateManager.currentRecord.answers.each(function(answer, i){
			$(this.getAnswerId(i)).show();
		}.bind(this));
		if (!this.readOnlyFlag) {
			$(this.getAddAnswerId()).show();
		}
		this.answersOpenFlag = true;
		$(this.getToggleAnswersButtonId()).addClassName(this.Config.ClassName.ANSWER_OPEND);
		$$('#' + this.Config.Id.QUESTION_ROW_PREFIX + this.id + ' td')[0].rowSpan = this.getRowSpan();
	},

	hideAnswers : function() {
		if (this.stateManager.isAnswersChanged() && !confirm(Global.Text.DO_YOU_DISCARD_ANSWERS)) {
			return;
		}
		this.stateManager.restoreAnswers();
		this.answersOpenFlag = false;
		$(this.getToggleAnswersButtonId()).removeClassName(this.Config.ClassName.ANSWER_OPEND);
		this.draw();
	},

	showSaveCancel : function() {
		$(this.getSaveCancelCellId()).show();
		this.saveCancelButtonAbled();
	},
	
	hideSaveCancel : function() {
		$(this.getSaveCancelCellId()).hide();
	},
	
	store : function() {
		this.stateManager.currentRecord.answers.each(function(answer, i){
			$(this.getDeleteAnswerId(i)).store('index', i);
		}.bind(this));
	},
	
	getAnswersCount : function() {
		return this.stateManager.currentRecord.answers.length;
	},

	/**
	 * 全体のdraw、tbody内にupdate
	 */
	draw : function() {
		this.stopEventObserving();
		this.update(this.createHtml());
		if (this.isAnswersOpen()) {
			this.showAnswers();
		}
		if (this.stateManager.isChanged()) {
			this.setUnsaved();
			Global.recordPanel = this;
		} else {
			Global.recordPanel = null;
		}
		this.store();
		this.initEventListeners();
		this.startEventObserving();
	},
	
	setUnsaved : function() {
		$(this.getUnsavedId()).show();
		this.showSaveCancel();
	},

	isEditing : function() {
		return (this.state == this.State.EDIT);
	},
	
	isUnsaved : function() {
		return (this.state == this.State.UNSAVED);
	},
	
	getQuestionSourceId : function() {
		return (this.Config.Id.QUESTION_PREFIX + this.Config.Id.SOURCE_PREFIX + this.id);
	},
	
	getQuestionTargetId : function() {
		return this.Config.Id.QUESTION_PREFIX + this.Config.Id.TARGET_PREFIX + this.id;
	},
	
	getDeleteAnswerId : function(i) {
		return this.Config.Id.DELETE_ANSWER_BUTTON_PREFIX + i + this.id;
	},
	
	getAddAnswerId : function() {
		return this.Config.Id.ADD_ANSWER_PREFIX + this.id;
	},
	
	getUnsavedId : function() {
		return this.Config.Id.UNSAVED_PREFIX + this.id;
	},
	
	getSaveCancelCellId : function() {
		return this.Config.Id.SAVE_CANCEL_CELL_PREFIX + this.id;
	},
	
	getSaveCancelRowId : function() {
		return this.Config.Id.SAVE_CANCEL_ROW_PREFIX + this.id;
	},
	
	getSaveButtonId : function() {
		return this.Config.Id.SAVE_BUTTON_PREFIX + this.id;
	},
	
	getCancelButtonId : function() {
		return this.Config.Id.CANCEL_BUTTON_PREFIX + this.id;
	},
	
	getToggleAnswersButtonId : function() {
		return this.Config.Id.TOGGLE_ANSWERS_BUTTON_PREFIX + this.id;
	},
	
	getAnswerId : function(i) {
		return this.Config.Id.ANSWER_PREFIX + i + '-' + this.id;
	},
	
	getCategoriesId : function() {
		return this.Config.Id.CATEGORIES_PREFIX + this.id;
	},
	
	getDeleteButtonId : function() {
		return this.Config.Id.DELETE_BUTTON_PREFIX + this.id;
	},
	
	getTextareaId : function() {
		return this.Config.Id.SINGLETON_TEXTAREA;
	},

	getIndexById : function(id) {
		var index = 0;
		if (this.getTypeById(id) == this.stateManager.Type.ANSWER) {
			return id.replace(this.Config.Id.ANSWER_PREFIX, '')
					.replace(this.Config.Id.TARGET_PREFIX, '')
					.replace(this.Config.Id.SOURCE_PREFIX, '')
					.replace(this.id, '').replace('-', '');
		}
	},

	getSaveLoadingId : function() {
		return this.Config.Id.SAVE_LOADING_PREFIX + this.id;
	},
	
	checkPanel : function() {
		if (Global.editingRecordPanel != this) {
			if (confirm) {
				return false;
			}
		}
		Global.editingRecordPanel = this;
	},

	getTypeById : function(id) {
		var type = this.stateManager.Type.QUESTION;
		if (id.match(this.Config.Id.ANSWER_PREFIX)) {
			type = this.stateManager.Type.ANSWER;
		}
		return type;
	},

	getLanguageById : function(id) {
		var language = this.sourceLanguage;
		if (id.match(this.Config.Id.TARGET_PREFIX)) {
			language = this.targetLanguage;
		}
		return language;
	},

	contentsEdited : function() {
		var newValue = $F(this.getTextareaId());
		var oldValue = $(this.getTextareaId()).retrieve('value');
		if (newValue == oldValue) {
			return;
		}
		var type = this.getTypeById(this.editingId);
		var language = this.getLanguageById(this.editingId);
		this.stateManager.setChangedParameter(
				type
				, language
				, this.stateManager.Operation.CHANGE
		);
		switch (type) {
		case this.stateManager.Type.QUESTION:
			this.stateManager.currentRecord.expressions[language] = newValue;
			break;
		case this.stateManager.Type.ANSWER:
			var index = this.getIndexById(this.editingId);
			this.stateManager.currentRecord.answers[index][language] = newValue;
			break;
		}
		Global.recordPanel = this;
		this.setUnsaved();
	},
	
	hide : function() {
		if (Global.recordPanel == this) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		$(this.id).hide();
	},
	
	show : function() {
		$(this.id).show();
	}
});
QaEditRecordPanel.prototype.Config = {
	Id : {
		SINGLETON_TEXTAREA : 'qa-edit-record-singleton-textarea',
		QUESTION_PREFIX : 'qa-edit-record-question-',
		ANSWER_PREFIX : 'qa-edit-record-answer-',
		SOURCE_PREFIX : 'qa-edit-record-source-',
		TARGET_PREFIX : 'qa-edit-record-target-',
		CATEGORIES_PREFIX : 'qa-edit-record-edit-category-',
		QUESTION_ROW_PREFIX : 'qa-edit-record-question-row-',
		TOGGLE_ANSWERS_BUTTON_PREFIX : 'qa-edit-record-toggle-answers-button-',
		DELETE_BUTTON_PREFIX : 'qa-edit-record-delete-record-button-',
		DELETE_ANSWER_BUTTON_PREFIX : 'qa-edit-record-delete-answer-record-button-',
		SAVE_LOADING_PREFIX : 'qa-edit-record-save-loading-',
		SAVE_CANCEL_ROW_PREFIX : 'qa-edit-record-save-cancel-row-',
		SAVE_CANCEL_CELL_PREFIX : 'qa-edit-record-save-cancel-cell-',
		SAVE_BUTTON_PREFIX : 'qa-edit-record-save-button-',
		CANCEL_BUTTON_PREFIX : 'qa-edit-record-cancel-button-',
		UNSAVED_PREFIX : 'qa-edit-record-unsaved-',
		ADD_ANSWER_PREFIX: 'qa-edit-record-add-answer-'
	},
	ClassName : {
		SAVE_DISABLED : 'qa-common-save-disabled',
		CANCEL_DISABLED : 'qa-common-cancel-disabled',
		EDITABLE_CELL : 'qa-edit-record-editable-cell',
		DELETE_BUTTON : 'qa-common-delete-button',
		ADD_ANSWER : 'qa-edit-record-add-answer',
		TOGGLE_ANSWER_BUTTON : 'qa-edit-record-toggle-answer-button',
		ANSWER_OPEND : 'qa-edit-record-answer-opend',
		NOW_EDITING : 'qa-edit-record-now-editing'
	}
};
QaEditRecordPanel.prototype.State = {
	READY : 0,
	EDIT : 10,
	UNSAVED : 20
};
QaEditRecordPanel.prototype.Event = {
	contentsClicked : function(event) {
		if (!!Global.recordPanel && Global.recordPanel != this) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		var element = Event.element(event);
		if (element.tagName == 'TEXTAREA' || element.hasClassName(this.Config.ClassName.NOW_EDITING)) {
			return;
		}
		this.editingId = element.id;
		var value = element.innerHTML;
		if (value == Global.Text.BLANK) {
			value = '';
		}
		var dimensions = element.getDimensions();
		element.update('<textarea style="width: '+(dimensions.width-22)+'px; height: 50px;" id="' + this.getTextareaId() + '">' + value + '</textarea>');
		$(this.getTextareaId()).focus();
		$(this.getTextareaId()).store({
			type : this.getLanguageById(this.editingId),
			index : this.getIndexById(this.editingId),
			value : value,
			language : this.getTypeById(this.editingId)
		});
		$(this.getTextareaId()).observe('blur', this.Event.onBlurContents.bindAsEventListener(this));
		$(this.getTextareaId()).observe('keyup', this.Event.onChangeContents.bindAsEventListener(this));
		element.addClassName(this.Config.ClassName.NOW_EDITING);
		element.removeClassName(this.Config.ClassName.EDITABLE_CELL);
	},

	onChangeContents : function(event) {
		this.contentsEdited();
	},

	onBlurContents : function(event) {
		this.contentsEdited();
		var value = $F(this.getTextareaId()).escapeHTML();
		if (value == '') {
			value = Global.Text.BLANK;
		}
		$(this.editingId).update(value);
		$(this.editingId).removeClassName(this.Config.ClassName.NOW_EDITING);
		$(this.editingId).addClassName(this.Config.ClassName.EDITABLE_CELL);
		this.contentsCache = null;
		this.editingId = null;
	},
	
	categoriesClicked : function(event) {
		if (!!Global.recordPanel && Global.recordPanel != this) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		var element = Event.element(event);
		var popup = new QaEditSelectCategoryPopupPanel();
		popup.resource = this.resource;
		popup.language = this.sourceLanguage;
		popup.record = this.stateManager.currentRecord;
		popup.checkedIds = this.stateManager.currentRecord.categoryIds;
		popup.recordPanel = this;
		popup.stateManager = this.stateManager;
		popup.onHidePanel = this.draw.bind(this);
		popup.show();
	},

	toggleAnswersButtonClicked : function(event) {
		if (!this.isAnswersOpen()) {
			this.showAnswers();
			return;
		}
		this.hideAnswers();
	},

	addAnswerClicked : function(event) {
		if (!!Global.recordPanel && Global.recordPanel != this) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		this.stateManager.addAnswer();
		Global.recordPanel = this;
		this.setUnsaved();
		this.stateManager.setChangedParameter(
				this.stateManager.Type.ANSWER
				, null
				, this.stateManager.Operation.ADD
		);
		this.draw();
	},
	
	deleteAnswerClicked : function(event) {
		if (!!Global.recordPanel && Global.recordPanel != this) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		if (!confirm(Global.Text.CONFIRM_DELETE_ANSWER_ROW)) {
			return;
		}
		Global.recordPanel = this;
		this.stateManager.deleteAnswer(Event.element(event).retrieve('index'));
		this.stateManager.setChangedParameter(
				this.stateManager.Type.ANSWER
				, null
				, this.stateManager.Operation.DELETE
		);
		this.setUnsaved();
		this.draw();
	},

	unsavedClicked : function(event) {
		var popup = new QaEditRecordUnsavedPopupPanel();
		popup.stateManager = this.stateManager;
		popup.show();
	},

	deleteQuestionButtonClicked : function(event) {
		if (!!Global.recordPanel) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		var element = Event.element(event);
		var message = Global.Text.CONFIRM_DELETE;
		if (this.getAnswersCount() > 0) {
			message = Global.Text.CONFIRM_DELETE_QUESTION.replace('#{0}', this.getAnswersCount());
		}
		if (!confirm(message)) {
			return;
		}
		if (this.newFlag) {
			this.parent.deleteNewRecord();
			return;
		}
		new Ajax.Request(Global.Url.DELETE_RECORD, {
			postBody : Object.toQueryString({
				questionId : this.record.questionId,
				name : this.resource.name
			}),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
			}.bind(this),
			onException : function() {
				
			},
			onFailure : function() {
				
			}
		});
		Global.recordPanel = null;
		this.parent.deleteRecord(this.id);
	},
	saveButtonClicked : function(event) {
		if (this.isSaveCancelButtonDisabled()) {
			return;
		}
		this.save();
	},
	cancelButtonClicked : function(event) {
		if (this.isSaveCancelButtonDisabled()) {
			return;
		}
		if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
			return;
		}
		if (this.newFlag) {
			this.parent.deleteNewRecord();
		} else {
			this.discardChange();
		}
	}
};
QaEditRecordPanel.prototype.Templates = {
	questionRow : '<tr id="#{id}">'
		+ '<td class="qa-edit-record-question-row" rowspan="#{rowSpan}">' + Global.Text.Q + '<span class="qa-common-clickable" id="#{unsavedId}" style="display: none;">*</span></td>'
		+ '<td colspan="2" style="width:30%;" class="#{editableCellClass}" colspan="2" id="#{sourceId}">#{inSourceLanguage}</td>'
		+ '<td style="width:27%" class="#{editableCellClass}" id="#{targetId}">#{inTargetLanguage}</td>'
		+ '<td style="width:25%" class="#{editableCellClass}" id="#{categoriesId}">#{categories}</td>'
		+ '<td style="width:15%"><button #{attribute} id="#{deleteButtonId}" class="#{deleteClassName}"><span>#{deleteLabel}</span></button></td>'
		+ '</tr>',
	controllerRow : '<tr><td colspan="4">'
		+ '<span id="#{id}" class="#{toggleAnswerClassName}">#{answers}</span> '
		+ '<button id="#{addAnswerId}" class="#{addAnswerClassName}" style="display: none;"><span>#{addAnswer}</span></button>'
		+ '</td></tr>',
	answerRow : '<tr id="#{id}" class="qa-edit-record-answer-tr" style="display: none;">'
		+ '<td style="width:2%;" class="qa-edit-record-answer-row">' + Global.Text.A + '</td>'
		+ '<td style="min-width: 200px;width:28%;" id="#{sourceId}" class="#{editableCellClass}">#{inSourceLanguage}</td>'
		+ '<td style="width:27%" id="#{targetId}" class="#{editableCellClass}">#{inTargetLanguage}</td>'
		+ '<td style="width:25%"></td>'
		+ '<td style="width:15%"><button class="#{deleteClassName}" id="#{deleteId}" #{attribute}><span>#{deleteLabel}</span></button></td>'
		+ '</tr>',
	saveCancelRow : '<tr id="#{rowId}"><td id="#{id}" style="display: none;" colspan="6">'
		+ '<div class="float-right">'
		+ '<span style="display: none;" id="#{saveLoading}">#{loading}</span>'
		+ '<button class="qa-edit-record-save-button" id="#{saveId}"><span>#{save}</span></button>'
		+ '<button class="qa-edit-record-cancel-button" id="#{cancelId}"><span>#{cancel}</span></button>'
		+ '</div>'
		+ '</td></tr>',
	footerLineRow : '<tr class="qa-edit-record-footer-line"><td colspan="6"></td></tr>'
};
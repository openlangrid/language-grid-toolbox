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
var QaSearchRecordPanel = Class.create();
Object.extend(QaSearchRecordPanel.prototype, QaEditRecordPanel.prototype);
Object.extend(QaSearchRecordPanel.prototype, {

	selectedParameters : null,

	initialize : function() {
		QaEditRecordPanel.prototype.initialize.apply(this, arguments);
		this.selectedParameters = new Hash({});
	},

	initEventListeners : function() {
		QaEditRecordPanel.prototype.initEventListeners.apply(this, arguments);

		this.getIndexes().each(function(index){
			this.addEvent('boundWordChangedOnChange' + index, $(this.getObservableId(index)), 'change', this.boundWordChanged.bindAsEventListener(this));
			this.addEvent('boundWordChangedOnKeypress' + index, $(this.getObservableId(index)), 'keypress', this.boundWordChanged.bindAsEventListener(this));
			this.addEvent('boundWordChangedOnKeydown' + index, $(this.getObservableId(index)), 'keydown', this.boundWordChanged.bindAsEventListener(this));
			this.addEvent('boundWordChangedOnKeyup' + index, $(this.getObservableId(index)), 'keyup', this.boundWordChanged.bindAsEventListener(this));
		}.bind(this));
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
			inSourceLanguage : this.createContentsHtml(this.sourceLanguage, this.stateManager.currentRecord.expressions[this.sourceLanguage], true),
			targetId : this.Config.Id.QUESTION_PREFIX + this.Config.Id.TARGET_PREFIX + this.id,
			inTargetLanguage : this.createContentsHtml(this.targetLanguage, this.stateManager.currentRecord.expressions[this.targetLanguage]),
			categoriesId : this.getCategoriesId(),
			categories : this.createCategoryHtml(),
			paramtersId : this.getParametersId(),
			parameters : this.createParameterHtml(),
			deleteClassName : this.Config.ClassName.DELETE_BUTTON,
			deleteButtonId : this.getDeleteButtonId(),
			deleteLabel : Global.Text.DELETE
		});
	},

	createContentsHtml : function(language, aContents, leftFrag) {
		var contents = '';
		if (this.languages.indexOf(language) == -1) {
			contents =Global.Text.NOT_SUPPORTED_LANGUAGE_EXPRESSION;
		} else if (!aContents) {
			contents = Global.Text.BLANK;
		} else {
			contents = aContents;
		}

		if (leftFrag) {
			return this.createObservableContents(contents);
		} else {
			return this.createObserverContents(contents);
		}
	},

	getObservableId : function(index) {
		return this.id + '-observable-' + index;
	},

	getObserverId : function(index) {
		return this.id + '-observer-' + index;
	},

	getTypeByIndex : function(index) {
		var parameterId = this.record.parameterIds[index];
		var wordSets = Global.WordSets.get(parameterId);

		if ((!parameterId) || (this.resource.wordSetIds.indexOf(parameterId) == -1) || (!wordSets)) {
			return 'text';
		}

		return wordSets.type;
	},

	createObservableContents : function(contents) {
		var contents = contents.sub(/\[([0-9]+)\]/, function(match) {
			var index = match[1];
			var type = this.getTypeByIndex(index);

			if (type == 'text' || type == 'number') {
				return this.buildTextarea(index);
			} else {
				return this.buildSelector(index);
			}
		}.bind(this), 30);

		return contents;
	},

	createObserverContents : function(contents) {
		var contents = contents.sub(/\[([0-9]+)\]/, function(match){
			var index = match[1];
			var value = this.getTargetValue(index);
			return '<span id="' + this.getObserverId(index) + '">' + (value || '') + '</span>';
		}.bind(this), 30);

		return contents;
	},

	buildTextarea : function(index) {
		var def = this.selectedParameters.get(index);

		if (!def) {
			this.selectedParameters.set(index, '');
			def = '';
		}

		return '<input id="' + this.getObservableId(index) + '" value="' + def + '"></input>';
	},

	buildSelector : function(index) {
		var list = [];
		var wordSets, wordSetId;

		switch(this.getTypeByIndex(index)) {
		case 'month':
			list = $A($R(1, 12));
			list.unshift('month');
			break;
		case 'date':
			list = $A($R(1, 31));
			list.unshift('date');
			break;
		case 'hour':
			list = $A($R(0, 23));
			list.unshift('hour');
			break;
		case 'minute':
			list = $A($R(0, 59));
			list.unshift('minute');
			break;
		case 'enum':
			wordSetId = this.record.parameterIds[index];
			wordIds = Object.keys(Global.WordSets.get(wordSetId).words);
			list.push(Global.WordSets.getName(wordSetId, this.sourceLanguage));
			wordIds.each(function(wordId){
				list.push(Global.Words.getName(wordId, this.sourceLanguage));
			}.bind(this));
			break;
		}

		var def = this.selectedParameters.get(index);

		if (!def) {
			def = list[0] || '';
			this.selectedParameters.set(index, def);
		}

		var html = [];
		html.push('<select id="' + this.getObservableId(index) +'">');
		list.each(function(val){
			if (val == def) {
				html.push('<option selected="selected" value="' + val + '">' + val + '</option>');
			} else {
				html.push('<option value="' + val + '">' + val + '</option>');
			}
		}.bind(this));
		html.push('</select>');

		return html.join('');
	},

	getIndexes : function() {
		var indexes = [];

		var contents = this.stateManager.currentRecord.expressions[this.sourceLanguage] || '';

		var maches = contents.match(/\[([0-9]+)\]/g);

		if (!maches || !maches.length) return [];

		maches.each(function(match){
		    indexes.push(match.match(/[0-9]+/));
		}.bind(this));

		return indexes;
	},

	getIndexByObservableId : function(id) {
		return id.replace(this.id + '-observable-', '');
	},

	getTargetValue : function(index) {
		var value = this.selectedParameters.get(index);
		var type = this.getTypeByIndex(index);
		var parameterId = this.record.parameterIds[index];

		if (type == 'enum') {
			var words = Global.WordSets.get(parameterId).words;

			if (value == Global.WordSets.getName(parameterId, this.sourceLanguage)) {
				value = Global.WordSets.getName(parameterId, this.targetLanguage);
			} else {
				words = (Object.isArray(words)) ? {} : words;
				$H(words).each(function(pair) {
					if (pair.value[this.sourceLanguage] == value) {
						value = pair.value[this.targetLanguage];
						throw $break;
					}
				}.bind(this));
			}
		}

		return value;
	},

	boundWordChanged : function(event) {
		var element = Event.element(event);
		var index = this.getIndexByObservableId(element.id);

		var observer = $(this.getObserverId(index)) || null;
		this.selectedParameters.set(index, element.value);

		if (!observer) return;

		observer.innerHTML = this.getTargetValue(index);
	}
});
QaSearchRecordPanel.prototype.Templates = {
	questionRow : '<tr id="#{id}">'
		+ '<td style="display: none;" class="qa-edit-record-question-row" rowspan="#{rowSpan}">' + Global.Text.Q + '<span class="qa-common-clickable" id="#{unsavedId}" style="display: none;">*</span></td>'
		+ '<td width="23%" class="#{editableCellClass}" id="#{sourceId}">#{inSourceLanguage}</td>'
		+ '<td width="23%" class="#{editableCellClass}" id="#{targetId}">#{inTargetLanguage}</td>'
		+ '<td width="23%" class="#{editableCellClass}" id="#{categoriesId}">#{categories}</td>'
		+ '<td width="23%" class="#{editableCellClass}" id="#{parametersId}" style="display: none;">#{parameters}</td>'
		+ '<td><button #{attribute} id="#{deleteButtonId}" class="#{deleteClassName}"><span>#{deleteLabel}</span></button></td>'
		+ '</tr>',
	controllerRow : '<tr style="display: none;"><td colspan="5">'
		+ '<span id="#{id}" class="#{toggleAnswerClassName}">#{answers}</span> '
		+ '<button id="#{addAnswerId}" class="#{addAnswerClassName}" style="display: none;"><span>#{addAnswer}</span></button>'
		+ '</td></tr>',
	answerRow : '<tr id="#{id}" class="qa-edit-record-answer-tr" style="display: none;">'
		+ '<td class="qa-edit-record-answer-row">' + Global.Text.A + '</td>'
		+ '<td style="min-width: 200px;" id="#{sourceId}" class="#{editableCellClass}">#{inSourceLanguage}</td>'
		+ '<td width="27%" id="#{targetId}" class="#{editableCellClass}">#{inTargetLanguage}</td>'
		+ '<td width="27%"></td>'
		+ '<td><button class="#{deleteClassName}" id="#{deleteId}" #{attribute}><span>#{deleteLabel}</span></button></td>'
		+ '</tr>',
	saveCancelRow : '<tr id="#{rowId}">'
		+ '<td><button style="display: none;" id="#{insertLeftId}" class="qa-common-blue-button"><span>' + Global.Text.INSERT_A_NEW_PARAMETER + '</span></button></td>'
		+ '<td><button style="display: none;" id="#{insertRightId}" class="qa-common-blue-button"><span>' + Global.Text.INSERT_A_NEW_PARAMETER + '</span></button></td>'
		+ '<td id="#{id}" style="display: none;" colspan="3">'
		+ '<div class="float-right">'
		+ '<span style="display: none;" id="#{saveLoading}">#{loading}</span>'
		+ '<button class="qa-edit-record-save-button" id="#{saveId}"><span>#{save}</span></button>'
		+ '<button class="qa-edit-record-cancel-button" id="#{cancelId}"><span>#{cancel}</span></button>'
		+ '</div>'
		+ '</td></tr>',
	footerLineRow : '<tr class="qa-edit-record-footer-line"><td colspan="6"></td></tr>'
};
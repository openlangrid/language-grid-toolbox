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
var QaEditRecordStateManager = Class.create();
Object.extend(QaEditRecordStateManager.prototype, {

	record : null,
	currentRecord : null,
	
	languages : null,
	
	sourceLanguage : null,
	targetLanguage : null,

	changedParameters : null,

	contentsCache : null,

	initialize : function() {
		this.currentRecord = {};
		this.changedParameters = new Hash({});
	},

	set : function(record, languages) {
		this.record = record;
		this.languages = languages;
		this.restore();
	},
	
	setNew : function() {
		this.changedParameters = new Hash({});
		this.setChangedParameter(
				this.Type.NEW
				, null
				, this.Operation.NEW
		);
	},
	
	commitRecord : function() {
		this.changedParameters = new Hash({});
//		this.record = {};
		for (var key in this.record) {
			delete this.record[key];
		}
		this.record.questionId = this.currentRecord.questionId;
		this.record.categoryIds = this.currentRecord.categoryIds.clone();
		this.record.expressions = Object.clone(this.currentRecord.expressions);

		this.record.answers = [];
		this.currentRecord.answers.each(function(answer){
			this.record.answers.push(Object.clone(answer));
		}.bind(this));
	},

	restore : function() {
		this.changedParameters = new Hash({});
//		this.currentRecord = {};
		for (var key in this.currentRecord) {
			delete this.currentRecord[key];
		}
		this.currentRecord.questionId = this.record.questionId;
		this.currentRecord.categoryIds = this.record.categoryIds.clone();
		this.currentRecord.expressions = Object.clone(this.record.expressions);
		this.restoreAnswers();
	},

	serialized : function() {
		var parameters = {
			recordId : this.currentRecord.questionId
		};
		
		this.currentRecord.categoryIds.each(function(cId, i){
			parameters['categoryIds[' + i + ']'] = cId;
		});
		
		this.languages.each(function(language){
			parameters['question[' + language + ']'] = this.currentRecord.expressions[language] || '';
		}.bind(this));
		
		this.currentRecord.answers.each(function(answer, i){
			this.languages.each(function(language){
				parameters['answers[' + i + '][' + language + ']'] = answer[language] || '';
			}.bind(this));
			parameters['answers[' + i + '][answerId]'] = answer.answerId;
			parameters['answers[' + i + '][creationDate]'] = answer.creationDate || 0;
		}.bind(this));
		return parameters;
	},

	restoreAnswers : function() {
		this.currentRecord.answers = [];
		this.record.answers.each(function(answer){
			this.currentRecord.answers.push(Object.clone(answer));
		}.bind(this));

		this.changedParameters.keys().each(function(key){
			if (key.startsWith(this.Type.ANSWER)) {
				this.changedParameters.unset(key);
			}
		}.bind(this));
	},

	addAnswer : function() {
		var expressions = {};
		this.languages.each(function(language){
			expressions[language] = '';
		}.bind(this));
		expressions.answerId = null;
		expressions.creationDate = 0;
		this.currentRecord.answers.unshift(expressions);
	},

	deleteAnswer : function(i) {
		this.currentRecord.answers.splice(i, 1);
	},

	setChangedParameter : function(type, language, operation) {
		if (!language) {
			language = 'all';
		}
		this.changedParameters.set(type + '-' + language + '-' + operation, {
			type : type,
			language : language,
			operation : operation,
			
			toString : function() {
				return this.type + this.language + this.operation;
			}
		});
	},

	isAnswersChanged : function() {
		return !!this.changedParameters.find(function(pair){
			return pair.key.startsWith(this.Type.ANSWER);
		}.bind(this));
	},

	isChanged : function() {
		return this.changedParameters.size() > 0;
	}
});
QaEditRecordStateManager.prototype.Type = {
	NEW : 'NEW',
	QUESTION : 'Question',
	ANSWER : 'Answer',
	CATEGORY : 'Category'
};
QaEditRecordStateManager.prototype.Operation = {
	NEW : 'NEW',
	ADD : 'Add',
	CHANGE : 'Change',
	DELETE : 'Delete'
};
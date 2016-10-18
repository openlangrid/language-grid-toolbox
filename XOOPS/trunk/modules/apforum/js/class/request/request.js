//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
var Request = Class.create();
Request.prototype = {
	_threadId : '',
	_sourceLanguageCode : '',
	_targetLanguageCode : '',
	_sourceText : '',
	_groupCode : '',
	_backTranslationFlag : false,
	_onSuccess : function(){},

	/**
	 * Constructor
	 */
	initialize : function() {
		;
	},

	/**
	 * is
	 */
	isBackTranslation : function() {
		return !!this.getBackTranslationFlag();
	},

	/**
	 * getter/setter
	 */
	getThreadId : function() {
		return this._threadId;
	},
	setThreadId : function(threadId) {
		this._threadId = threadId;
		return this;
	},
	getSourceLanguageCode : function() {
		return this._sourceLanguageCode;
	},
	setSourceLanguageCode : function(sourceLanguageCode) {
		this._sourceLanguageCode = sourceLanguageCode;
		return this;
	},
	getTargetLanguageCode : function() {
		return this._targetLanguageCode;
	},
	setTargetLanguageCode : function(targetLanguageCode) {
		this._targetLanguageCode = targetLanguageCode;
		return this;
	},
	getSourceText : function() {
		return this._sourceText;
	},
	setSourceText : function(sourceText) {
		this._sourceText = sourceText;
		return this;
	},
	getGroupCode : function() {
		return this._groupCode;
	},
	setGroupCode : function(groupCode) {
		this._groupCode = groupCode;
		return this;
	},
	getOnSuccess : function() {
		return this._onSuccess;
	},
	setOnSuccess : function(onSuccess) {
		this._onSuccess = onSuccess;
		return this;
	},
	getBackTranslationFlag : function() {
		return this._backTranslationFlag;
	},
	setBackTranslationFlag : function(backTranslationFlag) {
		this._backTranslationFlag = backTranslationFlag;
		return this;
	}
};
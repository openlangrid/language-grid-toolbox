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

var Process = {
	id: 0,
	cancelIds: [],

	getId: function() {
		return ++this.id;
	},

	setCancel: function() {
		this.cancelIds.push(this.id);
	},

	isCanceled: function(id) {
		return (this.cancelIds.indexOf(id-0) != -1);
	}
};

/**
 * @author kitajima
 * @require Request
 * @require RequestQueue
 * @require validate
 */
var TranslationPanel = Class.create();
TranslationPanel.prototype = {
	_SOURCE_BUTTON_ID_PREFIX : 'source-button-',
	_TARGET_BUTTON_ID_PREFIX : 'target-button-',
	_BUTTON_DISABLED_CLASS_NAME : 'disabled',
	_SOURCE_TEXTAREA_ID_PREFIX : 'source-textarea-',
	_TARGET_TEXTAREA_ID_PREFIX : 'target-textarea-',
	_BACK_TRANSLATION_TEXTAREA_ID_PREFIX : 'back-translation-textarea-',
	_COMMIT_CHECKBOX_ID_PREFIX : 'commit-checkbox-',
	_SOURCE_STATUS_AREA_ID_PREFIX : 'source-status-area-',
	_TARGET_STATUS_AREA_ID_PREFIX : 'target-status-area-',
	_BACK_TRANSLATION_STATUS_AREA_ID_PREFIX : 'back-translation-status-area-',

	_NOW_TRANSLATING_MESSAGE : '',
	_NOW_WAITING_MESSAGE : '',
	_MAX_CONNECTIONS : 2,
	_SEPARATOR : '_SEPARATOR_',
	_sourceLanguageCode : '',
	_targetLanguageCodes : new Array(),
	_groups : new Array(),
	_groupCodes : new Array(),
	_requestQueue : new Array(),
	_runningThreads : new Array(),
	_targetPair : new Array(),
	_licenseArea : null,
	_$cache : new Object(),

	/**
	 * Constructor
	 */
	initialize : function(sourceLanguageCode, targetLanguageCodes, groups, groupCodes) {
		this.setSourceLanguageCode(sourceLanguageCode);
		this.setTargetLanguageCodes(targetLanguageCodes);
		this.setGroups(groups);
		this.setGroupCodes(groupCodes);

		this.licenseArea = new LicenseArea('license-information-area');

		this.initEventListener();
		this.init();

		this._NOW_TRANSLATING_MESSAGE = Const.Images.loading + ' ' + Const.Message.nowTranslating;
		this._NOW_WAITING_MESSAGE = Const.Images.loading + ' ' + Const.Message.nowTranslating;

		this._adjustHeight();
	},

	/**
	 * Adjust the height of each area
	 */
	_adjustHeight : function() {
		var heights = new Array();
		this.getGroupCodes().each(function(groupCode){
			heights.push(
				this._$(
					this._getId(
						this._SOURCE_TEXTAREA_ID_PREFIX
						, this.getSourceLanguageCode()
						, groupCode)
				).getHeight());
		}.bind(this));
		this._getTargetPairs().each(function(targetPair){
			this._$(
				this._getId(
					this._BACK_TRANSLATION_TEXTAREA_ID_PREFIX
					, targetPair.targetLanguageCode
					, targetPair.groupCode
				)
			).setStyle({
				height : heights[targetPair.groupCode] + 'px',
				maxHeight : heights[targetPair.groupCode] + 'px'
			});
		}.bind(this));
	},

	/**
	 * Each event listener to initialize
	 * TODO
	 * Many times around the loop
	 */
	initEventListener : function() {
		this._initSourceButtonEventListener();
		this._initTargetButtonEventListener();
		this._initCommitCheckboxEventListener();
		this._initCommitTextareaEventListener();
	},

	/**
	 * Set the Source button event
	 */
	_initSourceButtonEventListener : function() {
		var sourceButtonId = this._getId(this._SOURCE_BUTTON_ID_PREFIX, this.getSourceLanguageCode(), 0);
		Event.observe(sourceButtonId, 'click', this.allTranslate.bindAsEventListener(this));
	},
	/**
	 * Set a target in the event of each button
	 */
	_initTargetButtonEventListener : function() {
		this.getTargetLanguageCodes().each(function(targetLanguageCode){
			this.getGroupCodes().each(function(groupCode){
				var targetButtonId = this._getId(this._TARGET_BUTTON_ID_PREFIX, targetLanguageCode, groupCode);
				Event.observe(targetButtonId, 'click', this.backTranslate.bindAsEventListener(this));
			}.bind(this));
		}.bind(this));
	},

	/**
	 * Set a commit in the event of each checkbox
	 */
	_initCommitCheckboxEventListener : function() {
		this.getTargetLanguageCodes().each(function(targetLanguageCode){
			this.getGroupCodes().each(function(groupCode){
				var commitCheckboxId = this._getId(this._COMMIT_CHECKBOX_ID_PREFIX, targetLanguageCode, groupCode)
				Event.observe(commitCheckboxId, 'change', this._commitCheckEvent.bindAsEventListener(this, targetLanguageCode, groupCode));
				this._doCommitCheck(targetLanguageCode, groupCode);
				this._doChangeTextarea(targetLanguageCode, groupCode);
			}.bind(this));
		}.bind(this));
	},

	/**
	 * Set a target in the event of each button
	 */
	_initCommitTextareaEventListener : function() {
		this.getTargetLanguageCodes().each(function(targetLanguageCode){
			this.getGroupCodes().each(function(groupCode){
				var targeTextareaId = this._getId(this._TARGET_TEXTAREA_ID_PREFIX, targetLanguageCode, groupCode);
				Event.observe(targeTextareaId, 'keyup', this._targetTextChangeEvent.bindAsEventListener(this, targetLanguageCode, groupCode));
			}.bind(this));
		}.bind(this));
	},

	/**
	 * Commit check box event listeners
	 */
	_commitCheckEvent : function(event, targetLanguageCode, groupCode){
		Event.stop(event);
		this._doCommitCheck(targetLanguageCode, groupCode);
	},
	/**
	 * Commit the actual processing of Check
	 */
	_doCommitCheck : function(targetLanguageCode, groupCode) {
		if (this.isRunning()) {
			return;
		}
		var commitCheckboxId = this._getId(this._COMMIT_CHECKBOX_ID_PREFIX, targetLanguageCode, groupCode);
		var targetButtonId = this._getId(this._TARGET_BUTTON_ID_PREFIX, targetLanguageCode, groupCode);
		if (!!$(commitCheckboxId).checked) {
			$(targetButtonId).addClassName(this._BUTTON_DISABLED_CLASS_NAME);
		} else {
			$(targetButtonId).removeClassName(this._BUTTON_DISABLED_CLASS_NAME);
		}

		var allCommit = true;
		this._getTargetPairs().each(function(targetPair){
			var commitCheckboxId = this._getId(this._COMMIT_CHECKBOX_ID_PREFIX, targetPair.targetLanguageCode, targetPair.groupCode);
			if (!$(commitCheckboxId).checked) {
				allCommit = false;
				throw $break;
			}
		}.bind(this));

		if (allCommit) {
			$(this._getId(this._SOURCE_BUTTON_ID_PREFIX, this.getSourceLanguageCode(), 0)).addClassName(this._BUTTON_DISABLED_CLASS_NAME);
		} else {
			$(this._getId(this._SOURCE_BUTTON_ID_PREFIX, this.getSourceLanguageCode(), 0)).removeClassName(this._BUTTON_DISABLED_CLASS_NAME);
		}
	},
	/**
	* TextAreaChangeEvent
	**/
	_targetTextChangeEvent : function(event, targetLanguageCode, groupCode){
		this._doChangeTextarea(targetLanguageCode, groupCode);
	},
	_doChangeTextarea : function(targetLanguageCode, groupCode){
		var textarea = this._getId(this._TARGET_TEXTAREA_ID_PREFIX, targetLanguageCode, groupCode)
		var checkbox = this._getId(this._COMMIT_CHECKBOX_ID_PREFIX, targetLanguageCode, groupCode)
		if($(textarea).value == ""){
			$(checkbox).checked = false;
			$(checkbox).removeAttribute("checked");
			$(checkbox).setAttribute("disabled", "true");
		}else{
			//$(checkbox).setAttribute("checked", "true");
			$(checkbox). removeAttribute("disabled");
		}
	},

	/**
	 * When you are finished you want to initialize each request
	 */
	init : function() {
		this.setRequestQueue(new RequestQueue());
	},

	onSuccess : function(request, transportWrapper, isFinish, targetText, isError) {
		var processId = request.processId;
		if (Process.isCanceled(processId)) {
			return;
		}

		var id = this._getIdByRequest(request);
		var backId = this._getBackIdByRequest(request);

		if (request.isBackTranslation()) {
			this._$(id).innerHTML += transportWrapper.getTargetText().escapeHTML();
		} else {
			this._$(id).value += transportWrapper.getTargetText();
			this._$(backId).innerHTML += transportWrapper.getBackText();
			var LangCode = this._getLanguageCode(this._TARGET_TEXTAREA_ID_PREFIX,id);
			var GrpCode = this._getGroupCode(this._TARGET_TEXTAREA_ID_PREFIX,id);
			this._doChangeTextarea(LangCode, GrpCode);
		}
		if (!isFinish) {
			if (request.isBackTranslation()) {
				this._$(id).innerHTML += '<br />';
			} else {
				this._$(id).value += '\n';
				this._$(backId).innerHTML += '<br />';
			}
			return;
		}
		
		this.licenseArea.addLicenses(transportWrapper.getLicenses());

		if (isError) {
			alert(Const.Message.langridError);
			if (request.isBackTranslation()) {
				this._$(id).innerHTML = Const.Message.translationUnavailable;
			} else {
				this._$(id).value = Const.Message.translationUnavailable;
				this._$(backId).innerHTML = Const.Message.translationUnavailable;
			}
		}


		/**
		 * About what is done to hide the round and round
		 */
		var statusPrefix;
		var statusLanguageCode;
		if (!request.isBackTranslation()) {
			statusPrefix = this._TARGET_STATUS_AREA_ID_PREFIX;
			statusLanguageCode = request.getTargetLanguageCode();
			this._hideTranslationMessage(statusPrefix, statusLanguageCode, request.getGroupCode());
			statusPrefix = this._BACK_TRANSLATION_STATUS_AREA_ID_PREFIX;
			this._hideTranslationMessage(statusPrefix, statusLanguageCode, request.getGroupCode());
		} else {
			statusPrefix = this._BACK_TRANSLATION_STATUS_AREA_ID_PREFIX;
			statusLanguageCode = request.getSourceLanguageCode();
			this._hideTranslationMessage(statusPrefix, statusLanguageCode, request.getGroupCode());
		}

		if (this.getRequestQueue().hasRequest(request.getThreadId())) {
			var request = this.getRequestQueue().getRequest(request.getThreadId());
			/**
			 * If the request queue is Bakkutoransureshon next
			 * Add a target to the source text
			 * If, because if you go out in the translated value from the rewritten
			 * Do the native back translation
			 */
			if (request.isBackTranslation()) {
				request.setSourceText(targetText);
			}
			new SentenceTranslator(request).start();
			return;
		}
		
		this._finishThread(request.getThreadId());
	},

	/**
	 * When you start a thread called
	 */
	_startThread : function(threadId) {
		this._allButtonsAreNonusable();
		this.getRunningThreads().push(threadId);
	},

	/**
	 * Called when the thread completes
	 */
	_finishThread : function(threadId) {
		this.setRunningThreads(this.getRunningThreads().without(threadId));
		if (this.isRunning()) {
			return;
		}
		this._completeAllThreads();
	},

	/**
	 * when all threads have completed
	 */
	_completeAllThreads : function() {
		$(this._SOURCE_BUTTON_ID_PREFIX + this._sourceLanguageCode + this._SEPARATOR + '0').innerHTML = Const.Label.translate;

		this.getTargetLanguageCodes().each(function(targetLanguageCode){
			this.getGroupCodes().each(function(groupCode){
				$(this._getId(this._TARGET_BUTTON_ID_PREFIX, targetLanguageCode, groupCode)).innerHTML = Const.Label.translate;
			}.bind(this));
		}.bind(this));

		/**
		 * To hide the source of the next round
		 */
		this._hideTranslationMessage(this._SOURCE_STATUS_AREA_ID_PREFIX, this.getSourceLanguageCode(), 0);
		this._allButtonsAreUsable();
	},

	/**
	 * Enable all buttons
	 */
	_allButtonsAreUsable : function() {
		//Source button, buttons and other targets
		//First, you can use the Source button

		$(this._getId(this._SOURCE_BUTTON_ID_PREFIX, this.getSourceLanguageCode(), 0)).removeClassName(this._BUTTON_DISABLED_CLASS_NAME);

		//Button can be used to target non isCommit
		this.getTargetLanguageCodes().each(function(targetLanguageCode){
			this.getGroupCodes().each(function(groupCode){
				if (!this._isCommit(targetLanguageCode, groupCode)) {
					$(this._getId(this._TARGET_BUTTON_ID_PREFIX, targetLanguageCode, groupCode)).removeClassName(this._BUTTON_DISABLED_CLASS_NAME);
				}
			}.bind(this));
		}.bind(this));
	},
	/**
	 * Impossible to use all the buttons
	 */
	_allButtonsAreNonusable : function() {
		//Source button, buttons and other targets
		//First, you can not use the Source button

		var sourceButtonId = this._getId(this._SOURCE_BUTTON_ID_PREFIX, this.getSourceLanguageCode(), 0);
		if ($(sourceButtonId).innerHTML != Const.Label.cancel) {
			$(sourceButtonId).addClassName(this._BUTTON_DISABLED_CLASS_NAME);
		}

		//Target button to disable the non-isCommit
		this.getTargetLanguageCodes().each(function(targetLanguageCode){
			this.getGroupCodes().each(function(groupCode){
				if (!this._isCommit(targetLanguageCode, groupCode)) {
					$(this._getId(this._TARGET_BUTTON_ID_PREFIX, targetLanguageCode, groupCode)).addClassName(this._BUTTON_DISABLED_CLASS_NAME);
				}
			}.bind(this));
		}.bind(this));
	},

	doCancel: function() {
		Process.setCancel();
		this.getTargetLanguageCodes().each(function(targetLanguageCode){
			this.getGroupCodes().each(function(groupCode){
				if (!this._isCommit(targetLanguageCode, groupCode)) {
					this._hideTranslationMessage(
							this._TARGET_STATUS_AREA_ID_PREFIX
							, targetLanguageCode, groupCode);
					this._hideTranslationMessage(
							this._BACK_TRANSLATION_STATUS_AREA_ID_PREFIX
							, targetLanguageCode, groupCode);
				}
			}.bind(this));
		}.bind(this));
		this.init();
		this.setRunningThreads([]);
    },

	/**
	 * Event
	 */
	allTranslate : function(e) {
		Event.stop(e);

		/**
		 * Determine whether the button is disable
		 */
		var element = Event.element(e);
		if (element.innerHTML == Const.Label.cancel) {
			this.doCancel();
			this._completeAllThreads();
			return;
		}
		if (element.hasClassName(this._BUTTON_DISABLED_CLASS_NAME)) {
			return;
		}

		/**
		 * TODO
		 * If the processing of the source text is empty
		 * var v = new Validator();
		 * v.add(value, 'required');
		 * if (v.validate());
		 */

		var messages = new Array();
		this.getGroupCodes().each(function(groupCode){
			if (!this._$(this._getId(this._SOURCE_TEXTAREA_ID_PREFIX, this.getSourceLanguageCode(), groupCode)).value) {
				messages.push(
					Const.Message.previewSourceError.replace('%s', this.getGroups()[groupCode])
				);
			}
		}.bind(this));
		if(messages.length > 0){
			alert(messages.join("\n"));
			return;
		}

/*
		var empty = false;
		this.getGroupCodes().each(function(groupCode){
			if (!this._$(this._getId(this._SOURCE_TEXTAREA_ID_PREFIX, this.getSourceLanguageCode(), groupCode)).value) {
				empty = true;
			}
		}.bind(this));
		if (empty) {
			return;
		}
*/
		/**
		 * When you press the Translate button to translate the original next round established
		 */
		this._showTranslationMessage(
				this._SOURCE_STATUS_AREA_ID_PREFIX,
				this.getSourceLanguageCode(), 0);
		/**
		 * Show isCommit all the other round and round
		 */
		this.getTargetLanguageCodes().each(function(targetLanguageCode){
			this.getGroupCodes().each(function(groupCode){
				if (!this._isCommit(targetLanguageCode, groupCode)) {
					this._showTranslationMessage(
							this._TARGET_STATUS_AREA_ID_PREFIX
							, targetLanguageCode, groupCode);
					this._showTranslationMessage(
							this._BACK_TRANSLATION_STATUS_AREA_ID_PREFIX
							, targetLanguageCode, groupCode);
				}
			}.bind(this));
		}.bind(this));
		
		element.innerHTML = Const.Label.cancel;
		this.licenseArea.clear();
		this.doAllTranslate();
	},

	backTranslate : function(e) {
		Event.stop(e);

		/**
		 * Determine whether the button is disable
		 */
		var element = Event.element(e);

		if (element.innerHTML == Const.Label.cancel) {
			this.doCancel();
			this._completeAllThreads();
			return;
		}

		if (element.hasClassName(this._BUTTON_DISABLED_CLASS_NAME)) {
			return;
		}

		/**
		 * isCommit determine whether
		 */
		if (this._isCommit(	this._getLanguageCode(this._TARGET_BUTTON_ID_PREFIX, Event.element(e).id)
				, this._getGroupCode(this._TARGET_BUTTON_ID_PREFIX, Event.element(e).id))) {
			return;
		}

		/**
		 * If the source text is empty
		 */
		if (!this._$(this._getId(this._TARGET_TEXTAREA_ID_PREFIX
					, this._getLanguageCode(this._TARGET_BUTTON_ID_PREFIX, Event.element(e).id)
					, this._getGroupCode(this._TARGET_BUTTON_ID_PREFIX, Event.element(e).id)
					)).value) {
			return;
		}

		/**
		 * Located next to the round back translation
		 */
		this._showTranslationMessage(
				this._BACK_TRANSLATION_STATUS_AREA_ID_PREFIX,
				this._getLanguageCode(this._TARGET_BUTTON_ID_PREFIX, Event.element(e).id)
				, this._getGroupCode(this._TARGET_BUTTON_ID_PREFIX, Event.element(e).id));

		this.licenseArea.clear();
		element.innerHTML = Const.Label.cancel;

		/**
		 * Actual processing
		 */
		this.doBackTranslate(
			this._getLanguageCode(this._TARGET_BUTTON_ID_PREFIX, Event.element(e).id)
			, this._getGroupCode(this._TARGET_BUTTON_ID_PREFIX, Event.element(e).id)
		);

		element.removeClassName(this._BUTTON_DISABLED_CLASS_NAME);
	},

	_showTranslationMessage : function(prefix, languageCode, groupCode) {
		$(this._getId(prefix, languageCode, groupCode)).innerHTML = this._NOW_TRANSLATING_MESSAGE;
	},

	_hideTranslationMessage : function(prefix, languageCode, groupCode) {
		$(this._getId(prefix, languageCode, groupCode)).innerHTML = '';
	},

	/**
	 * Actual processing
	 * All translation
	 */
	doAllTranslate : function() {
		this._createRequestQueue(this.getTargetLanguageCodes(), this.getGroupCodes());

		var found = false;
		/**
		 * Multi thread
		 */
		for (var i = 0; i < this._MAX_CONNECTIONS; i++) {
			var threadId = i;
			if (this.getRequestQueue().hasRequest(threadId)) {
				found = true;
				var request = this.getRequestQueue().getRequest(threadId);
				this._startThread(threadId);
				new SentenceTranslator(request).start();
			}
		}
		if (!found) {
			this._completeAllThreads();
		}
	},

	/**
	 * Actual processing
	 * singl translation
	 * TODO
	 * I could have done better to make a unified request queue and this
	 * If you have time to think about the final
	 */
	doBackTranslate : function(targetLanguageCode, groupCode) {
		var request = new Request();
		var id = this._getId(this._TARGET_TEXTAREA_ID_PREFIX, targetLanguageCode, groupCode);
		var threadId = 0;

		/**
		 * Single thread
		 */
		request.setThreadId(threadId)
				.setSourceLanguageCode(targetLanguageCode)
				.setTargetLanguageCode(this.getSourceLanguageCode())
				.setSourceText($(id).value || $(id).value)
				.setGroupCode(groupCode)
				.setOnSuccess(this.onSuccess.bind(this))
				.setBackTranslationFlag(true);
		request.processId = Process.getId();

		$(this._getId(this._BACK_TRANSLATION_TEXTAREA_ID_PREFIX, targetLanguageCode, groupCode)).innerHTML = '';

		this._startThread(threadId);
		new SentenceTranslator(request).start();
	},

	/**
	 * To generate the request queue, this store
	 * TODO
	 * More reduce branch thread
	 * Now, each thread has a language branch
	 * ↑Not even one request for a fashion language that skip
	 * ↑You okay now Maa.
	 */
	_createRequestQueue : function(targetLanguageCodes, groupCodes) {
		var threadId = 0;
		var processId = Process.getId();
		this.init();
		targetLanguageCodes.each(function(targetLanguageCode){
			var found = false;
			groupCodes.each(function(groupCode){
				/**
				 * If you have not committed any
				 */
				if (this._isCommit(targetLanguageCode, groupCode)) {
					return;
				}
				found = true;

				$(this._getId(this._TARGET_TEXTAREA_ID_PREFIX ,targetLanguageCode, groupCode)).value = '';
				this._doChangeTextarea(targetLanguageCode, groupCode);

				// translation
				var translationRequest = new Request()
						.setThreadId(threadId)
						.setSourceLanguageCode(this.getSourceLanguageCode())
						.setTargetLanguageCode(targetLanguageCode)
						.setSourceText($(this._getId(this._SOURCE_TEXTAREA_ID_PREFIX, this.getSourceLanguageCode(), groupCode)).value)
						.setGroupCode(groupCode)
						.setOnSuccess(this.onSuccess.bind(this))
						.setBackTranslationFlag(false);
				translationRequest.processId = processId;
				this.getRequestQueue().putRequest(translationRequest);

				$(this._getId(this._BACK_TRANSLATION_TEXTAREA_ID_PREFIX,targetLanguageCode, groupCode)).innerHTML = '';

//				// back translation
//				var backTranslationRequest = new Request()
//						.setThreadId(threadId)
//						.setSourceLanguageCode(targetLanguageCode)
//						.setTargetLanguageCode(this.getSourceLanguageCode())
//						.setSourceText('')
//						.setGroupCode(groupCode)
//						.setOnSuccess(this.onSuccess.bind(this))
//						.setBackTranslationFlag(true);
//				this.getRequestQueue().putRequest(backTranslationRequest);
			}.bind(this));

			if (found) {
				threadId++;
				if (threadId >= this._MAX_CONNECTIONS) {
					threadId = 0;
				}
			}
		}.bind(this));
	},

	/**
	 * Commit whether what has been given
	 */
	_isCommit : function(targetLanguageCode, groupCode) {
		return !!($(this._getId(this._COMMIT_CHECKBOX_ID_PREFIX, targetLanguageCode, groupCode)).checked);
	},

	/**
	 * Request model ID to obtain
	 */
	_getIdByRequest : function(request) {
		var prefix;
		var languageCode;
		if (!request.isBackTranslation()) {
			prefix = this._TARGET_TEXTAREA_ID_PREFIX;
			languageCode = request.getTargetLanguageCode();
		} else {
			prefix = this._BACK_TRANSLATION_TEXTAREA_ID_PREFIX;
			languageCode = request.getSourceLanguageCode();
		}
		var id = this._getId(prefix, languageCode, request.getGroupCode());
		return id;
	},

	/**
	 * Request model ID to obtain
	 */
	_getBackIdByRequest : function(request) {
		var prefix;
		var languageCode;
		prefix = this._BACK_TRANSLATION_TEXTAREA_ID_PREFIX;
		languageCode = request.getTargetLanguageCode();
		var id = this._getId(prefix, languageCode, request.getGroupCode());
		return id;
	},
	/**
	 * To get the ID from the arguments
	 */
	_getId : function(prefix, targetLanguageCode, groupCode) {
		return prefix + '' + targetLanguageCode + this._SEPARATOR + groupCode;
	},

	/**
	 * prefix, id to get the language code from
	 */
	_getLanguageCode : function(prefix, id) {
		return id.replace(prefix, '').split(this._SEPARATOR)[0];
	},
	/**
	 * prefix, id to get the language code from
	 */
	_getGroupCode : function(prefix, id) {
		return id.replace(prefix, '').split(this._SEPARATOR)[1];
	},

	_$ : function(id) {
		if (!this._$cache[id]) {
			this._$cache[id] = $(id);
		}
		return this._$cache[id];
	},

	_getTargetPairs : function() {
		if (!this._targetPair.length) {
			this.getTargetLanguageCodes().each(function(targetLanguageCode){
				this.getGroupCodes().each(function(groupCode){
					this._targetPair.push({
						targetLanguageCode : targetLanguageCode,
						groupCode : groupCode
					});
				}.bind(this));
			}.bind(this));
		}
		return this._targetPair;
	},

	/**
	 * @return
	 * {
	 * 		sourceLanguageCode : 'ja
	 * 		contents : [
	 * 				{languageCode : 'ja', groupCode : 0, content : ''}
	 * 				, {languageCode : 'ja', groupCode : 1, content : ''}
	 * 				, {languageCode : 'en', groupCode : 0, content : ''}
	 * 				...
	 * 		]
	 * }
	 */
	getValues : function() {
		var values = {
			sourceLanguageCode : this.getSourceLanguageCode(),
			contents : new Array()
		};

		this.getGroupCodes().each(function(groupCode){
			values.contents.push({
				languageCode : this.getSourceLanguageCode(),
				groupCode : groupCode,
				content : $(this._getId(this._SOURCE_TEXTAREA_ID_PREFIX, this.getSourceLanguageCode(), groupCode)).value || ''
			});
		}.bind(this));

		this._getTargetPairs().each(function(targetPair){
			values.contents.push({
				languageCode : targetPair.targetLanguageCode,
				groupCode : targetPair.groupCode,
				content : $(this._getId(this._TARGET_TEXTAREA_ID_PREFIX, targetPair.targetLanguageCode, targetPair.groupCode)).value || ''
			});
		}.bind(this));

		return values;
	},

	/**
	 * validate
	 */
	validate : function() {
		var validator = new Validator();
		this.getGroupCodes().each(function(groupCode){
			validator.add(
					this._$(this._getId(
							this._SOURCE_TEXTAREA_ID_PREFIX
							, this.getSourceLanguageCode()
							, groupCode
					)).value
					, 'required');
		}.bind(this));
		this._getTargetPairs().each(function(targetPair){
			validator.add(
					this._$(this._getId(
							this._TARGET_TEXTAREA_ID_PREFIX
							, targetPair.targetLanguageCode
							, targetPair.groupCode
					)).value
					, 'required');
		}.bind(this));
		return validator.validate();
	},

	/**
	 * To obtain a source pair of empty
	 */
	getBlankSourceTextareaPairs : function() {
		var sourcePairs = new Array();
		this.getGroupCodes().each(function(groupCode){
			var value = this._$(this._getId(this._SOURCE_TEXTAREA_ID_PREFIX
					, this.getSourceLanguageCode()
					, groupCode)).value;
			if (!value) {
				sourcePairs.push({
					languageCode : this.getSourceLanguageCode(),
					groupCode : groupCode
				});
			}
		}.bind(this));
		return sourcePairs;
	},

	/**
	 * Get a pair of empty target
	 */
	getBlankTargetTextareaPairs : function() {
		var targetPairs = new Array();
		this._getTargetPairs().each(function(targetPair){
			var value = this._$(this._getId(this._TARGET_TEXTAREA_ID_PREFIX
					, targetPair.targetLanguageCode
					, targetPair.groupCode)).value;
			if (!value) {
				targetPairs.push(targetPair);
			}
		}.bind(this));
		return targetPairs;
	},

	/**
	 * is
	 */
	isRunning : function() {
		return (this.getRunningThreads().length > 0);
	},

	/**
	 * getter/setter
	 */
	getSourceLanguageCode : function() {
		return this._sourceLanguageCode;
	},
	setSourceLanguageCode : function(sourceLanguageCode) {
		this._sourceLanguageCode = sourceLanguageCode;
		return this;
	},
	getTargetLanguageCodes : function() {
		return this._targetLanguageCodes;
	},
	setTargetLanguageCodes : function(targetLanguageCodes) {
		this._targetLanguageCodes = targetLanguageCodes;
		return this;
	},
	getGroups : function() {
		return this._groups;
	},
	setGroups : function(groups) {
		this._groups = groups;
		return this;
	},
	getGroupCodes : function() {
		return this._groupCodes;
	},
	setGroupCodes : function(groupCodes) {
		this._groupCodes = groupCodes;
		return this;
	},
	getRequestQueue : function() {
		return this._requestQueue;
	},
	setRequestQueue : function(requestQueue) {
		this._requestQueue = requestQueue;
		return this;
	},
	getRunningThreads : function() {
		return this._runningThreads;
	},
	setRunningThreads : function(runningThreads) {
		this._runningThreads = runningThreads;
		return this;
	}
};

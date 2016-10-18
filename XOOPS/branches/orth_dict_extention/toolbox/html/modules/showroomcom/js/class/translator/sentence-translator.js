//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
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
 * @require Translator
 */
var SentenceTranslator = Class.create();
Object.extend(SentenceTranslator.prototype, Translator.prototype);
Object.extend(SentenceTranslator.prototype, {

	_NO_TRANSLATION_TAG_BEGIN : '[tag]',
	_NO_TRANSLATION_TAG_END : '[/tag]',
	tagOpenFlag : false,
	cr : null,
	
	_getCrCharsByText : function(text) {
		if (text.indexOf("\r\n") != -1) {
			this.cr = "\r\n";
			return "\r\n";
		} else if (text.indexOf("\r") != -1) {
			this.cr = "\r\n";
			return "\r";
		} else {
			return "\n";
		}
	},

	run : function() {
		var source = this.getRequest().getSourceText();
		cr = this.cr || this._getCrCharsByText(source);
		this._sourceTexts = source.split(cr);
		this._targetTexts = new Array();
		this._sentence = 0;
		this._finishFlag = false;
		this.translate();
	},

	/**
	 * @Override
	 */
	translate : function() {

		var doTranslateFlag = true;

		while (this._getSourceText() == '') {
			var transportWrapper = this._createNullTransportWrapper();
			this.getTargetTexts().push('');
			var sentece = this.getSentence();
			this.setSentence(++sentece);
			(this.getRequest().getOnSuccess())(this.getRequest()
									, transportWrapper
									, this.isFinish()
									, this.getTargetTexts().join(this.cr || "\n"));
		}

		if (this.isFinish()) {
			return;
		}

		/**
		 * Tags, separated by a translation
		 */
		var result = this.tagSeparate(this._getSourceText(), this.tagOpenFlag);
		this.tagOpenFlag = result.openFlag;

		var translationText = new Array();
		$H(result.translationTexts).each(function(text){
			translationText.push(text.value);
		}.bind(this));

		/**
		 * When there is no translation
		 */
		if (!result.hasTranslationTexts) {
			var transportWrapper = this._createNullTransportWrapper();
			var targetTexts = transportWrapper.getTargetText().split(this.cr || "\n");
			var mergedTexts = new Array();
			var mergedBackTexts = new Array();
			for (var i = 0, length = result.length; i < length; i++) {
				if (!!result.noTranslationTexts[i]) {
					mergedTexts.push(result.noTranslationTexts[i]);
					mergedBackTexts.push(result.noTranslationTexts[i]);
				}
			}
			var mergedText = mergedTexts.join('');
			var mergedBackText = mergedBackTexts.join('');
//			mergedText = mergedText.replace(/(\n|\r)/g, '');
			this.getTargetTexts().push(this._getSourceText());
			var sentece = this.getSentence();
			this.setSentence(++sentece);
			transportWrapper.setTargetText(mergedText);
			transportWrapper.setBackText(mergedBackText);
			(this.getRequest().getOnSuccess())(this.getRequest()
					, transportWrapper
					, this.isFinish()
					, this.getTargetTexts().join(this.cr || "\n"));

			if (this.isFinish()) {
				return;
			}
			this.translate();
			return;
		}

		/**
		 * When there is a translation
		 */
		var parameters = this._getParameters();
		parameters.sourceText = translationText.join(this.cr || "\n")

		new Ajax.Request(this._AJAX_PATH, {
			postBody : $H(parameters).toQueryString(),
			onException : function(f,e) {
				console.error(e);
			},
			onSuccess : function(transport) {
				var transportWrapper = new TransportWrapper(transport);

				if (transportWrapper.isError()) {
					transportWrapper.setTargetText('');
					transportWrapper.setBackText('');
					(this.getRequest().getOnSuccess())(this.getRequest()
							, transportWrapper
							, true
							, '', true);
					return;
				}

				var targetTexts = transportWrapper.getTargetText().split(this.cr || "\n");
				var backTexts = transportWrapper.getBackText().split(this.cr || "\n");
				var mergedTexts = new Array();
				var mergedBackTexts = new Array();
				var mergedTextsWithTag = new Array();
				for (var i = 0, length = result.length; i < length; i++) {
					if (!!result.translationTexts[i]) {
						mergedTextsWithTag.push(targetTexts[0]);
						mergedTexts.push(targetTexts.shift());
						mergedBackTexts.push(backTexts.shift());
					} else if (!!result.noTranslationTexts[i]) {
						mergedTextsWithTag.push(this._NO_TRANSLATION_TAG_BEGIN + result.noTranslationTexts[i] + this._NO_TRANSLATION_TAG_END);
						mergedTexts.push(result.noTranslationTexts[i]);
						mergedBackTexts.push(result.noTranslationTexts[i]);
					}
				}
				
				var mergedText = mergedTexts.join('');
				this.getTargetTexts().push(mergedTextsWithTag.join(''));
				var sentece = this.getSentence();
				this.setSentence(++sentece);
				transportWrapper.setTargetText(mergedText);
				transportWrapper.setBackText(mergedBackTexts.join(''));
				(this.getRequest().getOnSuccess())(this.getRequest()
						, transportWrapper
						, this.isFinish()
						, this.getTargetTexts().join(this.cr || "\n"));

				if (this.isFinish()) {
					return;
				}
				this.translate();
			}.bind(this)
		});
//		if (this.isFinish()) {
//			return;
//		}

//		if (doTranslateFlag) {
//			this.doTranslate();
//		} else {
//			this.translate();
//		}
	},

	/**
	 * Text Analysis
	 * 
	 * @param text sentence
	 * @param openFlag When there is a translation
	 * @return result
	 */
	tagSeparate : function(text, openFlag) {
		var result = {
			translationTexts : new Object(),
			noTranslationTexts :  new Object(),
			openFlag : openFlag,
			hasTranslationTexts : false
		};
		var tagBeginLength = this._NO_TRANSLATION_TAG_BEGIN.length;
		var tagEndLength = this._NO_TRANSLATION_TAG_END.length;
		var id = 0;
		for (var i = 0, length = text.length; i < length; i++) {
			if (!result.openFlag && text.substr(i, tagBeginLength) == this._NO_TRANSLATION_TAG_BEGIN) {
				result.openFlag = true;
				if (!!result.noTranslationTexts[id] || !!result.translationTexts[id]) {
					id++;
				}
				i += tagBeginLength - 1;
				continue;
			} else if (result.openFlag && text.substr(i, tagEndLength) == this._NO_TRANSLATION_TAG_END) {
				result.openFlag = false;
				if (!!result.noTranslationTexts[id] || !!result.translationTexts[id]) {
					id++;
				}
				i += tagEndLength - 1;
				continue;
			} else {
				if (result.openFlag) {
					result.noTranslationTexts[id] = (result.noTranslationTexts[id] || '') + '' + text.charAt(i);
				} else {
					result.translationTexts[id] = (result.translationTexts[id] || '') + '' + text.charAt(i);
					result.hasTranslationTexts = true;
				}
			}
		}
		result.length = Object.keys(result.translationTexts).length + Object.keys(result.noTranslationTexts).length;
		return result;
	},

	/**
	 * Toriaezu to the caller, stating that the connection ended
	 * What to do with calling it free
	 * If they had left the sentence translated "SentenceTranslator the" translate to start new
	 */
	onSuccess : function(transportWrapper) {
		this.getTargetTexts().push(transportWrapper.getTargetText());
		var sentece = this.getSentence();
		this.setSentence(++sentece);
		(this.getRequest().getOnSuccess())(this.getRequest()
								, transportWrapper
								, this.isFinish()
								, this.getTargetTexts().join("\n"));

		if (!this.isFinish()) {
			this.translate();
		}
	},

	/**
	 * @Override
	 */
	_getParameters : function() {
		return {
			sourceLanguageCode : this.getRequest().getSourceLanguageCode(),
			targetLanguageCode : this.getRequest().getTargetLanguageCode(),
			sourceText : this._getSourceText()
		};
	},

	/**
	 * @return NullTransportWrapper
	 */
	_createNullTransportWrapper : function() {
		var data = {
			contents : {}
		};
		data.contents[this.getRequest().getTargetLanguageCode()] = {
			translation : {
				contents : ''
			},
			backTranslation : {
				contents : ''
			}
		};
		var nullTransportWrapper = new TransportWrapper({
			responseText : Object.toJSON(data),
			request : {
				body : this._getPostBody()
			}
		});
		return nullTransportWrapper;
	},
	_getSourceText : function() {
		return this._sourceTexts[this.getSentence()];
	},

	/**
	 * has
	 */
	_hasSentence : function() {
		return (this.getSourceTexts().length > this.getSentence());
	},

	/**
	 * is
	 */
	isFinish : function() {
		return !this._hasSentence();
	},

	/**
	 * getter/setter
	 */
	getSourceTexts : function() {
		return this._sourceTexts;
	},
	setSourceTexts : function(sourceTexts) {
		this._sourceTexts = sourceTexts;
		return this;
	},
	getTargetTexts : function() {
		return this._targetTexts;
	},
	setTargetTexts : function(targetTexts) {
		this._targetTexts = targetTexts;
		return this;
	},
	getSentence : function() {
		return this._sentence;
	},
	setSentence : function(sentence) {
		this._sentence = sentence;
		return this;
	}
});
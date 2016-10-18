//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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
var Translator = Class.create( {
	cache : null,
	licenseCache : null,
	results : null,
	nowSentence : [],
	// parameters
	sourceLang : "en",
	targetLang : "ja",
	translateCount : null,
	isBackTranslateTrans : true,
	isIntermediateTrans : false,
	isCancel : false,
	isCanceled : false,
	CACHE_LIMIT : 40,
	THREAD_COUNT : 1,
	MERGE_MAX : 400, // byte
	// external objects
	publisher : null,
	translateEditor : null,
	voiceGenerator : null,
	// error messages
	PRE_ERROR_CODE : "SAeou8oe9ugnjqka",
	INVALID_RESPONSE : "Invalid Response",
	SERVER_ERROR : 'Server Error',
	
	initialize : function(sourceLang, targetLang) {
		this.cache = new Hashtable();
		this.licenseCache = new Hashtable();
		
		this.sourceLang = sourceLang;
		this.targetLang = targetLang;
		

		this.FAILED_MESSAGE = Const.Message.Error.TranslateError;
		this.ERROR_SERVER_RESPONSE = Const.Message.Error.ServerError;
		
		// added in 6/3
		this.voiceGenerator = new VoiceGenerator(this.targetLang);
		this.voiceGenerator.clear();
	},
	
	setTranslateEditor : function(translateEditor) {
		this.translateEditor = translateEditor;
	},
	
	init : function(isBackTranslation, isIntermediate) {
		this.results = [];
		this.isBackTranslateTrans = isBackTranslation;
		this.isIntermediateTrans = isIntermediate;
		this.translateCount == null;
		this.isCancel = false;
		this.isCanceled = false;
		this.nowSentence = [];
		this.voiceGenerator.clear();
	},
	
	translate : function() {
		this.translateEditor.parseSourceText(this.isIntermediateTrans ? this.targetLang : this.sourceLang);
	},
	
	excludeSentences : function(sourceLang, targetLang, sentences) {
		var temp = [];
		for(var i = 0; i < sentences.length; i++) {
			if(sentences[i] == "" || sentences[i] == "[[#ret]]") {
				continue;
			}
			if(this.cache.containsKey(sourceLang + "2" + targetLang + ":" + sentences[i])) {
				continue;
			}
			temp.push({sentence: sentences[i], index: i});
		}
		return temp;
	},
	
	setNoTargetSentences : function(sentences) {
		for(var i = 0; i < sentences.length; i++) {
			if(sentences[i] == "" || sentences[i] == "[[#ret]]") {
				this.results[i] = [];
				this.results[i]['translation'] = sentences[i];
				if(this.isBackTranslateTrans) {
					this.results[i]['backTranslation'] = sentences[i];
				}
			}
		}
	},
	
	setCachedSentences : function(sourceLang, targetLang, sentences) {
		for(var i = 0; i < sentences.length; i++) {
			var sentence = sentences[i];
			var key = sourceLang + "2" + targetLang + ":" + sentence;
			if(this.cache.containsKey(key)) {
				this.results[i] = [];
				this.results[i]['translation'] = this.cache.get(key);
				
				if(this.isBackTranslateTrans) {
					this.results[i]['backTranslation'] = this.cache.get(
							targetLang + "2" + sourceLang + ":" 	+ this.results[i].translation);
				}
			}
		}
	},
	
	mergedSentences : function(sentences) {
		var count = 0;
		var temp = [];
		var tempCount = 0;
		temp[tempCount] = [];
		for(var i = 0; i < sentences.length ; i++) {
			if(count == 0 || (count + this.multibyteLength(sentences[i].sentence)) < this.MERGE_MAX) {
				count += this.multibyteLength(sentences[i].sentence);
				temp[tempCount].push(sentences[i]);
			} else {
				count = this.multibyteLength(sentences[i].sentence);
				temp[++tempCount] = [];
				temp[tempCount].push(sentences[i]);
			}
		}
		return temp;
	},
	
	callbackTranslate : function(sentences) {
		try {
			var sourceLang = this.sourceLang;
			var targetLang = this.targetLang;
			if(this.isIntermediateTrans)  {
				sourceLang = this.targetLang;
				targetLang = this.sourceLang;
			}
			this.setNoTargetSentences(sentences);
			this.setCachedSentences(sourceLang, targetLang, sentences);
			var tempSentences = this.excludeSentences(sourceLang, targetLang, sentences);
			tempSentences = this.mergedSentences(tempSentences);
			if(tempSentences.flatten().length == 0) {
				return;
			}
			for(var i = 0; i < this.THREAD_COUNT; i++) {
				this.doTranslate(i, sourceLang, targetLang, tempSentences);
			}
		} catch(e) {
			this.translateEditor.callRaiseError(e);
		}
	},
	
	doTranslate : function(threadId, sourceLang, targetLang, allSentences) {
		if(this.isCancel) {
			this.isCanceled = true;
			return;
		}
		var sentences = allSentences.shift();
		// end of translation
		if(sentences == undefined) {
			return;
		}
		var m = "";
		for(var i = 0; i < sentences.length; i++) {
			this.results[sentences[i].index] = [];
			m += sentences[i].sentence + " ";
		}
		this.nowSentence[threadId] = m;
		var params = $H({
				sourceLang : sourceLang
				, targetLang : targetLang
				, backTranslate : this.isBackTranslateTrans
			}).toQueryString();
		var qs = "&content[";
		for(var j = 0; j < sentences.length; j++) {
			qs += j + "]=" + encodeURIComponent(sentences[j].sentence);
			if(j + 1< sentences.length) {
				qs += "&content[";
			}
		}
		params += qs;
		
		// nomal translation
		new Ajax.Request(
					'./php/ajax/backtranslate.php'
				, {
					method : 'post'
					, parameters : params
					, onSuccess : function(response){
						try {
							try {
								var result = response.responseText.evalJSON()[0];
							} catch(e) {
								// invalid response. server error occurred? 
								for(var i = 0; i < sentences.length; i++) {
									this.results[sentences[i].index]['translation'] = this.PRE_ERROR_CODE + this.INVALID_RESPONSE;
									if(this.isBackTranslateTrans) {
										this.results[sentences[i].index]['backTranslation'] = this.PRE_ERROR_CODE + this.INVALID_RESPONSE;
									}
								}
								throw {message : this.INVALID_RESPONSE, contents : response.responseText};
							}
							// langrid or other error occurred, in server side process.
							if(result.status.toLowerCase() == 'error'){
								var errorCode = this.TIME_OUT_ERROR_CODE;
								for(var i = 0; i < sentences.length; i++) {
									this.results[sentences[i].index]['translation'] = errorCode + result.translate[0];
									if(this.isBackTranslateTrans) {
										this.results[sentences[i].index]['backTranslation'] = errorCode + result.backtranslate[0];
									}
								}
								throw {message : this.SERVER_ERROR, contents : result.translate};
							}
							if(this.CACHE_LIMIT < this.licenseCache.size()) {
								this.licenseCache.remove(this.cache.keys()[0]);
							}
							this.licenseCache.put(sourceLang + "2" + targetLang, result.licenseInformation);
							// translation is valid.
							for(var i = 0; i < result.translate.length; i++) {

								this.results[sentences[i].index]['translation'] = result.translate[i];
								
								if(this.CACHE_LIMIT < this.cache.size() + 1){
									this.cache.remove(this.cache.keys()[0]);
								}
								this.cache.put(
									sourceLang + "2" + targetLang + ":" + sentences[i].sentence.replace(/^[\s　]+|[\s　]+$/g, "")
									, result.translate[i].replace(/^[\s　]+|[\s　]+$/g, ""));
								if(this.isBackTranslateTrans) {
									this.results[sentences[i].index]['backTranslation'] = result.backtranslate[i];
									if(this.CACHE_LIMIT < this.cache.size() + 1){
										this.cache.remove(this.cache.keys()[0]);
									}
									this.cache.put(
										targetLang + "2" + sourceLang + ":" + result.translate[i].replace(/^[\s　]+|[\s　]+$/g, "")
										, result.backtranslate[i].replace(/^[\s　]+|[\s　]+$/g, ""));
								}
							}
							this.nowSentence[threadId] = "";
							this.doTranslate(threadId, sourceLang, targetLang, allSentences);
						}catch(e){
							this.translateEditor.callRaiseError(e);
						}
					}.bind(this)
					, onFailure : function(e) {
						this.translateEditor.callRaiseError(e);
					}.bind(this)
				}
			);
	}, 
	
	isTranslationFinished : function(index) {
		return  this.results[index] != null
				&& this.results[index] != null
				&& this.results[index]["translation"] != null;
	},
	
	isTranslationComplete : function() {
		for(var i = 0; i < this.results.length; i++) {
			if(this.results[i] == null || this.results[i] == undefined) {
				return false;
			}
			var bt = this.results[i].translation;
			if(bt == null || bt == undefined) {
				return false;
			}
		}
		
		return true;		
	},
	
	isBackTranslation : function() {
		return this.isBackTranslateTrans;
	},
	
	isIntermediate : function() {
		return this.isIntermediateTrans;
	},
	
	getFinishedLastIndex : function(startIndex) {
		for(var i = startIndex; i < this.results.length; i++) {
			if(this.results[i] == null || this.results[i] == undefined
				|| this.results[i].translation == null || this.results[i].translation == undefined)
			{
				break;
			}
		}
		return i - 1;
	},
	
	getTranslationSlicedResults : function(start, end) {
		return this.results.slice(start, end + 1);
	},
	
	getBackTranslationSlicedResults : function(start, end) {
		return this.results.slice(start, end + 1);
	},
	
	getTranslationResult : function(index) {
		return this.results[index]["translation"];
	},
	
	getBackTranslationResult : function(index) {
		return this.results[index]["backTranslation"];
	},
	
	getLicenseInformation : function() {
		return this.licenseCache.get(this.sourceLang + "2" + this.targetLang);
	},
	
	getTranslatingSentence : function() {
		return this.nowSentence;
	},
	
	getSentenceCount : function() {
		return this.results.length;
	},
	
	setSourceLang : function(languageCode) {
		this.sourceLang = languageCode;
	},
	
	setTargetLang : function(languageCode) {
		this.targetLang = languageCode;
	},
	
	setCancel : function() {
		this.isCancel = true;
	},
	
	wasCanceled : function() {
		return this.isCanceled;
	},
	
	multibyteLength : function(str) {
		var count = 0;
		for(var i = 0; i < str.length; i++) {
			 if (escape(str.charAt(i)).length < 4) {
				 count++;
			 } else {
				 count += 2;
			 }
		 }
		 var m = escape(str);
		 return count;
/*		
		str = escape(str);
		var i = 0;
		for(; i < str.length; i++){
			if(str.charAt(i) == '%'){
				if(str.charAt(i + 1) == 'u'){
					i += 4;
					length++;
				}else{
					i++;
				}
				i++;
			}
			length++;
		}
		return length;
*/
	},
	
	callVoiceGenerator : function() {	
		if(this.targetLang!='ja' && this.targetLang!='en' && this.targetLang!='zh') {
			return;
		}
		this.voiceGenerator.setLang(this.targetLang);
		
		var sentences = new Array();
		for(var i=0; i<this.getSentenceCount(); i++) {
			sentences.push(this.getTranslationResult(i));
		}
		
		this.voiceGenerator.generateAll(sentences);
	}
});
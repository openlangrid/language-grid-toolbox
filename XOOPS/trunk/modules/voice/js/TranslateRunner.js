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
var TranslateRunner = Class.create( {
	tResults : [],
	btResults : [],
	
	tLicenseCache : null,
	btLicenseCache : null,
	
	translateCache : null,
	se : null,
	te : null,
	bta : null,
	maxSentence : 0,
	isIdle : true,
	errorCallCount : 0,
	isCancel : false,
	threadPoolCount : 0,
	timerId : null,
	timeTimerId : null,
	timeArea : null,

	THREAD_COUNT : 1,
	CACHE_LIMIT : 40,
	ERROR_CALL_LIMIT : 5,
	//FAILED_MESSAGE : 'Processing is suspended due to errors in the Language Grid ToolBox.',
	//ERROR_LIMIT_MESSAGE : 'Processing is suspended due to an excess of server errors when using services.',
	//ERROR_SERVER_RESPONSE : 'Unexpected response is received from the server.',
	ERROR_LIMIT_MESSAGE : 'Processing is suspended due to an excess of server errors when using services.',

	initialize : function(sourceEditor, targetEditor, backTranslateArea, licenseArea) {
		this.FAILED_MESSAGE = Const.Message.Error.TranslateError;
		this.ERROR_SERVER_RESPONSE = Const.Message.Error.ServerError;

		this.se = sourceEditor;
		this.te = targetEditor;
		this.bta = backTranslateArea;
		this.translateCache = new Hashtable();
		this.backTranslateCache = new Hashtable();
		this.timeArea = $('time');
		this.backTranslateTimeArea = $('back-translate-time');
		
		this.licenseArea = licenseArea;
	},

	translate : function(){
		if(this.se.isEmpty() || ! this.isIdle){
			return;
		}
		try{
			this.licenseArea.clear();
			this.timeArea.innerHTML = 0;
			this.SetBackTranslateButtonDisabled(true);
			this.timeTimerId = setInterval(function(){
				this.timeArea.innerHTML = parseInt(this.timeArea.innerHTML) + 1;
			}.bind(this), 1000);
			this.isIdle = false;
			this.tResults = [];
			this.btResults = [];
			var sourceLang = this.se.getLangCode();
			var targetLang = this.te.getLangCode();
			$('translate-button').style.display = 'none';
			$('cancel-button').style.display = 'inline';
			$('parsing').style.display = 'inline';
			var value = this.se.getEditorText();
			var requestParams = $H({
				sourceLang : sourceLang
				, source : value
			}).toQueryString();
			new Ajax.Request(
				"./php/ajax/parse-text.php"
				, {
					method : 'post'
					, parameters : requestParams
					, onSuccess : function(httpObj) {
						try {
							var dSentences = httpObj.responseText.evalJSON()[0];
							this.se.clearText();
							var minus = 0;
							var sentences = [];
							for(var i = 0; i < dSentences.length; i++){
								var value = dSentences[i].replace(/&#039;/gi, "'");
								value = value.unescapeHTML();
								value = value.replace(/<\/?[^>]+>/gi, " ");
								value = value.replace(/ {2,}/gi, " ");
								var sentence = value.strip();
								if(sentence == ''){
									sentence = '.';
								}else if(sentence == "[[#ret]]"){
									minus++;
								}
								this.se.setEditorText(i - minus, sentence);
								sentences[i] = sentence;
							}
							$('parsing').style.display = 'none';
							this.displayExecutingInformation();
							this.maxSentence = sentences.length;
							this.te.clearText();
							this.bta.clearText();
							for(var i = 0; i < this.THREAD_COUNT; i++){
								this.doThreadingTranslate(i, sentences, sourceLang, targetLang);
							}
						}catch(e){
							this.threadPoolCount = 0;
							this.setCancel();
							alert(this.FAILED_MESSAGE);
							//alert(this.FAILED_MESSAGE + "\n" + e.name + ":" + e.message + "\n" + e);
						}
					}.bind(this)
				});
		}catch(e){
			this.threadPoolCount = 0;
			this.setCancel();
			alert(this.FAILED_MESSAGE);
			//alert(this.FAILED_MESSAGE + '\n' + e.name + ":" + e.message + "\n" + e);
		}
	},

	backTranslate : function(){
		if(this.te.isEmpty() || ! this.isIdle){
			return;
		}
		try{
			this.licenseArea.clear();
			this.backTranslateTimeArea.innerHTML = 0;
			this.SetTranslateButtonDisabled(true);
			this.timeTimerId = setInterval(function(){
				this.backTranslateTimeArea.innerHTML = parseInt(this.backTranslateTimeArea.innerHTML) + 1;
			}.bind(this), 1000);
			this.isIdle = false;
			this.tResults = [];
			this.btResults = [];
			var sourceLang = this.te.getLangCode();
			var targetLang = this.se.getLangCode();
			$('back-translate-button').style.display = 'none';
			$('back-translate-cancel-button').style.display = 'inline';
			$('translate-parsing').style.display = 'inline';
			var value = this.te.getEditorText();
			var requestParams = $H({
				sourceLang : sourceLang
				, source : value
			}).toQueryString();
			new Ajax.Request(
				"./php/ajax/parse-text.php"
				, {
					method : 'post'
					, parameters : requestParams
					, onSuccess : function(httpObj) {
						try {
							var dSentences = httpObj.responseText.evalJSON()[0];
							this.te.clearText();
							var minus = 0;
							var sentences = [];
							for(var i = 0; i < dSentences.length; i++){
								var value = dSentences[i].replace(/&#039;/gi, "'");
								value = value.unescapeHTML();
								value = value.replace(/<\/?[^>]+>/gi, " ");
								value = value.replace(/ {2,}/gi, " ");
								var sentence = value.strip();
								if(sentence == ''){
									sentence = '.';
								}else if(sentence == "[[#ret]]"){
									minus++;
								}
								this.te.setEditorText(i - minus, sentence);
								sentences[i] = sentence;
							}
							
							//OK
							$('translate-parsing').style.display = 'none';
							this.displayBackTranslateExecutingInformation();
							this.maxSentence = sentences.length;
//							this.te.clearText();
							this.bta.clearText();
							for(var i = 0; i < this.THREAD_COUNT; i++){
								this.doThreadingBackTranslate(i, sentences, sourceLang, targetLang);
							}
						}catch(e){
							this.threadPoolCount = 0;
							this.setBackTranslateCancel();
							alert(this.FAILED_MESSAGE);
							//alert('E184: ' + this.FAILED_MESSAGE + "\n" + e.name + ":" + e.message + "\n" + e);
						}
					}.bind(this)
				});
		}catch(e){
			this.threadPoolCount = 0;
			this.setBackTranslateCancel();
			alert(this.FAILED_MESSAGE);
			//alert('E192: ' + this.FAILED_MESSAGE + '\n' + e.name + ":" + e.message + "\n" + e);
		}
	},

	doThreadingTranslate : function(sentenceNumber, sentences, sourceLang, targetLang){
		if(this.isCancel){
			return;
		}
		this.threadPoolCount++;
		var isEnd = false;
		var isDone = true;
		$('traslatingSentence').innerHTML = sentences[sentenceNumber].slice(0, 24) + "...";
		$('backtranslatingSentence').innerHTML = sentences[sentenceNumber].slice(0, 24) + "...";
		if(this.tResults[sentenceNumber] == undefined){
			this.tResults[sentenceNumber] = [];
			this.btResults[sentenceNumber] = [];
		}
		if(sentences[sentenceNumber] == "." || sentences[sentenceNumber] == "[[#ret]]"){
			this.tResults[sentenceNumber]['sentence'] = sentences[sentenceNumber];
			this.tResults[sentenceNumber]['isDisplay'] = false;
			this.btResults[sentenceNumber]['sentence'] = sentences[sentenceNumber];
			this.btResults[sentenceNumber]['isDisplay'] = false;
			isEnd = this.writeTexts();
			this.threadPoolCount--;
		}else if(this.translateCache.containsKey(
				sourceLang + "2" + targetLang + ":" + sentences[sentenceNumber]))
		{
			isEnd =	this.cachedTranslate(
					sourceLang + "2" + targetLang + ":" + sentences[sentenceNumber]
					, sentenceNumber);
			this.threadPoolCount--;
		}else{
			this.runAjaxRequest(
					sentenceNumber, sentences, sourceLang, targetLang);
			isDone = false;
		}
		if(isEnd){
			this.endProcess();
		}else if(isDone){
			this.doThreadingTranslate(
					sentenceNumber + this.THREAD_COUNT, sentences, sourceLang, targetLang);
		}
	},

	doThreadingBackTranslate : function(sentenceNumber, sentences, sourceLang, targetLang){
		if(this.isCancel){
			return;
		}
		this.threadPoolCount++;
		var isEnd = false;
		var isDone = true;
//		$('traslatingSentence').innerHTML = sentences[sentenceNumber].slice(0, 24) + "...";
		$('backtranslatingSentence').innerHTML = sentences[sentenceNumber].slice(0, 24) + "...";
//		if(this.tResults[sentenceNumber] == undefined){
//			this.tResults[sentenceNumber] = [];
//			this.btResults[sentenceNumber] = [];
//		}
		if(this.btResults[sentenceNumber] == undefined){
//			this.tResults[sentenceNumber] = [];
			this.btResults[sentenceNumber] = [];
		}
		if(sentences[sentenceNumber] == "." || sentences[sentenceNumber] == "[[#ret]]"){
//			this.tResults[sentenceNumber]['sentence'] = sentences[sentenceNumber];
//			this.tResults[sentenceNumber]['isDisplay'] = false;
			this.btResults[sentenceNumber]['sentence'] = sentences[sentenceNumber];
			this.btResults[sentenceNumber]['isDisplay'] = false;
			isEnd = this.writeBackTexts();
			this.threadPoolCount--;
		}else if(this.backTranslateCache.containsKey(
				sourceLang + "2" + targetLang + ":" + sentences[sentenceNumber]))
		{
			isEnd =	this.cachedBackTranslate(
					sourceLang + "2" + targetLang + ":" + sentences[sentenceNumber]
					, sentenceNumber);
			this.threadPoolCount--;
		}else{
			this.runBackTranslateAjaxRequest(
					sentenceNumber, sentences, sourceLang, targetLang);
			isDone = false;
		}
		if(isEnd){
			this.endProcess();
		}else if(isDone){
			this.doThreadingBackTranslate(
					sentenceNumber + this.THREAD_COUNT, sentences, sourceLang, targetLang);
		}
	},

	cachedTranslate : function(cacheKey, sentenceNumber){
		tr = this.translateCache.get(cacheKey).translate;
		btr = this.translateCache.get(cacheKey).backtranslate;
		this.tResults[sentenceNumber]['sentence'] = tr;
		this.tResults[sentenceNumber]['isDisplay'] = false;
		this.btResults[sentenceNumber]['sentence'] = btr;
		this.btResults[sentenceNumber]['isDisplay'] = false;
		this.licenseArea.addLicenses(this.tLicenseCache);
		return this.writeTexts();
	},
	cachedBackTranslate : function(cacheKey, sentenceNumber){
//		tr = this.translateCache.get(cacheKey).translate;
		btr = this.backTranslateCache.get(cacheKey).translate;
//		this.tResults[sentenceNumber]['sentence'] = tr;
//		this.tResults[sentenceNumber]['isDisplay'] = false;
		this.btResults[sentenceNumber]['sentence'] = btr;
		this.btResults[sentenceNumber]['isDisplay'] = false;
		this.licenseArea.addLicenses(this.btLicenseCache);
		return this.writeBackTexts();
	},

	runAjaxRequest : function(sentenceNumber, sentences, sourceLang, targetLang){
		var requestParams = $H({
				sourceLang : sourceLang
				, targetLang : targetLang
				, backTranslate : true
				, content : sentences[sentenceNumber]
			}).toQueryString();
		new Ajax.Request(
				'./php/ajax/backtranslate.php'
//				'./php/ajax/translate.php'
				, {
					method : 'post'
					, parameters : requestParams
					, onSuccess : function(httpObj){
						var isEnd = false;
						try {
							try{
								var result = httpObj.responseText.evalJSON()[0];
							}catch(e){
								this.tResults[sentenceNumber]['sentence'] = this.te.TIME_OUT_ERROR_CODE + this.ERROR_SERVER_RESPONSE;
								this.tResults[sentenceNumber]['isDisplay'] = false;
								this.btResults[sentenceNumber]['sentence'] = this.te.TIME_OUT_ERROR_CODE + this.ERROR_SERVER_RESPONSE;
								this.btResults[sentenceNumber]['isDisplay'] = false;
								isEnd = this.writeTexts();
								throw httpObj.responseText;
							}
							this.licenseArea.addLicenses(result.licenseInformation);
							this.tLicenseCache = result.licenseInformation;
							if(result.status.toLowerCase() == 'error'){
								var errorCode = this.se.TIME_OUT_ERROR_CODE;
								this.tResults[sentenceNumber]['sentence'] = errorCode + result.translate;
								this.tResults[sentenceNumber]['isDisplay'] = false;
								this.btResults[sentenceNumber]['sentence'] = errorCode + result.backtranslate;
								this.btResults[sentenceNumber]['isDisplay'] = false;
								isEnd = this.writeTexts();
								throw result.translate;
							}
							this.tResults[sentenceNumber]['sentence'] = result.translate;
							this.tResults[sentenceNumber]['isDisplay'] = false;
							this.btResults[sentenceNumber]['sentence'] = result.backtranslate;
							this.btResults[sentenceNumber]['isDisplay'] = false;
							if(this.translateCache.size() > this.CACHE_LIMIT){
								this.translateCache.remove(this.translateCache.keys()[0]);
							}
							this.translateCache.put(
									sourceLang + "2" + targetLang + ":" + sentences[sentenceNumber]
									                                                , result);
							isEnd = this.writeTexts();
							this.threadPoolCount--;
							if(isEnd){
								this.endProcess();
								return;
							}
							this.doThreadingTranslate(
									sentenceNumber + this.THREAD_COUNT, sentences, sourceLang, targetLang);
						}catch(e){
							try{
								// 20091028 add
								throw new Error();
								
								this.threadPoolCount--;
								this.errorCallCount++;
								if(this.errorCallCount >= this.ERROR_CALL_LIMIT){
									this.setCancel();
									alert(this.ERROR_LIMIT_MESSAGE + "\n\n"
											+ e.name + ":" + e.message + "\n" + e.toString());
									this.errorCallCount = 0;
									return;
								}
								if((sentenceNumber + this.THREAD_COUNT) > (sentences.length - 1)){
									this.endProcess();
									this.errorCallCount = 0;
									return;
								}
								this.doThreadingTranslate(
										sentenceNumber + this.THREAD_COUNT, sentences, sourceLang, targetLang);
							}catch(e){
								this.threadPoolCount = 0;
								this.setCancel();
								alert(this.FAILED_MESSAGE);
								//alert('E371: ' + this.FAILED_MESSAGE + "\n" + e.name + ":" + e.message + "\n" + e.toString());
							}
						}
					}.bind(this)
					, onFailure : function(e) {
						this.threadPoolCount = 0;
						this.setCancel();
						alert(this.FAILED_MESSAGE);
						//alert('E378: ' + this.FAILED_MESSAGE + "\n" + e.name + ":" + e.message + "\n" + e.toString());
					}.bind(this)
				}
			);
	},

	runBackTranslateAjaxRequest : function(sentenceNumber, sentences, sourceLang, targetLang){
		var requestParams = $H({
				sourceLang : sourceLang
				, targetLang : targetLang
				, content : sentences[sentenceNumber]
			}).toQueryString();
		new Ajax.Request(
				'./php/ajax/backtranslate.php'
//				'./php/ajax/translate.php'
				, {
					method : 'post'
					, parameters : requestParams
					, onSuccess : function(httpObj){
						var isEnd = false;
						try {
							try{
								var result = httpObj.responseText.evalJSON()[0];
							}catch(e){
//								this.tResults[sentenceNumber]['sentence'] = this.te.TIME_OUT_ERROR_CODE + this.ERROR_SERVER_RESPONSE;
//								this.tResults[sentenceNumber]['isDisplay'] = false;
								this.btResults[sentenceNumber]['sentence'] = this.te.TIME_OUT_ERROR_CODE + this.ERROR_SERVER_RESPONSE;
								this.btResults[sentenceNumber]['isDisplay'] = false;
								isEnd = this.writeBackTexts();
								throw httpObj.responseText;
							}
							this.licenseArea.addLicenses(result.licenseInformation);
							this.btLicenseCache = result.licenseInformation;
							if(result.status == 'error'){
								var errorCode = this.se.TIME_OUT_ERROR_CODE;
//								this.tResults[sentenceNumber]['sentence'] = errorCode + result.translate;
//								this.tResults[sentenceNumber]['isDisplay'] = false;
								this.btResults[sentenceNumber]['sentence'] = errorCode + result.translate;
								this.btResults[sentenceNumber]['isDisplay'] = false;
								isEnd = this.writeBackTexts();
								throw result.translate;
							}
//							this.tResults[sentenceNumber]['sentence'] = result.translate;
//							this.tResults[sentenceNumber]['isDisplay'] = false;
							this.btResults[sentenceNumber]['sentence'] = result.translate;
							this.btResults[sentenceNumber]['isDisplay'] = false;
							if(this.backTranslateCache.size() > this.CACHE_LIMIT){
								this.backTranslateCache.remove(this.backTranslateCache.keys()[0]);
							}
							this.backTranslateCache.put(
									sourceLang + "2" + targetLang + ":" + sentences[sentenceNumber]
									                                                , result);
							isEnd = this.writeBackTexts();
							this.threadPoolCount--;
							if(isEnd){
								this.endProcess();
								return;
							}
							this.doThreadingBackTranslate(
									sentenceNumber + this.THREAD_COUNT, sentences, sourceLang, targetLang);
						}catch(e){
							try{
								throw new Error();
								this.threadPoolCount--;
								this.errorCallCount++;
								if(this.errorCallCount >= this.ERROR_CALL_LIMIT){
									this.setCancel();
									alert(this.ERROR_LIMIT_MESSAGE + "\n\n"
											+ e.name + ":" + e.message + "\n" + e.toString());
									this.errorCallCount = 0;
									return;
								}
								if((sentenceNumber + this.THREAD_COUNT) > (sentences.length - 1)){
									this.endProcess();
									this.errorCallCount = 0;
									return;
								}
								this.doThreadingBackTranslate(
										sentenceNumber + this.THREAD_COUNT, sentences, sourceLang, targetLang);
							}catch(e){
								this.threadPoolCount = 0;
								this.setBackTranslateCancel();
								alert(this.FAILED_MESSAGE);
								//alert('E456: ' + this.FAILED_MESSAGE + "\n" + e.name + ":" + e.message + "\n" + e.toString());
							}
						}
					}.bind(this)
					, onFailure : function(e) {
						this.threadPoolCount = 0;
						this.setBackTranslateCancel();
						alert(this.FAILED_MESSAGE);
						//alert('E463: ' + this.FAILED_MESSAGE + "\n" + e.name + ":" + e.message + "\n" + e.toString());
					}
				}
			);
	},

	writeTexts : function(){
		var tr = this.tResults;
		var allTranslationDisplay = false;
		var minus = 0;
		for(var i = 0; i < this.maxSentence; i++){
			if(tr[i] != undefined && tr[i]['sentence'] != null && tr[i]['sentence'] != undefined){
				if(tr[i]['sentence'] == "[[#ret]]" || tr[i]['sentence'] == "."){
					minus++;
				}
				if(! tr[i]['isDisplay']){
					this.te.setEditorText(i - minus, tr[i]['sentence']);
					tr[i]['isDisplay'] = true;
					if(this.maxSentence -1 == i){
						allTranslationDisplay = true;
					}
				}
			}else{
				break;
			}
		}

		var btr = this.btResults;
		var allBacktranslationDisplay = false;
		minus = 0;
		for(var i = 0; i < this.maxSentence; i++){
			if(btr[i] != undefined && btr[i]['sentence'] != null && btr[i]['sentence'] != undefined){
				if(btr[i]['sentence'] == "[[#ret]]" || btr[i]['sentence'] == "."){
					minus++;
				}
				if(! btr[i]['isDisplay']){
					this.bta.setEditorText(i - minus, btr[i]['sentence']);
					btr[i]['isDisplay'] = true;
					if(this.maxSentence -1 == i){
						allBacktranslationDisplay = true;
					}
				}
			}else{
				break;
			}
		}
		return allTranslationDisplay && allBacktranslationDisplay;
	},

	writeBackTexts : function(){
		var btr = this.btResults;
		var allBacktranslationDisplay = false;
		minus = 0;
		for(var i = 0; i < this.maxSentence; i++){
			if(btr[i] != undefined && btr[i]['sentence'] != null && btr[i]['sentence'] != undefined){
				if(btr[i]['sentence'] == "[[#ret]]" || btr[i]['sentence'] == "."){
					minus++;
				}
				if(! btr[i]['isDisplay']){
					this.bta.setEditorText(i - minus, btr[i]['sentence']);
					btr[i]['isDisplay'] = true;
					if(this.maxSentence -1 == i){
						allBacktranslationDisplay = true;
					}
				}
			}else{
				break;
			}
		}
		return allBacktranslationDisplay;
	},
	
	SetTranslateButtonDisabled : function(disable) {
		if (disable) {
			$$('#translate-button span')[0].addClassName('btn_gray01');
			$$('#translate-button span')[0].removeClassName('btn_blue01');
		} else {
			$$('#translate-button span')[0].removeClassName('btn_gray01');
			$$('#translate-button span')[0].addClassName('btn_blue01');
		}
		this.SetClearButtonDisabled(disable);
	},
	SetBackTranslateButtonDisabled : function(disable) {
		if (disable) {
			$$('#back-translate-button span')[0].addClassName('btn_gray01');
			$$('#back-translate-button span')[0].removeClassName('btn_blue01');
		} else {
			$$('#back-translate-button span')[0].removeClassName('btn_gray01');
			$$('#back-translate-button span')[0].addClassName('btn_blue01');
		}
		this.SetClearButtonDisabled(disable);
	},
	SetClearButtonDisabled : function(disable) {
		if (disable) {
			$$('#clear-button span')[0].addClassName('btn_gray01');
			$$('#clear-button span')[0].removeClassName('btn_blue01');
		} else {
			$$('#clear-button span')[0].removeClassName('btn_gray01');
			$$('#clear-button span')[0].addClassName('btn_blue01');
		}
	},
	checkEndProcess : function(){
		if(this.threadPoolCount == 0){
			this.endProcess();
		}
	},

	endProcess : function(){
		this.hideExecutingInformation();
		this.isIdle = true;
		this.isCancel = false;
		clearInterval(this.timerId);
		this.timerId = null;
		clearInterval(this.timeTimerId);
		this.timeTimerId = null;
		Event.fire(document,"dom:TranslationDone");
		this.SetTranslateButtonDisabled(false);
		this.SetBackTranslateButtonDisabled(false);
	},

	displayExecutingInformation : function(){
		$('translating').style.display = 'inline';
		$('backtranslating').style.display = 'inline';
	},

	displayBackTranslateExecutingInformation : function(){
//		$('translating').style.display = 'inline';
		$('backtranslating').style.display = 'inline';
	},

	hideExecutingInformation : function(){
		$('translating').style.display = 'none';
		$('translatingMessage').style.display = 'inline';
		$('cancelMessage').style.display = 'none';

		$('backtranslating').style.display = 'none';
		$('backtranslatingMessage').style.display = 'inline';
		$('cancelBackMessage').style.display = 'none';

		$('translate-button').style.display = 'inline';
		$('cancel-button').style.display = 'none';
		$('parsing').style.display = 'none';

		$('back-translate-button').style.display = 'inline';
		$('back-translate-cancel-button').style.display = 'none';
		$('translate-parsing').style.display = 'none';
	},

	setCancel : function(){
		if(this.timerId == null){
			this.timerId = setInterval(this.checkEndProcess.bind(this), 700);
			this.isCancel = true;
			$('translatingMessage').style.display = 'none';
			$('cancelMessage').style.display = 'inline';
			$('backtranslatingMessage').style.display = 'none';
			$('cancelBackMessage').style.display = 'inline';
		}
	},

	setBackTranslateCancel : function(){
		if(this.timerId == null){
			this.timerId = setInterval(this.checkEndProcess.bind(this), 700);
			this.isCancel = true;
//			$('translatingMessage').style.display = 'none';
//			$('cancelMessage').style.display = 'inline';
			$('backtranslatingMessage').style.display = 'none';
			$('cancelBackMessage').style.display = 'inline';
		}
	}
});
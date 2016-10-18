/**********************************************************************
* /js/component/web-translation-workspace.js
* Copyright (C) 2007-2008 Kyoto University
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-
* 1301  USA
***********************************************************************/
String.prototype.trim = function() {
    return this.replace(/^[ ]+|[ ]+$/g, '');
}
String.prototype.escapeQuotation = function() {
	return this.replace(/\"/g, '&quot;').replace(/\'/g, '&#39;');
}
String.prototype.unescapeQuotation = function() {
	return this.replace(/&quot;/g, '"').replace(/&#39;/g, '\'');
}
String.prototype.replaceAll = function (org, dest){
	return this.split(org).join(dest);
}
var WebTranslationWorkspace = Class.create();

WebTranslationWorkspace.prototype = {
	largeDictionaryId: "",
	undoStack : {"original" : [], "translate" : []},
	UNDO_ORIGINAL_KEY : "original",
	UNDO_TRANSLATE_KEY : "translate",
	UNDO_MAX_SAVE_COUNT : 3,

	TRANSLATE_PARALLEL_NUM : 1,
	translateAbortFlag : false,
	translateCounter : 0,
	translateNum : 0,
	translatePool : 0,
	translatingNo : [],
	resApplyTemplate : null,
	resAnalysisHtml : null,

//	SOURCE_TEXT_MAX_LENGTH : 200,
//	SEPARATOR_LENGTH : 8,

	initialize: function(){
		this.undoPush(this.UNDO_ORIGINAL_KEY, $("original-webpage-editor").value);
		this.undoPush(this.UNDO_TRANSLATE_KEY, $("translated-webpage-editor").value);
	},

	enableThirdLanguage: function(){

	},

	disableThirdLanguage: function(){

	},

	enableFourthLanguage: function(){

	},
	disableFourthLanguage: function(){

	},

	importWebPage : function(){
		Element.show($("NOW_IMPORTING"));
		var url = $("url-input").value;
		var callobj = {
			'url' : url
		};
		var hash = $H(callobj);
		var formText = hash.toQueryString();

		new Ajax.Request(
			'./?page=import-web-page',
			{
				method:'post',
				parameters:formText,
				onSuccess:function(httpObj){
					$("original-webpage-editor").value = httpObj.responseText;
					Element.hide($("NOW_IMPORTING"));
					Event.fire(document,"dom:TranslationDone");
				},
				onFailure:function(){
					alert(Const.Message.Error.ServerError);
					Element.hide($("NOW_IMPORTING"));
				},
				onComplete:function(){}
			}
		);
	},

	originalToTranslated : function(activeTemplatePairs, translateLoadedTemplates, createEditTemplateLoadedTemplates){
		if (this.translateAbortFlag){
			alert(Const.Message.Info.AbortTraslating);
			return ;
		}
		serviceInformation.reset();
		this.translateAbortFlag = false;
		var callobj = {};
		var templateCount = 0;
		for(var i = 0, len = activeTemplatePairs.pairData.length; i < len; i++){
			var item = activeTemplatePairs.pairData[i];
			if((typeof item) == 'undefined'){
				continue;
			}
			callobj['template[' + templateCount + '][SOURCE_TEXT]'] = item.SOURCE_TEXT.escapeQuotation();
			callobj['template[' + templateCount + '][TARGET_TEXT]'] = item.TARGET_TEXT.escapeQuotation();
			templateCount++;
		}

		for(var i = 0, len = translateLoadedTemplates.length; i < len; i++){
			var tmpTemplate = translateLoadedTemplates[i];
			for(var j = 0, len2 = tmpTemplate.pairData.length; j < len2; j++){
				var item = tmpTemplate.pairData[j];
				if((typeof item) == 'undefined'){
					continue;
				}
				callobj['template[' + templateCount + '][SOURCE_TEXT]'] = item.SOURCE_TEXT.escapeQuotation();
				callobj['template[' + templateCount + '][TARGET_TEXT]'] = item.TARGET_TEXT.escapeQuotation();
				templateCount++;
			}
		}

		for(var i = 0, len = createEditTemplateLoadedTemplates.length; i < len; i++){
			var tmpTemplate = createEditTemplateLoadedTemplates[i];
			for(var j = 0, len2 = tmpTemplate.pairData.length; j < len2; j++){
				var item = tmpTemplate.pairData[j];
				if((typeof item) == 'undefined'){
					continue;
				}
				callobj['template[' + templateCount + '][SOURCE_TEXT]'] = item.SOURCE_TEXT.escapeQuotation();
				callobj['template[' + templateCount + '][TARGET_TEXT]'] = item.TARGET_TEXT.escapeQuotation();
				templateCount++;
			}
		}
		this.undoPush(this.UNDO_ORIGINAL_KEY, $("original-webpage-editor").value);
		this.undoPush(this.UNDO_TRANSLATE_KEY, $("translated-webpage-editor").value);

		var html = $("original-webpage-editor").value;
		var tmp = html.replace(/(\n|\r| |\t|　)+/g,"")
		if(tmp == ""){
			$("original-webpage-editor").value = "";
			return;
		}

		html = html.escapeQuotation();
		callobj['html'] = html;
		callobj['from'] = sourceLangSelecter.getSelectedLangCode();
		callobj['to'] = targetLangSelecter.getSelectedLangCode();

		$('now-translating').style.display = 'block';
		$('translating-action-translated').style.display = 'none';
		$('now-translating-abort').style.display = 'inline';
		$('now-translating-text').style.display = 'inline';
		$('now-translating-msg').innerHTML = Const.Message.Info.NowTraslating;
		$('now-translating-text').innerHTML = Const.Message.Info.InitializeTranslate;

		if (this.translateAbortFlag){
			return;
		}
		this._translateOfApplyTemplate(callobj);
		return ;
		/*
		var hash = $H(callobj);
		var formText = hash.toQueryString();
		new Ajax.Request(
			'./php/ajax/web-translation/translate.php',
			{
				method:'post',
				parameters:formText,
				onSuccess:function(httpObj){
					var resultObj = null;
					try{
						var resultObj = httpObj.responseText.evalJSON();
					}catch(e){
						$('now-translating').style.display = 'none';
						alert(Const.Message.Error.ServerError);
					}
					if (resultObj.status == 'ERROR'){
						$("translated-webpage-editor").value = resultObj.message;
						$("back-translated-webpage-editor").value = resultObj.message;
					}else{
						$("translated-webpage-editor").value = resultObj.contents.result.targetWebPageContents;
						$("back-translated-webpage-editor").value = resultObj.contents.result.backTransWebPageContents;
					}
					$('now-translating').style.display = 'none';
				},
				onFailure:function(){
					alert(Const.Message.Error.ServerError);
				},
				onComplete:function(){}
			}
		);
		*/
	},

	_translateOfApplyTemplate : function(callobj){
		if(this.translateAbortFlag) {
			return;
		}
		var hash = $H(callobj);
		var formText = hash.toQueryString();
		var controller = this;
		$('now-translating').style.display = 'block';
		$('now-translating-text').innerHTML = Const.Message.Info.ApplyingTemplate;
		new Ajax.Request(
			'?page=service-client-apply-template',
			{
				method:'post',
				parameters:formText,
				controller:controller,
				callobj:callobj,
				onSuccess:function(httpObj){
					var resultObj = null;
					try{
						resultObj = httpObj.responseText.evalJSON();
					}catch(e){
						$('now-translating').style.display = 'none';
						alert(Const.Message.Error.ServerError);
					}
					if (resultObj.status == 'ERROR'){
						$("translated-webpage-editor").value = resultObj.message;
						$("back-translated-webpage-editor").value = resultObj.message;
						$('now-translating').style.display = 'none';
						alert(Const.Message.Error.ServerError);
					}else{
						controller.resApplyTemplate = resultObj;
						controller._translateOfAnalysisHtml(callobj['from'], resultObj.contents.result.skeletonHtml);
					}
					//$('now-translating').style.display = 'none';
				},
				onFailure:function(){
						$('now-translating').style.display = 'none';
						alert(Const.Message.Error.ServerError);
				},
				onComplete:function(){
					if (controller.translateAbortFlag){
						controller.translateAborted();
					}
				}
			}
		);
	},

	_translateOfAnalysisHtml : function(sourceLang, htmlText){
//		alert("_translateOfAnalysisHtml-" + sourceLang + "-" + htmlText);
		if(this.translateAbortFlag) {
			return;
		}
		var callobj = {};
		callobj['from'] = sourceLang;
		callobj['html'] = htmlText;
		var hash = $H(callobj);
		var formText = hash.toQueryString();
		var controller = this;
		$('now-translating').style.display = 'block';
		$('now-translating-text').innerHTML = Const.Message.Info.AnalysisHtml;
		new Ajax.Request(
			'?page=service-client-analysis-html',
			{
				method:'post',
				parameters:formText,
				controller:controller,
				callobj:callobj,
				onSuccess:function(httpObj){
					var resultObj = null;
					try{
						var resultObj = httpObj.responseText.evalJSON();
					}catch(e){
						$('now-translating').style.display = 'none';
						alert(Const.Message.Error.ServerError);
						controller.translateAbortFlag = true;
					}
					if (resultObj.status == 'ERROR'){
						$("translated-webpage-editor").value = resultObj.message;
						$("back-translated-webpage-editor").value = resultObj.message;
						$('now-translating').style.display = 'none';
						alert(Const.Message.Error.ServerError);
						controller.translateAbortFlag = true;
					}else{
						controller.resAnalysisHtml = resultObj;
						controller.resAnalysisHtml.contents.result['targetText'] = {};
						controller.resAnalysisHtml.contents.result['backText'] = {};
						if(resultObj.contents.result.sourceText.length > 0){
							controller._translateOfLoopRedy(resultObj);
						}else{
							controller.translateOfreplacementHtml();
						}
					}
					//$('now-translating').style.display = 'none';
				},
				onFailure:function(){
						$('now-translating').style.display = 'none';
						alert(Const.Message.Error.ServerError);
				},
				onComplete:function(){
					if (controller.translateAbortFlag){
						controller.translateAborted();
					}
				}
			}
		);
	},

	_translateOfLoopRedy : function(analysisObj){
		this.translateNum = analysisObj.contents.result.sourceText.length;
		if(this.translateNum == 0){
			this.translateOfreplacementHtml();
		}else{
			this.translateCounter = 0;
			this.translatePool = 0;
			this._translateOfdoTranslate();
		}
	},
/*
	_translateOfdoTranslate : function(){
		if(this.translateAbortFlag) {
			return;
		}
		var callobj = {};

		callobj['dictionary'] = this.largeDictionaryId;
		callobj['from'] = sourceLangSelecter.getSelectedLangCode();
		callobj['to'] = targetLangSelecter.getSelectedLangCode();

		var viewSourceText = "";
		for(var i=0; i<this.TRANSLATE_PARALLEL_NUM; i++){
			if (this.translateCounter + i < this.translateNum) {
				var SourceHtml = this.resAnalysisHtml.contents.result.sentenceAndCodes[this.translateCounter + i].analysisText;
				callobj['html[' + i + ']'] = SourceHtml;

				if(viewSourceText != ""){viewSourceText += ", ";}
				viewSourceText += SourceHtml;
			}
		}
//		callobj['html'] = this.resAnalysisHtml.contents.result.sentenceAndCodes[this.translateCounter].analysisText;

		$('now-translating').style.display = 'block';
		if(viewSourceText.length > 20){
			viewSourceText = viewSourceText.substring(0,20) + "...";
		}
		$('now-translating-text').innerHTML = '[' + viewSourceText + ']';

		var hash = $H(callobj);
		var formText = hash.toQueryString();
		var controller = this;
		new Ajax.Request(
			'?page=service-client-proxy',
			{
				method:'post',
				parameters:formText,
				controller:controller,
				callobj:callobj,
				onSuccess:function(httpObj){
					var resultObj = null;
					try{
						var resultObj = httpObj.responseText.evalJSON();
					}catch(e){
						$('now-translating').style.display = 'none';
						alert('The translator is currently out of service.');
					}
					if (resultObj.status == 'ERROR'){
						$("translated-webpage-editor").value = resultObj.message;
						$("back-translated-webpage-editor").value = resultObj.message;
					}else{
						//serviceInformation.update(resultObj.profile);
						// $("translated-webpage-editor").value = resultObj.contents.result.skeletonHtml;
						var len = resultObj.results.length;
						for(var i=0; i<len; i++){
							if(resultObj.results[i].status == 'OK'){
								var TgtTxt = resultObj.results[i].targetText;
								var bakTxt = resultObj.results[i].backText;
							} else {
								var TgtTxt = controller.resAnalysisHtml.contents.result.sentenceAndCodes[controller.translateCounter + i].sourceText;
								var bakTxt = controller.resAnalysisHtml.contents.result.sentenceAndCodes[controller.translateCounter + i].sourceText;
							}
							controller.resAnalysisHtml.contents.result.targetText[controller.translateCounter + i] = TgtTxt;
							controller.resAnalysisHtml.contents.result.backText[controller.translateCounter + i] = bakTxt;
						}
						controller.translateCounter = controller.translateCounter + len;
						if (controller.translateCounter < controller.translateNum) {
							controller._translateOfdoTranslate();
						} else {
							$('now-translating-text').innerHTML = "";
							controller.translateOfreplacementHtml();
						}
					}
				},
				onFailure:function(){
					$('now-translating').style.display = 'none';
					alert('The translator is currently out of service.');
				},
				onComplete:function(){
					if (controller.translateAbortFlag){
						controller.translateAborted();
					}
				}
			}
		);
	},
*/
	_translateOfdoTranslate : function(){
		if(this.translateAbortFlag) {
			return;
		}

		var srcLang = sourceLangSelecter.getSelectedLangCode();
		var tgtLang = targetLangSelecter.getSelectedLangCode();
		var SourceText = new Array();
		var sentenceNumber = new Array();

		var viewSourceText = "";
		for(var i=0; i<this.TRANSLATE_PARALLEL_NUM; i++){
			if (this.translateCounter + i < this.translateNum) {

				var sourceTextLength = 0;
				var sourceTextNum = 0;
				var analysisText = this.resAnalysisHtml.contents.result.sentenceAndCodes[this.translateCounter + i + sourceTextNum].analysisText;

				do {
					SourceText.push(analysisText);
					sentenceNumber.push(this.translateCounter + i + sourceTextNum);
					sourceTextLength += analysisText.length
					// + this.SEPARATOR_LENGTH;
					sourceTextNum++;

					if (this.translateCounter + i + sourceTextNum == this.translateNum) {
						break;
					}

					analysisText = this.resAnalysisHtml.contents.result.sentenceAndCodes[this.translateCounter + i + sourceTextNum].analysisText;
				} while (true);
					//(sourceTextLength + analysisText.length < this.SOURCE_TEXT_MAX_LENGTH);
					//(sourceTextLength + analysisText.length + this.SEPARATOR_LENGTH < this.SOURCE_TEXT_MAX_LENGTH);

				this.runAjaxRequest(sentenceNumber, SourceText, srcLang, tgtLang);

				//viewSourceText += ', ' + this.resAnalysisHtml.contents.result.sentenceAndCodes[this.translateCounter + i].analysisText;
				if(viewSourceText != ""){viewSourceText += ", ";}
				viewSourceText += SourceText.join("");

				SourceText.length = 0;
				sentenceNumber.length = 0;
			}
		}

		$('now-translating').style.display = 'block';
		if(viewSourceText.length > 20){
			viewSourceText = viewSourceText.substring(0,20) + "...";
		}
		$('now-translating-text').innerHTML = '['+this.translateCounter+' / '+this.translateNum+'] ' + '' + viewSourceText + '';

	},

	translateOfreplacementHtml : function(){
		//if (this.translateAbortFlag) {return;}
		try{
			for(i=0; i<this.translateNum; i++){
				var targetText = this.resAnalysisHtml.contents.result.targetText[i];
				var backText = this.resAnalysisHtml.contents.result.backText[i];
				var codeAndWord = this.resAnalysisHtml.contents.result.sentenceAndCodes[i];
				if(codeAndWord.codes){
					for(j=0; j<codeAndWord.codes.length; j++){
						//alert("codeAndWord.codes[j]=" + codeAndWord.codes[j] + " codeAndWord.words[j]=" + codeAndWord.words[j]);
						targetText = targetText.replace(codeAndWord.codes[j].trim(), codeAndWord.words[j]);
						backText = backText.replace(codeAndWord.codes[j].trim(), codeAndWord.words[j]);
					}
				}
				//alert(targetText);
				this.resAnalysisHtml.contents.result.targetText[i] = targetText;
				this.resAnalysisHtml.contents.result.backText[i] = backText;
			}

			var html = this.resAnalysisHtml.contents.result.skeletonHtml;
			var back = this.resAnalysisHtml.contents.result.skeletonHtml;
			for(i=0; i<this.resAnalysisHtml.contents.result.sentenceCodes.length; i++){
				html = html.replace(this.resAnalysisHtml.contents.result.sentenceCodes[i].trim(), this.resAnalysisHtml.contents.result.targetText[i]);
				if (this.resAnalysisHtml.contents.result.backText[i] == "") {
					//back = "";
				}
				back = back.replace(this.resAnalysisHtml.contents.result.sentenceCodes[i].trim(), this.resAnalysisHtml.contents.result.backText[i]);
			}

			for(i=0; i<this.resApplyTemplate.contents.result.templateCodes.length; i++){
				html = html.replaceAll(this.resApplyTemplate.contents.result.templateCodes[i].trim(), this.resApplyTemplate.contents.result.templates[i].target);
				back = back.replaceAll(this.resApplyTemplate.contents.result.templateCodes[i].trim(), this.resApplyTemplate.contents.result.templates[i].source);
			}
		}catch(e){
			alert(e.message);
		}
		html = html.unescapeQuotation();
		back = back.unescapeQuotation();
		$("translated-webpage-editor").value = html;
		$("back-translated-webpage-editor").value = back;
		this.translateOfFormatHtml('target', html);
		if (back != ""){
			this.translateOfFormatHtml('back', back);
		}
		$('now-translating').style.display = 'none';
		$('now-translating-abort').style.display = 'none';
		$('translating-action-translated').style.display = 'inline';
		Event.fire(document,"dom:TranslationDone");
	},

	translateOfFormatHtml : function(mode, htmlText){
		var callobj = {};
		callobj['html'] = htmlText;
		var hash = $H(callobj);
		var formText = hash.toQueryString();
		var controller = this;
		new Ajax.Request('?page=service-client-format-html',
			{
				method:'post',
				asynchronous:false,
				parameters:formText,
				controller:controller,
				mode:mode,
				callobj:callobj,
				onSuccess:function(httpObj){
					var resultObj = null;
					try{
						var resultObj = httpObj.responseText.evalJSON();
					}catch(e){
						$('now-translating').style.display = 'none';
						alert(Const.Message.Error.ServerError);
					}
					if (resultObj.status == 'ERROR'){
						$("translated-webpage-editor").value = resultObj.message;
						$("back-translated-webpage-editor").value = resultObj.message;
						$('now-translating').style.display = 'none';
						alert(Const.Message.Error.ServerError);
					}else{
						if (mode == 'target') {
							$("translated-webpage-editor").value = resultObj.contents.result;
						} else {
							$("back-translated-webpage-editor").value = resultObj.contents.result;
						}
					}
					//$('now-translating').style.display = 'none';
				},
				onFailure:function(){
					$('now-translating').style.display = 'none';
					alert(Const.Message.Error.ServerError);
				},
				onComplete:function(){
				}
			}
		);
	},

	translatingAbort : function(){
		$('now-translating-msg').innerHTML = Const.Message.Info.AbortTraslating;
		//$('now-translating-abort').style.display = 'none';
		$('now-translating-text').style.display = 'none';
		this.translateAbortFlag = true;
	},

	translateAborted : function(){
		$('now-translating').style.display = 'none';
		$('now-translating-abort').style.display = 'none';
		$('translating-action-translated').style.display = 'inline';
		$('now-translating-text').style.display = 'inline';
		this.translateAbortFlag = false;
	},

	__hoge : function(cnt){
		alert("__hoge()=" + this.resAnalysisHtml.contents.result.targetText[cnt]);
	},

	originalPageDisplay : function(){

		this.undoPush(this.UNDO_ORIGINAL_KEY, $("original-webpage-editor").value);

//		var subwin = window.open();
//		subwin.document.write(this.stripScript($("original-webpage-editor").value));
//		subwin.document.close();
		var m = new displayManager();
		m.display($("original-webpage-editor").value);
	},

	translatedPageDisplay : function(){

		this.undoPush(this.UNDO_TRANSLATE_KEY, $("translated-webpage-editor").value);

//		var subwin = window.open();
//		subwin.document.write(this.stripScript($("translated-webpage-editor").value));
//		subwin.document.close();
		var m = new displayManager();
		m.display($("translated-webpage-editor").value);
	},

	stripScript : function(value) {
		var coercionRegx = new RegExp("<(script)[^\"\']*?(\"[^\"]*?\"|\'[^\']*?\'|[^\"\']*?)[^/]*?>.*?</(script)[^/]*?>", "ig");
		return value.replace(coercionRegx, '');
	},

	undoPush : function(key, text){
		this.undoStack[key].push(text);
		if( this.undoStack[key].length > this.UNDO_MAX_SAVE_COUNT ){
			this.undoStack[key].shift();
		}
		this.undoDisplay(key);
	},

	undoOriginalOnChange : function(){
		this.undoDisplay(this.UNDO_ORIGINAL_KEY);
	},

	undoTranslatedOnChange : function(){
		this.undoDisplay(this.UNDO_TRANSLATE_KEY);
	},

	originalUndo : function(){

		value = this.undoStack[this.UNDO_ORIGINAL_KEY];
		if( value.length > 1 && $("original-webpage-editor").value == value[value.length-1] ){
			value.pop();
		}
		$("original-webpage-editor").value = value[value.length-1];
		this.undoDisplay(this.UNDO_ORIGINAL_KEY);

	},

	translatedUndo : function(){

		value = this.undoStack[this.UNDO_TRANSLATE_KEY];
		if( value.length > 1 && $("translated-webpage-editor").value == value[value.length-1] ){
			value.pop();
		}
		$("translated-webpage-editor").value = value[value.length-1];
		this.undoDisplay(this.UNDO_TRANSLATE_KEY);

	},

	undoDisplay : function(key){
		if( key == this.UNDO_ORIGINAL_KEY ){
			value = this.undoStack[this.UNDO_ORIGINAL_KEY];
			if( (value.length == 1 && $("original-webpage-editor").value != value[0]) || value.length > 1 ){
				$('original-webpage-undo').style.cursor = 'pointer';
				$('original-webpage-undo').disabled = false;
				Element.removeClassName($('original-webpage-undo'), 'btn_gray01')
				Element.addClassName($('original-webpage-undo'), 'btn_blue01');
			}else{
				$('original-webpage-undo').style.cursor = 'default';
				$('original-webpage-undo').disabled = true;
				Element.removeClassName($('original-webpage-undo'), 'btn_blue01')
				Element.addClassName($('original-webpage-undo'), 'btn_gray01');
			}
		}else{
			value = this.undoStack[this.UNDO_TRANSLATE_KEY];
			if( (value.length == 1 && $("translated-webpage-editor").value != value[0]) || value.length > 1 ){
				$('translated-webpage-undo').style.cursor = 'pointer';
				$('translated-webpage-undo').disabled = false;
				Element.removeClassName($('translated-webpage-undo'), 'btn_gray01')
				Element.addClassName($('translated-webpage-undo'), 'btn_blue01');
			}else{
				$('translated-webpage-undo').style.cursor = 'default';
				$('translated-webpage-undo').disabled = true;
				Element.removeClassName($('translated-webpage-undo'), 'btn_blue01')
				Element.addClassName($('translated-webpage-undo'), 'btn_gray01');
			}
		}
	},

	uploadOriginal : function(result){
		if( result['status'] != "OK" ){
			alert(result['message']);
			return ;
		}
		$('original-webpage-editor').value = result['contents'];
		$('original-webpage-upload-dummy-iframe-div').innerHTML = '';
		this.undoPush(this.UNDO_ORIGINAL_KEY, $("original-webpage-editor").value);
	},

	uploadTranslated : function(result){
		if( result['status'] != "OK" ){
			alert(result['message']);
			return ;
		}
		$('translated-webpage-editor').value = result['contents'];
		$('translated-webpage-upload-dummy-iframe-div').innerHTML = '';
		this.undoPush(this.UNDO_TRANSLATE_KEY, $("translated-webpage-editor").value);
	},

	applyUploadTemplate : function(result){
		if( result['status'] != "OK" ){
			alert(result['message']);
			return ;
		}

		for(var index = 0;index < result['contents'].length;index++){
			var indexMap = [];
			for(var i = 0, len = translate_loadedTemplates.length; i < len; i++){
				indexMap[translate_loadedTemplates[i].index] = 1;

			}
			var newTemplateIndex = 1;
			for(var i = 1, len = indexMap.length<=7?indexMap.length:7; i <= len; i++){
				if ((typeof indexMap[i]) == 'undefined'){
					newTemplateIndex = i;
					break;
				}
			}
			if(i > 7){
				var msg = Const.Message.Error.templateOverMax;
				alert(msg.replace('{0}',7));
				return;
			}
			var item = {
				pairData: result['contents'][index],
				index:newTemplateIndex,
				name:result['path'][index],
				fnDelete:function(){
					for(var j = 0; j < translate_loadedTemplates.length; j++){
						if(this == translate_loadedTemplates[j]){
							translate_loadedTemplates = translate_loadedTemplates.slice(0,j).concat(translate_loadedTemplates.slice(j + 1,translate_loadedTemplates.length))
							translate_createLoadedTemplateList();
							break;
						}
					}
				}
			};

			translate_loadedTemplates.push(item);
			translate_createLoadedTemplateList();
		}
		$('apply-template-upload-template-dummy-iframe-div').innerHTML = '';

		$('translateTemplateWindowUploadAnotherBase').innerHTML = '';
		$('translateTemplateWindowUploadAnother').setAttribute('_append_index', '2');
	},

	createEditUploadTemplate : function(result){
		if( result['status'] != "OK" ){
			alert(result['message']);
			return ;
		}

		for(var index = 0;index < result['contents'].length;index++){
			var templateBaseEle = null;
			for(var i = 1, len = 7; i <= len; i++){
				var ele = $('template-pair-block' + i);
				if (ele.style.display == "none"){
					templateBaseEle = ele;
					break;
				}
			}
			if(templateBaseEle == null){
				var msg = Const.Message.Error.templateOverMax;
				alert(msg.replace('{0}',len));
			}
			$('template-workspace-area').appendChild(templateBaseEle);
			var loadedTemplateIndex = i;
			var loadedTemplatePairs = $('loaded-template-pairs' + loadedTemplateIndex);

			loadedTemplatePairs.innerHTML = '';
			templateBaseEle.style.display = 'block';

			for(var i = 0, len = result.contents[index].length; i < len; i++){
				var table = document.createElement('table');
				Element.addClassName(table, 'pair-block');
				table.setAttribute('_pair_index', i);
				var tbody = document.createElement('tbody');
				var tr = document.createElement('tr');
				var td1 = document.createElement('td');
				Element.addClassName(td1, 'pair-left');
				var td2 = document.createElement('td');
				Element.addClassName(td2, 'pair-right');

				tr.appendChild(td1);
				tr.appendChild(td2);
				tbody.appendChild(tr);
				table.appendChild(tbody);
				td1.innerHTML  = '<textarea class="pair-original" readonly>'+htmlEscape(result.contents[index][i].SOURCE_TEXT)+'</textarea>';
				td1.innerHTML += '<textarea class="pair-translated" readonly>'+htmlEscape(result.contents[index][i].TARGET_TEXT)+'</textarea>';
				var deletePair = document.createElement('input');
				deletePair.type="button";
				deletePair.value = Const.Label.del_pair_btn;
				deletePair.className='btn_blue01';
				var deletePairPrototypeObj = $(deletePair);
				deletePairPrototypeObj.observe('click',function(event){
					var eventSrc = event.srcElement || event.target;
					var table = $(eventSrc.parentNode.parentNode.parentNode.parentNode);
					var pairIndex = table.getAttribute('_pair_index') - 0;
					var activePairs = $(table.parentNode);
					var activePairsBase = activePairs.parentNode;
					var thisBlockIndex = -1;
					for(var j = 0; j < createEditTemplate_loadedTemplates.length; j++){
						if(activePairsBase == createEditTemplate_loadedTemplates[j].templateBaseEle){
							thisBlockIndex = j;
							break;
						}
					}
					if(thisBlockIndex == -1){
						return;
					}
					delete createEditTemplate_loadedTemplates[thisBlockIndex].pairData[pairIndex];
					table.remove();
					if(activePairs.childNodes.length == 0){
						createEditTemplate_loadedTemplates = createEditTemplate_loadedTemplates.slice(0,thisBlockIndex).concat(createEditTemplate_loadedTemplates.slice(thisBlockIndex + 1,createEditTemplate_loadedTemplates.length))
						createEditTemplate_createLoadedTemplateList();
						activePairsBase.style.display = 'none';
					}
				});
				td2.appendChild(deletePair);
				loadedTemplatePairs.appendChild(table);
			}
			var item = {
				pairData: result['contents'][index],
				pairEle:loadedTemplatePairs,
				index:loadedTemplateIndex,
				templateBaseEle:templateBaseEle,
				name:result['path'][index],
				fnDelete:function(){
					for(var j = 0; j < createEditTemplate_loadedTemplates.length; j++){
						if(this == createEditTemplate_loadedTemplates[j]){
							createEditTemplate_loadedTemplates = createEditTemplate_loadedTemplates.slice(0,j).concat(createEditTemplate_loadedTemplates.slice(j + 1,createEditTemplate_loadedTemplates.length))
							createEditTemplate_createLoadedTemplateList();
							break;
						}
					}
					this.templateBaseEle.style.display = 'none';
				}
			};
			createEditTemplate_loadedTemplates.push(item);
			createEditTemplate_createLoadedTemplateList();
		}

		$('create-edit-template-upload-template-dummy-iframe-div').innerHTML = '';

		$('createEditLoadTemplateWindowUploadAnotherBase').innerHTML = '';
		$('createEditLoadTemplateWindowUploadAnother').setAttribute('_append_index', '2');
	},

	setLargeDictionaryId: function(dictId){
		this.largeDictionaryId = dictId;
	},

	runAjaxRequest: function(sentenceNumber, sentences, sourceLang, targetLang){
		if (this.translateAbortFlag) {
			return;
		}

		[].push.apply(this.translatingNo, sentenceNumber);
		this.translatePool++;

		var controller = this;
		var requestParams = $H({
				sourceLang : sourceLang
				, targetLang : targetLang
				, backTranslate : true
				, id : sentenceNumber.toJSON()
				, content : sentences.toJSON()
			}).toQueryString();
		new Ajax.Request('?page=translate',{
			method:'post',
			postBody:requestParams,
			asynchronous:true,
			controller:controller,
			onSuccess:function(httpObj){
				var resultObj = null;
				try {
					try{
						var result = httpObj.responseText.evalJSON();
					}catch(e){
						$('now-translating').style.display = 'none';
						alert(Const.Message.Error.TranslateError);
						controller.translateAbortFlag = true;
					}

					if (result.results.status != 'OK') {
						$('now-translating').style.display = 'none';
						alert(Const.Message.Error.TranslateError + "\n[" + result.results.message + "]");
						controller.translateAbortFlag = true;
						return;
					}

					for(i = 0; i < result.results.id.length; i++) {
						if (result.results.status == 'ERROR'){
							var TgtTxt = controller.resAnalysisHtml.contents.result.sentenceAndCodes[result.results.id[i]].sourceText;
							var bakTxt = controller.resAnalysisHtml.contents.result.sentenceAndCodes[result.results.id[i]].sourceText;
						}else{
							var TgtTxt = result.results.targetText[i];
							var bakTxt = result.results.backText[i];
							serviceInformation.update(result.licenseInfo);
						}
						controller.resAnalysisHtml.contents.result.targetText[result.results.id[i]] = TgtTxt;
						controller.resAnalysisHtml.contents.result.backText[result.results.id[i]] = bakTxt;
						controller.translateCounter++;

						for(j=0;j<controller.translatingNo.length;j++){
							if(controller.translatingNo[j] == result.results.id[i]){
								controller.translatingNo.splice(j,1);
							}
						}
					}
					controller.translatePool--;

					if(controller.translateAbortFlag == false){
						if (controller.translateCounter < controller.translateNum) {
							if(controller.translatePool == 0){
								controller._translateOfdoTranslate();
							}
						} else {
							if(controller.translatingNo.length == 0){
								$('now-translating-text').innerHTML = "";
								controller.translateOfreplacementHtml();
							}
						}
					}
				}catch(e){
					$('now-translating').style.display = 'none';
					alert(Const.Message.Error.ServerError);
					//alert(e.message+"\n"+httpObj.responseText);
					controller.translateAbortFlag = true;
					controller.translateAborted();
				}
			},
			onFailure:function(){
				$('now-translating').style.display = 'none';
				alert(Const.Message.Error.ServerError);
			},
			onComplete:function(){
				if (controller.translateAbortFlag){
					controller.translateAborted();
				}
			}
		});
	},

	dummy: function(){}
};

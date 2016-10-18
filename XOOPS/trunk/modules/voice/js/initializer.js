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
function initWorkspace(){
	sourceLangSelecter = new LanguageSelecter('sourceLang');
	targetLangSelecter = new LanguageSelecter('targetLang')

	$('source-editor-resize-large').observe('click', largeArea);
	$('source-editor-resize-small').observe('click', smallArea);
	$('target-editor-resize-large').observe('click', largeArea);
	$('target-editor-resize-small').observe('click', smallArea);
	$('backtarget-editor-resize-large').observe('click', largeArea);
	$('backtarget-editor-resize-small').observe('click', smallArea);

	$('source-editor-font-resize-large').observe('click', largeFont);
	$('source-editor-font-resize-small').observe('click', smallFont);
	$('target-editor-font-resize-large').observe('click', largeFont);
	$('target-editor-font-resize-small').observe('click', smallFont);
	$('backtarget-editor-font-resize-large').observe('click', largeFont);
	$('backtarget-editor-font-resize-small').observe('click', smallFont);

	sourceLangSelecter.setEvent('change', changeSourceLanguage);
	targetLangSelecter.setEvent('change', changeTargetLanguage);

	sourceArea = new DocumentEditor(
			'original-textarea', sourceLangSelecter.getSelectedLangCode()
			, false
		);
	targetArea = new DocumentEditor(
			'translation-textarea' , targetLangSelecter.getSelectedLangCode()
			, false
		);
	backtransArea = new DocumentEditor(
			'backtranslation-textarea' , sourceLangSelecter.getSelectedLangCode()
			, false
//			, "Back translation results of highlighted sentences are shown in this area."
			, ""
		);

	licenseArea = new LicenseArea('license-information-area')

	sourceArea.addTarget(backtransArea);
	sourceArea.addTarget(targetArea);
	targetArea.addTarget(sourceArea);
	targetArea.addTarget(backtransArea);
	backtransArea.addTarget(sourceArea);
	backtransArea.addTarget(targetArea);

	translateEditor = new TranslateEditor();
	translator = new Translator(sourceLangSelecter.getSelectedLangCode()
			, targetLangSelecter.getSelectedLangCode());
	publisher = new Publisher(sourceArea, targetArea, backtransArea, licenseArea);

	translator.setTranslateEditor(translateEditor);
	translateEditor.setPublisher(publisher);
	translateEditor.setTranslator(translator);
	publisher.setTranslator(translator);
	
	$('translate-button').observe('click', translate);
	$('cancel-button').observe('click', cancel);
	$('clear-button').observe('click', clearText);

	$('back-translate-button').observe('click', backTranslate);
	$('back-translate-cancel-button').observe('click', backTranslateCancel);

	getTargetLangTag();
	changeSourceLanguage();

	initInfoManager();
	Event.observe(document,'dom:TranslationDone', saveInfoData,false);
}


function doTranslation(isBackTranslation, isIntermediate) {
	translator.init(isBackTranslation, isIntermediate);
	publisher.init();
	publisher.startTimer();
	publisher.displayTranslationInfo();
	translator.translate();
	publisher.publish();
}
function translate(){
	if(publisher.isFreeTime) {
		doTranslation(true, false);
	}
}
function backTranslate(){
	if(publisher.isFreeTime) {
		doTranslation(false, true);
	}
}
function cancel(){
	publisher.cancel();
}
function backTranslateCancel(){
	publisher.cancel();
}

function getTargetLangTag(){
	var selecter = sourceLangSelecter;

	var sourceCode = selecter.getSelectedLangCode();
	var targetPair = $H(LangPairs[sourceCode]);
	var Tags = new String();
	targetPair.each(function(pair,i){
		var selflg = "";
		if(pair.value == "ja"){
			selflg = " selected";
		}
		Tags += "<option value='"+pair.value+"'"+selflg+">"+pair.key+"</option>\n";
	});
	targetLangSelecter.updateSelecter(Tags);
	changeTargetLanguage();
}

function changeSourceLanguage(){
	var langName = sourceLangSelecter.getSelectedLangName();
	Element.update($('from-language'),langName);
	Element.update($('backtranslation-head'),langName);
	translator.setSourceLang(sourceLangSelecter.getSelectedLangCode());
	getTargetLangTag();

	setEditorTextAlign(sourceLangSelecter.getSelectedLangCode(), sourceArea);
	setEditorTextAlign(sourceLangSelecter.getSelectedLangCode(), backtransArea);
}
function changeTargetLanguage(event){
	var targetSelecter = targetLangSelecter;
	var langName = targetLangSelecter.getSelectedLangName();
	Element.update($('to-language'),langName);
	translator.setTargetLang(targetLangSelecter.getSelectedLangCode());
	setEditorTextAlign(targetLangSelecter.getSelectedLangCode(), targetArea);
}

function setEditorTextAlign(langCode, myEditor){
	try{
		if(langCode == 'ar'){
			myEditor.getRealEditorElement().style.textAlign = 'right';
		}else{
			myEditor.getRealEditorElement().style.textAlign = 'left';
		}
	}catch(e){
		try{
			myEditor.editor.on('editorContentLoaded', function(){
				var LangCode = myEditor.getLangCode();

				if(langCode == 'ar'){
					myEditor.getRealEditorElement().style.textAlign = 'right';
				}else{
					myEditor.getRealEditorElement().style.textAlign = 'left';
				}
				myEditor.render();
			}.bind(myEditor));
		}catch(e){
			return;
		}
	}
}
function largeArea(){
	var es = $$('div.yui-editor-editable-container');
	for(var i = 0; i < es.length; i++){
		es[i].style.height = (parseInt(es[i].style.height) + sourceArea.sizeDefault) + 'px';
	}
}
function smallArea(){
	var es = $$('div.yui-editor-editable-container');
	if(parseInt(es[0].style.height) < 200){
		alert(Const.Message.Error.noMoreSmallToArea);
		return;
	}
	for(var i = 0; i < es.length; i++){
		es[i].style.height = (parseInt(es[i].style.height) - sourceArea.sizeDefault) + 'px';
	}
}
function largeFont(){
	var iframes = [];
	iframes[0] = $('original-textarea_editor');
	iframes[1] = $('translation-textarea_editor');
	iframes[2] = $('backtranslation-textarea_editor');

	for(var i = 0; i < frames.length; i++){
		var doc;
		if(iframes[i].contentDocument) {
	    	doc = iframes[i].contentDocument.body;
	    }else{
	    	doc = iframes[i].Document.body;
	    }
		 if(!doc.style.fontSize){
			 doc.style.fontSize = '15px';
		 }
		doc.style.fontSize = (parseInt(doc.style.fontSize) + 1) + 'px';
	}
}
function smallFont(){
	var iframes = [];
	iframes[0] = $('original-textarea_editor');
	iframes[1] = $('translation-textarea_editor');
	iframes[2] = $('backtranslation-textarea_editor');

	for(var i = 0; i < frames.length; i++){
		var doc;
		if(iframes[i].contentDocument) {
	    	doc = iframes[i].contentDocument.body;
	    }else{
	    	doc = iframes[i].Document.body;
	    }
		 if(!doc.style.fontSize){
			 doc.style.fontSize = '15px';
		 }
		 if(parseInt(doc.style.fontSize) < 10){
			alert(Const.Message.Error.noMoreSmallToFont);
			return;
		}
		doc.style.fontSize = (parseInt(doc.style.fontSize) - 1) + 'px';
	}
}
function clearText(ev){
	if(publisher.isFreeTime){
		sourceArea.clearText();
		targetArea.clearText();
		backtransArea.clearText();
		clearInfoData();
		licenseArea.clear();
		// 2010 5/27 update. !!this is a dirty fix.
		translator.voiceGenerator.clear();
	}
}

Event.observe(window, 'load', function(){
	try{
		initWorkspace();
	}catch(e){
		alert("Translation paths for text translation were not specified. Please set translation paths in Service Settings.");
	}
});

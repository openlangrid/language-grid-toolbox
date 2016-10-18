//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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

var localInfoManager = null;

function initInfoManager(){
	localInfoManager = new InfoManager("page");
	loadInfoData();
}

function saveInfoData(){
	var data = {};
	try{
		data['original-textarea'] = createSaveObj(sourceArea);
		data['translation-textarea'] = createSaveObj(targetArea);
		data['backtranslation-textarea'] = createSaveObj(backtransArea);
		data['sourceLang'] = sourceLangSelecter.getSelectedLangCode();
		data['targetLang'] = targetLangSelecter.getSelectedLangCode();
		
		if (!!voiceManager._contents) {
			data['voices'] = voiceManager._contents.join(',');
			data['timestamp'] = new Date().getTime();
		}

		var items = Object.toJSON(data);
		localInfoManager.saveItems($F("moduleId"),$F("screenId"),items);
	}catch(e){
		alert("save:"+e.message);
	}
}

function clearInfoData(){
	localInfoManager.clearItems($F("moduleId"),$F("screenId"));
}

function createSaveObj(myEditor){
	var data = {}
	var eBody = myEditor.getRealEditorElement();

	if(eBody.hasChildNodes()){

		var now_node = eBody.firstChild;
		var i = 0;
		do{
			if(now_node.tagName){
				data[i] = {}
				if(now_node.tagName.toLowerCase() != 'br'){
					data[i]['tag'] = "span";
					if(now_node.id){
						data[i]['no'] = now_node.id.split(":")[1];
					}
					data[i]['text'] = now_node.innerHTML;
					if(now_node.style.color && now_node.style.color != ""){
						data[i]['color'] = now_node.style.color;
					}
				}else{
					data[i]['tag'] = "br";
				}
				i++;
			}

			now_node = now_node.nextSibling;
		}while(now_node)
	}
	return Object.toJSON(data);
}


function loadInfoData(){
	var needToGenerateVoices = true;
	var items = localInfoManager.loadItems($F("moduleId"),$F("screenId"));
	if (items.each) {
		/*
		items.each(function(pair,i){
			if($(pair.key)){
				$(pair.key).value = pair.value;
			}
		});
		*/
		if(items.get('sourceLang') && $('sourceLang')){
			$('sourceLang').value = items.get('sourceLang');
			changeSourceLanguage();
		}

		if(items.get('targetLang') && $('targetLang')){
			$('targetLang').value = items.get('targetLang');
			voiceManager.setLanguage(items.get('targetLang'));
			changeTargetLanguage();
		}
		if(items.get('original-textarea') && sourceArea){
			setLoadSentence(items.get('original-textarea'),sourceArea);
		}
		if(items.get('translation-textarea') && targetArea){
			setLoadSentence(items.get('translation-textarea'),targetArea);
		}
		if(items.get('backtranslation-textarea') && backtransArea){
			setLoadSentence(items.get('backtranslation-textarea'),backtransArea);
		}
		if (items.get('voices') && items.get('timestamp')) {
			if ((new Date().getTime()) - items.get('timestamp') < 24*60*60*1000) {
				voiceManager.restoreContents(items.get('voices').split(','));
				needToGenerateVoices = false;
			}
		}
	}
	
	try {
		setTimeout(function() {
			//if (targetArea.getEditorText()) {
			if (targetArea.getVoiceText()) {
				voiceManager.setSource(targetArea.getVoiceText());
				var text = items.get('translation-textarea').evalJSON();
				var sources = [];
				for (var key in text) {
					if (text[key].text && text[key].text != '') 
						sources.push(text[key].text);
				}
				voiceManager.setSources(sources);
				if (needToGenerateVoices) {
					voiceManager.create();
				}
			}
		}, 1500);
	} catch (e) {
	}
}

function setLoadSentence(sourceText,myEditor) {
	if (sourceText != "") {
		myEditor.editor.on('editorContentLoaded', function(){
			try {
				var eBody = myEditor.getRealEditorElement();
				myEditor.clearText();
				source = $H(sourceText.evalJSON());
				if (source.each) {
					source.each(function(pair) {
						var val = $H(pair.value);
						if (val.get('tag')) {
							var doc = myEditor.getDocument();

							if (val.get('tag').toLowerCase() == "br") {
								var element = doc.createElement('br');
							} else {
								var element = doc.createElement('span');
								Element.extend(element);
								element.id = myEditor.id + ":" + val.get('no');
								if (val.get('color')) {
									element.style.color = val.get('color');
								}
								Element.update(element,val.get('text'));
								Event.observe(element,'click', function(event){
									//var ele = event.element();
									var ele = Event.element(event);
									this.highlightSentence(ele.id);
									for (var i = 0; i < this.targets.length; i++) {
										this.targets[i].highlightSentence(ele.id);
									}
								}.bind(myEditor));
							}
							
							if (eBody.innerHTML == "&nbsp;") {
								eBody.innerHTML = '';
							}

							if (eBody.innerHTML != '' && eBody.lastChild.innerHTML != ""
								&& eBody.lastChild.tagName.toLowerCase() != 'br'
								&& element.tagName.toLowerCase() != 'br')
							{
								eBody.appendChild(myEditor.getDocument().createTextNode(" "));
							}

							eBody.appendChild(element);
						}
					});
				}
			} catch(e) {
				alert("load:"+e.message);
			}

			//myEditor.editor.setEditorHTML(sourceText);
		});
	}
}

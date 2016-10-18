/**********************************************************************
* /js/web-translation-main.js
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
function htmlEscape(str){
	return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>').replace(/\s/g, '&nbsp;');
};
var ORIGINAL_INIT_MESSAGE = null;
var TRANSLATED_INIT_MESSAGE = null;
var TEMPLATE_INIT_MESSAGE = null;

var webTranslationWorkspaceInstance = null;
var serviceInformation = null;
var dictionarySelection = null;
var translate_loadedTemplates = [];
var sourceLangSelecter = null;
var targetLangSelecter = null;

var translate_createLoadedTemplateList = function(){
	var loadedTemplateTableBase = $('templateLoadedTemplateTableListBase');
	loadedTemplateTableBase.innerHTML = '';

	var table = $(document.createElement('table'));
	Element.addClassName(table, 'temp-list');
	var tbody = document.createElement('tbody');
	for(var i = 0, len = translate_loadedTemplates.length; i < len; i++){
		var tr = document.createElement('tr');
		if((i % 2) == 1){
			Element.addClassName(tr, 'bg-white');
		}
		var td1 = document.createElement('td');
		var td2 = document.createElement('th');
		var td3 = document.createElement('td');
		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		tbody.appendChild(tr);
		table.appendChild(tbody);
		td1.innerHTML = '<img src="img/bookicon0' + translate_loadedTemplates[i].index + '.gif" width="19" height="17" />';
		td2.innerHTML = translate_loadedTemplates[i].name;
		var trashEle = $(document.createElement('img'));
		trashEle.setAttribute('src','./img/trash.gif');
		trashEle.setAttribute('alt','trash');
		Element.addClassName(trashEle, 'trash');
		Event.observe(trashEle,'click', function(){this.fnDelete()}.bind(translate_loadedTemplates[i]));
		td3.appendChild(trashEle);
	}
	loadedTemplateTableBase.appendChild(table);
}
var createEditTemplate_loadedTemplates = [];
var createEditTemplate_createLoadedTemplateList = function(){
	var loadedTemplateTableBase = $('createEditTemplateLoadedTemplateTableListBase');
	loadedTemplateTableBase.innerHTML = '';

	var table = $(document.createElement('table'));
	Element.addClassName(table, 'temp-list');
	var tbody = document.createElement('tbody');
	for(var i = 0, len = createEditTemplate_loadedTemplates.length; i < len; i++){
		var tr = document.createElement('tr');
		if((i % 2) == 1){
			Element.addClassName(tr, 'bg-white');
		}
		var td1 = document.createElement('td');
		var td2 = document.createElement('th');
		var td3 = document.createElement('td');
		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		tbody.appendChild(tr);
		table.appendChild(tbody);
		td1.innerHTML = '<img src="img/bookicon0' + createEditTemplate_loadedTemplates[i].index + '.gif" width="19" height="17">';
		td2.innerHTML = createEditTemplate_loadedTemplates[i].name;
		var trashEle = $(document.createElement('img'));
		trashEle.setAttribute('src','./img/trash.gif');
		trashEle.setAttribute('alt','trash');
		Element.addClassName(trashEle, 'trash');
		Event.observe(trashEle,'click', function(){this.fnDelete()}.bind(createEditTemplate_loadedTemplates[i]));
		td3.appendChild(trashEle);
	}
	loadedTemplateTableBase.appendChild(table);
}

Event.observe(window,'load',function(){
	var ORIGINAL_INIT_MESSAGE = Const.Label.org_init_msg;
	var TRANSLATED_INIT_MESSAGE = Const.Label.tran_init_msg;
	var TEMPLATE_INIT_MESSAGE = Const.Label.temp_init_msg;

	sourceLangSelecter = new LanguageSelecter('sourceLang');
	targetLangSelecter = new LanguageSelecter('targetLang')

	sourceLangSelecter.setEvent('change', changeSourceLanguage);
	targetLangSelecter.setEvent('change', changeTargetLanguage);

	$('original-webpage-editor').value = ORIGINAL_INIT_MESSAGE;
	$('original-webpage-editor').observe('focus',function(){
		var self = $('original-webpage-editor');
		if (self.value == ORIGINAL_INIT_MESSAGE){
			self.value = '';
		}
	});
	$('translated-webpage-editor').value = TRANSLATED_INIT_MESSAGE;
	$('translated-webpage-editor').observe('focus',function(){
		var self = $('translated-webpage-editor');
		if (self.value == TRANSLATED_INIT_MESSAGE){
			self.value = '';
		}
	});
	$('template-pair1-editor').value = TEMPLATE_INIT_MESSAGE;
	$('template-pair1-editor').observe('focus',function(){
		var self = $('template-pair1-editor');
		if (self.value == TEMPLATE_INIT_MESSAGE){
			self.value = '';
		}
	});
	$('template-pair2-editor').value = TEMPLATE_INIT_MESSAGE;
	$('template-pair2-editor').observe('focus',function(){
		var self = $('template-pair2-editor');
		if (self.value == TEMPLATE_INIT_MESSAGE){
			self.value = '';
		}
	});

	webTranslationWorkspaceInstance = new WebTranslationWorkspace();

	Toggler.setToggleEvent('translation');
	Toggler.setToggleEvent('create-edit-template');

	serviceInformation = new ServiceInformation('service-information-form-area');

	var activeTemplateData = {
		pairData:[]
	};

	var addPairToTemplate = $('add-pair-to-template');
	addPairToTemplate.observe('click',function(event){
		var activePairs = $('active-pairs');
		var activePairsBase = activePairs.parentNode;
		if(activePairsBase.style.display=="none"){
			activePairsBase.style.display = "block";
		}
		var editor1 = $('template-pair1-editor');
		var editor2 = $('template-pair2-editor');
		var table = document.createElement('table');
		Element.addClassName(table, 'pair-block');
		table.setAttribute('_pair_index', activeTemplateData.pairData.length);

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
		td1.innerHTML  = '<textarea class="pair-original" readonly>'+htmlEscape(editor1.value)+'</textarea>';
		td1.innerHTML += '<textarea class="pair-translated" readonly>'+htmlEscape(editor2.value)+'</textarea>';
		var deletePair = document.createElement('input');
		deletePair.setAttribute('type','button');
		deletePair.value = Const.Label.del_pair_btn;
		Element.addClassName(deletePair, 'btn_blue01');
		var deletePairPrototypeObj = $(deletePair);
		deletePairPrototypeObj.observe('click',function(event){
			var eventSrc = event.srcElement || event.target;
			var table = $(eventSrc.parentNode.parentNode.parentNode.parentNode);
			var pairIndex = table.getAttribute('_pair_index') - 0;
			delete activeTemplateData.pairData[pairIndex];
			var activePairs = $(table.parentNode);
			var activePairsBase = activePairs.parentNode;
			table.remove();
			if(activePairs.childNodes.length == 0){
				activePairsBase.style.display = 'none';
			}
		});
		td2.appendChild(deletePair);
		activePairs.insertBefore(table, activePairs.firstChild);
		activeTemplateData.pairData.push({"SOURCE_TEXT":editor1.value,"TARGET_TEXT":editor2.value});
		editor1.value = '';
		editor2.value = '';
	});

	$('display-webpage').observe('click',function(event){
		if(!$F('url-input').match(/^https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+$/)){
			alert(Const.Message.Error.invalidUrl);
			return;
		}
		window.open($F('url-input'),'');
	});

	$('original-webpage-editor-expansion-button').observe('click', function(event){
		$('original-webpage-editor').setStyle({height: ($('original-webpage-editor').getHeight()+24)+'px'});
	});
	$('original-webpage-editor-reduction-button').observe('click', function(event){
		$('original-webpage-editor').setStyle({height: ((height=$('original-webpage-editor').getHeight()-60)<0?0:height)+'px'});
	});

	$('translated-webpage-editor-expansion-button').observe('click', function(event){
		$('translated-webpage-editor').setStyle({height: ($('translated-webpage-editor').getHeight()+24)+'px'});
	});
	$('translated-webpage-editor-reduction-button').observe('click', function(event){
		$('translated-webpage-editor').setStyle({height: ((height=$('translated-webpage-editor').getHeight()-60)<0?0:height)+'px'});
	});

	$('back-translated-webpage-editor-expansion-button').observe('click', function(event){
		$('back-translated-webpage-editor').setStyle({height: ($('back-translated-webpage-editor').getHeight()+24)+'px'});
	});
	$('back-translated-webpage-editor-reduction-button').observe('click', function(event){
		$('back-translated-webpage-editor').setStyle({height: ((height=$('back-translated-webpage-editor').getHeight()-60)<0?0:height)+'px'});
	});

	$('template-pair1-editor-expansion-button').observe('click', function(event){
		$('template-pair1-editor').setStyle({height: ($('template-pair1-editor').getHeight()+24)+'px'});
	});
	$('template-pair1-editor-reduction-button').observe('click', function(event){
		$('template-pair1-editor').setStyle({height: ((height=$('template-pair1-editor').getHeight()-60)<0?0:height)+'px'});
	});

	$('template-pair2-editor-expansion-button').observe('click', function(event){
		$('template-pair2-editor').setStyle({height: ($('template-pair2-editor').getHeight()+24)+'px'});
	});
	$('template-pair2-editor-reduction-button').observe('click', function(event){
		$('template-pair2-editor').setStyle({height: ((height=$('template-pair2-editor').getHeight()-60)<0?0:height)+'px'});
	});

	$('active-pairs-expansion-button').observe('click', function(event){
		$('active-pairs').setStyle({height: ($('active-pairs').getHeight()+24)+'px'});
	});
	$('active-pairs-reduction-button').observe('click', function(event){
		$('active-pairs').setStyle({height: ((height=$('active-pairs').getHeight()-38)<0?0:height)+'px'});
	});

	$('loaded-template-expansion-button1').observe('click', function(event){
		$('loaded-template-pairs1').setStyle({height: ($('loaded-template-pairs1').getHeight()+24)+'px'});
	});
	$('loaded-template-reduction-button1').observe('click', function(event){
		$('loaded-template-pairs1').setStyle({height: ((height=$('loaded-template-pairs1').getHeight()-38)<0?0:height)+'px'});
	});

	$('loaded-template-expansion-button2').observe('click', function(event){
		$('loaded-template-pairs2').setStyle({height: ($('loaded-template-pairs2').getHeight()+24)+'px'});
	});
	$('loaded-template-reduction-button2').observe('click', function(event){
		$('loaded-template-pairs2').setStyle({height: ((height=$('loaded-template-pairs2').getHeight()-38)<0?0:height)+'px'});
	});

	$('loaded-template-expansion-button3').observe('click', function(event){
		$('loaded-template-pairs3').setStyle({height: ($('loaded-template-pairs3').getHeight()+24)+'px'});
	});
	$('loaded-template-reduction-button3').observe('click', function(event){
		$('loaded-template-pairs3').setStyle({height: ((height=$('loaded-template-pairs3').getHeight()-38)<0?0:height)+'px'});
	});

	$('loaded-template-expansion-button4').observe('click', function(event){
		$('loaded-template-pairs4').setStyle({height: ($('loaded-template-pairs4').getHeight()+24)+'px'});
	});
	$('loaded-template-reduction-button4').observe('click', function(event){
		$('loaded-template-pairs4').setStyle({height: ((height=$('loaded-template-pairs4').getHeight()-38)<0?0:height)+'px'});
	});

	$('loaded-template-expansion-button5').observe('click', function(event){
		$('loaded-template-pairs5').setStyle({height: ($('loaded-template-pairs5').getHeight()+24)+'px'});
	});
	$('loaded-template-reduction-button5').observe('click', function(event){
		$('loaded-template-pairs5').setStyle({height: ((height=$('loaded-template-pairs5').getHeight()-38)<0?0:height)+'px'});
	});

	$('loaded-template-expansion-button6').observe('click', function(event){
		$('loaded-template-pairs6').setStyle({height: ($('loaded-template-pairs6').getHeight()+24)+'px'});
	});
	$('loaded-template-reduction-button6').observe('click', function(event){
		$('loaded-template-pairs6').setStyle({height: ((height=$('loaded-template-pairs6').getHeight()-38)<0?0:height)+'px'});
	});

	$('loaded-template-expansion-button7').observe('click', function(event){
		$('loaded-template-pairs7').setStyle({height: ($('loaded-template-pairs7').getHeight()+24)+'px'});
	});
	$('loaded-template-reduction-button7').observe('click', function(event){
		$('loaded-template-pairs7').setStyle({height: ((height=$('loaded-template-pairs7').getHeight()-38)<0?0:height)+'px'});
	});

	$('setup-import-webpage').observe('click', function(event){
		webTranslationWorkspaceInstance.importWebPage();
	});

	$('translating-action-translated').observe('click', function(event){
		webTranslationWorkspaceInstance.originalToTranslated(activeTemplateData, translate_loadedTemplates, createEditTemplate_loadedTemplates);
	});

	$('now-translating-abort').observe('click', function(event){
		webTranslationWorkspaceInstance.translatingAbort();
	});

	$('original-webpage-editor').observe('change', function(event){
		webTranslationWorkspaceInstance.undoOriginalOnChange(webTranslationWorkspaceInstance);
	});

	$('translated-webpage-editor').observe('change', function(event){
		webTranslationWorkspaceInstance.undoTranslatedOnChange(webTranslationWorkspaceInstance);
	});

	$('original-webpage-undo').observe('click', function(event){
		webTranslationWorkspaceInstance.originalUndo();
	});

	$('original-webpage-display').observe('click', function(event){
		webTranslationWorkspaceInstance.originalPageDisplay();
	});

	$('translated-webpage-undo').observe('click', function(event){
		webTranslationWorkspaceInstance.translatedUndo();
	});

	$('translated-webpage-display').observe('click', function(event){
		webTranslationWorkspaceInstance.translatedPageDisplay();
	});

	webTranslationWorkspaceInstance.undoDisplay(webTranslationWorkspaceInstance.UNDO_ORIGINAL_KEY);
	webTranslationWorkspaceInstance.undoDisplay(webTranslationWorkspaceInstance.UNDO_TRANSLATE_KEY);

	/*
	$('language-setup-form-area-ok-button').observe('click', function(event){
		webTranslationWorkspaceInstance.translatorsAndLanguagesSelection.languageSetupButtonClick(webTranslationWorkspaceInstance.translatorsAndLanguagesSelection);
		location.href='#workspace';
	});
	*/

	$('translateTemplateWindowLoadAnother').observe('click', function(event){
		loadTemplateData('translateTemplateWindowLoadAnother','translateTemplateWindowTemplateName');
	});

	$('createEditLoadTemplateWindowLoadAnother').observe('click', function(event){
		loadTemplateData('createEditLoadTemplateWindowLoadAnother','createEditLoadTemplateWindowTemplateName');
	});


	$('translateTemplateWindowUploadAnother').observe('click', function(event){
		var selfEle = $('translateTemplateWindowUploadAnother');
		var appendIndex = selfEle.getAttribute('_append_index');
		if(translate_loadedTemplates.length + appendIndex > 7){
			alert(Const.Message.Error.noMoreFieldAdd);
			return;
		}
		var base = $('translateTemplateWindowUploadAnotherBase');
		var item = $(document.createElement('div'));
		item.name = 'uploadFileName';
		item.innerHTML = '<input type="file" size="40" class="service-input2" name="uploadFileName[]"/>';
		selfEle.setAttribute('_append_index', appendIndex + 1);
		base.appendChild(item);
	});

	$('createEditLoadTemplateWindowUploadAnother').observe('click', function(event){
		var selfEle = $('createEditLoadTemplateWindowUploadAnother');
		var appendIndex = selfEle.getAttribute('_append_index');
		if(createEditTemplate_loadedTemplates.length + appendIndex > 7){
			alert(Const.Message.Error.noMoreFieldAdd);
			return;
		}
		var base = $('createEditLoadTemplateWindowUploadAnotherBase');
		var item = $(document.createElement('div'));
		item.name = 'uploadFileName';
		item.innerHTML = '<input type="file" size="40" class="service-input2" name="uploadFileName[]"/>';
		selfEle.setAttribute('_append_index', appendIndex + 1);
		base.appendChild(item);
	});

	// ---------- Create & Edit Template -----------------
	new PopupPanel('loadTemplate', 'loadTemplateWindow', {
		title : Const.Label.poptitle1,
		buttons : [
			{
				"label":"OK",
				"callback":function(panel){
					var templateNameEle = null;
					var templateNameIndex = 1;
					panel.hide();
					while((templateNameEle = $('translateTemplateWindowTemplateName' + templateNameIndex)) != null){
						var templateName = templateNameEle.value;
						templateNameIndex++;
						if(templateName == ''){
							continue;
						}
						if(!templateName.match(/^[a-zA-Z0-9_-]+$/)){
							alert(templateName + ' ' + Const.Message.Error.templateNameError);
							return;
						}
						var isContinue = false;
						translate_loadedTemplates.each(function(obj, idx) {
							if (obj.name == templateName) {
								isContinue = true;
							}
						});
						if (isContinue) {
							continue;
						}
						if(translate_loadedTemplates.length >= 7){
							alert(Const.Message.Error.templateOverMax.replace('{0}',7));
							break;
						}
						var queryString = $H({name:templateName}).toQueryString();
						new Ajax.Request(
							'?page=load-template',
							{
								asynchronous:false,
								method:'post',
								parameters:queryString,
								onSuccess:function(httpObj){
									var resultObj = null;
									try{
										var resultObj = httpObj.responseText.evalJSON();
									}catch(e){
										alert(Const.Message.Error.ServerError);
										return;
									}
									if(!resultObj.result){
										alert(Const.Message.Error.ServerError);
										return;
									}

									if(resultObj.data.pair.length == 0){
										var msg = Const.Message.Error.noTemplate;
										alert(msg.replace('{0}',resultObj.data.name));
										return;
									}
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
										throw $break;
									}
									var item = {
										pairData:resultObj.data.pair,
										index:newTemplateIndex,
										name:resultObj.data.name,
										fnDelete:function(){
											for(var j = 0; j < translate_loadedTemplates.length; j++){
												if(this == translate_loadedTemplates[j]){
													translate_loadedTemplates = translate_loadedTemplates.slice(0,j).concat(translate_loadedTemplates.slice(j + 1,translate_loadedTemplates.length))
													translate_createLoadedTemplateList();
													break;
												}
											}
										}
									}
									translate_loadedTemplates.push(item);
									translate_createLoadedTemplateList();
								},
								onFailure:function(){
									alert(Const.Message.Error.ServerError);
								},
								onComplete:function(){}
							}
						);
					}
					$('translateTemplateWindowLoadAnotherBase').innerHTML = '';
					$('translateTemplateWindowLoadAnother').setAttribute('_append_index', '1');
				},
				"class":"btn_blue01"
			},{
				"label":"Cancel",
				"callback":function(panel){
					panel.hide();
					$('translateTemplateWindowLoadAnotherBase').innerHTML = '';
					$('translateTemplateWindowLoadAnother').setAttribute('_append_index', '1');
				},
				"class":"btn_blue01"
			}
		],
		onopen:function(){
			$('translateTemplateWindowLoadAnotherBase').innerHTML = '';
			$('translateTemplateWindowLoadAnother').fire('click');
		},
		onclose:function(panel){
			panel.hide();
			$('translateTemplateWindowLoadAnotherBase').innerHTML = '';
			$('translateTemplateWindowLoadAnother').setAttribute('_append_index', '1');
		}
	});

	new PopupPanel('uploadTemplate', 'uploadTemplateWindow', {
		title : Const.Label.poptitle2,
		offset : [-230, 0],
		buttons : [
			{
				"label":"OK",
				"callback":function(panel){
					$('apply-template-upload-template-dummy-iframe-div').innerHTML = '<iframe name="apply-template-upload-iframe" style="width: 200px; height: 200px;"></iframe>';
					$('apply-template-upload-form').submit();
					panel.hide();
					$('translateTemplateWindowUploadAnotherBase').innerHTML = '';
					$('translateTemplateWindowUploadAnother').setAttribute('_append_index', '2');
				},
				"class":"btn_blue01"
			},{
				"label":"Cancel",
				"callback":function(panel){
					$('apply-template-upload-template-dummy-iframe-div').innerHTML = '';
					panel.hide();
					$('translateTemplateWindowUploadAnotherBase').innerHTML = '';
					$('translateTemplateWindowUploadAnother').setAttribute('_append_index', '2');
				},
				"class":"btn_blue01"
			}
		],
		onclose:function(panel){
			$('apply-template-upload-template-dummy-iframe-div').innerHTML = '';
			panel.hide();
			$('translateTemplateWindowUploadAnotherBase').innerHTML = '';
			$('translateTemplateWindowUploadAnother').setAttribute('_append_index', '2');
		}
	});

	new PopupPanel('originalWebpageUpload', 'originalWebpageUploadWindow', {
		title : Const.Label.poptitle3,
		buttons : [
			{
				"label":"OK",
				"callback":function(panel){
					$('original-webpage-upload-dummy-iframe-div').innerHTML = '<iframe name="upload_iframe" style="width: 200px; height: 200px;"></iframe>';
					$('original-webpage-upload-form').submit();
					panel.hide();
				},
				"class":"btn_blue01"
			},{
				"label":"Cancel",
				"callback":function(panel){
					$('original-webpage-upload-dummy-iframe-div').innerHTML = '';
					panel.hide();
				},
				"class":"btn_blue01"
			}
		]
	});

	new PopupPanel('originalWebpageDownload', 'originalWebpageDownloadWindow', {
		title : Const.Label.poptitle4,
		buttons : [
			{
				"label":"OK",
				"callback":function(panel){
					$('original-webpage-download-value').value = $('original-webpage-editor').value;
					$('original-webpage-download-form').submit();
					$('original-webpage-download-text').value = '';
					panel.hide();
				},
				"class":"btn_blue01"
			},{
				"label":"Cancel",
				"callback":function(panel){
					$('original-webpage-download-text').value = '';
					panel.hide();
				},
				"class":"btn_blue01"
			}
		]
	});

	new PopupPanel('translatedWebpageUpload', 'translatedWebpageUploadWindow', {
		title : Const.Label.poptitle3,
		buttons : [
			{
				"label":"OK",
				"callback":function(panel){
					$('translated-webpage-upload-dummy-iframe-div').innerHTML = '<iframe name="upload_iframe" style="width: 200px; height: 200px;"></iframe>';
					$('translated-webpage-upload-form').submit();
					panel.hide();
				},
				"class":"btn_blue01"
			},{
				"label":"Cancel",
				"callback":function(panel){
					$('translated-webpage-upload-dummy-iframe-div').innerHTML = '';
					panel.hide();
				},
				"class":"btn_blue01"
			}
		]
	});

	new PopupPanel('translatedWebpageDownload', 'translatedWebpageDownloadWindow', {
		title : Const.Label.poptitle4,
		buttons : [
			{
				"label":"OK",
				"callback":function(panel){
					$('translated-webpage-download-value').value = $('translated-webpage-editor').value;
					$('translated-webpage-download-form').submit();
					$('translated-webpage-download-text').value = '';
					panel.hide();
				},
				"class":"btn_blue01"
			},{
				"label":"Cancel",
				"callback":function(panel){
					panel.hide();
				},
				"class":"btn_blue01"
			}
		]
	});

	// ---------- Create & Edit Template -----------------
	new PopupPanel('createEditLoadTemplate', 'createEditLoadTemplateWindow', {
		title : Const.Label.poptitle1,
		buttons : [
			{
				"label":"OK",
				"callback":function(panel){
					var templateNameEle = null;
					var templateNameIndex = 1;
					panel.hide();
					while((templateNameEle = $('createEditLoadTemplateWindowTemplateName' + templateNameIndex)) != null){
						var templateName = templateNameEle.value;
						templateNameIndex++;
						if(templateName == ''){
							continue;
						}
						if(!templateName.match(/^[a-zA-Z0-9_-]+$/)){
							alert(templateName + ' ' + Const.Message.Error.templateNameError);
							return;
						}
						var isContinue = false;
						createEditTemplate_loadedTemplates.each(function(obj, idx) {
							if (obj.name == templateName) {
								isContinue = true;
							}
						});
						if(isContinue) {
							continue;
						}
						if(createEditTemplate_loadedTemplates.length >= 7){
							alert(Const.Message.Error.templateOverMax.replace('{0}',7));
							break;
						}
						var queryString = $H({name:templateName}).toQueryString();
						new Ajax.Request(
							'?page=load-template',
							{
								asynchronous:false,
								method:'post',
								parameters:queryString,
								onSuccess:function(httpObj){
									var resultObj = null;
									try{
										var resultObj = httpObj.responseText.evalJSON();
									}catch(e){
										alert(Const.Message.Error.ServerError);
										return;
									}
									if(!resultObj.result){
										alert(Const.Message.Error.ServerError);
										return;
									}

									if(resultObj.data.pair.length == 0){
										var msg = Const.Message.Error.noTemplate;
										alert(msg.replace('{0}',resultObj.data.name));
										return;
									}
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
										throw $break;
									}
									$('template-workspace-area').appendChild(templateBaseEle);

									var loadedTemplateIndex = i;
									var loadedTemplatePairs = $('loaded-template-pairs' + loadedTemplateIndex);

									loadedTemplatePairs.innerHTML = '';
									templateBaseEle.style.display = 'block';

									for(var i = 0, len = resultObj.data.pair.length; i < len; i++){
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
										td1.innerHTML  = '<textarea class="pair-original" readonly>'+htmlEscape(resultObj.data.pair[i].SOURCE_TEXT)+'</textarea>';
										td1.innerHTML += '<textarea class="pair-translated" readonly>'+htmlEscape(resultObj.data.pair[i].TARGET_TEXT)+'</textarea>';

										var deletePair = document.createElement('input');
										deletePair.setAttribute('type','button');
										deletePair.value = Const.Label.del_pair_btn;
										Element.addClassName(deletePair, 'btn_blue01');
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
												createEditTemplate_loadedTemplates = createEditTemplate_loadedTemplates.slice(
													0,
													thisBlockIndex
												).concat(
													createEditTemplate_loadedTemplates.slice(
														thisBlockIndex + 1,
														createEditTemplate_loadedTemplates.length
													)
												);
												createEditTemplate_createLoadedTemplateList();
												activePairsBase.style.display = 'none';
											}
										});
										td2.appendChild(deletePair);
										loadedTemplatePairs.appendChild(table);
									}
									var item = {
										pairData:resultObj.data.pair,
										pairEle:loadedTemplatePairs,
										index:loadedTemplateIndex,
										templateBaseEle:templateBaseEle,
										name:resultObj.data.name,
										fnDelete:function(){
											for(var j = 0; j < createEditTemplate_loadedTemplates.length; j++){
												if(this == createEditTemplate_loadedTemplates[j]){
													createEditTemplate_loadedTemplates = createEditTemplate_loadedTemplates.slice(0,j).concat(
														createEditTemplate_loadedTemplates.slice(j + 1,createEditTemplate_loadedTemplates.length)
													);
													createEditTemplate_createLoadedTemplateList();
													break;
												}
											}
											this.templateBaseEle.style.display = 'none';
										}
									};
									createEditTemplate_loadedTemplates.push(item);
									createEditTemplate_createLoadedTemplateList();
								},
								onFailure:function(){
									alert(Const.Message.Error.ServerError);
								},
								onComplete:function(){}
							}
						);
					}
					$('createEditLoadTemplateWindowLoadAnotherBase').innerHTML = '';
					$('createEditLoadTemplateWindowLoadAnother').setAttribute('_append_index', '1');
				},
				"class":"btn_blue01"
			},{
				"label":"Cancel",
				"callback":function(panel){
					panel.hide();
					$('createEditLoadTemplateWindowLoadAnotherBase').innerHTML = '';
					$('createEditLoadTemplateWindowLoadAnother').setAttribute('_append_index', '1');
				},
				"class":"btn_blue01"
			}
		],
		onopen:function(){
			$('createEditLoadTemplateWindowLoadAnotherBase').innerHTML = '';
			$('createEditLoadTemplateWindowLoadAnother').fire('click');
		},
		onclose:function(panel){
			panel.hide();
			$('createEditLoadTemplateWindowLoadAnotherBase').innerHTML = '';
			$('createEditLoadTemplateWindowLoadAnother').setAttribute('_append_index', '1');
		}
	});

	// ---------- Create & Edit Template -----------------
	new PopupPanel('createEditSaveTemplate', 'createEditSaveTemplateWindow', {
		title : Const.Label.poptitle5,
		buttons : [
			{
				"label":"OK",
				"callback":function(panel){
					var pairsEle = $('active-pairs');
					var templateNameEle = $('createEditSaveTemplateWindowTemplateName');
					var templateName = templateNameEle.value;
					if(!templateName.match(/^[a-zA-Z0-9_-]+$/)){
						alert(Const.Message.Error.templateNameError);
						return;
					}
					var queryArray = {};
					var pairIndex = 0;
					var found = false;
					var postIdx = 0;
					for(var i = 0, len = activeTemplateData.pairData.length; i < len; i++){
						var pair = activeTemplateData.pairData[i];
						if ((typeof pair) == "undefined"){
							continue;
						}
						queryArray['pairs[' + postIdx + '][source]'] = pair.SOURCE_TEXT;
						queryArray['pairs[' + postIdx + '][target]'] = pair.TARGET_TEXT;
						pairIndex++;
						postIdx++;
						found = true;
					}
					createEditTemplate_loadedTemplates.each(function(obj, idx){
						for(var i = 0, len = obj.pairData.length; i < len; i++){
							var pair = obj.pairData[i];
							if ((typeof pair) == "undefined"){
								continue;
							}
							queryArray['pairs[' + postIdx + '][source]'] = pair.SOURCE_TEXT;
							queryArray['pairs[' + postIdx + '][target]'] = pair.TARGET_TEXT;
							pairIndex++;
							postIdx++;
							found = true;
						}
					});

					if(!found){
						alert(Const.Message.Error.noPairMsg);
						return;
					}

					new Ajax.Request(
						'?page=save-template-overwrite-check',
						{
							method:'post',
							parameters:$H({name:templateName}).toQueryString(),
							onSuccess:function(httpObj){
								var resultObj = null;
								try{
									var resultObj = httpObj.responseText.evalJSON();
								}catch(e){
									alert(Const.Message.Error.ServerError);
									return;
								}
								if(!resultObj.result){
									alert(Const.Message.Error.ServerError);
									return;
								}
								var isOverwrite = false;
								if(resultObj.data.overwrite){
									var msg = Const.Message.Info.ConfirmOverwrite;
									msg = msg.replace('{0}',templateName);

									if(confirm(msg)){
										isOverwrite = true;
									}else{
										return;
									}
								}
								queryArray["name"] = templateName;
								queryArray["overwrite"] = isOverwrite;
								new Ajax.Request(
									'?page=save-template',
									{
										method:'post',
										parameters:queryArray,
										onSuccess:function(httpObj){
									var resultObj = null;
											try{
												var resultObj = httpObj.responseText.evalJSON();
											}catch(e){
												alert(Const.Message.Error.ServerError);
												return;
											}
											if(!resultObj.result){
												alert(Const.Message.Error.ServerError);
												return;
											}
											panel.hide();
											templateNameEle.value = '';
											alert(Const.Message.Info.saveComplate);
										},
										onFailure:function(){
											alert(Const.Message.Error.ServerError);
										},
										onComplete:function(){}
									}
								);
							},
							onFailure:function(){
								alert(Const.Message.Error.ServerError);
							},
							onComplete:function(){}
						}
					);
				},
				"class":"btn_blue01"
			},{
				"label":"Cancel",
				"callback":function(panel){
					panel.hide();
				},
				"class":"btn_blue01"
			}
		]
	});

	new PopupPanel('createEditUploadTemplate', 'createEditUploadTemplateWindow', {
		title : Const.Label.poptitle2,
		buttons : [
			{
				"label":"OK",
				"callback":function(panel){
					$('create-edit-template-upload-template-dummy-iframe-div').innerHTML = '<iframe name="create-edit-template-upload-iframe" style="width: 200px; height: 200px;"></iframe>';
					$('create-edit-template-upload-form').submit();
					panel.hide();
					$('createEditLoadTemplateWindowUploadAnotherBase').innerHTML = '';
					$('createEditLoadTemplateWindowUploadAnother').setAttribute('_append_index', '2');
				},
				"class":"btn_blue01"
			},{
				"label":"Cancel",
				"callback":function(panel){
					$('create-edit-template-upload-template-dummy-iframe-div').innerHTML = '';
					panel.hide();
					$('createEditLoadTemplateWindowUploadAnotherBase').innerHTML = '';
					$('createEditLoadTemplateWindowUploadAnother').setAttribute('_append_index', '2');
				},
				"class":"btn_blue01"
			}
		],
		onclose:function(panel){
			$('create-edit-template-upload-template-dummy-iframe-div').innerHTML = '';
			panel.hide();
			$('createEditLoadTemplateWindowUploadAnotherBase').innerHTML = '';
			$('createEditLoadTemplateWindowUploadAnother').setAttribute('_append_index', '2');
		}
	});

	new PopupPanel('createEditDownloadTemplate', 'createEditDownloadTemplateWindow', {
		title : Const.Label.poptitle6,
		offset : [-210, 0],
		buttons : [
			{
				"label":"OK",
				"callback":function(panel){
					var load_template    = createEditTemplate_loadedTemplates;
					var active_template  = activeTemplateData.pairData;
					var pairIndex        = 0;
					var queryArray       = [];
					var download_templateEle = $('download_template');
					download_templateEle.innerHTML="";
					var i = 0;

					for ( i = 0, len = active_template.length; i < len; i++) {
						if ((typeof active_template[i]) == 'undefined') {
							continue;
						}
						var ele = $(document.createElement('input'));
						ele.setAttribute('type','hidden');
						ele.setAttribute('name','template['+pairIndex+'][source]');
						ele.value = active_template[i].SOURCE_TEXT;
						download_templateEle.appendChild(ele);
						var ele = $(document.createElement('input'));
						ele.setAttribute('type','hidden');
						ele.setAttribute('name','template['+pairIndex+'][target]');
						ele.value = active_template[i].TARGET_TEXT;
						download_templateEle.appendChild(ele);
						pairIndex++;
					}

					for( i = 0, len = load_template.length; i < len; i++){
						for (var j = 0, pair_len = load_template[i].pairData.length; j < pair_len; j++) {
							if ((typeof load_template[i].pairData[j]) == 'undefined') {
								continue;
							}
							var ele = $(document.createElement('input'));
							ele.setAttribute('type','hidden');
							ele.setAttribute('name','template['+pairIndex+'][source]');
							ele.value = load_template[i].pairData[j].SOURCE_TEXT;
							download_templateEle.appendChild(ele);
							var ele = $(document.createElement('input'));
							ele.setAttribute('type','hidden');
							ele.setAttribute('name','template['+pairIndex+'][target]');
							ele.value = load_template[i].pairData[j].TARGET_TEXT;
							download_templateEle.appendChild(ele);
							pairIndex++;
						}
					}
					if(pairIndex==0){
						alert(Const.Message.Error.templateEmpty);
						return;
					}

					$('createEditDownloadTemplateForm').submit();
					$('createEditDownloadTemplateFileName').value = "";
					panel.hide();
				},
				"class":"btn_blue01"
			},{
				"label":"Cancel",
				"callback":function(panel){
					$('download_template').innerHTML="";
					$('createEditDownloadTemplateFileName').value = "";
					panel.hide();
				},
				"class":"btn_blue01"
			}
		]
	});

	getTargetLangTag();
	changeSourceLanguage();

	initInfoManager();
	Event.observe(document,'dom:TranslationDone', saveInfoData,false);
});

function loadTemplateData(btn_name,select_name){
	var selfEle = $(btn_name);
	var appendIndex = Number();
	appendIndex = Number(selfEle.getAttribute('_append_index'));

	if(appendIndex > 7){
		alert(Const.Message.Error.noMoreFieldAdd);
		return;
	}

	var base = $(btn_name+'Base');
	var item = $(document.createElement('div'));
	item.id = btn_name+'Item' + appendIndex;
	item.innerHTML  = Const.Label.template_name+' :';
	item.innerHTML += '<select id="'+select_name+appendIndex+'" style="width:200px;">';
	item.innerHTML += '<option> </option>';
	item.innerHTML += '</select>';
	selfEle.setAttribute('_append_index', appendIndex + 1);
	base.appendChild(item);

	new Ajax.Request(
		'?page=load-template-name',
		{
			asynchronous:false,
			method:'post',
			parameters:'',
			onSuccess:function(httpObj){
				var resultObj = null;
				try{
					var resultObj = httpObj.responseText.evalJSON();
				}catch(e){
					alert(Const.Message.Error.ServerError);
					return;
				}
				if(!resultObj.result){
					alert(Const.Message.Error.ServerError);
					return;
				}
				/*
				if(resultObj.data.names.length == 0){
					var msg = Const.Message.Error.noTemplate;
					alert(msg.replace('{0}',Const.Label.template_name));
					return;
				}
				*/

				var Tags = String("<option value=''>  </option>\n");
				for(i=0;i<resultObj.data.names.length;i++){
					Tags += "<option value='"+resultObj.data.names[i]+"'>"+resultObj.data.names[i]+"</option>\n";
				}
				$(select_name+appendIndex).update(Tags);
			},
			onFailure:function(){
				alert(Const.Message.Error.ServerError);
			},
			onComplete:function(){}
		}
	);
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
	$('workspace-area-original-language').update(langName);
	$('workspace-area-Backtranslated-language').update(langName);
	getTargetLangTag();

	var langCode = sourceLangSelecter.getSelectedLangCode();
	setEditorTextAlign('original-webpage-editor',langCode);
	setEditorTextAlign('back-translated-webpage-editor',langCode);
}
function changeTargetLanguage(event){
	var langName = targetLangSelecter.getSelectedLangName();
	$('workspace-area-translated-language').update(langName);

	var langCode = targetLangSelecter.getSelectedLangCode();
	setEditorTextAlign('translated-webpage-editor',langCode);
}

function setEditorTextAlign(myEditor,langCode){
	if(langCode == 'ar'){
		$(myEditor).style.textAlign = 'right';
	}else{
		$(myEditor).style.textAlign = 'left';
	}
}

//-----------------  PopupPanel Class  -----------------------
var PopupPanel = Class.create();
PopupPanel.prototype = {
	title : 'popup window',
	offset : [-110, 0],
	buttons : [
		{
			"label":"OK",
			"callback":function(panel){
				panel.hide();
			},
			"class":"btn_blue01"
		},
		{
			"label":"Cancel",
			"callback":function(panel){
				panel.hide();
			},
			"class":"btn_blue01"
		}
	],
	onopen:function(){
		//none
	},
	onclose:function(panel){
		panel.hide();
	},
	pos : [0,0],
	activeInstance : null,
	element : null,
	mask : null,
	initialize: function(buttonEle, windowContent, initObj){
		if((typeof initObj) !== 'undefined'){
			if((typeof initObj.offset) !== 'undefined'){
				this.offset = initObj.offset;
			}
			if((typeof initObj.title) !== 'undefined'){
				this.title = initObj.title;
			}
			if((typeof initObj.buttons) !== 'undefined'){
				this.buttons = initObj.buttons;
			}
			if((typeof initObj.onopen) !== 'undefined'){
				this.onopen = initObj.onopen;
			}
			if((typeof initObj.onclose) !== 'undefined'){
				this.onclose = initObj.onclose;
			}
		}
		if(!$('mask-pane')) {
			var mask = $(document.createElement('div'));
			mask.id = 'mask-pane';
			mask.style.zIndex = 5;
			$('webpage').appendChild(mask);
		}
		this.mask = $('mask-pane');

		this.buttonEle = $(buttonEle);
		this.pos = this.buttonEle.positionedOffset();
		this.pos[1] = this.offset[1] + this.pos[1] + this.buttonEle.getHeight();
		this.pos[0] = this.offset[0] + this.pos[0];

		this.windowEle = $(windowContent);
		this.windowEle.style.display = 'none';
		this.windowEle.style.position = 'absolute';
		this.windowEle.style.left = this.pos[0] + 'px';
		this.windowEle.style.top = this.pos[1] + 'px';
		this.windowEle.style.zIndex = 10;
		this.windowEle.className = 'popupbox';

		var self = this;

		var titleBaseEle = $(document.createElement('div'));
		Element.addClassName(titleBaseEle, 'popuptitle');

		var closeButtonEle = $(document.createElement('img'));
		closeButtonEle.setAttribute('src','../../themes/default/common/img/popup-close.gif');
		closeButtonEle.observe('click', function(){self.onclose(self)});

		titleBaseEle.appendChild(closeButtonEle);

		var titleEle = document.createTextNode(this.title);
		titleBaseEle.appendChild(titleEle);

		var innerEle = $(document.createElement('div'));
		Element.addClassName(innerEle, 'inner');
		var contentEle = $(document.createElement('div'));
		for(var i = 0, len = this.windowEle.childNodes.length; i < len; i++){
			contentEle.insertBefore(this.windowEle.childNodes.item(this.windowEle.childNodes.length-1), contentEle.firstChild);
		}

		var footerEle = $(document.createElement('div'));
		footerEle.style.textAlign = 'center';
		footerEle.style.marginTop = '10px';
		for(var i = 0, len = this.buttons.length; i < len; i++){
			var buttonData = this.buttons[i];
			var btn = $(document.createElement('input'));
			btn.type = "button";
			btn.style.marginRight = '10px';
			btn.value = buttonData.label;
			if((typeof buttonData['class']) !== 'undefined'){
				btn.className = buttonData['class'];
			}

			if((typeof buttonData['callback']) !== 'undefined'){
				var func = null;
				eval('func = function(){self.buttons[' + i + ']["callback"](self)}');
				Event.observe(btn,'click',func);
			}
			footerEle.appendChild(btn);
		}
		innerEle.appendChild(contentEle);
		innerEle.appendChild(footerEle);

		this.windowEle.innerHTML = '';
		this.windowEle.appendChild(titleBaseEle);
		this.windowEle.appendChild(innerEle);

		this.buttonEle.observe('click', function(){
			self.toggle();
		});
	},
	isVisible : function(){
		return this.windowEle.style.display == 'block';
	},
	show : function(){
		if (PopupPanel.prototype.activeInstance !== null &&
			PopupPanel.prototype.activeInstance !== this)
		{
			PopupPanel.prototype.activeInstance.hide();
		}
		PopupPanel.prototype.activeInstance = this;

		//this.pos = this.buttonEle.positionedOffset();
		//this.pos[1] = this.offset[1] + this.pos[1] + this.buttonEle.getHeight();
		//this.pos[0] = this.offset[0] + this.pos[0];
		//this.windowEle.style.left = this.pos[0] + 'px';
		//this.windowEle.style.top = this.pos[1] + 'px';
		var vp = document.viewport.getDimensions();
		var vp_sc = document.viewport.getScrollOffsets();

		var value_opacity = 4;
		$(this.mask).style.filter = 'alpha(opacity=' + (value_opacity * 10) + ')';
		$(this.mask).style.MozOpacity = value_opacity / 10;
		$(this.mask).style.opacity = value_opacity / 10;

		$(this.mask).show();
		$(this.mask).setStyle({position: 'absolute',top:vp_sc.top+'px',left:vp_sc.left+'px',width: vp.width + 'px',height: vp.height + 'px'});

		Event.observe(window, "scroll",this._onScrollWin.bind(this),false);
		Event.observe(window, "resize",this._onResizeWin.bind(this),false);


		this.windowEle.show();
		var left = ((vp.width - this.windowEle.offsetWidth) / 2) + vp_sc.left;
		var top = ((vp.height - this.windowEle.offsetHeight) / 2) + vp_sc.top;

		if((top + this.windowEle.offsetHeight - vp_sc.top) > vp.height){
			top = vp.height - this.windowEle.offsetHeight + vp_sc.top;
		}

		if((left + this.windowEle.offsetWidth - vp_sc.left) > vp.width){
			left = vp.width - this.windowEle.offsetWidth + vp_sc.left;
		}

		this.windowEle.setStyle({position: 'absolute' ,left : left + 'px' ,top : top + 'px'});

		//this.windowEle.style.display = 'block';

		this.onopen();
	},
	hide : function(){
		$(this.windowEle).hide();
		$(this.mask).hide();
	},
	_onScrollWin:function(ev){
		var vp_sc = document.viewport.getScrollOffsets();
		$(this.mask).setStyle({top:vp_sc.top+'px',left:vp_sc.left+'px'});
	},
	_onResizeWin:function(ev){
		var vp = document.viewport.getDimensions();
		$(this.mask).setStyle({width: vp.width + 'px',height: vp.height + 'px'});
	},
	toggle : function(){
		if(this.windowEle.style.display == 'none'){
			this.show();
		}else{
			this.hide();
		}
	}
}

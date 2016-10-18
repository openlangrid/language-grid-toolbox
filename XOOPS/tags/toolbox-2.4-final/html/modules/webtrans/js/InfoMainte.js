//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009  NICT Language Grid Project
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
	localInfoManager = new InfoManager("mainte");
	loadInfoData();
}

function saveInfoData(){
	var data = {};
	try{
		data['webpage-url'] = $F('url-input');
		data['original-webpagearea'] = $F('original-webpage-editor');
		data['translation-webpagearea'] = $F('translated-webpage-editor');
		data['backtranslation-webpagearea'] = $F('back-translated-webpage-editor');
		data['sourceLang'] = sourceLangSelecter.getSelectedLangCode();
		data['targetLang'] = targetLangSelecter.getSelectedLangCode();

		var items = Object.toJSON(data);
		localInfoManager.saveItems($F("moduleId"),$F("screenId"),items);
	}catch(e){
		alert(e.message);
	}
}

function clearInfoData(){
	localInfoManager.clearItems($F("moduleId"),$F("screenId"));
}

function loadInfoData(){
	var items = localInfoManager.loadItems($F("moduleId"),$F("screenId"));
	if(items.each){
		if(items.get('sourceLang') && $('sourceLang')){
			$('sourceLang').value = items.get('sourceLang');
			changeSourceLanguage();
		}

		if(items.get('targetLang') && $('targetLang')){
			$('targetLang').value = items.get('targetLang');
			changeTargetLanguage();
		}

		if(items.get('webpage-url') && $('url-input')){
			$('url-input').value = items.get('webpage-url');
		}
		if(items.get('original-webpagearea') && $('original-webpage-editor')){
			$('original-webpage-editor').value = items.get('original-webpagearea');
		}
		if(items.get('translation-webpagearea') && $('translated-webpage-editor')){
			$('translated-webpage-editor').value = items.get('translation-webpagearea');
		}
		if(items.get('backtranslation-webpagearea') && $('back-translated-webpage-editor')){
			$('back-translated-webpage-editor').value = items.get('backtranslation-webpagearea');
		}
	}
}

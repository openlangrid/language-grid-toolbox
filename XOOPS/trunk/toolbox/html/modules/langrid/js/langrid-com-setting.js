//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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

var ComAddPathWorkspace = Class.create();

Object.extend(ComAddPathWorkspace.prototype, AddPathWorkspace.prototype);
Object.extend(ComAddPathWorkspace.prototype, {
	_showPanel: function(setting,listArea) {
		var idx = this.pathPanelArray.length;
		var pathPanel = new ComPathPanel();
		pathPanel.makeTranslationPathPanel(idx, listArea,
			this._langridServiceInformations,
			this.pathPanelArray,
			this._DefaultSettings,
			 setting);
		this.pathPanelArray.push(pathPanel);
	},
	_loadSetting: function() {
		var controller = this;
		var postObj = {};
		var hash = $H(postObj).toQueryString();

		new Ajax.Request('./ajax/load-communication-setting.php', {
			method: 'post',
			parameters: hash,
			controller: controller,
			onSuccess: function(httpObj) {
				try {
					var responseJSON = httpObj.responseText.evalJSON()
					if (responseJSON.status != 'OK') {
						if(responseJSON.status == 'SESSIONTIMEOUT'){
							redirect2top();
							return;
						}
						alert(responseJSON.message);
						return;
					}
					controller._langridServiceInformations['_sourceLanguageArray'] = responseJSON.contents.supportLangs.sourceLanguages;
					controller._langridServiceInformations['_targetLanguageArray'] = responseJSON.contents.supportLangs.targetLanguages;
					controller._langridServiceInformations['_translationServicesArray'] = responseJSON.contents.translationServices;
					controller._langridServiceInformations['_analyzerServicesArray'] = responseJSON.contents.analyzeServices;
					controller._langridServiceInformations['_dictionaryServicesArray'] = responseJSON.contents.dictionaryServices;
					controller._langridServiceInformations['_currentSettings'] = responseJSON.contents.setting;

					var dictIds = responseJSON.contents.DefaultDicts.bind_global_dict_ids;
					if (dictIds != null && !dictIds == '') {
						controller._DefaultSettings.global = dictIds.split(',');
					}
					var localDictIds = responseJSON.contents.DefaultDicts.bind_local_dict_ids;
					if (localDictIds != null && !localDictIds == '') {
						controller._DefaultSettings.local = localDictIds.split(',');
					}
					var userDictIds = responseJSON.contents.DefaultDicts.bind_user_dict_ids;
					if (userDictIds != null && !userDictIds == '') {
						controller._DefaultSettings.temp = userDictIds.split(',');
					}

					controller._makeSearchPanel();
					controller._makeEditSettingArea();

					if($('list-area')){Element.remove($('list-area'));}
					var list_area = document.createElement('div');
					list_area.id = 'list-area';
					$A(controller._langridServiceInformations['_currentSettings']).each(function(setting, index){
						controller._showPanel(setting,list_area);
					}.bind(controller));
					controller.MainPanel.appendChild(list_area);

					controller._makePageTopArea();

					controller.__initializedView();
				} catch (e) {
					controller.__initializedError(e);
				}
				isInitializing = false;
			}.bind(this),
			onFailure: function(httpObj) {
				$('setting_error_message').innerHTML = '<pre>'+httpObj.responseText+'</pre>';
			},
			onComplete: function() {}
		});
	},
	onDictionarySaveHandler:function(){
		var controller = this;
		var postObj = {};
		postObj['mode'] = 'COMMUNICATION';
		postObj['global_dict_ids'] = this._DefaultSettings.global.join(',');
		postObj['local_dict_ids'] = this._DefaultSettings.local.join(',');
		postObj['user_dict_ids'] = this._DefaultSettings.temp.join(',');
		var hash = $H(postObj).toQueryString();
		new Ajax.Request('./ajax/save-communication-dict.php', {
			method: 'post',
			parameters: hash,
			controller: controller,
			onSuccess: function(httpObj) {
				var responseJSON = httpObj.responseText.evalJSON();
				if(responseJSON.status == 'SESSIONTIMEOUT'){
					redirect2top();
					return;
				}

				if (isInitializing == false) {
					$A(controller.pathPanelArray).each(function(panelElem, index){
						if(controller._DefaultSettings.global.length == 0 
						  && controller._DefaultSettings.local.length == 0
						  && controller._DefaultSettings.temp.length == 0){
							for(var i=0;i<=2;i++){
								if(panelElem._dict_flags[i] == 1){
									panelElem._dict_flags[i] = 0;
									if($(panelElem.elementsIds.panel+':viewdictimg_'+i)){
										panelElem._global_dicts[i] = new Array();
										panelElem._local_dicts[i] = new Array();
										panelElem._temp_dicts[i] = new Array();
										panelElem.updateViewPanel();

										$(panelElem.elementsIds.panel + ':viewdictimg_'+i).hide();
									}
								}
							}
						}else{
							for(var i=0;i<=2;i++){
								if(panelElem._dict_flags[i] == 0 || panelElem._dict_flags[i] == 1){
									panelElem._dict_flags[i] = 1;
									panelElem._global_dicts[i] = controller._DefaultSettings.global.join(",").split(",");
									panelElem._local_dicts[i] = controller._DefaultSettings.local.join(",").split(",");
									panelElem._temp_dicts[i] = controller._DefaultSettings.temp.join(",").split(",");
									panelElem.updateViewPanel();
									if($(panelElem.elementsIds.panel+':viewdictimg_'+i)){
										var img = $(panelElem.elementsIds.panel + ':viewdictimg_'+i);
										Element.removeClassName(img,"btn-user-dic");
										Element.addClassName(img,"btn-def-dic");
										img.show();
									}
								}
							}
						}
					}.bind(controller));
				}
			}.bind(this),
			onFailure: function(httpObj) {
				$('setting_error_message').innerHTML = '<per>' + httpObj.responseText + '</pre>';
			},
			onComplete: function() {
				this._setDictionaryCount();
			}.bind(this)
		});
	},
	_onAddPathButtonClicked:function(ev){
		var ret = false;
		$A(this.pathPanelArray).each(function(panelElem, index){
			if(Element.hasClassName(panelElem._selfPanel,'nowedit')){
				if(!panelElem._onCancelButtonClicked()){
					ret = true;
					throw $break;
				}
			}
		});
		if(ret){return;}

		var pathPanel = new ComPathPanel();
		pathPanel.makeNewTranslationPathPanel(
			$('list-area'),
			$('new-path-area'),
			this._langridServiceInformations,
			this.pathPanelArray,
			this._DefaultSettings
		);
		Element.addClassName(pathPanel._selfPanel,"nowedit");
		this.pathPanelArray.push(pathPanel);

		Element.hide($('div-add-path-area'));
		Element.show($('new-path-area'));
		document.fire('dom:refreshMessage');
	},
	_langridServiceInformations: {}

});

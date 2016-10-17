//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
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

var PageAddPathWorkspace = Class.create();

Object.extend(PageAddPathWorkspace.prototype, AddPathWorkspace.prototype);
Object.extend(PageAddPathWorkspace.prototype, {
	_showPanel: function(setting,listArea) {
		var idx = this.pathPanelArray.length;
		var pathPanel = new PagePathPanel();
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

		sajax_request_type = 'POST';
		sajax_do_call('LanguageGridAjaxController::invoke', ['Setting:Load', Const.Wiki.TitleDBKey], function(httpObj) {
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
				controller._langridServiceInformations['_sourceLanguageArray'] = LanguageGridServices.SupportedLanguage.sourceLanguages;
				controller._langridServiceInformations['_targetLanguageArray'] = LanguageGridServices.SupportedLanguage.targetLanguages;
				controller._langridServiceInformations['_translationServicesArray'] = LanguageGridServices.TranslatorServices;
				controller._langridServiceInformations['_analyzerServicesArray'] = LanguageGridServices.AnalyzerServices;
				controller._langridServiceInformations['_dictionaryServicesArray'] = LanguageGridServices.DictionaryServices;

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
				var lite = responseJSON.contents.TranslationOptions.lite;
				if (lite != null && !lite == '') {
					controller._DefaultSettings.lite = lite;
				}
				var rich = responseJSON.contents.TranslationOptions.rich;
				if (rich != null && !rich == '') {
					controller._DefaultSettings.rich = rich;
				}

				controller._makeSearchPanel();
				controller._makeEditSettingArea();

				if($('list-area')){Element.remove($('list-area'));}
				var list_area = document.createElement('div');
				list_area.id = 'list-area';
				if ($A(controller._langridServiceInformations['_currentSettings']).length == 0) {
					$('top-message-area').innerHTML = Const.Message.PathNotFound;
					$('top-message-area').show();
				} else {
					$A(controller._langridServiceInformations['_currentSettings']).each(function(setting, index){
						controller._showPanel(setting,list_area);
					}.bind(controller));
				}
				controller.MainPanel.appendChild(list_area);

				controller._makeDefaltDictionaryArea();
				controller._makeTranslationOptionsArea();
				controller._makePageTopArea();

				controller.__initializedView();
			} catch (e) {
				controller.__initializedError(e);
//				alert(e.toSource());
			}
			isInitializing = false;
		});
	},
	onDictionarySaveHandler:function(){
		if(this._DefaultSettings.global.length != 0
		|| this._DefaultSettings.local.length != 0
		|| this._DefaultSettings.temp.length != 0
		){
			var isAllSave = false;
			$A(this.pathPanelArray).each(function(panelElem, index){
				if(panelElem.elementsIds.panel != "panel-N" && panelElem._primaryId == ''){
					panelElem._makeSaveDataAll();
					document.fire('dom:refreshMessage');
					throw $break;
				}
			});
		}

		var controller = this;
		var postObj = {};
		postObj['title_db_key'] = Const.Wiki.TitleDBKey;
		postObj['global_dict_ids'] = this._DefaultSettings.global.join(',');
		var hash = $H(postObj).toQueryString();

		sajax_request_type = 'POST';
		sajax_do_call('LanguageGridAjaxController::invoke', ['Setting:DictSave', hash], function(httpObj) {
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
				});
				controller._setDictionaryCount();
			}
		});
	},
	
	onTranslationOptionsSaveHandler:function(){

		var controller = this;
		var postObj = {};
		postObj['title_db_key'] = Const.Wiki.TitleDBKey;
		postObj['lite'] = this._DefaultSettings.lite;
		postObj['rich'] = this._DefaultSettings.rich;
		var hash = $H(postObj).toQueryString();

		sajax_request_type = 'POST';
		sajax_do_call('LanguageGridAjaxController::invoke', ['Setting:OptionSave', hash], function(httpObj) {
			var responseJSON = httpObj.responseText.evalJSON();
			if(responseJSON.status == 'SESSIONTIMEOUT'){
				redirect2top();
				return;
			}

			if (isInitializing == false) {
				controller._setTranslationOptionsStatus();
			}
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

		var pathPanel = new PagePathPanel();
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

	_makeSearchPanel: function() {
		var baseElm = this.MainPanel;

		var SourceArray = this._langridServiceInformations['_sourceLanguageArray'];
		var TargetArray = this._langridServiceInformations['_targetLanguageArray'];

		var search_area_wrapper = document.createElement('div');
		search_area_wrapper.id = 'search-area';
		Element.addClassName(search_area_wrapper, 'div-search-area');
		Element.addClassName(search_area_wrapper, 'setting');

		var search_area = document.createElement('fieldset');
		search_area_wrapper.appendChild(search_area);

		var legend = document.createElement('legend');
		legend.appendChild(document.createTextNode(Const.Label.SearchBoxTitle));
		search_area.appendChild(legend);
		search_area.appendChild(document.createTextNode(Const.Label.FilterFrom));

		//-----from select----
		var fromElem = document.createElement('select');
		fromElem.id = "search-from";
		var defaultOpt = document.createElement('option');
		defaultOpt.setAttribute('value', '');
		defaultOpt.innerHTML = '---';
		fromElem.appendChild(defaultOpt);
		$H(SourceArray).each(function(data){
			var opt = document.createElement('option');
			opt.setAttribute('value', data.key);
			opt.innerHTML = data.value;
			fromElem.appendChild(opt);
		});
		search_area.appendChild(fromElem);
		//-------------------
		search_area.appendChild(document.createTextNode(" "+Const.Label.FilterTo+" "));
		//-----to select----
		var toElem = document.createElement('select');
		toElem.id = "search-to";
		var defaultOpt = document.createElement('option');
		defaultOpt.setAttribute('value', '');
		defaultOpt.innerHTML = '---';
		toElem.appendChild(defaultOpt);
		$H(SourceArray).each(function(data){
			var opt = document.createElement('option');
			opt.setAttribute('value', data.key);
			opt.innerHTML = data.value;
			toElem.appendChild(opt);
		});
		search_area.appendChild(toElem);
		//-------------------
		search_area.appendChild(document.createTextNode(" "));
		//-------------------
		var btn = document.createElement('input');
		btn.setAttribute('type', 'button');
		btn.setAttribute('value', Const.Label.FlterButton);
		Event.observe(btn, 'click', this._filterPanel.bind(this),false);
		search_area.appendChild(btn);
		//-------------------

		var matches = document.createElement('span');
		matches.id = "matched-area";
		var m_cnt = document.createElement('strong');
		m_cnt.id = "match-count";
		matches.appendChild(m_cnt);
		matches.appendChild(document.createTextNode(" ["));
		var reset = document.createElement('a');
		reset.innerHTML = Const.Label.DispAllPath;
		Event.observe(reset, 'click', this._resetFilter.bind(this));
		matches.appendChild(reset);
		matches.appendChild(document.createTextNode("]"));
		Element.hide(matches);
		search_area.appendChild(matches);

		baseElm.appendChild(search_area_wrapper);
	},

	_makeEditSettingArea:function(){
		var baseElm = this.MainPanel;
		var setting_area = document.createElement('div');
		setting_area.id = 'div-setting-area';

		var add_path_area = document.createElement('div');
		add_path_area.id = 'div-add-path-area';
		var add_path_btn = document.createElement('a');
		add_path_btn.innerHTML = Const.Label.AddPath;
		Event.observe(add_path_btn, 'click', this._onAddPathButtonClicked.bind(this));
		add_path_area.appendChild(add_path_btn);

		add_path_area.appendChild(document.createTextNode(Const.Label.AddPathDesc));

		setting_area.appendChild(add_path_area);

		baseElm.appendChild(setting_area);

		var new_path_area = document.createElement('div');
		new_path_area.id = 'new-path-area';
		Element.addClassName(new_path_area, 'div-path-add-area');
		Element.hide(new_path_area);

		baseElm.appendChild(new_path_area);

		var top_message_area = document.createElement('div');
		top_message_area.id = 'top-message-area';
		Element.addClassName(top_message_area, 'div-message-box');
		Element.hide(top_message_area);

		baseElm.appendChild(top_message_area);
	},

	_makeDefaltDictionaryArea: function() {
		var baseElm = this.MainPanel;

		var dd_area_wrapper = document.createElement('div');
		Element.addClassName(dd_area_wrapper, 'setting');

		var dd_area = document.createElement('fieldset');
		dd_area_wrapper.appendChild(dd_area);

		var legend = document.createElement('legend');
		legend.appendChild(document.createTextNode("Default dictionary setting"));
		dd_area.appendChild(legend);

		var dd_count = document.createElement('span');
		dd_count.id = 'def-dic-count';
		dd_area.appendChild(dd_count);

		var l = document.createTextNode('[');
		var r = document.createTextNode(']');
		var edit_btn = document.createElement('a');
		edit_btn.innerHTML = Const.Label.EditDefaultDict;
		Event.observe(edit_btn, 'click', this._onDefaultDictionaryButtonClicked.bind(this));
		dd_area.appendChild(l);
		dd_area.appendChild(edit_btn);
		dd_area.appendChild(r);

		baseElm.appendChild(dd_area_wrapper);
		this._setDictionaryCount();
	},

	_setDictionaryCount:function(){
		if(this._DefaultSettings.global.length > 0
		  || this._DefaultSettings.local.length > 0
		  || this._DefaultSettings.temp.length > 0){
			var cnt = this._DefaultSettings.global.length + this._DefaultSettings.local.length + this._DefaultSettings.temp.length;
			$('def-dic-count').innerHTML = "&nbsp;("+ String(Const.Label.DictSelect).replace("%S",cnt) + ")&nbsp;";
		}else{
			$('def-dic-count').innerHTML = "&nbsp;("+Const.Label.DictNoSelect+")&nbsp;";
		}
	},

	_makeTranslationOptionsArea: function() {
		var baseElm = this.MainPanel;
		
		var to_area_wrapper = document.createElement('div');
		Element.addClassName(to_area_wrapper, 'setting');

		var to_area = document.createElement('fieldset');
		to_area_wrapper.appendChild(to_area);

		var legend = document.createElement('legend');
		legend.appendChild(document.createTextNode("Translation options"));
		to_area.appendChild(legend);

		var to_status = document.createElement('span');
		to_status.id = 'translation-options-state';
		to_area.appendChild(to_status);
		
		var l = document.createTextNode('[');
		var r = document.createTextNode(']');
		var edit_btn = document.createElement('a');
		edit_btn.innerHTML = Const.Label.EditTranslationOptions;
		Event.observe(edit_btn, 'click', this._onTranslationOptionsButtonClicked.bind(this));
		to_area.appendChild(l);
		to_area.appendChild(edit_btn);
		to_area.appendChild(r);
		
		baseElm.appendChild(to_area_wrapper);
		this._setTranslationOptionsStatus();
	},

	_setTranslationOptionsStatus:function(){
		var innerHTML = '';
		if(!this._DefaultSettings.lite && !this._DefaultSettings.rich) {
			innerHTML = Const.Label.NoTranslationOptions;

		} else {
			if (this._DefaultSettings.lite) {
				innerHTML = '"'+Const.Label.Lite+'" enabled';
			}
			if (this._DefaultSettings.rich) {
				if (innerHTML) {
					innerHTML += '&nbsp;/&nbsp;';
				}
				innerHTML += '"'+Const.Label.Rich+'" enabled';
			}
		}
		$('translation-options-state').innerHTML = '&nbsp;('+innerHTML+')&nbsp;';
	},

	_onTranslationOptionsButtonClicked:function(ev){
		if(this.pathPanelArray.length){
			document.fire('dom:refreshMessage');
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
		}

		popupTranslationOptionsPanel = new TranslationOptionsPanel();

		popupTranslationOptionsPanel.setParams(0,'default',
			this._DefaultSettings,
			this);
		popupTranslationOptionsPanel.showPane("edit");
		
	},

	_langridServiceInformations: {}


});

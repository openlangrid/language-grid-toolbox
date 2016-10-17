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

var AddPathWorkspace = Class.create();
AddPathWorkspace.prototype = {
	MainPanel:null,
	pathPanelArray: Array(),
	_DefaultSettings: null,
	initialize: function() {
		this.MainPanel = $('translation-path-root-panel');
		Event.observe(document, 'dom:settingEditingThis', this._onSaveButtonToggle.bind(this));
		Event.observe(document, 'dom:refreshMessage', this._refreshMessage.bind(this));
		Event.observe(window, 'beforeunload', this._confirmChange.bind(this));
		this._DefaultSettings = {global:Array(),local:Array(),temp:Array()};
	},
	_onSaveButtonToggle: function(ev) {
		if (isInitializing == false) {
			$A(this.pathPanelArray).each(function(panelElem, index){
				if($(panelElem.elementsIds.panel+':message')){
					if(Element.visible($(panelElem.elementsIds.panel+':message'))){
						Element.hide($(panelElem.elementsIds.panel+':message'))
					}
				}
				if(Element.hasClassName(panelElem._selfPanel,'nowedit')){
					var save_btn = $(panelElem.elementsIds.panel+':save');
					var save_img = $(panelElem.elementsIds.panel+':save-img');

					if(save_btn){
						panelElem.updateEditElement();
						var vmsg = panelElem.validate();
						var isChange = panelElem._checkChange();

						if (vmsg == null && isChange) {
							Element.removeClassName(save_btn,"btn-disable");
							Element.addClassName(save_btn,"btn");
							try {
							save_btn.removeAttribute('disabled');
							} catch (e) {}
						}else{
							Element.removeClassName(save_btn,"btn");
							Element.addClassName(save_btn,"btn-disable");
							try {
							save_btn.setAttribute('disabled', 'yes');
							} catch (e) {}
						}
					}
				}
			});
		}
	},
	_refreshMessage: function(ev) {
		if($('top-message-area')){
			if(Element.visible($('top-message-area'))){
				Element.hide($('top-message-area'));
			}
		}
		$A(this.pathPanelArray).each(function(panelElem, index){
			if($(panelElem.elementsIds.panel+':message')){
				if(Element.visible($(panelElem.elementsIds.panel+':message'))){
					Element.hide($(panelElem.elementsIds.panel+':message'))
				}
			}
		});
	},
	_confirmChange: function(ev) {
		var isCancel = false;
		$A(this.pathPanelArray).each(function(panelElem, index){
			if(Element.hasClassName(panelElem._selfPanel,'nowedit')){
				if(!Element.hasClassName($(panelElem.elementsIds.panel+":save"),'btn-disable')){
					return ev.returnValue = Const.Message.CancelConfirm;
					throw $break;
				}
			}
		});
	},
	start: function() {
		isInitializing = true;
		this.__initializingView();
		this.pathPanelArray = new Array();
		this._loadSetting();
	},
	_showPanel: function(setting,listArea) {
		var idx = this.pathPanelArray.length;
		var pathPanel = new TranslationPathPanel();
		pathPanel.makeTranslationPathPanel(idx, listArea,
			this._langridServiceInformations,
			this.pathPanelArray,
			this._DefaultSettings,
			setting);
		this.pathPanelArray.push(pathPanel);
	},
	_makeSearchPanel: function() {
		var baseElm = this.MainPanel;

		var SourceArray = this._langridServiceInformations['_sourceLanguageArray'];
		var TargetArray = this._langridServiceInformations['_targetLanguageArray'];

		var search_area = document.createElement('div');
		search_area.id = 'search-area';
		search_area.appendChild(document.createTextNode(Const.Label.FilterFrom+" "));
		Element.addClassName(search_area, 'div-search-area');

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

		baseElm.appendChild(search_area);
	},
	_filterPanel:function (){
		var s_from = $('search-from').value;
		var s_to = $('search-to').value;
		if(this.pathPanelArray.length){
			if(s_from != "" || s_to != ""){
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

				var cnt = 0;
				$A(this.pathPanelArray).each(function(panelElem, index){
					var isView = false;
					var lang1 = "";
					var lang2 = "";
					var r_lang1 = "";
					var r_lang2 = "";

					lang1 = panelElem.FirstLang;
					lang2 = panelElem.LastLang;

					if(this._comp_lang(s_from,s_to,lang1,lang2)){
						cnt++;
						isView = true;
					}
					if(!isView && panelElem.flow == "both"){
						r_lang1 = lang2;
						r_lang2 = lang1;
						if(this._comp_lang(s_from,s_to,r_lang1,r_lang2)){
							panelElem.reverseValue();
							cnt++;
							isView = true;
						}
					}

					if(isView){
						Element.show(panelElem._selfPanel);
					}else{
						Element.hide(panelElem._selfPanel);
					}
				}.bind(this));
				this._sortPanelArray();
				$('match-count').innerHTML = String(cnt)+" "+Const.Label.Matches;
				Element.removeClassName($('search-area'),'div-search-area');
				Element.addClassName($('search-area'),'div-search-area-executed');
				Element.show($('matched-area'));
			}
		}
	},
	_comp_lang:function(s_from,s_to,lang1,lang2){
		if(s_from != "" && s_to != ""){
			if(lang1 == s_from && lang2 == s_to){
				return true;
			}
		}else if(s_from != ""){
			if(lang1 == s_from){
				return true;
			}
		}else if(s_to != ""){
			if(lang2 == s_to){
				return true;
			}
		}
		return false;
	},
	_sortPanelArray:function(){
		if(this.pathPanelArray.length){
			this.__sort_array(this.pathPanelArray, 0, this.pathPanelArray.length - 1);

			for(var i = 0;i<this.pathPanelArray.length;i++){
				$('list-area').appendChild(this.pathPanelArray[i]._selfPanel);
			}
		}
	},
	__sort_array:function (ary, head, tail) {
		var pivot = ary[parseInt( head +  (tail - head)/2 )];
		var i = head - 1;
		var j = tail + 1;
		while (1){
			while (this.__cmp_data(ary[++i], pivot) < 0);
			while (this.__cmp_data(ary[--j], pivot) > 0);
			if (i >= j) break;
			var tmp = ary[i];
			ary[i] = ary[j];
			ary[j] = tmp;
		}
		if (head < i - 1) this.__sort_array(ary, head, i - 1);
		if (j + 1 < tail) this.__sort_array(ary, j + 1, tail);
		return ary;
	},
	__cmp_data:function (a,b){
		var a_Lang1 = a.getdataArrayValue(a._langridServiceInformations['_sourceLanguageArray'],a.FirstLang);
		var a_Lang2 = a.getdataArrayValue(a._langridServiceInformations['_targetLanguageArray'],a.LastLang);

		var b_Lang1 = b.getdataArrayValue(b._langridServiceInformations['_sourceLanguageArray'],b.FirstLang);
		var b_Lang2 = b.getdataArrayValue(b._langridServiceInformations['_targetLanguageArray'],b.LastLang);

		if(a_Lang1 == b_Lang1){
			if(a_Lang2 == b_Lang2){
				return 0;
			}else{
				if(a_Lang2 > b_Lang2){
					return 1;
				}else{
					return -1;
				}
			}
		}else{
			if(a_Lang1 > b_Lang1){
				return 1;
			}else{
				return -1;
			}
		}

	},
	_resetFilter:function(){
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

		$A(this.pathPanelArray).each(function(panelElem, index){
			Element.show(panelElem._selfPanel);
		});
		$('search-from').value="";
		$('search-to').value="";
		Element.removeClassName($('search-area'),'div-search-area-executed');
		Element.addClassName($('search-area'), 'div-search-area');
		Element.hide($('matched-area'));
	},
	_makeEditSettingArea:function(){
		var baseElm = this.MainPanel;
		var setting_area = document.createElement('div');
		setting_area.id = 'div-setting-area';

		var default_dic_area = document.createElement('div');
		default_dic_area.id = 'div-edit-default-area';
		var default_dic_btn = document.createElement('a');
		default_dic_btn.innerHTML = ' '+Const.Label.EditDefaultDict;
		Event.observe(default_dic_btn, 'click', this._onDefaultDictionaryButtonClicked.bind(this));
		default_dic_area.appendChild(default_dic_btn);

		var dics_count = document.createElement('span');
		dics_count.id = "def-dic-count";
		default_dic_area.appendChild(dics_count);

		var add_path_area = document.createElement('div');
		add_path_area.id = 'div-add-path-area';
		var add_path_btn = document.createElement('a');
		Element.addClassName(add_path_btn, 'btn btn-addpath');
		add_path_btn.innerHTML = ' '+Const.Label.AddPath;
		Event.observe(add_path_btn, 'click', this._onAddPathButtonClicked.bind(this));
		add_path_area.appendChild(add_path_btn);

		setting_area.appendChild(default_dic_area);
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

		this._setDictionaryCount();
	},
	_onDefaultDictionaryButtonClicked:function(ev){
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

		popupDictionaryPanel = new DictionaryPanel();

		popupDictionaryPanel.setParams(0,'default',
			this._langridServiceInformations['_dictionaryServicesArray'],
			this._langridServiceInformations['_analyzerServicesArray'],
			{lang1:'',langname1:'',lang2:'',langname2:''},
			this._DefaultSettings.global,
			this._DefaultSettings.local,
			this._DefaultSettings.temp,
			'',
			'',
			{global:Array(),local:Array(),temp:Array()},
			this);
		popupDictionaryPanel.showPane("edit");
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

		var pathPanel = new TranslationPathPanel();
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
	_makePageTopArea:function (){
		var baseElm = this.MainPanel;

		var pagetop_area = document.createElement('div');
		Element.addClassName(pagetop_area, 'div-pagetop-area');

		var hlink = document.createElement('a');
		Element.addClassName(hlink, 'inner-link');

		hlink.innerHTML = Const.Label.ReturnTop;
		$(hlink).observe('click',this._gotoPageTop);
		pagetop_area.appendChild(hlink);

		baseElm.appendChild(pagetop_area);
	},
	_gotoPageTop :function(){
		Element.scrollTo($('globalWrapper'));
	},
	__initializingView: function() {
		if($('div-page-message')){
			$('div-page-message').hide();
		}
		$('setting_nowload').show();

		var length = this.MainPanel.childNodes.length-1;
		if(length>0){
			for(var i=length; i>0; i--){
				var childObj=this.MainPanel.childNodes[i];
				if(childObj.nodeName!=undefined){
					this.MainPanel.removeChild(childObj);
				}
			}
		}
		this.MainPanel.innerHTML = '';

		$('setting_error_message').innerHTML = '';
	},
	__initializedView: function() {
		this._sortPanelArray();
		$('setting_nowload').hide();
		if($('div-page-message')){
			$('div-page-message').show();
		}
	},
	__initializedError: function(e) {
		var html = new Array();
		html.push('<div style="color:#FF6347;">');
		html.push('<h2>ERROR:failed in initialization processing.</h2>');
		html.push('<pre>'+e.description+'</pre>');
		html.push('</div>');
		$('setting_nowload').innerHTML = html.join('');
	},
	_loadSetting: function() {
		var controller = this;

		var postObj = {};
		var hash = $H(postObj).toQueryString();
		new Ajax.Request('./ajax/load-user-setting.php', {
		//new Ajax.Request('./ajax/dummy.php', {
			method: 'post',
			parameters: hash,
			controller: controller,
			onSuccess: function(httpObj) {
				try {
					//var loaddata = decodeURIComponent(Const.DEFINE.loadJsonData);
					//var responseJSON = loaddata.evalJSON()
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


				var TimeFinish=new Date();
				var dummy = (parseFloat(TimeFinish.getMinutes())*60000
				+ parseFloat(TimeFinish.getSeconds()*1000)
				+ parseFloat(TimeFinish.getMilliseconds()) )
				-( parseFloat(TimeBegin.getMinutes())*60000
				+ parseFloat(TimeBegin.getSeconds()*1000)
				+ parseFloat(TimeBegin.getMilliseconds()) )
				var SyoriJikan = Math.round(dummy);

				$('debug').value = SyoriJikan;

			}.bind(this),
			onFailure: function(httpObj) {
				$('setting_error_message').innerHTML = '<pre>'+httpObj.responseText+'</pre>';
			},
			onComplete: function() {}
		});
	},
	onDictionaryClickHandler: function(level,did, checked, dictionaryType) {
		//level is not use.
		switch(dictionaryType){
			case 'GLOBAL':
				if (checked == true) {
					this._DefaultSettings.global.push(did);
				} else {
					var idx = this._DefaultSettings.global.indexOf(did);
					if (idx > -1) {
						this._DefaultSettings.global.splice(idx, 1);
					}
				}
				this._DefaultSettings.global = this._DefaultSettings.global.sort();
				break;
			case 'LOCAL':
				if (checked == true) {
					this._DefaultSettings.local.push(did);
				} else {
					var idx = this._DefaultSettings.local.indexOf(did);
					if (idx > -1) {
						this._DefaultSettings.local.splice(idx, 1);
					}
				}
				this._DefaultSettings.local = this._DefaultSettings.local.sort();
				break;
			case 'USER':
				if (checked == true) {
					this._DefaultSettings.temp.push(did);
				} else {
					var idx = this._DefaultSettings.temp.indexOf(did);
					if (idx > -1) {
						this._DefaultSettings.temp.splice(idx, 1);
					}
				}
				this._DefaultSettings.temp = this._DefaultSettings.temp.sort();
				break;
		}
	},
	clearSelectDictionary: function() {
		this._DefaultSettings.global.clear();
		this._DefaultSettings.local.clear();
		this._DefaultSettings.temp.clear();
		this._DefaultSettings.morph_from = '';
		this._DefaultSettings.morph_to = '';
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
		postObj['mode'] = 'ALL';
		postObj['global_dict_ids'] = this._DefaultSettings.global.join(',');
		postObj['local_dict_ids'] = this._DefaultSettings.local.join(',');
		postObj['user_dict_ids'] = this._DefaultSettings.temp.join(',');
		var hash = $H(postObj).toQueryString();
		new Ajax.Request('./ajax/save-user-dict.php', {
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

										//$(panelElem.elementsIds.panel + ':viewdictimg_'+i).hide();
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
	_setDictionaryCount:function(){
		if(this._DefaultSettings.global.length > 0
		  || this._DefaultSettings.local.length > 0
		  || this._DefaultSettings.temp.length > 0){
			Element.show($('img-def-dict'));	//icon
			var cnt = this._DefaultSettings.global.length + this._DefaultSettings.local.length + this._DefaultSettings.temp.length;
			$('def-dic-count').innerHTML = "&nbsp;("+ String(Const.Label.DictSelect).replace("%S",cnt) + ")&nbsp;";
		}else{
			Element.hide($('img-def-dict'));	//icon
			$('def-dic-count').innerHTML = "&nbsp;("+Const.Label.DictNoSelect+")&nbsp;";
		}
	},
	__util: function(pair) {
		var langs = pair.split('2');
		var src = this._langridServiceInformations['_sourceLanguageArray'][langs[0]];
		var tgt = this._langridServiceInformations['_sourceLanguageArray'][langs[1]];
		return src + '-->' + tgt;
	},
	_langridServiceInformations: {}
}
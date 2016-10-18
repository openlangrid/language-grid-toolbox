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


var TranslationPathPanel = Class.create();
TranslationPathPanel.prototype = {
	_CONST_FLOW_OPS: {"both":"&lt;&gt;","left":"&gt;"},
	_rootPanel: null,
	_addPanel: null,
	_PathArray: null,
	_selfPanel: null,
	elementsIds: {},
	_selectBoxElements: null,
	_checkBoxElements: null,
	_langridServiceInformations: null,
	_servicePanelElements: null,
	_blockPanelArray: null,
	_PanelMode: null,
	_preData: null,

	_default_Settings: null,
	_primaryId: null,
	_isDelete: null,
	_openedEdit: null,
	_saveButton: null,
	viewData: null,
	FirstLang: null,
	LastLang: null,
	flow: null,
	_global_dicts: null,
	_local_dicts: null,
	_temp_dicts: null,
	_dict_flags: null,
	_morphemes_from: null,
	_morphemes_to: null,

	initialize: function() {
		this._global_dicts = new Array(Array(),Array(),Array());
		this._local_dicts = new Array(Array(),Array(),Array());
		this._temp_dicts = new Array(Array(),Array(),Array());
		this._dict_flags = new Array(Array(),Array(),Array());
		this._morphemes_from = new Array('','','');
		this._morphemes_to = new Array('','','');
	},
	errorView: function(error) {
		if (error) {
			Element.removeClassName($(this.elementsIds.panel+":message_box"),"div-message-box");
			Element.addClassName($(this.elementsIds.panel+":message_box"),"div-message-box-alart");
		} else {
			Element.removeClassName($(this.elementsIds.panel+":message_box"),"div-message-box-alart");
			Element.addClassName($(this.elementsIds.panel+":message_box"),"div-message-box");
		}
	},
	showMessageBox: function(msg){
		$(this.elementsIds.panel+":message_box").innerHTML = msg;
		Element.show($(this.elementsIds.panel+":message"));
	},
	makeTranslationPathPanel: function(idx, rootPanel, lgServiceArray,PathArray,defaultSetting, activeSetting) {
		this._rootPanel = rootPanel;
		this._PathArray = PathArray;
		this._default_Settings = defaultSetting;
		this._langridServiceInformations = lgServiceArray;
		this._isDelete = '0';
		this._initializeElementIds(idx);
		this._PanelMode = "edit";
		var data = this._initializeSettingData(activeSetting);
		this._preData = data;
		this._initEvent(data);
	},
	makeTranslationPathPanel_A: function(idx, rootPanel, lgServiceArray,PathArray,defaultSetting, activeSetting) {
		this._rootPanel = rootPanel;
		this._PathArray = PathArray;
		this._default_Settings = defaultSetting;
		this._langridServiceInformations = lgServiceArray;
		this._isDelete = '0';
		this._initializeElementIds(idx);
		this._PanelMode = "edit";
		var data = this._initializeSettingData(activeSetting);
		this._preData = data;
		this._initEvent(data);
	},
	makeNewTranslationPathPanel: function(rootPanel,addPanel,lgServiceArray,PathArray,defaultSetting) {
		this._rootPanel = rootPanel;
		this._addPanel = addPanel;
		this._PathArray = PathArray;
		this._default_Settings = defaultSetting;
		this._langridServiceInformations = lgServiceArray;
		this._isDelete = '0';
		this._initializeElementIds("N");
		this._PanelMode = "new";
		var data = this._initializeSettingData(null);

		for(var i=0;i<=2;i++){
			data.dict_flags[i] = 1;		//use default
			data.global_dicts[i] = defaultSetting.global.join(",");
			data.local_dicts[i] = defaultSetting.local.join(",");
			data.temp_dicts[i] = defaultSetting.temp.join(",");

			this._dict_flags[i] = 1;
			this._global_dicts[i] = this._copyArrayValue(defaultSetting.global);
			this._local_dicts[i] = this._copyArrayValue(defaultSetting.local);
			this._temp_dicts[i] = this._copyArrayValue(defaultSetting.temp);
		}

		this._preData = data;
		this._initNewEvent(data);
	},

	_initializeElementIds: function(idx) {
		this.IndexNumber = idx;
		this.elementsIds = {
			'panel':'panel-' + idx,
			'view' :'panel-' + idx + ':view',
			'edit' :'panel-' + idx + ':edit',
			'lang1':'panel-' + idx + ':lang1',
			'lang2':'panel-' + idx + ':lang2',
			'lang3':'panel-' + idx + ':lang3',
			'lang4':'panel-' + idx + ':lang4',
			'flow1':'panel-' + idx + ':flow1',
			'flow2':'panel-' + idx + ':flow2',
			'flow3':'panel-' + idx + ':flow3'
		};
		this._selectBoxElements = {
			lang1: null,
			lang2: null,
			lang3: null,
			lang4: null,
			flow1: null,
			flow2: null,
			flow3: null
		};
		this._checkBoxElements = {
			checkbox1: null,
			checkbox2: null
		};
	},

	_initializeSettingData: function(setting) {
		//lang1: 'en',
		//lang2: 'ja',

		var data = {
			lang1: '',
			lang2: '',
			lang3: '',
			lang4: '',
			service1: '',
			service2: '',
			service3: '',
			flow: '',
			global_dicts: Array('','',''),
			local_dicts: Array('','',''),
			temp_dicts: Array('','',''),
			morph_from:Array('','',''),
			morph_to:Array('','',''),
			dict_flags:Array('','','')
		};
		if (setting == null) {
			this._primaryId = '';
			return data;
		}

		var AnalyzeSvc = new Array('','','','');
		if(setting.source_lang){
			if (setting.translator_service_3) {
				data.lang1 = setting.source_lang;
				data.lang2 = setting.inter_lang_1;
				data.lang3 = setting.inter_lang_2;
				data.lang4 = setting.target_lang;
				data.service1 = setting.translator_service_1;
				data.service2 = setting.translator_service_2;
				data.service3 = setting.translator_service_3;

				AnalyzeSvc[0] = this._getValidAnalyzer(setting.morph_analyzer1,setting.lang1);
				AnalyzeSvc[1] = this._getValidAnalyzer(setting.morph_analyzer2,setting.lang2);
				AnalyzeSvc[2] = this._getValidAnalyzer(setting.morph_analyzer3,setting.lang3);
				AnalyzeSvc[3] = this._getValidAnalyzer(setting.morph_analyzer4,setting.lang4);
			} else if (setting.translator_service_2) {
				data.lang1 = setting.source_lang;
				data.lang2 = setting.inter_lang_1;
				data.lang3 = setting.target_lang;
				data.service1 = setting.translator_service_1;
				data.service2 = setting.translator_service_2;

				AnalyzeSvc[0] = this._getValidAnalyzer(setting.morph_analyzer1,setting.lang1);
				AnalyzeSvc[1] = this._getValidAnalyzer(setting.morph_analyzer2,setting.lang2);
				AnalyzeSvc[2] = this._getValidAnalyzer(setting.morph_analyzer3,setting.lang3);
			} else {
				data.lang1 = setting.source_lang;
				data.lang2 = setting.target_lang;
				data.service1 = setting.translator_service_1;

				AnalyzeSvc[0] = this._getValidAnalyzer(setting.morph_analyzer1,setting.lang1);
				AnalyzeSvc[1] = this._getValidAnalyzer(setting.morph_analyzer2,setting.lang2);
			}
			data.flow = setting.flow;
		}else if(setting.lang1){		//use save data
			if (setting.service3) {
				data.lang1 = setting.lang1;
				data.lang2 = setting.lang2;
				data.lang3 = setting.lang3;
				data.lang4 = setting.lang4;
				data.service1 = setting.service1;
				data.service2 = setting.service2;
				data.service3 = setting.service3;

				AnalyzeSvc[0] = this._getValidAnalyzer(setting.morph_analyzer1,setting.lang1);
				AnalyzeSvc[1] = this._getValidAnalyzer(setting.morph_analyzer2,setting.lang2);
				AnalyzeSvc[2] = this._getValidAnalyzer(setting.morph_analyzer3,setting.lang3);
				AnalyzeSvc[3] = this._getValidAnalyzer(setting.morph_analyzer4,setting.lang4);
			} else if (setting.service2) {
				data.lang1 = setting.lang1;
				data.lang2 = setting.lang2;
				data.lang3 = setting.lang3;
				data.service1 = setting.service1;
				data.service2 = setting.service2;

				AnalyzeSvc[0] = this._getValidAnalyzer(setting.morph_analyzer1,setting.lang1);
				AnalyzeSvc[1] = this._getValidAnalyzer(setting.morph_analyzer2,setting.lang2);
				AnalyzeSvc[2] = this._getValidAnalyzer(setting.morph_analyzer3,setting.lang3);
			} else {
				data.lang1 = setting.lang1;
				data.lang2 = setting.lang2;
				data.service1 = setting.service1;

				AnalyzeSvc[0] = this._getValidAnalyzer(setting.morph_analyzer1,setting.lang1);
				AnalyzeSvc[1] = this._getValidAnalyzer(setting.morph_analyzer2,setting.lang2);
			}
			data.flow = setting.flow;
		}

		slist = $H(setting);
		for(var i=0;i<=2;i++){
			if(slist.keys().indexOf('global_dict_'+String(i+1)) > -1){
				if(slist.get('global_dict_'+String(i+1)) != null){
					data.global_dicts[i] = slist.get('global_dict_'+String(i+1));
				}
			}
			if(slist.keys().indexOf('local_dict_'+String(i+1)) > -1){
				if(slist.get('local_dict_'+String(i+1)) != null){
					data.local_dicts[i] = slist.get('local_dict_'+String(i+1));
				}
			}
			if(slist.keys().indexOf('temp_dict_'+String(i+1)) > -1){
				if(slist.get('temp_dict_'+String(i+1)) != null){
					data.temp_dicts[i] = slist.get('temp_dict_'+String(i+1));
				}
			}
			if(slist.keys().indexOf('dict_flag_'+String(i+1)) > -1){
				if(slist.get('dict_flag_'+String(i+1)) != null){
					data.dict_flags[i] = slist.get('dict_flag_'+String(i+1));
				}
			}

			data.morph_from[i] = AnalyzeSvc[i];
			data.morph_to[i] = AnalyzeSvc[i+1];
		}

		if(setting['id']){
			if (String(setting['id']).substr(0, 1) == '-') {
				this._primaryId = '';
			} else {
				this._primaryId = setting['id'];
			}
		}else{
			this._primaryId = '';
		}

		for(var i=0;i<=2;i++){
			this._global_dicts[i] = this._explode(data.global_dicts[i],",");
			this._local_dicts[i] = this._explode(data.local_dicts[i],",");
			this._temp_dicts[i] = this._explode(data.temp_dicts[i],",");
			this._morphemes_from[i] = data.morph_from[i];
			this._morphemes_to[i] = data.morph_to[i];
			this._dict_flags[i] = data.dict_flags[i];
		}

		return data;
	},
	_initEvent: function(data) {
		var selfPanel = document.createElement('div');
		selfPanel.id = this.elementsIds.panel;
		Element.addClassName(selfPanel,"div-path-panel");

		var viewPanel = this._createViewPanel(data);
		//var editPanel = this._createEditPanel(data);

		selfPanel.appendChild(this._createMessageArea());
		selfPanel.appendChild(viewPanel);
		//selfPanel.appendChild(editPanel);

		this._rootPanel.appendChild(selfPanel);
		this._selfPanel = selfPanel;

		//this.updateEditElement();
	},
	_initNewEvent: function(data) {
		var selfPanel = document.createElement('div');
		selfPanel.id = this.elementsIds.panel;
		Element.addClassName(selfPanel,"div-path-panel");
		Element.addClassName(selfPanel,"nowedit");

		selfPanel.appendChild(this._createMessageArea());
		var editPanel = this._createEditPanel(data);
		selfPanel.appendChild(editPanel);

		this._addPanel.appendChild(selfPanel);
		this._selfPanel = selfPanel;

		this.updateEditElement();
	},
	_createMessageArea:function(){
		var MessagePanel = document.createElement('div');
		MessagePanel.id = this.elementsIds.panel+":message";
		MessagePanel.className = 'div-message-area';

		var MessageBox = document.createElement('div');
		MessageBox.className = 'div-message-box';
		MessageBox.id = this.elementsIds.panel+":message_box";
		MessageBox.innerHTML = "";

		MessagePanel.appendChild(MessageBox);

		Element.hide(MessagePanel);
		return MessagePanel;
	},
	_createViewPanel: function(data) {
		this.viewData = data;

		var ViewPanel = document.createElement('div');
		ViewPanel.id = this.elementsIds.view;
		ViewPanel.className = 'trans-path';

		var lang1 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],data.lang1);
		var lang2 = this.getdataArrayValue(this._langridServiceInformations['_targetLanguageArray'],data.lang2);
		var lang3 = this.getdataArrayValue(this._langridServiceInformations['_targetLanguageArray'],data.lang3);
		var lang4 = this.getdataArrayValue(this._langridServiceInformations['_targetLanguageArray'],data.lang4);
		var flow = this.getdataArrayValue(this._CONST_FLOW_OPS,data.flow);
		var service1 = this.getSurviceNameFromArray(this._langridServiceInformations['_translationServicesArray'],data.service1);
		var service2 = this.getSurviceNameFromArray(this._langridServiceInformations['_translationServicesArray'],data.service2);
		var service3 = this.getSurviceNameFromArray(this._langridServiceInformations['_translationServicesArray'],data.service3);

		var table = document.createElement('table');
		table.style.border = 'none';
		var tbody = document.createElement('tbody');
		var tr = new Array(document.createElement('tr'),document.createElement('tr'));

		tr[0].appendChild(this.makeTdObj("",this.makeDivObj(lang1,"lang-cell,lang-cell-strong")));
		tr[0].appendChild(this.makeTdObj("",this.makeDivObj(flow,"allow-cell")));

		var td =  document.createElement('td');
		var div1 = document.createElement('div');
		div1.className = 'div-dictionary-area';

		var dict_btn = new Array();
		for(var i=0;i<=2;i++){
			dict_btn[i] = document.createElement('div');
			dict_btn[i].id = this.elementsIds.panel + ':viewdictimg_'+i;
			if(data.dict_flags[i] == 2){
				Element.addClassName(dict_btn[i],"btn-user-dic");
				dict_btn[i].innerHTML = Const.Label.Custom;
				dict_btn[i].setAttribute('alt', Const.Label.Custom);
				dict_btn[i].setAttribute('title', Const.Label.Custom);
				dict_btn[i].show();
			}else {
				Element.addClassName(dict_btn[i],"btn-def-dic");
				dict_btn[i].innerHTML = Const.Label.Default;
				dict_btn[i].setAttribute('alt', Const.Label.Default);
				dict_btn[i].setAttribute('title', Const.Label.Default);
				dict_btn[i].show();
				if(data.dict_flags[i] == 0){
					dict_btn[i].hide();
				}
				if(this._default_Settings.global.length == 0
				  && this._default_Settings.local.length == 0
				  && this._default_Settings.temp.length == 0){
					dict_btn[i].hide();
				}
			}
			$(dict_btn[i]).observe('click', this._onDictionaryViewClicked.bind(this));
		}

		/*
		var dict_btn = document.createElement('div');
		dict_btn.id = this.elementsIds.panel + ':viewdictimg';
		if(this._DictFlag == 2){
			Element.addClassName(dict_btn,"btn-user-dic");
			dict_btn.innerHTML = Const.Label.Custom;
			dict_btn.setAttribute('alt', Const.Label.Custom);
			dict_btn.setAttribute('title', Const.Label.Custom);
			dict_btn.show();
		}else {
			Element.addClassName(dict_btn,"btn-def-dic");
			dict_btn.innerHTML = Const.Label.Default;
			dict_btn.setAttribute('alt', Const.Label.Default);
			dict_btn.setAttribute('title', Const.Label.Default);
			dict_btn.show();
			if(this._DictFlag == 0){
				dict_btn.hide();
			}
			if(this._default_global_dicts.length == 0 && this._default_temp_dicts.length == 0){
				dict_btn.hide();
			}
		}
		$(dict_btn).observe('click', this._onDictionaryViewClicked.bind(this));
		*/

		//tr[1].appendChild(this.makeTdObj("",dict_btn));
		tr[1].appendChild(this.makeTdObj("&nbsp;"));
		tr[1].appendChild(this.makeTdObj("&nbsp;"));
		tr[1].appendChild(this.makeTdObj("",this.makeServiceViewObj(service1,dict_btn[0])));

		this.FirstLang = data.lang1;
		if(lang3 != ""){
			tr[0].appendChild(this.makeTdObj("",this.makeDivObj(lang2,"lang-cell")));
			tr[0].appendChild(this.makeTdObj("",this.makeDivObj(flow,"allow-cell")));
			tr[1].appendChild(this.makeTdObj("&nbsp;"));
			tr[1].appendChild(this.makeTdObj("",this.makeServiceViewObj(service2,dict_btn[1])));
			if(lang4 != ""){
				this.LastLang = data.lang4;
				tr[0].appendChild(this.makeTdObj("",this.makeDivObj(lang3,"lang-cell")));
				tr[0].appendChild(this.makeTdObj("",this.makeDivObj(flow,"allow-cell")));
				tr[0].appendChild(this.makeTdObj("",this.makeDivObj(lang4,"lang-cell,lang-cell-strong")));
				tr[1].appendChild(this.makeTdObj("&nbsp;"));
				tr[1].appendChild(this.makeTdObj("",this.makeServiceViewObj(service3,dict_btn[2])));
			}else{
				this.LastLang = data.lang3;
				tr[0].appendChild(this.makeTdObj("",this.makeDivObj(lang3,"lang-cell,lang-cell-strong")));
			}
		}else{
			this.LastLang = data.lang2;
			tr[0].appendChild(this.makeTdObj("",this.makeDivObj(lang2,"lang-cell,lang-cell-strong")));
		}
		this.flow = data.flow;

		tbody.appendChild(tr[0]);
		tbody.appendChild(tr[1]);
		table.appendChild(tbody);
		ViewPanel.appendChild(table);

		var edit_area = document.createElement('div');
		edit_area.className = 'edit-area';
		var edit_btn = document.createElement('div');
		edit_btn.className = 'btn-edit';
		//$( edit_btn).observe('click', this._onEditButtonClicked.bind(this));
		edit_btn.innerHTML = "edit";

		$(ViewPanel).observe('click', this._onEditButtonClicked.bind(this));

		edit_area.appendChild(edit_btn);
		ViewPanel.appendChild(edit_area);

		return ViewPanel;
	},
	_createEditPanel: function(data) {
		this._servicePanelElements = new Array();
		this._blockPanelArray = new Array();

		var EditPanel = document.createElement('div');
		EditPanel.id = this.elementsIds.edit;
		EditPanel.className = 'trans-path-edit';
		if(this._PanelMode != "new"){
			Element.hide(EditPanel);
		}

		var firstPanel = this._createFirstBlockPanel(data);
		var secondPanel = this._createSecondBlockPanel(data);
		var thirdPanel = this._createThirdBlockPanel(data);

		EditPanel.appendChild(firstPanel);
		EditPanel.appendChild(secondPanel);
		EditPanel.appendChild(thirdPanel);

		this._blockPanelArray.push(firstPanel);
		this._blockPanelArray.push(secondPanel);
		this._blockPanelArray.push(thirdPanel);

		var br = document.createElement('br');
		br.style.clear = 'both';
		EditPanel.appendChild(br);

		var ctrlPanel = this._createControllPanel();
		EditPanel.appendChild(ctrlPanel);

		return EditPanel;
	},
	_createFirstBlockPanel: function(data) {
		var blockPanel = document.createElement('div');
		blockPanel.id = this.elementsIds.panel + ':block-0';
		blockPanel.className = 'lang_block';

		var lang1Elem = this.__createSelectBoxElem(this.elementsIds.lang1,
			this._langridServiceInformations['_sourceLanguageArray'], data.lang1, this._onLanguageChanged, true);

		var flow1Elem = this.__createSelectBoxElem(this.elementsIds.flow1, this._CONST_FLOW_OPS, data.flow, this._onLanguageChanged, false);

		var lang2Elem = this.__createSelectBoxElem(this.elementsIds.lang2,
			this._langridServiceInformations['_targetLanguageArray'], data.lang2, this._onLanguageChanged, true);

		var table = document.createElement('table');
		table.style.border = 'none';
		var tbody = document.createElement('tbody');
		var tr = new Array(document.createElement('tr'),document.createElement('tr'));

		var lang1div = document.createElement('div');
		Element.addClassName(lang1div, 'lang-cell');
		lang1div.appendChild(lang1Elem);
		var lang2div = document.createElement('div');
		Element.addClassName(lang2div, 'lang-cell');
		lang2div.appendChild(lang2Elem);

		tr[0].appendChild(this.makeTdObj("",lang1div));
		tr[0].appendChild(this.makeTdObj("",flow1Elem));
		tr[0].appendChild(this.makeTdObj("",lang2div));

		tr[1].appendChild(this.makeTdObj("&nbsp;"));
		tr[1].appendChild(this.makeTdObj("&nbsp;"));
		var td = this.makeTdObj("");
		var servicePanel = new ServicePanel(this._langridServiceInformations['_targetLanguageArray']);
		servicePanel.makeServicePanel(td,blockPanel.id, this._langridServiceInformations['_translationServicesArray'], 'translator',lang1Elem, lang2Elem, flow1Elem);
		servicePanel.setLanguageSelectors(lang1Elem, lang2Elem, flow1Elem);
		servicePanel.setActiveServiceId(data.service1);
		//*****Advanced options***********
		var dictdiv = document.createElement('div');
		dictdiv.id = this.elementsIds.panel + ':adv_opt_area_0';
		Element.addClassName(dictdiv, 'advanced-option-area');

		var dic = document.createElement('a');
		dic.id = this.elementsIds.panel + ':adv_opt_0';
		Element.addClassName(dic, 'advanced-option');
		dic.appendChild(document.createTextNode(Const.Label.Advanced));

		var img = document.createElement('img');
		img.id = this.elementsIds.panel + ':dictimg_0';
		img.setAttribute('src','./images/icon/icn_default_dictionary.gif');
		dic.appendChild(img);

		$(dic).observe('click', this._onDictionaryButtonClicked.bind(this));
		dictdiv.appendChild(dic);
		td.appendChild(dictdiv);
		//*****Advanced options(end)***********
		tr[1].appendChild(td);

		this._selectBoxElements.lang1 = lang1Elem;
		this._selectBoxElements.lang2 = lang2Elem;
		this._selectBoxElements.flow1 = flow1Elem;


		var ctrl = this;
		this._selectBoxElements.flow1.observe('change', function(ev){
			var me = Event.element(ev);
			$(ctrl._selectBoxElements.flow2).value = me.value;
			$(ctrl._selectBoxElements.flow3).value = me.value;
			this._onLanguageChanged();
		}.bind(this));

		var chk_td = document.createElement('td');
		var plus_btn = document.createElement('a');
		plus_btn.id = this.elementsIds.panel+":plus1";
		Element.addClassName(plus_btn, 'btn btn-mk');
		plus_btn.innerHTML = '<img src="./images/icon/icn_plus.gif"/>';
		plus_btn.hide();
		chk_td.appendChild(plus_btn);
		Event.observe(plus_btn,'click',function(ev){
			this._checkBoxElements.checkbox1.checked = true;
			this._blockPanelArray[1].show();

			$(this.elementsIds.panel+":flow_div_2").innerHTML = this.getdataArrayValue(this._CONST_FLOW_OPS,$(this._selectBoxElements.flow1).value);
			$(this.elementsIds.panel+":flow_div_2").show();
			$(this.elementsIds.panel+":plus1").hide();
			$(this.elementsIds.panel+":minus1").show();

			this._onLanguageChanged();
			document.fire('dom:settingEditingThis');
		}.bind(this));


		var flow_div_2 = document.createElement('div');
		flow_div_2.id = this.elementsIds.panel+":flow_div_2";
		Element.addClassName(flow_div_2, 'allow-cell');
		flow_div_2.innerHTML = this.getdataArrayValue(this._CONST_FLOW_OPS,data.flow);
		chk_td.appendChild( flow_div_2);
		//--plus button
		var checkboxElem = servicePanel.makeAndBindTranslationCombinationCheckbox(this, function(ev, item) {
			if (!servicePanel.getSetting()) {
				alert(Const.Message.NoSelectedTranslator);
				item.checked = false;
				return false;
			}
			if(item.checked) {
				this._blockPanelArray[1].show();
			} else {
				this._checkBoxElements.checkbox2.checked = false;

				this._blockPanelArray[1].hide();
				this._servicePanelElements[1].clearSelection();
				this._selectBoxElements.lang3.value = '';

				this._blockPanelArray[2].hide();
				this._servicePanelElements[2].clearSelection();
				this._selectBoxElements.lang4.value = '';
			}
		});
		$(checkboxElem).checked = true;
		$(checkboxElem).hide();
		this._checkBoxElements.checkbox1 = $(checkboxElem);

		this._servicePanelElements.push(servicePanel);
		chk_td.appendChild(checkboxElem);

		tr[0].appendChild(chk_td);
		tr[1].appendChild(this.makeTdObj(""));

		tbody.appendChild(tr[0]);
		tbody.appendChild(tr[1]);
		table.appendChild(tbody);
		blockPanel.appendChild(table);

		if (data.service2) {
			this._checkBoxElements.checkbox1.checked = true;
		}else{
			this._checkBoxElements.checkbox1.checked = false;
		}

		return blockPanel;
	},

	_createSecondBlockPanel: function(data) {
		var blockPanel = document.createElement('div');
		blockPanel.id = this.elementsIds.panel + ':block-1';
		blockPanel.className = 'lang_block';


		var flow2Elem = this.__createSelectBoxElem(this.elementsIds.flow2, this._CONST_FLOW_OPS, data.flow, this._onLanguageChanged, false);
		flow2Elem.setAttribute('disabled', 'disabled');
		flow2Elem.style.backgroundColor = '#fff';
		flow2Elem.style.color = '#000';
		flow2Elem.hide();

		var lang3Elem = this.__createSelectBoxElem(this.elementsIds.lang3,
			this._langridServiceInformations['_targetLanguageArray'], data.lang3, this._onLanguageChanged, true);

		var table = document.createElement('table');
		table.style.border = 'none';
		var tbody = document.createElement('tbody');
		var tr = new Array(document.createElement('tr'),document.createElement('tr'));

		var lang3div = document.createElement('div');
		Element.addClassName(lang3div, 'lang-cell');
		lang3div.appendChild(lang3Elem);

		var flow_td = document.createElement('td');
		flow_td.appendChild(flow2Elem);

		//--minus button
		var minus_btn = document.createElement('a');
		minus_btn.id = this.elementsIds.panel+":minus1";
		Element.addClassName(minus_btn, 'btn btn-mk');
		minus_btn.innerHTML = '<img src="./images/icon/icn_minus.gif"/>';
		flow_td.appendChild(minus_btn);
		Event.observe(minus_btn,'click',function(ev){
			if(!Element.visible(this._blockPanelArray[2])){
				this._checkBoxElements.checkbox1.checked = false;
				this._checkBoxElements.checkbox2.checked = false;

				this._blockPanelArray[1].hide();
				this._servicePanelElements[1].clearSelection();
				this._selectBoxElements.lang3.value = '';

				this._blockPanelArray[2].hide();
				this._servicePanelElements[2].clearSelection();
				this._selectBoxElements.lang4.value = '';

				$(this.elementsIds.panel+":flow_div_3").hide();
				$(this.elementsIds.panel+":flow_div_2").hide();
				$(this.elementsIds.panel+":plus1").show();
				$(this.elementsIds.panel+":minus1").hide();
				this._onLanguageChanged();
				document.fire('dom:settingEditingThis');
			}
		}.bind(this));

		tr[0].appendChild(flow_td);
		tr[0].appendChild(this.makeTdObj("",lang3div));

		this._selectBoxElements.lang3 = lang3Elem;
		this._selectBoxElements.flow2 = flow2Elem;


		tr[1].appendChild(this.makeTdObj("&nbsp;"));
		var td = this.makeTdObj("");
		var servicePanel = new ServicePanel(this._langridServiceInformations['_targetLanguageArray']);
		servicePanel.makeServicePanel(td, blockPanel.id,this._langridServiceInformations['_translationServicesArray'], 'translator',this._selectBoxElements.lang2, lang3Elem, this._selectBoxElements.flow1);
		servicePanel.setLanguageSelectors(this._selectBoxElements.lang2, lang3Elem, this._selectBoxElements.flow1);
		servicePanel.setActiveServiceId(data.service2);
		//*****Advanced options***********
		var dictdiv = document.createElement('div');
		dictdiv.id = this.elementsIds.panel + ':adv_opt_area_1';
		Element.addClassName(dictdiv, 'advanced-option-area');

		var dic = document.createElement('a');
		dic.id = this.elementsIds.panel + ':adv_opt_1';
		Element.addClassName(dic, 'advanced-option');
		dic.appendChild(document.createTextNode(Const.Label.Advanced));

		var img = document.createElement('img');
		img.id = this.elementsIds.panel + ':dictimg_1';
		img.setAttribute('src','./images/icon/icn_default_dictionary.gif');
		dic.appendChild(img);

		$(dic).observe('click', this._onDictionaryButtonClicked.bind(this));
		dictdiv.appendChild(dic);
		td.appendChild(dictdiv);
		//*****Advanced options(end)***********
		tr[1].appendChild(td);


		var chk_td = document.createElement('td');
		var plus_btn = document.createElement('a');
		plus_btn.id = this.elementsIds.panel+":plus2";
		Element.addClassName(plus_btn, 'btn btn-mk');
		plus_btn.innerHTML = '<img src="./images/icon/icn_plus.gif"/>';
		plus_btn.hide();
		chk_td.appendChild(plus_btn);
		Event.observe(plus_btn,'click',function(ev){
			this._checkBoxElements.checkbox2.checked = true;
			this._blockPanelArray[2].show();

			$(this.elementsIds.panel+":flow_div_3").innerHTML = this.getdataArrayValue(this._CONST_FLOW_OPS,$(this._selectBoxElements.flow1).value);
			$(this.elementsIds.panel+":flow_div_3").show();
			$(this.elementsIds.panel+":plus2").hide();
			$(this.elementsIds.panel+":minus2").show();

			this._onLanguageChanged();
			document.fire('dom:settingEditingThis');
		}.bind(this));

		var flow_div = document.createElement('div');
		flow_div.id = this.elementsIds.panel+":flow_div_3";
		Element.addClassName(flow_div, 'allow-cell');
		flow_div.innerHTML = this.getdataArrayValue(this._CONST_FLOW_OPS,data.flow);
		chk_td.appendChild(flow_div);

		var checkboxElem = servicePanel.makeAndBindTranslationCombinationCheckbox(this, function(ev, item) {
			if (!servicePanel.getSetting()) {
				alert(Const.Message.NoSelectedTranslator);
				item.checked = false;
				return false;
			}
			if(item.checked) {
				this._blockPanelArray[2].show();
			} else {
				this._blockPanelArray[2].hide();
				this._servicePanelElements[2].clearSelection();
				this._selectBoxElements.lang4.value = '';
			}
		});
		$(checkboxElem).hide();

		this._checkBoxElements.checkbox2 = $(checkboxElem);

		this._servicePanelElements.push(servicePanel);
		chk_td.appendChild(checkboxElem);

		tr[0].appendChild(chk_td);
		tr[1].appendChild(this.makeTdObj(""));

		tbody.appendChild(tr[0]);
		tbody.appendChild(tr[1]);
		table.appendChild(tbody);
		blockPanel.appendChild(table);

		if (data.service3) {
			this._checkBoxElements.checkbox2.checked = true;
		}else{
			this._checkBoxElements.checkbox2.checked = false;
		}

		return blockPanel;
	},
	_createThirdBlockPanel: function(data) {
		var blockPanel = document.createElement('div');
		blockPanel.id = this.elementsIds.panel + ':block-2';
		blockPanel.className = 'lang_block';

		var flow3Elem = this.__createSelectBoxElem(this.elementsIds.flow3, this._CONST_FLOW_OPS, data.flow, this._onLanguageChanged, false);
		flow3Elem.setAttribute('disabled', 'disabled');
		flow3Elem.style.backgroundColor = '#fff';
		flow3Elem.style.color = '#000';
		flow3Elem.hide();

		var lang4Elem = this.__createSelectBoxElem(this.elementsIds.lang4,
			this._langridServiceInformations['_targetLanguageArray'], data.lang4, this._onLanguageChanged, true);

		var table = document.createElement('table');
		table.style.border = 'none';
		var tbody = document.createElement('tbody');
		var tr = new Array(document.createElement('tr'),document.createElement('tr'));

		var lang4div = document.createElement('div');
		Element.addClassName(lang4div, 'lang-cell');
		lang4div.appendChild(lang4Elem);

		var flow_td = document.createElement('td');
		flow_td.appendChild(flow3Elem);

		var minus_btn = document.createElement('a');
		minus_btn.id = this.elementsIds.panel+":minus2";
		Element.addClassName(minus_btn, 'btn btn-mk');
		minus_btn.innerHTML = '<img src="./images/icon/icn_minus.gif"/>';
		flow_td.appendChild(minus_btn);
		Event.observe(minus_btn,'click',function(ev){
			this._checkBoxElements.checkbox2.checked = false;

			this._blockPanelArray[2].hide();
			this._servicePanelElements[2].clearSelection();
			this._selectBoxElements.lang4.value = '';


			$(this.elementsIds.panel+":flow_div_3").hide();
			$(this.elementsIds.panel+":plus2").show();
			$(this.elementsIds.panel+":minus2").hide();
			this._onLanguageChanged();
			document.fire('dom:settingEditingThis');
		}.bind(this));

		tr[0].appendChild(flow_td);
		tr[0].appendChild(this.makeTdObj("",lang4div));

		this._selectBoxElements.lang4 = lang4Elem;
		this._selectBoxElements.flow3 = flow3Elem;

		tr[1].appendChild(this.makeTdObj("&nbsp;"));
		var td = this.makeTdObj("");
		var servicePanel = new ServicePanel(this._langridServiceInformations['_targetLanguageArray']);
		servicePanel.makeServicePanel(td, blockPanel.id, this._langridServiceInformations['_translationServicesArray'], 'translator',this._selectBoxElements.lang3, lang4Elem, this._selectBoxElements.flow1);
		servicePanel.setLanguageSelectors(this._selectBoxElements.lang3, lang4Elem, this._selectBoxElements.flow1);
		servicePanel.setActiveServiceId(data.service3);
		//*****Advanced options***********
		var dictdiv = document.createElement('div');
		dictdiv.id = this.elementsIds.panel + ':adv_opt_area_2';
		Element.addClassName(dictdiv, 'advanced-option-area');

		var dic = document.createElement('a');
		dic.id = this.elementsIds.panel + ':adv_opt_2';
		Element.addClassName(dic, 'advanced-option');
		dic.appendChild(document.createTextNode(Const.Label.Advanced));

		var img = document.createElement('img');
		img.id = this.elementsIds.panel + ':dictimg_2';
		img.setAttribute('src','./images/icon/icn_default_dictionary.gif');
		dic.appendChild(img);

		$(dic).observe('click', this._onDictionaryButtonClicked.bind(this));
		dictdiv.appendChild(dic);
		td.appendChild(dictdiv);
		//*****Advanced options(end)***********
		tr[1].appendChild(td);

		if (data.service3) {
			$(blockPanel).show();
		} else {
			$(blockPanel).hide();
		}

		this._servicePanelElements.push(servicePanel);

		tbody.appendChild(tr[0]);
		tbody.appendChild(tr[1]);
		table.appendChild(tbody);
		blockPanel.appendChild(table);

		return blockPanel;
	},

	_createControllPanel: function() {
		var blockPanel = document.createElement('div');
		blockPanel.id = this.elementsIds.panel + ':controll';
		Element.addClassName(blockPanel, 'trans-path-edit-area');

		var left_div = document.createElement('div');
		Element.addClassName(left_div, 'left-area');

		//var dic = document.createElement('a');
		//Element.addClassName(dic, 'advanced-option');
		//dic.appendChild(document.createTextNode(Const.Label.Advanced));

		//var img = document.createElement('img');
		//img.id = this.elementsIds.panel + ':dictimg';
		//img.setAttribute('src','./images/icon/icn_default_dictionary.gif');
		//dic.appendChild(img);

		//$(dic).observe('click', this._onDictionaryButtonClicked.bind(this));
		//left_div.appendChild(dic);

		var right_div = document.createElement('div');
		Element.addClassName(right_div, 'right-area');

		if(this._PanelMode != 'new'){
			var del = document.createElement('a');
			del.id = this.elementsIds.panel + ":del";
			Element.addClassName(del, 'btn btn-delete');
			del.innerHTML += '<img src="./images/icon/icn_delete.gif" />'+Const.Label.DeleteButton;
			$(del).observe('click', this._onDeleteButtonClicked.bind(this));
			right_div.appendChild(del);
		}

		var cancel = document.createElement('a');
		cancel.id = this.elementsIds.panel + ":cancel";
		Element.addClassName(cancel, 'btn btn-cancel');
		cancel.innerHTML += '<img src="./images/icon/icn_cancel.gif" />'+Const.Popup.BTN_CANCEL;
		$(cancel).observe('click', this._onCancelButtonClicked.bind(this));
		right_div.appendChild(cancel);

		var save = document.createElement('a');
		save.id = this.elementsIds.panel + ":save";
		Element.addClassName(save, 'btn-disable btn-save');
		var save_img = document.createElement('img');
		save_img.id = this.elementsIds.panel + ":save-img";
		save_img.setAttribute('src','./images/icon/icn_save_disable.gif');
		save.appendChild(save_img);
		if(this._PanelMode != 'new'){
			save.appendChild(document.createTextNode(Const.Label.Save));
		}else{
			save.appendChild(document.createTextNode(Const.Label.AddButton));
		}
		$(save).observe('click', this._onSaveButtonClicked.bind(this));
		this._saveButton = save;
		right_div.appendChild(save);

		blockPanel.appendChild(left_div);
		blockPanel.appendChild(right_div);

		return blockPanel;
	},

	__createSelectBoxElem: function(elemId, dataArray, initval, handler, hasNoVal) {
		var selectElem = document.createElement('select');
		selectElem.id = elemId;
		if (hasNoVal) {
			var defaultOpt = document.createElement('option');
			defaultOpt.setAttribute('value', '');
			defaultOpt.innerHTML = '---';
			selectElem.appendChild(defaultOpt);
		}
		$H(dataArray).each(function(data){
			var opt = document.createElement('option');
			opt.setAttribute('value', data.key);
			opt.innerHTML = data.value;
//			if (data.key == initval) {
//				opt.setAttribute('selected', 'yes');
//			}
			selectElem.appendChild(opt);
		});
		selectElem.value = initval;
		if (handler != null) {
			Event.observe(selectElem, 'change', handler.bind(this));
		}
		return selectElem;
	},

	getdataArrayValue: function(dataArray,dataKey){
		var ret = "";
		var hash = $H(dataArray);
		if(dataKey != "" && hash){
			if(hash.get(dataKey)){
				ret = hash.get(dataKey);
			}
		}
		return ret;
	},
	getSurviceNameFromArray: function(dataArray,dataKey){
		ret = "";
		dataArray.each(function(obj,i){
			if(obj.service_id == dataKey){
				ret = obj.service_name;
			}
		});

		return ret;

	},
	_onLanguageChanged: function(ev) {
		this.updateEditElement();
	},
	updateEditElement:function(){
		try{
			if(isInitializing == false){
				if(this._selfPanel != null && $(this._selectBoxElements.lang3) != null){
					$(this.elementsIds.panel+":plus1").hide();
					$(this.elementsIds.panel+":plus2").hide();
					$(this.elementsIds.panel+":flow_div_2").hide();
					$(this.elementsIds.panel+":flow_div_3").hide();
					$(this.elementsIds.panel+":minus1").hide();
					$(this.elementsIds.panel+":minus2").hide();
					$(this.elementsIds.panel+":adv_opt_area_0").hide();
					$(this.elementsIds.panel+":adv_opt_area_1").hide();
					$(this.elementsIds.panel+":adv_opt_area_2").hide();
					$(this._blockPanelArray[1]).hide();
					$(this._blockPanelArray[2]).hide();
					$(this._selectBoxElements.lang1).removeAttribute('disabled');
					$(this._selectBoxElements.lang2).removeAttribute('disabled');
					$(this._selectBoxElements.lang3).removeAttribute('disabled');
					$(this._selectBoxElements.flow1).removeAttribute('disabled');

					if($(this._selectBoxElements.lang1).value == ""){
						$(this._selectBoxElements.lang2).value = "";
						$(this._selectBoxElements.lang2).setAttribute('disabled', 'disabled');
						this._servicePanelElements[0].disabled = false;
					}else{
						if($(this._selectBoxElements.lang2).value != ""){
							$(this.elementsIds.panel+":adv_opt_area_0").show();
						}

						if(this._servicePanelElements[0].getSetting()){	//--service1_on
							if(this._checkBoxElements.checkbox1.checked){
								$(this._selectBoxElements.lang1).setAttribute('disabled', 'disabled');
								$(this._selectBoxElements.flow1).setAttribute('disabled', 'disabled');
								$(this._selectBoxElements.lang2).setAttribute('disabled', 'disabled');
								this._servicePanelElements[0].disabled = true;

								$(this.elementsIds.panel+":minus1").show();
								$(this.elementsIds.panel+":flow_div_2").show();
								this._blockPanelArray[1].show();
							}else{
								$(this._selectBoxElements.lang1).removeAttribute('disabled');
								$(this._selectBoxElements.flow1).removeAttribute('disabled');
								$(this._selectBoxElements.lang2).removeAttribute('disabled');
								this._servicePanelElements[0].disabled = false;
								if($(this._selectBoxElements.lang2).value != ""){
									$(this.elementsIds.panel+":plus1").show();
								}
								$(this._blockPanelArray[1]).hide();
							}
							if($(this._selectBoxElements.lang3).value != ""){
								$(this.elementsIds.panel+":adv_opt_area_1").show();
							}
							if(this._servicePanelElements[1].getSetting()){	//--service2_on
								if(this._checkBoxElements.checkbox2.checked){
									$(this.elementsIds.panel+":minus2").show();
									Element.removeClassName($(this.elementsIds.panel+":minus1"),"btn");
									Element.addClassName($(this.elementsIds.panel+":minus1"),"btn-disable");
									Element.removeClassName($(this.elementsIds.panel+":minus1"),"btn-mk");
									Element.addClassName($(this.elementsIds.panel+":minus1"),"btn-mk-disable");
									$(this.elementsIds.panel+":flow_div_3").show();
									$(this._selectBoxElements.lang3).setAttribute('disabled', 'disabled');
									this._servicePanelElements[1].disabled = true;
									this._blockPanelArray[2].show();
								}else{
									if($(this._selectBoxElements.lang3).value != ""){
										$(this.elementsIds.panel+":plus2").show();
										Element.removeClassName($(this.elementsIds.panel+":minus1"),"btn-disable");
										Element.addClassName($(this.elementsIds.panel+":minus1"),"btn");
										Element.removeClassName($(this.elementsIds.panel+":minus1"),"btn-mk-disable");
										Element.addClassName($(this.elementsIds.panel+":minus1"),"btn-mk");
									}
									this._servicePanelElements[1].disabled = false;
									$(this._selectBoxElements.lang3).removeAttribute('disabled');
									$(this._blockPanelArray[2]).hide();
								}
								if($(this._selectBoxElements.lang4).value != ""){
									$(this.elementsIds.panel+":adv_opt_area_2").show();
								}
								if(this._servicePanelElements[2].getSetting()){	//--service3_on


								}
							}
						}
					}
				}
			}
		}catch(e){
			alert(e.message);
		}

	},
	_onDeleteButtonClicked: function(ev) {
		var srcLang = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],this.FirstLang);
		var tgtLang = this.getdataArrayValue(this._langridServiceInformations['_targetLanguageArray'],this.LastLang);
		var Flow = this.getdataArrayValue(this._CONST_FLOW_OPS,this._preData.flow);
		var c_message = Const.Message.ConfirmDeleteBBS;
		c_message = c_message.replace("%SRC",srcLang);
		c_message = c_message.replace("%FLW",Flow.unescapeHTML());
		c_message = c_message.replace("%TGT", tgtLang);

		if (confirm(c_message)) {
			this._selfPanel.hide();
			this._isDelete = 'yes';

			var isAll = false;
			$A(this._PathArray).each(function(panelElem, index){
				if(panelElem.elementsIds.panel != "panel-N" && panelElem._primaryId == ''){
					isAll = true;
					throw $break;
				}
			});
			if(isAll){
				this._makeSaveDataAll();
			}else{
				this._makeSaveData(this);
			}
		}
	},
	_onSaveButtonClicked: function(ev) {
		if(!Element.hasClassName(Event.findElement(ev,'a'),'btn-disable')){
			var validMessage = '';
			var pairs = Array();

			$A(this._PathArray).each(function(panelElem, index){
				panelElem.errorView(false);
				if(panelElem != this){
					if (Element.visible(panelElem._selfPanel)) {
						pairs.push(panelElem.getLanguagePairFromView());
					}
				}
			}.bind(this));
			pairs = pairs.flatten();

			var MyPair = this.getLanguagePair();
			for(var i = 0;i < MyPair.length;i++){
				if (pairs.indexOf(MyPair[i]) > -1) {
					validMessage = Const.Message.SaveOnError2 + ' ['+this.__util(MyPair[i])+']';
					Element.scrollTo($(this._selfPanel));
					this.errorView(true);
					this.showMessageBox(validMessage);
					return;
				}
			}

			var isAll = false;
			$A(this._PathArray).each(function(panelElem, index){
				if(panelElem.elementsIds.panel != "panel-N" && panelElem._primaryId == ''){
					isAll = true;
					throw $break;
				}
			});
			if(isAll){
				this._makeSaveDataAll();
			}else{
				this._makeSaveData(this);
			}
		}
	},
	_makeSaveData: function(panelElem){
		var save_data = Array();
		var validMessage = '';
		var pairs = Array();

		panelElem = this;

		index = panelElem.elementsIds.panel;
		var setting = panelElem.getSetting();
		setting = 'index=' +index + '&'+ setting;
		if (setting) {
			save_data.push(setting);
		}

		this._saveSetting(save_data);
	},
	_makeSaveDataAll: function(){
		var save_data = Array();

		$A(this._PathArray).each(function(panelElem, index){
			var setting = panelElem.getSetting();
			var idx = panelElem.elementsIds.panel;
			setting = 'index=' +idx + '&'+ setting;
			if (setting) {
				save_data.push(setting);
			}
		}.bind(this));

		this._saveSetting(save_data);
	},
	_saveSetting: function(save_data) {
		var controller = this;
		var postObj = {};
		postObj['mode'] = 'ALL';
		$A(save_data).each(function(setting, index){
			postObj['data['+index+']'] = setting;
		});
//		postObj['data'] = save_data;
		var hash = $H(postObj).toQueryString();
		new Ajax.Request('./ajax/save-user-setting.php', {
			method: 'post',
			parameters: hash,
			asynchronous:false,
			controller: controller,
			onSuccess: function(httpObj) {
				var responseJSON = httpObj.responseText.evalJSON();
				if(responseJSON.status == 'SESSIONTIMEOUT'){
					redirect2top();
					return;
				}

				this.errorView(false);
				var data = this._buildeData();
				var Lang1 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],data.lang1);
				var Flow = this.getdataArrayValue(this._CONST_FLOW_OPS,data.flow);
				if(data.lang4 != ""){
					var Lang2 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],data.lang4);
				}else if(data.lang3 != ""){
					var Lang2 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],data.lang3);
				}else{
					var Lang2 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],data.lang2);
				}

				if(this._PanelMode == "new"){
					var save_message = Const.Message.AddedMessage;
				}else{
					var save_message = Const.Message.SavedMessage;
				}
				save_message = save_message.replace("%SRC",Lang1);
				save_message = save_message.replace("%FLW",Flow);
				save_message = save_message.replace("%TGT",Lang2);

				$A(controller._PathArray).each(function(panelElem, index){
					panelElem.errorView(false);
					if(responseJSON.contents[panelElem.elementsIds.panel] && responseJSON.contents[panelElem.elementsIds.panel] != "undefined" && responseJSON.contents[panelElem.elementsIds.panel] != ""){
						var id = responseJSON.contents[panelElem.elementsIds.panel];
						panelElem.updatePrimaryId(id);
					}
				});

				//var id = responseJSON.contents[index];
				//this.updatePrimaryId(id);
				if(this._PanelMode == "new"){
					$('top-message-area').innerHTML = save_message;
					$('top-message-area').show();

					var data = this._buildeData();

					var idx = this._PathArray.length;
					var pathPanel = new TranslationPathPanel();
					pathPanel.makeTranslationPathPanel_A(idx, this._rootPanel,
						this._langridServiceInformations,
						this._PathArray,
						this._default_Settings,
						data);
					this._PathArray.push(pathPanel);
					for(var i=0;i < this._PathArray.length;i++){
						if(this._PathArray[i] == this){
							this._PathArray.splice(i,1);
							break;
						}
					}
					this._sortPanelArray();
				}else{
					if(this._isDelete == 'yes'){
						for(var i=0;i < this._PathArray.length;i++){
							if(this._PathArray[i] == this){
								this._PathArray.splice(i,1);
								break;
							}
						}
					}else{
						this.updateViewPanel();
						this.showMessageBox(save_message);
					}
					//Translation path "English <> Japanese" is added.
					//Translation path "English <> Japanese" is changed.
				}
				//addPathWorkspace._resetFilter();

			}.bind(this),
			onFailure: function(httpObj) {
				$('setting_error_message').innerHTML = '<per>' + httpObj.responseText + '</pre>';
			},
			onComplete: function() {
				$$(".nowedit").each(function(ele){
					if($(ele.id+':edit')){$(ele.id+':edit').hide();}
					if($(ele.id+':view')){$(ele.id+':view').show();}
					Element.removeClassName(ele,"nowedit");
					var save_btn = $(ele.id+':save');
					Element.removeClassName(save_btn,"btn");
					Element.addClassName(save_btn,"btn-disable");
					var save_img = $(ele.id+':save-img');
					save_img.setAttribute('src','./images/icon/icn_save_disable.gif');
				});
				if(this._PanelMode == 'new'){
					this._addPanel.innerHTML = "";
					this._addPanel.hide();
					$('div-add-path-area').show();
				}
				addPathWorkspace._resetFilter();
			}.bind(this)
		});
	},
	updateViewPanel:function (){
		var data = this._initializeSettingData(this._buildeData());
		this._preData = data;
		var viewPanel = this._createViewPanel(data);
		var oldNode = $(this.elementsIds.view);
		this._selfPanel.replaceChild(viewPanel, oldNode);
	},
	reverseValue:function(){
		var data = {
			lang1: '',
			lang2: '',
			lang3: '',
			lang4: '',
			service1: '',
			service2: '',
			service3: '',
			flow: this._preData.flow,
			global_dicts: Array('','',''),
			local_dicts: Array('','',''),
			temp_dicts: Array('','',''),
			morph_from:Array('','',''),
			morph_to:Array('','',''),
			dict_flags:Array('','','')
		};
		if (this._preData.service3 != '') {
			data.lang1 = this._preData.lang4;
			data.lang2 = this._preData.lang3;
			data.lang3 = this._preData.lang2;
			data.lang4 = this._preData.lang1;
			data.service1 = this._preData.service3;
			data.service2 = this._preData.service2;
			data.service3 = this._preData.service1;

			data.global_dicts[0] = this._preData.global_dicts[2];
			data.global_dicts[1] = this._preData.global_dicts[1];
			data.global_dicts[2] = this._preData.global_dicts[0];
			data.local_dicts[0] = this._preData.local_dicts[2];
			data.local_dicts[1] = this._preData.local_dicts[1];
			data.local_dicts[2] = this._preData.local_dicts[0];
			data.temp_dicts[0] = this._preData.temp_dicts[2];
			data.temp_dicts[1] = this._preData.temp_dicts[1];
			data.temp_dicts[2] = this._preData.temp_dicts[0];
			data.dict_flags[0] = this._preData.dict_flags[2];
			data.dict_flags[1] = this._preData.dict_flags[1];
			data.dict_flags[2] = this._preData.dict_flags[0];

			data.morph_from[0] = this._preData.morph_to[2];
			data.morph_from[1] = this._preData.morph_to[1];
			data.morph_from[2] = this._preData.morph_to[0];
			data.morph_to[0] = this._preData.morph_from[2];
			data.morph_to[1] = this._preData.morph_from[1];
			data.morph_to[2] = this._preData.morph_from[0];
		} else if (this._preData.service2 != '') {
			data.lang1 = this._preData.lang3;
			data.lang2 = this._preData.lang2;
			data.lang3 = this._preData.lang1;
			data.service1 = this._preData.service2;
			data.service2 = this._preData.service1;

			data.global_dicts[0] = this._preData.global_dicts[1];
			data.global_dicts[1] = this._preData.global_dicts[0];
			data.local_dicts[0] = this._preData.local_dicts[1];
			data.local_dicts[1] = this._preData.local_dicts[0];
			data.temp_dicts[0] = this._preData.temp_dicts[1];
			data.temp_dicts[1] = this._preData.temp_dicts[0];
			data.dict_flags[0] = this._preData.dict_flags[1];
			data.dict_flags[1] = this._preData.dict_flags[0];

			data.morph_from[0] = this._preData.morph_from[2];
			data.morph_from[1] = this._preData.morph_from[1];
			data.morph_from[2] = this._preData.morph_from[0];
			data.morph_to[0] = this._preData.morph_from[1];
			data.morph_to[1] = this._preData.morph_from[0];
		}else{
			data.lang1 = this._preData.lang2;
			data.lang2 = this._preData.lang1;
			data.service1 = this._preData.service1;
			data.global_dicts[0] = this._preData.global_dicts[0];
			data.local_dicts[0] = this._preData.local_dicts[0];
			data.temp_dicts[0] = this._preData.temp_dicts[0];
			data.dict_flags[0] = this._preData.dict_flags[0];

			data.morph_from[0] = this._preData.morph_from[1];
			data.morph_from[1] = this._preData.morph_from[0];
			data.morph_to[0] = this._preData.morph_from[0];
		}

		for(var i=0;i<=2;i++){
			this._global_dicts[i] = data.global_dicts[i].split(",");
			this._local_dicts[i] = data.local_dicts[i].split(",");
			this._temp_dicts[i] = data.temp_dicts[i].split(",");
			this._morphemes_from[i] = data.morph_from[i];
			this._morphemes_to[i] = data.morph_to[i];
			this._dict_flags[i] = data.dict_flags[i];
		}

		this._preData = data;

		var viewPanel = this._createViewPanel(data);
		var editPanel = this._createEditPanel(data);

		this._selfPanel.innerHTML = "";
		this._selfPanel.appendChild(viewPanel);
		this._selfPanel.appendChild(editPanel);

		this.updateEditElement();
	},
	_onDictionaryButtonClicked: function(ev) {
		popupDictionaryPanel = new DictionaryPanel();

		var btn = Event.findElement(ev,"a");
		var id = btn.id.substr(btn.id.length-1,1);

		var langs = {lang1:'',langname1:'',lang2:'',langname2:''}
		langs.lang1 = $(this._selectBoxElements['lang'+String(Number(id)+1)]).value;
		langs.langname1 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],langs.lang1);
		langs.lang2 = $(this._selectBoxElements['lang'+String(Number(id)+2)]).value;
		langs.langname2 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],langs.lang2);

		popupDictionaryPanel.setParams(
			id,
			this.elementsIds.panel,
			this._langridServiceInformations['_dictionaryServicesArray'],
			this._langridServiceInformations['_analyzerServicesArray'],
			langs,
			this._global_dicts[id],
			this._local_dicts[id],
			this._temp_dicts[id],
			this._morphemes_from[id],
			this._morphemes_to[id],
			this._default_Settings,
			this);
		popupDictionaryPanel.showPane("edit");

	},
	_onDictionaryViewClicked: function(ev) {
		Event.stop(ev);
		popupDictionaryPanel = new DictionaryPanel();

		var btn = Event.findElement(ev,"div");
		var id = btn.id.substr(btn.id.length-1,1);

		var langs = {lang1:'',langname1:'',lang2:'',langname2:''}
		langs.lang1 = this._preData['lang'+String(Number(id)+1)];
		langs.langname1 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],langs.lang1);
		langs.lang2 = this._preData['lang'+String(Number(id)+2)];
		langs.langname2 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],langs.lang2);

		popupDictionaryPanel.setParams(
			id,
			this.elementsIds.panel,
			this._langridServiceInformations['_dictionaryServicesArray'],
			this._langridServiceInformations['_analyzerServicesArray'],
			langs,
			this._global_dicts[id],
			this._local_dicts[id],
			this._temp_dicts[id],
			this._morphemes_from[id],
			this._morphemes_to[id],
			this._default_Settings,
			this);
		popupDictionaryPanel.showPane("view");
	},
	_onEditButtonClicked: function(ev) {
		var ret = false;

		$A(this._PathArray).each(function(panelElem, index){
			if(Element.hasClassName(panelElem._selfPanel,'nowedit')){
				if(!panelElem._onCancelButtonClicked()){
					ret = true;
					throw $break;
				}
			}
		});
		if(ret){return;}
		
		if(!$(this.elementsIds.edit)){
			var editPanel = this._createEditPanel(this._preData);
			this._selfPanel.appendChild(editPanel);
			this.updateEditElement();
		}
		$(this.elementsIds.view).hide();
		for(var i=0;i<=2;i++){
			if(this._dict_flags[i] == 2){
				var src = "icn_custom_dicttionary.gif";
			}else{
				var src = "icn_default_dictionary.gif";
			}
			if($(this.elementsIds.panel + ':dictimg_' + i)){
				$(this.elementsIds.panel + ':dictimg_' + i).setAttribute('src','./images/icon/'+src);
			}
		}

		$(this.elementsIds.edit).show();
		Element.addClassName($(this._selfPanel), 'nowedit');
		document.fire('dom:settingEditingThis');
		document.fire('dom:refreshMessage');
	},
	_onCancelButtonClicked: function(ev) {
		if(!Element.hasClassName($(this.elementsIds.panel+":save"),'btn-disable')){
			if(!confirm(Const.Message.CancelConfirm)){
				return false;
			}
		}

		$$(".nowedit").each(function(ele){
			if($(ele.id+':edit')){
				$(ele.id+':edit').hide();
			}
			if($(ele.id+':view')){
				$(ele.id+':view').show();
			}
			Element.removeClassName(ele,"nowedit");
		});

		if(this._PanelMode != 'new'){
			this._resetEditPanel();
		}else{
			$A(this._PathArray).each(function(panelElem, index){
				if(panelElem.IndexNumber == this.IndexNumber){
					this._PathArray.splice(index,1);
				}
			}.bind(this))
			this._addPanel.innerHTML = "";
			$('new-path-area').hide();
			$('div-add-path-area').show();
		}
		return true;
	},
	_resetEditPanel:function(){
		var data = this._preData;
		var editPanel = this._createEditPanel(data);
		var oldNode = this._selfPanel.lastChild;
		this._selfPanel.replaceChild(editPanel, oldNode);
		this.updateEditElement();
	},
	setCurrentDictionaryArray: function(dictionaryArray) {

	},
	onDictionaryClickHandler: function(level,did, checked, dictionaryType) {
		switch(dictionaryType){
			case "GLOBAL":
				if (checked == true) {
					this._global_dicts[level].push(did);
				} else {
					var idx = this._global_dicts[level].indexOf(did);
					if (idx > -1) {
						this._global_dicts[level].splice(idx, 1);
					}
				}
				this._global_dicts[level] = this._global_dicts[level].sort();
			break;
			case "LOCAL":
				if (checked == true) {
					this._local_dicts[level].push(did);
				} else {
					var idx = this._local_dicts[level].indexOf(did);
					if (idx > -1) {
						this._local_dicts[level].splice(idx, 1);
					}
				}
				this._local_dicts[level] = this._local_dicts[level].sort();
			break;
			case "USER":
				if (checked == true) {
					this._temp_dicts[level].push(did);
				} else {
					var idx = this._temp_dicts[level].indexOf(did);
					if (idx > -1) {
						this._temp_dicts[level].splice(idx, 1);
					}
				}
				this._temp_dicts[level] = this._temp_dicts[level].sort();
			break;
			case "M_SRC":
				if (checked == true) {
					this._morphemes_from[level] = did;
				}
			break;
			case "M_TGT":
				if (checked == true) {
					this._morphemes_to[level] = did;
				}
			break;
		}
	},
	updateDictionaryFlag: function(level,DictFlag){
		this._global_dicts[level] = this._global_dicts[level].sort();
		this._local_dicts[level] = this._local_dicts[level].sort();
		this._temp_dicts[level] = this._temp_dicts[level].sort();

		this._default_Settings.global = this._default_Settings.global.sort();
		this._default_Settings.local = this._default_Settings.local.sort();
		this._default_Settings.temp = this._default_Settings.temp.sort();

		var Cur_G_Dict = this._global_dicts[level].join(',');
		var Cur_L_Dict = this._local_dicts[level].join(',');
		var Cur_T_Dict = this._temp_dicts[level].join(',');
		var Def_G_Dict = this._default_Settings.global.join(',');
		var Def_L_Dict = this._default_Settings.local.join(',');
		var Def_T_Dict = this._default_Settings.temp.join(',');


		if(Def_G_Dict == '' && Def_L_Dict == '' && Def_T_Dict == ''){
			if(Cur_G_Dict == '' && Cur_L_Dict == '' && Cur_T_Dict == ''){
				this._dict_flags[level] = 0;
			}else{
				this._dict_flags[level] = 2;
			}
		}else{
			this._dict_flags[level] = DictFlag;
		}

		if(this._dict_flags[level] == 2){
			var src = "icn_custom_dicttionary.gif";
		}else{
			var src = "icn_default_dictionary.gif";
		}
		if($(this.elementsIds.panel + ':dictimg_'+level)){
			$(this.elementsIds.panel + ':dictimg_'+level).setAttribute('src','./images/icon/'+src);
		}
	},
	updateMorphologicalAnalyzer: function(level){
		var id = Number(level);
		switch(id){
			case 0:
				this._morphemes_from[id+1] = this._morphemes_to[id];
				break;
			case 1:
				this._morphemes_to[id-1] = this._morphemes_from[id];
				this._morphemes_from[id+1] = this._morphemes_to[id];
				break;
			case 2:
				this._morphemes_to[id-1] = this._morphemes_from[id];
				break;
		}
	},
	clearSelectDictionary: function(level) {
		this._global_dicts[level].clear();
		this._local_dicts[level].clear();
		this._temp_dicts[level].clear();
	},
	makeTdObj: function(str,child){
		var ret = document.createElement('td');
		if(str != ""){
			ret.innerHTML = str;
		}
		if(child){
			ret.appendChild(child);
		}
		return ret;
	},
	makeDivObj: function(str,classname){
		var ret = document.createElement('div');
		var c_ary = classname.split(',');
		ret.className = c_ary[0];
		if(c_ary.length > 1){
			for(i=1;i<c_ary.length;i++){
				Element.addClassName(ret, c_ary[i]);
			}
		}
		ret.innerHTML = str;
		return ret;
	},
	makeServiceViewObj: function(service,dictbtn){
		var ret = document.createElement('div');
		Element.addClassName(ret, 'service-cell');

		var svc = document.createElement('span');
		Element.setStyle(svc, { float:"left"});
		svc.innerHTML = service;

		var btn = document.createElement('span');
		Element.setStyle(btn, { float:"right"});
		btn.appendChild(dictbtn);

		var br = document.createElement('br');
		Element.setStyle(br, { clear:"both"});

		ret.appendChild(svc);
		ret.appendChild(btn);
		ret.appendChild(br);

		return ret;
	},

	getSetting: function() {
		var data = this._buildeData();
		if(data){
			return decodeURIComponent($H(data).toQueryString());
		}else{
			return decodeURIComponent($H(this._preData).toQueryString());
		}
	},
	validate: function() {
		var data = this._buildeData();
		if (data.isDelete == 'yes') {
			return null;
		}

		if ((data.lang1 && data.lang2 && data.service1) == false) {
			return Const.Message.SaveOnError1;
		}

		if (this._checkBoxElements.checkbox1.checked == true) {
			if ((data.lang2 && data.lang3 && data.service2) == false) {
				return Const.Message.SaveOnError1;
			}
		}

		if (this._checkBoxElements.checkbox2.checked == true) {
			if ((data.lang3 && data.lang4 && data.service3) == false) {
				return Const.Message.SaveOnError1;
			}
		}

		if(data.service3 != ""){
			if(data.lang1 == data.lang4){
				return Const.Message.SaveOnError1;
			}
		}else if(data.service2 != ""){
			if(data.lang1 == data.lang3){
				return Const.Message.SaveOnError1;
			}
		}else{
			if(data.lang1 == data.lang2){
				return Const.Message.SaveOnError1;
			}
		}

		return null;
	},
	getLanguagePairFromView: function() {
		var ret = new Array();

		var data = this._preData;

		var srcLang = data.lang1;
		var tgtLang = "";
		if (data.service3 != "") {
			tgtLang = data.lang4;
		}else if (data.service2 != "") {
			tgtLang = data.lang3;
		}else{
			tgtLang = data.lang2;
		}

		ret.push(srcLang + "2" + tgtLang);
		if(data.flow == "both"){
			ret.push(tgtLang + "2" + srcLang);
		}

		return ret;
	},
	getLanguagePair: function() {
		var ret = new Array();

		var data = this._buildeData();
		if (data.isDelete == 'yes') {
			return ret;
		}
		var srcLang = data.lang1;
		var tgtLang = "";
		if (this._checkBoxElements.checkbox2.checked == true) {
			tgtLang = data.lang4;
		}else if (this._checkBoxElements.checkbox1.checked == true) {
			tgtLang = data.lang3;
		}else{
			tgtLang = data.lang2;
		}
		ret.push(srcLang + "2" + tgtLang);
		if(data.flow == "both"){
			ret.push(tgtLang + "2" + srcLang);
		}

		return ret;
	},
	_buildeData: function() {
		try {
			var data = {
				id: this._primaryId,
				isDelete: this._isDelete,
				lang1: $(this._selectBoxElements.lang1).value,
				lang2: $(this._selectBoxElements.lang2).value,
				lang3: $(this._selectBoxElements.lang3).value,
				lang4: $(this._selectBoxElements.lang4).value,
				flow: $(this._selectBoxElements.flow1).value,
				flow1: $(this._selectBoxElements.flow1).value,
				flow2: $(this._selectBoxElements.flow2).value,
				flow3: $(this._selectBoxElements.flow3).value,
				service1: this._servicePanelElements[0].getSetting(),
				service2: this._servicePanelElements[1].getSetting(),
				service3: this._servicePanelElements[2].getSetting(),
				global_dict_1: this._global_dicts[0].join(","),
				global_dict_2: this._global_dicts[1].join(","),
				global_dict_3: this._global_dicts[2].join(","),
				local_dict_1: this._local_dicts[0].join(","),
				local_dict_2: this._local_dicts[1].join(","),
				local_dict_3: this._local_dicts[2].join(","),
				temp_dict_1: this._temp_dicts[0].join(","),
				temp_dict_2: this._temp_dicts[1].join(","),
				temp_dict_3: this._temp_dicts[2].join(","),
				dict_flag_1: this._dict_flags[0],
				dict_flag_2: this._dict_flags[1],
				dict_flag_3: this._dict_flags[2],
				morph_analyzer1:this._morphemes_from[0],
				morph_analyzer2:this._morphemes_from[1],
				morph_analyzer3:this._morphemes_from[2],
				morph_analyzer4:this._morphemes_to[2],
				panelId: $(this._selfPanel).id
			};

			if(!this.isSupportedAnalyzer(data.morph_analyzer1,data.lang1)){
				data.morph_analyzer1 = this.getDefaultAnalyzer(data.lang1);
			}
			if(!this.isSupportedAnalyzer(data.morph_analyzer2,data.lang2)){
				data.morph_analyzer2 = this.getDefaultAnalyzer(data.lang2);
			}
			if(!this.isSupportedAnalyzer(data.morph_analyzer3,data.lang3)){
				data.morph_analyzer3 = this.getDefaultAnalyzer(data.lang3);
			}
			if(!this.isSupportedAnalyzer(data.morph_analyzer4,data.lang4)){
				data.morph_analyzer4 = this.getDefaultAnalyzer(data.lang4);
			}

			return data;
		} catch (e) {
			data = this._preData;
			data.id = this._primaryId;
			data.isDelete = this._isDelete;
			data.flow1 = data.flow;
			data.flow2 = data.flow;
			data.flow3 = data.flow;
			data.global_dict_1 = this._global_dicts[0].join(",");
			data.global_dict_2 = this._global_dicts[1].join(",");
			data.global_dict_3 = this._global_dicts[2].join(",");
			data.local_dict_1 = this._local_dicts[0].join(",");
			data.local_dict_2 = this._local_dicts[1].join(",");
			data.local_dict_3 = this._local_dicts[2].join(",");
			data.temp_dict_1 = this._temp_dicts[0].join(",");
			data.temp_dict_2 = this._temp_dicts[1].join(",");
			data.temp_dict_3 = this._temp_dicts[2].join(",");
			data.dict_flag_1 = this._dict_flags[0];
			data.dict_flag_2 = this._dict_flags[1];
			data.dict_flag_3 = this._dict_flags[2];
			data.morph_analyzer1 = this._morphemes_from[0];
			data.morph_analyzer2 = this._morphemes_from[1];
			data.morph_analyzer3 = this._morphemes_from[2];
			data.morph_analyzer4 = this._morphemes_to[2];
			data.panelId = $(this._selfPanel).id

			if(!this.isSupportedAnalyzer(data.morph_analyzer1,data.lang1)){
				data.morph_analyzer1 = this.getDefaultAnalyzer(data.lang1);
			}
			if(!this.isSupportedAnalyzer(data.morph_analyzer2,data.lang2)){
				data.morph_analyzer2 = this.getDefaultAnalyzer(data.lang2);
			}
			if(!this.isSupportedAnalyzer(data.morph_analyzer3,data.lang3)){
				data.morph_analyzer3 = this.getDefaultAnalyzer(data.lang3);
			}
			if(!this.isSupportedAnalyzer(data.morph_analyzer4,data.lang4)){
				data.morph_analyzer4 = this.getDefaultAnalyzer(data.lang4);
			}
			return data;
		}
	},
	_checkChange:function(){
		var data1 = this._preData;

		if(data1.lang1 != $(this._selectBoxElements.lang1).value)       { return true;}
		if(data1.lang2 != $(this._selectBoxElements.lang2).value)       { return true;}
		if(data1.lang3 != $(this._selectBoxElements.lang3).value)       { return true;}
		if(data1.lang4 != $(this._selectBoxElements.lang4).value)       { return true;}
		if(data1.flow != $(this._selectBoxElements.flow1).value)        { return true;}
		if(data1.service1 != this._servicePanelElements[0].getSetting()){ return true;}
		if(data1.service2 != this._servicePanelElements[1].getSetting()){ return true;}
		if(data1.service3 != this._servicePanelElements[2].getSetting()){ return true;}

		var last = 0;
		if($(this._selectBoxElements.lang4).value != ""){
			var last = 2;
		}else{
			if($(this._selectBoxElements.lang3).value != ""){
				var last = 1;
			}
		}

		for(var i=0;i<=2;i++){
			if(data1.dict_flags[i] != this._dict_flags[i]){return true;}

			var tmp1 = data1.global_dicts[i].split(",").sort();
			var tmp2 = this._global_dicts[i].sort();
			if(tmp1.join(",") != tmp2.join(",")){
				return true;
			}

			var tmp1 = data1.local_dicts[i].split(",").sort();
			var tmp2 = this._local_dicts[i].sort();
			if(tmp1.join(",") != tmp2.join(",")) return true;

			var tmp1 = data1.temp_dicts[i].split(",").sort();
			var tmp2 = this._temp_dicts[i].sort();
			if(tmp1.join(",") != tmp2.join(",")){
				return true;
			}

			if(data1.morph_from[i] != this._morphemes_from[i]){
				return true;
			}
			if(data1.morph_to[i] != this._morphemes_to[i]){
				return true;
			}
		}
		//&& data1.dict_flag == this._DictFlag
		//&& data1.global_dict_ids ==  this._currentDictionaryArray.join(',')
		//&& data1.user_dict_ids == this._currentUserDictionaryArray.join(',')
		return false;
	},
	updatePrimaryId: function(id) {
		this._primaryId = id;
	},
	getViewData: function(){
		return this.viewData;
	},
	isSupportedAnalyzer: function(service_id,lang){
		var ret = false;
		this._langridServiceInformations['_analyzerServicesArray'].each(function(service, index){
			if(service.service_id == service_id){
				var supported_langs = new Array();
				supported_langs = service.supported_languages_paths.split(",");
				if (service.service_type == 'MORPHOLOGICALANALYSIS' && supported_langs.indexOf(lang) > -1) {
					ret = true;
					throw $break;
				}
			}
		});
		return ret;
	},
	getDefaultAnalyzer: function(lang) {
		var defaultId = getDefaultMorphologicalAnalyzer(lang);
		var serviceId = null;
		this._langridServiceInformations['_analyzerServicesArray']
			.each(
				function(service) {
					var supported_langs =
						service.supported_languages_paths.split(",");
					if (service.service_type != 'MORPHOLOGICALANALYSIS'
						|| supported_langs.indexOf(lang) < 0) {
						return;
					}
					if (serviceId == null) {
						serviceId = service.service_id;
					}
					if (service.service_id.split(":").indexOf(defaultId) > -1) {
						serviceId = service.service_id;
						throw $break;
					}
				});
		return serviceId;
	},
	_getValidAnalyzer:function (service_id,lang){
		if(!this.isSupportedAnalyzer(service_id,lang)){
			return this.getDefaultAnalyzer(lang);
		}else{
			return service_id;
		}
	},
	_sortPanelArray:function(){
		if(this._PathArray.length){
			this.__sort_array(this._PathArray, 0, this._PathArray.length - 1);

			for(var i = 0;i<this._PathArray.length;i++){
				$('list-area').appendChild(this._PathArray[i]._selfPanel);
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
	_copyArrayValue: function(ary){
		var ret = new Array();
		if(ary instanceof Array){
			for(var i=0;i<ary.length;i++){
				if(ary[i] instanceof Array){
					ret[i] = this._copyArrayValue(ary[i]);
				}else{
					ret[i] = ary[i];
				}
			}
		}
		return ret;
	},
	_explode: function(str,sep){
		if(str != null){
			return str.split(sep);
		}else{
			return new Array()
		}
	},
	__util: function(pair) {
		var langs = pair.split('2');
		var src = this._langridServiceInformations['_sourceLanguageArray'][langs[0]];
		var tgt = this._langridServiceInformations['_sourceLanguageArray'][langs[1]];
		return src + '-->' + tgt;
	}
};

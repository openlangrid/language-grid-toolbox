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

var BBSSettingView = Class.create();

Object.extend(BBSSettingView.prototype, BBSAddPathWorkspace.prototype);
Object.extend(BBSSettingView.prototype, {
	_makeEditSettingArea:function(){
		var baseElm = this.MainPanel;
		var setting_area = document.createElement('div');
		setting_area.id = 'div-setting-area';
		
		var default_dic_area = document.createElement('div');
		default_dic_area.id = 'div-edit-default-area';
		var default_dic_btn = document.createElement('a');
		default_dic_btn.innerHTML = '<img src="'+Const.Images.DefaultDict+'" id="img-def-dict"> '+Const.Label.ViewDefaultDict;
		Event.observe(default_dic_btn, 'click', this._onDefaultDictionaryViewClicked.bind(this));
		default_dic_area.appendChild(default_dic_btn);
		
		var dics_count = document.createElement('span');
		dics_count.id = "def-dic-count";
		default_dic_area.appendChild(dics_count);

		setting_area.appendChild(default_dic_area);
		
		baseElm.appendChild(setting_area);
		this._setDictionaryCount();
	},
	_showPanel: function(setting,listArea) {
		var idx = this.pathPanelArray.length;
		var pathPanel = new TranslationPathPanelView();
		pathPanel.makeTranslationPathPanel(idx, listArea, 
			this._langridServiceInformations,
			this.pathPanelArray,
			this._DefaultSettings,
			 setting);
		this.pathPanelArray.push(pathPanel);
	},
	_onDefaultDictionaryViewClicked:function(ev){
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
		popupDictionaryPanel.showPane("view");
	}
});
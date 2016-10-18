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

var TranslationPathPanelView = Class.create();


Object.extend(TranslationPathPanelView.prototype, TranslationPathPanel.prototype);
Object.extend(TranslationPathPanelView.prototype, {
	_initEvent: function(data) {
		var selfPanel = document.createElement('div');
		selfPanel.id = this.elementsIds.panel;
		Element.addClassName(selfPanel,"div-path-panel");

		var viewPanel = this._createViewPanel(data);
		selfPanel.appendChild(viewPanel);
		
		this._rootPanel.appendChild(selfPanel);
		this._selfPanel = selfPanel;
		
	},
	_createViewPanel: function(data) {
		this.viewData = data;
		
		var ViewPanel = document.createElement('div');
		ViewPanel.id = this.elementsIds.view;
		ViewPanel.className = 'trans-path-noedit';
		
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
		
		return ViewPanel;
	}
});
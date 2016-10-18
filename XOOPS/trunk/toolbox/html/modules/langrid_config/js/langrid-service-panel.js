//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2010  NICT Language Grid Project
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
/* $Id: langrid-service-panel.js 6093 2011-10-06 00:34:50Z mtanaka $ */

var ServicePanel = Class.create();
ServicePanel.prototype = {
	_langridServiceInformations: null,
	_interElement: null,
	_thisPanel: null,
	_this_id: null,
	_serviceButtonArray: null,
	_currentSelectServiceId: null,
	_currentSelectSourceLang: null,
	_currentSelectTargetLang: null,
	_currentSelectFlow: null,
	_afterInsertTargetElement: null,
	_sourceSelect: null,
	_targetSelect: null,
	_flowSelect: null,
	disabled: false,
	_targetLanguageArray: null,
	
	_errorMessage: null,
	
	_separator: '#',
	
	initialize: function(LangAry) {
		this._targetLanguageArray = LangAry;
	},
	
	makeServicePanel: function(interElement, Panel_ID, langridServiceInformations, fix,sourceSelect, targetSelect, flowSelect) {
		this._interElement = interElement;
		this._langridServiceInformations = langridServiceInformations;
		this._this_id = Panel_ID + this._separator + 'services-' + fix;
		this._serviceButtonArray = {};
		this._currentSelectServiceId = [];
		this._sourceSelect = sourceSelect;
		this._targetSelect = targetSelect;
		this._flowSelect = flowSelect;

		this._initEvent();
	},
	
	_initEvent: function() {
		this._interElement.innerHTML += 'Translator';
		
		var PanelDiv = document.createElement('div');
		PanelDiv.id = this._this_id;

		this._langridServiceInformations.each(function(service, index) {
			var button = new ServiceButton();
			button.makeServiceButton(PanelDiv, service, this._onServiceButtonSelectHandler, this);
			this._serviceButtonArray[service.service_id] = button;
			button.setButtonMode('hide');
		}.bind(this));
		
		this._interElement.appendChild(PanelDiv);
	},
	
	setLanguageSelectors: function(src, tgt, flow) {
		this._currentSelectSourceLang = $(src).value;
		this._currentSelectTargetLang = $(tgt).value;
		this._currentSelectFlow = $(flow).value;

		src.observe('change', function(ev) {
			var lang = $(Event.element(ev)).value;
			this._currentSelectSourceLang = lang;
			this._updateService();
		}.bind(this), false);
		
		tgt.observe('change', function(ev) {
			var lang = $(Event.element(ev)).value;
			this._currentSelectTargetLang = lang;
			this._updateService();
		}.bind(this), false);
		
		if (flow != null) {
			flow.observe('change', function(ev) {
				var flow = $(Event.element(ev)).value;
				this._currentSelectFlow = flow;
				this._updateService();
			}.bind(this),false);
		}
		
		this._afterInsertTargetElement = tgt;
	},
	
	_updateService: function() {
		this._crearAllSelect(this._targetSelect);
		
		var serviceId = this._currentSelectServiceId;
		var languages = [];
		
		$H(this._serviceButtonArray).each(function(item) {
			
			languages.push($(item.value)._getMatchLangTgt(this._currentSelectSourceLang, this._currentSelectFlow));

			if (serviceId.indexOf(item.key) != -1) {
				if ($(item.value).isSupportedLanguage(this._currentSelectSourceLang, this._currentSelectTargetLang, this._currentSelectFlow)) {
					$(item.value).setButtonMode('active');
				} else {
					$(item.value).setButtonMode('hide');
					this._currentSelectServiceId = [];
				}
			} else if ($(item.value).isSupportedLanguage(this._currentSelectSourceLang, this._currentSelectTargetLang, this._currentSelectFlow)) {
				$(item.value).setButtonMode('display');
			} else {
				$(item.value).setButtonMode('hide');
			}
		}.bind(this));
		
		languages = languages.flatten();
		
		var Pairs = [];
		if (languages.length) {
			for (i = 0; i < languages.length; i++) {
				var PairStr = this.getdataArrayValue(this._targetLanguageArray,languages[i]) + "\n"+String(languages[i]);
				for(j = 0;j<Pairs.length;j++){
					if(Pairs[j] == PairStr){
						PairStr = "";
						break;
					}
				}
				if(PairStr != ""){
					Pairs.push(PairStr);
				}
			}
		}
		
		Pairs = Pairs.sort();

		this._makeTargetSelect(Pairs);

		document.fire('dom:settingEditingThis');
	},
	
	_crearAllSelect:function (obj){
		if(obj.hasChildNodes()){
			var cnode = obj.lastChild;
			while (cnode){
				var delnode = cnode;
				cnode = cnode.previousSibling;
				obj.removeChild(delnode);
			}
		}
	},
	
	_makeTargetSelect:function(Pairs){
		if(this._targetSelect){
			var selectElem = this._targetSelect;

			var defaultOpt = document.createElement('option');
			defaultOpt.setAttribute('value', '');
			defaultOpt.innerHTML = '---';
			selectElem.appendChild(defaultOpt);

			for(i=0;i<Pairs.length;i++){
				var tmp = Pairs[i].split("\n");
				if(tmp[0] != ""){
					var opt = document.createElement('option');
					opt.setAttribute('value', tmp[1]);
					opt.innerHTML = tmp[0];
					if (tmp[1] == this._currentSelectTargetLang) {
						opt.setAttribute('selected', 'yes');
					}
					selectElem.appendChild(opt);
				}
			}
		}
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
	
	setActiveServiceId: function(serviceId) {
		if (serviceId == null || serviceId == '' || this._serviceButtonArray[serviceId] == 'undefined') {
			this._currentSelectServiceId = [];
		} else {
//			if (this._currentSelectServiceId.indexOf(serviceId) == -1) {
//				this._currentSelectServiceId.push(serviceId);
//			} else {
//				this._currentSelectServiceId = this._currentSelectServiceId.without(serviceId);
//			}
			this._currentSelectServiceId = [serviceId];
		}

		this._updateService();
	},
	
	clearSelection: function() {
		this._currentSelectServiceId = [];
		this._currentSelectFlow = 'left';
		this._currentSelectTargetLang = '';
		this._updateService();
	},
	
	makeAndBindTranslationCombinationCheckbox: function(controller, handler) {
		var checkbox = document.createElement('input');
		checkbox.id = this._this_id + this._separator + 'combination';
		checkbox.setAttribute('type', 'checkbox');
		checkbox.setAttribute('value', 'on');

		Event.observe(checkbox, 'click', handler.bindAsEventListener(controller, checkbox));

		return checkbox;
	},
	
	hasSetting: function() {
		return (this._currentSelectServiceId.length > 0);
	},
	
	getSetting: function() {
		return this._currentSelectServiceId;
	},
	
	_canSelectTranslatorButton: function(id) {
		this._errorMessage = null;
		
		if (this.disabled) {
			return false;
		}

		if (this._currentSelectServiceId.indexOf(id) == -1 && this._currentSelectServiceId.length >= 5) {
			this._errorMessage = Resource.TRANSLATOR_LIMIT_WARNING;
			return false;
		}
		
		var sourceLang = this._currentSelectSourceLang;
		var targetLang = this._currentSelectTargetLang;
		var both = (this._currentSelectFlow == 'both');
		
		// if (this._currentSelectServiceId.indexOf(id) == -1 && this.hasSetting() && !LangridServices.hasSimilarityCalculations(sourceLang, targetLang, both)) {
		// 	this._errorMessage = Resource.TRANSLATOR_MULTI_SELECT_WARNING;
		// 	return false;
		// }
		
		return true;
	},
	
	// -
	// Action methods

	/**
	 * translator button clicked
	 */
	_onServiceButtonSelectHandler: function(ev) {
		var tokens = $(Event.element(ev).up()).id.split(this._separator);
		var id = tokens[tokens.length - 1];
		
		if (!this._canSelectTranslatorButton(id)) {
			if (this._errorMessage) {
				alert(this._errorMessage);
			}
			return;
		}
		
		this.setActiveServiceId(id);
	}
};
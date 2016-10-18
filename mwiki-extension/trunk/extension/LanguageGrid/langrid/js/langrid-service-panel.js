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
	initialize: function(LangAry) {
		this._targetLanguageArray = LangAry;
	},
	makeServicePanel: function(interElement,Panel_ID, langridServiceInformations, fix,sourceSelect,targetSelect,flowSelect) {
		this._interElement = interElement;
		this._langridServiceInformations = langridServiceInformations;
		this._this_id = Panel_ID + ':services-' + fix;
		this._serviceButtonArray = {};
		this._currentSelectServiceId = '';
		this._sourceSelect = sourceSelect;
		this._targetSelect = targetSelect;
		this._flowSelect = flowSelect;

		this._initEvent();
	},
	_initEvent: function() {
		this._interElement.innerHTML += 'Translator';
		var PanelDiv = document.createElement('div');
		PanelDiv.id = this._this_id;
		var controller = this;
		this._langridServiceInformations.each(function(service, index){
			var button = new ServiceButton();
			button.makeServiceButton(PanelDiv, service, controller._onServiceButtonSelectHandler, controller);
			controller._serviceButtonArray[service.service_id] = button;
		});
		this._interElement.appendChild(PanelDiv);
	},
	_onSelectService: function(ev) {
		var sid = ev.memo.service_id;
		this.setActiveServiceId(sid);
	},
	_onServiceButtonSelectHandler: function(ev) {
		if(!this.disabled){
			var tokens = $(Event.element(ev).up()).id.split(':');
			var id = tokens[tokens.length - 1];
			this.setActiveServiceId(id);
		}
	},
	setLanguageSelectors: function(src, tgt, flow) {
		this._currentSelectSourceLang = $(src).value;
		this._currentSelectTargetLang = $(tgt).value;
		this._currentSelectFlow = $(flow).value;
		var controller = this;
		src.observe('change', function(ev){
			var lang = $(Event.element(ev)).value;
			controller._currentSelectSourceLang = lang;
			controller._updateService();
		}.bind(controller),false);
		tgt.observe('change', function(ev){
			var lang = $(Event.element(ev)).value;
			controller._currentSelectTargetLang = lang;
			controller._updateService();
		}.bind(controller),false);
		if (flow != null) {
			flow.observe('change', function(ev){
				var flow = $(Event.element(ev)).value;
				controller._currentSelectFlow = flow;
				controller._updateService();
			}.bind(controller),false);
		}
		this._afterInsertTargetElement = tgt;
	},
	_updateService: function() {
		this._crearAllSelect(this._targetSelect);
		var serviceId = this._currentSelectServiceId;
		var languages = new Array();
		var _this = this;
		$H(this._serviceButtonArray).each(function(item){
			languages.push($(item.value)._getMatchLangTgt(_this._currentSelectSourceLang, _this._currentSelectFlow));
			if (item.key == serviceId) {
				if ($(item.value).isSupportedLanguage(_this._currentSelectSourceLang, _this._currentSelectTargetLang, _this._currentSelectFlow)) {
					$(item.value).setButtonMode('active');
				} else {
					$(item.value).setButtonMode('hide');
					_this._currentSelectServiceId = '';
				}
			} else if ($(item.value).isSupportedLanguage(_this._currentSelectSourceLang, _this._currentSelectTargetLang, _this._currentSelectFlow)) {
				$(item.value).setButtonMode('display');
			} else {
				$(item.value).setButtonMode('hide');
			}
		}.bind(_this));
		languages = languages.flatten();
		var Pairs = new Array();
		if(languages.length){
			for(i=0;i<languages.length;i++){
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
			this._currentSelectServiceId = '';
		} else {
			this._currentSelectServiceId = serviceId;
		}

		this._updateService();
	},
	clearSelection: function() {
		this._currentSelectServiceId = '';
		this._currentSelectFlow = 'left';
		this._currentSelectTargetLang = '';
		this._updateService();
	},
	makeAndBindTranslationCombinationCheckbox: function(controller, handler) {
		var checkbox = document.createElement('input');
		checkbox.id = this._this_id + ':combination';
		checkbox.setAttribute('type', 'checkbox');
		checkbox.setAttribute('value', 'on');

		Event.observe(checkbox, 'click', handler.bindAsEventListener(controller, checkbox));

		return checkbox;
	},
	getSetting: function() {
		return this._currentSelectServiceId;
	}
};


//-----------------------------------------------------------------------------------------------------
var ServiceButton = Class.create();
ServiceButton.prototype = {
	__CSS_ON: 'setting_btn_on',
	__CSS_OFF:'setting_btn_off',
	_serviceInfo : null,
	_parentElem : null,
	_this_id : null,
	_buttonElem : null,
	_buttonArea : null,

	initialize: function() {
		// non;
	},
	makeServiceButton: function(parentElem, serviceInfo, clickHandler, controller) {
		this._parentElem = parentElem;
		this._serviceInfo = serviceInfo;
		this._this_id = parentElem.id + ':' + this._serviceInfo.service_id;

		var wrapper = document.createElement('div');
		wrapper.id = parentElem.id + ':btArea:' + this._serviceInfo.service_id;

		var radioElem = document.createElement('input');
		try {
		radioElem.setAttribute('type', 'radio');
		radioElem.setAttribute('name', parentElem.id + ':radio');
		radioElem.setAttribute('value', this._serviceInfo.service_id);
		} catch (e) {alert(e.toSource())}
		Event.observe(radioElem, 'click', clickHandler.bindAsEventListener(controller));

		var info = document.createElement('a');
		info.innerHTML = '[i]';
// 2010.01.12 mod IE8 bug fix.
//		info.observe('click', this._showPopup.bindAsEventListener(this));
		Event.observe(info, 'click', this._showPopup.bindAsEventListener(this));

		wrapper.appendChild(radioElem);
		wrapper.appendChild(document.createTextNode(this._serviceInfo.service_name));
		wrapper.appendChild(info);

		this._parentElem.appendChild(wrapper);

		this._buttonElem = radioElem;
		this._buttonArea = wrapper;
		return this._buttonElem;
	},

	setButtonMode: function(mode) {
		if (mode == 'active') {
			Element.removeClassName(this._buttonElem, this.__CSS_OFF);
			Element.addClassName(this._buttonElem, this.__CSS_ON);
			Element.show(this._buttonElem);
			this._buttonElem.setAttribute('checked', 'yes');
		} else if (mode == 'display') {
			Element.removeClassName(this._buttonElem, this.__CSS_ON);
			Element.addClassName(this._buttonElem, this.__CSS_OFF);
			Element.show(this._buttonArea);
			this._buttonElem.removeAttribute('checked');
		} else if (mode == 'hide') {
			Element.removeClassName(this._buttonElem, this.__CSS_ON);
			Element.addClassName(this._buttonElem, this.__CSS_OFF);
			Element.hide(this._buttonArea);
			this._buttonElem.removeAttribute('checked');
		}
	},
	isSupportedLanguage: function(src, tgt, flow) {
		var pairs = this._getSupportedLanguage();
		var ret = false;

		if (flow == 'both') {

			var comp = src + '2' + tgt;
			var revs = tgt + '2' + src;
			if (pairs.indexOf(comp) > -1 && pairs.indexOf(revs) > -1) {
				ret = true;
			}
		} else if (flow == 'left'){
			var comp = src + '2' + tgt;
			if (pairs.indexOf(comp) > -1) {
				ret = true;
			}
		} else {
			ret = false;
		}
		return ret;
	},
	isSupportedLanguageExt: function(src, tgt, flow) {
		var ret = false;

		if(src != '' && tgt != ''){
			var pairs = this._getSupportedLanguage();
			if (flow == 'both') {
				var comp = src + '2' + tgt;
				var revs = tgt + '2' + src;
				if (pairs.indexOf(comp) > -1 && pairs.indexOf(revs) > -1) {
					ret = true;
				}
			} else if (flow == 'left'){
				var comp = src + '2' + tgt;
				if (pairs.indexOf(comp) > -1) {
					ret = true;
				}
			}else{
				ret = false;
			}
		} else if (src != '' || tgt != '') {
			var pairs = ","+this._serviceInfo.supported_languages_paths+",";
			if(src != ''){var skey = ","+src+"2";}
			if(tgt != ''){var skey = "2"+tgt+",";}

			if (pairs.indexOf(skey) > -1) {
				ret = true;
			}
			if (flow == 'both') {
				if(src != ''){var r_skey = "2"+src+",";}
				if(tgt != ''){var r_skey = ","+tgt+"2";}
				if (pairs.indexOf(r_skey) > -1) {
					ret = true;
				}
			}
		} else {
			ret = false;
		}
		return ret;
	},
	_getMatchLangTgt: function(src, flow) {
		var ret = Array();
		var skey =new RegExp("^" + src + "2.+$");
		var r_skey =new RegExp("^.+2" + src + "$");

		var pairs = this._getSupportedLanguage();
		for(i=0;i<pairs.length;i++){
			if(pairs[i].match(skey)) {
				var tmp = pairs[i].split("2");
				if(tmp[1] != src){
					ret.push(tmp[1]);
				}
			}else{
				if (flow == 'both') {
					if(pairs[i].match(r_skey)) {
						var tmp = pairs[i].split("2");
						if(tmp[1] != src){
							ret.push(tmp[1]);
						}
					}
				}
			}
		}
		return ret;
	},
	_getSupportedLanguage: function() {
		var pathTokens = this._serviceInfo.supported_languages_paths.split(',');
		return pathTokens;
	},

	_showPopup: function(ev) {
		Event.stop(ev);
		var infobtn = Event.element(ev);
		var pos = infobtn.cumulativeOffset();

		var top = pos[1];
		var left = pos[0] + 20;

		var balElem = $('baloon-' + this._serviceInfo.service_id);
		if(balElem.style.display == 'none'){
			$$(".popnowopen").each(function(ele){
				Element.removeClassName(ele,"popnowopen");
				Element.hide(ele);
			});
			Element.addClassName(balElem,"popnowopen");
			Element.show(balElem);

			var vp = document.viewport.getDimensions();
			var vp_sc = document.viewport.getScrollOffsets();

			if((top + balElem.offsetHeight - vp_sc.top) > vp.height){
				top = vp.height - balElem.offsetHeight + vp_sc.top;
			}

			if((left + balElem.offsetWidth - vp_sc.left) > vp.width){
				if((left - 30) > balElem.offsetWidth){
					left = left - 30 - balElem.offsetWidth;
				}else{
					left = vp.width - balElem.offsetWidth + vp_sc.left;
				}
			}

			balElem.setStyle('position:absolute; left:'+left+'px; top:'+top+'px');

		}else{
			Element.removeClassName(balElem,"popnowopen");
			Element.hide(balElem);
		}
	},

	_hidePopup: function(ev) {
		Event.stop(ev);
		var balElem = $('baloon-' + this._serviceInfo.service_id);
		balElem.hide();
	}
};

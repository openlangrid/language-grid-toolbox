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

var AbstractSingletonPane = Class.create();
AbstractSingletonPane.prototype = {

	initialize: function(table) {
		if($('singleton-pane')) {
			$('singleton-pane').remove();
		}
		if($('mask-pane')) {
			$('mask-pane').remove();
		}
		
		this.paneID = 'singleton-pane';
		this.maskID = 'mask-pane';
		var pane = '<div class="input-box" id="' + this.paneID + '" style="z-index:10;"></div>';
		pane += '<div id="' + this.maskID + '" style="z-index:5;" class="pop-mask"></div>';
		this.table = table;
		try {
			new Insertion.Bottom($$('body')[0], pane);
			this.hidePane();
		} catch(e) {
			;
		}
	},

	showPane : function(x, y) {

		if (this.notShowPane()) {
			return false;
		}

		$(this.paneID).setStyle({
			position: 'absolute' ,
			left : x + 'px' ,
			top : y + 'px'
		});

		$(this.paneID).innerHTML = this.getPane();
		$(this.paneID).show();
		this.onShowPane();
	},

	setStatus : function(message, index) {
		var index = index || 0;
		$('singleton-pane-status-' + index).innerHTML = message;
	},

	notShowPane : function() {
		return false;
	},

	onShowPane : function() {
		return;
	},

	getPane : function() {
		return;
	},

	submit : function() {
		return;
	},

	hidePane: function(){
		$(this.paneID).hide();
		$(this.maskID).hide();
	}
};
//-------------------------------------------------------------------------------------------------------
var DictionaryPanel = Class.create();
var popupDictionaryPanel = null;
DictionaryPanel.prototype = Object.extend(new AbstractSingletonPane(), {
	_level: null,
	_fix: null,
	_DictInfoArray: null,
	_AnalyzerInfoArray: null,
	_langs: null,
	_cur_global_dics: null,
	_cur_local_dics: null,
	_cur_temp_dics: null,
	_cur_morph_from: null,
	_cur_morph_to: null,
	_defaultSettings: null,
	_controller: null,
	_mode:null,
	_DictFlag:null,
	_seperator: '#',

	showPane : function(mode) {
		this._mode = mode;
		if (this.notShowPane()) {return false;}

		var vp = document.viewport.getDimensions();
		var vp_sc = document.viewport.getScrollOffsets();

		var mdiv = $(this.maskID);
		var value_opacity = 4;
		mdiv.style.filter = 'alpha(opacity=' + (value_opacity * 10) + ')';
		mdiv.style.MozOpacity = value_opacity / 10;
		mdiv.style.opacity = value_opacity / 10;
		
		mdiv.show();
		mdiv.setStyle({position: 'absolute',top:vp_sc.top+'px',left:vp_sc.left+'px',width: vp.width + 'px',height: vp.height + 'px'});
		
		Event.observe(window, "scroll",this._onScrollWin.bind(this),false);
		Event.observe(window, "resize",this._onResizeWin.bind(this),false);
		
		Element.update(this.paneID, this.getPane());
		$(this.paneID).show();

		var left = ((vp.width - $("popwin").offsetWidth) / 2) + vp_sc.left;
		var top = ((vp.height - $("popwin").offsetHeight) / 2) + vp_sc.top;
		
		if((top + $("popwin").offsetHeight - vp_sc.top) > vp.height){
			top = vp.height - $("popwin").offsetHeight + vp_sc.top;
		}

		if((left + $("popwin").offsetWidth - vp_sc.left) > vp.width){
			left = vp.width - $("popwin").offsetWidth + vp_sc.left;
		}
		
		$(this.paneID).setStyle({position: 'absolute' ,left : left + 'px' ,top : top + 'px'});
		
		this.onShowPane();
	},
	hidePane: function(){
		Event.stopObserving(window, "scroll",this._onScrollWin.bind(this),false);
		Event.stopObserving(window, "resize",this._onResizeWin.bind(this),false);
		
		$(this.paneID).hide();
		$(this.maskID).hide();
	},
	_onScrollWin:function(ev){
		var vp_sc = document.viewport.getScrollOffsets();
		$(this.maskID).setStyle({top:vp_sc.top+'px',left:vp_sc.left+'px'});
	},
	_onResizeWin:function(ev){
		var vp = document.viewport.getDimensions();
		$(this.maskID).setStyle({width: vp.width + 'px',height: vp.height + 'px'});
	},
	setParams: function(level,fix,DictsArray,AnalysesArray,langs, global_dict,local_dict,temp_dict,morph_from,morph_to,defaulsetings,controller) {
		this._level = level;
		this._fix = fix;
		this._DictInfoArray = DictsArray;
		this._AnalyzerInfoArray = AnalysesArray;
		this._langs = langs;
		this._cur_global_dics = global_dict;
		this._cur_local_dics = local_dict;
		this._cur_temp_dics = temp_dict;
		this._cur_morph_from = morph_from;
		this._cur_morph_to = morph_to;
		this._defaultSettings = defaulsetings;
		this._controller = controller;
		if(fix != 'default'){
			this._DictFlag = controller._dict_flags[level];
			if(!controller.isSupportedAnalyzer(this._cur_morph_from,langs.lang1)){
				this._cur_morph_from = controller.getDefaultAnalyzer(langs.lang1);
			}
			if(!controller.isSupportedAnalyzer(this._cur_morph_to,langs.lang2)){
				this._cur_morph_to = controller.getDefaultAnalyzer(langs.lang2);
			}
		}
	},
	getPane : function() {
		var html = new Array();
		var dictSvs = new Array();
		var userDictSvs = new Array();
		
		html.push('<div class="dic-subwindow-border" id="popwin">');
		html.push('<div class="dictionary-body">');
		
		html.push('<div class="tab_on" id="dic_title" onclick="popupDictionaryPanel.onChangeTab(0);">');
		html.push(Const.Popup.Title);
		if(this._fix == "default"){
			html.push('('+Const.Label.Default+')');
		}
		html.push('</div>');

		if(this._fix != "default"){
			html.push('<div class="tab_off" id="morph_title" onclick="popupDictionaryPanel.onChangeTab(1);">');
			html.push(Const.Popup.MA_Title+'</div>');
		}

		//------------Dictionary-----------------------------
		html.push('<div class="main_contents" id="dict_body">');
		//--------global dictionaly --------
		html.push('<h2>'+Const.Popup.Label1+'</h2>');
		var _this = this;
		var SelectedCnt = 0;
		this._DictInfoArray.each(function(service, index){
			if (service.service_type == 'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH') {
				var sid = _this._fix +this._seperator+ service.service_id;
				var html_str = '';
				html_str += '<div class="clearfix">';
				html_str += '<label for="'+sid+this._seperator + 'GLOBAL">';
				
				if(this._mode != "view"){
					html_str += '<input type="checkbox" id="'+sid+this._seperator + 'GLOBAL" value="'+sid+'"';
					html_str += ' onclick="popupDictionaryPanel.onChangeVal(this);"';
					//if(this._mode == "view"){html_str += ' disabled="true" ';}
					
					if (_this._cur_global_dics.indexOf(service.service_id) > -1) {
						html_str += ' checked="yes" ';
						dictSvs.push(service.service_id);
					}
					html_str += '/>';
					html_str += '<span style="margin-left:2px;">'+service.service_name+'</span>';
				}else{
					if (_this._cur_global_dics.indexOf(service.service_id) > -1) {
						html_str += '<input type="checkbox" checked="yes" disabled="true" />';
						html_str += '<span style="margin-left:2px;">'+service.service_name+'</span>';
						SelectedCnt++;
					}
				}
				html_str += '</label></div>';
				
				html.push(html_str);
			}
		}.bind(this));
		if(this._mode == "view" && SelectedCnt == 0){
			html.push(Const.Message.NoSelectedDict);
		}
		//--------local dictionaly --------
		html.push('<h2>'+Const.Popup.Label3+'</h2>');
		var _this = this;
		var DictCnt = 0;
		var SelectedCnt = 0;
		this._DictInfoArray.each(function(service, index){
			if (service.service_type == 'IMPORTED_DICTIONARY') {
				var sid = _this._fix +this._seperator+ service.service_id;
				var html_str = '';
				DictCnt++;
				html_str += '<div class="clearfix">';
				html_str += '<label for="'+sid+this._seperator + 'LOCAL">';
				
				if(this._mode != "view"){
					html_str += '<input type="checkbox" id="'+sid+this._seperator + 'LOCAL" value="'+sid+'"';
					html_str += ' onclick="popupDictionaryPanel.onChangeVal(this);"';
					//if(this._mode == "view"){html_str += ' disabled="true" ';}
					
					if (_this._cur_local_dics.indexOf(service.service_id) > -1) {
						html_str += ' checked="yes" ';
						dictSvs.push(service.service_id);
					}
					html_str += '/>';
					html_str += '<span style="margin-left:2px;">'+service.service_name+'</span>';
				}else{
					if (_this._cur_local_dics.indexOf(service.service_id) > -1) {
						html_str += '<input type="checkbox" checked="yes" disabled="true" />';
						html_str += '<span style="margin-left:2px;">'+service.service_name+'</span>';
						SelectedCnt++;
					}
				}
				html_str += '</label></div>';
				
				html.push(html_str);
			}
		}.bind(this));
		if(this._mode == "view" && SelectedCnt == 0){
			html.push(Const.Message.NoSelectedLocalDict);
		}else{
			if(DictCnt == 0){
				html.push(Const.Message.NoDictionaryLocal);
			}
		}
		//--------user dictionaly --------
		html.push('<h2>'+Const.Popup.Label2+'</h2>');
		var DictCnt = 0;
		var U_SelectedCnt = 0;
		this._DictInfoArray.each(function(service, index){
			if (service.service_type == 'USER_DICTIONARY') {
				var sid = _this._fix +this._seperator+ service.service_id;
				var html_str = '';
				DictCnt++;
				html_str += '<div class="clearfix">';
				html_str += '<label for="'+sid+this._seperator + 'USER">';
				
				if(this._mode != "view"){
					html_str += '<input type="checkbox" id="'+sid+this._seperator + 'USER" value="'+sid+'"';
					html_str += ' onclick="popupDictionaryPanel.onChangeVal(this);"';
					//if(this._mode == "view"){html_str += ' disabled="true" ';}
					
					if (_this._cur_temp_dics.indexOf(service.service_id) > -1) {
						html_str += ' checked="yes" ';
						userDictSvs.push(service.service_id);
					}
					html_str += '/>';
					html_str += '<span style="margin-left:2px;">'+service.service_name+'</span>';
				}else{
					if (_this._cur_temp_dics.indexOf(service.service_id) > -1) {
						html_str += '<input type="checkbox" checked="yes" disabled="true" />';
						html_str += '<span style="margin-left:2px;">'+service.service_name+'</span>';
						U_SelectedCnt++;
					}
				}
				html_str += '</label></div>';
				
				html.push(html_str);
			}
		}.bind(this));
		if(this._mode == "view" && U_SelectedCnt == 0){
			html.push(Const.Message.NoSelectedUserDict);
		}else{
			if(DictCnt == 0){
				html.push(Const.Message.NoDictionaryTemporal);
			}
		}
		html.push('</div>');
		
		if(this._fix != "default"){
			//------------Morphological Analyzer-----------------------------
			html.push('<div class="main_contents" id="morph_body" style="display:none;">');
			html.push('<h2>'+this._langs.langname1+'</h2>');
			var _this = this;
			var SelectedCnt = 0;
			this._AnalyzerInfoArray.each(function(service, index){
				var supported_langs = new Array();
				supported_langs = service.supported_languages_paths.split(",");
				if (service.service_type == 'MORPHOLOGICALANALYSIS' && supported_langs.indexOf(_this._langs.lang1) > -1) {
					var sid = _this._fix +this._seperator+ service.service_id;
					var html_str = '';
					html_str += '<div class="clearfix">';
					html_str += '<label for="'+sid+this._seperator + 'M_SRC">';
					
					if(this._mode != "view"){
						html_str += '<input type="radio" id="'+sid+this._seperator + 'M_SRC" value="'+sid+'" name="M_SRC"';
						//if(this._mode == "view"){html_str += ' disabled="true" ';}
						
						if (_this._cur_morph_from == service.service_id) {
							html_str += ' checked="yes" ';
						}
						html_str += '/>';
						html_str += '<span style="margin-left:2px;">'+service.service_name+'</span>';
						SelectedCnt++;
					}else{
						if (_this._cur_morph_from == service.service_id) {
							html_str += '<input type="radio" checked="yes" disabled="true" />';
							html_str += '<span style="margin-left:2px;">'+service.service_name+'</span>';
							SelectedCnt++;
						}
					}
					html_str += '</label></div>';
					
					html.push(html_str);
				}
			}.bind(this));
			if(SelectedCnt == 0){
				if(this._mode == "view"){
					html.push('--');
				}else{
					html.push('--');
				}
			}
			html.push('<h2>'+this._langs.langname2+'</h2>');
			var _this = this;
			var SelectedCnt = 0;
			this._AnalyzerInfoArray.each(function(service, index){
				var supported_langs = new Array();
				supported_langs = service.supported_languages_paths.split(",");
				if (service.service_type == 'MORPHOLOGICALANALYSIS' && supported_langs.indexOf(_this._langs.lang2) > -1) {
					var sid = _this._fix +this._seperator+ service.service_id;
					var html_str = '';
					html_str += '<div class="clearfix">';
					html_str += '<label for="'+sid+this._seperator + 'M_TGT">';
					
					if(this._mode != "view"){
						html_str += '<input type="radio" id="'+sid+this._seperator + 'M_TGT" value="'+sid+'" name="M_TGT"';
						//if(this._mode == "view"){html_str += ' disabled="true" ';}
						
						if (_this._cur_morph_to == service.service_id) {
							html_str += ' checked="yes" ';
						}
						html_str += '/>';
						html_str += '<span style="margin-left:2px;">'+service.service_name+'</span>';
						SelectedCnt++;
					}else{
						if (_this._cur_morph_to == service.service_id) {
							html_str += '<input type="radio" checked="yes" disabled="true" />';
							html_str += '<span style="margin-left:2px;">'+service.service_name+'</span>';
							SelectedCnt++;
						}
					}
					html_str += '</label></div>';
					
					html.push(html_str);
				}
			}.bind(this));
			if(SelectedCnt == 0){
				if(this._mode == "view"){
					html.push('--');
				}else{
					html.push('--');
				}
			}
			//------------Morphological Analyzer(end)-----------------------------
		}
		html.push('</div>');


		html.push('<div style="margin-top: 8px;">');
		if(this._mode == "view"){
			html.push('<a class="btn" style="width: 140px; margin-left: 330px;" onclick="popupDictionaryPanel.hidePane();">');
			html.push('<img src="'+Const.Images.Close+'" />'+Const.Label.CloseBtn+'</a> ');
		}else{
			html.push('<div style="float:left;">');
			if(this._fix != "default"){
				html.push(' <a class="btn" style="width: 140px;" onclick="popupDictionaryPanel.onLoadDefault();" id="DEFAULT_BTN">');
				html.push(Const.Popup.LoadDefault +'</a>');
			}
			html.push(' </div>');
			html.push('<div style="float:right; margin-right:8px;"> ');
			html.push('<a class="btn" onclick="popupDictionaryPanel.hidePane();">');
			html.push('<img src="'+Const.Images.Cancel+'" />'+Const.Popup.BTN_CANCEL+'</a> ');
			html.push('<a class="btn" onclick="popupDictionaryPanel._onSubmit();">');
			html.push('<img src="'+Const.Images.SaveOn+'" />'+Const.Label.Save+'</a></div>');
			html.push('</div>');
		}
		html.push('<br class="clear" />');
		html.push('</div>');
		
		//html.push('<div id="def_status"></div>');
		
		html.push('</div>');

		return html.join('');
	},
	_onSubmit: function() {
		this._controller.clearSelectDictionary(this._level);
		var _ctrl = this._controller;
		var ChkCnt1 = 0;
		var ChkCnt2 = 0;
		$$('#singleton-pane input').each(function(obj, index) {
			var id = obj.id;
			var token = id.split(this._seperator);
			if (token.length != 3) {
				alert('Error:Element-ID='+id);
			} else {
				_ctrl.onDictionaryClickHandler(this._level,token[1], obj.checked, token[2]);
				if(token[2] == "M_SRC" && obj.checked){ChkCnt1++;}
				if(token[2] == "M_TGT" && obj.checked){ChkCnt2++;}
			}
		}.bind(this));
		if(this._fix == "default"){
			_ctrl.onDictionarySaveHandler();
		}else{
			if(ChkCnt1 == 0){_ctrl._morphemes_from[this._level] = '';}
			if(ChkCnt2 == 0){_ctrl._morphemes_to[this._level] = '';}
			
			_ctrl.updateDictionaryFlag(this._level,this._DictFlag);
			_ctrl.updateMorphologicalAnalyzer(this._level);
			document.fire('dom:settingEditingThis');
		}
		this.hidePane();
	},
	onLoadDefault:function(){
		$$('#singleton-pane input').each(function(obj, index) {
			var id = obj.id;
			//token = prefix:service_id:type
			var token = id.split(this._seperator);
			if (token.length != 3) {
				obj.checked = false;
			} else {
				switch(token[2]){
					case "GLOBAL":
						if(this._defaultSettings.global.indexOf(token[1]) > -1){
							obj.checked = true;
						}else{
							obj.checked = false;
						}
						break;
					case "LOCAL":
						if(this._defaultSettings.local.indexOf(token[1]) > -1){
							obj.checked = true;
						}else{
							obj.checked = false;
						}
						break;
					case "USER":
						if(this._defaultSettings.temp.indexOf(token[1]) > -1){
							obj.checked = true;
						}else{
							obj.checked = false;
						}
						break;
					case "M_SRC":
						if(this._defaultSettings.morph_from == token[1]){
							obj.checked = true;
						}
						break;
					case "M_TGT":
						if(this._defaultSettings.morph_to == token[1]){
							obj.checked = true;
						}
						break;
				}
				//_ctrl.onDictionaryClickHandler(token[1], obj.checked, token[2]);
			}
		}.bind(this));
		this._DictFlag = 1;
	},
	onChangeVal:function(evobj){
		var DictCount = 0;
		$$('#singleton-pane input').each(function(obj, index) {
			var id = obj.id;
			var token = id.split(this._seperator);
			if (token.length != 3) {
				obj.checked = false;
			} else {
				switch(token[2]){
					case "GLOBAL":
						if(obj.checked){DictCount++;}
						break;
					case "LOCAL":
						if(obj.checked){DictCount++;}
						break;
					case "USER":
						//if(obj.checked){DictCount++;}
						break;
				}
			}
		}.bind(this));
		
		if(DictCount > Const.DEFINE.MaxDicts){
			evobj.checked = false;

			var alert_msg = Const.Message.OverMaxDictCount;
			alert_msg = alert_msg.replace("%MAX",Const.DEFINE.MaxDicts);
			
			alert(alert_msg);
		}else{
			this._DictFlag = 2;
		}
	},
	onChangeTab:function(id){
		if(id == 0){
			if(Element.hasClassName($('dic_title'),'tab_off')){
				Element.removeClassName($('dic_title'),"tab_off");
				Element.addClassName($('dic_title'),"tab_on");
				$('dict_body').show();
				
				Element.removeClassName($('morph_title'),"tab_on");
				Element.addClassName($('morph_title'),"tab_off");
				$('morph_body').hide();
				
				if($('DEFAULT_BTN')){
					$('DEFAULT_BTN').show();
				}
			}
		}else{
			if(Element.hasClassName($('morph_title'),'tab_off')){
				Element.removeClassName($('morph_title'),"tab_off");
				Element.addClassName($('morph_title'),"tab_on");
				$('morph_body').show();
				
				Element.removeClassName($('dic_title'),"tab_on");
				Element.addClassName($('dic_title'),"tab_off");
				$('dict_body').hide();

				if($('DEFAULT_BTN')){
					$('DEFAULT_BTN').hide();
				}
			}
		}
	},
	__dump: function() {
		alert(this._controller.source());
	}
});

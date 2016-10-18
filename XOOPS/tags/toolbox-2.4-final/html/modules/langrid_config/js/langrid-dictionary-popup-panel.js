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
/* $Id: langrid-dictionary-popup-panel.js 6005 2011-09-05 07:02:45Z mtanaka $ */

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
	_cur_similarity: null,
	_cur_parallel_texts: null,
	_cur_translation_templates: null,
	_defaultSettings: null,
	_controller: null,
	_mode:null,
	_DictFlag:null,
	_both: null,
	_separator: "#", 
	
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
	
	hidePane: function() {
		Event.stopObserving(window, "scroll", this._onScrollWin.bind(this),false);
		Event.stopObserving(window, "resize", this._onResizeWin.bind(this),false);

		$(this.paneID).hide();
		$(this.maskID).hide();
	},
	
	_onScrollWin: function(ev) {
		var vp_sc = document.viewport.getScrollOffsets();
		$(this.maskID).setStyle({top:vp_sc.top+'px',left:vp_sc.left+'px'});
	},
	
	_onResizeWin: function(ev) {
		var vp = document.viewport.getDimensions();
		$(this.maskID).setStyle({width: vp.width + 'px',height: vp.height + 'px'});
	},
	
	setParams: function(level, fix, DictsArray, AnalysesArray, langs, global_dict,local_dict,temp_dict,morph_from,morph_to,defaulsetings,controller, similarity, parallelTexts, translationTemplates) {
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
		this._cur_similarity = similarity;
		this._cur_parallel_texts = parallelTexts;
		this._cur_translation_templates = translationTemplates;
		
		if (fix != 'default') {
			this._DictFlag = controller._dict_flags[level];
			
			if(!controller.isSupportedAnalyzer(this._cur_morph_from,langs.lang1)){
				this._cur_morph_from = controller.getDefaultAnalyzer(langs.lang1);
				if (!this._cur_morph_from) {
					this._AnalyzerInfoArray.each(function(service, index){
						var supportedLangs = service.supported_languages_paths.split(",");
						if (service.service_type == 'MORPHOLOGICALANALYSIS' && supportedLangs.indexOf(this._langs.lang1) > -1) {
							this._cur_morph_from = service.service_id;
							throw $break;
						}
					}.bind(this));
				}
			}
			
			if(!controller.isSupportedAnalyzer(this._cur_morph_to,langs.lang2)){
				this._cur_morph_to = controller.getDefaultAnalyzer(langs.lang2);
				if (!this._cur_morph_to) {
					this._AnalyzerInfoArray.each(function(service, index){
						var supportedLangs = service.supported_languages_paths.split(",");
						if (service.service_type == 'MORPHOLOGICALANALYSIS' && supportedLangs.indexOf(this._langs.lang2) > -1) {
							this._cur_morph_to = service.service_id;
							throw $break;
						}
					}.bind(this));
				}
			}
		}
	},
	
	getPane : function() {
		var html = [];

		html.push('<div class="dic-subwindow-border" id="popwin">');
		html.push('<div class="dictionary-body">');

		html.push('<div class="tab_on" id="dic_title" onclick="popupDictionaryPanel.onChangeTab(0);">');
		html.push(Const.Popup.Title);
		
		if (this._fix == "default") {
			html.push('('+Const.Label.Default+')');
		}
		
		html.push('</div>');

		if (this._fix != "default") {
			html.push('<div class="tab_off" id="morph_title" onclick="popupDictionaryPanel.onChangeTab(1);">');
			html.push(Const.Popup.MA_Title+'</div>');
			
			html.push('<div class="tab_off" id="parallel_title" onclick="popupDictionaryPanel.onChangeTab(2);">');
			html.push(Resource.PARALLEL_TEXT+'</div>');
			
			html.push('<div style="display:none;" class="tab_off" id="similarity_title" onclick="popupDictionaryPanel.onChangeTab(3);">');
			html.push(Resource.BEST_SELECTION+'</div>');
		}

		html.push('<div class="main_contents" id="dict_body">');
		
		this.addGlobalDictionary(html);
		this.addLocalDictionary(html);
		this.addUserDictionary(html);

		if (this._fix != "default") {
			this.addMorphologicalAnalyzer(html);
			this.addParallelText(html);
			this.addSimilaritySelection(html);
		}
		
		//html.push('</div>');
		
		this.addFooter(html);

		return html.join('');
	},
	
	addGlobalDictionary: function(html) {
		html.push('<h2>' + Const.Popup.Label1 + '</h2>');
		
		var SelectedCnt = 0;
		this._DictInfoArray.each(function(service, index) {
			if (service.service_type == 'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH' && service.allowed_app_provision != 'IMPORTED') {
				var sid = this._fix + this._separator + service.service_id;
				
				html.push('<div class="clearfix">');
				html.push('<label for="'+sid+this._separator+'GLOBAL">');

				if (this._mode != "view") {
					html.push('<input type="checkbox" id="'+sid+this._separator+'GLOBAL" value="'+sid+'"');
					html.push(' onclick="popupDictionaryPanel.onChangeVal(this);"');
					if (this._cur_global_dics.indexOf(service.service_id) > -1) {
						html.push(' checked="yes" ');
					}
					html.push('/>');
					html.push('<span style="margin-left:2px;">'+service.service_name+'</span>');
				} else {
					if (this._cur_global_dics.indexOf(service.service_id) > -1) {
						html.push('<input type="checkbox" checked="yes" disabled="true" />');
						html.push('<span style="margin-left:2px;">'+service.service_name+'</span>');
						SelectedCnt++;
					}
				}
				
				html.push('</label></div>');
			}
		}.bind(this));
		
		if (this._mode == "view" && SelectedCnt == 0) {
			html.push(Const.Message.NoSelectedDict);
		}
	},
	
	addLocalDictionary: function(html) {
		html.push('<h2>' + Const.Popup.Label3 + '</h2>');
		
		var DictCnt = 0;
		var SelectedCnt = 0;
		this._DictInfoArray.each(function(service, index) {
			if (service.service_type == 'IMPORTED_DICTIONARY' || (service.service_type == 'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH' && service.allowed_app_provision == 'IMPORTED')) {
				var sid = this._fix +this._separator+ service.service_id;
				DictCnt++;
				html.push('<div class="clearfix">');
				html.push('<label for="'+sid+this._separator+'LOCAL">');

				if (this._mode != "view") {
					html.push('<input type="checkbox" id="'+sid+this._separator+'LOCAL" value="'+sid+'"');
					html.push(' onclick="popupDictionaryPanel.onChangeVal(this);"');
					if (this._cur_local_dics.indexOf(service.service_id) > -1) {
						html.push(' checked="yes" ');
						//dictSvs.push(service.service_id);
					}
					html.push('/>');
					html.push('<span style="margin-left:2px;">'+service.service_name+'</span>');
				} else {
					if (this._cur_local_dics.indexOf(service.service_id) > -1) {
						html.push('<input type="checkbox" checked="yes" disabled="true" />');
						html.push('<span style="margin-left:2px;">'+service.service_name+'</span>');
						SelectedCnt++;
					}
				}
				html.push('</label></div>');
			}
		}.bind(this));
		
		if (this._mode == "view" && SelectedCnt == 0) {
			html.push(Const.Message.NoSelectedLocalDict);
		} else if (DictCnt == 0) {
			html.push(Const.Message.NoDictionaryLocal);
		}
	},
	
	addUserDictionary: function(html) {
		html.push('<h2>' + Const.Popup.Label2 + '</h2>');
		
		var DictCnt = 0;
		var U_SelectedCnt = 0;
		this._DictInfoArray.each(function(service, index){
			if (service.service_type == 'USER_DICTIONARY') {
				var sid = this._fix +this._separator+ service.service_id;
				DictCnt++;
				html.push('<div class="clearfix">');
				html.push('<label for="'+sid+this._separator+'USER">');

				if(this._mode != "view"){
					html.push('<input type="checkbox" id="'+sid+this._separator+'USER" value="'+sid+'"');
					html.push(' onclick="popupDictionaryPanel.onChangeVal(this);"');

					if (this._cur_temp_dics.indexOf(service.service_id) > -1) {
						html.push(' checked="yes" ');
						//userDictSvs.push(service.service_id);
					}
					html.push('/>');
					html.push('<span style="margin-left:2px;">'+service.service_name+'</span>');
				}else{
					if (this._cur_temp_dics.indexOf(service.service_id) > -1) {
						html.push('<input type="checkbox" checked="yes" disabled="true" />');
						html.push('<span style="margin-left:2px;">'+service.service_name+'</span>');
						U_SelectedCnt++;
					}
				}
				html.push('</label></div>');
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
	},
	
	// TODO: リファクタリング（言語ごとに作ってるのをまとめる）
	addMorphologicalAnalyzer: function(html) {
		html.push('<div class="main_contents" id="morph_body" style="display:none;">');
		html.push('<h2>' + this._langs.langname1 + '</h2>');
		
		var SelectedCnt = 0;
		this._AnalyzerInfoArray.each(function(service, index) {
			var supported_langs = [];
			supported_langs = service.supported_languages_paths.split(",");
			if (service.service_type == 'MORPHOLOGICALANALYSIS' && supported_langs.indexOf(this._langs.lang1) > -1) {
				var sid = this._fix +this._separator+ service.service_id;
				html.push('<div class="clearfix">');
				html.push('<label for="'+sid+this._separator+'M_SRC">');

				if(this._mode != "view"){
					html.push('<input type="radio" id="'+sid+this._separator+'M_SRC" value="'+sid+'" name="M_SRC"');
					if (this._cur_morph_from == service.service_id) {
						html.push(' checked="yes" ');
					}
					html.push('/>');
					html.push('<span style="margin-left:2px;">'+service.service_name+'</span>');
					SelectedCnt++;
				} else {
					if (this._cur_morph_from == service.service_id) {
						html.push('<input type="radio" checked="yes" disabled="true" />');
						html.push('<span style="margin-left:2px;">'+service.service_name+'</span>');
						SelectedCnt++;
					}
				}
				html.push('</label></div>');
			}
		}.bind(this));
		
		if (SelectedCnt == 0) {
			html.push('--');
		}
		
		html.push('<h2>' + this._langs.langname2 + '</h2>');
		
		var SelectedCnt = 0;
		this._AnalyzerInfoArray.each(function(service, index){
			var supported_langs = [];
			supported_langs = service.supported_languages_paths.split(",");
			if (service.service_type == 'MORPHOLOGICALANALYSIS' && supported_langs.indexOf(this._langs.lang2) > -1) {
				var sid = this._fix +this._separator+ service.service_id;
				html.push('<div class="clearfix">');
				html.push('<label for="'+sid+this._separator+'M_TGT">');

				if  (this._mode != "view"){
					html.push('<input type="radio" id="'+sid+this._separator+'M_TGT" value="'+sid+'" name="M_TGT"');

					if (this._cur_morph_to == service.service_id) {
						html.push(' checked="yes" ');
					}
					html.push('/>');
					html.push('<span style="margin-left:2px;">'+service.service_name+'</span>');
					SelectedCnt++;
				} else {
					if (this._cur_morph_to == service.service_id) {
						html.push('<input type="radio" checked="yes" disabled="true" />');
						html.push('<span style="margin-left:2px;">'+service.service_name+'</span>');
						SelectedCnt++;
					}
				}
				html.push('</label></div>');
			}
		}.bind(this));
		
		if (SelectedCnt == 0) {
			html.push('--');
		}
		
		html.push('</div>');
	},
	
	addParallelText: function(html) {
		html.push('<div class="main_contents" id="parallel_body" style="display:none;">');
		
		var enabled = (this._controller.hasEBMT(this._level)) && (this._mode != 'view'); 
		
		if (!this._controller.hasEBMT(this._level)) {
			html.push('<p style="color: #f00;">' + Resource.PARALLEL_TEXT_WARNING + '</p>');
		}
		
		html.push('<h2>' + Resource.PARALLEL_TEXT + '</h2>');

		var parallelTexts = LangridServices.getParallelTexts(this._langs.lang1, this._langs.lang2);
		
		if (parallelTexts.length === 0) {
			html.push('<p style="color: #f00;">' + Resource.PARALLEL_TEXT_NOT_FOUND + '</p>');
		}
		
		var count = 0;
		parallelTexts.each(function(service, index){
			var sid = this._fix +this._separator+ service.service_id;
			
			var checked = (this._cur_parallel_texts.indexOf(service.service_id) != -1) ? ' checked="checked" ' : '';
			var disabled = (!enabled) ? ' disabled="disabled" ' : '';
			html.push('<label>');
			html.push('<input type="checkbox" ' + checked + disabled + ' id="' + sid + this._separator + 'PARALLELTEXT" value="'+sid+'" />');
			html.push('<span style="margin-left:2px;">' + service.service_name + '</span>');
			html.push('</label>');
			html.push('<br />');
			count++;
		}.bind(this));
		
		if (this._mode == 'view' && count == 0) {
			html.push('--');
		}
		
		this.addTranslationTemplates(html);
		
		html.push('</div>');
	},
	
	addTranslationTemplates: function(html) {
		html.push('<h2>' + Resource.BLANK_PARALLEL_TEXT + '</h2>');
		
		var enabled = (this._controller.hasEBMT(this._level)) && (this._mode != 'view'); 
		
		var translationTemplates = LangridServices.getTranslationTemplates(this._langs.lang1, this._langs.lang2);
		
		if (translationTemplates.length === 0) {
			html.push('<p style="color: #f00;">' + Resource.BLANK_PARALLEL_TEXT + '</p>');
		}
		
		var count = 0;
		translationTemplates.each(function(service, index){
			var sid = this._fix +this._separator+ service.service_id;
			
			var checked = (this._cur_translation_templates.indexOf(service.service_id) != -1) ? ' checked="checked" ' : '';
			var disabled = (!enabled) ? ' disabled="disabled" ' : '';
			html.push('<label>');
			html.push('<input type="checkbox" ' + disabled + checked + ' id="' + sid + this._separator + 'TRANSLATION_TEMPLATE" value="'+sid+'" />');
			html.push('<span style="margin-left:2px;">' + service.service_name + '</span>');
			html.push('</label>');
			html.push('<br />');
			count++;
		}.bind(this));
		
//		if (this._mode == 'view' && count == 0) {
//			html.push('--');
//		}
	},
	
	addSimilaritySelection: function(html) {
		html.push('<div style="display:none;" class="main_contents" id="similarity_body" style="display:none;">');
		
		var services = LangridServices.getSimilarityCalculations(this._langs.lang1, this._langs.lang2, this._both);
		
		if (services.length === 0) {
			html.push('<p style="color: #f00;">' + Resource.SIMILARITY_CALCURATION_NOT_FOUND + '</p>');
			html.push('</div>');
			return;
		}
		
		var count = this._controller.getTranslators(this._level).length;
		var enabled = (count >= 2) && (this._mode != 'view'); 
		
		if (count < 2) {
			html.push('<p style="color: #f00;">' + Resource.SIMILARITY_CALCURATION_WARNING + '</p>');
		}

		if (!this._cur_similarity) {
			this._cur_similarity = services[0].service_id;
		}

		html.push('<h2>' + Resource.SIMILARITY_CALCURATION + '</h2>');
		
		var disabled = (!enabled) ? ' disabled="disabled" ' : '';
		services.each(function(s) {
			var id = this._fix + this._separator + s.service_id;
			
			var checked = (this._cur_similarity == s.service_id) ? ' checked="checked" ' : '';

			html.push('<label>');
			html.push('<input name="similarity" id="' + id + this._separator + 'SIMILARITY" type="radio" ' + disabled + checked + ' value="' + id + '" /> ');
			html.push(s.service_name);
			html.push('</label>');
			html.push('<br />');
		}.bind(this));
		
		html.push('</div>');
	},

	addFooter: function(html) {
		html.push('<div style="margin-top: 8px;">');
		if (this._mode == "view") {
			html.push('<a class="btn" style="width: 140px; margin-left: 330px;" onclick="popupDictionaryPanel.hidePane();">');
			html.push('<img src="'+Const.Images.Close+'" />'+Const.Label.CloseBtn+'</a> ');
		} else {
			html.push('<div style="float:left;">');
			if (this._fix != "default") {
				html.push(' <a class="btn" style="width: 140px;" onclick="popupDictionaryPanel.onLoadDefault();" id="DEFAULT_BTN">');
				html.push(Const.Popup.LoadDefault +'</a>');
			}
			html.push(' </div>');
			html.push('<div style="float:right; margin-right:8px;"> ');
			html.push('<a class="btn" onclick="popupDictionaryPanel.hidePane();">');
			html.push('<img src="'+Const.Images.Cancel+'" />'+Const.Popup.BTN_CANCEL+'</a> ');
			html.push('<a class="btn" onclick="popupDictionaryPanel.saveButtonClicked();">');
			html.push('<img src="'+Const.Images.SaveOn+'" />'+Const.Label.Save+'</a></div>');
			html.push('</div>');
		}
		html.push('<br class="clear" />');
		html.push('</div>');

		html.push('</div>');
	},
	
	onLoadDefault:function(){
		$$('#singleton-pane input').each(function(obj, index) {
			var id = obj.id;
			var token = id.split(this._separator);
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
						if (this._defaultSettings.local.indexOf(token[1]) > -1) {
							obj.checked = true;
						} else {
							obj.checked = false;
						}
						break;
						
					case "USER":
						if (this._defaultSettings.temp.indexOf(token[1]) > -1) {
							obj.checked = true;
						} else {
							obj.checked = false;
						}
						break;
						
					case "M_SRC":
						if (this._defaultSettings.morph_from == token[1]) {
							obj.checked = true;
						}
						break;
						
					case "M_TGT":
						if (this._defaultSettings.morph_to == token[1]) {
							obj.checked = true;
						}
						break;
				}
			}
		}.bind(this));
		
		this._DictFlag = 1;
	},
	
	// -
	// Action methods
	
	saveButtonClicked: function() {
		this._controller.clearSelectDictionary(this._level);
		
		var ChkCnt1 = 0;
		var ChkCnt2 = 0;
		
		$$('#singleton-pane input').each(function(obj, index) {
			var id = obj.id;
			var token = id.split(this._separator);
			if (token.length != 3) {
				alert('Error:Element-ID=' + id);
			} else {
				this._controller.onDictionaryClickHandler(this._level, token[1], obj.checked, token[2]);
				
				if (token[2] == "M_SRC" && obj.checked) {
					ChkCnt1++;
				}
				
				if (token[2] == "M_TGT" && obj.checked) {
					ChkCnt2++;
				}
			}
		}.bind(this));
		
		if (this._fix == "default") {
			this._controller.onDictionarySaveHandler();
		} else {
			if (ChkCnt1 == 0) {
				this._controller._morphemes_from[this._level] = '';
			}
			
			if (ChkCnt2 == 0) {
				this._controller._morphemes_to[this._level] = '';
			}

			this._controller.updateDictionaryFlag(this._level,this._DictFlag);
			this._controller.updateMorphologicalAnalyzer(this._level);
			
			document.fire('dom:settingEditingThis');
		}
		this.hidePane();
	},
	
	onChangeVal:function(evobj){
		var DictCount = 0;
		$$('#singleton-pane input').each(function(obj, index) {
			var id = obj.id;
			var token = id.split(this._separator);
			if (token.length != 3) {
				obj.checked = false;
			} else {
				switch(token[2]){
					case "GLOBAL":
						if (obj.checked) {
							DictCount++;
						}
						break;
						
					case "LOCAL":
						if (obj.checked) {
							DictCount++;
						}
						break;
						
					case "USER":
						break;
				}
			}
		}.bind(this));

		if (DictCount > Const.DEFINE.MaxDicts) {
			evobj.checked = false;

			var alert_msg = Const.Message.OverMaxDictCount;
			alert_msg = alert_msg.replace("%MAX", Const.DEFINE.MaxDicts);

			alert(alert_msg);
		} else {
			this._DictFlag = 2;
		}
	},
	
	onChangeTab:function(id) {
		this.hideAll();
		
		switch (id) {
			case 0:
				this.showDictionary();
				break;
				
			case 1:
				this.showMorphologicalAnalyzer();
				break;
				
			case 2:
				this.showParallelText();
				break;
				
			case 3:
				this.showSimilarity();
				break;
		}
	},
	
	hideAll: function() {
		$('dic_title').removeClassName("tab_on");
		$('dic_title').addClassName("tab_off");
		$('dict_body').hide();

		$('morph_title').removeClassName("tab_on");
		$('morph_title').addClassName("tab_off");
		$('morph_body').hide();

		$('parallel_title').removeClassName("tab_on");
		$('parallel_title').addClassName("tab_off");
		$('parallel_body').hide();

		$('similarity_title').removeClassName("tab_on");
		$('similarity_title').addClassName("tab_off");
		$('similarity_body').hide();

		if ($('DEFAULT_BTN')) {
			$('DEFAULT_BTN').hide();
		}
	},
	
	showDictionary: function() {
		$('dic_title').removeClassName("tab_off");
		$('dic_title').addClassName("tab_on");
		$('dict_body').show();
		
		if ($('DEFAULT_BTN')) {
			$('DEFAULT_BTN').show();
		}
	},
	
	showMorphologicalAnalyzer: function() {
		$('morph_title').removeClassName("tab_off");
		$('morph_title').addClassName("tab_on");
		$('morph_body').show();
	},
	
	showParallelText: function() {
		$('parallel_title').removeClassName("tab_off");
		$('parallel_title').addClassName("tab_on");
		$('parallel_body').show();
	},
	
	showSimilarity: function() {
		$('similarity_title').removeClassName("tab_off");
		$('similarity_title').addClassName("tab_on");
		$('similarity_body').show();
	}
});

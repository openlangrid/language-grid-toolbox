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

var TranslationOptionsPanel = Class.create();
var popupTranslationOptionsPanel = null;
TranslationOptionsPanel.prototype = Object.extend(new AbstractSingletonPane(), {
	_level: null,
	_fix: null,
	_defaultSettings: null,
	_controller: null,
    _mode: null,

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
    onShowPane: function() {
        this.onLoadDefault();
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
	setParams: function(level,fix,defaulsetings,controller) {
		this._level = level;
		this._fix = fix;
		this._defaultSettings = defaulsetings;
		this._controller = controller;
	},
	getPane : function() {
		var html = new Array();

		html.push('<div class="dic-subwindow-border" id="popwin">');
		html.push('<div class="dictionary-body">');

		html.push('<div class="tab_on" id="dic_title">');
		html.push('Translation Options');
		html.push('</div>');

		html.push('<div class="main_contents" id="dict_body">');

        // lite
        html.push('<div class="clearfix">');
        html.push('<label for="">');
        html.push('<input type="checkbox" id="lite">');
        html.push('<span style="margin-left:2px;">'+Const.Label.Lite+'</span>');
        html.push('</label></div>');

        // rich
        html.push('<div class="clearfix">');
        html.push('<label for="">');
        html.push('<input type="checkbox" id="rich">');
        html.push('<span style="margin-left:2px;">'+Const.Label.Rich+'</span>');
        html.push('</label></div>');
        
		html.push('</div>');
		html.push('</div>');

		html.push('<div style="margin-top: 8px;">');
		html.push('<div style="margin-top: 8px;">');
		html.push('<input type="button" value="'+Const.Label.Save+'" name="" onclick="popupTranslationOptionsPanel._onSubmit();">');
		html.push('<span style="margin-left: 20px;">');
		html.push('<a onclick="popupTranslationOptionsPanel.hidePane();"">'+Const.Popup.BTN_CANCEL+'</a>');
		html.push('</span>');
		html.push('</div>');
		html.push('<br class="clear" />');
		html.push('</div>');

		html.push('</div>');

		return html.join('');
	},
	_onSubmit: function() {
        this._defaultSettings.lite = $('lite').checked;
        this._defaultSettings.rich = $('rich').checked;
        
		this._controller.onTranslationOptionsSaveHandler();

		this.hidePane();
	},
	onLoadDefault:function(){
        if (this._defaultSettings.lite) {
            $('lite').checked = true;
        }
        if (this._defaultSettings.rich) {
            $('rich').checked = true;
        }
	},
	__dump: function() {
		alert(this._controller.source());
	}
});

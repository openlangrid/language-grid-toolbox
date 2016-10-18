//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
var PreferencesPanel = Class.create();
Object.extend(PreferencesPanel.prototype, LightPopupPanel.prototype);
Object.extend(PreferencesPanel.prototype, {

	id: "preferences",
	panelId : "preferences-panel",
	maskId : "preferences-mask",
	opacity:0.7,
	body:null,

	postedNoticePanel : null,
	tagPanelWrapper : null,

	initialize : function() {
		LightPopupPanel.prototype.initialize.apply(this, arguments);
	},

	getBody : function(){
		return this.body;
	},

	initEventListeners : function() {
		LightPopupPanel.prototype.initEventListeners.apply(this, arguments);
		this.addEventCache(this._ID_.okButton, 'click', 'onClick_OkButton');
		this.addEventCache(this._ID_.cancelButton, 'click', 'onClick_CancelButton');
	},

	setBody : function(){
		this.body="<div class='preferences-popup-container'>";
		this.postedNoticePanel = new PostedNoticePanel();
		this.postedNoticePanel.setBody();
		this.body += this.postedNoticePanel.getBody();

		if (TagConfigConst.IsShow == 'yes') {
			this.tagPanelWrapper = new TagPanelWrapper();
			this.tagPanelWrapper.setBody();
			this.body += this.tagPanelWrapper.getBody();
			Element.addClassName(this.panelId, 'with-admin-tag-manager');
		} else {
			Element.addClassName(this.panelId, 'only-notify-config');
		}

		this.body+="<p align='center'>";
		this.body+="<button class='toolbox-common-2button' id='"+this._ID_.okButton+"'>"+Const.Label.ok+"</button>";
		this.body+="<button class='toolbox-common-2button' id='"+this._ID_.cancelButton+"'>"+Const.Label.cancel+"</button>";
		this.body+="</p>";
		this.body+="<div class='spaceButtom'></div>";
		this.body+='</div>';

	},

	onShowPanel : function() {
		this.postedNoticePanel.onShowPanel();
		if (this.tagPanelWrapper) {
			this.tagPanelWrapper.onShowPanel();
		}
	},

	onClick_OkButton : function(event) {
		this.postedNoticePanel.onClick_OkButton(event, {onSuccess:this.hide.bind(this)});
	},

	onClick_CancelButton : function(event) {
		this.hide();
	},

	_ID_ : {
		okButton : 'preferences-ok',
		cancelButton : 'preferences-cancel'
	}
});
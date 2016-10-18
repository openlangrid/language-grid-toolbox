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
var PostedNotice = Class.create();
PostedNotice.prototype = {

	initialize : function() {
		this.lightpop = new PostedNoticePopUp();
		$('posted-notice-config-button').observe('click', this.showPopup.bindAsEventListener(this));
	},

	showPopup : function() {
		this.lightpop.setBody();
		this.lightpop.show();
	},

	hidePop : function(){
		this.lightpop.hide();
	}
};

var PostedNoticePopUp = Class.create();
Object.extend(PostedNoticePopUp.prototype, LightPopupPanel.prototype);
Object.extend(PostedNoticePopUp.prototype, {
	id: "posted_notice",
	panelId : "posted_notice-panel",
	maskId : "posted_notice-mask",
	opacity:0.7,
	body:null,
	getBody : function(){
		return this.body;
	},

	initEventListeners : function() {
		LightPopupPanel.prototype.initEventListeners.apply(this, arguments);
		this.addEventCache(this._ID_.okButton, 'click', 'onClick_OkButton');
		this.addEventCache(this._ID_.cancelButton, 'click', 'onClick_CancelButton');
	},

	setBody : function(){
//		this.body="<div class='langrid-popup-container'><div class='fileListTableTitle'>"+PostedNoticeConst.Label.popTitle+"</div>";
		this.body="<div class='langrid-popup-container'><h3>"+PostedNoticeConst.Label.popTitle+"</h3>";
		this.body+="<div class='container'>";
		this.body+="<h4>"+PostedNoticeConst.Label.interval+"</h4>";
		this.body+="<table><tbody id='tbodyUpload'><tr>";
//		this.body+="<td><span class=''>"+PostedNoticeConst.Label.interval+"</span></td>";
		this.body+="<td>"+new Template(PostedNoticeConst.Templates.intervalSelector).evaluate({ID:this._ID_.intervalSelector})+"</td>";
		this.body+="<td><span class='label'>"+PostedNoticeConst.Label.language+"</span></td>";
		this.body+="<td>"+new Template(PostedNoticeConst.Templates.languageSelector).evaluate({ID:this._ID_.languageSelector})+"</td>";
		this.body+="</tr></tbody></table></div>";

		this.body+="<p align='center'>";
		this.body+="<button class='toolbox-common-2button' id='"+this._ID_.okButton+"'>"+Const.Label.ok+"</button>";
		this.body+="<button class='toolbox-common-2button' id='"+this._ID_.cancelButton+"'>"+Const.Label.cancel+"</button>";
		this.body+="</p>";
		this.body+="<div class='spaceButtom'></div>";
		this.body+='</div>';

	},

	onShowPanel : function() {
		$(this._ID_.languageSelector).value = PostedNoticeConst.Config.language;
		$(this._ID_.intervalSelector).value = PostedNoticeConst.Config.type;
	},

	onClick_OkButton : function(event) {
		this.send();
		PostedNoticeConst.Config.language = $F(this._ID_.languageSelector);
		PostedNoticeConst.Config.type = $F(this._ID_.intervalSelector);
	},

	onClick_CancelButton : function(event) {
		this.hide();
	},

	getParameters : function() {
		return {
			language : $F(this._ID_.languageSelector),
			interval : $F(this._ID_.intervalSelector),
			op : 'save'
		};
	},

	send: function() {
		new Ajax.Request('./?page=ajax_posted_notice', {
			asynchronous: false,
			postBody: Object.toQueryString(this.getParameters()),
			onSuccess: this.onSuccess.bind(this),
			onException: this.onException.bind(this),
			onFailure: this.onFailure.bind(this),
			onComplete: this.onComplete.bind(this)
		});
	},

	onSuccess: function(transport) {
		var response = transport.responseText.evalJSON();

		if (response.status.toUpperCase() == 'ERROR') {
			throw new Error(response.message);
		}

		this.hide();
	},

	onException: function(t, e) {
		alert(e.message);
	},

	onFailure: function(e) {
	},

	onComplete: function() {
	},

	_ID_ : {
		languageSelector : 'posted-notice-language',
		intervalSelector : 'posted-notice-interval',
		okButton : 'posted-notice-ok',
		cancelButton : 'posted-notice-cancel'
	}
});

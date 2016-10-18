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
var PostedNoticePanel = Class.create();
Object.extend(PostedNoticePanel.prototype, {

	body:null,

	getBody : function(){
		return this.body;
	},

	setBody : function(){
		this.body = '';
		this.body+="<div id='posted_notice-panel'>";
		this.body+="<h4>"+PostedNoticeConst.Label.interval+"</h4>";
		this.body+="<table><tbody id='tbodyUpload'><tr>";
		this.body+="<td>"+new Template(PostedNoticeConst.Templates.intervalSelector).evaluate({ID:this._ID_.intervalSelector})+"</td>";
		this.body+="<td><span class='label'>"+PostedNoticeConst.Label.language+"</span></td>";
		this.body+="<td>"+new Template(PostedNoticeConst.Templates.languageSelector).evaluate({ID:this._ID_.languageSelector})+"</td>";
		this.body+="</tr></tbody></table></div>";
	},

	onShowPanel : function() {
		$(this._ID_.languageSelector).value = PostedNoticeConst.Config.language;
		$(this._ID_.intervalSelector).value = PostedNoticeConst.Config.type;
	},

	onClick_OkButton : function(event, callbackHandler) {
		this.callbackHandler = callbackHandler;
		this.send();
		PostedNoticeConst.Config.language = $F(this._ID_.languageSelector);
		PostedNoticeConst.Config.type = $F(this._ID_.intervalSelector);
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

//		this.hide();
		this.callbackHandler.onSuccess();
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

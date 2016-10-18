//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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

var displayManager = Class.create({
	initialize: function() {

	},
	display: function(htmlText) {
		this.htmlText = htmlText;
		this.subWinObj = window.open();
		this._upload();
	},
	openWindow: function(key) {
//		var subWin = window.open('./php/ajax/display-web-page.php?display_key=' + key);
		this.subWinObj.location.href = './php/ajax/display-web-page.php?display_key=' + key;
	},
	_upload: function() {
		postObj = {
			contents: this.htmlText
		};
		var hash = $H(postObj).toQueryString();
		new Ajax.Request(
			'./php/ajax/display-web-page.php'
			, {
				method : 'post'
				, parameters : hash
				, onSuccess : function(response){
					try {
						var result = response.responseText.evalJSON();
						if (result.status == "SESSIONTIMEOUT") {
							alert(Const.Message.Error.sessionTimeout);
							return;
						}
						if (result.status == "SAVEERROR") {
							alert(Const.Message.Error.displaySaveError);
							return;
						}
						this.openWindow(result.display_key);
					} catch(e) {
						alert(Const.Message.Error.ServerError);
					}
				}.bind(this)
				, onFailure : function(e) {
					alert(Const.Message.Error.ServerError);
				}.bind(this)
			}
		);
	}
});

//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This preserves contents
// entered in forms.
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
var InfoManager = Class.create();

InfoManager.prototype = {
	initialize: function(Identifier) {
		//this.target_path = path+'infoMainte/php/ajax/';
		this.GetIdentifier = Identifier;

		//this.moduleId = $F("moduleId");
		//this.screenId = $F("screenId");

		this.result = {};
	},

	saveItems: function(moduleId,screenId,items) {
		var postObj = {};
		postObj['moduleId'] = moduleId;
		postObj['screenId'] = screenId;
		postObj['items'] = items;

		var hash = $H(postObj).toQueryString();
		//var Target_url = this.target_path+"saveScreenInfo.php";
		var Target_url = "./?"+this.GetIdentifier+"=saveScreenInfo";
		new Ajax.Request(Target_url,
			{
				method: "post",
				parameters: hash,
				onSuccess: function(response) {},
				onComplete: function(response) {},
				onFailure: function() {},
				onException: function (response, exception) {}
			}
		);
	},

	loadItems: function(moduleId,screenId) {
		var postObj = {};
		postObj['moduleId'] = moduleId;
		postObj['screenId'] = screenId;
		var hash = $H(postObj).toQueryString();

		//var Target_url = this.target_path+"loadScreenInfo.php";
		var Target_url = "./?"+this.GetIdentifier+"=loadScreenInfo";
		var ret = {};
		new Ajax.Request(Target_url,
			{
				method: "post",
				postBody: hash,
				asynchronous:false,
				onSuccess: function(request) {
					try {
						var responseJSON = request.responseText.evalJSON()
						if (responseJSON.status == 'OK') {
							ret = $H(responseJSON.items);
						}else{
							return false;
						}
					}catch(err){
						return false;
					}
				},
				onFailure: function() {},
				onException: function (request, exception) {}
			}
		);
		return ret;
	},

	clearItems: function(moduleId,screenId) {
		var postObj = {};
		postObj['moduleId'] = moduleId;
		postObj['screenId'] = screenId;
		var hash = $H(postObj).toQueryString();

		//var Target_url = this.target_path+"clearScreenInfo.php";
		var Target_url = "./?"+this.GetIdentifier+"=clearScreenInfo";
		new Ajax.Request(Target_url,
			{
				method: "post",
				postBody: hash,
				onSuccess: function(response) {},
				onComplete: function(response) {},
				onFailure: function() {},
				onException: function (response, exception) {}
			}
		);
	}
}

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

var DisplayCacheAction = Class.create(AbstractAction, {
	
	erorMessages: null,
	
	/**
	 * 
	 */
	initialize: function() {
		this.erorMessages = [];
	},
	
	getContents: function() {
		return '';
	},
	
	getParameters: function() {
		var parameters = {
			contents: this.getContents()
		};
		
		return parameters;
	},
	
	valid: function() {
		this.erorMessages = [];
		
		var parameters = this.getParameters();
		
		if (!parameters.contents) {
			this.erorMessages.push('Error');
		}
		
		return (this.erorMessages.length == 0);
	},
	
	execute: function() {
		Logger.info('DisplayCacheAction.execute');

		this.subWindow = window.open();

		this.subWindow.document.open();
//		this.subWindow.document.write(Resource.Image.NOW_LOADING);
		this.subWindow.document.write(Resource.NOW_LOADING);
		this.subWindow.document.close();
		
		this.send();
	},
	
	send: function() {
		new Ajax.Request(
			Resource.Url.SAVE_CACHE
			, {
				postBody : Object.toQueryString(this.getParameters())
				, onSuccess : this.onSucess.bind(this)
				, onFailure : this.onFailure.bind(this)
				, onException : this.onException.bind(this)
			}
		);
	},
	
	onSucess: function(transport) {
		var response = transport.responseText.evalJSON();
		
		if (response.status.toUpperCase() == 'ERROR') {
			throw new Error(response.message);
		}
		
		this.subWindow.location.href = response.contents;
	},
	
	onException: function(t, e) {
		Logger.error(e);
	},
	
	onFailure: function() {
		Logger.error(e);
	},
	
	onComplete: function() {
		Logger.info('DisplayCacheAction.onComplete');
	}
});

var SourceDisplayAction = Class.create(DisplayCacheAction, {
	getContents: function() {
		return Model.Translation.getSourceAsString();
	}
});

var TargetDisplayAction = Class.create(DisplayCacheAction, {
	getContents: function() {
		return Model.Translation.getTargetAsString();
	}
});
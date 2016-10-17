//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
/**
 * @author kitajima
 * @require Thread
 * @require TransportWrapper
 */
var Translator = Class.create();
Object.extend(Translator.prototype, Thread.prototype);
Object.extend(Translator.prototype, {
	_AJAX_PATH : './?page=ajax_preview',
	_request : null,

	/**
	 * Constructor
	 */
	initialize : function(request) {
		this._request = request;
	},
	run : function() {
		this.translate();
	},
	translate : function() {
		this.doTranslate();
	},
	doTranslate : function() {
		new Ajax.Request(this._AJAX_PATH, {
			postBody : this._getPostBody(),
			onSuccess : function(transport) {
				this.onSuccess(new TransportWrapper(transport));
			}.bind(this),
			onFailure : function(transport) {
			},
			onException : function(request, e) {
			}
		});
	},
	_getParameters : function() {
		return {
			sourceLanguageCode : this.getRequest().getSourceLanguageCode(),
			targetLanguageCode : this.getRequest().getTargetLanguageCode(),
			sourceText : this.getRequest().getSourceText()
		};
	},
	_getPostBody : function() {
		return $H(this._getParameters()).toQueryString();
	},
	onSuccess : function(transportWrapper) {

	},

	/**
	 * getter/setter
	 */
	getRequest : function() {
		return this._request;
	},
	setRequest : function(request) {
		this._request = request;
		return this;
	}
});
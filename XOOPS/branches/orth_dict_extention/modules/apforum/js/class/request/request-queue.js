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
 */
var RequestQueue = Class.create();
RequestQueue.prototype = {
	_requests : new Array(),

	/**
	 * Constructor
	 */
	initialize : function() {
		this.init();
	},
	init : function() {
		this._requests = new Array();
	},
	hasRequest : function(threadId) {
		return this._requests[threadId] && !!this._requests[threadId].length;
	},

	/**
	 * TODO
	 */
	size : function(threadId) {
		if (!threadId) {
			return this._requests.length;
		}
		return this._requests[threadId].length;
	},
	getRequest : function(threadId) {
		if (!this.hasRequest(threadId)) {
			return null;
		}
		return this._requests[threadId].shift();
	},
	putRequest : function(request) {
		if (!this.hasRequest(request.getThreadId())) {
			this._requests[request.getThreadId()] = new Array();
		}
		this._requests[request.getThreadId()].push(request);
	}
};
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

var AbstractAction = Class.create();
AbstractAction.prototype = {
		
	id: null,
	_parent: null,
		
	/**
	 * @param String id
	 * Constructor
	 */
	initialize: function() {
	},
	
	isEnabled: function() {
		return this._parent.isEnabled();
	},
	
	setEnabled: function(flag) {
		return this._parent.setEnabled(flag);
	},
	
	/**
	 * 
	 */
	execute: function(event) {
	}
};

var NullAction = Class.create(AbstractAction, {
	initialize: function() {},
	isEnabled: function() {},
	setEnabled: function(flag) {},
	execute: function(event) {}
});
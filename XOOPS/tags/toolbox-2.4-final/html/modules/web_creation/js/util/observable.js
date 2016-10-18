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

var Observable = Class.create();
Observable.prototype = {
		
	observers: null,
	changed: false,
		
	/**
	 * 
	 */
	initialize: function() {
		this.observers = [];
	},
	
	/**
	 * 
	 */
	addObserver: function(o) {
		this.observers.push(o);
	},
	
	/**
	 * 
	 */
	clearChanged: function() {
		this.changed = false;
	},
	
	/**
	 * 
	 */
	countObservers: function() {
		this.observers.length;
	},
	
	/**
	 * 
	 */
	deleteObserver: function(o) {
		this.observers.each(function(myO, i) {
			
			if (myO != o) {
				return;
			}
			
			this.observers.splice(i, 1);
			throw $break;
		}.bind(this));
	},
	
	/**
	 * 
	 */
	deleteObservers: function() {
		this.observers = [];
	},
	
	/**
	 * 
	 */
	hasChanged: function() {
		return this.changed;
	},
	
	/**
	 * 
	 */
	notifyObservers: function() {
		if (!this.hasChanged()) {
			return;
		}

		var args = $A(arguments).clone();
		args.unshift(this);
		this.observers.each(function(o) {
			o.update.apply(o, args);
		}.bind(this));

		this.clearChanged();
	},
	
	/**
	 * 
	 */
	setChanged: function() {
		this.changed = true;
	}
		
};
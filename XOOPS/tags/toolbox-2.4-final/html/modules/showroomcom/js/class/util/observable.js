//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
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
var Observable = Class.create();
Observable.prototype = {
	observers : null,
	
	// Observable.prototype.initialize.apply(this, arguments);
	initialize : function() {
		this.observers = new Array();
	},
	
	addObserver : function(observer) {
		this.observers.push(observer);
	},

	deleteObserver : function(observer) {
		for (var i = 0, length = this.observers.length; i < length; i++) {
			if (o == observer) {
				this.observers.splice(i, 1);
				break;
			}
		}
	},
	
	notifyObservers : function() {
		this.observers.each(function(observer){
			observer.update(this);
		}.bind(this));
	}
};
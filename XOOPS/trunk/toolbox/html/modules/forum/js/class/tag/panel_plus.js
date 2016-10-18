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
/**
 * @author kitajima
 */
var PanelPlus = Class.create();
PanelPlus.prototype = {

	// panel id
	id : null,

	// Events added to this parameters can start/stop
	// what a cool!
	events : null,

	/**
	 * @param Object object
	 * @return void
	 */
	initialize : function() {
		this.events = new Hash();
	},

	/**
	 * 
	 * @param String eventName
	 * @param function eventFunction
	 * @param Element element
	 * @param String eventType
	 * @return void
	 */
	addEvent : function(eventName, element, eventType, eventFunction, useCapture) {
		this.events.set(eventName, {
			element : element,
			fn : eventFunction,
			type : eventType,
			useCapture : !!useCapture 
		});
	},
	
	/**
	 * @return void
	 */
	startEventObserving : function() {
		this.events.each(function(event){
			try {
				Event.observe(event.value.element, event.value.type, event.value.fn, event.value.useCapture);
			} catch (e) {
				;
			}
		}.bind(this));
	},

	/**
	 * @return void
	 */
	stopEventObserving : function() {
		this.events.each(function(event){
			try {
				Event.stopObserving(event.value.element, event.value.type, event.value.fn, event.value.useCapture);
			} catch (e) {
				;
			}
		}.bind(this));
	},

	/**
	 * @return Element panel's element
	 */
	getElement : function() {
		return $(this.id);
	},
	
	/**
	 * @return void
	 */
	draw : function() {
		return;
	},
	
	/**
	 * 
	 * @param {Object} html
	 */
	update : function(html) {
		$(this.id).update(html);
	},
	
	/**
	 * 
	 */
	show : function() {
		$(this.id).show();
	},
	
	/**
	 * 
	 */
	hide : function() {
		$(this.id).hide();
	}
};
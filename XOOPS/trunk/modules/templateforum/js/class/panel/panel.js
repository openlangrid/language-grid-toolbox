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
var Panel = Class.create();
Panel.prototype = {
	
	id : null,
	element : null,
	
	eventCaches : null,
	
	initialize : function(object) {
		for (var key in object) {
			this[key] = object[key];
		}
		this.element = $(this.id) || null;
		this.eventCaches = new Hash();
		this.init();
	},
	init : function() {
		;
	},
	initEventListeners : function() {
		;
	},
	addEventCache : function(element, eventType, eventName) {
		this.eventCaches.set(eventName, {
			element : element,
			bind : this[eventName].bindAsEventListener(this),
			type : eventType
		});
	},
	startEventObserving : function() {
		this.eventCaches.each(function(eventCache){
			try {
				Event.observe(eventCache.value.element, eventCache.value.type, eventCache.value.bind);
			} catch (e) {
			}
		}.bind(this));
	},
	
	stopEventObserving : function() {
		this.eventCaches.each(function(eventCache){
			try {
				Event.stopObserving(eventCache.value.element, eventCache.value.type, eventCache.value.bind);
			} catch (e) {
			}
		}.bind(this));
	},
	
	/**
	 * 常に新しいエレメントを返す
	 * 同じIDでもエレメントの参照が古くなると正常に作動しない　
	 */
	getElement : function() {
		this.element = $(this.id) || null;
		return this.element;
	}
};
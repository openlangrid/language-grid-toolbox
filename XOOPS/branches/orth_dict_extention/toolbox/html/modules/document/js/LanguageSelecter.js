//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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
var LanguageSelecter = Class.create( {
	element : null,

	initialize : function(elementId) {
		this.element = $(elementId);
	},

	getInputedValue : function(){
		return this.element.value;
	},

	getSelectedLangName : function(){
		return this.element.options[this.element.selectedIndex].text;
	},

	getSelectedLangCode : function(){
		if(this.element.options.length > 0){
			return this.element.options[this.element.selectedIndex].value;
		}
	},

	updateSelecter : function(content){
		Element.update(this.element,content);
	},

	setEvent : function(eventName, callback){
		Event.observe(this.element,eventName, callback);
	}
});
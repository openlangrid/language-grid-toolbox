//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

var HtmlSelector = Class.create({
	
	id: null,
	
	contents: null,
	action: null,
	
	initialize : function(id, action) {
		this.id = id;
		this.action = action || new NullAction();
		this.action._parent = this;
		
		this.initEventListeners();
	},
	
	initEventListeners: function() {
		$(this.id).observe('change', this.onChange.bind(this));
	},
	
	onChange: function(event) {
		this.action.execute(event);
	},
	
	/**
	 * @param bool disabled
	 * @return void
	 */
	setEnabled: function(flag) {
		$(this.id).disabled = !flag;
	},
	
	/**
	 * @return bool
	 */
	isEnabled: function() {
		return !$(this.id).disabled;
	},
	
	clear: function() {
		$(this.id).update('');
	},
	
	update: function() {
		this.clear();
		$H(this.contents).each(function(pair) {
			var option = document.createElement('option');
			option.value = pair.key;
			option.appendChild(document.createTextNode(pair.value));
			$(this.id).appendChild(option);
		}.bind(this));
	},
	
	setContents: function(contents) {
		this.contents = contents;
		this.update();
	},

	getValue : function(){
		return $F(this.id);
	},
	
	setValue: function(value) {
		$(this.id).childElements().each(function(elem){
			elem.selected = (elem.value == value);
		});
	},

	getText : function(){
		return $(this.id).options[$(this.id).selectedIndex].text;
	}
});
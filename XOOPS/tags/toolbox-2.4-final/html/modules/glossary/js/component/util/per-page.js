//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// glossaries.
// Copyright (C) 2010  CITY OF KYOTO
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
var PerPage = Class.create();
PerPage.prototype = {
		
	perPage : null,
	currentPerPage : 10,
	
	prefix : 'item-',
	className : 'item-class',
	
	MAX_VALUE : 19880316,

	initialize : function() {
		this.perPages = [
		    {view : 5, value : 5},
		    {view : 10, value : 10},
		    {view : 20, value : 20},
		    {view : 50, value : 50},
		    {view :Global.Text.ALL, value : this.MAX_VALUE}
		];
	},

	createHtml : function() {

		var html = [];
		html.push(Global.Text.ITEMS + ' ');
		this.perPages.each(function(perPage, i) {
			var assign = {
				id : this.prefix + perPage.value,
				className :Global.ClassName.CLICKABLE_TEXT + ' ' + this.className,
				perPage : perPage.view
			};
			if (this.currentPerPage == perPage.value) {
				assign.className =Global.ClassName.DISABLE_TEXT;
			}
			html.push(new Template(this.Templates.item).evaluate(assign));
			html.push(this.Templates.separator);
		}.bind(this));
		html.pop();

		return html.join('');
	},
	
	getPerPageById : function(pagerId) {
		return pagerId.replace(this.prefix, '');
	}
};
PerPage.prototype.Templates = {
	item : '<span id="#{id}" class="#{className}">#{perPage}</span>',
	separator : ' | '
};
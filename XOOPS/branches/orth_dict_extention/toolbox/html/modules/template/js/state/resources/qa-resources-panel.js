//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
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
var QaResourcesPanel = Class.create();
Object.extend(QaResourcesPanel.prototype, Panel.prototype);
Object.extend(QaResourcesPanel.prototype, {
	
	resources : null,
	
	headerPanel : null,
	bodyPanel : null,

	initialize : function() {
		Panel.prototype.initialize.apply(this, arguments);
		this.headerPanel = new QaResourcesHeaderPanel();
		this.bodyPanel = new QaResourcesBodyPanel();
		this.initEventListeners();
	},

	initEventListeners : function() {
		Event.observe(document, 'menuPopup:hide', function(event){
			this.bodyPanel.hidePopup();
		}.bindAsEventListener(this));
	},

	draw : function() {
		this.headerPanel.draw();
		this.bodyPanel.load();
	},
	hide : function() {
		Panel.prototype.hide.apply(this, arguments);
		this.bodyPanel.hide();
	}
});
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
var PopupPanel = Class.create();
Object.extend(PopupPanel.prototype, Panel.prototype);
Object.extend(PopupPanel.prototype, {

	id : 'popup-panel-wrapper',
	WIDTH : '410',

	panelId : 'popup-panel',
	panel : null,
	
	initialize : function() {
		Panel.prototype.initialize.apply(this, arguments);
		this.init();
	},

	init : function() {
		if (!this.element) {
			new Insertion.Bottom($$('body')[0], new Template('<div id="#{id}"><div id="#{panelId}"></div></div>').evaluate({
				id : this.id,
				panelId : this.panelId
			}));
			this.element = $(this.id);
		}

		this.panel = $(this.panelId);
		this.initEventListeners();
		this.hide();
	},

	show : function(x, y) {
		this.panel.update(this.getBody());
		this.element.show();
		this.setupPanel(x, y);
		this.startEventObserving();
		this.onShowPanel();
	},

	onShowPanel : function() {

	},

	hide : function() {
		this.stopEventObserving();
		this.element.hide();
		this.onHidePanel();
	},

	onHidePanel : function() {

	},
	
	setupPanel : function(x, y) {
		this.panel.setStyle({
			border : '2px solid #0667B4'
			, padding : '4px'
			, zIndex : 10
			, position : 'absolute'
			, width : this.WIDTH + 'px'
			, background : '#f5f5f5'
			, left : x + 'px'
			,top : y + 'px'
		});
	},

	getBody : function() {
		return '';
	}
});
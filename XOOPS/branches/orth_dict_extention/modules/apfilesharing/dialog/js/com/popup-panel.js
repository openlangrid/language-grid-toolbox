//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user
// to open or save files on the File Sharing function.
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
/* $Id: popup-panel.js 4583 2010-10-13 09:53:46Z yoshimura $ */

var PopupPanel = Class.create();
Object.extend(PopupPanel.prototype, Panel.prototype);
Object.extend(PopupPanel.prototype, {

	WIDTH: '200',

	initialize: function(id) {
		Panel.prototype.initialize.apply(this, arguments);
		this.id = id;
	},

	fetch: function() {
		alert(this.id);
		this._setStyle();
		this.getElement().innerHTML = this.getBody();
	},

	show: function(x, y) {
		this.getElement().setAttribute('top', y+'px');
		this.getElement().setAttribute('left', x+'px');
		Panel.prototype.show.apply(this, arguments);
	},

	_setStyle: function() {
		this.getElement().setStyle({
			position: 'absolute',
			zIndex: 100,
			width: this.WIDTH+'px',
			backgroundColor: '#f5f5f5'
		});
	},

	getBody: function() {
		return '';
	}
});
